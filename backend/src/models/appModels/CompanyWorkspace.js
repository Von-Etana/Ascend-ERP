const mongoose = require('mongoose');
const { tenantFields, applyPlatformPlugins } = require('../platformFields');
const { DEFAULT_CURRENCY_CODE } = require('../../config/platformDefaults');

const schema = new mongoose.Schema({
  ...tenantFields,
  name: { type: String, required: true },
  code: String,
  branch: { type: mongoose.Schema.ObjectId, ref: 'Branch', autopopulate: true },
  timezone: String,
  defaultCurrency: { type: String, default: DEFAULT_CURRENCY_CODE },
  status: {
    type: String,
    enum: ['draft', 'active', 'archived'],
    default: 'active',
  },
});

module.exports = mongoose.model('CompanyWorkspace', applyPlatformPlugins(schema));
