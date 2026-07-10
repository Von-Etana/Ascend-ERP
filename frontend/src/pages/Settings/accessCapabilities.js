import { canAccessPermission, permissionForEntity } from '../../utils/permissions.js';

const byEntity = (entity, action) => ({
  entity,
  action,
  allows(permissions) {
    return permissions.has(permissionForEntity(entity, action));
  },
});

const byPermission = (permission) => ({
  permission,
  allows(permissions) {
    return canAccessPermission({ permissions: [...permissions] }, permission);
  },
});

const alwaysAllowed = () => ({
  permission: null,
  allows() {
    return true;
  },
});

export const ACCESS_CAPABILITY_GROUPS = {
  routes: [
    { key: 'route.dashboard', label: 'Dashboard', category: 'route', ...alwaysAllowed() },
    { key: 'route.customer', label: 'Customer', category: 'route', ...byEntity('client', 'read') },
    { key: 'route.people', label: 'People', category: 'route', ...byEntity('contact', 'read') },
    { key: 'route.company', label: 'Company', category: 'route', ...byEntity('company', 'read') },
    { key: 'route.lead', label: 'Lead', category: 'route', ...byEntity('lead', 'read') },
    { key: 'route.crm', label: 'CRM', category: 'route', ...byEntity('deal', 'read') },
    { key: 'route.offers', label: 'Offers', category: 'route', ...byEntity('offer', 'read') },
    { key: 'route.sales', label: 'Sales', category: 'route', ...byEntity('quote', 'read') },
    { key: 'route.finance', label: 'Finance', category: 'route', ...byEntity('invoice', 'read') },
    { key: 'route.catalog', label: 'Catalog / Inventory', category: 'route', ...byEntity('product', 'read') },
    { key: 'route.orders', label: 'Orders / Purchase', category: 'route', ...byEntity('order', 'read') },
    { key: 'route.vendors', label: 'Vendors', category: 'route', ...byEntity('purchaseorder', 'read') },
    { key: 'route.marketing', label: 'Marketing', category: 'route', ...byEntity('campaign', 'read') },
    { key: 'route.tasks', label: 'Tasks', category: 'route', ...byEntity('task', 'read') },
    { key: 'route.operations', label: 'Operations', category: 'route', ...byEntity('branch', 'read') },
    { key: 'route.forms', label: 'Public Forms', category: 'route', ...byEntity('publicform', 'read') },
    { key: 'route.developer', label: 'Developer API Keys', category: 'route', ...byEntity('apikey', 'read') },
    { key: 'route.automations', label: 'Automations', category: 'route', ...byPermission('automations.runner.read') },
    { key: 'route.ai-studio', label: 'AI Studio', category: 'route', ...byPermission('ai.studio.read') },
    {
      key: 'route.settings-access-control',
      label: 'Settings / Access Control',
      category: 'route',
      ...byEntity('role', 'read'),
    },
  ],
  workflows: [
    { key: 'workflow.advance-deal-stage', label: 'Advance Deal Stage', category: 'workflow', ...byEntity('deal', 'update') },
    { key: 'workflow.run-deal-won', label: 'Run Deal Won Workflow', category: 'workflow', ...byPermission('automations.workflow.create') },
    { key: 'workflow.create-follow-up-task', label: 'Create Follow-up Task', category: 'workflow', ...byEntity('task', 'create') },
    { key: 'workflow.create-quote', label: 'Create Quote', category: 'workflow', ...byEntity('quote', 'create') },
    { key: 'workflow.create-invoice', label: 'Create Invoice', category: 'workflow', ...byEntity('invoice', 'create') },
    { key: 'workflow.create-purchase-order', label: 'Create Purchase Order', category: 'workflow', ...byEntity('purchaseorder', 'create') },
    { key: 'workflow.schedule-campaign', label: 'Schedule Campaign', category: 'workflow', ...byEntity('campaign', 'create') },
    { key: 'workflow.run-due-jobs', label: 'Run Due Jobs', category: 'workflow', ...byPermission('automations.runner.create') },
  ],
  ai: [
    { key: 'ai.generate-text', label: 'Generate Text', category: 'ai', ...byPermission('ai.studio.create') },
    { key: 'ai.generate-brand-asset', label: 'Generate Brand Asset', category: 'ai', ...byPermission('ai.studio.create') },
    { key: 'ai.draft-campaign', label: 'Draft Campaign', category: 'ai', ...byPermission('ai.studio.create') },
  ],
};

export const buildCapabilityGroups = (effectivePermissions = []) => {
  const permissionSet = new Set(effectivePermissions);

  return Object.fromEntries(
    Object.entries(ACCESS_CAPABILITY_GROUPS).map(([group, capabilities]) => [
      group,
      capabilities.map(({ allows, ...capability }) => ({
        ...capability,
        allowed: allows(permissionSet),
      })),
    ])
  );
};
