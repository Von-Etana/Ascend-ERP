const PROVIDER_DEFINITIONS = {
  openai: {
    key: 'openai',
    label: 'OpenAI',
    category: 'llm',
    supportsLiveTest: true,
    envMap: {
      apiKey: 'OPENAI_API_KEY',
      baseUrl: 'OPENAI_API_URL',
      model: 'OPENAI_MODEL',
    },
    publicFields: [
      { key: 'model', label: 'Model', placeholder: 'gpt-4o-mini' },
      { key: 'baseUrl', label: 'Base URL', placeholder: 'https://api.openai.com/v1/chat/completions' },
    ],
    secretFields: [{ key: 'apiKey', label: 'API Key', inputType: 'password' }],
  },
  kimi: {
    key: 'kimi',
    label: 'Kimi',
    category: 'llm',
    supportsLiveTest: true,
    envMap: {
      apiKey: 'KIMI_API_KEY',
      baseUrl: 'KIMI_API_URL',
      model: 'KIMI_MODEL',
    },
    publicFields: [
      { key: 'model', label: 'Model', placeholder: 'kimi-k2-0711-preview' },
      { key: 'baseUrl', label: 'Base URL', placeholder: 'https://api.moonshot.ai/v1/chat/completions' },
    ],
    secretFields: [{ key: 'apiKey', label: 'API Key', inputType: 'password' }],
  },
  hermes: {
    key: 'hermes',
    label: 'Hermes',
    category: 'orchestration',
    supportsLiveTest: false,
    envMap: {
      apiKey: 'HERMES_API_KEY',
      baseUrl: 'HERMES_API_URL',
    },
    publicFields: [{ key: 'baseUrl', label: 'Base URL', placeholder: 'https://api.hermes.local/orchestrate' }],
    secretFields: [{ key: 'apiKey', label: 'API Key', inputType: 'password' }],
  },
  fal: {
    key: 'fal',
    label: 'Fal.ai',
    category: 'media',
    supportsLiveTest: false,
    envMap: {
      apiKey: 'FAL_KEY',
      baseUrl: 'FAL_API_URL',
    },
    publicFields: [{ key: 'baseUrl', label: 'API URL', placeholder: 'https://fal.run/fal-ai/flux-pro/v1.1' }],
    secretFields: [{ key: 'apiKey', label: 'API Key', inputType: 'password' }],
  },
  resend: {
    key: 'resend',
    label: 'Resend',
    category: 'email',
    supportsLiveTest: true,
    envMap: {
      apiKey: 'RESEND_API',
      fromEmail: 'RESEND_FROM',
    },
    publicFields: [{ key: 'fromEmail', label: 'Default From Email', placeholder: 'IDURAR <no-reply@example.com>' }],
    secretFields: [{ key: 'apiKey', label: 'API Key', inputType: 'password' }],
  },
  meta: {
    key: 'meta',
    label: 'Meta',
    category: 'social',
    supportsLiveTest: true,
    envMap: {
      accessToken: 'META_ACCESS_TOKEN',
      apiUrl: 'META_API_URL',
      whatsappPhoneNumberId: 'META_WHATSAPP_PHONE_NUMBER_ID',
      facebookPageId: 'META_FACEBOOK_PAGE_ID',
      instagramAccountId: 'META_INSTAGRAM_ACCOUNT_ID',
    },
    publicFields: [
      { key: 'apiUrl', label: 'API URL', placeholder: 'https://graph.facebook.com/v20.0' },
      { key: 'whatsappPhoneNumberId', label: 'WhatsApp Phone Number ID' },
      { key: 'facebookPageId', label: 'Facebook Page ID' },
      { key: 'instagramAccountId', label: 'Instagram Account ID' },
    ],
    secretFields: [{ key: 'accessToken', label: 'Access Token', inputType: 'password' }],
  },
  linkedin: {
    key: 'linkedin',
    label: 'LinkedIn',
    category: 'social',
    supportsLiveTest: true,
    envMap: {
      accessToken: 'LINKEDIN_ACCESS_TOKEN',
      orgId: 'LINKEDIN_ORG_ID',
    },
    publicFields: [{ key: 'orgId', label: 'Organization ID' }],
    secretFields: [{ key: 'accessToken', label: 'Access Token', inputType: 'password' }],
  },
  x: {
    key: 'x',
    label: 'X',
    category: 'social',
    supportsLiveTest: true,
    envMap: {
      accessToken: 'X_ACCESS_TOKEN',
    },
    publicFields: [],
    secretFields: [{ key: 'accessToken', label: 'Access Token', inputType: 'password' }],
  },
};

const getProviderDefinition = (provider) => PROVIDER_DEFINITIONS[String(provider || '').toLowerCase()] || null;
const listProviderDefinitions = () => Object.values(PROVIDER_DEFINITIONS);

module.exports = {
  PROVIDER_DEFINITIONS,
  getProviderDefinition,
  listProviderDefinitions,
};
