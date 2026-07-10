const mongoose = require('mongoose');
const { tenantFields, moneySchema, applyPlatformPlugins } = require('../platformFields');

const schema = new mongoose.Schema({
  ...tenantFields,
  owner: { type: mongoose.Schema.ObjectId, ref: 'Admin', required: true },
  periodStart: Date,
  periodEnd: Date,
  target: moneySchema,
  actual: moneySchema,
});

module.exports = mongoose.model('SalesTarget', applyPlatformPlugins(schema));
