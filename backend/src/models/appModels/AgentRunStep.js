const mongoose = require('mongoose');
const { tenantFields, applyPlatformPlugins } = require('../platformFields');

const schema = new mongoose.Schema({
  ...tenantFields,
  run: { type: mongoose.Schema.ObjectId, ref: 'AgentRun', required: true, index: true },
  sequence: { type: Number, required: true },
  kind: { type: String, enum: ['model', 'tool', 'approval', 'output'], required: true },
  tool: String,
  input: mongoose.Schema.Types.Mixed,
  output: mongoose.Schema.Types.Mixed,
  citations: [{ title: String, url: String, sourceId: mongoose.Schema.ObjectId, location: String }],
  status: { type: String, enum: ['queued', 'running', 'needs_approval', 'succeeded', 'failed', 'skipped'], default: 'queued' },
  durationMs: Number,
  retryCount: { type: Number, default: 0 },
  error: String,
});

module.exports = mongoose.model('AgentRunStep', applyPlatformPlugins(schema));
