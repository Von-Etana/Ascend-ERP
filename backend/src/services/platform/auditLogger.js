const mongoose = require('mongoose');

const auditLog = async ({ req, action, entity, entityId, before = null, after = null }) => {
  if (!mongoose.models.AuditLog) return null;

  return mongoose.model('AuditLog').create({
    tenant: req.tenantId,
    actor: req.admin?._id,
    action,
    entity,
    entityId,
    before,
    after,
    ip: req.ip,
  });
};

module.exports = { auditLog };
