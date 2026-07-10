const mongoose = require('mongoose');
const Schema = mongoose.Schema;

const adminSchema = new Schema({
  removed: {
    type: Boolean,
    default: false,
  },
  enabled: {
    type: Boolean,
    default: false,
  },

  email: {
    type: String,
    lowercase: true,
    trim: true,
    required: true,
  },
  name: { type: String, required: true },
  surname: { type: String },
  photo: {
    type: String,
    trim: true,
  },
  created: {
    type: Date,
    default: Date.now,
  },
  tenant: { type: mongoose.Schema.ObjectId, ref: 'Tenant', autopopulate: true },
  roleRefs: [{ type: mongoose.Schema.ObjectId, ref: 'Role', autopopulate: true }],
  manager: { type: mongoose.Schema.ObjectId, ref: 'Admin' },
  orgUnit: { type: mongoose.Schema.ObjectId, ref: 'OrgUnit' },
  permissions: [String],
  deniedFields: [String],
  fieldPermissions: [
    {
      entity: String,
      deniedFields: [String],
    },
  ],
  role: {
    type: String,
    default: 'owner',
    enum: ['owner', 'admin', 'sales_rep', 'sales_manager', 'finance_officer', 'vendor_manager', 'marketing_manager'],
  },
});

adminSchema.plugin(require('mongoose-autopopulate'));

module.exports = mongoose.model('Admin', adminSchema);
