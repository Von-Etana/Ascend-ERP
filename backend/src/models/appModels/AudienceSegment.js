const mongoose = require('mongoose');
const { tenantFields, applyPlatformPlugins } = require('../platformFields');

const schema = new mongoose.Schema({
  ...tenantFields,
  name: { type: String, required: true },
  description: String,
  filters: [mongoose.Schema.Types.Mixed],
});

module.exports = mongoose.model('AudienceSegment', applyPlatformPlugins(schema));
