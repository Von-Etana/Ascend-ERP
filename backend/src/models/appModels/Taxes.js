const mongoose = require('mongoose');
const { tenantFields, applyPlatformPlugins } = require('../platformFields');

const schema = new mongoose.Schema({
  ...tenantFields,
  taxName: { type: String, required: true },
  taxValue: { type: Number, default: 0 },
  isDefault: { type: Boolean, default: false },
});

module.exports = mongoose.model('Taxes', applyPlatformPlugins(schema));
