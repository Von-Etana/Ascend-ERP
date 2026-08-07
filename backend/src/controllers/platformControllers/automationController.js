const mongoose = require('mongoose');
const { processDueAutomationJobs } = require('@/services/automation/runner');

const runDue = async (req, res) => {
  const limit = Number(req.body?.limit || req.query?.limit || 10);
  const results = await processDueAutomationJobs({ limit });

  return res.status(200).json({
    success: true,
    result: {
      processed: results.length,
      results: results.map((item) => ({
        processed: item.processed,
        job: item.job?._id,
        run: item.run?._id,
        status: item.run?.status || item.job?.status,
        error: item.error?.message,
      })),
    },
    message: 'Automation jobs processed',
  });
};

const runHistory = async (req, res) => {
  const limit = Number(req.query.limit || 20);
  const runs = await mongoose
    .model('AutomationRun')
    .find({
      removed: false,
      ...(req.tenantId ? { tenant: req.tenantId } : {}),
    })
    .sort({ created: -1 })
    .limit(limit)
    .populate()
    .exec();

  return res.status(200).json({
    success: true,
    result: runs,
    message: 'Automation run history loaded',
  });
};

module.exports = { runDue, runHistory };
