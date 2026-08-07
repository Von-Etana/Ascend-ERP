const {
  buildMarketingWorkspaceSummary,
  listAiWorkspaceAssets,
  previewAutomationRule,
  buildAutomationWorkspaceSummary,
  launchCampaignWorkflow,
} = require('@/services/platform/growthWorkspaces');

const marketingSummary = async (req, res) => {
  const result = await buildMarketingWorkspaceSummary({ tenantId: req.tenantId });
  return res.status(200).json({
    success: true,
    result,
    message: 'Marketing workspace summary loaded',
  });
};

const aiAssets = async (req, res) => {
  const result = await listAiWorkspaceAssets({
    tenantId: req.tenantId,
    filters: req.query || {},
  });
  return res.status(200).json({
    success: true,
    result,
    message: 'AI workspace assets loaded',
  });
};

const automationPreview = async (req, res) => {
  const result = await previewAutomationRule({
    tenantId: req.tenantId,
    draft: req.body?.rule || req.body || {},
    eventId: req.body?.eventId,
    event: req.body?.event,
  });
  return res.status(200).json({
    success: true,
    result,
    message: 'Automation preview generated',
  });
};

const automationSummary = async (req, res) => {
  const result = await buildAutomationWorkspaceSummary({ tenantId: req.tenantId });
  return res.status(200).json({
    success: true,
    result,
    message: 'Automation workspace summary loaded',
  });
};

const launchCampaign = async (req, res) => {
  const result = await launchCampaignWorkflow({
    tenantId: req.tenantId,
    adminId: req.admin?._id,
    payload: req.body || {},
  });
  return res.status(200).json({
    success: true,
    result,
    message: 'Campaign launched successfully',
  });
};

module.exports = {
  marketingSummary,
  aiAssets,
  automationPreview,
  automationSummary,
  launchCampaign,
};
