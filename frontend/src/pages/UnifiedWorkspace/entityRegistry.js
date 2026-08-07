import { DEFAULT_CURRENCY_CODE, DEFAULT_CURRENCY_SYMBOL } from '@/constants/platformDefaults';

export const moduleRegistry = {
  crm: {
    title: 'CRM Workspace',
    description: 'Customers, people, companies, leads, deals, activities, communication history, and enterprise offer flows.',
    themeColor: '#4f46e5', // Solid indigo
    stats: [
      ['Total Entities', 9],
      ['Automation Status', 'Active'],
      ['Active Channels', 3],
    ],
    workflows: [
      { name: 'Capture inbound lead & qualify', link: '/lead' },
      { name: 'Manage active deal pipeline stages', link: '/deals' },
      { name: 'Generate and send commercial proposals', link: '/offers' },
    ],
    entities: [
      {
        entity: 'client',
        label: 'Customers',
        icon: 'UserOutlined',
        description: 'Primary customer profiles representing accounts and contract entities.',
        sample: { name: 'Northwind Retail', email: 'ops@northwind.test', lifecycleStage: 'customer' }
      },
      {
        entity: 'contact',
        label: 'People',
        icon: 'ContactsOutlined',
        description: 'Individual contact profiles linked to customer companies and vendors.',
        sample: { firstName: 'Ada', lastName: 'Cole', email: 'ada@example.com', lifecycleStage: 'lead' }
      },
      {
        entity: 'company',
        label: 'Companies',
        icon: 'ApartmentOutlined',
        description: 'Business accounts, organizations, and corporate partners.',
        sample: { name: 'Acme Growth Labs', type: 'customer', email: 'hello@acme.test' }
      },
      {
        entity: 'lead',
        label: 'Leads',
        icon: 'UserAddOutlined',
        description: 'Prospects and marketing inquiries currently undergoing qualification.',
        sample: { title: 'Enterprise CRM Upgrade Inquiry', source: 'landing_form', status: 'new' }
      },
      {
        entity: 'deal',
        label: 'Deals',
        icon: 'DollarOutlined',
        description: 'Sales opportunities moving through stage progression pipeline.',
        sample: { name: 'Custom ERP Implementation', status: 'open', value: { amount: 8500, currency: DEFAULT_CURRENCY_CODE } }
      },
      {
        entity: 'offer',
        label: 'Offers',
        icon: 'GiftOutlined',
        description: 'Proposals and pricing agreements drafted for prospects.',
        sample: { title: 'Q3 Integration Proposal', kind: 'lead_offer', status: 'draft' }
      },
      {
        entity: 'pipelinestage',
        label: 'Pipeline Stages',
        icon: 'OrderedListOutlined',
        description: 'Custom deal pipeline column configurations and probabilities.',
        sample: { name: 'Proposal Sent', order: 3, probability: 60 }
      },
      {
        entity: 'activity',
        label: 'Activities',
        icon: 'NotificationOutlined',
        description: 'Notes, calls, and actions logged by workspace operators.',
        sample: { type: 'call', subject: 'Requirement Discovery Session', body: 'Discussed CRM scale requirements.' }
      },
      {
        entity: 'communicationlog',
        label: 'Communication Logs',
        icon: 'MessageOutlined',
        description: 'Historical records of outbound and inbound contact touches.',
        sample: { channel: 'email', direction: 'outbound', subject: 'Following up on API access request' }
      },
    ],
  },
  sales: {
    title: 'Sales Workspace',
    description: 'Quotes, invoices, payments, offers, sales orders, approvals, and finance-linked commercial operations.',
    themeColor: '#10b981', // Solid emerald green
    stats: [
      ['Sales Entities', 7],
      ['Pending Approvals', '2 Required'],
      ['Forecast Status', 'Ready'],
    ],
    workflows: [
      { name: 'Convert approved quote to invoice', link: '/quote' },
      { name: 'Review and approve discount exceptions', link: '/records/approvalrequest' },
      { name: 'Issue payment requests and reminders', link: '/invoice' },
    ],
    entities: [
      {
        entity: 'offer',
        label: 'Offers / Proforma',
        icon: 'GiftOutlined',
        description: 'Commercial proforma quotes and proposals waiting conversion.',
        sample: { title: 'Q3 License Renewal Proforma', kind: 'proforma', status: 'draft' }
      },
      {
        entity: 'quote',
        label: 'Quotes',
        icon: 'FileTextOutlined',
        description: 'Formal price quotes generated for customer sign-off.',
        sample: { number: 1002, year: 2026, total: 2500, status: 'draft' }
      },
      {
        entity: 'invoice',
        label: 'Invoices',
        icon: 'FileDoneOutlined',
        description: 'Billed invoices tracking outstanding accounts receivable.',
        sample: { number: 2002, year: 2026, total: 1800, status: 'overdue' }
      },
      {
        entity: 'payment',
        label: 'Payments',
        icon: 'WalletOutlined',
        description: 'Customer transactions logged and matched to open invoices.',
        sample: { amount: 1500, currency: DEFAULT_CURRENCY_CODE, ref: 'TX-998271', reconciled: false }
      },
      {
        entity: 'salesorder',
        label: 'Sales Orders',
        icon: 'ShoppingCartOutlined',
        description: 'Customer purchase confirmations awaiting fulfillment and delivery.',
        sample: { number: 3004, status: 'confirmed', procurementNeeded: true }
      },
      {
        entity: 'salestarget',
        label: 'Sales Targets',
        icon: 'AimOutlined',
        description: 'Quota and performance benchmarks set for individual sales teams.',
        sample: { target: { amount: 50000, currency: DEFAULT_CURRENCY_CODE } }
      },
      {
        entity: 'approvalrequest',
        label: 'Approval Requests',
        icon: 'CheckSquareOutlined',
        description: 'Discount, credit, or policy exceptions requiring management approval.',
        sample: { title: '15% Off-list Discount Approval', status: 'pending', reason: 'Strategic account renewal' }
      },
    ],
  },
  inventory: {
    title: 'Catalog and Inventory',
    description: 'Products, categories, orders, purchases, suppliers, and fulfillment-facing inventory records.',
    themeColor: '#f59e0b', // Solid amber orange
    stats: [
      ['SKU Count', 6],
      ['Stock Tracking', 'Active'],
      ['Procurement Link', 'Synced'],
    ],
    workflows: [
      { name: 'Add new product specifications', link: '/products' },
      { name: 'Reorder items and generate purchase orders', link: '/purchases' },
      { name: 'Update vendor catalogs & onboarding status', link: '/suppliers' },
    ],
    entities: [
      {
        entity: 'product',
        label: 'Products',
        icon: 'BoxPlotOutlined',
        description: 'Catalog products, service packages, and stock items.',
        sample: { name: 'Premium Cloud Subscription', sku: 'CLD-PREM-01', stockQuantity: 45 }
      },
      {
        entity: 'productcategory',
        label: 'Product Categories',
        icon: 'AppstoreOutlined',
        description: 'Product taxonomies, tags, and classification groups.',
        sample: { name: 'SaaS Subscriptions', color: '#f59e0b' }
      },
      {
        entity: 'order',
        label: 'Orders',
        icon: 'ShoppingOutlined',
        description: 'Customer sales orders driving inventory depletion.',
        sample: { number: 'ORD-8871', status: 'confirmed' }
      },
      {
        entity: 'purchaseorder',
        label: 'Purchases',
        icon: 'AuditOutlined',
        description: 'Supplier purchases tracking incoming items and costs.',
        sample: { number: 4001, status: 'pending_approval', paymentTerms: 'Net 30' }
      },
      {
        entity: 'supplier',
        label: 'Suppliers',
        icon: 'TeamOutlined',
        description: 'Product suppliers, inventory partners, and manufacturers.',
        sample: { name: 'Global Logistics Co', email: 'procurement@globallogistics.test', status: 'active' }
      },
      {
        entity: 'vendor',
        label: 'Vendors',
        icon: 'ShopOutlined',
        description: 'Outsourced vendors and local service contractors.',
        sample: { name: 'Techno Systems Ltd', email: 'vendor@techno.test', onboardingStatus: 'active' }
      },
    ],
  },
  marketing: {
    title: 'Marketing Automation',
    description: 'Campaigns, audience segments, social posting, public forms, AI content, and lead-score feedback loops.',
    themeColor: '#e1306c', // Solid pinkish red
    stats: [
      ['Campaigns', 7],
      ['AI Engine', 'Enabled'],
      ['Channels Support', 5],
    ],
    workflows: [
      { name: 'Draft marketing content with AI', link: '/marketing' },
      { name: 'Create custom lead capture forms', link: '/forms' },
      { name: 'Publish & schedule social media campaigns', link: '/ai-studio/social' },
    ],
    entities: [
      {
        entity: 'campaign',
        label: 'Campaigns',
        icon: 'RocketOutlined',
        description: 'Email, Whatsapp, or multichannel marketing campaigns.',
        sample: { name: 'Summer Product Nurture', channel: 'email', status: 'draft' }
      },
      {
        entity: 'audiencesegment',
        label: 'Audience Segments',
        icon: 'TeamOutlined',
        description: 'Filtered customer segments based on actions or attributes.',
        sample: { name: 'Active Trial Inquiries', filters: [{ field: 'leadScore', operator: 'gte', value: 30 }] }
      },
      {
        entity: 'landingform',
        label: 'Landing Forms',
        icon: 'FormOutlined',
        description: 'Capture parameters and styles for integrated landing sites.',
        sample: { name: 'Webinar RSVP Form', slug: 'webinar-rsvp', fields: ['name', 'email'] }
      },
      {
        entity: 'publicform',
        label: 'Public Forms',
        icon: 'GlobalOutlined',
        description: 'Embeddable data capture forms saving contacts directly to CRM.',
        sample: { name: 'Contact Sales Inbound', slug: 'contact-sales', targetEntity: 'lead' }
      },
      {
        entity: 'campaignevent',
        label: 'Campaign Events',
        icon: 'NotificationOutlined',
        description: 'Interactions logged from campaigns (clicks, opens, unsubscribes).',
        sample: { type: 'click', metadata: { source: 'newsletter' } }
      },
      {
        entity: 'socialpost',
        label: 'Social Posts',
        icon: 'ShareAltOutlined',
        description: 'Scheduled posts for Facebook, Instagram, or LinkedIn.',
        sample: { provider: 'instagram', caption: 'Scaling businesses with AI', status: 'scheduled' }
      },
      {
        entity: 'contentasset',
        label: 'Content Assets',
        icon: 'FileTextOutlined',
        description: 'AI drafts, copywriting concepts, and media assets.',
        sample: { title: 'Q3 Launch Email Newsletter Body', type: 'email', prompt: 'Announce integration release' }
      },
    ],
  },
  finance: {
    title: 'Finance Management',
    description: 'Accounts, invoices, payments, expenses, taxes, payment modes, currencies, and document output settings.',
    themeColor: '#8b5cf6', // Solid violet purple
    stats: [
      ['Accounts Count', 11],
      ['Currencies', 'Multi-currency'],
      ['Compliance Logs', 'Enabled'],
    ],
    workflows: [
      { name: 'Reconcile outstanding payments', link: '/dashboard' },
      { name: 'Log operating expense records', link: '/dashboard' },
      { name: 'Configure regional tax profiles', link: '/taxes' },
    ],
    entities: [
      {
        entity: 'account',
        label: 'Accounts',
        icon: 'BankOutlined',
        description: 'General ledger charts, bank accounts, and asset codes.',
        sample: { code: '4000', name: 'Software Sales Revenue', type: 'income', currency: DEFAULT_CURRENCY_CODE }
      },
      {
        entity: 'ledgerentry',
        label: 'Ledger Entries',
        icon: 'OrderedListOutlined',
        description: 'Double-entry bookkeeping transactions recorded.',
        sample: { description: 'Sales Invoice Posted', lines: [] }
      },
      {
        entity: 'invoice',
        label: 'Invoices',
        icon: 'FileDoneOutlined',
        description: 'Customer invoices representing revenue generated.',
        sample: { number: 1005, year: 2026, total: 3200, status: 'paid' }
      },
      {
        entity: 'payment',
        label: 'Payments',
        icon: 'WalletOutlined',
        description: 'Customer payments mapped to invoices or ledger items.',
        sample: { amount: 3200, currency: DEFAULT_CURRENCY_CODE, ref: 'RECON-88', reconciled: true }
      },
      {
        entity: 'expense',
        label: 'Expenses',
        icon: 'CalculatorOutlined',
        description: 'Operating expenses, purchases, and utilities bills.',
        sample: { title: 'Social Platform Marketing Spend', amount: { amount: 450, currency: DEFAULT_CURRENCY_CODE }, status: 'paid' }
      },
      {
        entity: 'expensecategory',
        label: 'Expense Categories',
        icon: 'FolderOpenOutlined',
        description: 'Operating expense taxonomy codes.',
        sample: { name: 'Marketing & Ads', code: 'MKT-AD' }
      },
      {
        entity: 'bill',
        label: 'Bills',
        icon: 'AuditOutlined',
        description: 'Supplier invoices received tracking outgoing payments.',
        sample: { number: 'BILL-0021', amount: { amount: 1200, currency: DEFAULT_CURRENCY_CODE }, status: 'draft' }
      },
      {
        entity: 'budget',
        label: 'Budgets',
        icon: 'DashboardOutlined',
        description: 'Departmental budgets tracking allocated vs actual costs.',
        sample: { name: 'Operations Q3 Budget', planned: { amount: 15000, currency: DEFAULT_CURRENCY_CODE } }
      },
      {
        entity: 'currency',
        label: 'Currencies',
        icon: 'GlobalOutlined',
        description: 'Active operating and reporting currencies.',
        sample: { code: DEFAULT_CURRENCY_CODE, name: 'Nigerian Naira', symbol: DEFAULT_CURRENCY_SYMBOL, exchangeRate: 1 }
      },
      {
        entity: 'paymentmode',
        label: 'Payment Modes',
        icon: 'CreditCardOutlined',
        description: 'Customer-facing receipt channels (Bank, Stripe, Cash).',
        sample: { name: 'Bank Transfer Routing', isDefault: true }
      },
      {
        entity: 'taxes',
        label: 'Taxes',
        icon: 'PercentageOutlined',
        description: 'Country-specific sales, VAT, or withholding taxes.',
        sample: { taxName: 'VAT 7.5%', taxValue: 7.5, isDefault: true }
      },
      {
        entity: 'pdfsetting',
        label: 'PDF Settings',
        icon: 'SettingOutlined',
        description: 'Layout settings, logos, and signatures for invoices and quotes.',
        sample: { moduleKey: 'invoice', footerText: 'Thank you for choosing Acme Growth Labs' }
      },
    ],
  },
  operations: {
    title: 'Operations Workspace',
    description: 'Branches, company workspaces, booking, appointments, documents, and contracts across the tenant.',
    themeColor: '#06b6d4', // Solid cyan teal
    stats: [
      ['Operational Nodes', 6],
      ['Scheduling Mode', 'Active'],
      ['Document Controls', 'Secure'],
    ],
    workflows: [
      { name: 'Schedule customer appointments & meetings', link: '/appointment' },
      { name: 'Configure operational branches & locations', link: '/branches' },
      { name: 'Review and signs business contracts', link: '/contracts' },
    ],
    entities: [
      {
        entity: 'branch',
        label: 'Branches',
        icon: 'EnvironmentOutlined',
        description: 'Corporate branches, warehouses, or local offices.',
        sample: { name: 'Lagos Headquarters', code: 'LOS-HQ' }
      },
      {
        entity: 'companyworkspace',
        label: 'Company Workspaces',
        icon: 'AppstoreOutlined',
        description: 'Internal workspaces grouping branches and settings.',
        sample: { name: 'Core Corporate Workspace', code: 'CORE', defaultCurrency: DEFAULT_CURRENCY_CODE }
      },
      {
        entity: 'calendarbooking',
        label: 'Bookings',
        icon: 'CalendarOutlined',
        description: 'Meeting or resource bookings scheduled by stakeholders.',
        sample: { inviteeName: 'Ada Cole', inviteeEmail: 'ada@example.com', status: 'requested' }
      },
      {
        entity: 'appointment',
        label: 'Appointments',
        icon: 'ScheduleOutlined',
        description: 'Customer-facing calendar calls and events.',
        sample: { title: 'Product Integration Onboarding Discovery', startsAt: new Date().toISOString(), status: 'scheduled' }
      },
      {
        entity: 'document',
        label: 'Documents',
        icon: 'FileProtectOutlined',
        description: 'Company documentation, requirements sheets, or compliance records.',
        sample: { title: 'Product Specification Blueprint v2', type: 'specification', status: 'active' }
      },
      {
        entity: 'contract',
        label: 'Contracts',
        icon: 'SafetyCertificateOutlined',
        description: 'Service level, employment, or corporate business contracts.',
        sample: { title: 'Standard Service Retainer Agreement', status: 'active' }
      },
    ],
  },
  vendors: {
    title: 'Vendor Management',
    description: 'Onboarding, contracts, purchase orders, approval chains, terms, and performance scorecards.',
    themeColor: '#ec4899', // Solid pink
    stats: [
      ['Vendors Tracked', 4],
      ['Approvals Queue', 'Active'],
      ['Quality Checks', 'Enabled'],
    ],
    workflows: [
      { name: 'Setup vendor profile & catalog', link: '/vendors' },
      { name: 'Generate procurement purchase orders', link: '/vendors' },
      { name: 'Complete vendor performance evaluations', link: '/vendors' },
    ],
    entities: [
      {
        entity: 'vendor',
        label: 'Vendors',
        icon: 'ShopOutlined',
        description: 'Outsourced vendors, suppliers, and service providers.',
        sample: { name: 'Techno Systems Ltd', email: 'vendor@techno.test', onboardingStatus: 'active' }
      },
      {
        entity: 'vendorcontract',
        label: 'Vendor Contracts',
        icon: 'SafetyCertificateOutlined',
        description: 'Commercial vendor agreements, terms, and pricing.',
        sample: { title: 'Annual IT Support SLA', status: 'active' }
      },
      {
        entity: 'purchaseorder',
        label: 'Purchase Orders',
        icon: 'AuditOutlined',
        description: 'Supplier orders tracking procurement requirements.',
        sample: { number: 5001, status: 'approved', paymentTerms: 'Net 30' }
      },
      {
        entity: 'vendorscorecard',
        label: 'Vendor Scorecards',
        icon: 'AimOutlined',
        description: 'Vendor performance reports tracking quality and timing.',
        sample: { qualityScore: 85, deliveryScore: 90, costScore: 80 }
      },
    ],
  },
  platform: {
    title: 'Platform Console',
    description: 'Reports, forms, API keys, automation, access control, integrations, and AI-assisted operations.',
    themeColor: '#64748b', // Solid slate gray
    stats: [
      ['System Elements', 8],
      ['Engine Status', 'Online'],
      ['API Interface', 'Active'],
    ],
    workflows: [
      { name: 'Manage event-based automation rules', link: '/automations' },
      { name: 'Generate developer API credentials', link: '/developer/api-keys' },
      { name: 'Monitor active integration accounts', link: '/ai-studio/providers' },
    ],
    entities: [
      {
        entity: 'apikey',
        label: 'Developer API Keys',
        icon: 'KeyOutlined',
        description: 'Developer credentials managing custom integrations.',
        sample: { name: 'Platform Webhook Sync API', keyPreview: 'pk_live_8871ab', scopes: ['crm.contact.read'] }
      },
      {
        entity: 'automationrule',
        label: 'Automation Rules',
        icon: 'ThunderboltOutlined',
        description: 'Rules governing automated actions based on business events.',
        sample: { name: 'Deal Won Billing Trigger', trigger: { type: 'event', eventType: 'crm.deal.won' }, actions: [] }
      },
      {
        entity: 'automationevent',
        label: 'Automation Events',
        icon: 'NotificationOutlined',
        description: 'Events generated across business modules.',
        sample: { type: 'crm.deal.won', sourceModule: 'crm', payload: {} }
      },
      {
        entity: 'automationrun',
        label: 'Automation Runs',
        icon: 'HistoryOutlined',
        description: 'Chronological logs of automation rule execution.',
        sample: { status: 'succeeded', actionResults: [{ type: 'create_task' }] }
      },
      {
        entity: 'job',
        label: 'Jobs',
        icon: 'OrderedListOutlined',
        description: 'Async queues tracking pending automation jobs.',
        sample: { type: 'automation.run', payload: {}, status: 'queued' }
      },
      {
        entity: 'integrationaccount',
        label: 'Integration Accounts',
        icon: 'ApiOutlined',
        description: 'Third-party accounts (Meta, Resend, OpenAI) connected.',
        sample: { provider: 'resend', name: 'Resend Email Gateway', status: 'active' }
      },
      {
        entity: 'role',
        label: 'Roles',
        icon: 'LockOutlined',
        description: 'Access roles assigning entity-specific permissions.',
        sample: { name: 'Accountant Rep', key: 'accountant_rep', permissions: ['finance.invoice.read'] }
      },
      {
        entity: 'permission',
        label: 'Permissions',
        icon: 'SafetyOutlined',
        description: 'Access permissions map linked to roles and actions.',
        sample: { key: 'finance.invoice.read', module: 'finance', action: 'read' }
      },
    ],
  },
};

export const allEntityConfigs = Object.values(moduleRegistry)
  .flatMap((module) => module.entities)
  .reduce((acc, config) => {
    acc[config.entity] = config;
    return acc;
  }, {});
