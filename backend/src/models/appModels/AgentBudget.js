const mongoose = require('mongoose');
const { tenantFields, moneySchema, applyPlatformPlugins } = require('../platformFields');

const schema = new mongoose.Schema({
  ...tenantFields,
  name: { type: String, required: true },
  agent: { type: mongoose.Schema.ObjectId, ref: 'AgentDefinition' },
  campaign: { type: mongoose.Schema.ObjectId, ref: 'Campaign' },
  periodStart: Date,
  periodEnd: Date,
  allocated: { type: moneySchema, default: () => ({ amount: 0, currency: 'NGN' }) },
  actual: { type: moneySchema, default: () => ({ amount: 0, currency: 'NGN' }) },
  channelLimits: mongoose.Schema.Types.Mixed,
  categoryLimits: mongoose.Schema.Types.Mixed,
  approvalThreshold: { type: moneySchema, default: () => ({ amount: 0, currency: 'NGN' }) },
  status: { type: String, enum: ['draft', 'active', 'exhausted', 'closed'], default: 'draft' },
});

module.exports = mongoose.model('AgentBudget', applyPlatformPlugins(schema));
