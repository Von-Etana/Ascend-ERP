const mongoose = require('mongoose');
const { tenantFields, applyPlatformPlugins } = require('../platformFields');

const schema = new mongoose.Schema({
  ...tenantFields,
  name: { type: String, required: true },
  eventType: String,
  conditions: [mongoose.Schema.Types.Mixed],
  points: { type: Number, default: 0 },
});

module.exports = mongoose.model('LeadScoreRule', applyPlatformPlugins(schema));
