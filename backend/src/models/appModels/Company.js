const mongoose = require('mongoose');
const { tenantFields, applyPlatformPlugins } = require('../platformFields');

const schema = new mongoose.Schema({
  ...tenantFields,
  name: { type: String, required: true },
  type: { type: String, enum: ['lead', 'customer', 'vendor', 'partner'], default: 'customer' },
  website: String,
  phone: String,
  email: String,
  address: String,
  industry: String,
});

module.exports = mongoose.model('Company', applyPlatformPlugins(schema));
