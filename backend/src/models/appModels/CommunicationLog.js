const mongoose = require('mongoose');
const { tenantFields, applyPlatformPlugins } = require('../platformFields');

const schema = new mongoose.Schema({
  ...tenantFields,
  channel: { type: String, enum: ['email', 'sms', 'whatsapp', 'facebook', 'instagram'], required: true },
  direction: { type: String, enum: ['inbound', 'outbound'], default: 'outbound' },
  contact: { type: mongoose.Schema.ObjectId, ref: 'Contact', autopopulate: true },
  subject: String,
  body: String,
  provider: String,
  providerMessageId: String,
  status: { type: String, default: 'logged' },
  sentAt: Date,
});

module.exports = mongoose.model('CommunicationLog', applyPlatformPlugins(schema));
