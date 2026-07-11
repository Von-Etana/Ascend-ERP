const { createProviderRegistry } = require('@/services/integrations/providerRegistry');

const providerOrder = (policy = {}) => [...new Set([policy.provider || 'openai', ...(policy.fallbackProviders || []), 'kimi'])];

const estimateUsage = (prompt = '', content = '') => {
  const inputTokens = Math.ceil(String(prompt).length / 4);
  const outputTokens = Math.ceil(String(content || '').length / 4);
  return { inputTokens, outputTokens, totalTokens: inputTokens + outputTokens };
};

const requestModel = async ({ policy = {}, action = 'agentRun', instruction, prompt, providers, timeoutMs = 45000 }) => {
  const registry = providers || createProviderRegistry(process.env);
  const errors = [];
  for (const name of providerOrder(policy)) {
    const provider = registry[name];
    if (!provider?.request) continue;
    try {
      const result = await Promise.race([
        provider.request(action, { instruction, prompt, temperature: policy.temperature, model: policy.model }),
        new Promise((_, reject) => setTimeout(() => reject(new Error(`${name} timed out`)), timeoutMs)),
      ]);
      const content = result.content || result.message || buildMockContent({ action, prompt, provider: name });
      return { ...result, content, provider: name, usage: result.usage || estimateUsage(prompt, content) };
    } catch (error) {
      errors.push(`${name}: ${error.message}`);
    }
  }
  throw new Error(`No LLM provider completed the request. ${errors.join('; ')}`);
};

const buildMockContent = ({ action, prompt, provider }) =>
  `## ${action === 'assistant' ? 'Agent setup recommendation' : 'Agent result'}\n\n` +
  `Prepared in development mode through **${provider}**.\n\n` +
  `### Recommended next steps\n\n1. Review the objective and audience.\n2. Confirm the selected knowledge and tools.\n3. Approve any external or financial action before execution.\n\n` +
  `**Working brief:** ${String(prompt || '').slice(0, 500)}`;

module.exports = { requestModel, estimateUsage, providerOrder };
