const mongoose = require('mongoose');
const { tenantFields, applyPlatformPlugins } = require('../platformFields');

const schema = new mongoose.Schema({
  ...tenantFields,
  firstName: { type: String, required: true },
  lastName: String,
  name: String,
  email: String,
  phone: String,
  whatsapp: String,
  lifecycleStage: {
    type: String,
    enum: ['lead', 'customer', 'vendor_contact', 'partner'],
    default: 'lead',
  },
  company: { type: mongoose.Schema.ObjectId, ref: 'Company', autopopulate: true },
  vendor: { type: mongoose.Schema.ObjectId, ref: 'Vendor' },
  leadScore: { type: Number, default: 0 },
  lastActivityAt: Date,
  tags: [String],
});

schema.pre('save', function setName(next) {
  if (!this.name) this.name = [this.firstName, this.lastName].filter(Boolean).join(' ');
  next();
});

module.exports = mongoose.model('Contact', applyPlatformPlugins(schema));
