const mongoose = require('mongoose');
const { tenantFields, applyPlatformPlugins } = require('../platformFields');

const schema = new mongoose.Schema({
  ...tenantFields,
  source: { type: mongoose.Schema.ObjectId, ref: 'KnowledgeSource', required: true, index: true },
  document: { type: mongoose.Schema.ObjectId, ref: 'KnowledgeDocument', required: true, index: true },
  sequence: Number,
  text: { type: String, required: true },
  embedding: [Number],
  metadata: mongoose.Schema.Types.Mixed,
});

module.exports = mongoose.model('KnowledgeChunk', applyPlatformPlugins(schema));
