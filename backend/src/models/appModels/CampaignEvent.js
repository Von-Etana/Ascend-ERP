const mongoose = require('mongoose');
const { tenantFields, applyPlatformPlugins } = require('../platformFields');

const schema = new mongoose.Schema({
  ...tenantFields,
  campaign: { type: mongoose.Schema.ObjectId, ref: 'Campaign', autopopulate: true },
  contact: { type: mongoose.Schema.ObjectId, ref: 'Contact', autopopulate: true },
  type: { type: String, enum: ['sent', 'open', 'click', 'conversion', 'bounce'], required: true },
  metadata: mongoose.Schema.Types.Mixed,
  occurredAt: { type: Date, default: Date.now },
});

module.exports = mongoose.model('CampaignEvent', applyPlatformPlugins(schema));
