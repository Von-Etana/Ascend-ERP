const { createProviderRegistry } = require('@/services/integrations/providerRegistry');
const { publishAutomationEvent } = require('@/services/automation/eventBus');
const {
  getPublishedPublicForm,
  submitPublicForm,
} = require('@/services/platform/enterpriseWorkflows');

const getForm = async (req, res) => {
  try {
    const result = await getPublishedPublicForm({ slug: req.params.slug });
    return res.status(200).json({
      success: true,
      result,
      message: 'Public form loaded',
    });
  } catch (error) {
    return res.status(error.status || 500).json({
      success: false,
      result: null,
      message: error.message || 'Unable to load public form',
    });
  }
};

const submit = async (req, res) => {
  try {
    const result = await submitPublicForm({
      slug: req.params.slug,
      payload: req.body,
      providers: createProviderRegistry(process.env),
      publishEvent: publishAutomationEvent,
    });

    return res.status(200).json({
      success: true,
      result,
      message: 'Public form submitted successfully',
    });
  } catch (error) {
    return res.status(error.status || 500).json({
      success: false,
      result: null,
      message: error.message || 'Unable to submit public form',
    });
  }
};

module.exports = {
  getForm,
  submit,
};
