import { DEFAULT_CURRENCY_CODE, DEFAULT_CURRENCY_SYMBOL } from '@/constants/platformDefaults';

export const moduleRegistry = {
  crm: {
    title: 'CRM Workspace',
    description: 'Customers, people, companies, leads, deals, activities, communication history, and enterprise offer flows.',
    stats: [
      ['Entities', 9],
      ['Automation Ready', 'Yes'],
      ['Timeline Channels', 3],
    ],
    workflows: [
      'Lead capture to CRM qualification',
      'Deal pipeline with stage automation',
      'Customer and lead offer generation',
    ],
    entities: [
      { entity: 'client', label: 'Customers', sample: { name: 'Acme Ltd', email: 'billing@acme.test', lifecycleStage: 'customer' } },
      { entity: 'contact', label: 'People', sample: { firstName: 'Ada', lastName: 'Cole', email: 'ada@example.com', lifecycleStage: 'lead' } },
      { entity: 'company', label: 'Companies', sample: { name: 'Acme Ltd', type: 'customer', email: 'hello@acme.test' } },
      { entity: 'lead', label: 'Leads', sample: { title: 'Website enquiry', source: 'landing_page', status: 'new' } },
      { entity: 'deal', label: 'Deals', sample: { name: 'New website project', status: 'open', value: { amount: 2500, currency: DEFAULT_CURRENCY_CODE } } },
      { entity: 'offer', label: 'Offers', sample: { title: 'Lead proposal', kind: 'lead_offer', status: 'draft' } },
      { entity: 'pipelinestage', label: 'Pipeline Stages', sample: { name: 'Qualified', order: 2, probability: 40 } },
      { entity: 'activity', label: 'Activities', sample: { type: 'note', subject: 'Discovery call', body: 'Customer needs automation.' } },
      { entity: 'communicationlog', label: 'Communication Logs', sample: { channel: 'email', direction: 'outbound', subject: 'Follow up' } },
    ],
  },
  sales: {
    title: 'Sales Workspace',
    description: 'Quotes, invoices, payments, offers, sales orders, approvals, and finance-linked commercial operations.',
    stats: [
      ['Entities', 7],
      ['Approvals', 2],
      ['Forecast Ready', 'Yes'],
    ],
    workflows: [
      'Quote and offer to invoice conversion',
      'Discount approval chains',
      'Deal won to order and invoice handoff',
    ],
    entities: [
      { entity: 'offer', label: 'Offers / Proforma', sample: { title: 'Q3 renewal proposal', kind: 'proforma', status: 'draft' } },
      { entity: 'quote', label: 'Quotes', sample: { number: 1, year: 2026, total: 1000, status: 'draft' } },
      { entity: 'invoice', label: 'Invoices', sample: { number: 1, year: 2026, total: 1000, status: 'draft' } },
      { entity: 'payment', label: 'Payments', sample: { amount: { amount: 1000, currency: DEFAULT_CURRENCY_CODE }, status: 'pending' } },
      { entity: 'salesorder', label: 'Sales Orders', sample: { number: 1, status: 'draft', procurementNeeded: false } },
      { entity: 'salestarget', label: 'Sales Targets', sample: { target: { amount: 10000, currency: DEFAULT_CURRENCY_CODE } } },
      { entity: 'approvalrequest', label: 'Approval Requests', sample: { title: 'Discount approval', status: 'pending', reason: 'Above threshold' } },
    ],
  },
  inventory: {
    title: 'Catalog and Inventory',
    description: 'Products, categories, orders, purchases, suppliers, and fulfillment-facing inventory records.',
    stats: [
      ['Entities', 6],
      ['Stock Tracking', 'On'],
      ['Procurement', 'Linked'],
    ],
    workflows: [
      'Product setup and categorization',
      'Order to purchase fulfillment handoff',
      'Supplier-backed procurement tracking',
    ],
    entities: [
      { entity: 'product', label: 'Products', sample: { name: 'Starter Package', sku: 'SKU-001', stockQuantity: 15 } },
      { entity: 'productcategory', label: 'Product Categories', sample: { name: 'Services', color: '#1677ff' } },
      { entity: 'order', label: 'Orders', sample: { number: 'ORD-001', status: 'confirmed' } },
      { entity: 'purchaseorder', label: 'Purchases', sample: { number: 1, status: 'draft', paymentTerms: 'Net 30' } },
      { entity: 'supplier', label: 'Suppliers', sample: { name: 'Supply Partner', email: 'ops@supplier.test', status: 'active' } },
      { entity: 'vendor', label: 'Vendors', sample: { name: 'Vendor Partner', email: 'vendor@example.test', onboardingStatus: 'draft' } },
    ],
  },
  marketing: {
    title: 'Marketing Automation',
    description: 'Campaigns, audience segments, social posting, public forms, AI content, and lead-score feedback loops.',
    stats: [
      ['Entities', 7],
      ['AI Actions', 3],
      ['Channels', 5],
    ],
    workflows: [
      'Landing and public form capture into CRM',
      'Campaign analytics to lead score',
      'Social autopost scheduling',
    ],
    entities: [
      { entity: 'campaign', label: 'Campaigns', sample: { name: 'July nurture', channel: 'email', status: 'draft' } },
      { entity: 'audiencesegment', label: 'Audience Segments', sample: { name: 'Warm leads', filters: [{ field: 'leadScore', operator: 'gte', value: 20 }] } },
      { entity: 'landingform', label: 'Landing Forms', sample: { name: 'Demo request', slug: 'demo-request', fields: ['name', 'email'] } },
      { entity: 'publicform', label: 'Public Forms', sample: { name: 'Lead capture', slug: 'lead-capture', targetEntity: 'lead' } },
      { entity: 'campaignevent', label: 'Campaign Events', sample: { type: 'open', metadata: { source: 'email' } } },
      { entity: 'socialpost', label: 'Social Posts', sample: { provider: 'facebook', caption: 'Launch week starts now', status: 'draft' } },
      { entity: 'contentasset', label: 'Content Assets', sample: { title: 'Launch email', type: 'email', prompt: 'Write launch copy' } },
    ],
  },
  finance: {
    title: 'Finance and Settings',
    description: 'Accounts, invoices, payments, expenses, taxes, payment modes, currencies, and document output settings.',
    stats: [
      ['Entities', 11],
      ['Currencies', 'Multi'],
      ['Audit', 'On'],
    ],
    workflows: [
      'Invoice payment reconciliation',
      'Expense categorization and budget tracking',
      'PDF footer and regional tax configuration',
    ],
    entities: [
      { entity: 'account', label: 'Accounts', sample: { code: '4000', name: 'Sales Revenue', type: 'income', currency: DEFAULT_CURRENCY_CODE } },
      { entity: 'ledgerentry', label: 'Ledger Entries', sample: { description: 'Invoice posted', lines: [] } },
      { entity: 'invoice', label: 'Invoices', sample: { number: 1, year: 2026, total: 1000, status: 'draft' } },
      { entity: 'payment', label: 'Payments', sample: { amount: { amount: 250, currency: DEFAULT_CURRENCY_CODE }, status: 'pending' } },
      { entity: 'expense', label: 'Expenses', sample: { title: 'Ad spend', amount: { amount: 250, currency: DEFAULT_CURRENCY_CODE }, status: 'draft' } },
      { entity: 'expensecategory', label: 'Expense Categories', sample: { name: 'Marketing', code: 'MKT' } },
      { entity: 'bill', label: 'Bills', sample: { number: 'BILL-001', amount: { amount: 500, currency: DEFAULT_CURRENCY_CODE }, status: 'draft' } },
      { entity: 'budget', label: 'Budgets', sample: { name: 'Marketing Q3', planned: { amount: 5000, currency: DEFAULT_CURRENCY_CODE } } },
      { entity: 'currency', label: 'Currencies', sample: { code: DEFAULT_CURRENCY_CODE, name: 'Nigerian Naira', symbol: DEFAULT_CURRENCY_SYMBOL, exchangeRate: 1 } },
      { entity: 'paymentmode', label: 'Payment Modes', sample: { name: 'Bank Transfer', isDefault: true } },
      { entity: 'taxes', label: 'Taxes', sample: { taxName: 'Tax 0%', taxValue: 0, isDefault: true } },
      { entity: 'pdfsetting', label: 'PDF Settings', sample: { moduleKey: 'invoice', footerText: 'Thank you for your business.' } },
    ],
  },
  operations: {
    title: 'Operations Workspace',
    description: 'Branches, company workspaces, booking, appointments, documents, and contracts across the tenant.',
    stats: [
      ['Entities', 6],
      ['Scheduling', 'Enabled'],
      ['Documents', 'Tracked'],
    ],
    workflows: [
      'Branch and workspace setup',
      'Booking and appointment scheduling',
      'Document and contract lifecycle tracking',
    ],
    entities: [
      { entity: 'branch', label: 'Branches', sample: { name: 'Lagos HQ', code: 'LOS-HQ' } },
      { entity: 'companyworkspace', label: 'Company Workspaces', sample: { name: 'Core Workspace', code: 'CORE', defaultCurrency: DEFAULT_CURRENCY_CODE } },
      { entity: 'calendarbooking', label: 'Bookings', sample: { inviteeName: 'Ada Cole', inviteeEmail: 'ada@example.com', status: 'requested' } },
      { entity: 'appointment', label: 'Appointments', sample: { title: 'Demo call', startsAt: new Date().toISOString(), status: 'scheduled' } },
      { entity: 'document', label: 'Documents', sample: { title: 'Requirements brief', type: 'brief', status: 'active' } },
      { entity: 'contract', label: 'Contracts', sample: { title: 'Annual retainer', status: 'active' } },
    ],
  },
  vendors: {
    title: 'Vendor Management',
    description: 'Onboarding, contracts, purchase orders, approval chains, terms, and performance scorecards.',
    stats: [
      ['Entities', 4],
      ['Approvals', 1],
      ['Scorecards', 1],
    ],
    workflows: [
      'Procurement from won deals',
      'Purchase order approvals',
      'Vendor performance review',
    ],
    entities: [
      { entity: 'vendor', label: 'Vendors', sample: { name: 'Supply Partner', email: 'ops@supplier.test', onboardingStatus: 'draft' } },
      { entity: 'vendorcontract', label: 'Vendor Contracts', sample: { title: 'Annual supply agreement', status: 'draft' } },
      { entity: 'purchaseorder', label: 'Purchase Orders', sample: { number: 1, status: 'draft', paymentTerms: 'Net 30' } },
      { entity: 'vendorscorecard', label: 'Vendor Scorecards', sample: { qualityScore: 80, deliveryScore: 75, costScore: 70 } },
    ],
  },
  platform: {
    title: 'Platform Console',
    description: 'Reports, forms, API keys, automation, access control, integrations, and AI-assisted operations.',
    stats: [
      ['Entities', 8],
      ['Automation', 'Shared'],
      ['API First', 'Yes'],
    ],
    workflows: [
      'Automation rule and run management',
      'Public forms and API key administration',
      'Access control and AI-assisted operator tasks',
    ],
    entities: [
      { entity: 'apikey', label: 'Developer API Keys', sample: { name: 'Partner integration', keyPreview: 'pk_live_xxxx', scopes: ['crm.contact.read'] } },
      { entity: 'automationrule', label: 'Automation Rules', sample: { name: 'Deal won handoff', trigger: { type: 'event', eventType: 'crm.deal.won' }, actions: [] } },
      { entity: 'automationevent', label: 'Automation Events', sample: { type: 'crm.deal.won', sourceModule: 'crm', payload: {} } },
      { entity: 'automationrun', label: 'Automation Runs', sample: { status: 'queued', actionResults: [] } },
      { entity: 'job', label: 'Jobs', sample: { type: 'automation.run', payload: {}, status: 'queued' } },
      { entity: 'integrationaccount', label: 'Integration Accounts', sample: { provider: 'resend', name: 'Resend', status: 'disabled' } },
      { entity: 'role', label: 'Roles', sample: { name: 'Sales Rep', key: 'sales_rep', permissions: ['crm.deal.read'] } },
      { entity: 'permission', label: 'Permissions', sample: { key: 'crm.deal.read', module: 'crm', action: 'read' } },
    ],
  },
  tasks: {
    title: 'Tasks, Reminders and Booking',
    description: 'Task management, reminders, appointments, and calendar booking linked to any module record.',
    stats: [
      ['Entities', 4],
      ['Reminders', 'Multi-channel'],
      ['Booking', 'Enabled'],
    ],
    workflows: [
      'Follow-up task creation',
      'Appointment booking',
      'Reminder notifications',
    ],
    entities: [
      { entity: 'task', label: 'Tasks', sample: { title: 'Follow up lead', status: 'todo', priority: 'normal' } },
      { entity: 'reminder', label: 'Reminders', sample: { title: 'Call back', remindAt: new Date().toISOString(), channel: 'in_app' } },
      { entity: 'appointment', label: 'Appointments', sample: { title: 'Demo call', startsAt: new Date().toISOString(), status: 'scheduled' } },
      { entity: 'calendarbooking', label: 'Calendar Bookings', sample: { inviteeName: 'Ada Cole', inviteeEmail: 'ada@example.com', status: 'requested' } },
    ],
  },
  ai: {
    title: 'AI Studio',
    description: 'Generate newsletters, email marketing, blogs, captions, flyer copy, scripts, and brand assets.',
    stats: [
      ['Providers', 4],
      ['Content Types', 7],
      ['Mock Mode', 'On'],
    ],
    workflows: [
      'Kimi text generation',
      'Fal.ai brand asset generation',
      'Hermes orchestration actions',
    ],
    entities: [
      { entity: 'contentasset', label: 'Content Assets', sample: { title: 'Flyer copy', type: 'flyer_copy', prompt: 'Write copy for a launch flyer' } },
      { entity: 'integrationaccount', label: 'Integration Accounts', sample: { provider: 'kimi', name: 'Kimi', status: 'disabled' } },
    ],
  },
};

export const allEntityConfigs = Object.values(moduleRegistry)
  .flatMap((module) => module.entities)
  .reduce((acc, config) => {
    acc[config.entity] = config;
    return acc;
  }, {});
