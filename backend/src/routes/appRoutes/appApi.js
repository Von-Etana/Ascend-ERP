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
