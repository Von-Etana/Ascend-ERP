const mongoose = require('mongoose');
const { tenantFields, applyPlatformPlugins } = require('../platformFields');

const schema = new mongoose.Schema({
  ...tenantFields,
  appointment: { type: mongoose.Schema.ObjectId, ref: 'Appointment', autopopulate: true },
  inviteeName: String,
  inviteeEmail: String,
  startsAt: Date,
  endsAt: Date,
  status: { type: String, enum: ['requested', 'confirmed', 'cancelled'], default: 'requested' },
});

module.exports = mongoose.model('CalendarBooking', applyPlatformPlugins(schema));
