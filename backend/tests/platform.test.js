require('module-alias/register');
const test = require('node:test');
const assert = require('node:assert/strict');

const {
  buildTenantQuery,
  canAccessAction,
  maskRecordFields,
  permissionFor,
  computeEffectiveAccess,
} = require('../src/services/platform/accessControl');
const { eventMatchesRule } = require('../src/services/automation/ruleMatcher');
const { createProviderRegistry } = require('../src/services/integrations/providerRegistry');
const { interpolateTemplate } = require('../src/services/automation/template');
const { executeAutomationAction } = require('../src/services/automation/actionExecutor');
const { processAutomationJob } = require('../src/services/automation/runner');
const {
  convertOfferToInvoice,
  transitionEntityStatus,
  submitPublicForm,
  getPublishedPublicForm,
  recordInvoicePayment,
  reconcilePaymentRecord,
  buildEnterpriseOverviewReport,
} = require('../src/services/platform/enterpriseWorkflows');
const {
  buildMarketingWorkspaceSummary,
  listAiWorkspaceAssets,
  previewAutomationRule,
  buildAutomationWorkspaceSummary,
  launchCampaignWorkflow,
} = require('../src/services/platform/growthWorkspaces');
const {
  buildPermissionCatalog,
  seedTenantAccessControl,
} = require('../src/services/platform/defaultAccessControl');
const accessControlController = require('../src/controllers/platformControllers/accessControlController');
const {
  buildProviderCatalogForUi,
  resolveProviderEnvForTenant,
  sanitizeIntegrationAccount,
} = require('../src/services/integrations/accountService');
const { encryptJson } = require('../src/services/integrations/secretStore');

test('buildTenantQuery scopes tenant-aware models to the current tenant', () => {
  const Model = {
    schema: {
      path: (field) => (field === 'tenant' ? true : null),
    },
  };

  assert.deepEqual(buildTenantQuery(Model, { tenantId: 'tenant-a' }), { tenant: 'tenant-a' });
});

test('buildTenantQuery leaves global models unscoped', () => {
  const Model = {
    schema: {
      path: () => null,
    },
  };

  assert.deepEqual(buildTenantQuery(Model, { tenantId: 'tenant-a' }), {});
});

test('canAccessAction allows admins and blocks roles without explicit permission', () => {
  assert.equal(canAccessAction({ role: 'owner' }, 'finance.ledger.read'), true);
  assert.equal(
    canAccessAction({ role: 'sales_rep', permissions: ['crm.contact.read'] }, 'finance.ledger.read'),
    false
  );
  assert.equal(
    canAccessAction({ role: 'sales_rep', permissions: ['crm.contact.read'] }, 'crm.contact.read'),
    true
  );
});

test('permissionFor maps entities into namespaced module permissions', () => {
  assert.equal(permissionFor('deal', 'update'), 'crm.deal.update');
  assert.equal(permissionFor('invoice', 'read'), 'finance.invoice.read');
  assert.equal(permissionFor('automation', 'create'), 'automations.runner.create');
  assert.equal(permissionFor('ai', 'create'), 'ai.studio.create');
  assert.equal(permissionFor('role', 'read'), 'settings.role.read');
  assert.equal(permissionFor('product', 'read'), 'inventory.product.read');
  assert.equal(permissionFor('supplier', 'create'), 'inventory.supplier.create');
  assert.equal(permissionFor('branch', 'read'), 'operations.branch.read');
  assert.equal(permissionFor('apikey', 'read'), 'platform.apikey.read');
});

test('buildPermissionCatalog includes workflow, AI, and settings permissions', () => {
  const permissions = buildPermissionCatalog();
  const keys = permissions.map((permission) => permission.key);

  assert.ok(keys.includes('crm.deal.read'));
  assert.ok(keys.includes('finance.invoice.update'));
  assert.ok(keys.includes('automations.workflow.create'));
  assert.ok(keys.includes('ai.studio.create'));
  assert.ok(keys.includes('settings.role.read'));
  assert.ok(keys.includes('inventory.product.read'));
  assert.ok(keys.includes('operations.branch.read'));
  assert.ok(keys.includes('platform.apikey.read'));
});

test('seedTenantAccessControl upserts permission catalog and starter roles', async () => {
  const permissionWrites = [];
  const roleWrites = [];

  const result = await seedTenantAccessControl({
    tenantId: 'tenant-1',
    actorId: 'admin-1',
    models: {
      Permission: {
        findOneAndUpdate: async (filter, update) => {
          permissionWrites.push({ filter, update });
          return { _id: filter.key, ...update.$set };
        },
      },
      Role: {
        findOneAndUpdate: async (filter, update) => {
          roleWrites.push({ filter, update });
          return { _id: filter.key, ...update.$set };
        },
      },
    },
  });

  assert.ok(permissionWrites.length > 20);
  assert.equal(result.roles.length, 6);
  assert.equal(result.permissions.length, permissionWrites.length);

  const adminRole = roleWrites.find(({ filter }) => filter.key === 'admin');
  const salesRepRole = roleWrites.find(({ filter }) => filter.key === 'sales_rep');
  const financeOfficerRole = roleWrites.find(({ filter }) => filter.key === 'finance_officer');

  assert.deepEqual(adminRole.update.$set.permissions, ['*']);
  assert.ok(salesRepRole.update.$set.permissions.includes('crm.deal.read'));
  assert.ok(salesRepRole.update.$set.permissions.includes('sales.quote.create'));
  assert.ok(!salesRepRole.update.$set.permissions.includes('finance.ledgerentry.read'));
  assert.ok(financeOfficerRole.update.$set.permissions.includes('finance.invoice.read'));
  assert.deepEqual(salesRepRole.update.$set.fieldPermissions, [
    { entity: 'deal', deniedFields: ['margin', 'commissionAmount'] },
    { entity: 'client', deniedFields: ['bankDetails', 'taxId'] },
  ]);
});

test('computeEffectiveAccess merges role and direct permissions without duplicates', () => {
  const effective = computeEffectiveAccess({
    permissions: ['crm.contact.read', 'sales.quote.create'],
    deniedFields: ['bankDetails'],
    fieldPermissions: [{ entity: 'invoice', deniedFields: ['bankAccount'] }],
    roleRefs: [
      {
        permissions: ['crm.contact.read', 'crm.deal.read'],
        fieldPermissions: [{ entity: 'deal', deniedFields: ['margin'] }],
      },
      {
        permissions: ['finance.invoice.read'],
        fieldPermissions: [{ entity: 'client', deniedFields: ['taxId'] }],
      },
    ],
  });

  assert.deepEqual(effective.permissions, [
    'crm.contact.read',
    'sales.quote.create',
    'crm.deal.read',
    'finance.invoice.read',
  ]);
  assert.deepEqual(effective.deniedFields, ['bankDetails', 'margin', 'taxId']);
  assert.deepEqual(effective.fieldPermissions, [
    { entity: 'invoice', deniedFields: ['bankAccount'] },
    { entity: 'deal', deniedFields: ['margin'] },
    { entity: 'client', deniedFields: ['taxId'] },
  ]);
});

test('bootstrapDefaults returns role summaries for the access control console', async () => {
  const req = {
    tenantId: 'tenant-1',
    admin: { _id: 'admin-1' },
  };
  const payload = {};
  const res = {
    status(code) {
      this.statusCode = code;
      return this;
    },
    json(body) {
      payload.body = body;
      return body;
    },
  };

  const originalSeed = accessControlController.__test.seedTenantAccessControl;
  accessControlController.__test.seedTenantAccessControl = async () => ({
    permissions: [{ _id: 'p1' }, { _id: 'p2' }],
    roles: [
      { key: 'admin', name: 'Admin', dataScope: 'tenant', permissions: ['*'] },
      {
        key: 'sales_rep',
        name: 'Sales Rep',
        dataScope: 'own',
        permissions: ['crm.deal.read', 'sales.quote.create'],
      },
    ],
  });

  await accessControlController.bootstrapDefaults(req, res);

  assert.equal(res.statusCode, 200);
  assert.equal(payload.body.result.permissions, 2);
  assert.equal(payload.body.result.roles.length, 2);
  assert.deepEqual(payload.body.result.roles[1], {
    key: 'sales_rep',
    name: 'Sales Rep',
    dataScope: 'own',
    permissionCount: 2,
  });

  accessControlController.__test.seedTenantAccessControl = originalSeed;
});

test('listAdminAccess returns role assignments with effective permission summaries', async () => {
  const req = {
    tenantId: 'tenant-1',
  };
  const payload = {};
  const res = {
    status(code) {
      this.statusCode = code;
      return this;
    },
    json(body) {
      payload.body = body;
      return body;
    },
  };

  const originalFind = accessControlController.__test.findAdminsWithAccess;
  accessControlController.__test.findAdminsWithAccess = async () => [
    {
      _id: 'admin-1',
      name: 'Ada',
      surname: 'Lovelace',
      email: 'ada@example.com',
      enabled: true,
      role: 'admin',
      roleRefs: [
        { _id: 'role-1', key: 'sales_manager', name: 'Sales Manager', permissions: ['crm.deal.read'] },
      ],
      permissions: ['sales.quote.create'],
      deniedFields: ['bankDetails'],
      fieldPermissions: [{ entity: 'invoice', deniedFields: ['bankAccount'] }],
      manager: { _id: 'admin-2', name: 'Grace' },
      orgUnit: { _id: 'org-1', name: 'Sales' },
    },
  ];

  await accessControlController.listAdminAccess(req, res);

  assert.equal(res.statusCode, 200);
  assert.equal(payload.body.result.length, 1);
  assert.deepEqual(payload.body.result[0], {
    _id: 'admin-1',
    name: 'Ada Lovelace',
    email: 'ada@example.com',
    enabled: true,
    role: 'admin',
    managerId: 'admin-2',
    manager: 'Grace',
    orgUnitId: 'org-1',
    orgUnit: 'Sales',
    roleRefs: [{ _id: 'role-1', key: 'sales_manager', name: 'Sales Manager' }],
    directPermissions: ['sales.quote.create'],
    deniedFields: ['bankDetails'],
    fieldPermissions: [{ entity: 'invoice', deniedFields: ['bankAccount'] }],
    effectivePermissions: ['crm.deal.read', 'sales.quote.create'],
  });

  accessControlController.__test.findAdminsWithAccess = originalFind;
});

test('updateAdminAccess saves role assignments and returns effective access summary', async () => {
  const req = {
    params: { id: 'admin-1' },
    tenantId: 'tenant-1',
    body: {
      roleRefs: ['role-1', 'role-2'],
      permissions: ['tasks.task.create'],
      deniedFields: ['commissionAmount'],
      fieldPermissions: [
        { entity: 'deal', deniedFields: ['margin'] },
        { entity: 'invoice', deniedFields: ['bankAccount'] },
      ],
      enabled: true,
      manager: 'admin-2',
      orgUnit: 'org-1',
    },
  };
  const payload = {};
  const res = {
    status(code) {
      this.statusCode = code;
      return this;
    },
    json(body) {
      payload.body = body;
      return body;
    },
  };

  const originalUpdate = accessControlController.__test.updateAdminAccess;
  accessControlController.__test.updateAdminAccess = async () => ({
    _id: 'admin-1',
    name: 'Ada',
    surname: 'Lovelace',
    email: 'ada@example.com',
    enabled: true,
    role: 'admin',
    manager: { _id: 'admin-2', name: 'Grace' },
    orgUnit: { _id: 'org-1', name: 'Sales' },
    roleRefs: [
      { _id: 'role-1', key: 'sales_manager', name: 'Sales Manager', permissions: ['crm.deal.read'] },
      { _id: 'role-2', key: 'marketing_manager', name: 'Marketing Manager', permissions: ['marketing.campaign.read'] },
    ],
    permissions: ['tasks.task.create'],
    deniedFields: ['commissionAmount'],
    fieldPermissions: [
      { entity: 'deal', deniedFields: ['margin'] },
      { entity: 'invoice', deniedFields: ['bankAccount'] },
    ],
  });

  await accessControlController.updateAdminAccess(req, res);

  assert.equal(res.statusCode, 200);
  assert.deepEqual(payload.body.result, {
    _id: 'admin-1',
    name: 'Ada Lovelace',
    email: 'ada@example.com',
    enabled: true,
    role: 'admin',
    managerId: 'admin-2',
    manager: 'Grace',
    orgUnitId: 'org-1',
    orgUnit: 'Sales',
    roleRefs: [
      { _id: 'role-1', key: 'sales_manager', name: 'Sales Manager' },
      { _id: 'role-2', key: 'marketing_manager', name: 'Marketing Manager' },
    ],
    directPermissions: ['tasks.task.create'],
    deniedFields: ['commissionAmount'],
    fieldPermissions: [
      { entity: 'deal', deniedFields: ['margin'] },
      { entity: 'invoice', deniedFields: ['bankAccount'] },
    ],
    effectivePermissions: ['crm.deal.read', 'marketing.campaign.read', 'tasks.task.create'],
  });

  accessControlController.__test.updateAdminAccess = originalUpdate;
});

test('maskRecordFields removes fields denied by field permissions', () => {
  const record = { name: 'Acme', margin: 42, bankDetails: 'secret' };
  const masked = maskRecordFields(record, ['margin', 'bankDetails']);

  assert.deepEqual(masked, { name: 'Acme' });
});

test('eventMatchesRule matches event triggers and simple field conditions', () => {
  const rule = {
    trigger: { type: 'event', eventType: 'crm.deal.won' },
    conditions: [{ field: 'payload.amount', operator: 'gte', value: 1000 }],
  };
  const event = {
    type: 'crm.deal.won',
    payload: { amount: 1250 },
  };

  assert.equal(eventMatchesRule(rule, event), true);
});

test('provider registry uses disabled providers when credentials are absent', async () => {
  const registry = createProviderRegistry({});
  const result = await registry.resend.sendEmail({
    to: 'buyer@example.com',
    subject: 'Invoice',
    html: '<p>Invoice</p>',
  });

  assert.equal(result.disabled, true);
  assert.equal(result.provider, 'resend');
});

test('configured kimi provider sends OpenAI-compatible chat completion request', async () => {
  const calls = [];
  const registry = createProviderRegistry(
    {
      KIMI_API_KEY: 'kimi-secret',
      KIMI_API_URL: 'https://kimi.example.test/v1/chat/completions',
      KIMI_MODEL: 'kimi-k2',
    },
    {
      fetch: async (url, options) => {
        calls.push({ url, options });
        return {
          ok: true,
          status: 200,
          json: async () => ({
            choices: [{ message: { content: 'Generated email copy' } }],
          }),
        };
      },
    }
  );

  const result = await registry.kimi.request('generateContent', {
    instruction: 'Write an email',
    prompt: 'Launch campaign',
  });

  assert.equal(result.disabled, false);
  assert.equal(result.content, 'Generated email copy');
  assert.equal(calls[0].url, 'https://kimi.example.test/v1/chat/completions');
  assert.equal(calls[0].options.headers.Authorization, 'Bearer kimi-secret');
});

test('configured fal provider sends generation request and returns media url', async () => {
  const registry = createProviderRegistry(
    {
      FAL_KEY: 'fal-secret',
      FAL_API_URL: 'https://fal.example.test/generate',
    },
    {
      fetch: async () => ({
        ok: true,
        status: 200,
        json: async () => ({
          images: [{ url: 'https://cdn.example.test/flyer.png' }],
        }),
      }),
    }
  );

  const result = await registry.fal.request('generateBrandAsset', {
    prompt: 'Create a launch flyer',
  });

  assert.equal(result.disabled, false);
  assert.equal(result.mediaUrl, 'https://cdn.example.test/flyer.png');
});

test('sanitizeIntegrationAccount returns masked secret previews only', () => {
  const account = {
    _id: 'integration-1',
    provider: 'openai',
    name: 'OpenAI',
    status: 'active',
    enabled: true,
    publicConfig: { model: 'gpt-4o-mini' },
    secretFields: ['apiKey'],
    secretConfigEncrypted: encryptJson({ apiKey: 'sk-demo-123456' }, { JWT_SECRET: 'demo-secret' }),
  };

  const result = sanitizeIntegrationAccount(account, { JWT_SECRET: 'demo-secret' });

  assert.equal(result.secretConfigured, true);
  assert.deepEqual(result.secretPreview, [{ key: 'apiKey', maskedValue: 'sk-••••456' }]);
  assert.equal(result.publicConfig.model, 'gpt-4o-mini');
});

test('resolveProviderEnvForTenant merges tenant integration credentials into env overrides', async () => {
  const env = await resolveProviderEnvForTenant({
    tenantId: 'tenant-1',
    env: { OPENAI_MODEL: 'gpt-4o-mini', JWT_SECRET: 'demo-secret' },
    models: {
      IntegrationAccount: {
        find() {
          return {
            lean: async () => [
              {
                provider: 'openai',
                publicConfig: { model: 'gpt-5-mini' },
                secretConfigEncrypted: encryptJson({ apiKey: 'sk-tenant-secret' }, { JWT_SECRET: 'demo-secret' }),
              },
            ],
          };
        },
      },
    },
  });

  assert.equal(env.OPENAI_API_KEY, 'sk-tenant-secret');
  assert.equal(env.OPENAI_MODEL, 'gpt-5-mini');
});

test('buildProviderCatalogForUi combines provider definitions with tenant account status', async () => {
  const result = await buildProviderCatalogForUi({
    tenantId: 'tenant-1',
    env: { RESEND_API: 'env-secret', JWT_SECRET: 'demo-secret' },
    models: {
      IntegrationAccount: {
        find() {
          return {
            sort() {
              return {
                lean: async () => [
                  {
                    _id: 'integration-2',
                    provider: 'resend',
                    name: 'Mailer',
                    status: 'active',
                    enabled: true,
                    publicConfig: { fromEmail: 'Acme <hello@example.com>' },
                    secretFields: ['apiKey'],
                    secretConfigEncrypted: encryptJson({ apiKey: 're_123456789' }, { JWT_SECRET: 'demo-secret' }),
                  },
                ],
              };
            },
          };
        },
      },
    },
  });

  const resend = result.find((item) => item.key === 'resend');
  assert.equal(resend.account.name, 'Mailer');
  assert.equal(resend.account.secretConfigured, true);
  assert.equal(resend.envFallbackActive, true);
});

test('listAiWorkspaceAssets applies workspace filters', async () => {
  const calls = [];
  const assets = [{ _id: 'asset-1', title: 'Welcome email' }];
  const models = {
    ContentAsset: {
      find(query) {
        calls.push(query);
        return {
          sort() {
            return {
              limit() {
                return {
                  lean: async () => assets,
                };
              },
            };
          },
        };
      },
    },
  };

  const result = await listAiWorkspaceAssets({
    tenantId: 'tenant-1',
    filters: { type: 'email', provider: 'kimi', campaignId: 'campaign-1', limit: 5 },
    models,
  });

  assert.deepEqual(calls[0], {
    removed: false,
    tenant: 'tenant-1',
    type: 'email',
    provider: 'kimi',
    'brandContext.campaignId': 'campaign-1',
  });
  assert.deepEqual(result, assets);
});

test('previewAutomationRule returns matched summaries for a draft and event', async () => {
  const result = await previewAutomationRule({
    draft: {
      trigger: { type: 'event', eventType: 'crm.deal.won' },
      conditions: [{ field: 'payload.amount', operator: 'gte', value: 1000 }],
      actions: [
        { type: 'create_task', config: { title: 'Follow up with buyer' } },
        { type: 'send_email', config: { subject: 'Thank you for your order' } },
      ],
    },
    event: {
      type: 'crm.deal.won',
      payload: { amount: 2400 },
    },
  });

  assert.equal(result.matched, true);
  assert.deepEqual(result.conditionSummaries, ['payload.amount gte 1000']);
  assert.deepEqual(result.actionSummaries, [
    'Create task: Follow up with buyer',
    'Send email: Thank you for your order',
  ]);
});

test('buildMarketingWorkspaceSummary aggregates campaigns, segments, and posts', async () => {
  const campaigns = [
    { _id: 'c1', status: 'scheduled', metrics: { sent: 20, opened: 10, clicked: 3, converted: 1 } },
    { _id: 'c2', status: 'completed', metrics: { sent: 15, opened: 6, clicked: 2, converted: 1 } },
  ];
  const segments = [{ _id: 's1', name: 'Warm leads', filters: [{ field: 'leadScore' }] }];
  const posts = [{ _id: 'p1', status: 'scheduled' }, { _id: 'p2', status: 'published' }];
  const events = [{ _id: 'e1', audienceSegment: 's1' }, { _id: 'e2', audienceSegment: 's1' }];
  const sortable = (rows) => ({
    sort() {
      return {
        limit() {
          return {
            lean: async () => rows,
          };
        },
      };
    },
  });

  const result = await buildMarketingWorkspaceSummary({
    tenantId: 'tenant-1',
    models: {
      Campaign: { find: () => sortable(campaigns) },
      AudienceSegment: { find: () => sortable(segments) },
      SocialPost: { find: () => sortable(posts) },
      CampaignEvent: { find: () => sortable(events) },
    },
  });

  assert.equal(result.metrics.totalCampaigns, 2);
  assert.equal(result.metrics.scheduledCampaigns, 1);
  assert.equal(result.metrics.completedCampaigns, 1);
  assert.equal(result.metrics.sent, 35);
  assert.equal(result.segments[0].recentEngagements, 2);
  assert.equal(result.scheduledPosts.length, 1);
});

test('buildAutomationWorkspaceSummary groups queue and failure metrics', async () => {
  const sortable = (rows) => ({
    sort() {
      return {
        limit() {
          return {
            lean: async () => rows,
          };
        },
      };
    },
  });

  const result = await buildAutomationWorkspaceSummary({
    tenantId: 'tenant-1',
    models: {
      AutomationRun: { find: () => sortable([{ status: 'failed' }, { status: 'running' }]) },
      AutomationEvent: { find: () => sortable([{ type: 'crm.deal.won' }]) },
      Job: { find: () => sortable([{ status: 'queued', type: 'automation.run' }, { status: 'failed', type: 'automation.run' }]) },
      AutomationRule: {
        find: () =>
          sortable([
            { name: 'Deal won follow-up', enabled: true, trigger: { type: 'event', eventType: 'crm.deal.won' }, actions: [{ type: 'create_task' }] },
          ]),
      },
    },
  });

  assert.deepEqual(result.metrics, {
    queuedJobs: 1,
    failedRuns: 1,
    runningRuns: 1,
    activeRules: 1,
  });
  assert.equal(result.starterRules[0].actionSummaries[0], 'Create task');
});

test('launchCampaignWorkflow creates campaign and social posts with readiness checks', async () => {
  const campaigns = [];
  const posts = [];
  const result = await launchCampaignWorkflow({
    tenantId: 'tenant-1',
    adminId: 'admin-1',
    payload: {
      name: 'Launch campaign',
      channel: 'multi',
      audienceSegment: 'segment-1',
      body: 'Welcome to the launch.',
      socialProviders: ['facebook', 'instagram'],
    },
    models: {
      Campaign: {
        create: async (payload) => {
          campaigns.push(payload);
          return { _id: 'campaign-1', ...payload };
        },
      },
      SocialPost: {
        create: async (payload) => {
          posts.push(payload);
          return { _id: `post-${posts.length}`, ...payload };
        },
      },
    },
  });

  assert.equal(campaigns.length, 1);
  assert.equal(posts.length, 2);
  assert.equal(result.socialPosts.length, 2);
  assert.deepEqual(result.readiness, {
    hasAudience: true,
    hasContent: true,
    hasSchedule: true,
    hasChannel: true,
  });
});

test('interpolateTemplate replaces event paths inside nested action config', () => {
  const output = interpolateTemplate(
    {
      title: 'Follow up {{payload.deal.name}}',
      relatedTo: { entityType: '{{entity}}', entityId: '{{entityId}}' },
    },
    {
      entity: 'deal',
      entityId: 'deal-1',
      payload: { deal: { name: 'ACME renewal' } },
    }
  );

  assert.deepEqual(output, {
    title: 'Follow up ACME renewal',
    relatedTo: { entityType: 'deal', entityId: 'deal-1' },
  });
});

test('executeAutomationAction creates a linked task', async () => {
  const created = [];
  const models = {
    Task: {
      create: async (payload) => {
        created.push(payload);
        return { _id: 'task-1', ...payload };
      },
    },
  };

  const result = await executeAutomationAction({
    action: {
      type: 'create_task',
      config: {
        title: 'Follow up {{payload.deal.name}}',
        priority: 'high',
      },
    },
    event: {
      tenant: 'tenant-1',
      actor: 'admin-1',
      entity: 'deal',
      entityId: 'deal-1',
      payload: { deal: { name: 'ACME renewal' } },
    },
    models,
    providers: {},
  });

  assert.equal(result.type, 'create_task');
  assert.equal(result.record._id, 'task-1');
  assert.deepEqual(created[0], {
    tenant: 'tenant-1',
    createdBy: 'admin-1',
    assignedTo: 'admin-1',
    title: 'Follow up ACME renewal',
    priority: 'high',
    relatedTo: { entityType: 'deal', entityId: 'deal-1' },
  });
});

test('processAutomationJob marks successful job and run as succeeded', async () => {
  const job = {
    _id: 'job-1',
    tenant: 'tenant-1',
    payload: { rule: 'rule-1', event: 'event-1' },
    attempts: 0,
    saveCalls: 0,
    save: async function save() {
      this.saveCalls += 1;
      return this;
    },
  };
  const run = {
    _id: 'run-1',
    save: async function save() {
      return this;
    },
  };

  const models = {
    Job: {
      findOneAndUpdate: () => ({ exec: async () => job }),
    },
    AutomationRule: {
      findOne: () => ({ exec: async () => ({ _id: 'rule-1', actions: [{ type: 'create_task', config: { title: 'Call {{entityId}}' } }] }) }),
    },
    AutomationEvent: {
      findOne: () => ({ exec: async () => ({ _id: 'event-1', tenant: 'tenant-1', actor: 'admin-1', entity: 'deal', entityId: 'deal-1', payload: {} }) }),
    },
    AutomationRun: {
      create: async (payload) => Object.assign(run, payload),
    },
    Task: {
      create: async (payload) => ({ _id: 'task-1', ...payload }),
    },
  };

  const result = await processAutomationJob({ models, providers: {}, now: new Date() });

  assert.equal(result.processed, true);
  assert.equal(job.status, 'succeeded');
  assert.equal(run.status, 'succeeded');
  assert.equal(run.actionResults[0].record._id, 'task-1');
});

test('processAutomationJob marks failed job and run as failed', async () => {
  const job = {
    _id: 'job-2',
    tenant: 'tenant-1',
    payload: { rule: 'rule-2', event: 'event-2' },
    attempts: 0,
    save: async function save() {
      return this;
    },
  };
  const run = {
    _id: 'run-2',
    save: async function save() {
      return this;
    },
  };

  const models = {
    Job: {
      findOneAndUpdate: () => ({ exec: async () => job }),
    },
    AutomationRule: {
      findOne: () => ({ exec: async () => ({ _id: 'rule-2', actions: [{ type: 'not_real', config: {} }] }) }),
    },
    AutomationEvent: {
      findOne: () => ({ exec: async () => ({ _id: 'event-2', tenant: 'tenant-1', actor: 'admin-1', entity: 'deal', entityId: 'deal-1', payload: {} }) }),
    },
    AutomationRun: {
      create: async (payload) => Object.assign(run, payload),
    },
  };

  const result = await processAutomationJob({ models, providers: {}, now: new Date() });

  assert.equal(result.processed, false);
  assert.equal(job.status, 'failed');
  assert.equal(run.status, 'failed');
  assert.match(run.error, /Unsupported automation action/);
});

test('convertOfferToInvoice converts an offer into an invoice and marks it converted', async () => {
  const offer = {
    _id: 'offer-1',
    tenant: 'tenant-1',
    title: 'Q3 renewal proposal',
    kind: 'proforma',
    contact: { _id: 'contact-1', name: 'Ada Cole', email: 'ada@example.com', phone: '123' },
    lead: null,
    deal: null,
    items: [{ itemName: 'Renewal', quantity: 1, price: 500, total: 500 }],
    total: 500,
    value: { amount: 500, currency: 'USD' },
    notes: 'Converted from offer',
    status: 'sent',
    save: async function save() {
      return this;
    },
  };

  const invoiceCreates = [];
  const models = {
    Offer: {
      findOne: () => ({ populate: () => ({ populate: () => ({ populate: () => ({ exec: async () => offer }) }) }) }),
    },
    Client: {
      findOne: () => ({ exec: async () => null }),
      create: async (payload) => ({ _id: 'client-1', ...payload }),
    },
    Invoice: {
      countDocuments: async () => 2,
      create: async (payload) => {
        invoiceCreates.push(payload);
        return { _id: 'invoice-1', ...payload };
      },
    },
  };

  const events = [];
  const result = await convertOfferToInvoice({
    offerId: 'offer-1',
    tenantId: 'tenant-1',
    actorId: 'admin-1',
    models,
    publishEvent: async (payload) => {
      events.push(payload);
      return payload;
    },
  });

  assert.equal(result.invoice._id, 'invoice-1');
  assert.equal(result.offer.status, 'converted');
  assert.deepEqual(invoiceCreates[0].converted, {
    from: 'offer',
    offer: 'offer-1',
    quote: undefined,
  });
  assert.equal(events[0].type, 'sales.offer.converted');
});

test('transitionEntityStatus updates supported workflow records and publishes an event', async () => {
  const record = {
    _id: 'purchase-1',
    status: 'draft',
    save: async function save() {
      return this;
    },
  };
  const models = {
    PurchaseOrder: {
      findOne: () => ({ exec: async () => record }),
    },
  };

  const events = [];
  const result = await transitionEntityStatus({
    entityName: 'purchaseorder',
    entityId: 'purchase-1',
    status: 'approved',
    tenantId: 'tenant-1',
    actorId: 'admin-1',
    models,
    publishEvent: async (payload) => {
      events.push(payload);
      return payload;
    },
  });

  assert.equal(result.record.status, 'approved');
  assert.equal(events[0].type, 'vendors.purchaseorder.status_changed');
});

test('submitPublicForm creates CRM records and sends auto-reply when enabled', async () => {
  const form = {
    _id: 'form-1',
    name: 'Lead capture',
    slug: 'lead-capture',
    targetEntity: 'lead',
    autoReplyEnabled: true,
    autoReplySubject: 'Thanks for reaching out',
    autoReplyBody: 'We will be in touch shortly.',
  };
  const sent = [];
  const events = [];

  const models = {
    PublicForm: {
      findOne: () => ({ exec: async () => form }),
    },
    Contact: {
      create: async (payload) => ({ _id: 'contact-1', ...payload }),
    },
    Lead: {
      create: async (payload) => ({ _id: 'lead-1', ...payload }),
    },
    Client: {
      create: async (payload) => ({ _id: 'client-1', ...payload }),
    },
  };

  const result = await submitPublicForm({
    slug: 'lead-capture',
    tenantId: 'tenant-1',
    actorId: 'admin-1',
    payload: { firstName: 'Ada', lastName: 'Cole', email: 'ada@example.com', phone: '123' },
    models,
    providers: {
      resend: {
        sendEmail: async (payload) => {
          sent.push(payload);
          return { id: 'email-1', disabled: false };
        },
      },
    },
    publishEvent: async (payload) => {
      events.push(payload);
      return payload;
    },
  });

  assert.equal(result.contact._id, 'contact-1');
  assert.equal(result.record._id, 'lead-1');
  assert.equal(sent[0].to, 'ada@example.com');
  assert.equal(events[0].type, 'platform.publicform.submitted');
});

test('submitPublicForm falls back to the form tenant for public submissions', async () => {
  const form = {
    _id: 'form-2',
    tenant: 'tenant-public',
    name: 'Customer intake',
    slug: 'customer-intake',
    targetEntity: 'client',
    autoReplyEnabled: false,
  };
  const createdContacts = [];
  const createdClients = [];
  const models = {
    PublicForm: {
      findOne: () => ({ exec: async () => form }),
    },
    Contact: {
      create: async (payload) => {
        createdContacts.push(payload);
        return { _id: 'contact-2', ...payload };
      },
    },
    Lead: {
      create: async (payload) => ({ _id: 'lead-unused', ...payload }),
    },
    Client: {
      create: async (payload) => {
        createdClients.push(payload);
        return { _id: 'client-2', ...payload };
      },
    },
  };

  const result = await submitPublicForm({
    slug: 'customer-intake',
    payload: { firstName: 'Grace', email: 'grace@example.com' },
    models,
    providers: {},
  });

  assert.equal(result.contact.tenant, 'tenant-public');
  assert.equal(createdContacts[0].tenant, 'tenant-public');
  assert.equal(createdClients[0].tenant, 'tenant-public');
});

test('getPublishedPublicForm returns public metadata for published forms only', async () => {
  const form = {
    _id: 'form-3',
    tenant: 'tenant-1',
    name: 'Demo request',
    slug: 'demo-request',
    description: 'Book a demo',
    targetEntity: 'lead',
    fields: ['firstName', 'email', 'message'],
    successMessage: 'Thanks!',
    status: 'published',
  };

  const models = {
    PublicForm: {
      findOne: ({ slug, status }) => ({
        exec: async () => (slug === 'demo-request' && status === 'published' ? form : null),
      }),
    },
  };

  const result = await getPublishedPublicForm({ slug: 'demo-request', models });

  assert.deepEqual(result, {
    _id: 'form-3',
    tenant: 'tenant-1',
    name: 'Demo request',
    slug: 'demo-request',
    description: 'Book a demo',
    targetEntity: 'lead',
    fields: ['firstName', 'email', 'message'],
    successMessage: 'Thanks!',
    status: 'published',
  });
});

test('recordInvoicePayment creates a payment and updates invoice credit and payment status', async () => {
  const invoice = {
    _id: 'invoice-1',
    tenant: 'tenant-1',
    client: { _id: 'client-1' },
    total: 1000,
    discount: 100,
    credit: 200,
    currency: 'USD',
    payment: [],
    paymentStatus: 'partially',
    save: async function save() {
      return this;
    },
  };
  const createdPayments = [];
  const models = {
    Invoice: {
      findOne: () => ({ exec: async () => invoice }),
    },
    Payment: {
      countDocuments: async () => 4,
      create: async (payload) => {
        createdPayments.push(payload);
        return { _id: 'payment-1', ...payload };
      },
    },
  };
  const events = [];

  const result = await recordInvoicePayment({
    invoiceId: 'invoice-1',
    tenantId: 'tenant-1',
    actorId: 'admin-1',
    paymentInput: {
      amount: 700,
      currency: 'USD',
      paymentMode: 'mode-1',
      ref: 'TRX-123',
      description: 'Bank transfer',
    },
    models,
    publishEvent: async (payload) => {
      events.push(payload);
      return payload;
    },
  });

  assert.equal(result.payment._id, 'payment-1');
  assert.equal(createdPayments[0].number, 5);
  assert.equal(invoice.credit, 900);
  assert.equal(invoice.paymentStatus, 'paid');
  assert.deepEqual(invoice.payment, ['payment-1']);
  assert.equal(events[0].type, 'finance.invoice.payment_recorded');
});

test('reconcilePaymentRecord marks a payment reconciled and emits an event', async () => {
  const payment = {
    _id: 'payment-2',
    tenant: 'tenant-1',
    reconciled: false,
    reconciledAt: null,
    save: async function save() {
      return this;
    },
  };
  const models = {
    Payment: {
      findOne: () => ({ exec: async () => payment }),
    },
  };
  const events = [];

  const result = await reconcilePaymentRecord({
    paymentId: 'payment-2',
    tenantId: 'tenant-1',
    actorId: 'admin-1',
    models,
    publishEvent: async (payload) => {
      events.push(payload);
      return payload;
    },
  });

  assert.equal(result.payment.reconciled, true);
  assert.ok(result.payment.reconciledAt instanceof Date);
  assert.equal(events[0].type, 'finance.payment.reconciled');
});

test('buildEnterpriseOverviewReport returns finance, operations, and conversion rollups', async () => {
  const matches = [];
  const models = {
    Invoice: {
      aggregate: async (pipeline) => {
        matches.push(pipeline[0].$match);
        return [
          {
            count: 3,
            total: 2400,
            outstanding: 900,
            paidCount: 1,
            partialCount: 1,
            unpaidCount: 1,
          },
        ];
      },
    },
    Payment: {
      aggregate: async () => [
        {
          count: 2,
          total: 1500,
          reconciledCount: 1,
          unreconciledCount: 1,
        },
      ],
    },
    Order: {
      aggregate: async () => [
        { _id: 'confirmed', count: 2 },
        { _id: 'processing', count: 1 },
      ],
    },
    PurchaseOrder: {
      aggregate: async () => [
        { _id: 'approved', count: 1 },
        { _id: 'received', count: 2 },
      ],
    },
    AutomationEvent: {
      countDocuments: async (filter) => {
        if (filter.type === 'platform.publicform.submitted') return 5;
        if (filter.type === 'sales.offer.converted') return 3;
        if (filter.type === 'finance.invoice.payment_recorded') return 4;
        if (filter.type === 'finance.payment.reconciled') return 2;
        return 0;
      },
    },
    PublicForm: {
      countDocuments: async (filter) => (filter.status === 'published' ? 2 : 0),
    },
  };

  const result = await buildEnterpriseOverviewReport({ tenantId: 'tenant-1', models });

  assert.deepEqual(matches[0], { removed: false, tenant: 'tenant-1' });
  assert.deepEqual(result.finance, {
    invoices: {
      count: 3,
      total: 2400,
      outstanding: 900,
      paidCount: 1,
      partialCount: 1,
      unpaidCount: 1,
    },
    payments: {
      count: 2,
      total: 1500,
      reconciledCount: 1,
      unreconciledCount: 1,
    },
  });
  assert.deepEqual(result.operations.orders.statusCounts, [
    { status: 'confirmed', count: 2 },
    { status: 'processing', count: 1 },
  ]);
  assert.deepEqual(result.operations.purchases.statusCounts, [
    { status: 'approved', count: 1 },
    { status: 'received', count: 2 },
  ]);
  assert.deepEqual(result.conversions, {
    publicFormSubmissions: 5,
    publishedForms: 2,
    convertedOffers: 3,
    paymentsRecorded: 4,
    reconciledPayments: 2,
  });
});
