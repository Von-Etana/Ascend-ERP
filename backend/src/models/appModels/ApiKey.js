const mongoose = require('mongoose');
const { tenantFields, applyPlatformPlugins } = require('../platformFields');

const schema = new mongoose.Schema({
  ...tenantFields,
  name: { type: String, required: true },
  keyPreview: String,
  scopes: [String],
  lastUsedAt: Date,
  status: {
    type: String,
    enum: ['active', 'disabled', 'rotated'],
    default: 'active',
  },
});

module.exports = mongoose.model('ApiKey', applyPlatformPlugins(schema));
