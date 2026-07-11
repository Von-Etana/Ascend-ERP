const mongoose = require('mongoose');
const { tenantFields, applyPlatformPlugins } = require('../platformFields');

const schema = new mongoose.Schema({
  ...tenantFields,
  run: { type: mongoose.Schema.ObjectId, ref: 'AgentRun', required: true, index: true },
  step: { type: mongoose.Schema.ObjectId, ref: 'AgentRunStep' },
  tool: { type: String, required: true },
  riskLevel: { type: String, enum: ['low', 'medium', 'high', 'critical'], default: 'high' },
  payloadPreview: mongoose.Schema.Types.Mixed,
  status: { type: String, enum: ['pending', 'approved', 'rejected', 'expired'], default: 'pending', index: true },
  approver: { type: mongoose.Schema.ObjectId, ref: 'Admin' },
  decidedAt: Date,
  expiresAt: Date,
  reason: String,
});

module.exports = mongoose.model('AgentApproval', applyPlatformPlugins(schema));
