const mongoose = require('mongoose');
const { createProviderRegistryForTenant } = require('@/services/integrations/providerRegistry');

const contentTypePrompts = {
  newsletter: 'Write a newsletter for this brand and audience.',
  email: 'Write a marketing email with subject and body.',
  blog: 'Write a structured blog post.',
  caption: 'Write social media captions for Facebook and Instagram.',
  flyer_copy: 'Write concise flyer headline, subhead, body, and CTA copy.',
  script: 'Write a short promotional video or sales script.',
};

const generateContent = async (req, res) => {
  const { type = 'email', prompt = '', brandContext = {}, save = true } = req.body;
  const providers = await createProviderRegistryForTenant({ tenantId: req.tenantId });
  const result = await providers.kimi.request('generateContent', {
    instruction: contentTypePrompts[type] || contentTypePrompts.email,
    prompt,
    brandContext,
  });

  let asset = null;
  if (save && mongoose.models.ContentAsset) {
    asset = await mongoose.model('ContentAsset').create({
      tenant: req.tenantId,
      createdBy: req.admin?._id,
      assignedTo: req.admin?._id,
      title: `${type} draft`,
      type,
      prompt,
      content: result.content || result.message || `${contentTypePrompts[type] || ''}\n\n${prompt}`,
      provider: 'kimi',
      brandContext,
    });
  }

  return res.status(200).json({
    success: true,
    result: { provider: result, asset },
    message: 'Content generation request processed',
  });
};

const generateBrandAsset = async (req, res) => {
  const { prompt = '', brandContext = {}, save = true } = req.body;
  const providers = await createProviderRegistryForTenant({ tenantId: req.tenantId });
  const result = await providers.fal.request('generateBrandAsset', { prompt, brandContext });

  let asset = null;
  if (save && mongoose.models.ContentAsset) {
    asset = await mongoose.model('ContentAsset').create({
      tenant: req.tenantId,
      createdBy: req.admin?._id,
      assignedTo: req.admin?._id,
      title: 'Brand asset draft',
      type: 'brand_asset',
      prompt,
      content: result.message,
      provider: 'fal',
      brandContext,
    });
  }

  return res.status(200).json({
    success: true,
    result: { provider: result, asset },
    message: 'Brand asset generation request processed',
  });
};

const draftCampaign = async (req, res) => {
  req.body.type = 'newsletter';
  return generateContent(req, res);
};

module.exports = { generateContent, generateBrandAsset, draftCampaign };
