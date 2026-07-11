const mongoose = require('mongoose');
const { tenantFields, applyPlatformPlugins } = require('../platformFields');

const schema = new mongoose.Schema({
  ...tenantFields,
  provider: {
    type: String,
    enum: ['resend', 'meta', 'hermes', 'kimi', 'fal', 'openai', 'linkedin', 'x', 'custom'],
    required: true,
  },
  name: { type: String, required: true },
  status: { type: String, enum: ['disabled', 'active', 'error'], default: 'disabled' },
  publicConfig: mongoose.Schema.Types.Mixed,
  config: mongoose.Schema.Types.Mixed,
  secretConfigEncrypted: {
    version: { type: String, default: 'v1' },
    iv: String,
    authTag: String,
    ciphertext: String,
  },
  secretFields: [{ type: String }],
  lastTestedAt: Date,
  lastTestStatus: {
    type: String,
    enum: ['untested', 'passed', 'failed', 'skipped'],
    default: 'untested',
  },
  lastTestMessage: String,
  lastError: String,
});

module.exports = mongoose.model('IntegrationAccount', applyPlatformPlugins(schema));
