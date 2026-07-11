const mongoose = require('mongoose');
const { queueAgentRun, processAgentRun } = require('@/services/agents/runtime');
const { processDueAgentJobs } = require('@/services/agents/worker');
const { requestModel } = require('@/services/agents/llmGateway');
const { agentTemplates, getAgentTemplate } = require('@/services/agents/templates');
const { toolDefinitions } = require('@/services/agents/toolRegistry');
const { canAccessAction } = require('@/services/platform/accessControl');
const { createProviderRegistryForTenant } = require('@/services/integrations/providerRegistry');

const scope = (req) => ({ tenant: req.tenantId, removed: false });
const json = (res, result, message) => res.status(200).json({ success: true, result, message });
const allowed = (req, res, permission) => canAccessAction(req.admin || {}, permission) || (res.status(403).json({ success: false, result: null, message: `Missing permission: ${permission}` }), false);

const templates = async (_req, res) => json(res, agentTemplates, 'Agent templates loaded');
const tools = async (_req, res) => json(res, toolDefinitions, 'Agent tools loaded');

const createFromTemplate = async (req, res) => {
  const template = getAgentTemplate(req.body.templateKey) || {};
  const agent = await mongoose.model('AgentDefinition').create({
    tenant: req.tenantId, createdBy: req.admin?._id, assignedTo: req.admin?._id,
    name: req.body.name || template.name || 'Custom AI Agent',
    description: req.body.description || template.description,
    specialistType: req.body.specialistType || template.key || 'custom',
    instructions: req.body.instructions || template.instructions || 'Assist the user while respecting permissions and approval policies.',
    tools: req.body.tools || template.tools || [], modelPolicy: { ...template.modelPolicy, ...req.body.modelPolicy },
    knowledgeSources: req.body.knowledgeSources || [], approvalPolicy: req.body.approvalPolicy,
    schedule: req.body.schedule, limits: req.body.limits,
  });
  return json(res, agent, 'Agent created');
};

const publish = async (req, res) => {
  if (!allowed(req, res, 'ai.agent.publish')) return;
  const Agent = mongoose.model('AgentDefinition');
  const agent = await Agent.findOne({ _id: req.params.id, ...scope(req) });
  if (!agent) return res.status(404).json({ success: false, message: 'Agent not found' });
  agent.version += 1; agent.status = 'published'; agent.publishedAt = new Date(); await agent.save();
  await mongoose.model('AgentVersion').create({ tenant: req.tenantId, createdBy: req.admin?._id, agent: agent._id, version: agent.version, snapshot: agent.toObject(), publishedBy: req.admin?._id });
  return json(res, agent, 'Agent published');
};

const version = async (req, res) => json(res, await mongoose.model('AgentVersion').find({ agent: req.params.id, ...scope(req) }).sort({ version: -1 }).lean(), 'Agent versions loaded');

const startRun = (testMode) => async (req, res) => {
  if (!allowed(req, res, 'ai.agent.run')) return;
  const agent = await mongoose.model('AgentDefinition').findOne({ _id: req.params.id, ...scope(req) });
  if (!agent) return res.status(404).json({ success: false, message: 'Agent not found' });
  const run = await queueAgentRun({ agent, actor: req.admin, tenant: req.tenantId, input: req.body || {}, trigger: testMode ? 'test' : 'manual', testMode });
  if (testMode || req.body?.runNow) {
    const providers = await createProviderRegistryForTenant({ tenantId: req.tenantId });
    await processAgentRun({ runId: run._id, actor: req.admin, providers });
  }
  return json(res, await mongoose.model('AgentRun').findById(run._id), testMode ? 'Test run completed' : 'Agent run queued');
};

const runDetail = async (req, res) => {
  const run = await mongoose.model('AgentRun').findOne({ _id: req.params.id, ...scope(req) }).lean();
  if (!run) return res.status(404).json({ success: false, message: 'Agent run not found' });
  const [steps, approvals] = await Promise.all([
    mongoose.model('AgentRunStep').find({ run: run._id, ...scope(req) }).sort({ sequence: 1 }).lean(),
    mongoose.model('AgentApproval').find({ run: run._id, ...scope(req) }).sort({ created: -1 }).lean(),
  ]);
  return json(res, { ...run, steps, approvals }, 'Agent run loaded');
};

const runs = async (req, res) => json(res, await mongoose.model('AgentRun').find(scope(req)).sort({ created: -1 }).limit(Number(req.query.limit || 50)).lean(), 'Agent runs loaded');

const cancel = async (req, res) => {
  if (!allowed(req, res, 'ai.agent.run')) return;
  const run = await mongoose.model('AgentRun').findOneAndUpdate({ _id: req.params.id, ...scope(req), status: { $in: ['queued', 'running', 'needs_approval'] } }, { status: 'cancelled', completedAt: new Date() }, { new: true });
  await mongoose.model('Job').updateMany({ tenant: req.tenantId, 'payload.run': req.params.id, status: { $in: ['queued', 'running', 'waiting_approval'] } }, { status: 'cancelled' });
  return json(res, run, 'Agent run cancelled');
};

const decideApproval = async (req, res) => {
  if (!allowed(req, res, 'ai.approval.decide')) return;
  const approval = await mongoose.model('AgentApproval').findOne({ _id: req.params.id, ...scope(req), status: 'pending' });
  if (!approval) return res.status(404).json({ success: false, message: 'Pending approval not found' });
  approval.status = req.body.decision === 'approved' ? 'approved' : 'rejected'; approval.approver = req.admin?._id; approval.decidedAt = new Date(); approval.reason = req.body.reason; await approval.save();
  if (approval.status === 'approved') {
    await mongoose.model('AgentRunStep').findByIdAndUpdate(approval.step, { status: 'skipped', output: { approved: true, note: 'Approved action queued for execution.' } });
    const run = await mongoose.model('AgentRun').findById(approval.run);
    const approvedTools = new Set(run.input?._approvedTools || []); approvedTools.add(approval.tool);
    run.input = { ...(run.input || {}), _approvedTools: [...approvedTools] }; run.status = 'queued'; await run.save();
    await mongoose.model('Job').create({ tenant: req.tenantId, createdBy: req.admin?._id, type: 'agent.resume', payload: { run: approval.run, actor: req.admin?._id }, status: 'queued' });
  } else {
    await mongoose.model('AgentRun').findByIdAndUpdate(approval.run, { status: 'cancelled', completedAt: new Date() });
  }
  return json(res, approval, `Approval ${approval.status}`);
};

const approvals = async (req, res) => json(res, await mongoose.model('AgentApproval').find(scope(req)).sort({ created: -1 }).limit(50).lean(), 'Approvals loaded');

const assistant = async (req, res) => {
  const providers = await createProviderRegistryForTenant({ tenantId: req.tenantId });
  const result = await requestModel({ policy: req.body.modelPolicy || { provider: 'openai', fallbackProviders: ['kimi'] }, action: 'assistant', instruction: 'You are the in-house AI Agent Studio assistant. Recommend a safe agent purpose, instructions, tools, knowledge, and guardrails. Never publish or widen permissions.', prompt: req.body.message || JSON.stringify(req.body.context || {}), providers });
  return json(res, { message: result.content, provider: result.provider, usage: result.usage }, 'Assistant response generated');
};

const runDue = async (req, res) => json(res, await processDueAgentJobs({ limit: Number(req.body?.limit || 5) }), 'Due agent jobs processed');

module.exports = { templates, tools, createFromTemplate, publish, version, test: startRun(true), run: startRun(false), runDetail, runs, cancel, decideApproval, approvals, assistant, runDue };
