const { interpolateTemplate } = require('./template');

const modelNameFor = (entity) =>
  String(entity || '')
    .split(/[-_\s]/)
    .filter(Boolean)
    .map((part) => part.charAt(0).toUpperCase() + part.slice(1))
    .join('');

const getModel = (models, entityOrModelName) => {
  const modelName = models[entityOrModelName] ? entityOrModelName : modelNameFor(entityOrModelName);
  const Model = models[modelName];
  if (!Model) throw new Error(`Model not available for automation action: ${entityOrModelName}`);
  return Model;
};

const buildContext = (event) => ({
  ...(event && typeof event.toObject === 'function' ? event.toObject() : event),
  id: event?._id,
});

const withOwnership = (event, payload) => ({
  tenant: event.tenant,
  createdBy: event.actor,
  assignedTo: event.actor,
  ...payload,
});

const executeAutomationAction = async ({ action, event, models, providers }) => {
  const config = interpolateTemplate(action.config || {}, buildContext(event));

  switch (action.type) {
    case 'create_record': {
      const Model = getModel(models, config.entity || config.model);
      const record = await Model.create(withOwnership(event, config.data || {}));
      return { type: action.type, entity: config.entity || config.model, record };
    }
    case 'update_record': {
      const entity = config.entity || event.entity;
      const Model = getModel(models, entity);
      const id = config.id || event.entityId;
      const record = await Model.findOneAndUpdate(
        { _id: id, ...(event.tenant ? { tenant: event.tenant } : {}) },
        config.data || {},
        { new: true, runValidators: true }
      ).exec();
      return { type: action.type, entity, record };
    }
    case 'send_email': {
      const result = await providers.resend.sendEmail(config);
      return { type: action.type, provider: 'resend', result };
    }
    case 'send_whatsapp': {
      const result = await providers.meta.request('sendWhatsApp', config);
      return { type: action.type, provider: 'meta', result };
    }
    case 'generate_content': {
      const providerName = config.provider || 'kimi';
      const result = await providers[providerName].request('generateContent', config);
      let asset = null;
      if (models.ContentAsset) {
        asset = await models.ContentAsset.create(
          withOwnership(event, {
            title: config.title || 'Automation content',
            type: config.contentType || 'email',
            prompt: config.prompt,
            content: result.content || result.message,
            provider: providerName,
            brandContext: config.brandContext,
          })
        );
      }
      return { type: action.type, provider: providerName, result, asset };
    }
    case 'create_task': {
      const record = await getModel(models, 'Task').create(
        withOwnership(event, {
          relatedTo: { entityType: event.entity, entityId: event.entityId },
          ...config,
        })
      );
      return { type: action.type, record };
    }
    case 'schedule_social_post': {
      const record = await getModel(models, 'SocialPost').create(withOwnership(event, config));
      return { type: action.type, record };
    }
    default:
      throw new Error(`Unsupported automation action: ${action.type}`);
  }
};

module.exports = {
  executeAutomationAction,
  modelNameFor,
};
