const mongoose = require('mongoose');
const { tenantFields, moneySchema, applyPlatformPlugins } = require('../platformFields');

const schema = new mongoose.Schema({
  ...tenantFields,
  number: Number,
  deal: { type: mongoose.Schema.ObjectId, ref: 'Deal', autopopulate: true },
  quote: { type: mongoose.Schema.ObjectId, ref: 'Quote' },
  contact: { type: mongoose.Schema.ObjectId, ref: 'Contact' },
  company: { type: mongoose.Schema.ObjectId, ref: 'Company' },
  invoice: { type: mongoose.Schema.ObjectId, ref: 'Invoice' },
  purchaseOrders: [{ type: mongoose.Schema.ObjectId, ref: 'PurchaseOrder' }],
  status: { type: String, enum: ['draft', 'pending_approval', 'approved', 'fulfilled', 'cancelled'], default: 'draft' },
  discountPercent: { type: Number, default: 0 },
  value: moneySchema,
  procurementNeeded: { type: Boolean, default: false },
});

module.exports = mongoose.model('SalesOrder', applyPlatformPlugins(schema));
