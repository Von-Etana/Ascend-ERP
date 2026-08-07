const mongoose = require('mongoose');
const { tenantFields, applyPlatformPlugins } = require('../platformFields');

const schema = new mongoose.Schema({
  ...tenantFields,
  code: { type: String, required: true, uppercase: true },
  name: { type: String, required: true },
  symbol: String,
  exchangeRate: { type: Number, default: 1 },
  isDefault: { type: Boolean, default: false },
});

module.exports = mongoose.model('Currency', applyPlatformPlugins(schema));
