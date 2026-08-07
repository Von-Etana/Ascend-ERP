const mongoose = require('mongoose');

const schema = new mongoose.Schema({
  tenant: { type: mongoose.Schema.ObjectId, ref: 'Tenant', index: true },
  removed: {
    type: Boolean,
    default: false,
  },
  enabled: {
    type: Boolean,
    default: true,
  },

  name: {
    type: String,
    required: true,
  },
  phone: String,
  country: String,
  address: String,
  email: String,
  lifecycleStage: {
    type: String,
    enum: ['lead', 'customer', 'vendor_contact', 'partner'],
    default: 'customer',
  },
  company: { type: mongoose.Schema.ObjectId, ref: 'Company', autopopulate: true },
  leadScore: { type: Number, default: 0 },
  createdBy: { type: mongoose.Schema.ObjectId, ref: 'Admin' },
  assigned: { type: mongoose.Schema.ObjectId, ref: 'Admin' },
  assignedTo: { type: mongoose.Schema.ObjectId, ref: 'Admin' },
  created: {
    type: Date,
    default: Date.now,
  },
  updated: {
    type: Date,
    default: Date.now,
  },
});

schema.plugin(require('mongoose-autopopulate'));

module.exports = mongoose.model('Client', schema);
