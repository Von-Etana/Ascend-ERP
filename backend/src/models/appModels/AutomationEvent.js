const mongoose = require('mongoose');
const { tenantFields, applyPlatformPlugins } = require('../platformFields');

const schema = new mongoose.Schema({
  ...tenantFields,
  type: { type: String, required: true, index: true },
  sourceModule: String,
  entity: String,
  entityId: mongoose.Schema.Types.ObjectId,
  actor: { type: mongoose.Schema.ObjectId, ref: 'Admin' },
  payload: mongoose.Schema.Types.Mixed,
  status: { type: String, enum: ['queued', 'processed', 'failed'], default: 'queued' },
  occurredAt: { type: Date, default: Date.now },
});

module.exports = mongoose.model('AutomationEvent', applyPlatformPlugins(schema));
