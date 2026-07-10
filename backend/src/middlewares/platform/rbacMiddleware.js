const { canAccessAction, permissionFor } = require('@/services/platform/accessControl');

const ACTION_BY_METHOD = {
  GET: 'read',
  POST: 'create',
  PATCH: 'update',
  PUT: 'update',
  DELETE: 'delete',
};

const rbacMiddleware = (req, res, next) => {
  if (!req.admin) return next();

  const entity = req.path.split('/').filter(Boolean)[0];
  if (!entity) return next();

  const action = ACTION_BY_METHOD[req.method] || 'read';
  const permission = permissionFor(entity, action);

  if (!canAccessAction(req.admin, permission)) {
    return res.status(403).json({
      success: false,
      result: null,
      message: `Missing permission: ${permission}`,
    });
  }

  next();
};

module.exports = rbacMiddleware;
