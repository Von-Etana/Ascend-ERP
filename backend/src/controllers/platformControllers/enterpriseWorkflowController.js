const { createProviderRegistryForTenant } = require('@/services/integrations/providerRegistry');
const { publishAutomationEvent } = require('@/services/automation/eventBus');
const {
  convertOfferToInvoice,
  transitionEntityStatus,
  submitPublicForm,
  recordInvoicePayment,
  reconcilePaymentRecord,
} = require('@/services/platform/enterpriseWorkflows');

const handleError = (res, error, fallbackMessage) =>
  res.status(error.status || 500).json({
    success: false,
    result: null,
    message: error.message || fallbackMessage,
  });

const convertOffer = async (req, res) => {
  try {
    const result = await convertOfferToInvoice({
      offerId: req.params.id,
      tenantId: req.tenantId,
      actorId: req.admin?._id,
      publishEvent: publishAutomationEvent,
    });

    return res.status(200).json({
      success: true,
      result,
      message: 'Offer converted to invoice',
    });
  } catch (error) {
    return handleError(res, error, 'Unable to convert offer');
  }
};

const updateOrderStatus = async (req, res) => {
  try {
    const result = await transitionEntityStatus({
      entityName: 'order',
      entityId: req.params.id,
      status: req.body.status,
      tenantId: req.tenantId,
      actorId: req.admin?._id,
      publishEvent: publishAutomationEvent,
    });

    return res.status(200).json({
      success: true,
      result,
      message: 'Order status updated',
    });
  } catch (error) {
    return handleError(res, error, 'Unable to update order status');
  }
};

const updatePurchaseOrderStatus = async (req, res) => {
  try {
    const result = await transitionEntityStatus({
      entityName: 'purchaseorder',
      entityId: req.params.id,
      status: req.body.status,
      tenantId: req.tenantId,
      actorId: req.admin?._id,
      publishEvent: publishAutomationEvent,
    });

    return res.status(200).json({
      success: true,
      result,
      message: 'Purchase order status updated',
    });
  } catch (error) {
    return handleError(res, error, 'Unable to update purchase order status');
  }
};

const submitForm = async (req, res) => {
  try {
    const result = await submitPublicForm({
      slug: req.params.slug,
      payload: req.body,
      tenantId: req.tenantId,
      actorId: req.admin?._id,
      providers: await createProviderRegistryForTenant({ tenantId: req.tenantId }),
      publishEvent: publishAutomationEvent,
    });

    return res.status(200).json({
      success: true,
      result,
      message: 'Public form submitted successfully',
    });
  } catch (error) {
    return handleError(res, error, 'Unable to submit public form');
  }
};

const recordPayment = async (req, res) => {
  try {
    const result = await recordInvoicePayment({
      invoiceId: req.params.id,
      paymentInput: req.body,
      tenantId: req.tenantId,
      actorId: req.admin?._id,
      publishEvent: publishAutomationEvent,
    });

    return res.status(200).json({
      success: true,
      result,
      message: 'Invoice payment recorded',
    });
  } catch (error) {
    return handleError(res, error, 'Unable to record invoice payment');
  }
};

const reconcilePayment = async (req, res) => {
  try {
    const result = await reconcilePaymentRecord({
      paymentId: req.params.id,
      tenantId: req.tenantId,
      actorId: req.admin?._id,
      publishEvent: publishAutomationEvent,
    });

    return res.status(200).json({
      success: true,
      result,
      message: 'Payment reconciled',
    });
  } catch (error) {
    return handleError(res, error, 'Unable to reconcile payment');
  }
};

module.exports = {
  convertOffer,
  updateOrderStatus,
  updatePurchaseOrderStatus,
  submitForm,
  recordPayment,
  reconcilePayment,
};
