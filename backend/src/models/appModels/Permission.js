const mongoose = require('mongoose');
const { tenantFields, applyPlatformPlugins } = require('../platformFields');

const schema = new mongoose.Schema({
  ...tenantFields,
  key: { type: String, required: true, index: true },
  module: { type: String, required: true },
  action: { type: String, required: true },
  description: String,
});

module.exports = mongoose.model('Permission', applyPlatformPlugins(schema));
