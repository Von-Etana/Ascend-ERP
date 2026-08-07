import test from 'node:test';
import assert from 'node:assert/strict';

import {
  buildAiBriefPayload,
  buildCampaignChecklist,
  buildCampaignLaunchPayload,
  buildAudienceSummary,
  serializeAutomationDraft,
} from '../src/pages/Workflows/growthWorkspace.js';

test('buildAiBriefPayload maps guided brief fields into generation payload', () => {
  assert.deepEqual(
    buildAiBriefPayload({
      type: 'email',
      prompt: 'Welcome sequence',
      brandName: 'Acme',
      goal: 'Convert signups',
      channel: 'email',
      audience: 'Warm leads',
      brandVoice: 'Direct',
      cta: 'Book a demo',
      offerContext: 'Starter plan',
      linkedCampaignId: 'campaign-1',
      linkedSegmentId: 'segment-1',
    }),
    {
      type: 'email',
      prompt: 'Welcome sequence',
      brandContext: {
        brandName: 'Acme',
        goal: 'Convert signups',
        channel: 'email',
        audience: 'Warm leads',
        brandVoice: 'Direct',
        cta: 'Book a demo',
        offerContext: 'Starter plan',
        campaignId: 'campaign-1',
        audienceSegment: 'segment-1',
      },
    }
  );
});

test('buildAudienceSummary renders readable segment context', () => {
  assert.equal(
    buildAudienceSummary({
      name: 'Warm leads',
      description: 'Contacts who engaged this week',
      filters: [{ field: 'leadScore' }, { field: 'lastSeenAt' }],
    }),
    'Contacts who engaged this week • 2 filters'
  );
});

test('buildCampaignChecklist tracks review readiness across flow steps', () => {
  const checklist = buildCampaignChecklist(
    {
      audienceSegment: 'segment-1',
      channel: 'email',
      scheduleMode: 'scheduled',
      scheduledAt: '2026-07-10T12:00:00.000Z',
      followUpMode: 'skip',
    },
    { draftContent: 'Welcome aboard' }
  );

  assert.deepEqual(
    checklist.map((item) => item.done),
    [true, true, true, true, true]
  );
});

test('serializeAutomationDraft converts builder state into backend rule shape', () => {
  assert.deepEqual(
    serializeAutomationDraft({
      name: 'Lead reminder',
      description: 'Follow up inactive leads',
      triggerType: 'event',
      eventType: 'crm.lead.updated',
      schedule: '',
      conditions: [{ field: 'payload.status', operator: 'eq', value: 'inactive' }],
      actions: [{ type: 'create_task', config: { title: 'Call lead' } }],
    }),
    {
      name: 'Lead reminder',
      description: 'Follow up inactive leads',
      enabled: true,
      trigger: {
        type: 'event',
        eventType: 'crm.lead.updated',
        schedule: '',
      },
      conditions: [{ field: 'payload.status', operator: 'eq', value: 'inactive' }],
      actions: [{ type: 'create_task', config: { title: 'Call lead' } }],
    }
  );
});

test('buildCampaignLaunchPayload packages guided campaign state for workflow endpoint', () => {
  const payload = buildCampaignLaunchPayload(
    {
      name: 'July nurture',
      channel: 'multi',
      audienceSegment: 'segment-1',
      scheduleMode: 'now',
      assetType: 'email',
      subject: 'Hello there',
      prompt: 'Draft a welcome note',
    },
    { draftContent: 'Welcome to the program', mediaUrl: 'https://cdn.example.com/flyer.png' }
  );

  assert.equal(payload.status, 'running');
  assert.equal(payload.body, 'Welcome to the program');
  assert.equal(payload.caption, 'Welcome to the program');
  assert.deepEqual(payload.socialProviders, ['facebook', 'instagram']);
});
