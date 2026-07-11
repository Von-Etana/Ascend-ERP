const mongoose = require('mongoose');
const { tenantFields, applyPlatformPlugins } = require('../platformFields');

const schema = new mongoose.Schema({
  ...tenantFields,
  agent: { type: mongoose.Schema.ObjectId, ref: 'AgentDefinition', required: true, index: true },
  version: { type: Number, required: true },
  snapshot: { type: mongoose.Schema.Types.Mixed, required: true },
  publishedBy: { type: mongoose.Schema.ObjectId, ref: 'Admin' },
  publishedAt: { type: Date, default: Date.now },
});
schema.index({ tenant: 1, agent: 1, version: 1 }, { unique: true });

module.exports = mongoose.model('AgentVersion', applyPlatformPlugins(schema));
