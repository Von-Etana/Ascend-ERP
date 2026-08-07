const mongoose = require('mongoose');
const { tenantFields, moneySchema, linkedEntitySchema, applyPlatformPlugins } = require('../platformFields');

const schema = new mongoose.Schema({
  ...tenantFields,
  agent: { type: mongoose.Schema.ObjectId, ref: 'AgentDefinition', required: true, index: true },
  agentVersion: { type: Number, default: 0 },
  trigger: { type: String, enum: ['manual', 'test', 'schedule', 'event', 'resume'], default: 'manual' },
  status: { type: String, enum: ['queued', 'running', 'needs_approval', 'succeeded', 'failed', 'cancelled'], default: 'queued', index: true },
  input: mongoose.Schema.Types.Mixed,
  output: mongoose.Schema.Types.Mixed,
  currentStep: { type: Number, default: 0 },
  usage: { inputTokens: { type: Number, default: 0 }, outputTokens: { type: Number, default: 0 }, totalTokens: { type: Number, default: 0 } },
  cost: { type: moneySchema, default: () => ({ amount: 0, currency: 'NGN' }) },
  linkedRecords: [linkedEntitySchema],
  error: String,
  startedAt: Date,
  completedAt: Date,
  testMode: { type: Boolean, default: false },
});

module.exports = mongoose.model('AgentRun', applyPlatformPlugins(schema));
