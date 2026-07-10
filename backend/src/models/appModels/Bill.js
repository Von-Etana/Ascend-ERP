const mongoose = require('mongoose');
const { tenantFields, moneySchema, applyPlatformPlugins } = require('../platformFields');

const schema = new mongoose.Schema({
  ...tenantFields,
  vendor: { type: mongoose.Schema.ObjectId, ref: 'Vendor', autopopulate: true },
  purchaseOrder: { type: mongoose.Schema.ObjectId, ref: 'PurchaseOrder' },
  number: String,
  amount: moneySchema,
  dueDate: Date,
  status: { type: String, enum: ['draft', 'open', 'paid', 'cancelled'], default: 'draft' },
});

module.exports = mongoose.model('Bill', applyPlatformPlugins(schema));
