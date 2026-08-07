const mongoose = require('mongoose');
const { tenantFields, applyPlatformPlugins } = require('../platformFields');

const schema = new mongoose.Schema({
  ...tenantFields,
  name: { type: String, required: true },
  description: String,
  isDefault: { type: Boolean, default: false },
});

module.exports = mongoose.model('PaymentMode', applyPlatformPlugins(schema));
