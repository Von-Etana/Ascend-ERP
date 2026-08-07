const { disabledResult } = require('@/services/integrations/disabledProvider');

const createSocialPublisher = (env = process.env, fetchImpl = global.fetch) => ({
  async publish(channel, payload = {}) {
    const configs = {
      facebook: [env.META_ACCESS_TOKEN, payload.endpoint || `${env.META_API_URL || 'https://graph.facebook.com/v20.0'}/${payload.accountId}/feed`],
      instagram: [env.META_ACCESS_TOKEN, payload.endpoint || `${env.META_API_URL || 'https://graph.facebook.com/v20.0'}/${payload.accountId}/media`],
      whatsapp: [env.META_ACCESS_TOKEN, `${env.META_API_URL || 'https://graph.facebook.com/v20.0'}/${env.META_WHATSAPP_PHONE_NUMBER_ID}/messages`],
      linkedin: [env.LINKEDIN_ACCESS_TOKEN, payload.endpoint || 'https://api.linkedin.com/v2/ugcPosts'],
      x: [env.X_ACCESS_TOKEN, payload.endpoint || 'https://api.x.com/2/tweets'],
    };
    const [token, endpoint] = configs[channel] || [];
    if (!token || !endpoint) return disabledResult(channel, 'publish', payload);
    const response = await fetchImpl(endpoint, { method: 'POST', headers: { Authorization: `Bearer ${token}`, 'Content-Type': 'application/json' }, body: JSON.stringify(payload.body || payload) });
    const body = await response.json().catch(() => ({}));
    if (!response.ok) throw new Error(body.error?.message || body.message || `${channel} publishing failed`);
    return { provider: channel, disabled: false, providerPostId: body.id || body.data?.id, raw: body };
  },
});

module.exports = { createSocialPublisher };
