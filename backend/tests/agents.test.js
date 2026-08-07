const test = require('node:test');
const assert = require('node:assert/strict');

require('module-alias/register');

const { agentTemplates } = require('../src/services/agents/templates');
const { requestModel, providerOrder } = require('../src/services/agents/llmGateway');
const { toolDefinitions, requiresApproval, assertToolAccess } = require('../src/services/agents/toolRegistry');
const { calculateCost } = require('../src/services/agents/costService');
const { isPrivateIp, assertPublicUrl } = require('../src/services/agents/webResearchService');
const { createSocialPublisher } = require('../src/services/agents/socialPublisher');

test('ships every requested specialist agent template', () => {
  const keys = new Set(agentTemplates.map((item) => item.key));
  for (const key of ['marketing_strategy', 'social_media_manager', 'lead_generation', 'competitor_analysis', 'web_research', 'budget_planning', 'expense_intelligence', 'brand_guardian', 'content_production']) {
    assert.equal(keys.has(key), true, `missing ${key}`);
  }
});

test('provider order is stable and de-duplicated', () => {
  assert.deepEqual(providerOrder({ provider: 'openai', fallbackProviders: ['kimi', 'openai'] }), ['openai', 'kimi']);
});

test('LLM gateway falls back after a provider failure and records usage', async () => {
  const providers = {
    openai: { request: async () => { throw new Error('unavailable'); } },
    kimi: { request: async () => ({ content: 'fallback result', usage: { inputTokens: 10, outputTokens: 4, totalTokens: 14 } }) },
  };
  const result = await requestModel({ policy: { provider: 'openai', fallbackProviders: ['kimi'] }, prompt: 'test', providers, timeoutMs: 100 });
  assert.equal(result.provider, 'kimi');
  assert.equal(result.content, 'fallback result');
  assert.equal(result.usage.totalTokens, 14);
});

test('publishing and budget allocation always require approval', () => {
  const agent = { approvalPolicy: { mode: 'autonomous', approvalFor: [] } };
  for (const key of ['social.publish', 'budget.allocate']) {
    const definition = toolDefinitions.find((item) => item.key === key);
    assert.equal(requiresApproval({ definition, agent, testMode: false }), true);
    assert.equal(requiresApproval({ definition, agent, testMode: true }), false);
  }
});

test('tool access cannot exceed actor permissions', () => {
  assert.throws(() => assertToolAccess({ tool: 'social.publish', actor: { permissions: [] } }), /Permission denied/);
  assert.equal(assertToolAccess({ tool: 'social.publish', actor: { role: 'owner' } }).key, 'social.publish');
});

test('agent cost is normalized to NGN', () => {
  const cost = calculateCost({ usage: { inputTokens: 1000, outputTokens: 500 }, provider: 'openai' });
  assert.equal(cost.currency, 'NGN');
  assert.ok(cost.amount > 0);
});

test('web research blocks local and private network targets', async () => {
  assert.equal(isPrivateIp('127.0.0.1'), true);
  assert.equal(isPrivateIp('192.168.1.5'), true);
  await assert.rejects(() => assertPublicUrl('http://internal.example', { lookup: async () => [{ address: '10.0.0.8' }] }), /Private or local/);
  const url = await assertPublicUrl('https://public.example/path', { lookup: async () => [{ address: '8.8.8.8' }] });
  assert.equal(url.hostname, 'public.example');
});

test('social publisher stays disabled without channel credentials', async () => {
  const result = await createSocialPublisher({}).publish('linkedin', { caption: 'Test' });
  assert.equal(result.disabled, true);
  assert.equal(result.provider, 'linkedin');
});
