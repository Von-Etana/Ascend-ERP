const mongoose = require('mongoose');
const { tenantFields, linkedEntitySchema, applyPlatformPlugins } = require('../platformFields');

const schema = new mongoose.Schema({
  ...tenantFields,
  title: { type: String, required: true },
  contact: { type: mongoose.Schema.ObjectId, ref: 'Contact' },
  startsAt: { type: Date, required: true },
  endsAt: Date,
  location: String,
  bookingUrl: String,
  status: { type: String, enum: ['scheduled', 'completed', 'cancelled'], default: 'scheduled' },
  relatedTo: linkedEntitySchema,
});

module.exports = mongoose.model('Appointment', applyPlatformPlugins(schema));
