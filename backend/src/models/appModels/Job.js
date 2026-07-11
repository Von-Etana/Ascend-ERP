const mongoose = require('mongoose');
const { tenantFields, applyPlatformPlugins } = require('../platformFields');

const schema = new mongoose.Schema({
  ...tenantFields,
  type: { type: String, required: true, index: true },
  status: { type: String, enum: ['queued', 'running', 'waiting_approval', 'succeeded', 'failed', 'cancelled'], default: 'queued' },
  payload: mongoose.Schema.Types.Mixed,
  runAfter: { type: Date, default: Date.now },
  attempts: { type: Number, default: 0 },
  lastError: String,
});

module.exports = mongoose.model('Job', applyPlatformPlugins(schema));
