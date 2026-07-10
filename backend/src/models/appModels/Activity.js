const mongoose = require('mongoose');
const { tenantFields, linkedEntitySchema, applyPlatformPlugins } = require('../platformFields');

const schema = new mongoose.Schema({
  ...tenantFields,
  type: { type: String, enum: ['note', 'call', 'email', 'sms', 'whatsapp', 'meeting', 'task'], default: 'note' },
  subject: { type: String, required: true },
  body: String,
  contact: { type: mongoose.Schema.ObjectId, ref: 'Contact' },
  company: { type: mongoose.Schema.ObjectId, ref: 'Company' },
  relatedTo: linkedEntitySchema,
  dueAt: Date,
  completedAt: Date,
});

module.exports = mongoose.model('Activity', applyPlatformPlugins(schema));
