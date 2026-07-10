const mongoose = require('mongoose');
const { tenantFields, moneySchema, applyPlatformPlugins } = require('../platformFields');

const itemSchema = new mongoose.Schema(
  {
    itemName: String,
    description: String,
    quantity: { type: Number, default: 1 },
    price: { type: Number, default: 0 },
    total: { type: Number, default: 0 },
  },
  { _id: false }
);

const schema = new mongoose.Schema({
  ...tenantFields,
  title: { type: String, required: true },
  kind: {
    type: String,
    enum: ['customer_offer', 'lead_offer', 'proforma'],
    default: 'customer_offer',
  },
  client: { type: mongoose.Schema.ObjectId, ref: 'Client', autopopulate: true },
  contact: { type: mongoose.Schema.ObjectId, ref: 'Contact', autopopulate: true },
  lead: { type: mongoose.Schema.ObjectId, ref: 'Lead', autopopulate: true },
  deal: { type: mongoose.Schema.ObjectId, ref: 'Deal', autopopulate: true },
  validUntil: Date,
  items: [itemSchema],
  total: { type: Number, default: 0 },
  value: moneySchema,
  status: {
    type: String,
    enum: ['draft', 'sent', 'accepted', 'declined', 'converted'],
    default: 'draft',
  },
  notes: String,
});

module.exports = mongoose.model('Offer', applyPlatformPlugins(schema));
