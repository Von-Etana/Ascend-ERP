const mongoose = require('mongoose');
const { tenantFields, applyPlatformPlugins } = require('../platformFields');

const schema = new mongoose.Schema({
  ...tenantFields,
  name: { type: String, required: true },
  channel: { type: String, enum: ['email', 'sms', 'whatsapp', 'facebook', 'instagram', 'multi'], default: 'email' },
  status: { type: String, enum: ['draft', 'scheduled', 'running', 'paused', 'completed'], default: 'draft' },
  audienceSegment: { type: mongoose.Schema.ObjectId, ref: 'AudienceSegment' },
  steps: [mongoose.Schema.Types.Mixed],
  scheduledAt: Date,
  metrics: {
    sent: { type: Number, default: 0 },
    opened: { type: Number, default: 0 },
    clicked: { type: Number, default: 0 },
    converted: { type: Number, default: 0 },
  },
});

module.exports = mongoose.model('Campaign', applyPlatformPlugins(schema));
