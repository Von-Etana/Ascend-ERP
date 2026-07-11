const mongoose = require('mongoose');
const { buildTenantQuery } = require('@/services/platform/accessControl');
const { auditLog } = require('@/services/platform/auditLogger');
const {
  buildEnvOverrides,
  buildProviderCatalogForUi,
  getIntegrationModel,
  hasEnvFallback,
  prepareAccountPayload,
  sanitizeIntegrationAccount,
} = require('@/services/integrations/accountService');
const { getProviderDefinition } = require('@/services/integrations/providerCatalog');
const { createProviderRegistry } = require('@/services/integrations/providerRegistry');
const { decryptJson } = require('@/services/integrations/secretStore');

const scope = (req) => ({
  removed: false,
  ...buildTenantQuery(getIntegrationModel(), req),
});

const ok = (res, result, message) => res.status(200).json({ success: true, result, message });
const notFound = (res, message = 'Integration account not found') =>
  res.status(404).json({ success: false, result: null, message });

const liveTestProvider = async ({ provider, env }) => {
  const registry = createProviderRegistry(env);

  if (provider === 'resend') {
    if (!env.RESEND_API) return { ok: false, skipped: true, message: 'No Resend API key configured' };
    const response = await fetch('https://api.resend.com/domains', {
      headers: { Authorization: `Bearer ${env.RESEND_API}` },
    });
    if (!response.ok) throw new Error('Resend credentials rejected');
    return { ok: true, message: 'Resend credentials validated' };
  }

  if (provider === 'meta') {
    if (!env.META_ACCESS_TOKEN) return { ok: false, skipped: true, message: 'No Meta access token configured' };
    const baseUrl = env.META_API_URL || 'https://graph.facebook.com/v20.0';
    const response = await fetch(`${baseUrl}/me?fields=id`, {
      headers: { Authorization: `Bearer ${env.META_ACCESS_TOKEN}` },
    });
    if (!response.ok) throw new Error('Meta credentials rejected');
    return { ok: true, message: 'Meta credentials validated' };
  }

  if (provider === 'linkedin') {
    if (!env.LINKEDIN_ACCESS_TOKEN) return { ok: false, skipped: true, message: 'No LinkedIn access token configured' };
    const response = await fetch('https://api.linkedin.com/v2/userinfo', {
      headers: { Authorization: `Bearer ${env.LINKEDIN_ACCESS_TOKEN}` },
    });
    if (!response.ok) throw new Error('LinkedIn credentials rejected');
    return { ok: true, message: 'LinkedIn credentials validated' };
  }

  if (provider === 'x') {
    if (!env.X_ACCESS_TOKEN) return { ok: false, skipped: true, message: 'No X access token configured' };
    const response = await fetch('https://api.x.com/2/users/me', {
      headers: { Authorization: `Bearer ${env.X_ACCESS_TOKEN}` },
    });
    if (!response.ok) throw new Error('X credentials rejected');
    return { ok: true, message: 'X credentials validated' };
  }

  if (provider === 'openai' || provider === 'kimi') {
    const result = await registry[provider].request('connectionTest', {
      instruction: 'Reply with OK.',
      prompt: 'Connection test',
      temperature: 0,
    });
    if (result.disabled) {
      return { ok: false, skipped: true, message: `${provider} is disabled because credentials are missing` };
    }
    return { ok: true, message: `${getProviderDefinition(provider)?.label || provider} credentials validated` };
  }

  if (provider === 'hermes' || provider === 'fal') {
    return { ok: true, skipped: true, message: `${getProviderDefinition(provider)?.label || provider} credentials saved. Live ping is not available yet.` };
  }

  return { ok: true, skipped: true, message: 'Credentials saved' };
};

const list = async (req, res) => {
  const catalog = await buildProviderCatalogForUi({ tenantId: req.tenantId, env: process.env });
  return ok(res, catalog.map((item) => item.account).filter(Boolean), 'Integration accounts loaded');
};

const listAll = list;
const summary = list;
const filter = list;
const search = list;

const read = async (req, res) => {
  const IntegrationAccount = getIntegrationModel();
  const account = await IntegrationAccount.findOne({
    _id: req.params.id,
    ...scope(req),
  }).exec();
  if (!account) return notFound(res);
  return ok(res, sanitizeIntegrationAccount(account, process.env), 'Integration account loaded');
};

const upsert = async (req, res, current) => {
  const provider = String(req.body.provider || current?.provider || '').toLowerCase();
  const definition = getProviderDefinition(provider);
  if (!definition) {
    return res.status(400).json({
      success: false,
      result: null,
      message: `Unsupported provider: ${provider}`,
    });
  }

  const IntegrationAccount = getIntegrationModel();
  const prepared = prepareAccountPayload({ provider, body: req.body || {}, env: process.env });
  const now = new Date();
  const beforeSanitized = current ? sanitizeIntegrationAccount(current, process.env) : null;

  const payload = {
    tenant: req.tenantId,
    createdBy: current?.createdBy || req.admin?._id,
    assignedTo: current?.assignedTo || req.admin?._id,
    provider,
    name: prepared.name,
    enabled: prepared.enabled,
    status: prepared.status,
    publicConfig: prepared.publicConfig,
    config: prepared.publicConfig,
    secretFields:
      prepared.secretConfigEncrypted ? prepared.secretFields : current?.secretFields || [],
    lastError: '',
    lastTestStatus: current?.lastTestStatus || 'untested',
    lastTestMessage: current?.lastTestMessage || '',
    lastTestedAt: current?.lastTestedAt || null,
    updated: now,
  };

  if (prepared.secretConfigEncrypted) {
    payload.secretConfigEncrypted = prepared.secretConfigEncrypted;
  } else if (req.body.clearSecret) {
    payload.secretConfigEncrypted = null;
    payload.secretFields = [];
  }

  let account;
  if (current) {
    account = await IntegrationAccount.findOneAndUpdate(
      { _id: current._id, ...scope(req) },
      { $set: payload },
      { new: true, runValidators: true }
    ).exec();
  } else {
    account = await IntegrationAccount.create(payload);
  }

  const afterSanitized = sanitizeIntegrationAccount(account, process.env);
  await auditLog({
    req,
    action: current ? 'update' : 'create',
    entity: 'integrationaccount',
    entityId: account._id,
    before: beforeSanitized,
    after: afterSanitized,
  });

  return ok(
    res,
    afterSanitized,
    current ? 'Integration account updated' : 'Integration account created'
  );
};

const create = async (req, res) => upsert(req, res, null);

const update = async (req, res) => {
  const IntegrationAccount = getIntegrationModel();
  const current = await IntegrationAccount.findOne({
    _id: req.params.id,
    ...scope(req),
  }).exec();
  if (!current) return notFound(res);
  return upsert(req, res, current);
};

const remove = async (req, res) => {
  const IntegrationAccount = getIntegrationModel();
  const current = await IntegrationAccount.findOne({
    _id: req.params.id,
    ...scope(req),
  }).exec();
  if (!current) return notFound(res);
  const before = sanitizeIntegrationAccount(current, process.env);

  current.removed = true;
  current.status = 'disabled';
  current.secretConfigEncrypted = null;
  current.secretFields = [];
  current.publicConfig = {};
  current.config = {};
  await current.save();

  await auditLog({
    req,
    action: 'delete',
    entity: 'integrationaccount',
    entityId: current._id,
    before,
    after: { removed: true, provider: current.provider, name: current.name },
  });

  return ok(res, { _id: current._id, removed: true }, 'Integration account removed');
};

const providers = async (req, res) => {
  const result = await buildProviderCatalogForUi({ tenantId: req.tenantId, env: process.env });
  return ok(res, result, 'Integration provider catalog loaded');
};

const testConnection = async (req, res) => {
  const provider = String(req.params.provider || '').toLowerCase();
  const definition = getProviderDefinition(provider);
  if (!definition) return notFound(res, 'Integration provider not found');

  const IntegrationAccount = getIntegrationModel();
  const account = await IntegrationAccount.findOne({
    provider,
    ...scope(req),
  }).exec();

  const publicConfig = account?.publicConfig || account?.config || {};
  const env = {
    ...process.env,
    ...buildEnvOverrides(provider, publicConfig),
  };
  if (account?.secretConfigEncrypted) {
    const secretConfig = decryptJson(account.secretConfigEncrypted, process.env);
    Object.assign(
      env,
      buildEnvOverrides(provider, { ...publicConfig, ...secretConfig })
    );
  }

  let result;
  try {
    result = await liveTestProvider({ provider, env });
  } catch (error) {
    result = { ok: false, skipped: false, message: error.message };
  }

  if (account) {
    account.lastTestedAt = new Date();
    account.lastTestStatus = result.ok ? 'passed' : result.skipped ? 'skipped' : 'failed';
    account.lastTestMessage = result.message;
    account.lastError = result.ok ? '' : result.message;
    account.status = result.ok || result.skipped
      ? account.status === 'disabled'
        ? 'active'
        : account.status
      : 'error';
    await account.save();
    await auditLog({
      req,
      action: 'test',
      entity: 'integrationaccount',
      entityId: account._id,
      before: null,
      after: {
        provider,
        lastTestStatus: account.lastTestStatus,
        lastTestMessage: account.lastTestMessage,
      },
    });
  }

  return ok(
    res,
    {
      provider,
      account: account ? sanitizeIntegrationAccount(account, process.env) : null,
      envFallbackActive: hasEnvFallback(provider, process.env),
      ...result,
    },
    'Integration connection test completed'
  );
};

const clearSecret = async (req, res) => {
  const provider = String(req.params.provider || '').toLowerCase();
  const IntegrationAccount = getIntegrationModel();
  const account = await IntegrationAccount.findOne({
    provider,
    ...scope(req),
  }).exec();
  if (!account) return notFound(res);

  const before = sanitizeIntegrationAccount(account, process.env);
  account.secretConfigEncrypted = null;
  account.secretFields = [];
  account.status = 'disabled';
  account.lastTestStatus = 'untested';
  account.lastTestMessage = 'Secret removed from admin console';
  await account.save();

  await auditLog({
    req,
    action: 'update',
    entity: 'integrationaccount',
    entityId: account._id,
    before,
    after: sanitizeIntegrationAccount(account, process.env),
  });

  return ok(res, sanitizeIntegrationAccount(account, process.env), 'Integration secret removed');
};

module.exports = {
  create,
  read,
  update,
  delete: remove,
  list,
  listAll,
  summary,
  filter,
  search,
  providers,
  testConnection,
  clearSecret,
};
