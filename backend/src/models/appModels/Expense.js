const mongoose = require('mongoose');
const { tenantFields, moneySchema, applyPlatformPlugins } = require('../platformFields');

const schema = new mongoose.Schema({
  ...tenantFields,
  title: { type: String, required: true },
  category: {
    type: String,
    enum: [
      'vendor_payment',
      'staff_reimbursement',
      'travel',
      'utilities',
      'capital',
      'marketing',
      'operations',
      'other',
    ],
    default: 'operations',
  },
  spendingType: {
    type: String,
    enum: ['internal', 'external'],
    default: 'internal',
  },
  vendor: { type: mongoose.Schema.ObjectId, ref: 'Vendor' },
  account: { type: mongoose.Schema.ObjectId, ref: 'Account' },
  budget: { type: mongoose.Schema.ObjectId, ref: 'Budget' },
  department: { type: String },
  paidBy: { type: String },
  approvedBy: { type: String },
  amount: moneySchema,
  date: { type: Date, default: Date.now },
  description: { type: String },
  status: { type: String, enum: ['draft', 'pending', 'approved', 'paid', 'rejected'], default: 'draft' },
  taxProfile: { type: mongoose.Schema.ObjectId, ref: 'TaxProfile' },
  attachments: [
    {
      url: String,
      name: String,
      mimeType: String,
    },
  ],
});

module.exports = mongoose.model('Expense', applyPlatformPlugins(schema));
