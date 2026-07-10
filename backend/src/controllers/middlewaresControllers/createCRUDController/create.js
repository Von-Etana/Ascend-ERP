const { auditLog } = require('@/services/platform/auditLogger');
const { publishAutomationEvent } = require('@/services/automation/eventBus');

const create = async (Model, req, res) => {
  // Creating a new document in the collection
  req.body.removed = false;
  if (Model.schema.path('tenant') && req.tenantId) req.body.tenant = req.tenantId;
  if (Model.schema.path('createdBy') && req.admin?._id) req.body.createdBy = req.admin._id;
  const result = await new Model({
    ...req.body,
  }).save();

  const entity = Model.modelName.toLowerCase();
  await auditLog({
    req,
    action: 'create',
    entity,
    entityId: result._id,
    after: result,
  });
  await publishAutomationEvent({
    tenant: req.tenantId,
    type: `${entity}.created`,
    sourceModule: entity,
    entity,
    entityId: result._id,
    actor: req.admin?._id,
    payload: result,
  });

  // Returning successfull response
  return res.status(200).json({
    success: true,
    result,
    message: 'Successfully Created the document in Model ',
  });
};

module.exports = create;
