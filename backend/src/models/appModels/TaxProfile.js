const mongoose = require('mongoose');
const { tenantFields, applyPlatformPlugins } = require('../platformFields');

const schema = new mongoose.Schema({
  ...tenantFields,
  name: { type: String, required: true },
  region: String,
  rate: { type: Number, default: 0 },
  isDefault: { type: Boolean, default: false },
});

module.exports = mongoose.model('TaxProfile', applyPlatformPlugins(schema));
