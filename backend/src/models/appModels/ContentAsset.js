const mongoose = require('mongoose');
const { tenantFields, applyPlatformPlugins } = require('../platformFields');

const schema = new mongoose.Schema({
  ...tenantFields,
  title: { type: String, required: true },
  type: { type: String, enum: ['newsletter', 'email', 'blog', 'caption', 'flyer_copy', 'script', 'brand_asset'], required: true },
  prompt: String,
  content: String,
  mediaUrl: String,
  provider: String,
  brandContext: mongoose.Schema.Types.Mixed,
});

module.exports = mongoose.model('ContentAsset', applyPlatformPlugins(schema));
