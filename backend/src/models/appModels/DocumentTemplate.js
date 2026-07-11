const mongoose = require('mongoose');
const { tenantFields, applyPlatformPlugins } = require('../platformFields');

const schema = new mongoose.Schema({
  ...tenantFields,
  name: {
    type: String,
    required: true,
    trim: true,
  },
  type: {
    type: String,
    enum: ['invoice', 'receipt', 'contract', 'quote', 'other'],
    default: 'invoice',
  },
  description: {
    type: String,
    trim: true,
  },
  // Field keys that the template expects the AI to fill
  fields: {
    type: [String],
    default: ['clientName', 'clientEmail', 'total', 'currency', 'date', 'items', 'notes'],
  },
  // Full HTML layout used to compile the final PDF — supports {{variable}} placeholders
  htmlContent: {
    type: String,
  },
  // Whether the template is active and available for selection
  enabled: {
    type: Boolean,
    default: true,
  },
  // Accent color for document branding
  accentColor: {
    type: String,
    default: '#1677ff',
  },
  // Footer text embedded in generated documents
  footerText: {
    type: String,
    default: 'Thank you for your business.',
  },
});

module.exports = mongoose.model('DocumentTemplate', applyPlatformPlugins(schema));
