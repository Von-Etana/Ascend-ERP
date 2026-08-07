const mongoose = require('mongoose');
const { tenantFields, applyPlatformPlugins } = require('../platformFields');

const schema = new mongoose.Schema({
  ...tenantFields,
  name: { type: String, required: true },
  code: String,
  address: String,
  phone: String,
  email: String,
  manager: { type: mongoose.Schema.ObjectId, ref: 'Admin', autopopulate: true },
});

module.exports = mongoose.model('Branch', applyPlatformPlugins(schema));
