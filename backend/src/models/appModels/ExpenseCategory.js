const mongoose = require('mongoose');
const { tenantFields, applyPlatformPlugins } = require('../platformFields');

const schema = new mongoose.Schema({
  ...tenantFields,
  name: { type: String, required: true },
  code: String,
  description: String,
});

module.exports = mongoose.model('ExpenseCategory', applyPlatformPlugins(schema));
