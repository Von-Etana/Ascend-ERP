const mongoose = require('mongoose');
const { tenantFields, moneySchema, applyPlatformPlugins } = require('../platformFields');

const itemSchema = new mongoose.Schema(
  {
    itemName: String,
    quantity: { type: Number, default: 1 },
    unitPrice: { type: Number, default: 0 },
    total: { type: Number, default: 0 },
  },
  { _id: false }
);

const schema = new mongoose.Schema({
  ...tenantFields,
  number: String,
  client: { type: mongoose.Schema.ObjectId, ref: 'Client', autopopulate: true },
  deal: { type: mongoose.Schema.ObjectId, ref: 'Deal', autopopulate: true },
  invoice: { type: mongoose.Schema.ObjectId, ref: 'Invoice', autopopulate: true },
  items: [itemSchema],
  total: moneySchema,
  status: {
    type: String,
    enum: ['draft', 'confirmed', 'processing', 'delivered', 'returned', 'refunded', 'cancelled'],
    default: 'draft',
  },
});

module.exports = mongoose.model('Order', applyPlatformPlugins(schema));
