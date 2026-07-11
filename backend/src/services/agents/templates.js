const templates = [
  ['marketing_strategy', 'Marketing Strategy Agent', 'Develop positioning, channel plans, KPIs, content calendars, and budget recommendations.', ['erp.search', 'web.research', 'competitor.analyze', 'marketing.strategy', 'budget.plan']],
  ['social_media_manager', 'Social Media Manager', 'Create channel-native content, maintain the publishing calendar, and optimize performance.', ['erp.search', 'content.generate', 'social.schedule', 'social.publish', 'social.metrics']],
  ['lead_generation', 'Lead Generation Agent', 'Research an ICP, identify compliant prospects, score them, and prepare CRM follow-up.', ['web.research', 'lead.research', 'crm.lead.create', 'task.create']],
  ['competitor_analysis', 'Competitor Analysis Agent', 'Monitor competitors and produce evidence-linked offer and content comparisons.', ['web.research', 'competitor.analyze', 'content.generate', 'task.create']],
  ['web_research', 'Web Research Agent', 'Research approved public sources and produce a citation-backed briefing.', ['web.research', 'content.generate']],
  ['budget_planning', 'Budget Planning Agent', 'Compare NGN allocation scenarios and track forecast versus actual.', ['erp.search', 'budget.plan', 'budget.allocate']],
  ['expense_intelligence', 'Expense Intelligence Agent', 'Classify expenses, detect anomalies, and identify budget variance without making payments.', ['erp.search', 'expense.analyze', 'task.create']],
  ['brand_guardian', 'Brand Guardian', 'Review content against brand voice, terminology, visual identity, and prohibited claims.', ['erp.search', 'brand.review', 'content.generate']],
  ['content_production', 'Content Production Agent', 'Create newsletters, email, blogs, captions, scripts, flyers, and variants.', ['erp.search', 'content.generate', 'asset.generate', 'social.schedule']],
].map(([key, name, description, tools]) => ({
  key,
  name,
  description,
  instructions: `You are the ${name}. ${description} Cite sources, respect tenant permissions, and request approval before sensitive actions.`,
  tools,
  modelPolicy: { provider: 'openai', fallbackProviders: ['kimi'], temperature: 0.3, maxOutputTokens: 2048, maxSteps: 8 },
}));

module.exports = { agentTemplates: templates, getAgentTemplate: (key) => templates.find((item) => item.key === key) };
