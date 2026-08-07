const mongoose = require('mongoose');
const { tenantFields, linkedEntitySchema, applyPlatformPlugins } = require('../platformFields');

const schema = new mongoose.Schema({
  ...tenantFields,
  title: { type: String, required: true },
  message: String,
  remindAt: { type: Date, required: true },
  channel: { type: String, enum: ['in_app', 'email', 'whatsapp'], default: 'in_app' },
  status: { type: String, enum: ['scheduled', 'sent', 'cancelled'], default: 'scheduled' },
  relatedTo: linkedEntitySchema,
});

module.exports = mongoose.model('Reminder', applyPlatformPlugins(schema));
