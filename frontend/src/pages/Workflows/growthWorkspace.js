export const CHANNEL_OPTIONS = [
  { value: 'email', label: 'Email' },
  { value: 'whatsapp', label: 'WhatsApp' },
  { value: 'facebook', label: 'Facebook' },
  { value: 'instagram', label: 'Instagram' },
  { value: 'multi', label: 'Multi-channel' },
];

export const CONTENT_TYPE_OPTIONS = [
  { value: 'newsletter', label: 'Newsletter' },
  { value: 'email', label: 'Email' },
  { value: 'blog', label: 'Blog' },
  { value: 'caption', label: 'Caption' },
  { value: 'flyer_copy', label: 'Flyer Copy' },
  { value: 'script', label: 'Script' },
];

export const AUTOMATION_TEMPLATES = [
  {
    key: 'deal-won',
    label: 'Deal Won Follow-up',
    draft: {
      name: 'Deal Won Follow-up',
      description: 'Creates a follow-up task and thank-you content when a deal is won.',
      triggerType: 'event',
      eventType: 'crm.deal.won',
      conditions: [{ field: 'payload.amount', operator: 'gte', value: 1 }],
      actions: [
        { type: 'create_task', config: { title: 'Welcome new customer', priority: 'high' } },
        { type: 'send_email', config: { subject: 'Thank you for choosing us' } },
      ],
    },
  },
  {
    key: 'lead-reengagement',
    label: 'Lead Re-engagement',
    draft: {
      name: 'Lead Re-engagement',
      description: 'Reaches back out when a lead is inactive for too long.',
      triggerType: 'event',
      eventType: 'crm.lead.updated',
      conditions: [{ field: 'payload.status', operator: 'eq', value: 'inactive' }],
      actions: [
        { type: 'generate_content', config: { type: 'email' } },
        { type: 'create_task', config: { title: 'Review inactive lead', priority: 'medium' } },
      ],
    },
  },
  {
    key: 'campaign-response',
    label: 'Campaign Response Task',
    draft: {
      name: 'Campaign Response Task',
      description: 'Creates a follow-up task when a campaign engagement event lands.',
      triggerType: 'event',
      eventType: 'marketing.campaign.clicked',
      conditions: [{ field: 'payload.score', operator: 'gte', value: 10 }],
      actions: [{ type: 'create_task', config: { title: 'Follow up on campaign click', priority: 'high' } }],
    },
  },
  {
    key: 'abandoned-lead',
    label: 'Abandoned Lead Reminder',
    draft: {
      name: 'Abandoned Lead Reminder',
      description: 'Sends a reminder and notifies the owner when a lead drops off.',
      triggerType: 'field_change',
      eventType: 'crm.lead.updated',
      conditions: [{ field: 'payload.stage', operator: 'eq', value: 'abandoned' }],
      actions: [
        { type: 'send_whatsapp', config: {} },
        { type: 'create_task', config: { title: 'Recover abandoned lead', priority: 'high' } },
      ],
    },
  },
  {
    key: 'post-invoice',
    label: 'Post-Invoice Thank You',
    draft: {
      name: 'Post-Invoice Thank You',
      description: 'Thanks the customer after invoice payment and records the touchpoint.',
      triggerType: 'event',
      eventType: 'finance.invoice.payment_recorded',
      conditions: [{ field: 'payload.status', operator: 'eq', value: 'paid' }],
      actions: [
        { type: 'send_email', config: { subject: 'Payment received, thank you' } },
        { type: 'create_task', config: { title: 'Check upsell opportunity', priority: 'low' } },
      ],
    },
  },
];

export const buildAiBriefPayload = (values = {}) => ({
  type: values.type || 'newsletter',
  prompt: values.prompt || '',
  brandContext: {
    brandName: values.brandName,
    goal: values.goal,
    channel: values.channel,
    audience: values.audience,
    brandVoice: values.brandVoice,
    cta: values.cta,
    offerContext: values.offerContext,
    campaignId: values.linkedCampaignId,
    audienceSegment: values.linkedSegmentId,
  },
});

export const buildAudienceSummary = (segment) => {
  if (!segment) return 'No audience selected yet.';
  const filterCount = Array.isArray(segment.filters) ? segment.filters.length : 0;
  const base = segment.description || segment.name;
  return `${base}${filterCount ? ` • ${filterCount} filter${filterCount === 1 ? '' : 's'}` : ''}`;
};

export const buildCampaignChecklist = (values = {}, context = {}) => [
  {
    key: 'audience',
    label: 'Audience selected',
    done: Boolean(values.audienceSegment),
  },
  {
    key: 'content',
    label: 'Content drafted',
    done: Boolean(context.draftContent || values.body || values.caption || values.prompt),
  },
  {
    key: 'schedule',
    label: 'Schedule set',
    done: values.scheduleMode === 'now' || Boolean(values.scheduledAt),
  },
  {
    key: 'channel',
    label: 'Delivery channels confirmed',
    done: Boolean(values.channel),
  },
  {
    key: 'followup',
    label: 'Automation follow-up attached or skipped',
    done: values.followUpMode === 'skip' || Boolean(values.followUpRuleId || values.followUpTaskTitle),
  },
];

export const summarizeAction = (action = {}) => {
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

export const serializeAutomationDraft = (values = {}) => ({
  name: values.name,
  description: values.description,
  enabled: values.enabled !== false,
  trigger: {
    type: values.triggerType || 'event',
    eventType: values.eventType,
    schedule: values.schedule,
  },
  conditions: (values.conditions || []).filter((item) => item?.field).map((item) => ({
    field: item.field,
    operator: item.operator || 'eq',
    value: item.value,
  })),
  actions: (values.actions || []).filter((item) => item?.type).map((item) => ({
    type: item.type,
    config: item.config || {},
  })),
});

export const summarizeAutomationRule = (rule = {}) => ({
  triggerSummary:
    rule.trigger?.type === 'time'
      ? `Schedule ${rule.trigger?.schedule || ''}`.trim()
      : `${rule.trigger?.type || 'event'}${rule.trigger?.eventType ? `: ${rule.trigger.eventType}` : ''}`,
  actionSummaries: (rule.actions || []).map(summarizeAction),
});

export const buildCampaignLaunchPayload = (values = {}, context = {}) => {
  const body = context.draftContent || values.body || values.prompt || '';
  const caption = context.draftContent || values.caption || values.prompt || '';

  return {
    name: values.name,
    channel: values.channel,
    audienceSegment: values.audienceSegment,
    scheduleMode: values.scheduleMode || 'now',
    scheduledAt: values.scheduledAt?.toISOString?.() || values.scheduledAt || undefined,
    assetType: values.assetType || 'email',
    subject: values.subject,
    body,
    caption,
    mediaUrl: context.mediaUrl,
    socialProviders: values.channel === 'multi' ? ['facebook', 'instagram'] : undefined,
    status: values.scheduleMode === 'now' ? 'running' : 'scheduled',
    steps: [
      {
        subject: values.subject,
        body,
        type: values.assetType || 'email',
      },
    ],
  };
};

export const createHandoffState = ({ source, campaign, asset, segment, automationRule, content } = {}) => ({
  source,
  campaign,
  asset,
  segment,
  automationRule,
  content,
  createdAt: new Date().toISOString(),
});
