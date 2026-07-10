const mongoose = require('mongoose');
const { permissionFor, ENTITY_MODULE_MAP } = require('./accessControl');

const DEFAULT_ACTIONS = ['read', 'create', 'update', 'delete'];
const EXTRA_ENTITIES = ['role', 'permission', 'tenant', 'orgunit', 'auditlog', 'automation', 'workflows', 'ai'];

const uniq = (items) => [...new Set(items)];

const getModel = (name, models) => models?.[name] || mongoose.model(name);

const buildPermissionCatalog = () => {
  const entities = uniq([...Object.keys(ENTITY_MODULE_MAP), ...EXTRA_ENTITIES]);

  return entities.flatMap((entity) =>
    DEFAULT_ACTIONS.map((action) => {
      const key = permissionFor(entity, action);
      const [module, entityName, actionName] = key.split('.');

      return {
        key,
        module,
        entity: entityName,
        action: actionName,
        description: `${actionName} ${entityName} records in ${module}`,
      };
    })
  );
};

const collectPermissions = (...prefixes) => {
  const catalog = buildPermissionCatalog();
  return catalog
    .map((permission) => permission.key)
    .filter((key) => prefixes.some((prefix) => key.startsWith(prefix)));
};

const DEFAULT_ROLE_DEFINITIONS = [
  {
    name: 'Admin',
    key: 'admin',
    permissions: ['*'],
    fieldPermissions: [],
    dataScope: 'tenant',
  },
  {
    name: 'Sales Rep',
    key: 'sales_rep',
    permissions: collectPermissions(
      'crm.contact.',
      'crm.company.',
      'crm.lead.',
      'crm.deal.',
      'crm.activity.',
      'crm.communicationlog.',
      'sales.offer.',
      'sales.quote.',
      'tasks.task.',
      'tasks.reminder.',
      'tasks.appointment.',
      'automations.workflow.'
    ),
    fieldPermissions: [
      { entity: 'deal', deniedFields: ['margin', 'commissionAmount'] },
      { entity: 'client', deniedFields: ['bankDetails', 'taxId'] },
    ],
    dataScope: 'own',
  },
  {
    name: 'Sales Manager',
    key: 'sales_manager',
    permissions: uniq([
      ...collectPermissions(
        'crm.contact.',
        'crm.company.',
        'crm.lead.',
        'crm.deal.',
        'crm.activity.',
        'crm.communicationlog.',
        'crm.pipelinestage.',
        'sales.offer.',
        'sales.quote.',
        'sales.salesorder.',
        'inventory.order.read',
        'sales.salestarget.',
        'sales.approvalrequest.',
        'sales.commission.',
        'tasks.task.',
        'tasks.reminder.',
        'tasks.appointment.',
        'automations.workflow.'
      ),
      'finance.invoice.read',
    ]),
    fieldPermissions: [{ entity: 'ledgerentry', deniedFields: ['accountNumber'] }],
    dataScope: 'team',
  },
  {
    name: 'Finance Officer',
    key: 'finance_officer',
    permissions: uniq([
      ...collectPermissions(
        'finance.account.',
        'finance.ledgerentry.',
        'finance.invoice.',
        'finance.payment.',
        'finance.expense.',
        'finance.expensecategory.',
        'finance.bill.',
        'finance.budget.',
        'finance.taxprofile.',
        'finance.taxes.',
        'finance.paymentmode.',
        'finance.currency.',
        'finance.pdfsetting.',
        'tasks.task.'
      ),
      'vendors.purchaseorder.read',
    ]),
    fieldPermissions: [],
    dataScope: 'tenant',
  },
  {
    name: 'Vendor Manager',
    key: 'vendor_manager',
    permissions: uniq([
      ...collectPermissions(
        'vendors.vendor.',
        'inventory.supplier.',
        'vendors.vendorcontract.',
        'vendors.purchaseorder.',
        'vendors.vendorscorecard.',
        'tasks.task.'
      ),
      'finance.bill.read',
      'finance.bill.update',
      'finance.payment.read',
    ]),
    fieldPermissions: [{ entity: 'vendor', deniedFields: ['bankDetails'] }],
    dataScope: 'tenant',
  },
  {
    name: 'Marketing Manager',
    key: 'marketing_manager',
    permissions: uniq([
      ...collectPermissions(
        'marketing.campaign.',
        'marketing.audiencesegment.',
        'marketing.landingform.',
        'marketing.campaignevent.',
        'marketing.socialpost.',
        'marketing.contentasset.',
        'platform.publicform.',
        'tasks.task.',
        'ai.studio.'
      ),
      'crm.contact.read',
      'crm.company.read',
      'crm.lead.read',
    ]),
    fieldPermissions: [],
    dataScope: 'tenant',
  },
];

const seedTenantAccessControl = async ({ tenantId, actorId = null, models = {} }) => {
  const Permission = getModel('Permission', models);
  const Role = getModel('Role', models);

  const permissions = [];
  for (const permission of buildPermissionCatalog()) {
    const record = await Permission.findOneAndUpdate(
      { tenant: tenantId, key: permission.key },
      {
        $set: {
          ...permission,
          tenant: tenantId,
          removed: false,
        },
        $setOnInsert: {
          createdBy: actorId,
          assignedTo: actorId,
        },
      },
      { upsert: true, new: true, setDefaultsOnInsert: true }
    );
    permissions.push(record);
  }

  const roles = [];
  for (const role of DEFAULT_ROLE_DEFINITIONS) {
    const record = await Role.findOneAndUpdate(
      { tenant: tenantId, key: role.key },
      {
        $set: {
          ...role,
          tenant: tenantId,
          removed: false,
        },
        $setOnInsert: {
          createdBy: actorId,
          assignedTo: actorId,
        },
      },
      { upsert: true, new: true, setDefaultsOnInsert: true }
    );
    roles.push(record);
  }

  return { permissions, roles };
};

module.exports = {
  buildPermissionCatalog,
  DEFAULT_ROLE_DEFINITIONS,
  seedTenantAccessControl,
};
