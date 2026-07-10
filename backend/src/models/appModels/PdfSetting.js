const mongoose = require('mongoose');
const { tenantFields, applyPlatformPlugins } = require('../platformFields');

const schema = new mongoose.Schema({
  ...tenantFields,
  moduleKey: {
    type: String,
    enum: ['invoice', 'quote', 'offer'],
    default: 'invoice',
  },
  headerText: String,
  footerText: String,
  termsAndConditions: String,
  accentColor: String,
  enabled: { type: Boolean, default: true },
});

module.exports = mongoose.model('PdfSetting', applyPlatformPlugins(schema));
