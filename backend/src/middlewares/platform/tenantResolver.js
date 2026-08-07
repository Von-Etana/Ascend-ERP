const resolveTenant = (req, res, next) => {
  const headerTenant = req.headers['x-tenant-id'];
  const adminTenant = req.admin?.tenant?._id || req.admin?.tenant;

  req.tenantId = adminTenant || headerTenant || null;
  next();
};

module.exports = resolveTenant;
