const mongoose = require('mongoose');
const { tenantFields, applyPlatformPlugins } = require('../platformFields');

const schema = new mongoose.Schema({
  ...tenantFields,
  provider: { type: String, enum: ['facebook', 'instagram', 'linkedin', 'x', 'whatsapp'], required: true },
  accountName: String,
  externalAccountId: String,
  credentials: mongoose.Schema.Types.Mixed,
  scopes: [String],
  status: { type: String, enum: ['connected', 'expired', 'disabled', 'error'], default: 'disabled' },
  lastCheckedAt: Date,
  error: String,
});

module.exports = mongoose.model('SocialConnection', applyPlatformPlugins(schema));
