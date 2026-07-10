const mongoose = require('mongoose');
const { tenantFields, applyPlatformPlugins } = require('../platformFields');

const schema = new mongoose.Schema({
  ...tenantFields,
  campaign: { type: mongoose.Schema.ObjectId, ref: 'Campaign' },
  provider: { type: String, enum: ['facebook', 'instagram', 'linkedin', 'x'], default: 'facebook' },
  caption: String,
  mediaUrl: String,
  scheduledAt: Date,
  status: { type: String, enum: ['draft', 'scheduled', 'published', 'failed'], default: 'draft' },
  providerPostId: String,
});

module.exports = mongoose.model('SocialPost', applyPlatformPlugins(schema));
