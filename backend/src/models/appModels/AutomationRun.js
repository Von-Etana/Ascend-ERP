const mongoose = require('mongoose');
const { tenantFields, applyPlatformPlugins } = require('../platformFields');

const schema = new mongoose.Schema({
  ...tenantFields,
  rule: { type: mongoose.Schema.ObjectId, ref: 'AutomationRule', autopopulate: true },
  event: { type: mongoose.Schema.ObjectId, ref: 'AutomationEvent', autopopulate: true },
  status: { type: String, enum: ['queued', 'running', 'succeeded', 'failed'], default: 'queued' },
  actionResults: [mongoose.Schema.Types.Mixed],
  error: String,
});

module.exports = mongoose.model('AutomationRun', applyPlatformPlugins(schema));
