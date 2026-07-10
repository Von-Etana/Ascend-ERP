const mongoose = require('mongoose');
const { DEFAULT_CURRENCY_CODE } = require('../config/platformDefaults');

const tenantFields = {
  tenant: { type: mongoose.Schema.ObjectId, ref: 'Tenant', index: true },
  removed: { type: Boolean, default: false, index: true },
  enabled: { type: Boolean, default: true },
  createdBy: { type: mongoose.Schema.ObjectId, ref: 'Admin' },
  assignedTo: { type: mongoose.Schema.ObjectId, ref: 'Admin' },
  created: { type: Date, default: Date.now },
  updated: { type: Date, default: Date.now },
};

const linkedEntitySchema = new mongoose.Schema(
  {
    entityType: String,
    entityId: mongoose.Schema.Types.ObjectId,
  },
  { _id: false }
);

const moneySchema = new mongoose.Schema(
  {
    amount: { type: Number, default: 0 },
    currency: { type: String, default: DEFAULT_CURRENCY_CODE, uppercase: true },
  },
  { _id: false }
);

const applyPlatformPlugins = (schema) => {
  schema.pre('save', function setUpdated(next) {
    this.updated = new Date();
    next();
  });
  schema.plugin(require('mongoose-autopopulate'));
  return schema;
};

module.exports = {
  tenantFields,
  linkedEntitySchema,
  moneySchema,
  applyPlatformPlugins,
};
