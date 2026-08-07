const { buildTenantQuery } = require('@/services/platform/accessControl');
const { auditLog } = require('@/services/platform/auditLogger');
const { publishAutomationEvent } = require('@/services/automation/eventBus');

const update = async (Model, req, res) => {
  // Find document by id and updates with the required fields
  req.body.removed = false;
  delete req.body.tenant;
  const before = await Model.findOne({
    _id: req.params.id,
    removed: false,
    ...buildTenantQuery(Model, req),
  }).exec();
  const result = await Model.findOneAndUpdate(
    {
      _id: req.params.id,
      removed: false,
      ...buildTenantQuery(Model, req),
    },
    req.body,
    {
      new: true, // return the new result instead of the old one
      runValidators: true,
    }
  ).exec();
  if (!result) {
    return res.status(404).json({
      success: false,
      result: null,
      message: 'No document found ',
    });
  } else {
    const entity = Model.modelName.toLowerCase();
    await auditLog({
      req,
      action: 'update',
      entity,
      entityId: result._id,
      before,
      after: result,
    });
    await publishAutomationEvent({
      tenant: req.tenantId,
      type: `${entity}.updated`,
      sourceModule: entity,
      entity,
      entityId: result._id,
      actor: req.admin?._id,
      payload: { before, after: result },
    });
    return res.status(200).json({
      success: true,
      result,
      message: 'we update this document ',
    });
  }
};

module.exports = update;
