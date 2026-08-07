const mongoose = require('mongoose');
const { tenantFields, applyPlatformPlugins } = require('../platformFields');

const schema = new mongoose.Schema({
  ...tenantFields,
  source: { type: mongoose.Schema.ObjectId, ref: 'KnowledgeSource', required: true, index: true },
  title: { type: String, required: true },
  sourceUrl: String,
  mimeType: String,
  contentHash: String,
  metadata: mongoose.Schema.Types.Mixed,
  status: { type: String, enum: ['processing', 'ready', 'failed'], default: 'processing' },
});

module.exports = mongoose.model('KnowledgeDocument', applyPlatformPlugins(schema));
