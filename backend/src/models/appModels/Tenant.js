const mongoose = require('mongoose');

const schema = new mongoose.Schema({
  removed: { type: Boolean, default: false, index: true },
  enabled: { type: Boolean, default: true },
  name: { type: String, required: true },
  slug: { type: String, required: true, unique: true, lowercase: true, trim: true },
  status: { type: String, enum: ['active', 'suspended'], default: 'active' },
  settings: { type: mongoose.Schema.Types.Mixed, default: {} },
  created: { type: Date, default: Date.now },
  updated: { type: Date, default: Date.now },
});

module.exports = mongoose.model('Tenant', schema);
