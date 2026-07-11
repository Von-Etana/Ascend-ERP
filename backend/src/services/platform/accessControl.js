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
  agent: 'ai',
  knowledge: 'ai',
  brand: 'ai',
  social: 'marketing',
  agentdefinition: 'ai',
  agentversion: 'ai',
  agentrun: 'ai',
  agentrunstep: 'ai',
  agentapproval: 'ai',
  knowledgesource: 'ai',
  knowledgedocument: 'ai',
  knowledgechunk: 'ai',
  brandprofile: 'ai',
  socialconnection: 'marketing',
  agentbudget: 'finance',
};

const getSchemaPath = (Model, field) => {
  if (!Model || !Model.schema || typeof Model.schema.path !== 'function') return null;
  return Model.schema.path(field);
};

const buildTenantQuery = (Model, req = {}) => {
  if (!getSchemaPath(Model, 'tenant') || !req.tenantId) return {};
  return { tenant: req.tenantId };
};

const getPermissionSet = (user = {}) => {
  const permissions = new Set(user.permissions || []);

  for (const role of user.roles || user.roleRefs || []) {
    if (typeof role === 'string') continue;
    for (const permission of role.permissions || []) {
      permissions.add(permission);
    }
  }

  return permissions;
};

const canAccessAction = (user = {}, permission) => {
  if (!permission) return true;
  if (ADMIN_ROLES.has(user.role)) return true;

  const permissions = getPermissionSet(user);
  return permissions.has(permission) || permissions.has('*');
};

const maskRecordFields = (record, deniedFields = []) => {
  const source =
    record && typeof record.toObject === 'function' ? record.toObject({ virtuals: true }) : record;
  if (!source || deniedFields.length === 0) return source;

  const clone = { ...source };
  for (const field of deniedFields) {
    delete clone[field];
  }
  return clone;
};

const computeEffectiveAccess = (user = {}) => {
  const permissions = [];
  const deniedFields = [];
  const fieldPermissions = [];
  const upsertFieldPermission = (entry = {}) => {
    if (!entry.entity) return;
    const existing = fieldPermissions.find((item) => item.entity === entry.entity);
    if (!existing) {
      fieldPermissions.push({
        entity: entry.entity,
        deniedFields: [...new Set(entry.deniedFields || [])],
      });
      return;
    }

    for (const field of entry.deniedFields || []) {
      if (!existing.deniedFields.includes(field)) existing.deniedFields.push(field);
    }
  };

  for (const permission of user.permissions || []) {
    if (!permissions.includes(permission)) permissions.push(permission);
  }

  for (const field of user.deniedFields || []) {
    if (!deniedFields.includes(field)) deniedFields.push(field);
  }

  for (const entry of user.fieldPermissions || []) {
    upsertFieldPermission(entry);
  }

  for (const role of user.roles || user.roleRefs || []) {
    if (!role || typeof role === 'string') continue;

    for (const permission of role.permissions || []) {
      if (!permissions.includes(permission)) permissions.push(permission);
    }

    for (const fieldPermission of role.fieldPermissions || []) {
      upsertFieldPermission(fieldPermission);
      for (const field of fieldPermission.deniedFields || []) {
        if (!deniedFields.includes(field)) deniedFields.push(field);
      }
    }
  }

  return { permissions, deniedFields, fieldPermissions };
};

const permissionFor = (entity, action) => {
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

module.exports = {
  buildTenantQuery,
  canAccessAction,
  maskRecordFields,
  computeEffectiveAccess,
  permissionFor,
  ENTITY_MODULE_MAP,
};
