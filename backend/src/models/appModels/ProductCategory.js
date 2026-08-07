const mongoose = require('mongoose');
const { tenantFields, applyPlatformPlugins } = require('../platformFields');

const schema = new mongoose.Schema({
  ...tenantFields,
  name: { type: String, required: true },
  description: String,
  parent: { type: mongoose.Schema.ObjectId, ref: 'ProductCategory', autopopulate: true },
  color: String,
});

module.exports = mongoose.model('ProductCategory', applyPlatformPlugins(schema));
