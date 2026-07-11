const mongoose = require('mongoose');
const { getProviderDefinition, listProviderDefinitions } = require('./providerCatalog');
const { decryptJson, encryptJson, maskSecret } = require('./secretStore');

const pickFields = (source = {}, keys = []) =>
  keys.reduce((acc, key) => {
    if (source[key] !== undefined && source[key] !== null && source[key] !== '') {
      acc[key] = source[key];
    }
    return acc;
  }, {});

const buildEnvOverrides = (provider, config = {}) => {
  const definition = getProviderDefinition(provider);
  if (!definition) return {};
  return Object.entries(definition.envMap || {}).reduce((acc, [fieldKey, envKey]) => {
    if (config[fieldKey] !== undefined && config[fieldKey] !== null && config[fieldKey] !== '') {
      acc[envKey] = config[fieldKey];
    }
    return acc;
  }, {});
};

const hasEnvFallback = (provider, env = process.env) => {
  const definition = getProviderDefinition(provider);
  if (!definition) return false;
  return Object.values(definition.envMap || {}).some((envKey) => Boolean(env[envKey]));
};

const getIntegrationModel = (models = {}) =>
  models.IntegrationAccount || mongoose.models.IntegrationAccount || null;

const sanitizeIntegrationAccount = (account, env = process.env) => {
  if (!account) return null;
  const source =
    typeof account.toObject === 'function' ? account.toObject({ virtuals: true }) : { ...account };
  const decrypted = source.secretConfigEncrypted
    ? decryptJson(source.secretConfigEncrypted, env)
    : {};
  const secretPreview = (source.secretFields || []).map((field) => ({
    key: field,
    maskedValue: maskSecret(decrypted[field]),
  }));

  return {
    _id: source._id,
    provider: source.provider,
    name: source.name,
    status: source.status,
    enabled: source.enabled,
    publicConfig: source.publicConfig || source.config || {},
    secretFields: source.secretFields || [],
    secretConfigured: Boolean(secretPreview.length),
    secretPreview,
    lastError: source.lastError || '',
    lastTestedAt: source.lastTestedAt || null,
    lastTestStatus: source.lastTestStatus || 'untested',
    lastTestMessage: source.lastTestMessage || '',
    envFallbackActive: hasEnvFallback(source.provider, env),
    created: source.created,
    updated: source.updated,
  };
};

const loadIntegrationAccounts = async ({ tenantId, models = {}, env = process.env } = {}) => {
  const IntegrationAccount = getIntegrationModel(models);
  if (!IntegrationAccount) return [];
  const rows = await IntegrationAccount.find({
    removed: false,
    ...(tenantId ? { tenant: tenantId } : {}),
  })
    .sort({ provider: 1, created: -1 })
    .lean();
  return rows.map((row) => sanitizeIntegrationAccount(row, env));
};

const resolveProviderEnvForTenant = async ({ tenantId, models = {}, env = process.env } = {}) => {
  const IntegrationAccount = getIntegrationModel(models);
  if (!IntegrationAccount || !tenantId) return { ...env };

  const accounts = await IntegrationAccount.find({
    tenant: tenantId,
    removed: false,
    enabled: { $ne: false },
    status: { $in: ['active', 'error'] },
  }).lean();

  const overrides = {};
  for (const account of accounts) {
    const publicConfig = account.publicConfig || account.config || {};
    const secretConfig = decryptJson(account.secretConfigEncrypted, env);
    Object.assign(overrides, buildEnvOverrides(account.provider, { ...publicConfig, ...secretConfig }));
  }

  return { ...env, ...overrides };
};

const buildProviderCatalogForUi = async ({ tenantId, models = {}, env = process.env } = {}) => {
  const accounts = await loadIntegrationAccounts({ tenantId, models, env });
  const byProvider = new Map(accounts.map((account) => [account.provider, account]));

  return listProviderDefinitions().map((definition) => ({
    key: definition.key,
    label: definition.label,
    category: definition.category,
    supportsLiveTest: definition.supportsLiveTest,
    publicFields: definition.publicFields || [],
    secretFields: definition.secretFields || [],
    account: byProvider.get(definition.key) || null,
    envFallbackActive: hasEnvFallback(definition.key, env),
  }));
};

const prepareAccountPayload = ({ provider, body = {}, env = process.env }) => {
  const definition = getProviderDefinition(provider);
  if (!definition) {
    throw new Error(`Unsupported provider: ${provider}`);
  }

  const publicFieldKeys = (definition.publicFields || []).map((field) => field.key);
  const secretFieldKeys = (definition.secretFields || []).map((field) => field.key);
  const nextPublicConfig = pickFields(body.publicConfig || body.config || body, publicFieldKeys);
  const nextSecretConfig = pickFields(body.secretConfig || body, secretFieldKeys);

  return {
    name: body.name || definition.label,
    status: body.status || 'active',
    enabled: typeof body.enabled === 'boolean' ? body.enabled : true,
    publicConfig: nextPublicConfig,
    secretFields: Object.keys(nextSecretConfig),
    secretConfigEncrypted: Object.keys(nextSecretConfig).length
      ? encryptJson(nextSecretConfig, env)
      : null,
  };
};

module.exports = {
  buildEnvOverrides,
  buildProviderCatalogForUi,
  getIntegrationModel,
  hasEnvFallback,
  loadIntegrationAccounts,
  prepareAccountPayload,
  resolveProviderEnvForTenant,
  sanitizeIntegrationAccount,
};
