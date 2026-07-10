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
    createRecord('payment', { number: 'PAY-3001', status: 'paid', amount: 3200, date: iso(-2) }),
    createRecord('payment', { number: 'PAY-3002', status: 'pending', amount: 900, date: iso(-1) }),
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
        reconciledCount: 1,
        unreconciledCount: Math.max(0, payments.length - 1),
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
      paymentsRecorded: 5,
      reconciledPayments: 1,
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

    if (method === 'create') {
      const record = createNewRecord(entity, payload.jsonData || {});
      return success(record, `${entity} record created`);
    }

    if (method === 'update') {
      const collection = getCollection(entity);
      const index = collection.findIndex((item) => item._id === payload.id);
      if (index >= 0) {
        collection[index] = { ...collection[index], ...(payload.jsonData || {}), updated: iso(0, 1) };
        return success(collection[index], `${entity} updated`);
      }
      const record = createNewRecord(entity, payload.jsonData || {});
      return success(record, `${entity} updated`);
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
