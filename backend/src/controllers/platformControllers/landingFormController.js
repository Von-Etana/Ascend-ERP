const mongoose = require('mongoose');
const { createProviderRegistry } = require('@/services/integrations/providerRegistry');
const { publishAutomationEvent } = require('@/services/automation/eventBus');
const { submitPublicForm } = require('@/services/platform/enterpriseWorkflows');

const capture = async (req, res) => {
  const Contact = mongoose.model('Contact');
  const Lead = mongoose.model('Lead');
  const { firstName, lastName, name, email, phone, source = 'landing_form', campaign } = req.body;

  const contact = await Contact.create({
    tenant: req.tenantId,
    firstName: firstName || name || email || 'New',
    lastName,
    name,
    email,
    phone,
    lifecycleStage: 'lead',
    createdBy: req.admin?._id,
  });

  const lead = await Lead.create({
    tenant: req.tenantId,
    title: `Lead from ${source}`,
    contact: contact._id,
    source,
    status: 'new',
    createdBy: req.admin?._id,
  });

  return res.status(200).json({
    success: true,
    result: { contact, lead, campaign },
    message: 'Landing form captured and lead created',
  });
};

const submit = async (req, res) => {
  try {
    const result = await submitPublicForm({
      slug: req.params.slug,
      payload: req.body,
      tenantId: req.tenantId,
      actorId: req.admin?._id,
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

module.exports = { capture, submit };
