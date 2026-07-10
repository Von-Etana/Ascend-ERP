const mongoose = require('mongoose');
const { tenantFields, applyPlatformPlugins } = require('../platformFields');

const schema = new mongoose.Schema({
  ...tenantFields,
  vendor: { type: mongoose.Schema.ObjectId, ref: 'Vendor', autopopulate: true },
  periodStart: Date,
  periodEnd: Date,
  qualityScore: { type: Number, default: 0 },
  deliveryScore: { type: Number, default: 0 },
  costScore: { type: Number, default: 0 },
  notes: String,
});

module.exports = mongoose.model('VendorScorecard', applyPlatformPlugins(schema));
