const mongoose = require('mongoose');
const { publishAutomationEvent } = require('@/services/automation/eventBus');

const updateStage = async (req, res) => {
  const Deal = mongoose.model('Deal');
  const PipelineStage = mongoose.model('PipelineStage');
  const { stageId } = req.body;

  const stage = await PipelineStage.findOne({
    _id: stageId,
    removed: false,
    ...(req.tenantId ? { tenant: req.tenantId } : {}),
  }).exec();

  if (!stage) return res.status(404).json({ success: false, result: null, message: 'Stage not found' });

  const deal = await Deal.findOneAndUpdate(
    {
      _id: req.params.id,
      removed: false,
      ...(req.tenantId ? { tenant: req.tenantId } : {}),
    },
    {
      stage: stage._id,
      status: stage.isWon ? 'won' : stage.isLost ? 'lost' : 'open',
    },
    { new: true, runValidators: true }
  ).exec();

  if (!deal) return res.status(404).json({ success: false, result: null, message: 'Deal not found' });

  await publishAutomationEvent({
    tenant: req.tenantId,
    type: stage.isWon ? 'crm.deal.won' : 'crm.deal.stage_changed',
    sourceModule: 'crm',
    entity: 'deal',
    entityId: deal._id,
    actor: req.admin?._id,
    payload: { deal, stage },
  });

  return res.status(200).json({ success: true, result: deal, message: 'Deal stage updated' });
};

module.exports = { updateStage };
