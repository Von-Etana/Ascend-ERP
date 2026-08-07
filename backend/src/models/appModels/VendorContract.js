const mongoose = require('mongoose');
const { tenantFields, applyPlatformPlugins } = require('../platformFields');

const schema = new mongoose.Schema({
  ...tenantFields,
  vendor: { type: mongoose.Schema.ObjectId, ref: 'Vendor', autopopulate: true },
  title: { type: String, required: true },
  startDate: Date,
  endDate: Date,
  status: { type: String, enum: ['draft', 'active', 'expired', 'terminated'], default: 'draft' },
  documentUrl: String,
});

module.exports = mongoose.model('VendorContract', applyPlatformPlugins(schema));
