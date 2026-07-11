const mongoose = require('mongoose');
const { createProviderRegistry, createProviderRegistryForTenant } = require('@/services/integrations/providerRegistry');
const { requestModel } = require('./llmGateway');
const { calculateCost } = require('./costService');
const { assertToolAccess, requiresApproval, executeTool } = require('./toolRegistry');

const createStep = (payload) => mongoose.model('AgentRunStep').create(payload);

const queueAgentRun = async ({ agent, actor, tenant, input = {}, trigger = 'manual', testMode = false }) => {
  const run = await mongoose.model('AgentRun').create({ tenant, createdBy: actor?._id, assignedTo: actor?._id, agent: agent._id, agentVersion: agent.version, trigger, input, testMode, status: 'queued' });
  await mongoose.model('Job').create({ tenant, createdBy: actor?._id, type: 'agent.run', payload: { run: run._id, actor: actor?._id }, status: 'queued' });
  return run;
};

const processAgentRun = async ({ runId, actor, providers }) => {
  const Run = mongoose.model('AgentRun');
  const run = await Run.findById(runId);
  if (!run || ['cancelled', 'succeeded'].includes(run.status)) return run;
  const resolvedProviders =
    providers || (await createProviderRegistryForTenant({ tenantId: run.tenant })) || createProviderRegistry(process.env);
  const agent = await mongoose.model('AgentDefinition').findOne({ _id: run.agent, tenant: run.tenant, removed: false });
  if (!agent) throw new Error('Agent definition not found');
  run.status = 'running'; run.startedAt ||= new Date(); await run.save();

  const tools = (agent.tools || []).slice(0, agent.modelPolicy?.maxSteps || 8);
  const runActor = actor || (run.createdBy ? await mongoose.model('Admin').findById(run.createdBy).populate('roleRefs') : null);
  for (let index = run.currentStep || 0; index < tools.length; index += 1) {
    const tool = tools[index];
    const definition = assertToolAccess({ tool, actor: runActor || {} });
    const step = await createStep({ tenant: run.tenant, createdBy: run.createdBy, run: run._id, sequence: index + 1, kind: 'tool', tool, input: run.input, status: 'running' });
    const approvedTools = new Set(run.input?._approvedTools || []);
    if (requiresApproval({ definition, agent, testMode: run.testMode }) && !approvedTools.has(tool)) {
      step.status = 'needs_approval'; await step.save();
      await mongoose.model('AgentApproval').create({ tenant: run.tenant, createdBy: run.createdBy, run: run._id, step: step._id, tool, riskLevel: definition.riskLevel, payloadPreview: run.input, expiresAt: new Date(Date.now() + 86400000) });
      run.status = 'needs_approval'; run.currentStep = index; await run.save();
      return run;
    }
    if (approvedTools.has(tool)) {
      run.input = { ...run.input, _approvedTools: [...approvedTools].filter((key) => key !== tool) };
    }
    try {
      const started = Date.now();
      step.output = await executeTool({ tool, input: run.input?.toolInputs?.[tool] || run.input, tenant: run.tenant, actor: runActor, testMode: run.testMode, providers: resolvedProviders });
      step.durationMs = Date.now() - started; step.status = 'succeeded'; await step.save();
      run.currentStep = index + 1; await run.save();
    } catch (error) {
      step.status = 'failed'; step.error = error.message; await step.save(); throw error;
    }
  }

  const result = await requestModel({ policy: agent.modelPolicy, action: 'agentRun', instruction: agent.instructions, prompt: JSON.stringify(run.input), providers: resolvedProviders });
  const cost = calculateCost({ usage: result.usage, provider: result.provider });
  await createStep({ tenant: run.tenant, createdBy: run.createdBy, run: run._id, sequence: tools.length + 1, kind: 'output', output: { content: result.content, provider: result.provider }, status: 'succeeded' });
  run.output = { content: result.content, provider: result.provider };
  run.usage = result.usage; run.cost = cost; run.status = 'succeeded'; run.completedAt = new Date(); await run.save();
  return run;
};

module.exports = { queueAgentRun, processAgentRun };
