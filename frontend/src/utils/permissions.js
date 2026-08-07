const ADMIN_ROLES = new Set(['owner', 'admin', 'super_admin']);

const ENTITY_MODULE_MAP = {
  contact: 'crm',
  company: 'crm',
  lead: 'crm',
  deal: 'crm',
  pipelinestage: 'crm',
  activity: 'crm',
  communicationlog: 'crm',
  leadscorerule: 'crm',
  client: 'crm',
  offer: 'sales',
  quote: 'sales',
  salesorder: 'sales',
  salestarget: 'sales',
  commission: 'sales',
  approvalrequest: 'sales',
  product: 'inventory',
  productcategory: 'inventory',
  order: 'inventory',
  supplier: 'inventory',
  campaign: 'marketing',
  audiencesegment: 'marketing',
  landingform: 'marketing',
  campaignevent: 'marketing',
  socialpost: 'marketing',
  contentasset: 'marketing',
  account: 'finance',
  ledgerentry: 'finance',
  invoice: 'finance',
  payment: 'finance',
  expense: 'finance',
  expensecategory: 'finance',
  bill: 'finance',
  budget: 'finance',
  taxprofile: 'finance',
  taxes: 'finance',
  paymentmode: 'finance',
  currency: 'finance',
  pdfsetting: 'finance',
  role: 'settings',
  permission: 'settings',
  tenant: 'settings',
  orgunit: 'settings',
  auditlog: 'settings',
  vendor: 'vendors',
  vendorcontract: 'vendors',
  purchaseorder: 'vendors',
  vendorscorecard: 'vendors',
  automationrule: 'automations',
  automationevent: 'automations',
  automationrun: 'automations',
  job: 'automations',
  integrationaccount: 'automations',
  automation: 'automations',
  workflows: 'automations',
  task: 'tasks',
  reminder: 'tasks',
  appointment: 'tasks',
  calendarbooking: 'tasks',
  branch: 'operations',
  companyworkspace: 'operations',
  document: 'operations',
  contract: 'operations',
  publicform: 'platform',
  apikey: 'platform',
  ai: 'ai',
};

const getPermissionSet = (admin = {}) => {
  const permissions = new Set(admin.permissions || []);
  for (const role of admin.roles || admin.roleRefs || []) {
    if (!role || typeof role === 'string') continue;
    for (const permission of role.permissions || []) {
      permissions.add(permission);
    }
  }
  return permissions;
};

export const permissionForEntity = (entity, action) => {
  const normalizedEntity = String(entity || '').toLowerCase();
  const moduleName = ENTITY_MODULE_MAP[normalizedEntity] || normalizedEntity;
  const entityName =
    normalizedEntity === 'automation'
      ? 'runner'
      : normalizedEntity === 'ai'
        ? 'studio'
        : normalizedEntity === 'workflows'
          ? 'workflow'
          : normalizedEntity;

  return `${moduleName}.${entityName}.${action}`;
};

export const canAccessPermission = (admin, permission) => {
  if (!permission) return true;
  if (ADMIN_ROLES.has(admin?.role)) return true;
  const permissions = getPermissionSet(admin);
  return permissions.has(permission) || permissions.has('*');
};

export const canAccessEntityAction = (admin, entity, action) =>
  canAccessPermission(admin, permissionForEntity(entity, action));
