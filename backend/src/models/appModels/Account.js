const mongoose = require('mongoose');
const { tenantFields, applyPlatformPlugins } = require('../platformFields');
const { DEFAULT_CURRENCY_CODE } = require('../../config/platformDefaults');

const schema = new mongoose.Schema({
  ...tenantFields,
  code: { type: String, required: true },
  name: { type: String, required: true },
  type: { type: String, enum: ['asset', 'liability', 'equity', 'income', 'expense'], required: true },
  currency: { type: String, default: DEFAULT_CURRENCY_CODE, uppercase: true },
});

module.exports = mongoose.model('Account', applyPlatformPlugins(schema));
