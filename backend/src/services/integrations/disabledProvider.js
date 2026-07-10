const disabledResult = (provider, action, payload = {}) => ({
  provider,
  action,
  disabled: true,
  payload,
  message: `${provider} is disabled because credentials are not configured.`,
});

module.exports = { disabledResult };
