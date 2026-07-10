import test from 'node:test';
import assert from 'node:assert/strict';

import { buildAccessPreview } from '../src/pages/Settings/accessPreview.js';

test('buildAccessPreview groups effective permissions by module and preserves entity field restrictions', () => {
  const preview = buildAccessPreview({
    catalog: [
      { key: 'crm.deal.read', module: 'crm' },
      { key: 'sales.quote.create', module: 'sales' },
      { key: 'marketing.campaign.read', module: 'marketing' },
    ],
    roles: [
      {
        _id: 'role-1',
        key: 'sales_manager',
        name: 'Sales Manager',
        permissions: ['crm.deal.read'],
        fieldPermissions: [{ entity: 'deal', deniedFields: ['margin'] }],
      },
      {
        _id: 'role-2',
        key: 'marketing_manager',
        name: 'Marketing Manager',
        permissions: ['marketing.campaign.read'],
      },
    ],
    selectedRoleIds: ['role-1', 'role-2'],
    directPermissions: ['sales.quote.create'],
    directFieldPermissions: [{ entity: 'invoice', deniedFields: ['bankAccount'] }],
  });

  assert.deepEqual(preview.effectivePermissions, [
    'crm.deal.read',
    'marketing.campaign.read',
    'sales.quote.create',
  ]);
  assert.deepEqual(preview.moduleSummary, [
    { module: 'crm', count: 1 },
    { module: 'marketing', count: 1 },
    { module: 'sales', count: 1 },
  ]);
  assert.deepEqual(preview.fieldPermissions, [
    { entity: 'deal', deniedFields: ['margin'] },
    { entity: 'invoice', deniedFields: ['bankAccount'] },
  ]);
});

test('buildAccessPreview returns route, workflow, and AI capability badges', () => {
  const preview = buildAccessPreview({
    catalog: [
      { key: 'crm.client.read', module: 'crm' },
      { key: 'crm.contact.read', module: 'crm' },
      { key: 'crm.company.read', module: 'crm' },
      { key: 'crm.lead.read', module: 'crm' },
      { key: 'crm.deal.read', module: 'crm' },
      { key: 'sales.offer.read', module: 'sales' },
      { key: 'sales.quote.read', module: 'sales' },
      { key: 'sales.quote.create', module: 'sales' },
      { key: 'inventory.product.read', module: 'inventory' },
      { key: 'inventory.order.read', module: 'inventory' },
      { key: 'operations.branch.read', module: 'operations' },
      { key: 'platform.publicform.read', module: 'platform' },
      { key: 'platform.apikey.read', module: 'platform' },
      { key: 'automations.workflow.create', module: 'automations' },
      { key: 'ai.studio.create', module: 'ai' },
    ],
    roles: [
      {
        _id: 'role-1',
        key: 'sales_manager',
        name: 'Sales Manager',
        permissions: [
          'crm.client.read',
          'crm.contact.read',
          'crm.company.read',
          'crm.lead.read',
          'crm.deal.read',
          'sales.offer.read',
          'sales.quote.read',
          'sales.quote.create',
          'inventory.product.read',
          'inventory.order.read',
          'operations.branch.read',
          'platform.publicform.read',
          'platform.apikey.read',
        ],
      },
    ],
    selectedRoleIds: ['role-1'],
    directPermissions: ['automations.workflow.create'],
  });

  assert.deepEqual(preview.capabilityGroups.routes, [
    { key: 'route.dashboard', label: 'Dashboard', category: 'route', permission: null, allowed: true },
    { key: 'route.customer', label: 'Customer', category: 'route', entity: 'client', action: 'read', allowed: true },
    { key: 'route.people', label: 'People', category: 'route', entity: 'contact', action: 'read', allowed: true },
    { key: 'route.company', label: 'Company', category: 'route', entity: 'company', action: 'read', allowed: true },
    { key: 'route.lead', label: 'Lead', category: 'route', entity: 'lead', action: 'read', allowed: true },
    { key: 'route.crm', label: 'CRM', category: 'route', entity: 'deal', action: 'read', allowed: true },
    { key: 'route.offers', label: 'Offers', category: 'route', entity: 'offer', action: 'read', allowed: true },
    { key: 'route.sales', label: 'Sales', category: 'route', entity: 'quote', action: 'read', allowed: true },
    { key: 'route.finance', label: 'Finance', category: 'route', entity: 'invoice', action: 'read', allowed: false },
    { key: 'route.catalog', label: 'Catalog / Inventory', category: 'route', entity: 'product', action: 'read', allowed: true },
    { key: 'route.orders', label: 'Orders / Purchase', category: 'route', entity: 'order', action: 'read', allowed: true },
    { key: 'route.vendors', label: 'Vendors', category: 'route', entity: 'purchaseorder', action: 'read', allowed: false },
    { key: 'route.marketing', label: 'Marketing', category: 'route', entity: 'campaign', action: 'read', allowed: false },
    { key: 'route.tasks', label: 'Tasks', category: 'route', entity: 'task', action: 'read', allowed: false },
    { key: 'route.operations', label: 'Operations', category: 'route', entity: 'branch', action: 'read', allowed: true },
    { key: 'route.forms', label: 'Public Forms', category: 'route', entity: 'publicform', action: 'read', allowed: true },
    { key: 'route.developer', label: 'Developer API Keys', category: 'route', entity: 'apikey', action: 'read', allowed: true },
    { key: 'route.automations', label: 'Automations', category: 'route', permission: 'automations.runner.read', allowed: false },
    { key: 'route.ai-studio', label: 'AI Studio', category: 'route', permission: 'ai.studio.read', allowed: false },
    { key: 'route.settings-access-control', label: 'Settings / Access Control', category: 'route', entity: 'role', action: 'read', allowed: false },
  ]);

  assert.deepEqual(preview.capabilityGroups.workflows, [
    { key: 'workflow.advance-deal-stage', label: 'Advance Deal Stage', category: 'workflow', entity: 'deal', action: 'update', allowed: false },
    { key: 'workflow.run-deal-won', label: 'Run Deal Won Workflow', category: 'workflow', permission: 'automations.workflow.create', allowed: true },
    { key: 'workflow.create-follow-up-task', label: 'Create Follow-up Task', category: 'workflow', entity: 'task', action: 'create', allowed: false },
    { key: 'workflow.create-quote', label: 'Create Quote', category: 'workflow', entity: 'quote', action: 'create', allowed: true },
    { key: 'workflow.create-invoice', label: 'Create Invoice', category: 'workflow', entity: 'invoice', action: 'create', allowed: false },
    { key: 'workflow.create-purchase-order', label: 'Create Purchase Order', category: 'workflow', entity: 'purchaseorder', action: 'create', allowed: false },
    { key: 'workflow.schedule-campaign', label: 'Schedule Campaign', category: 'workflow', entity: 'campaign', action: 'create', allowed: false },
    { key: 'workflow.run-due-jobs', label: 'Run Due Jobs', category: 'workflow', permission: 'automations.runner.create', allowed: false },
  ]);

  assert.deepEqual(preview.capabilityGroups.ai, [
    { key: 'ai.generate-text', label: 'Generate Text', category: 'ai', permission: 'ai.studio.create', allowed: false },
    { key: 'ai.generate-brand-asset', label: 'Generate Brand Asset', category: 'ai', permission: 'ai.studio.create', allowed: false },
    { key: 'ai.draft-campaign', label: 'Draft Campaign', category: 'ai', permission: 'ai.studio.create', allowed: false },
  ]);
});
