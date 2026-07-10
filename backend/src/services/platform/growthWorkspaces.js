const mongoose = require('mongoose');
const { eventMatchesRule } = require('@/services/automation/ruleMatcher');

const getModel = (name, models = {}) => models[name] || mongoose.model(name);

const formatActionSummary = (action = {}) => {
  const config = action.config || {};
  switch (action.type) {
    case 'create_task':
      return `Create task${config.title ? `: ${config.title}` : ''}`;
    case 'send_email':
      return `Send email${config.subject ? `: ${config.subject}` : ''}`;
    case 'send_whatsapp':
      return 'Send WhatsApp message';
    case 'generate_content':
      return `Generate ${config.type || 'content'}`;
    case 'schedule_social_post':
      return `Schedule ${config.provider || 'social'} post`;
    case 'create_record':
      return `Create ${config.model || 'record'}`;
    case 'update_record':
      return `Update ${config.model || config.entity || 'record'}`;
    default:
      return action.type || 'Action';
  }
};

const formatConditionSummary = (condition = {}) =>
  [condition.field || 'field', condition.operator || 'eq', condition.value].filter((value) => value !== undefined).join(' ');

const describeAudienceSegment = (segment = {}, events = []) => {
  const filterCount = Array.isArray(segment.filters) ? segment.filters.length : 0;
  return {
    id: segment._id,
    name: segment.name,
    description:
      segment.description ||
      (filterCount ? `${filterCount} filter${filterCount === 1 ? '' : 's'} applied` : 'All available contacts'),
    filterCount,
    recentEngagements: events.length,
  };
};

const buildMarketingWorkspaceSummary = async ({ tenantId, models = {} }) => {
  const Campaign = getModel('Campaign', models);
  const AudienceSegment = getModel('AudienceSegment', models);
  const SocialPost = getModel('SocialPost', models);
  const CampaignEvent = getModel('CampaignEvent', models);

  const scope = tenantId ? { tenant: tenantId, removed: false } : { removed: false };

  const [campaigns, segments, socialPosts, events] = await Promise.all([
    Campaign.find(scope).sort({ updated: -1 }).limit(10).lean(),
    AudienceSegment.find(scope).sort({ updated: -1 }).limit(20).lean(),
    SocialPost.find(scope).sort({ scheduledAt: 1, created: -1 }).limit(10).lean(),
    CampaignEvent.find(scope).sort({ created: -1 }).limit(50).lean(),
  ]);

  const metrics = campaigns.reduce(
    (accumulator, campaign) => {
      accumulator.totalCampaigns += 1;
      accumulator.scheduledCampaigns += campaign.status === 'scheduled' ? 1 : 0;
      accumulator.runningCampaigns += campaign.status === 'running' ? 1 : 0;
      accumulator.completedCampaigns += campaign.status === 'completed' ? 1 : 0;
      accumulator.sent += Number(campaign.metrics?.sent || 0);
      accumulator.opened += Number(campaign.metrics?.opened || 0);
      accumulator.clicked += Number(campaign.metrics?.clicked || 0);
      accumulator.converted += Number(campaign.metrics?.converted || 0);
      return accumulator;
    },
    {
      totalCampaigns: 0,
      scheduledCampaigns: 0,
      runningCampaigns: 0,
      completedCampaigns: 0,
      sent: 0,
      opened: 0,
      clicked: 0,
      converted: 0,
    }
  );

  const segmentSummaries = segments.map((segment) =>
    describeAudienceSegment(
      segment,
      events.filter((event) => String(event.segment || event.audienceSegment || '') === String(segment._id || ''))
    )
  );

  return {
    metrics,
    campaigns,
    segments: segmentSummaries,
    scheduledPosts: socialPosts.filter((post) => post.status === 'scheduled'),
    recentPosts: socialPosts,
    recentEvents: events.slice(0, 10),
  };
};

const listAiWorkspaceAssets = async ({ tenantId, filters = {}, models = {} }) => {
  const ContentAsset = getModel('ContentAsset', models);
  const scope = {
    removed: false,
    ...(tenantId ? { tenant: tenantId } : {}),
  };

  if (filters.type) scope.type = filters.type;
  if (filters.provider) scope.provider = filters.provider;
  if (filters.createdBy) scope.createdBy = filters.createdBy;

  if (filters.campaignId) {
    scope['brandContext.campaignId'] = filters.campaignId;
  }

  return ContentAsset.find(scope).sort({ created: -1 }).limit(Number(filters.limit || 30)).lean();
};

const previewAutomationRule = async ({ tenantId, draft = {}, eventId, event: inlineEvent, models = {} }) => {
  let event = inlineEvent;

  if (!event && eventId) {
    const AutomationEvent = getModel('AutomationEvent', models);
    event = await AutomationEvent.findOne({
      _id: eventId,
      removed: false,
      ...(tenantId ? { tenant: tenantId } : {}),
    }).lean();
  }

  if (!event) {
    throw new Error('Automation preview requires an event payload or event id');
  }

  const matched = eventMatchesRule(
    {
      ...draft,
      enabled: true,
    },
    event
  );

  return {
    matched,
    event,
    triggerSummary:
      draft.trigger?.type === 'time'
        ? `Runs on schedule ${draft.trigger?.schedule || ''}`.trim()
        : `${draft.trigger?.type || 'event'}${draft.trigger?.eventType ? `: ${draft.trigger.eventType}` : ''}`,
    conditionSummaries: (draft.conditions || []).map(formatConditionSummary),
    actionSummaries: (draft.actions || []).map(formatActionSummary),
  };
};

const buildAutomationWorkspaceSummary = async ({ tenantId, models = {} }) => {
  const AutomationRun = getModel('AutomationRun', models);
  const AutomationEvent = getModel('AutomationEvent', models);
  const Job = getModel('Job', models);
  const AutomationRule = getModel('AutomationRule', models);

  const scope = tenantId ? { tenant: tenantId, removed: false } : { removed: false };

  const [runs, events, jobs, rules] = await Promise.all([
    AutomationRun.find(scope).sort({ created: -1 }).limit(20).lean(),
    AutomationEvent.find(scope).sort({ occurredAt: -1, created: -1 }).limit(20).lean(),
    Job.find({ ...scope, type: 'automation.run' }).sort({ created: -1 }).limit(20).lean(),
    AutomationRule.find(scope).sort({ updated: -1 }).limit(20).lean(),
  ]);

  return {
    metrics: {
      queuedJobs: jobs.filter((job) => job.status === 'queued').length,
      failedRuns: runs.filter((run) => run.status === 'failed').length,
      runningRuns: runs.filter((run) => run.status === 'running').length,
      activeRules: rules.filter((rule) => rule.enabled !== false).length,
    },
    recentRuns: runs,
    recentEvents: events,
    recentJobs: jobs,
    starterRules: rules.slice(0, 5).map((rule) => ({
      _id: rule._id,
      name: rule.name,
      triggerSummary:
        rule.trigger?.type === 'time'
          ? `Schedule ${rule.trigger?.schedule || ''}`.trim()
          : `${rule.trigger?.type || 'event'}${rule.trigger?.eventType ? `: ${rule.trigger.eventType}` : ''}`,
      actionSummaries: (rule.actions || []).map(formatActionSummary),
    })),
  };
};

const launchCampaignWorkflow = async ({ tenantId, adminId, payload = {}, models = {} }) => {
  const Campaign = getModel('Campaign', models);
  const SocialPost = getModel('SocialPost', models);

  const scheduledAt = payload.scheduleMode === 'now' ? new Date() : payload.scheduledAt || new Date();
  const steps = Array.isArray(payload.steps) && payload.steps.length
    ? payload.steps
    : [
        {
          subject: payload.subject,
          body: payload.body,
          type: payload.assetType || 'email',
        },
      ];

  const campaign = await Campaign.create({
    tenant: tenantId,
    createdBy: adminId,
    assignedTo: adminId,
    name: payload.name,
    channel: payload.channel || 'email',
    audienceSegment: payload.audienceSegment || undefined,
    status: payload.status || 'scheduled',
    scheduledAt,
    steps,
    metrics: payload.metrics || undefined,
  });

  const socialProviders = Array.isArray(payload.socialProviders)
    ? payload.socialProviders
    : ['facebook', 'instagram'].includes(payload.channel)
      ? [payload.channel]
      : payload.channel === 'multi'
        ? ['facebook', 'instagram']
        : [];

  const socialPosts = [];
  for (const provider of socialProviders) {
    const post = await SocialPost.create({
      tenant: tenantId,
      createdBy: adminId,
      assignedTo: adminId,
      campaign: campaign._id,
      provider,
      caption: payload.caption || payload.body || '',
      mediaUrl: payload.mediaUrl,
      scheduledAt,
      status: 'scheduled',
    });
    socialPosts.push(post);
  }

  return {
    campaign,
    socialPosts,
    readiness: {
      hasAudience: Boolean(payload.audienceSegment),
      hasContent: Boolean(payload.body || payload.caption || steps[0]?.body),
      hasSchedule: Boolean(scheduledAt),
      hasChannel: Boolean(payload.channel),
    },
  };
};

module.exports = {
  buildMarketingWorkspaceSummary,
  listAiWorkspaceAssets,
  previewAutomationRule,
  buildAutomationWorkspaceSummary,
  launchCampaignWorkflow,
  formatActionSummary,
  formatConditionSummary,
  describeAudienceSegment,
};
