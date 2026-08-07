const mongoose = require('mongoose');
const { tenantFields, applyPlatformPlugins } = require('../platformFields');

const schema = new mongoose.Schema({
  ...tenantFields,
  name: { type: String, required: true },
  voice: String,
  audience: String,
  positioning: String,
  vocabulary: [String],
  prohibitedClaims: [String],
  channelGuidelines: mongoose.Schema.Types.Mixed,
  visualIdentity: mongoose.Schema.Types.Mixed,
  examples: [{ label: String, content: String }],
  isDefault: { type: Boolean, default: false },
});

module.exports = mongoose.model('BrandProfile', applyPlatformPlugins(schema));
