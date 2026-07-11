const mongoose = require('mongoose');
const { canAccessAction } = require('@/services/platform/accessControl');
const { fetchPublicPage } = require('./webResearchService');
const { createSocialPublisher } = require('./socialPublisher');

const definitions = [
  ['erp.search', 'Search ERP', 'ai.agent.run', 'low'],
  ['web.research', 'Web Research', 'ai.web.research', 'low'],
  ['competitor.analyze', 'Competitor Analysis', 'ai.agent.run', 'low'],
  ['marketing.strategy', 'Marketing Strategy', 'ai.agent.run', 'low'],
  ['content.generate', 'Generate Content', 'ai.studio.create', 'low'],
  ['asset.generate', 'Generate Brand Asset', 'ai.studio.create', 'medium'],
  ['lead.research', 'Lead Research', 'ai.web.research', 'low'],
  ['crm.lead.create', 'Create CRM Lead', 'crm.lead.create', 'medium'],
  ['task.create', 'Create Task', 'tasks.task.create', 'medium'],
  ['social.schedule', 'Schedule Social Post', 'marketing.socialpost.create', 'medium'],
  ['social.publish', 'Publish Social Post', 'marketing.social.publish', 'high'],
  ['social.metrics', 'Read Social Metrics', 'marketing.socialpost.read', 'low'],
  ['brand.review', 'Review Brand Compliance', 'ai.agent.run', 'low'],
  ['budget.plan', 'Plan Budget', 'finance.agent.read', 'low'],
  ['budget.allocate', 'Allocate Budget', 'finance.agent.allocate', 'high'],
  ['expense.analyze', 'Analyze Expenses', 'finance.expense.read', 'low'],
].map(([key, label, permission, riskLevel]) => ({ key, label, permission, riskLevel, available: true }));

const mandatoryApproval = new Set(['social.publish', 'budget.allocate']);

const requiresApproval = ({ definition, agent, testMode }) => {
  if (testMode) return false;
  if (mandatoryApproval.has(definition.key)) return true;
  if (agent.approvalPolicy?.mode === 'always' && definition.riskLevel !== 'low') return true;
  return (agent.approvalPolicy?.approvalFor || []).includes(definition.key);
};

const assertToolAccess = ({ tool, actor }) => {
  const definition = definitions.find((item) => item.key === tool);
  if (!definition) throw new Error(`Unknown agent tool: ${tool}`);
  if (!canAccessAction(actor || {}, definition.permission)) throw new Error(`Permission denied for agent tool: ${tool}`);
  return definition;
};

const simulateOutput = (tool, input = {}) => ({
  simulated: true,
  summary: `${definitions.find((item) => item.key === tool)?.label || tool} completed in test mode.`,
  input,
});

const executeTool = async ({ tool, input = {}, tenant, actor, testMode = false, providers }) => {
  if (testMode) return simulateOutput(tool, input);
  switch (tool) {
    case 'task.create':
      return mongoose.model('Task').create({ tenant, createdBy: actor?._id, assignedTo: actor?._id, name: input.name || 'AI follow-up', description: input.description, status: 'pending' });
    case 'crm.lead.create':
      return mongoose.model('Lead').create({ tenant, createdBy: actor?._id, assignedTo: actor?._id, name: input.name || 'AI researched lead', email: input.email, source: 'ai-agent', status: 'new' });
    case 'social.schedule':
      return mongoose.model('SocialPost').create({ tenant, createdBy: actor?._id, caption: input.caption, channels: input.channels || ['facebook'], scheduledAt: input.scheduledAt, timezone: input.timezone || 'Africa/Lagos', status: 'pending_approval', approvalStatus: 'pending' });
    case 'social.publish':
      return createSocialPublisher().publish(input.channel || 'facebook', input);
    case 'web.research':
      return fetchPublicPage(input.url);
    case 'content.generate':
      return providers.kimi.request('generateContent', input);
    case 'asset.generate':
      return providers.fal.request('generateBrandAsset', input);
    case 'erp.search': {
      const allowed = new Set(['Lead', 'Contact', 'Company', 'Campaign', 'Expense', 'Budget']);
      const model = allowed.has(input.model) ? input.model : 'Lead';
      return mongoose.model(model).find({ tenant, removed: false }).limit(Math.min(Number(input.limit || 10), 25)).lean();
    }
    default:
      return simulateOutput(tool, input);
  }
};

module.exports = { toolDefinitions: definitions, mandatoryApproval, requiresApproval, assertToolAccess, executeTool };
