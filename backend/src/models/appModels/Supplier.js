const mongoose = require('mongoose');
const { tenantFields, applyPlatformPlugins } = require('../platformFields');

const schema = new mongoose.Schema({
  ...tenantFields,
  name: { type: String, required: true },
  email: String,
  phone: String,
  address: String,
  paymentTerms: String,
  status: {
    type: String,
    enum: ['prospect', 'active', 'inactive'],
    default: 'active',
  },
});

module.exports = mongoose.model('Supplier', applyPlatformPlugins(schema));
