const mongoose = require('mongoose');
const { tenantFields, applyPlatformPlugins } = require('../platformFields');

const schema = new mongoose.Schema({
  ...tenantFields,
  provider: {
    type: String,
    enum: ['resend', 'meta', 'hermes', 'kimi', 'fal', 'openai', 'custom'],
    required: true,
  },
  name: { type: String, required: true },
  status: { type: String, enum: ['disabled', 'active', 'error'], default: 'disabled' },
  config: mongoose.Schema.Types.Mixed,
  lastError: String,
});

module.exports = mongoose.model('IntegrationAccount', applyPlatformPlugins(schema));
