const mongoose = require('mongoose');
const { tenantFields, applyPlatformPlugins } = require('../platformFields');

const schema = new mongoose.Schema({
  ...tenantFields,
  name: { type: String, required: true },
  order: { type: Number, default: 0 },
  probability: { type: Number, default: 0 },
  isWon: { type: Boolean, default: false },
  isLost: { type: Boolean, default: false },
});

module.exports = mongoose.model('PipelineStage', applyPlatformPlugins(schema));
