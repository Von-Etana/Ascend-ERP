const mongoose = require('mongoose');

const schema = new mongoose.Schema({
  tenant: { type: mongoose.Schema.ObjectId, ref: 'Tenant', index: true },
  actor: { type: mongoose.Schema.ObjectId, ref: 'Admin' },
  action: { type: String, required: true },
  entity: { type: String, required: true },
  entityId: mongoose.Schema.Types.ObjectId,
  before: mongoose.Schema.Types.Mixed,
  after: mongoose.Schema.Types.Mixed,
  ip: String,
  created: { type: Date, default: Date.now },
});

module.exports = mongoose.model('AuditLog', schema);
