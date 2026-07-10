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
  number: Number,
  year: Number,
  client: { type: mongoose.Schema.ObjectId, ref: 'Client', autopopulate: true },
  contact: { type: mongoose.Schema.ObjectId, ref: 'Contact' },
  deal: { type: mongoose.Schema.ObjectId, ref: 'Deal' },
  date: { type: Date, default: Date.now },
  expiredDate: Date,
  items: [itemSchema],
  total: { type: Number, default: 0 },
  value: moneySchema,
  status: { type: String, enum: ['draft', 'sent', 'accepted', 'declined'], default: 'draft' },
  notes: String,
});

module.exports = mongoose.model('Quote', applyPlatformPlugins(schema));
