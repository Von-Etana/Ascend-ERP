const express = require('express');
const { catchErrors } = require('@/handlers/errorHandlers');
const router = express.Router();

const appControllers = require('@/controllers/appControllers');
const { routesList } = require('@/models/utils');
const workflowController = require('@/controllers/platformControllers/workflowController');
const dealController = require('@/controllers/platformControllers/dealController');
const aiController = require('@/controllers/platformControllers/aiController');
const landingFormController = require('@/controllers/platformControllers/landingFormController');
const socialPostController = require('@/controllers/platformControllers/socialPostController');
const automationController = require('@/controllers/platformControllers/automationController');
const accessControlController = require('@/controllers/platformControllers/accessControlController');
const enterpriseWorkflowController = require('@/controllers/platformControllers/enterpriseWorkflowController');
const reportController = require('@/controllers/platformControllers/reportController');
const growthWorkspaceController = require('@/controllers/platformControllers/growthWorkspaceController');
const agentController = require('@/controllers/platformControllers/agentController');
const knowledgeController = require('@/controllers/platformControllers/knowledgeController');
const socialWorkspaceController = require('@/controllers/platformControllers/socialWorkspaceController');

const routerApp = (entity, controller) => {
  router.route(`/${entity}/create`).post(catchErrors(controller['create']));
  router.route(`/${entity}/read/:id`).get(catchErrors(controller['read']));
  router.route(`/${entity}/update/:id`).patch(catchErrors(controller['update']));
  router.route(`/${entity}/delete/:id`).delete(catchErrors(controller['delete']));
  router.route(`/${entity}/search`).get(catchErrors(controller['search']));
  router.route(`/${entity}/list`).get(catchErrors(controller['list']));
  router.route(`/${entity}/listAll`).get(catchErrors(controller['listAll']));
  router.route(`/${entity}/filter`).get(catchErrors(controller['filter']));
  router.route(`/${entity}/summary`).get(catchErrors(controller['summary']));

  if (entity === 'invoice' || entity === 'quote' || entity === 'payment') {
    router.route(`/${entity}/mail`).post(catchErrors(controller['mail']));
  }

  if (entity === 'quote') {
    router.route(`/${entity}/convert/:id`).get(catchErrors(controller['convert']));
  }
};

routesList.forEach(({ entity, controllerName }) => {
  const controller = appControllers[controllerName];
  routerApp(entity, controller);
});

router.route('/deal/:id/stage').patch(catchErrors(dealController.updateStage));
router.route('/workflows/deal-won').post(catchErrors(workflowController.dealWon));
router.route('/offer/convert/:id').post(catchErrors(enterpriseWorkflowController.convertOffer));
router.route('/invoice/:id/record-payment').post(catchErrors(enterpriseWorkflowController.recordPayment));
router.route('/payment/:id/reconcile').patch(catchErrors(enterpriseWorkflowController.reconcilePayment));
router.route('/order/:id/status').patch(catchErrors(enterpriseWorkflowController.updateOrderStatus));
router.route('/purchaseorder/:id/status').patch(catchErrors(enterpriseWorkflowController.updatePurchaseOrderStatus));
router.route('/landingform/capture').post(catchErrors(landingFormController.capture));
router.route('/publicform/submit/:slug').post(catchErrors(landingFormController.submit));
router.route('/socialpost/schedule').post(catchErrors(socialPostController.schedule));
router.route('/ai/content/generate').post(catchErrors(aiController.generateContent));
router.route('/ai/brand-asset/generate').post(catchErrors(aiController.generateBrandAsset));
router.route('/ai/campaign/draft').post(catchErrors(aiController.draftCampaign));
router.route('/ai/workspace/assets').get(catchErrors(growthWorkspaceController.aiAssets));
router.route('/agent/templates').get(catchErrors(agentController.templates));
router.route('/agent/tools').get(catchErrors(agentController.tools));
router.route('/agent/from-template').post(catchErrors(agentController.createFromTemplate));
router.route('/agent/:id/version').get(catchErrors(agentController.version));
router.route('/agent/:id/publish').post(catchErrors(agentController.publish));
router.route('/agent/:id/test').post(catchErrors(agentController.test));
router.route('/agent/:id/run').post(catchErrors(agentController.run));
router.route('/agent/run/history').get(catchErrors(agentController.runs));
router.route('/agent/run/:id').get(catchErrors(agentController.runDetail));
router.route('/agent/run/:id/cancel').post(catchErrors(agentController.cancel));
router.route('/agent/approval').get(catchErrors(agentController.approvals));
router.route('/agent/approval/:id/decide').post(catchErrors(agentController.decideApproval));
router.route('/agent/assistant/chat').post(catchErrors(agentController.assistant));
router.route('/agent/run-due').post(catchErrors(agentController.runDue));
router.route('/integrationaccount/providers').get(catchErrors(appControllers.integrationaccountController.providers));
router.route('/integrationaccount/test/:provider').post(catchErrors(appControllers.integrationaccountController.testConnection));
router.route('/integrationaccount/secret/:provider').delete(catchErrors(appControllers.integrationaccountController.clearSecret)).post(catchErrors(appControllers.integrationaccountController.clearSecret));
router.route('/knowledge/source/:id/ingest').post(catchErrors(knowledgeController.ingest));
router.route('/knowledge/search').get(catchErrors(knowledgeController.search));
router.route('/social/calendar').get(catchErrors(socialWorkspaceController.calendar));
router.route('/social/post/preview').post(catchErrors(socialWorkspaceController.preview));
router.route('/social/post/schedule').post(catchErrors(socialWorkspaceController.schedule));
router.route('/social/post/:id/publish').post(catchErrors(socialWorkspaceController.publish));
router.route('/social/metrics').get(catchErrors(socialWorkspaceController.metrics));
router.route('/marketing/workspace/summary').get(catchErrors(growthWorkspaceController.marketingSummary));
router.route('/campaign/launch').post(catchErrors(growthWorkspaceController.launchCampaign));
router.route('/automation/run-due').post(catchErrors(automationController.runDue));
router.route('/automation/run-history').get(catchErrors(automationController.runHistory));
router.route('/automation/preview').post(catchErrors(growthWorkspaceController.automationPreview));
router.route('/automation/workspace/summary').get(catchErrors(growthWorkspaceController.automationSummary));
router.route('/reports/overview').get(catchErrors(reportController.overview));
router.route('/permission/catalog').get(catchErrors(accessControlController.listPermissionCatalog));
router.route('/role/bootstrap-defaults').post(catchErrors(accessControlController.bootstrapDefaults));
router.route('/role/admin-access').get(catchErrors(accessControlController.listAdminAccess));
router.route('/role/admin-access/:id').patch(catchErrors(accessControlController.updateAdminAccess));

module.exports = router;
