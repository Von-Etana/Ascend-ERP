const mongoose = require('mongoose');
const { tenantFields, moneySchema, applyPlatformPlugins } = require('../platformFields');

const schema = new mongoose.Schema({
  ...tenantFields,
  name: { type: String, required: true },
  sku: String,
  description: String,
  category: { type: mongoose.Schema.ObjectId, ref: 'ProductCategory', autopopulate: true },
  supplier: { type: mongoose.Schema.ObjectId, ref: 'Supplier', autopopulate: true },
  salePrice: moneySchema,
  purchasePrice: moneySchema,
  stockQuantity: { type: Number, default: 0 },
  reorderLevel: { type: Number, default: 0 },
  status: {
    type: String,
    enum: ['draft', 'active', 'archived'],
    default: 'active',
  },
});

module.exports = mongoose.model('Product', applyPlatformPlugins(schema));
