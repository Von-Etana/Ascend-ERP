const mongoose = require('mongoose');
const { tenantFields, moneySchema, applyPlatformPlugins } = require('../platformFields');

const allocationLineSchema = new mongoose.Schema({
  department: { type: String },
  description: { type: String },
  amount: { type: moneySchema, default: () => ({ amount: 0, currency: 'NGN' }) },
  spent: { type: moneySchema, default: () => ({ amount: 0, currency: 'NGN' }) },
}, { _id: false });

const schema = new mongoose.Schema({
  ...tenantFields,
  name: { type: String, required: true },
  description: { type: String },
  category: {
    type: String,
    enum: ['operational', 'capital', 'marketing', 'hr', 'it', 'research', 'other'],
    default: 'operational',
  },
  department: { type: String },
  periodStart: { type: Date },
  periodEnd: { type: Date },
  planned: { type: moneySchema, default: () => ({ amount: 0, currency: 'NGN' }) },
  allocated: { type: moneySchema, default: () => ({ amount: 0, currency: 'NGN' }) },
  actual: { type: moneySchema, default: () => ({ amount: 0, currency: 'NGN' }) },
  allocationLines: [allocationLineSchema],
  approvalRequired: { type: Boolean, default: false },
  approvalThreshold: { type: moneySchema, default: () => ({ amount: 0, currency: 'NGN' }) },
  notes: { type: String },
  attachments: [
    {
      url: String,
      name: String,
      mimeType: String,
    },
  ],
  status: {
    type: String,
    enum: ['draft', 'active', 'exhausted', 'closed', 'archived'],
    default: 'draft',
  },
});

module.exports = mongoose.model('Budget', applyPlatformPlugins(schema));
