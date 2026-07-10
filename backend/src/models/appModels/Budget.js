const mongoose = require('mongoose');
const { tenantFields, moneySchema, applyPlatformPlugins } = require('../platformFields');

const schema = new mongoose.Schema({
  ...tenantFields,
  name: { type: String, required: true },
  account: { type: mongoose.Schema.ObjectId, ref: 'Account' },
  periodStart: Date,
  periodEnd: Date,
  planned: moneySchema,
  actual: moneySchema,
});

module.exports = mongoose.model('Budget', applyPlatformPlugins(schema));
