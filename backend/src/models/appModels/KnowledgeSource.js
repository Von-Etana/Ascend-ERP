const mongoose = require('mongoose');
const { tenantFields, applyPlatformPlugins } = require('../platformFields');

const schema = new mongoose.Schema({
  ...tenantFields,
  name: { type: String, required: true },
  type: { type: String, enum: ['erp', 'file', 'website'], required: true, index: true },
  config: mongoose.Schema.Types.Mixed,
  status: { type: String, enum: ['draft', 'queued', 'processing', 'ready', 'failed'], default: 'draft' },
  documentCount: { type: Number, default: 0 },
  chunkCount: { type: Number, default: 0 },
  lastIngestedAt: Date,
  refreshSchedule: String,
  error: String,
});

module.exports = mongoose.model('KnowledgeSource', applyPlatformPlugins(schema));
