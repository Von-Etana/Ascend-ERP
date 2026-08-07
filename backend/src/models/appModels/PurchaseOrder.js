const mongoose = require('mongoose');
const { tenantFields, moneySchema, applyPlatformPlugins } = require('../platformFields');

const schema = new mongoose.Schema({
  ...tenantFields,
  number: Number,
  vendor: { type: mongoose.Schema.ObjectId, ref: 'Vendor', autopopulate: true },
  salesOrder: { type: mongoose.Schema.ObjectId, ref: 'SalesOrder' },
  bill: { type: mongoose.Schema.ObjectId, ref: 'Bill' },
  status: {
    type: String,
    enum: ['draft', 'pending_approval', 'approved', 'processing', 'received', 'returned', 'refunded', 'cancelled'],
    default: 'draft',
  },
  items: [mongoose.Schema.Types.Mixed],
  total: moneySchema,
  paymentTerms: String,
});

module.exports = mongoose.model('PurchaseOrder', applyPlatformPlugins(schema));
