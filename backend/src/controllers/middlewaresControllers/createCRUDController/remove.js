const { buildTenantQuery } = require('@/services/platform/accessControl');
const { auditLog } = require('@/services/platform/auditLogger');
const { publishAutomationEvent } = require('@/services/automation/eventBus');

const remove = async (Model, req, res) => {
  // Find the document by id and delete it
  let updates = {
    removed: true,
  };
  // Find the document by id and delete it
  const result = await Model.findOneAndUpdate(
    {
      _id: req.params.id,
      ...buildTenantQuery(Model, req),
    },
    { $set: updates },
    {
      new: true, // return the new result instead of the old one
    }
  ).exec();
  // If no results found, return document not found
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
      action: 'delete',
      entity,
      entityId: result._id,
      after: result,
    });
    await publishAutomationEvent({
      tenant: req.tenantId,
      type: `${entity}.deleted`,
      sourceModule: entity,
      entity,
      entityId: result._id,
      actor: req.admin?._id,
      payload: result,
    });
    return res.status(200).json({
      success: true,
      result,
      message: 'Successfully Deleted the document ',
    });
  }
};

module.exports = remove;
