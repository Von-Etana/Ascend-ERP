const mongoose = require('mongoose');
const { tenantFields, applyPlatformPlugins } = require('../platformFields');

const schema = new mongoose.Schema({
  ...tenantFields,
  title: { type: String, required: true },
  docType: {
    type: String,
    enum: ['contract', 'receipt', 'invoice_attachment', 'agreement', 'report', 'other'],
    default: 'other',
  },
  fileUrl: { type: String },
  fileName: { type: String },
  fileSize: { type: Number },
  mimeType: { type: String },
  relatedEntity: {
    entityType: {
      type: String,
      enum: ['budget', 'expense', 'invoice', 'payment', 'contract', 'vendor', 'client'],
    },
    entityId: { type: mongoose.Schema.ObjectId },
  },
  uploadedBy: { type: mongoose.Schema.ObjectId, ref: 'Admin' },
  tags: [String],
  notes: { type: String },
  status: {
    type: String,
    enum: ['active', 'archived'],
    default: 'active',
  },
});

module.exports = mongoose.model('Document', applyPlatformPlugins(schema));
