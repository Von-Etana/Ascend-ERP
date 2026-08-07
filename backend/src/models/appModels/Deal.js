const mongoose = require('mongoose');
const { tenantFields, moneySchema, applyPlatformPlugins } = require('../platformFields');

const schema = new mongoose.Schema({
  ...tenantFields,
  name: { type: String, required: true },
  contact: { type: mongoose.Schema.ObjectId, ref: 'Contact', autopopulate: true },
  company: { type: mongoose.Schema.ObjectId, ref: 'Company', autopopulate: true },
  stage: { type: mongoose.Schema.ObjectId, ref: 'PipelineStage', autopopulate: true },
  status: { type: String, enum: ['open', 'won', 'lost'], default: 'open' },
  value: moneySchema,
  expectedCloseDate: Date,
  quote: { type: mongoose.Schema.ObjectId, ref: 'Quote' },
  salesOrder: { type: mongoose.Schema.ObjectId, ref: 'SalesOrder' },
  invoice: { type: mongoose.Schema.ObjectId, ref: 'Invoice' },
  campaign: { type: mongoose.Schema.ObjectId, ref: 'Campaign' },
});

module.exports = mongoose.model('Deal', applyPlatformPlugins(schema));
