const mongoose = require('mongoose');
const { tenantFields, moneySchema, applyPlatformPlugins } = require('../platformFields');

const schema = new mongoose.Schema({
  ...tenantFields,
  title: { type: String, required: true },
  counterparty: String,
  startDate: Date,
  endDate: Date,
  value: moneySchema,
  status: {
    type: String,
    enum: ['draft', 'active', 'expired', 'terminated'],
    default: 'draft',
  },
  notes: String,
});

module.exports = mongoose.model('Contract', applyPlatformPlugins(schema));
