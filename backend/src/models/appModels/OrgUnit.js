const mongoose = require('mongoose');
const { tenantFields, applyPlatformPlugins } = require('../platformFields');

const schema = new mongoose.Schema({
  ...tenantFields,
  name: { type: String, required: true },
  parent: { type: mongoose.Schema.ObjectId, ref: 'OrgUnit' },
  manager: { type: mongoose.Schema.ObjectId, ref: 'Admin' },
});

module.exports = mongoose.model('OrgUnit', applyPlatformPlugins(schema));
