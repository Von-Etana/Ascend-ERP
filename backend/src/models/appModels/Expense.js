const mongoose = require('mongoose');
const { tenantFields, moneySchema, applyPlatformPlugins } = require('../platformFields');

const schema = new mongoose.Schema({
  ...tenantFields,
  title: { type: String, required: true },
  vendor: { type: mongoose.Schema.ObjectId, ref: 'Vendor' },
  account: { type: mongoose.Schema.ObjectId, ref: 'Account' },
  amount: moneySchema,
  date: { type: Date, default: Date.now },
  status: { type: String, enum: ['draft', 'approved', 'paid'], default: 'draft' },
  taxProfile: { type: mongoose.Schema.ObjectId, ref: 'TaxProfile' },
});

module.exports = mongoose.model('Expense', applyPlatformPlugins(schema));
