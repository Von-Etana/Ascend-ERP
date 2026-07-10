const mongoose = require('mongoose');
const { tenantFields, moneySchema, applyPlatformPlugins } = require('../platformFields');

const schema = new mongoose.Schema({
  ...tenantFields,
  owner: { type: mongoose.Schema.ObjectId, ref: 'Admin', required: true },
  deal: { type: mongoose.Schema.ObjectId, ref: 'Deal' },
  invoice: { type: mongoose.Schema.ObjectId, ref: 'Invoice' },
  rate: { type: Number, default: 0 },
  amount: moneySchema,
  status: { type: String, enum: ['pending', 'approved', 'paid'], default: 'pending' },
});

module.exports = mongoose.model('Commission', applyPlatformPlugins(schema));
