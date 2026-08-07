const mongoose = require('mongoose');
const { tenantFields, applyPlatformPlugins } = require('../platformFields');

const schema = new mongoose.Schema({
  ...tenantFields,
  title: { type: String, required: true },
  contact: { type: mongoose.Schema.ObjectId, ref: 'Contact', autopopulate: true },
  company: { type: mongoose.Schema.ObjectId, ref: 'Company', autopopulate: true },
  source: String,
  status: { type: String, enum: ['new', 'contacted', 'qualified', 'disqualified'], default: 'new' },
  score: { type: Number, default: 0 },
  lastContactedAt: Date,
});

module.exports = mongoose.model('Lead', applyPlatformPlugins(schema));
