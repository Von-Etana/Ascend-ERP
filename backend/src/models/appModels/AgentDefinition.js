const mongoose = require('mongoose');
const { tenantFields, moneySchema, applyPlatformPlugins } = require('../platformFields');

const schema = new mongoose.Schema({
  ...tenantFields,
  name: { type: String, required: true },
  description: String,
  specialistType: { type: String, default: 'custom', index: true },
  instructions: { type: String, required: true },
  modelPolicy: {
    provider: { type: String, default: 'openai' },
    model: String,
    fallbackProviders: [String],
    temperature: { type: Number, default: 0.3, min: 0, max: 2 },
    maxOutputTokens: { type: Number, default: 2048 },
    maxSteps: { type: Number, default: 8, min: 1, max: 30 },
  },
  knowledgeSources: [{ type: mongoose.Schema.ObjectId, ref: 'KnowledgeSource' }],
  tools: [String],
  schedule: { enabled: Boolean, expression: String, timezone: { type: String, default: 'Africa/Lagos' } },
  approvalPolicy: {
    mode: { type: String, enum: ['risk_based', 'always', 'autonomous'], default: 'risk_based' },
    approvalFor: [String],
  },
  limits: {
    tokenBudget: { type: Number, default: 50000 },
    runFrequencyPerDay: { type: Number, default: 20 },
    messagesPerRun: { type: Number, default: 25 },
    socialPostsPerRun: { type: Number, default: 10 },
    scrapedPagesPerRun: { type: Number, default: 20 },
    costBudget: { type: moneySchema, default: () => ({ amount: 0, currency: 'NGN' }) },
  },
  status: { type: String, enum: ['draft', 'published', 'paused', 'archived'], default: 'draft', index: true },
  version: { type: Number, default: 0 },
  publishedAt: Date,
});

module.exports = mongoose.model('AgentDefinition', applyPlatformPlugins(schema));
