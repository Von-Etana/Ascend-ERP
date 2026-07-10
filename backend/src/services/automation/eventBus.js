const mongoose = require('mongoose');
const { eventMatchesRule } = require('./ruleMatcher');

const publishAutomationEvent = async ({
  tenant,
  type,
  sourceModule,
  entity,
  entityId,
  actor,
  payload = {},
}) => {
  if (!mongoose.models.AutomationEvent) return null;

  const AutomationEvent = mongoose.model('AutomationEvent');
  const event = await AutomationEvent.create({
    tenant,
    type,
    sourceModule,
    entity,
    entityId,
    actor,
    payload,
    status: 'queued',
  });

  await enqueueMatchingRules(event);
  return event;
};

const enqueueMatchingRules = async (event) => {
  if (!mongoose.models.AutomationRule || !mongoose.models.Job) return;

  const AutomationRule = mongoose.model('AutomationRule');
  const Job = mongoose.model('Job');
  const rules = await AutomationRule.find({
    tenant: event.tenant,
    enabled: true,
    'trigger.type': 'event',
  }).exec();

  const jobs = rules
    .filter((rule) => eventMatchesRule(rule, event))
    .map((rule) => ({
      tenant: event.tenant,
      type: 'automation.run',
      status: 'queued',
      payload: {
        event: event._id,
        rule: rule._id,
      },
      runAfter: new Date(),
    }));

  if (jobs.length) await Job.insertMany(jobs);
};

module.exports = { publishAutomationEvent };
