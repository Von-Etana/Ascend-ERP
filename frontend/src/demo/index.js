import { DEFAULT_CURRENCY_CODE, DEFAULT_CURRENCY_SYMBOL } from '@/constants/platformDefaults';

export const DEMO_MODE =
  String(import.meta.env.VITE_DEMO_MODE || '').toLowerCase() === 'true';

const now = new Date('2026-07-10T10:00:00.000Z');
const iso = (daysOffset = 0, hoursOffset = 0) =>
  new Date(now.getTime() + daysOffset * 24 * 60 * 60 * 1000 + hoursOffset * 60 * 60 * 1000).toISOString();

export const demoAuthState = {
  current: {
    _id: 'demo-admin-1',
    name: 'Amina',
    surname: 'Okafor',
    email: 'demo@idurar.local',
    role: 'owner',
    permissions: ['*'],
    roleRefs: [],
    token: 'demo-token',
    enabled: true,
  },
  isLoggedIn: true,
  isLoading: false,
  isSuccess: true,
};

const demoSettingsRecords = [
  ['app_settings', 'idurar_app_language', 'en_us'],
  ['app_settings', 'idurar_company_name', 'Acme Growth Labs'],
  ['app_settings', 'idurar_website', 'https://acmegrowth.example'],
  ['company_settings', 'company_name', 'Acme Growth Labs'],
  ['company_settings', 'company_email', 'hello@acmegrowth.example'],
  ['company_settings', 'company_phone', '+234 800 000 0000'],
  ['crm_settings', 'lead_source_default', 'campaign'],
  ['finance_settings', 'default_currency_code', DEFAULT_CURRENCY_CODE],
  ['money_format_settings', 'default_currency_code', DEFAULT_CURRENCY_CODE],
  ['money_format_settings', 'currency_code', DEFAULT_CURRENCY_CODE],
  ['money_format_settings', 'currency_symbol', DEFAULT_CURRENCY_SYMBOL],
  ['money_format_settings', 'currency_position', 'before'],
  ['money_format_settings', 'thousand_sep', ','],
  ['money_format_settings', 'decimal_sep', '.'],
  ['money_format_settings', 'cent_precision', '2'],
].map(([settingCategory, settingKey, settingValue], index) => ({
  _id: `setting-${index + 1}`,
  settingCategory,
  settingKey,
  settingValue,
}));

const createRecord = (prefix, fields) => ({
  _id: `${prefix}-${Math.random().toString(36).slice(2, 10)}`,
  created: iso(-2),
  updated: iso(-1),
  removed: false,
  ...fields,
});

const createDemoDb = () => ({
  setting: [...demoSettingsRecords],
  client: [
    createRecord('client', {
      name: 'Northwind Retail',
      email: 'ops@northwind.test',
      lifecycleStage: 'customer',
    }),
    createRecord('client', {
      name: 'Bluewave Ventures',
      email: 'growth@bluewave.test',
      lifecycleStage: 'customer',
    }),
  ],
  quote: [
    createRecord('quote', { number: 'Q-1001', status: 'sent', total: 2200 }),
    createRecord('quote', { number: 'Q-1002', status: 'draft', total: 1850 }),
  ],
  invoice: [
    createRecord('invoice', { number: 'INV-2001', status: 'paid', total: 3200, date: iso(-3) }),
    createRecord('invoice', { number: 'INV-2002', status: 'overdue', total: 1800, date: iso(-8) }),
    createRecord('invoice', { number: 'INV-2003', status: 'sent', total: 900, date: iso(-1) }),
  ],
  payment: [
    createRecord('payment', { number: 'PAY-3001', status: 'paid', amount: 3200, date: iso(-2), reconciled: true, reconciledAt: iso(-2) }),
    createRecord('payment', { number: 'PAY-3002', status: 'pending', amount: 900, date: iso(-1), reconciled: false }),
  ],
  offer: [
    createRecord('offer', { name: 'Q3 Retainer Upsell', kind: 'proforma', status: 'converted' }),
    createRecord('offer', { name: 'Onboarding Bundle', kind: 'proposal', status: 'draft' }),
  ],
  productcategory: [
    createRecord('productcategory', { name: 'Services', color: '#1677ff' }),
    createRecord('productcategory', { name: 'Creative Assets', color: '#13c2c2' }),
  ],
  supplier: [
    createRecord('supplier', { name: 'Pixel Foundry', status: 'active', email: 'hello@pixelfoundry.test' }),
    createRecord('supplier', { name: 'Outbound Ops Co', status: 'prospect', email: 'ops@outbound.test' }),
  ],
  product: [
    createRecord('product', {
      name: 'Lifecycle Email Pack',
      sku: 'LIFECYCLE-01',
      stockQuantity: 6,
      reorderLevel: 10,
      status: 'active',
      salePrice: { amount: 399, currency: DEFAULT_CURRENCY_CODE },
    }),
    createRecord('product', {
      name: 'Campaign Creative Kit',
      sku: 'CREATIVE-02',
      stockQuantity: 18,
      reorderLevel: 8,
      status: 'active',
      salePrice: { amount: 699, currency: DEFAULT_CURRENCY_CODE },
    }),
  ],
  expensecategory: [
    createRecord('expensecategory', { name: 'Paid Media', code: 'MEDIA' }),
    createRecord('expensecategory', { name: 'Production', code: 'PROD' }),
  ],
  expense: [
    createRecord('expense', {
      title: 'Meta retargeting spend',
      amount: { amount: 1200, currency: DEFAULT_CURRENCY_CODE },
      status: 'approved',
      date: iso(-4),
    }),
    createRecord('expense', {
      title: 'Creative production sprint',
      amount: { amount: 650, currency: DEFAULT_CURRENCY_CODE },
      status: 'paid',
      date: iso(-1),
    }),
  ],
  order: [
    createRecord('order', { name: 'Northwind rollout', status: 'processing' }),
    createRecord('order', { name: 'Bluewave launch', status: 'delivered' }),
  ],
  purchaseorder: [
    createRecord('purchaseorder', { number: 'PO-4101', status: 'processing' }),
    createRecord('purchaseorder', { number: 'PO-4102', status: 'confirmed' }),
  ],
  vendor: [
    createRecord('vendor', { name: 'Acme Supply Partners', onboardingStatus: 'approved', email: 'vendor@acme.test' }),
    createRecord('vendor', { name: 'Studio Merchants', onboardingStatus: 'draft', email: 'hello@studio.test' }),
  ],
  currency: [
    createRecord('currency', {
      code: DEFAULT_CURRENCY_CODE,
      name: 'Nigerian Naira',
      symbol: DEFAULT_CURRENCY_SYMBOL,
      exchangeRate: 1,
      isDefault: true,
    }),
    createRecord('currency', { code: 'USD', name: 'US Dollar', symbol: '$', exchangeRate: 0.00067, isDefault: false }),
  ],
  paymentmode: [
    createRecord('paymentmode', { name: 'Bank Transfer', description: 'Direct business transfer', isDefault: true }),
    createRecord('paymentmode', { name: 'Card', description: 'POS or payment link', isDefault: false }),
  ],
  account: [
    createRecord('account', { code: '4000', name: 'Sales Revenue', type: 'income', currency: DEFAULT_CURRENCY_CODE }),
    createRecord('account', { code: '6100', name: 'Marketing Expense', type: 'expense', currency: DEFAULT_CURRENCY_CODE }),
  ],
  taxprofile: [createRecord('taxprofile', { name: 'VAT 7.5%', rate: 7.5, isDefault: true })],
  branch: [createRecord('branch', { name: 'Lagos HQ', code: 'LOS-HQ' })],
  companyworkspace: [
    createRecord('companyworkspace', {
      name: 'Growth Ops Workspace',
      code: 'GROWTH',
      status: 'active',
      defaultCurrency: DEFAULT_CURRENCY_CODE,
      timezone: 'Africa/Lagos',
    }),
  ],
  campaign: [
    createRecord('campaign', {
      name: 'July Warm Lead Nurture',
      channel: 'email',
      status: 'running',
      audienceSegment: 'segment-warm',
      scheduledAt: iso(0, 3),
      steps: [{ subject: 'Still evaluating? Here is the fast path', body: '...' }],
      metrics: { sent: 820, opened: 412, clicked: 137, converted: 28 },
    }),
    createRecord('campaign', {
      name: 'Founder Story Retargeting',
      channel: 'multi',
      status: 'scheduled',
      audienceSegment: 'segment-unopened',
      scheduledAt: iso(1, 4),
      steps: [{ subject: 'See how teams like yours cut cycle time', body: '...' }],
      metrics: { sent: 0, opened: 0, clicked: 0, converted: 0 },
    }),
    createRecord('campaign', {
      name: 'Customer Thank-you Sequence',
      channel: 'email',
      status: 'completed',
      audienceSegment: 'segment-customers',
      scheduledAt: iso(-7),
      steps: [{ subject: 'Thanks for your trust', body: '...' }],
      metrics: { sent: 210, opened: 162, clicked: 54, converted: 19 },
    }),
  ],
  audiencesegment: [
    createRecord('audiencesegment', {
      _id: 'segment-warm',
      name: 'Warm Leads',
      description: 'Contacts with recent campaign engagement and lead score above 25.',
      filters: [{ field: 'leadScore', operator: 'gte', value: 25 }],
    }),
    createRecord('audiencesegment', {
      _id: 'segment-unopened',
      name: 'Unopened Nurture Contacts',
      description: 'Recipients who did not open the last nurture sequence.',
      filters: [{ field: 'lastCampaignOpened', operator: 'eq', value: false }],
    }),
    createRecord('audiencesegment', {
      _id: 'segment-customers',
      name: 'New Customers',
      description: 'Closed-won customers in the last 30 days.',
      filters: [{ field: 'customerSince', operator: 'gte', value: iso(-30) }],
    }),
  ],
  campaignevent: [
    createRecord('campaignevent', { type: 'open', audienceSegment: 'segment-warm', campaign: 'campaign-1' }),
    createRecord('campaignevent', { type: 'click', audienceSegment: 'segment-warm', campaign: 'campaign-1' }),
    createRecord('campaignevent', { type: 'conversion', audienceSegment: 'segment-customers', campaign: 'campaign-3' }),
  ],
  socialpost: [
    createRecord('socialpost', {
      campaign: 'campaign-2',
      provider: 'facebook',
      caption: 'Still nurturing warm leads? Here is the case study angle.',
      scheduledAt: iso(1, 4),
      status: 'scheduled',
    }),
    createRecord('socialpost', {
      campaign: 'campaign-2',
      provider: 'instagram',
      caption: 'A sharper nurture story for high-intent leads.',
      scheduledAt: iso(1, 4),
      status: 'scheduled',
    }),
  ],
  contentasset: [
    createRecord('contentasset', {
      title: 'Warm Lead Email Draft',
      type: 'email',
      prompt: 'Create a high-intent warm lead follow-up.',
      content: 'Subject: Still evaluating? Here is the fast path\n\nHi {{firstName}},\nWe noticed you engaged with our launch sequence...',
      provider: 'kimi',
      brandContext: { brandName: 'Acme Growth Labs', campaignId: 'campaign-1', audienceSegment: 'segment-warm' },
      createdBy: 'demo-admin-1',
    }),
    createRecord('contentasset', {
      title: 'Founder Story Caption',
      type: 'caption',
      prompt: 'Write social captions for a founder story retargeting angle.',
      content: 'What happens when a growth team stops spraying messages and starts sequencing intent?',
      provider: 'kimi',
      brandContext: { brandName: 'Acme Growth Labs', campaignId: 'campaign-2', audienceSegment: 'segment-unopened' },
      createdBy: 'demo-admin-1',
    }),
    createRecord('contentasset', {
      title: 'Brand Flyer Concept',
      type: 'brand_asset',
      prompt: 'Generate a clean flyer for nurture retargeting.',
      content: 'Generated brand asset concept ready for export.',
      mediaUrl: 'https://images.example.com/demo-flyer.png',
      provider: 'fal',
      brandContext: { brandName: 'Acme Growth Labs', campaignId: 'campaign-2' },
      createdBy: 'demo-admin-1',
    }),
  ],
  agentdefinition: [
    createRecord('agent', {
      name: 'Growth Strategist',
      description: 'Research opportunities and prepare a measurable multi-channel growth plan.',
      specialistType: 'marketing_strategy',
      instructions: 'Analyze tenant data and approved public sources. Cite evidence and request approval before publishing or allocating budget.',
      modelPolicy: { provider: 'openai', fallbackProviders: ['kimi'], temperature: 0.3, maxOutputTokens: 2048, maxSteps: 8 },
      tools: ['erp.search', 'web.research', 'competitor.analyze', 'marketing.strategy', 'budget.plan'],
      knowledgeSources: ['knowledge-growth', 'knowledge-competitors'],
      approvalPolicy: { mode: 'risk_based', approvalFor: [] },
      limits: { tokenBudget: 50000, scrapedPagesPerRun: 20, costBudget: { amount: 50000, currency: DEFAULT_CURRENCY_CODE } },
      status: 'published', version: 3,
    }),
    createRecord('agent', {
      name: 'Social Media Manager',
      description: 'Draft, schedule, and optimize social content across all connected channels.',
      specialistType: 'social_media_manager',
      instructions: 'Use the brand profile and require approval before live publishing.',
      modelPolicy: { provider: 'kimi', fallbackProviders: ['openai'], temperature: 0.6, maxOutputTokens: 1600, maxSteps: 7 },
      tools: ['content.generate', 'social.schedule', 'social.publish', 'social.metrics'],
      approvalPolicy: { mode: 'risk_based', approvalFor: ['social.publish'] },
      status: 'published', version: 2,
    }),
  ],
  agentrun: [
    createRecord('agentrun', { agent: { _id: 'agent-growth', name: 'Growth Strategist' }, trigger: 'schedule', status: 'succeeded', currentStep: 5, usage: { inputTokens: 3420, outputTokens: 1180, totalTokens: 4600 }, cost: { amount: 81.6, currency: DEFAULT_CURRENCY_CODE }, startedAt: iso(-1, -2), output: { content: '## Growth strategy ready\n\nThree evidence-backed channel opportunities were identified with an NGN allocation scenario.' } }),
    createRecord('agentrun', { agent: { _id: 'agent-social', name: 'Social Media Manager' }, trigger: 'manual', status: 'needs_approval', currentStep: 3, usage: { inputTokens: 2100, outputTokens: 840, totalTokens: 2940 }, cost: { amount: 42.4, currency: DEFAULT_CURRENCY_CODE }, startedAt: iso(0, -1) }),
  ],
  agentapproval: [
    createRecord('approval', { run: 'run-social', tool: 'social.publish', riskLevel: 'high', status: 'pending', payloadPreview: { channels: ['facebook', 'instagram', 'linkedin'], scheduledAt: iso(1, 4) }, expiresAt: iso(1) }),
  ],
  knowledgesource: [
    createRecord('knowledge', { name: 'CRM and campaign performance', type: 'erp', status: 'ready', documentCount: 6, chunkCount: 184, lastIngestedAt: iso(0, -2), config: { entities: ['Lead', 'Campaign', 'Expense'] } }),
    createRecord('knowledge', { name: 'Competitor websites', type: 'website', status: 'ready', documentCount: 12, chunkCount: 356, lastIngestedAt: iso(-1), config: { url: 'https://example.com' } }),
    createRecord('knowledge', { name: '2026 Brand Playbook', type: 'file', status: 'ready', documentCount: 1, chunkCount: 48, lastIngestedAt: iso(-2) }),
  ],
  brandprofile: [
    createRecord('brand', { name: 'Acme Growth Labs', voice: 'Clear, confident, commercially practical', audience: 'Nigerian growth leaders and founders', positioning: 'Measured growth without operational chaos', vocabulary: ['measurable', 'practical', 'trusted'], prohibitedClaims: ['guaranteed revenue', 'instant results'], isDefault: true }),
  ],
  agentbudget: [
    createRecord('agentbudget', { name: 'Q3 Growth Agents', allocated: { amount: 750000, currency: DEFAULT_CURRENCY_CODE }, actual: { amount: 286400, currency: DEFAULT_CURRENCY_CODE }, approvalThreshold: { amount: 100000, currency: DEFAULT_CURRENCY_CODE }, status: 'active' }),
  ],
  socialconnection: [
    ...['facebook', 'instagram', 'linkedin', 'x', 'whatsapp'].map((provider) => createRecord('socialconnection', { provider, accountName: `Acme ${provider}`, status: 'connected', scopes: ['publish', 'read_metrics'] })),
  ],
  integrationaccount: [
    createRecord('integrationaccount', {
      provider: 'openai',
      name: 'OpenAI Production',
      status: 'active',
      enabled: true,
      publicConfig: { model: 'gpt-4o-mini', baseUrl: 'https://api.openai.com/v1/chat/completions' },
      secretFields: ['apiKey'],
      secretConfigured: true,
      secretPreview: [{ key: 'apiKey', maskedValue: 'sk-••••••123' }],
      lastTestStatus: 'passed',
      lastTestMessage: 'OpenAI credentials validated',
      lastTestedAt: iso(-1),
    }),
    createRecord('integrationaccount', {
      provider: 'resend',
      name: 'Resend Mailer',
      status: 'active',
      enabled: true,
      publicConfig: { fromEmail: 'IDURAR <hello@acmegrowth.example>' },
      secretFields: ['apiKey'],
      secretConfigured: true,
      secretPreview: [{ key: 'apiKey', maskedValue: 're_••••••789' }],
      lastTestStatus: 'passed',
      lastTestMessage: 'Resend credentials validated',
      lastTestedAt: iso(-2),
    }),
    createRecord('integrationaccount', {
      provider: 'meta',
      name: 'Meta Channels',
      status: 'disabled',
      enabled: false,
      publicConfig: { whatsappPhoneNumberId: '1234567890', facebookPageId: 'acme-page', instagramAccountId: 'acme-ig' },
      secretFields: [],
      secretConfigured: false,
      secretPreview: [],
      lastTestStatus: 'untested',
      lastTestMessage: '',
      lastTestedAt: null,
    }),
  ],
  automationrule: [
    createRecord('automationrule', {
      name: 'Deal Won Follow-up',
      description: 'Creates a welcome task and thank-you email.',
      enabled: true,
      trigger: { type: 'event', eventType: 'crm.deal.won' },
      conditions: [{ field: 'payload.amount', operator: 'gte', value: 1 }],
      actions: [
        { type: 'create_task', config: { title: 'Welcome new customer' } },
        { type: 'send_email', config: { subject: 'Thank you for choosing us' } },
      ],
    }),
    createRecord('automationrule', {
      name: 'Campaign Click Follow-up',
      description: 'Creates sales follow-up tasks after high-intent clicks.',
      enabled: true,
      trigger: { type: 'event', eventType: 'marketing.campaign.clicked' },
      conditions: [{ field: 'payload.score', operator: 'gte', value: 10 }],
      actions: [{ type: 'create_task', config: { title: 'Follow up on campaign click' } }],
    }),
  ],
  automationevent: [
    createRecord('automationevent', {
      type: 'crm.deal.won',
      entity: 'deal',
      payload: { amount: 2400, status: 'won' },
      occurredAt: iso(-1),
      status: 'processed',
    }),
    createRecord('automationevent', {
      type: 'marketing.campaign.clicked',
      entity: 'campaign',
      payload: { score: 12, contactName: 'Ada' },
      occurredAt: iso(0, -4),
      status: 'queued',
    }),
  ],
  automationrun: [
    createRecord('automationrun', {
      status: 'succeeded',
      rule: { _id: 'rule-1', name: 'Deal Won Follow-up' },
      event: { _id: 'event-1', type: 'crm.deal.won' },
      actionResults: [{ type: 'create_task' }, { type: 'send_email' }],
      error: '',
    }),
    createRecord('automationrun', {
      status: 'failed',
      rule: { _id: 'rule-2', name: 'Campaign Click Follow-up' },
      event: { _id: 'event-2', type: 'marketing.campaign.clicked' },
      actionResults: [{ type: 'create_task', status: 'failed' }],
      error: 'Provider timeout while creating downstream task',
    }),
  ],
  job: [
    createRecord('job', { type: 'automation.run', status: 'queued', payload: { rule: 'rule-2', event: 'event-2' } }),
    createRecord('job', { type: 'automation.run', status: 'failed', payload: { rule: 'rule-1', event: 'event-1' } }),
  ],
  task: [
    createRecord('task', { title: 'Call high-intent clickers', status: 'open', priority: 'high' }),
  ],
  appointment: [
    createRecord('appointment', { title: 'Product demo', startsAt: iso(1, 2), status: 'scheduled', location: 'Google Meet' }),
  ],
  calendarbooking: [
    createRecord('calendarbooking', {
      inviteeName: 'Chioma Eze',
      inviteeEmail: 'chioma@example.com',
      startsAt: iso(2, 1),
      status: 'requested',
    }),
  ],
  document: [
    createRecord('document', {
      title: 'Sales Process Overview',
      type: 'playbook',
      status: 'active',
      url: 'https://example.com/docs/sales-process',
    }),
  ],
  contract: [
    createRecord('contract', {
      title: 'Annual Retainer',
      counterparty: 'Northwind Retail',
      status: 'active',
      value: { amount: 5000000, currency: DEFAULT_CURRENCY_CODE },
    }),
  ],
  publicform: [
    createRecord('publicform', { name: 'Demo Request', slug: 'demo-request', targetEntity: 'lead', status: 'published', fields: ['firstName', 'email', 'message'] }),
  ],
  apikey: [
    createRecord('apikey', { name: 'Partner Demo Key', keyPreview: 'pk_demo_1234', scopes: ['crm.contact.read'], status: 'active' }),
  ],
  documenttemplate: [
    createRecord('documenttemplate', {
      name: 'Standard Invoice',
      type: 'invoice',
      description: 'Professional invoice template with line items, totals, and client details.',
      fields: ['clientName', 'clientEmail', 'date', 'documentNumber', 'currency', 'total', 'items', 'notes'],
      accentColor: '#1677ff',
      footerText: 'Thank you for your business. Payment is due within 30 days.',
      enabled: true,
    }),
    createRecord('documenttemplate', {
      name: 'Payment Receipt',
      type: 'receipt',
      description: 'Receipt confirming payment received from a client.',
      fields: ['clientName', 'clientEmail', 'date', 'documentNumber', 'currency', 'total', 'paymentMethod', 'notes'],
      accentColor: '#52c41a',
      footerText: 'This receipt confirms payment in full. Keep for your records.',
      enabled: true,
    }),
    createRecord('documenttemplate', {
      name: 'Service Quote',
      type: 'quote',
      description: 'Quotation template for proposed services with validity period.',
      fields: ['clientName', 'clientEmail', 'date', 'documentNumber', 'currency', 'total', 'items', 'validUntil', 'notes'],
      accentColor: '#fa8c16',
      footerText: 'This quote is valid for 14 days from the issue date.',
      enabled: true,
    }),
    createRecord('documenttemplate', {
      name: 'Service Contract',
      type: 'contract',
      description: 'Formal service agreement outlining scope, terms, and deliverables.',
      fields: ['clientName', 'clientEmail', 'date', 'documentNumber', 'startDate', 'endDate', 'scope', 'currency', 'total', 'notes'],
      accentColor: '#722ed1',
      footerText: 'Both parties agree to the terms outlined in this contract.',
      enabled: true,
    }),
  ],
});

const ensureDb = () => {
  if (!globalThis.__IDURAR_DEMO_DB) {
    globalThis.__IDURAR_DEMO_DB = createDemoDb();
  }
  return globalThis.__IDURAR_DEMO_DB;
};

const clone = (value) => JSON.parse(JSON.stringify(value));

const normalizeEntity = (entity = '') => entity.split('?')[0].replace(/^\/+|\/+$/g, '');

const getCollection = (entity) => {
  const db = ensureDb();
  const collection = db[entity];
  if (!collection) {
    db[entity] = [];
  }
  return db[entity];
};

const parseQueryString = (entity = '') => {
  const [, query = ''] = entity.split('?');
  const params = new URLSearchParams(query);
  return Object.fromEntries(params.entries());
};

const buildReportOverview = () => {
  const db = ensureDb();
  const invoices = db.invoice;
  const payments = db.payment;
  const orders = db.order;
  const purchases = db.purchaseorder;
  const forms = db.publicform;
  const offers = db.offer;

  const sum = (items, selector) => items.reduce((total, item) => total + Number(selector(item) || 0), 0);
  const countStatus = (items, key) =>
    Object.entries(items.reduce((acc, item) => ({ ...acc, [item[key]]: (acc[item[key]] || 0) + 1 }), {})).map(
      ([status, count]) => ({ status, count })
    );

  return {
    finance: {
      invoices: {
        count: invoices.length,
        total: sum(invoices, (item) => item.total),
        outstanding: sum(invoices.filter((item) => item.status !== 'paid'), (item) => item.total),
        paidCount: invoices.filter((item) => item.status === 'paid').length,
        partialCount: invoices.filter((item) => item.status === 'sent').length,
        unpaidCount: invoices.filter((item) => item.status !== 'paid').length,
      },
      payments: {
        count: payments.length,
        total: sum(payments, (item) => item.amount),
        reconciledCount: payments.filter((p) => p.reconciled).length,
        unreconciledCount: payments.filter((p) => !p.reconciled).length,
      },
    },
    operations: {
      orders: { total: orders.length, statusCounts: countStatus(orders, 'status') },
      purchases: { total: purchases.length, statusCounts: countStatus(purchases, 'status') },
    },
    conversions: {
      publicFormSubmissions: 14,
      publishedForms: forms.filter((item) => item.status === 'published').length,
      convertedOffers: offers.filter((item) => item.status === 'converted').length,
      paymentsRecorded: payments.length,
      reconciledPayments: payments.filter((p) => p.reconciled).length,
    },
  };
};

const buildMarketingSummary = () => {
  const db = ensureDb();
  const campaigns = clone(db.campaign);
  const segments = clone(db.audiencesegment).map((segment) => ({
    id: segment._id,
    name: segment.name,
    description: segment.description,
    filterCount: segment.filters?.length || 0,
    recentEngagements: segment._id === 'segment-warm' ? 42 : segment._id === 'segment-unopened' ? 18 : 25,
  }));
  const metrics = campaigns.reduce(
    (acc, campaign) => {
      acc.totalCampaigns += 1;
      acc.scheduledCampaigns += campaign.status === 'scheduled' ? 1 : 0;
      acc.runningCampaigns += campaign.status === 'running' ? 1 : 0;
      acc.completedCampaigns += campaign.status === 'completed' ? 1 : 0;
      acc.sent += Number(campaign.metrics?.sent || 0);
      acc.opened += Number(campaign.metrics?.opened || 0);
      acc.clicked += Number(campaign.metrics?.clicked || 0);
      acc.converted += Number(campaign.metrics?.converted || 0);
      return acc;
    },
    { totalCampaigns: 0, scheduledCampaigns: 0, runningCampaigns: 0, completedCampaigns: 0, sent: 0, opened: 0, clicked: 0, converted: 0 }
  );

  return {
    metrics,
    campaigns,
    segments,
    scheduledPosts: clone(db.socialpost).filter((item) => item.status === 'scheduled'),
    recentPosts: clone(db.socialpost),
    recentEvents: clone(db.campaignevent),
  };
};

const buildAutomationSummary = () => {
  const db = ensureDb();
  return {
    metrics: {
      queuedJobs: db.job.filter((item) => item.status === 'queued').length,
      failedRuns: db.automationrun.filter((item) => item.status === 'failed').length,
      runningRuns: db.automationrun.filter((item) => item.status === 'running').length,
      activeRules: db.automationrule.filter((item) => item.enabled !== false).length,
    },
    recentRuns: clone(db.automationrun),
    recentEvents: clone(db.automationevent),
    recentJobs: clone(db.job),
    starterRules: clone(db.automationrule).map((rule) => ({
      _id: rule._id,
      name: rule.name,
      triggerSummary: `${rule.trigger?.type || 'event'}${rule.trigger?.eventType ? `: ${rule.trigger.eventType}` : ''}`,
      actionSummaries: (rule.actions || []).map((action) => action.type),
    })),
  };
};

const success = (result, message = 'Demo request completed') => ({
  success: true,
  result: clone(result),
  message,
});

const createNewRecord = (entity, payload) => {
  const record = createRecord(entity, payload);
  getCollection(entity).unshift(record);
  return record;
};

export const demoApi = {
  login: async ({ loginData }) => {
    const validEmail = ['admin@admin.com', 'demo@idurar.local'];
    const validPassword = ['admin123', 'demo123'];
    if (!validEmail.includes(loginData?.email) || !validPassword.includes(loginData?.password)) {
      return { success: false, result: null, message: 'Invalid demo credentials' };
    }
    return success(demoAuthState.current, 'Demo login successful');
  },
  logout: async () => success({}, 'Logged out of demo mode'),
  request: async (method, payload = {}) => {
    const entity = normalizeEntity(payload.entity || '');
    const query = parseQueryString(payload.entity || '');
    const db = ensureDb();

    if (method === 'post' && /invoice\/([^\/]+)\/record-payment/.test(entity)) {
      const invoiceId = entity.split('/')[1];
      const invoices = getCollection('invoice');
      const invoice = invoices.find((item) => item._id === invoiceId);
      if (!invoice) {
        return { success: false, result: null, message: 'Invoice not found' };
      }
      const amount = Number(payload.jsonData?.amount || 0);
      invoice.credit = (invoice.credit || 0) + amount;
      if (invoice.credit >= invoice.total) {
        invoice.status = 'paid';
        invoice.paymentStatus = 'paid';
      } else if (invoice.credit > 0) {
        invoice.status = 'sent';
        invoice.paymentStatus = 'partially';
      }
      const payment = createNewRecord('payment', {
        number: `PAY-${Math.floor(1000 + Math.random() * 9000)}`,
        status: 'paid',
        amount,
        invoice: invoiceId,
        client: invoice.client,
        date: payload.jsonData?.date || iso(),
        paymentMode: payload.jsonData?.paymentMode || 'Bank Transfer',
        ref: payload.jsonData?.ref || '',
        description: payload.jsonData?.description || 'Invoice Payment Recorded',
        reconciled: false,
      });
      return success(payment, 'Invoice payment recorded successfully');
    }

    if (method === 'patch' && /payment\/([^\/]+)\/reconcile/.test(entity)) {
      const paymentId = entity.split('/')[1];
      const payments = getCollection('payment');
      const payment = payments.find((item) => item._id === paymentId);
      if (!payment) {
        return { success: false, result: null, message: 'Payment not found' };
      }
      payment.reconciled = true;
      payment.reconciledAt = iso();
      return success(payment, 'Payment reconciled successfully');
    }


    if (method === 'get' && entity === 'reports/overview') {
      return success(buildReportOverview(), 'Report overview loaded');
    }
    if (method === 'get' && entity === 'marketing/workspace/summary') {
      return success(buildMarketingSummary(), 'Marketing workspace summary loaded');
    }
    if (method === 'get' && entity === 'automation/workspace/summary') {
      return success(buildAutomationSummary(), 'Automation workspace summary loaded');
    }
    if (method === 'get' && entity === 'ai/workspace/assets') {
      const assets = clone(db.contentasset).filter((asset) => {
        if (query.type && asset.type !== query.type) return false;
        if (query.provider && asset.provider !== query.provider) return false;
        if (query.createdBy && asset.createdBy !== query.createdBy) return false;
        if (query.campaignId && asset.brandContext?.campaignId !== query.campaignId) return false;
        return true;
      });
      return success(assets);
    }
    if (method === 'get' && entity === 'automation/run-history') {
      return success(clone(db.automationrun));
    }
    if (method === 'get' && entity === 'agent/templates') {
      const rows = [
        ['marketing_strategy','Marketing Strategy Agent','Develop positioning, channel plans, KPIs, and budget recommendations.',['erp.search','web.research','competitor.analyze','marketing.strategy','budget.plan']],
        ['social_media_manager','Social Media Manager','Create channel-native content and maintain the publishing calendar.',['content.generate','social.schedule','social.publish','social.metrics']],
        ['lead_generation','Lead Generation Agent','Research an ICP, identify prospects, score them, and prepare CRM follow-up.',['web.research','lead.research','crm.lead.create','task.create']],
        ['competitor_analysis','Competitor Analysis Agent','Monitor competitors and produce evidence-linked comparisons.',['web.research','competitor.analyze','content.generate']],
        ['web_research','Web Research Agent','Research approved public sources with citations.',['web.research','content.generate']],
        ['budget_planning','Budget Planning Agent','Compare NGN allocation scenarios and forecast versus actual.',['erp.search','budget.plan','budget.allocate']],
        ['expense_intelligence','Expense Intelligence Agent','Classify expenses, detect anomalies, and identify variance.',['erp.search','expense.analyze','task.create']],
        ['brand_guardian','Brand Guardian','Review content against brand voice and prohibited claims.',['erp.search','brand.review','content.generate']],
        ['content_production','Content Production Agent','Create newsletters, email, blogs, captions, scripts, and flyers.',['content.generate','asset.generate','social.schedule']],
      ];
      return success(rows.map(([key,name,description,tools]) => ({ key,name,description,tools })));
    }
    if (method === 'get' && entity === 'agent/tools') {
      const rows = [['erp.search','Search ERP','low'],['web.research','Web Research','low'],['competitor.analyze','Competitor Analysis','low'],['marketing.strategy','Marketing Strategy','low'],['content.generate','Generate Content','low'],['asset.generate','Generate Brand Asset','medium'],['lead.research','Lead Research','low'],['crm.lead.create','Create CRM Lead','medium'],['task.create','Create Task','medium'],['social.schedule','Schedule Social Post','medium'],['social.publish','Publish Social Post','high'],['social.metrics','Read Social Metrics','low'],['brand.review','Review Brand Compliance','low'],['budget.plan','Plan Budget','low'],['budget.allocate','Allocate Budget','high'],['expense.analyze','Analyze Expenses','low']];
      return success(rows.map(([key,label,riskLevel]) => ({ key,label,riskLevel,available:true })));
    }
    if (method === 'get' && entity === 'agent/run/history') return success(clone(db.agentrun));
    if (method === 'get' && entity === 'agent/approval') return success(clone(db.agentapproval));
    if (method === 'get' && entity === 'integrationaccount/providers') {
      const providerDefs = [
        ['openai', 'OpenAI', 'llm', true, [{ key: 'model', label: 'Model' }, { key: 'baseUrl', label: 'Base URL' }], [{ key: 'apiKey', label: 'API Key', inputType: 'password' }]],
        ['kimi', 'Kimi', 'llm', true, [{ key: 'model', label: 'Model' }, { key: 'baseUrl', label: 'Base URL' }], [{ key: 'apiKey', label: 'API Key', inputType: 'password' }]],
        ['hermes', 'Hermes', 'orchestration', false, [{ key: 'baseUrl', label: 'Base URL' }], [{ key: 'apiKey', label: 'API Key', inputType: 'password' }]],
        ['fal', 'Fal.ai', 'media', false, [{ key: 'baseUrl', label: 'API URL' }], [{ key: 'apiKey', label: 'API Key', inputType: 'password' }]],
        ['resend', 'Resend', 'email', true, [{ key: 'fromEmail', label: 'Default From Email' }], [{ key: 'apiKey', label: 'API Key', inputType: 'password' }]],
        ['meta', 'Meta', 'social', true, [{ key: 'whatsappPhoneNumberId', label: 'WhatsApp Phone Number ID' }, { key: 'facebookPageId', label: 'Facebook Page ID' }, { key: 'instagramAccountId', label: 'Instagram Account ID' }], [{ key: 'accessToken', label: 'Access Token', inputType: 'password' }]],
        ['linkedin', 'LinkedIn', 'social', true, [{ key: 'orgId', label: 'Organization ID' }], [{ key: 'accessToken', label: 'Access Token', inputType: 'password' }]],
        ['x', 'X', 'social', true, [], [{ key: 'accessToken', label: 'Access Token', inputType: 'password' }]],
      ];
      return success(providerDefs.map(([key, label, category, supportsLiveTest, publicFields, secretFields]) => ({
        key,
        label,
        category,
        supportsLiveTest,
        publicFields,
        secretFields,
        account: clone(db.integrationaccount).find((item) => item.provider === key) || null,
        envFallbackActive: false,
      })));
    }

    if (method === 'post' && entity === 'agent/from-template') {
      const jsonData = payload.jsonData || {};
      const agent = createNewRecord('agentdefinition', { name: jsonData.name || 'Custom AI Agent', description: jsonData.description || 'A governed business agent ready to configure.', specialistType: jsonData.templateKey || 'custom', instructions: jsonData.instructions || 'Assist the team while respecting permissions and approvals.', modelPolicy: jsonData.modelPolicy || { provider: 'openai', fallbackProviders: ['kimi'], maxSteps: 8 }, tools: jsonData.tools || ['content.generate'], approvalPolicy: jsonData.approvalPolicy || { mode: 'risk_based' }, status: 'draft', version: 0 });
      return success(agent, 'Agent created');
    }
    if (method === 'post' && entity === 'agent/assistant/chat') {
      return success({ provider: 'openai', usage: { totalTokens: 420 }, message: `## Recommended agent setup\n\nI would define one measurable outcome, attach the relevant ERP and brand sources, then enable only the tools required for that outcome.\n\n### Guardrails\n\n1. Keep public research citation-backed.\n2. Require approval for publishing and budget allocation.\n3. Test with simulated writes before publishing.` }, 'Assistant response generated');
    }
    if (method === 'post' && /agent\/.+\/test$/.test(entity)) {
      const run = createNewRecord('agentrun', { status: 'succeeded', trigger: 'test', testMode: true, currentStep: 4, usage: { inputTokens: 980, outputTokens: 460, totalTokens: 1440 }, cost: { amount: 18.2, currency: DEFAULT_CURRENCY_CODE }, output: { content: '## Safe test passed\n\nThe configured knowledge and tools were validated. External publishing and financial changes were simulated.' }, startedAt: iso(0), completedAt: iso(0,1) });
      return success(run, 'Test run completed');
    }
    if (method === 'post' && /agent\/.+\/publish$/.test(entity)) return success({ status: 'published', version: 1 }, 'Agent published');
    if (method === 'post' && /agent\/approval\/.+\/decide$/.test(entity)) {
      const approval = db.agentapproval.find((item) => entity.includes(item._id));
      if (approval) approval.status = payload.jsonData?.decision || 'approved';
      return success(approval || {}, `Approval ${payload.jsonData?.decision || 'approved'}`);
    }
    if (method === 'post' && /integrationaccount\/test\/.+$/.test(entity)) {
      const provider = entity.split('/').pop();
      const account = db.integrationaccount.find((item) => item.provider === provider) || null;
      if (account) {
        account.lastTestStatus = account.secretConfigured ? 'passed' : 'skipped';
        account.lastTestMessage = account.secretConfigured ? `${provider} credentials validated in demo mode` : 'No secret saved in demo mode';
        account.lastTestedAt = iso(0);
        account.status = account.secretConfigured ? 'active' : account.status;
      }
      return success({
        provider,
        ok: Boolean(account?.secretConfigured),
        skipped: !account?.secretConfigured,
        message: account?.lastTestMessage || 'No account configured',
        account,
      }, 'Integration connection test completed');
    }

    if (method === 'post' && entity === 'ai/content/generate') {
      const jsonData = payload.jsonData || {};
      const asset = createNewRecord('contentasset', {
        title: `${jsonData.type || 'content'} draft`,
        type: jsonData.type || 'email',
        prompt: jsonData.prompt,
        content: `Draft for ${jsonData.brandContext?.brandName || 'your brand'}\n\nGoal: ${jsonData.brandContext?.goal || 'Drive response'}\nChannel: ${jsonData.brandContext?.channel || 'email'}\n\n${jsonData.prompt || 'Tell the story clearly and give the audience one easy next step.'}`,
        provider: 'kimi',
        brandContext: jsonData.brandContext || {},
        createdBy: demoAuthState.current._id,
      });
      return success({ provider: { content: asset.content }, asset }, 'Content generation request processed');
    }

    if (method === 'post' && entity === 'ai/brand-asset/generate') {
      const jsonData = payload.jsonData || {};
      const asset = createNewRecord('contentasset', {
        title: 'Brand asset draft',
        type: 'brand_asset',
        prompt: jsonData.prompt,
        content: 'Generated brand asset concept ready for client review.',
        mediaUrl: 'https://images.example.com/demo-generated-brand-asset.png',
        provider: 'fal',
        brandContext: jsonData.brandContext || {},
        createdBy: demoAuthState.current._id,
      });
      return success({ provider: { mediaUrl: asset.mediaUrl, message: asset.content }, asset }, 'Brand asset generation request processed');
    }

    if (method === 'post' && entity === 'ai/campaign/draft') {
      const jsonData = payload.jsonData || {};
      const asset = createNewRecord('contentasset', {
        title: `${jsonData.type || 'campaign'} campaign draft`,
        type: jsonData.type || 'newsletter',
        prompt: jsonData.prompt,
        content: `Subject: A smarter next step for ${jsonData.brandContext?.audience || 'your audience'}\n\n${jsonData.prompt || 'This campaign is ready for launch.'}`,
        provider: 'kimi',
        brandContext: jsonData.brandContext || {},
        createdBy: demoAuthState.current._id,
      });
      return success({ provider: { content: asset.content }, asset }, 'Campaign draft generated');
    }

    if (method === 'post' && entity === 'campaign/launch') {
      const jsonData = payload.jsonData || {};
      const campaign = createNewRecord('campaign', {
        name: jsonData.name,
        channel: jsonData.channel || 'email',
        audienceSegment: jsonData.audienceSegment,
        status: jsonData.status || 'scheduled',
        scheduledAt: jsonData.scheduledAt || iso(0, 2),
        steps: jsonData.steps || [],
        metrics: { sent: 0, opened: 0, clicked: 0, converted: 0 },
      });
      const socialPosts = (jsonData.socialProviders || []).map((provider) =>
        createNewRecord('socialpost', {
          campaign: campaign._id,
          provider,
          caption: jsonData.caption || '',
          mediaUrl: jsonData.mediaUrl,
          scheduledAt: jsonData.scheduledAt || iso(0, 2),
          status: 'scheduled',
        })
      );
      return success({
        campaign,
        socialPosts,
        readiness: {
          hasAudience: Boolean(jsonData.audienceSegment),
          hasContent: Boolean(jsonData.body || jsonData.caption),
          hasSchedule: true,
          hasChannel: Boolean(jsonData.channel),
        },
      }, 'Campaign launched successfully');
    }

    // ─── AI Document Template management ───────────────────────────────────────
    if (method === 'get' && entity === 'ai/document/templates') {
      return success(clone(db.documenttemplate || []), 'Templates loaded');
    }

    if (method === 'post' && entity === 'documenttemplate/create') {
      const jsonData = payload.jsonData || {};
      const template = createNewRecord('documenttemplate', {
        name: jsonData.name || 'New Template',
        type: jsonData.type || 'invoice',
        description: jsonData.description || '',
        fields: jsonData.fields || ['clientName', 'clientEmail', 'total', 'currency', 'date', 'items', 'notes', 'documentNumber'],
        htmlContent: jsonData.htmlContent || '',
        accentColor: jsonData.accentColor || '#1677ff',
        footerText: jsonData.footerText || 'Thank you for your business.',
        enabled: true,
      });
      if (!db.documenttemplate) db.documenttemplate = [];
      db.documenttemplate.push(template);
      return success(template, 'Document template created');
    }

    if (method === 'get' && /^documenttemplate\/list/.test(entity)) {
      return success(clone(db.documenttemplate || []), 'Templates loaded');
    }

    if (method === 'post' && entity === 'ai/document/generate-from-template') {
      const jsonData = payload.jsonData || {};
      const prompt = jsonData.prompt || '';
      const templateId = jsonData.templateId;
      const sendEmail = Boolean(jsonData.sendEmail);

      // Load or fall back to default template
      const template = (db.documenttemplate || []).find((t) => t._id === templateId) || null;
      const templateType = template?.type || 'invoice';
      const accentColor = template?.accentColor || '#1677ff';
      const footerText = template?.footerText || 'Thank you for your business.';

      // Deterministic demo AI parser: extract email, amount, name
      const emailMatch = prompt.match(/[\w.-]+@[\w.-]+\.\w+/);
      const amountMatch = prompt.match(/[\d,]+(\.\d{1,2})?/);
      const nameMatch = prompt.match(/(?:for|to|client)\s+([A-Z][a-z]+(?:\s+[A-Z][a-z]+)?)/i);
      const total = amountMatch ? parseFloat(amountMatch[0].replace(',', '')) : 0;
      const clientName = nameMatch ? nameMatch[1] : 'Demo Client';
      const clientEmail = emailMatch ? emailMatch[0] : (jsonData.recipientEmail || 'client@example.com');
      const today = new Date().toISOString().slice(0, 10);
      const docNumber = `${templateType.slice(0, 3).toUpperCase()}-${Math.floor(1000 + Math.random() * 9000)}`;

      const extractedFields = {
        clientName,
        clientEmail,
        date: today,
        documentNumber: docNumber,
        currency: 'NGN',
        total,
        items: [{ description: prompt.slice(0, 60) || 'Professional services', qty: 1, price: total }],
        notes: '',
      };

      // Build compiled HTML preview
      const compiledHtml = `<html><body style="font-family:Arial;padding:24px;color:#1e293b;">
        <div style="border-left:5px solid ${accentColor};padding-left:16px;margin-bottom:20px;">
          <h2 style="margin:0;text-transform:uppercase;color:${accentColor};">${templateType}</h2>
          <span style="color:#64748b;font-size:13px;">${docNumber} &mdash; ${today}</span>
        </div>
        <p><strong>Bill To:</strong> ${clientName} &lt;${clientEmail}&gt;</p>
        <table style="width:100%;border-collapse:collapse;margin-top:16px;">
          <thead><tr style="background:#f1f5f9;">
            <th style="padding:8px 12px;text-align:left;color:#64748b;font-size:11px;">DESCRIPTION</th>
            <th style="padding:8px;color:#64748b;font-size:11px;">QTY</th>
            <th style="padding:8px;color:#64748b;font-size:11px;">PRICE</th>
            <th style="padding:8px;color:#64748b;font-size:11px;">AMOUNT</th>
          </tr></thead>
          <tbody>
            ${extractedFields.items.map((item) => `<tr><td style="padding:10px 12px;border-bottom:1px solid #e2e8f0;">${item.description}</td><td style="padding:10px 8px;border-bottom:1px solid #e2e8f0;">${item.qty}</td><td style="padding:10px 8px;border-bottom:1px solid #e2e8f0;">${item.price}</td><td style="padding:10px 8px;border-bottom:1px solid #e2e8f0;">${item.qty * item.price}</td></tr>`).join('')}
          </tbody>
          <tfoot><tr style="font-weight:bold;border-top:2px solid ${accentColor};">
            <td colspan="3" style="padding:10px 12px;">Total</td>
            <td style="padding:10px 8px;color:${accentColor};">NGN ${total}</td>
          </tr></tfoot>
        </table>
        <div style="margin-top:32px;border-top:1px solid #e2e8f0;padding-top:12px;color:#94a3b8;font-size:11px;text-align:center;">${footerText}</div>
      </body></html>`;

      // Simulate email send
      const emailResult = sendEmail && clientEmail
        ? { id: `email-${Date.now()}`, accepted: [clientEmail], message: `Document dispatched to ${clientEmail} in demo mode` }
        : null;

      // Save content asset record
      const asset = createNewRecord('contentasset', {
        title: `${templateType} — ${clientName} — ${today}`,
        type: templateType,
        prompt,
        content: compiledHtml,
        provider: 'kimi',
        brandContext: { documentType: templateType },
        createdBy: demoAuthState.current._id,
      });

      return success({ extractedFields, compiledHtml, pdfFileName: null, emailResult, asset },
        `${templateType} generated${sendEmail ? ' and dispatched via email' : ''} successfully`);
    }

    if (method === 'post' && entity === 'automation/preview') {
      const rule = payload.jsonData?.rule || payload.jsonData || {};
      const event = db.automationevent.find((item) => item._id === payload.jsonData?.eventId) || db.automationevent[0];
      const matched = (rule.trigger?.eventType ? rule.trigger.eventType === event.type : true) &&
        (rule.conditions || []).every((condition) => {
          const val = condition.field === 'payload.amount' ? event.payload?.amount : condition.field === 'payload.score' ? event.payload?.score : event.payload?.status;
          if (condition.operator === 'gte') return Number(val) >= Number(condition.value);
          if (condition.operator === 'eq') return String(val) === String(condition.value);
          return true;
        });
      return success({
        matched,
        event,
        triggerSummary: `${rule.trigger?.type || 'event'}${rule.trigger?.eventType ? `: ${rule.trigger.eventType}` : ''}`,
        conditionSummaries: (rule.conditions || []).map((item) => `${item.field} ${item.operator} ${item.value}`),
        actionSummaries: (rule.actions || []).map((item) => item.type),
      }, 'Automation preview generated');
    }

    if (method === 'post' && entity === 'automation/run-due') {
      const run = createNewRecord('automationrun', {
        status: 'succeeded',
        rule: { _id: 'rule-demo', name: 'Demo queued rule' },
        event: { _id: 'event-demo', type: 'marketing.campaign.clicked' },
        actionResults: [{ type: 'create_task' }],
        error: '',
      });
      return success({
        processed: 1,
        results: [{ processed: true, job: 'job-demo', run: run._id, status: run.status }],
      }, 'Automation jobs processed');
    }

    if (method === 'post' && /integrationaccount\/secret\/.+$/.test(entity)) {
      const provider = entity.split('/').pop();
      const account = db.integrationaccount.find((item) => item.provider === provider);
      if (account) {
        account.secretConfigured = false;
        account.secretFields = [];
        account.secretPreview = [];
        account.status = 'disabled';
        account.lastTestStatus = 'untested';
        account.lastTestMessage = 'Secret removed from demo mode';
      }
      return success(account || {}, 'Integration secret removed');
    }

    if (method === 'create' && entity === 'integrationaccount') {
      const secretFields = Object.keys(payload.jsonData?.secretConfig || {});
      const record = createNewRecord(entity, {
        ...payload.jsonData,
        publicConfig: payload.jsonData?.publicConfig || {},
        secretConfigured: secretFields.length > 0,
        secretFields,
        secretPreview: secretFields.map((key) => ({ key, maskedValue: '********' })),
        lastTestStatus: 'untested',
        lastTestMessage: '',
        lastTestedAt: null,
      });
      return success(record, `${entity} record created`);
    }

    if (method === 'update' && entity === 'integrationaccount') {
      const collection = getCollection(entity);
      const index = collection.findIndex((item) => item._id === payload.id);
      const integrationSecretFields = Object.keys(payload.jsonData?.secretConfig || {});
      const nextRecord = {
        ...(index >= 0 ? collection[index] : {}),
        ...(payload.jsonData || {}),
        publicConfig: payload.jsonData?.publicConfig || (index >= 0 ? collection[index].publicConfig : {}) || {},
        ...(integrationSecretFields.length
          ? {
              secretConfigured: true,
              secretPreview: integrationSecretFields.map((key) => ({
                key,
                maskedValue: '********',
              })),
              secretFields: integrationSecretFields,
            }
          : {}),
        updated: iso(0, 1),
      };
      if (index >= 0) {
        collection[index] = nextRecord;
        return success(collection[index], `${entity} updated`);
      }
      const record = createNewRecord(entity, nextRecord);
      return success(record, `${entity} updated`);
    }

    if (method === 'create') {
      const record = createNewRecord(entity, payload.jsonData || {});
      return success(record, `${entity} record created`);
    }

    if (method === 'update') {
      const collection = getCollection(entity);
      const index = collection.findIndex((item) => item._id === payload.id);
      if (index >= 0) {
        collection[index] = {
          ...collection[index],
          ...(payload.jsonData || {}),
          ...(entity === 'integrationaccount' && payload.jsonData?.secretConfig
            ? {
                secretConfigured: true,
                secretPreview: Object.keys(payload.jsonData.secretConfig).map((key) => ({
                  key,
                  maskedValue: '••••••••',
                })),
                secretFields: Object.keys(payload.jsonData.secretConfig),
              }
            : {}),
          updated: iso(0, 1),
        };
        return success(collection[index], `${entity} updated`);
      }
      const record = createNewRecord(entity, payload.jsonData || {});
      return success(record, `${entity} updated`);
    }

    if (method === 'delete' && /integrationaccount\/secret\/.+$/.test(entity)) {
      const provider = entity.split('/').pop();
      const account = db.integrationaccount.find((item) => item.provider === provider);
      if (account) {
        account.secretConfigured = false;
        account.secretFields = [];
        account.secretPreview = [];
        account.status = 'disabled';
        account.lastTestStatus = 'untested';
        account.lastTestMessage = 'Secret removed from demo mode';
      }
      return success(account || {}, 'Integration secret removed');
    }

    if (method === 'delete') {
      return success({}, `${entity} removed`);
    }

    if (method === 'read') {
      const record = getCollection(entity).find((item) => item._id === payload.id);
      return success(record || null);
    }

    if (method === 'list' || method === 'listAll' || method === 'summary' || method === 'filter' || method === 'search') {
      return success(getCollection(entity));
    }

    if (method === 'patch' || method === 'upload' || method === 'mail' || method === 'convert') {
      return success(payload.jsonData || {}, 'Demo action completed');
    }

    return success([]);
  },
};
