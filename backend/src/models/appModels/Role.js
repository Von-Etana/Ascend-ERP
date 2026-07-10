const mongoose = require('mongoose');
const { tenantFields, applyPlatformPlugins } = require('../platformFields');

const schema = new mongoose.Schema({
  ...tenantFields,
  name: { type: String, required: true },
  key: { type: String, required: true },
  permissions: [String],
  fieldPermissions: [
    {
      entity: String,
      deniedFields: [String],
    },
  ],
  dataScope: {
    type: String,
    enum: ['own', 'team', 'tenant'],
    default: 'own',
  },
});

module.exports = mongoose.model('Role', applyPlatformPlugins(schema));
