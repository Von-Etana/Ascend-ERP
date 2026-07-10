const mongoose = require('mongoose');
const { tenantFields, applyPlatformPlugins } = require('../platformFields');

const schema = new mongoose.Schema({
  ...tenantFields,
  name: { type: String, required: true },
  email: String,
  phone: String,
  address: String,
  onboardingStatus: { type: String, enum: ['draft', 'pending', 'approved', 'rejected'], default: 'draft' },
  paymentTerms: String,
  performanceScore: { type: Number, default: 0 },
});

module.exports = mongoose.model('Vendor', applyPlatformPlugins(schema));
