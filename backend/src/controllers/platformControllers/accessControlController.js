const {
  buildPermissionCatalog,
  seedTenantAccessControl,
} = require('@/services/platform/defaultAccessControl');
const mongoose = require('mongoose');
const { computeEffectiveAccess } = require('@/services/platform/accessControl');

const __test = {};

const mapAdminAccessSummary = (admin) => {
  const effective = computeEffectiveAccess(admin);

  return {
    _id: admin._id,
    name: [admin.name, admin.surname].filter(Boolean).join(' '),
    email: admin.email,
    enabled: admin.enabled,
    role: admin.role,
    managerId: admin.manager?._id || null,
    manager: admin.manager?.name || admin.manager?.email || '',
    orgUnitId: admin.orgUnit?._id || null,
    orgUnit: admin.orgUnit?.name || '',
    roleRefs: (admin.roleRefs || []).map((role) => ({
      _id: role._id,
      key: role.key,
      name: role.name,
    })),
    directPermissions: admin.permissions || [],
    deniedFields: admin.deniedFields || [],
    fieldPermissions: effective.fieldPermissions || [],
    effectivePermissions: [...effective.permissions].sort(),
  };
};

const listPermissionCatalog = async (req, res) => {
  return res.status(200).json({
    success: true,
    result: buildPermissionCatalog(),
    message: 'Permission catalog loaded',
  });
};

const bootstrapDefaults = async (req, res) => {
  const tenantId = req.tenantId;

  if (!tenantId) {
    return res.status(400).json({
      success: false,
      result: null,
      message: 'Tenant context is required to bootstrap access control defaults',
    });
  }

  const seed = __test.seedTenantAccessControl || seedTenantAccessControl;
  const result = await seed({
    tenantId,
    actorId: req.admin?._id || null,
  });

  return res.status(200).json({
    success: true,
    result: {
      permissions: result.permissions.length,
      roles: result.roles.map((role) => ({
        key: role.key,
        name: role.name,
        dataScope: role.dataScope,
        permissionCount: Array.isArray(role.permissions) ? role.permissions.length : 0,
      })),
    },
    message: 'Default roles and permissions bootstrapped',
  });
};

const listAdminAccess = async (req, res) => {
  const loadAdmins =
    __test.findAdminsWithAccess ||
    (async (tenantId) =>
      mongoose
        .model('Admin')
        .find({
          removed: false,
          ...(tenantId ? { tenant: tenantId } : {}),
        })
        .populate('roleRefs')
        .populate('manager')
        .populate('orgUnit')
        .sort({ created: -1 })
        .exec());

  const admins = await loadAdmins(req.tenantId);

  return res.status(200).json({
    success: true,
    result: admins.map(mapAdminAccessSummary),
    message: 'Admin access assignments loaded',
  });
};

const updateAdminAccess = async (req, res) => {
  const updateAccess =
    __test.updateAdminAccess ||
    (async ({ id, tenantId, body }) =>
      mongoose
        .model('Admin')
        .findOneAndUpdate(
          {
            _id: id,
            removed: false,
            ...(tenantId ? { tenant: tenantId } : {}),
          },
          {
            $set: {
              roleRefs: body.roleRefs || [],
              permissions: body.permissions || [],
              deniedFields: body.deniedFields || [],
              fieldPermissions: body.fieldPermissions || [],
              enabled: typeof body.enabled === 'boolean' ? body.enabled : true,
              manager: body.manager || null,
              orgUnit: body.orgUnit || null,
            },
          },
          { new: true }
        )
        .populate('roleRefs')
        .populate('manager')
        .populate('orgUnit')
        .exec());

  const admin = await updateAccess({
    id: req.params.id,
    tenantId: req.tenantId,
    body: req.body || {},
  });

  if (!admin) {
    return res.status(404).json({
      success: false,
      result: null,
      message: 'Admin user not found',
    });
  }

  return res.status(200).json({
    success: true,
    result: mapAdminAccessSummary(admin),
    message: 'Admin access updated',
  });
};

module.exports = {
  listPermissionCatalog,
  bootstrapDefaults,
  listAdminAccess,
  updateAdminAccess,
  __test,
};
