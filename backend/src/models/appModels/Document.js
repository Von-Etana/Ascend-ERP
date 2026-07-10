const mongoose = require('mongoose');
const { tenantFields, linkedEntitySchema, applyPlatformPlugins } = require('../platformFields');

const schema = new mongoose.Schema({
  ...tenantFields,
  title: { type: String, required: true },
  type: String,
  url: String,
  relatedTo: linkedEntitySchema,
  status: {
    type: String,
    enum: ['draft', 'active', 'archived'],
    default: 'active',
  },
});

module.exports = mongoose.model('Document', applyPlatformPlugins(schema));
