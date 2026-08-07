const mongoose = require('mongoose');
const { tenantFields, applyPlatformPlugins } = require('../platformFields');

const schema = new mongoose.Schema({
  ...tenantFields,
  campaign: { type: mongoose.Schema.ObjectId, ref: 'Campaign' },
  provider: { type: String, enum: ['facebook', 'instagram', 'linkedin', 'x', 'whatsapp'], default: 'facebook' },
  channels: [{ type: String, enum: ['facebook', 'instagram', 'linkedin', 'x', 'whatsapp'] }],
  channelVariants: mongoose.Schema.Types.Mixed,
  caption: String,
  mediaUrl: String,
  scheduledAt: Date,
  timezone: { type: String, default: 'Africa/Lagos' },
  recurrence: mongoose.Schema.Types.Mixed,
  approvalStatus: { type: String, enum: ['not_required', 'pending', 'approved', 'rejected'], default: 'pending' },
  status: { type: String, enum: ['draft', 'pending_approval', 'scheduled', 'publishing', 'published', 'failed', 'cancelled'], default: 'draft' },
  providerPostId: String,
  publicationAttempts: [{ provider: String, status: String, attemptedAt: Date, providerPostId: String, error: String }],
  analytics: mongoose.Schema.Types.Mixed,
});

module.exports = mongoose.model('SocialPost', applyPlatformPlugins(schema));
