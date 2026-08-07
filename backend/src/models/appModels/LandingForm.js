const mongoose = require('mongoose');
const { tenantFields, applyPlatformPlugins } = require('../platformFields');

const schema = new mongoose.Schema({
  ...tenantFields,
  name: { type: String, required: true },
  slug: { type: String, required: true },
  campaign: { type: mongoose.Schema.ObjectId, ref: 'Campaign' },
  fields: [mongoose.Schema.Types.Mixed],
  thankYouMessage: String,
});

module.exports = mongoose.model('LandingForm', applyPlatformPlugins(schema));
