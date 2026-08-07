const mongoose = require('mongoose');
const { tenantFields, applyPlatformPlugins } = require('../platformFields');

const schema = new mongoose.Schema({
  ...tenantFields,
  name: { type: String, required: true },
  slug: { type: String, required: true },
  description: String,
  targetEntity: {
    type: String,
    enum: ['lead', 'contact', 'client'],
    default: 'lead',
  },
  fields: [String],
  autoReplyEnabled: { type: Boolean, default: false },
  autoReplySubject: String,
  autoReplyBody: String,
  successMessage: String,
  status: {
    type: String,
    enum: ['draft', 'published', 'archived'],
    default: 'draft',
  },
});

module.exports = mongoose.model('PublicForm', applyPlatformPlugins(schema));
