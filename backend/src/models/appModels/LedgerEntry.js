const mongoose = require('mongoose');
const { tenantFields, linkedEntitySchema, applyPlatformPlugins } = require('../platformFields');

const lineSchema = new mongoose.Schema(
  {
    account: { type: mongoose.Schema.ObjectId, ref: 'Account' },
    debit: { type: Number, default: 0 },
    credit: { type: Number, default: 0 },
    memo: String,
  },
  { _id: false }
);

const schema = new mongoose.Schema({
  ...tenantFields,
  date: { type: Date, default: Date.now },
  description: { type: String, required: true },
  lines: [lineSchema],
  relatedTo: linkedEntitySchema,
  status: { type: String, enum: ['draft', 'posted', 'void'], default: 'draft' },
});

module.exports = mongoose.model('LedgerEntry', applyPlatformPlugins(schema));
