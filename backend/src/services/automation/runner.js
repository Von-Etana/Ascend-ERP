const mongoose = require('mongoose');
const { createProviderRegistry, createProviderRegistryForTenant } = require('../integrations/providerRegistry');
const { executeAutomationAction } = require('./actionExecutor');

const getDefaultModels = () => mongoose.models;

const claimDueJob = ({ models, now }) =>
  models.Job.findOneAndUpdate(
    {
      type: 'automation.run',
      status: 'queued',
      runAfter: { $lte: now },
    },
    {
      $set: { status: 'running' },
      $inc: { attempts: 1 },
    },
    {
      new: true,
      sort: { runAfter: 1, created: 1 },
    }
  ).exec();

const loadRunInputs = async ({ models, job }) => {
  const [rule, event] = await Promise.all([
    models.AutomationRule.findOne({ _id: job.payload.rule, removed: false }).exec(),
    models.AutomationEvent.findOne({ _id: job.payload.event, removed: false }).exec(),
  ]);

  if (!rule) throw new Error('Automation rule not found');
  if (!event) throw new Error('Automation event not found');

  return { rule, event };
};

const processAutomationJob = async ({
  models = getDefaultModels(),
  providers,
  now = new Date(),
} = {}) => {
  const job = await claimDueJob({ models, now });
  if (!job) return { processed: false, reason: 'no_due_job' };
  const resolvedProviders =
    providers ||
    (await createProviderRegistryForTenant({ tenantId: job.tenant, models })) ||
    createProviderRegistry();

  let run = null;
  try {
    const { rule, event } = await loadRunInputs({ models, job });
    run = await models.AutomationRun.create({
      tenant: job.tenant,
      rule: rule._id,
      event: event._id,
      status: 'running',
      actionResults: [],
    });

    const actionResults = [];
    for (const action of rule.actions || []) {
      actionResults.push(
        await executeAutomationAction({ action, event, models, providers: resolvedProviders })
      );
    }

    run.status = 'succeeded';
    run.actionResults = actionResults;
    await run.save();

    job.status = 'succeeded';
    job.lastError = undefined;
    await job.save();

    return { processed: true, job, run };
  } catch (error) {
    if (run) {
      run.status = 'failed';
      run.error = error.message;
      await run.save();
    }

    job.status = 'failed';
    job.lastError = error.message;
    await job.save();

    return { processed: false, job, run, error };
  }
};

const processDueAutomationJobs = async ({ limit = 10, models, providers, now = new Date() } = {}) => {
  const results = [];
  for (let index = 0; index < limit; index += 1) {
    const result = await processAutomationJob({ models, providers, now });
    if (!result.processed && result.reason === 'no_due_job') break;
    results.push(result);
  }
  return results;
};

module.exports = {
  processAutomationJob,
  processDueAutomationJobs,
};
