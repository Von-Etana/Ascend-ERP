const mongoose = require('mongoose');
const { tenantFields, linkedEntitySchema, applyPlatformPlugins } = require('../platformFields');

const schema = new mongoose.Schema({
  ...tenantFields,
  title: { type: String, required: true },
  relatedTo: linkedEntitySchema,
  requestedBy: { type: mongoose.Schema.ObjectId, ref: 'Admin' },
  approvers: [{ type: mongoose.Schema.ObjectId, ref: 'Admin' }],
  status: { type: String, enum: ['pending', 'approved', 'rejected'], default: 'pending' },
  reason: String,
  decidedAt: Date,
});

module.exports = mongoose.model('ApprovalRequest', applyPlatformPlugins(schema));
