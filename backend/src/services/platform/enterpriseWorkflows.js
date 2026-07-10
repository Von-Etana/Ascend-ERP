const mongoose = require('mongoose');
const { DEFAULT_CURRENCY_CODE } = require('../../config/platformDefaults');

const getModel = (name, models = {}) => models[name] || mongoose.model(name);

const nextNumber = async (Model, tenantId) => {
  const count = await Model.countDocuments(tenantId ? { tenant: tenantId } : {});
  return count + 1;
};

const loadOfferQuery = (Offer, offerId, tenantId) =>
  Offer.findOne({
    _id: offerId,
    removed: false,
    ...(tenantId ? { tenant: tenantId } : {}),
  })
    .populate('contact')
    .populate('lead')
    .populate('deal');

const getPublishedPublicForm = async ({ slug, models = {} }) => {
  const PublicForm = getModel('PublicForm', models);
  const form = await PublicForm.findOne({
    slug,
    status: 'published',
    removed: false,
  }).exec();

  if (!form) {
    const error = new Error('Public form not found');
    error.status = 404;
    throw error;
  }

  return {
    _id: form._id,
    tenant: form.tenant,
    name: form.name,
    slug: form.slug,
    description: form.description,
    targetEntity: form.targetEntity,
    fields: form.fields || [],
    successMessage: form.successMessage,
    status: form.status,
  };
};

const computeInvoicePaymentStatus = ({ total = 0, discount = 0, credit = 0 }) => {
  const outstanding = Number(total || 0) - Number(discount || 0);
  if (outstanding <= 0 || Number(credit || 0) >= outstanding) return 'paid';
  if (Number(credit || 0) > 0) return 'partially';
  return 'unpaid';
};

const convertOfferToInvoice = async ({
  offerId,
  tenantId,
  actorId,
  models = {},
  publishEvent = async () => null,
}) => {
  const Offer = getModel('Offer', models);
  const Client = getModel('Client', models);
  const Invoice = getModel('Invoice', models);

  const offer = await loadOfferQuery(Offer, offerId, tenantId).exec();
  if (!offer) {
    const error = new Error('Offer not found');
    error.status = 404;
    throw error;
  }

  let client = null;
  const contact = offer.contact || offer.lead?.contact || null;
  if (contact?.email) {
    client = await Client.findOne({
      tenant: tenantId,
      email: contact.email,
      removed: false,
    }).exec();
  }

  if (!client) {
    client = await Client.create({
      tenant: tenantId,
      name: contact?.name || offer.title,
      email: contact?.email,
      phone: contact?.phone,
      lifecycleStage: 'customer',
      createdBy: actorId,
      assignedTo: actorId,
    });
  }

  const total = offer.value?.amount || offer.total || 0;
  const invoice = await Invoice.create({
    tenant: tenantId,
    createdBy: actorId,
    assignedTo: actorId,
    number: await nextNumber(Invoice, tenantId),
    year: new Date().getFullYear(),
    date: new Date(),
    expiredDate: new Date(Date.now() + 14 * 24 * 60 * 60 * 1000),
    client: client._id,
    contact: contact?._id,
    deal: offer.deal?._id,
    converted: {
      from: 'offer',
      offer: offer._id,
      quote: undefined,
    },
    items:
      offer.items?.length > 0
        ? offer.items
        : [
            {
              itemName: offer.title,
              quantity: 1,
              price: total,
              total,
            },
          ],
    subTotal: total,
    total,
    currency: offer.value?.currency || DEFAULT_CURRENCY_CODE,
    notes: offer.notes,
    status: 'draft',
    paymentStatus: 'unpaid',
  });

  offer.status = 'converted';
  if (typeof offer.save === 'function') {
    await offer.save();
  }

  await publishEvent({
    tenant: tenantId,
    type: 'sales.offer.converted',
    sourceModule: 'sales',
    entity: 'offer',
    entityId: offer._id,
    actor: actorId,
    payload: { offer, invoice, client },
  });

  return { offer, invoice, client };
};

const STATUS_RULES = {
  order: {
    modelName: 'Order',
    eventType: 'inventory.order.status_changed',
    allowed: ['draft', 'confirmed', 'processing', 'delivered', 'returned', 'refunded', 'cancelled'],
  },
  purchaseorder: {
    modelName: 'PurchaseOrder',
    eventType: 'vendors.purchaseorder.status_changed',
    allowed: ['draft', 'pending_approval', 'approved', 'processing', 'received', 'returned', 'refunded', 'cancelled'],
  },
};

const transitionEntityStatus = async ({
  entityName,
  entityId,
  status,
  tenantId,
  actorId,
  models = {},
  publishEvent = async () => null,
}) => {
  const rule = STATUS_RULES[String(entityName || '').toLowerCase()];
  if (!rule) {
    const error = new Error('Unsupported workflow status entity');
    error.status = 400;
    throw error;
  }
  if (!rule.allowed.includes(status)) {
    const error = new Error('Unsupported status transition');
    error.status = 400;
    throw error;
  }

  const Model = getModel(rule.modelName, models);
  const record = await Model.findOne({
    _id: entityId,
    removed: false,
    ...(tenantId ? { tenant: tenantId } : {}),
  }).exec();

  if (!record) {
    const error = new Error('Record not found');
    error.status = 404;
    throw error;
  }

  record.status = status;
  if (actorId && record.assignedTo == null) {
    record.assignedTo = actorId;
  }
  await record.save();

  await publishEvent({
    tenant: tenantId,
    type: rule.eventType,
    sourceModule: rule.modelName === 'Order' ? 'inventory' : 'vendors',
    entity: entityName,
    entityId: record._id,
    actor: actorId,
    payload: { status, record },
  });

  return { record };
};

const submitPublicForm = async ({
  slug,
  payload = {},
  tenantId,
  actorId,
  models = {},
  providers = {},
  publishEvent = async () => null,
}) => {
  const PublicForm = getModel('PublicForm', models);
  const Contact = getModel('Contact', models);
  const Lead = getModel('Lead', models);
  const Client = getModel('Client', models);

  const form = await PublicForm.findOne({
    slug,
    removed: false,
    ...(tenantId ? { tenant: tenantId } : {}),
  }).exec();

  if (!form) {
    const error = new Error('Public form not found');
    error.status = 404;
    throw error;
  }

  const resolvedTenantId = tenantId || form.tenant;

  const contact = await Contact.create({
    tenant: resolvedTenantId,
    firstName: payload.firstName || payload.name || payload.email || 'New',
    lastName: payload.lastName,
    name: payload.name,
    email: payload.email,
    phone: payload.phone,
    whatsapp: payload.whatsapp,
    lifecycleStage: form.targetEntity === 'client' ? 'customer' : 'lead',
    createdBy: actorId,
    assignedTo: actorId,
  });

  let record;
  if (form.targetEntity === 'client') {
    record = await Client.create({
      tenant: resolvedTenantId,
      name: contact.name || [contact.firstName, contact.lastName].filter(Boolean).join(' '),
      email: contact.email,
      phone: contact.phone,
      lifecycleStage: 'customer',
      createdBy: actorId,
      assignedTo: actorId,
    });
  } else {
    record = await Lead.create({
      tenant: resolvedTenantId,
      title: payload.title || `Lead from ${form.name}`,
      contact: contact._id,
      source: 'public_form',
      status: 'new',
      createdBy: actorId,
      assignedTo: actorId,
    });
  }

  let autoReply = null;
  if (form.autoReplyEnabled && contact.email && providers.resend?.sendEmail) {
    autoReply = await providers.resend.sendEmail({
      to: contact.email,
      subject: form.autoReplySubject || `Thanks for contacting ${form.name}`,
      html: `<p>${form.autoReplyBody || 'Thanks for your submission.'}</p>`,
    });
  }

  await publishEvent({
    tenant: resolvedTenantId,
    type: 'platform.publicform.submitted',
    sourceModule: 'platform',
    entity: 'publicform',
    entityId: form._id,
    actor: actorId,
    payload: { form, contact, record },
  });

  return { form, contact, record, autoReply };
};

const recordInvoicePayment = async ({
  invoiceId,
  paymentInput = {},
  tenantId,
  actorId,
  models = {},
  publishEvent = async () => null,
}) => {
  const Invoice = getModel('Invoice', models);
  const Payment = getModel('Payment', models);

  const invoice = await Invoice.findOne({
    _id: invoiceId,
    removed: false,
    ...(tenantId ? { tenant: tenantId } : {}),
  }).exec();

  if (!invoice) {
    const error = new Error('Invoice not found');
    error.status = 404;
    throw error;
  }

  const amount = Number(paymentInput.amount || 0);
  if (amount <= 0) {
    const error = new Error("The Minimum Amount couldn't be 0");
    error.status = 400;
    throw error;
  }

  const outstanding = Number(invoice.total || 0) - Number(invoice.discount || 0) - Number(invoice.credit || 0);
  if (amount > outstanding) {
    const error = new Error(`The Max Amount you can add is ${outstanding}`);
    error.status = 400;
    throw error;
  }

  const payment = await Payment.create({
    tenant: tenantId || invoice.tenant,
    createdBy: actorId,
    assignedTo: actorId,
    number: await nextNumber(Payment, tenantId || invoice.tenant),
    client: invoice.client?._id || invoice.client,
    invoice: invoice._id,
    paymentMode: paymentInput.paymentMode,
    amount,
    currency: paymentInput.currency || invoice.currency || DEFAULT_CURRENCY_CODE,
    ref: paymentInput.ref,
    description: paymentInput.description,
    date: paymentInput.date ? new Date(paymentInput.date) : new Date(),
    reconciled: Boolean(paymentInput.reconciled),
    reconciledAt: paymentInput.reconciled ? new Date() : undefined,
  });

  invoice.credit = Number(invoice.credit || 0) + amount;
  invoice.paymentStatus = computeInvoicePaymentStatus(invoice);
  invoice.payment = [...(invoice.payment || []), payment._id];
  if (typeof invoice.save === 'function') {
    await invoice.save();
  }

  await publishEvent({
    tenant: tenantId || invoice.tenant,
    type: 'finance.invoice.payment_recorded',
    sourceModule: 'finance',
    entity: 'invoice',
    entityId: invoice._id,
    actor: actorId,
    payload: { invoice, payment },
  });

  return { invoice, payment };
};

const reconcilePaymentRecord = async ({
  paymentId,
  tenantId,
  actorId,
  models = {},
  publishEvent = async () => null,
}) => {
  const Payment = getModel('Payment', models);
  const payment = await Payment.findOne({
    _id: paymentId,
    removed: false,
    ...(tenantId ? { tenant: tenantId } : {}),
  }).exec();

  if (!payment) {
    const error = new Error('Payment not found');
    error.status = 404;
    throw error;
  }

  payment.reconciled = true;
  payment.reconciledAt = new Date();
  if (typeof payment.save === 'function') {
    await payment.save();
  }

  await publishEvent({
    tenant: tenantId || payment.tenant,
    type: 'finance.payment.reconciled',
    sourceModule: 'finance',
    entity: 'payment',
    entityId: payment._id,
    actor: actorId,
    payload: { payment },
  });

  return { payment };
};

const countByStatus = async (Model, tenantId) => {
  const result = await Model.aggregate([
    {
      $match: {
        removed: false,
        ...(tenantId ? { tenant: tenantId } : {}),
      },
    },
    {
      $group: {
        _id: '$status',
        count: { $sum: 1 },
      },
    },
    {
      $sort: { _id: 1 },
    },
  ]);

  return result.map((item) => ({ status: item._id, count: item.count }));
};

const buildEnterpriseOverviewReport = async ({ tenantId, models = {} }) => {
  const Invoice = getModel('Invoice', models);
  const Payment = getModel('Payment', models);
  const Order = getModel('Order', models);
  const PurchaseOrder = getModel('PurchaseOrder', models);
  const AutomationEvent = getModel('AutomationEvent', models);
  const PublicForm = getModel('PublicForm', models);

  const [invoiceRollup = {}, paymentRollup = {}, orderStatuses, purchaseStatuses, publicFormSubmissions, publishedForms, convertedOffers, paymentsRecorded, reconciledPayments] =
    await Promise.all([
      Invoice.aggregate([
        {
          $match: {
            removed: false,
            ...(tenantId ? { tenant: tenantId } : {}),
          },
        },
        {
          $group: {
            _id: null,
            count: { $sum: 1 },
            total: { $sum: '$total' },
            outstanding: { $sum: { $subtract: ['$total', '$credit'] } },
            paidCount: {
              $sum: { $cond: [{ $eq: ['$paymentStatus', 'paid'] }, 1, 0] },
            },
            partialCount: {
              $sum: { $cond: [{ $eq: ['$paymentStatus', 'partially'] }, 1, 0] },
            },
            unpaidCount: {
              $sum: { $cond: [{ $eq: ['$paymentStatus', 'unpaid'] }, 1, 0] },
            },
          },
        },
        {
          $project: {
            _id: 0,
            count: 1,
            total: 1,
            outstanding: 1,
            paidCount: 1,
            partialCount: 1,
            unpaidCount: 1,
          },
        },
      ]).then((rows) => rows[0] || {}),
      Payment.aggregate([
        {
          $match: {
            removed: false,
            ...(tenantId ? { tenant: tenantId } : {}),
          },
        },
        {
          $group: {
            _id: null,
            count: { $sum: 1 },
            total: { $sum: '$amount' },
            reconciledCount: {
              $sum: { $cond: ['$reconciled', 1, 0] },
            },
            unreconciledCount: {
              $sum: { $cond: ['$reconciled', 0, 1] },
            },
          },
        },
        {
          $project: {
            _id: 0,
            count: 1,
            total: 1,
            reconciledCount: 1,
            unreconciledCount: 1,
          },
        },
      ]).then((rows) => rows[0] || {}),
      countByStatus(Order, tenantId),
      countByStatus(PurchaseOrder, tenantId),
      AutomationEvent.countDocuments({
        removed: false,
        ...(tenantId ? { tenant: tenantId } : {}),
        type: 'platform.publicform.submitted',
      }),
      PublicForm.countDocuments({
        removed: false,
        ...(tenantId ? { tenant: tenantId } : {}),
        status: 'published',
      }),
      AutomationEvent.countDocuments({
        removed: false,
        ...(tenantId ? { tenant: tenantId } : {}),
        type: 'sales.offer.converted',
      }),
      AutomationEvent.countDocuments({
        removed: false,
        ...(tenantId ? { tenant: tenantId } : {}),
        type: 'finance.invoice.payment_recorded',
      }),
      AutomationEvent.countDocuments({
        removed: false,
        ...(tenantId ? { tenant: tenantId } : {}),
        type: 'finance.payment.reconciled',
      }),
    ]);

  return {
    finance: {
      invoices: {
        count: invoiceRollup.count || 0,
        total: invoiceRollup.total || 0,
        outstanding: invoiceRollup.outstanding || 0,
        paidCount: invoiceRollup.paidCount || 0,
        partialCount: invoiceRollup.partialCount || 0,
        unpaidCount: invoiceRollup.unpaidCount || 0,
      },
      payments: {
        count: paymentRollup.count || 0,
        total: paymentRollup.total || 0,
        reconciledCount: paymentRollup.reconciledCount || 0,
        unreconciledCount: paymentRollup.unreconciledCount || 0,
      },
    },
    operations: {
      orders: {
        total: orderStatuses.reduce((sum, item) => sum + item.count, 0),
        statusCounts: orderStatuses,
      },
      purchases: {
        total: purchaseStatuses.reduce((sum, item) => sum + item.count, 0),
        statusCounts: purchaseStatuses,
      },
    },
    conversions: {
      publicFormSubmissions,
      publishedForms,
      convertedOffers,
      paymentsRecorded,
      reconciledPayments,
    },
  };
};

module.exports = {
  convertOfferToInvoice,
  transitionEntityStatus,
  submitPublicForm,
  getPublishedPublicForm,
  recordInvoicePayment,
  reconcilePaymentRecord,
  buildEnterpriseOverviewReport,
};
