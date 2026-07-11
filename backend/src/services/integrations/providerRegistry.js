const { disabledResult } = require('./disabledProvider');
const { resolveProviderEnvForTenant } = require('./accountService');

const createResendProvider = (env) => {
  if (!env.RESEND_API) {
    return {
      sendEmail: async (payload) => disabledResult('resend', 'sendEmail', payload),
    };
  }

  const { Resend } = require('resend');
  const resend = new Resend(env.RESEND_API);
  return {
    sendEmail: (payload) =>
      resend.emails.send({
        from: payload.from || env.RESEND_FROM || 'IDURAR <no-reply@example.com>',
        to: payload.to,
        subject: payload.subject,
        html: payload.html,
      }),
  };
};

const parseJsonResponse = async (response, provider) => {
  const body = await response.json().catch(() => ({}));
  if (!response.ok) {
    const message = body.error?.message || body.message || `${provider} request failed`;
    const error = new Error(message);
    error.status = response.status;
    error.body = body;
    throw error;
  }
  return body;
};

const createOpenAiCompatibleProvider = ({ provider, requiredKey, urlKey, modelKey, defaultUrl, defaultModel, env, fetchImpl }) => {
  if (!env[requiredKey]) {
    return {
      request: async (action, payload) => disabledResult(provider, action, payload),
    };
  }

  return {
    request: async (action, payload = {}) => {
      const response = await fetchImpl(env[urlKey] || defaultUrl, {
        method: 'POST',
        headers: {
          Authorization: `Bearer ${env[requiredKey]}`,
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          model: env[modelKey] || defaultModel,
          messages: [
            { role: 'system', content: payload.instruction || `You are the ${provider} content agent.` },
            { role: 'user', content: payload.prompt || JSON.stringify(payload) },
          ],
          temperature: payload.temperature ?? 0.7,
        }),
      });
      const body = await parseJsonResponse(response, provider);
      return {
        provider,
        action,
        disabled: false,
        content: body.choices?.[0]?.message?.content || body.output_text || body.content,
        raw: body,
      };
    },
  };
};

const createFalProvider = (env, fetchImpl) => {
  if (!env.FAL_KEY) {
    return {
      request: async (action, payload) => disabledResult('fal', action, payload),
    };
  }

  return {
    request: async (action, payload = {}) => {
      const response = await fetchImpl(
        env.FAL_API_URL || 'https://fal.run/fal-ai/flux-pro/v1.1',
        {
          method: 'POST',
          headers: {
            Authorization: `Key ${env.FAL_KEY}`,
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            prompt: payload.prompt,
            image_size: payload.imageSize || 'landscape_4_3',
            num_images: payload.numImages || 1,
            brand_context: payload.brandContext,
          }),
        }
      );
      const body = await parseJsonResponse(response, 'fal');
      return {
        provider: 'fal',
        action,
        disabled: false,
        mediaUrl: body.images?.[0]?.url || body.image?.url || body.url,
        raw: body,
      };
    },
  };
};

const createMetaProvider = (env, fetchImpl) => {
  if (!env.META_ACCESS_TOKEN) {
    return {
      request: async (action, payload) => disabledResult('meta', action, payload),
    };
  }

  return {
    request: async (action, payload = {}) => {
      const baseUrl = env.META_API_URL || 'https://graph.facebook.com/v20.0';
      const endpoint =
        action === 'sendWhatsApp'
          ? `${baseUrl}/${env.META_WHATSAPP_PHONE_NUMBER_ID}/messages`
          : payload.endpoint || baseUrl;
      const response = await fetchImpl(endpoint, {
        method: 'POST',
        headers: {
          Authorization: `Bearer ${env.META_ACCESS_TOKEN}`,
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(payload.body || payload),
      });
      const body = await parseJsonResponse(response, 'meta');
      return { provider: 'meta', action, disabled: false, raw: body };
    },
  };
};

const createHermesProvider = (env, fetchImpl) => {
  if (!env.HERMES_API_KEY) {
    return {
      request: async (action, payload) => disabledResult('hermes', action, payload),
    };
  }

  return {
    request: async (action, payload = {}) => {
      const response = await fetchImpl(env.HERMES_API_URL || 'https://api.hermes.local/orchestrate', {
        method: 'POST',
        headers: {
          Authorization: `Bearer ${env.HERMES_API_KEY}`,
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ action, ...payload }),
      });
      const body = await parseJsonResponse(response, 'hermes');
      return { provider: 'hermes', action, disabled: false, raw: body, decision: body.decision };
    },
  };
};

const createProviderRegistry = (env = process.env, options = {}) => {
  const fetchImpl = options.fetch || global.fetch;

  return {
  resend: createResendProvider(env),
  meta: createMetaProvider(env, fetchImpl),
  hermes: createHermesProvider(env, fetchImpl),
  kimi: createOpenAiCompatibleProvider({
    provider: 'kimi',
    requiredKey: 'KIMI_API_KEY',
    urlKey: 'KIMI_API_URL',
    modelKey: 'KIMI_MODEL',
    defaultUrl: 'https://api.moonshot.ai/v1/chat/completions',
    defaultModel: 'kimi-k2-0711-preview',
    env,
    fetchImpl,
  }),
  fal: createFalProvider(env, fetchImpl),
  openai: createOpenAiCompatibleProvider({
    provider: 'openai',
    requiredKey: 'OPENAI_API_KEY',
    urlKey: 'OPENAI_API_URL',
    modelKey: 'OPENAI_MODEL',
    defaultUrl: 'https://api.openai.com/v1/chat/completions',
    defaultModel: 'gpt-4o-mini',
    env,
    fetchImpl,
  }),
};
};

const createProviderRegistryForTenant = async ({
  tenantId,
  env = process.env,
  models = {},
  fetch,
} = {}) => {
  const resolvedEnv = await resolveProviderEnvForTenant({ tenantId, models, env });
  return createProviderRegistry(resolvedEnv, { fetch });
};

module.exports = { createProviderRegistry, createProviderRegistryForTenant };
