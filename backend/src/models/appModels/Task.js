const mongoose = require('mongoose');
const { tenantFields, linkedEntitySchema, applyPlatformPlugins } = require('../platformFields');

const schema = new mongoose.Schema({
  ...tenantFields,
  title: { type: String, required: true },
  description: String,
  status: { type: String, enum: ['todo', 'in_progress', 'done', 'cancelled'], default: 'todo' },
  priority: { type: String, enum: ['low', 'normal', 'high', 'urgent'], default: 'normal' },
  relatedTo: linkedEntitySchema,
  dueAt: Date,
  completedAt: Date,
});

module.exports = mongoose.model('Task', applyPlatformPlugins(schema));
