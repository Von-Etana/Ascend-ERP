const mongoose = require('mongoose');
const { DEFAULT_CURRENCY_CODE } = require('../../config/platformDefaults');
const { publishAutomationEvent } = require('@/services/automation/eventBus');

const nextNumber = async (Model, tenant) => {
  const count = await Model.countDocuments(tenant ? { tenant } : {});
  return count + 1;
};

const dealWon = async (req, res) => {
  const {
    dealId,
    procurementNeeded = false,
    thankYouCampaignName = 'Thank-you campaign',
  } = req.body;

  const Deal = mongoose.model('Deal');
  const SalesOrder = mongoose.model('SalesOrder');
  const Invoice = mongoose.model('Invoice');
  const Client = mongoose.model('Client');
  const Campaign = mongoose.model('Campaign');
  const Activity = mongoose.model('Activity');
  const PurchaseOrder = mongoose.models.PurchaseOrder;

  const deal = await Deal.findOne({
    _id: dealId,
    removed: false,
    ...(req.tenantId ? { tenant: req.tenantId } : {}),
  })
    .populate('contact')
    .populate('company')
    .exec();

  if (!deal) {
    return res.status(404).json({ success: false, result: null, message: 'Deal not found' });
  }

  deal.status = 'won';
  await deal.save();

  let client = await Client.findOne({
    tenant: req.tenantId,
    email: deal.contact?.email,
    removed: false,
  }).exec();

  if (!client) {
    client = await Client.create({
      tenant: req.tenantId,
      name: deal.company?.name || deal.contact?.name || deal.name,
      email: deal.contact?.email,
      phone: deal.contact?.phone,
      createdBy: req.admin?._id,
      assignedTo: req.admin?._id,
    });
  }

  const salesOrder = await SalesOrder.create({
    tenant: req.tenantId,
    createdBy: req.admin?._id,
    assignedTo: req.admin?._id,
    number: await nextNumber(SalesOrder, req.tenantId),
    deal: deal._id,
    contact: deal.contact?._id,
    company: deal.company?._id,
    status: 'approved',
    procurementNeeded,
    value: deal.value,
  });

  const total = deal.value?.amount || 0;
  const invoice = await Invoice.create({
    tenant: req.tenantId,
    createdBy: req.admin?._id,
    assignedTo: req.admin?._id,
    number: await nextNumber(Invoice, req.tenantId),
    year: new Date().getFullYear(),
    date: new Date(),
    expiredDate: new Date(Date.now() + 14 * 24 * 60 * 60 * 1000),
    client: client._id,
    contact: deal.contact?._id,
    deal: deal._id,
    salesOrder: salesOrder._id,
    items: [
      {
        itemName: deal.name,
        quantity: 1,
        price: total,
        total,
      },
    ],
    subTotal: total,
    total,
    currency: deal.value?.currency || DEFAULT_CURRENCY_CODE,
    status: 'pending',
  });

  let purchaseOrder = null;
  if (procurementNeeded && PurchaseOrder) {
    purchaseOrder = await PurchaseOrder.create({
      tenant: req.tenantId,
      createdBy: req.admin?._id,
      assignedTo: req.admin?._id,
      number: await nextNumber(PurchaseOrder, req.tenantId),
      salesOrder: salesOrder._id,
      status: 'pending_approval',
      total: deal.value,
    });
  }

  const campaign = await Campaign.create({
    tenant: req.tenantId,
    createdBy: req.admin?._id,
    assignedTo: req.admin?._id,
    name: thankYouCampaignName,
    channel: 'email',
    status: 'scheduled',
    scheduledAt: new Date(),
  });

  await Activity.create({
    tenant: req.tenantId,
    createdBy: req.admin?._id,
    assignedTo: req.admin?._id,
    type: 'note',
    subject: 'Deal won workflow completed',
    body: 'Created sales order, invoice, campaign, and procurement request when needed.',
    contact: deal.contact?._id,
    company: deal.company?._id,
    relatedTo: { entityType: 'Deal', entityId: deal._id },
  });

  deal.salesOrder = salesOrder._id;
  deal.invoice = invoice._id;
  deal.campaign = campaign._id;
  await deal.save();

  await publishAutomationEvent({
    tenant: req.tenantId,
    type: 'crm.deal.won',
    sourceModule: 'crm',
    entity: 'deal',
    entityId: deal._id,
    actor: req.admin?._id,
    payload: {
      deal,
      salesOrder,
      invoice,
      purchaseOrder,
      campaign,
    },
  });

  return res.status(200).json({
    success: true,
    result: { deal, salesOrder, invoice, purchaseOrder, campaign },
    message: 'Deal won workflow completed',
  });
};

module.exports = { dealWon };
