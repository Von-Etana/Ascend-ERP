const mongoose = require('mongoose');
const { tenantFields, applyPlatformPlugins } = require('../platformFields');

const schema = new mongoose.Schema({
  ...tenantFields,
  name: { type: String, required: true },
  description: String,
  trigger: {
    type: { type: String, enum: ['event', 'field_change', 'time'], default: 'event' },
    eventType: String,
    schedule: String,
  },
  conditions: [
    {
      field: String,
      operator: { type: String, default: 'eq' },
      value: mongoose.Schema.Types.Mixed,
    },
  ],
  actions: [
    {
      type: { type: String, required: true },
      config: mongoose.Schema.Types.Mixed,
    },
  ],
});

module.exports = mongoose.model('AutomationRule', applyPlatformPlugins(schema));
