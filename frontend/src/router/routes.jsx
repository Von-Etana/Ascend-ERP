import { lazy } from 'react';

import { Navigate } from 'react-router-dom';

const Logout = lazy(() => import('@/pages/Logout.jsx'));
const NotFound = lazy(() => import('@/pages/NotFound.jsx'));
const CustomerWorkbench = lazy(() => import('@/pages/Workflows/CustomerWorkbench'));
const PeopleWorkbench = lazy(() => import('@/pages/Workflows/PeopleWorkbench'));
const CompanyWorkbench = lazy(() => import('@/pages/Workflows/CompanyWorkbench'));
const LeadWorkbench = lazy(() => import('@/pages/Workflows/LeadWorkbench'));
const SupplierWorkbench = lazy(() => import('@/pages/Workflows/SupplierWorkbench'));
const BranchWorkbench = lazy(() => import('@/pages/Workflows/BranchWorkbench'));
const CompanyWorkspaceWorkbench = lazy(() => import('@/pages/Workflows/CompanyWorkspaceWorkbench'));
const CurrencyWorkbench = lazy(() => import('@/pages/Workflows/CurrencyWorkbench'));
const PaymentModeWorkbench = lazy(() => import('@/pages/Workflows/PaymentModeWorkbench'));
const TaxWorkbench = lazy(() => import('@/pages/Workflows/TaxWorkbench'));
const PdfSettingsWorkbench = lazy(() => import('@/pages/Workflows/PdfSettingsWorkbench'));
const DocumentsWorkbench = lazy(() => import('@/pages/Workflows/DocumentsWorkbench'));
const ContractsWorkbench = lazy(() => import('@/pages/Workflows/ContractsWorkbench'));
const BookingWorkbench = lazy(() => import('@/pages/Workflows/BookingWorkbench'));
const AppointmentWorkbench = lazy(() => import('@/pages/Workflows/AppointmentWorkbench'));
const ApiKeysWorkbench = lazy(() => import('@/pages/Workflows/ApiKeysWorkbench'));
const ProductsWorkbench = lazy(() => import('@/pages/Workflows/ProductsWorkbench'));
const ProductCategoriesWorkbench = lazy(() => import('@/pages/Workflows/ProductCategoriesWorkbench'));
const ExpensesWorkbench = lazy(() => import('@/pages/Workflows/ExpensesWorkbench'));
const ExpenseCategoriesWorkbench = lazy(() => import('@/pages/Workflows/ExpenseCategoriesWorkbench'));
const EnterpriseDashboard = lazy(() => import('@/pages/Workflows/EnterpriseDashboard'));
const EntityWorkspace = lazy(() => import('@/pages/Workflows/EntityWorkspace'));
const OfferWorkbench = lazy(() => import('@/pages/Workflows/OfferWorkbench'));
const OrderWorkbench = lazy(() => import('@/pages/Workflows/OrderWorkbench'));
const PublicFormsWorkbench = lazy(() => import('@/pages/Workflows/PublicFormsWorkbench'));
const PublicFormPage = lazy(() => import('@/pages/Workflows/PublicFormPage'));
const ReportsOverview = lazy(() => import('@/pages/Workflows/ReportsOverview'));
const Invoice = lazy(() => import('@/pages/Workflows/InvoiceWorkbench'));
const Quote = lazy(() => import('@/pages/Workflows/QuoteWorkbench'));
const DealPipeline = lazy(() => import('@/pages/Workflows/DealPipeline'));
const PurchaseOrder = lazy(() => import('@/pages/Workflows/PurchaseOrderWorkbench'));
const CampaignComposer = lazy(() => import('@/pages/Workflows/CampaignComposer'));
const TaskBoard = lazy(() => import('@/pages/Workflows/TaskBoard'));
const AIStudio = lazy(() => import('@/pages/Workflows/AIStudio'));
const AgentStudio = lazy(() => import('@/pages/Workflows/AgentStudio'));
const AutomationConsole = lazy(() => import('@/pages/Workflows/AutomationConsole'));
const UnifiedWorkspace = lazy(() => import('@/pages/UnifiedWorkspace'));
const FinanceManagement = lazy(() => import('@/pages/Workflows/FinanceManagement'));
const GenericEntityManager = lazy(() => import('@/pages/GenericEntityManager'));
const Settings = lazy(() => import('@/pages/Settings/Settings'));
const Profile = lazy(() => import('@/pages/Profile'));

const WorkspaceRoute = ({ moduleKey }) => <UnifiedWorkspace moduleKey={moduleKey} />;
const RecordRoute = ({ entity, title, description, sample, links }) => (
  <EntityWorkspace
    entity={entity}
    title={title}
    description={description}
    sample={sample}
    links={links}
  />
);

let routes = {
  expense: [],
  default: [
    {
      path: '/login',
      element: <Navigate to="/" />,
    },
    {
      path: '/logout',
      element: <Logout />,
    },
    {
      path: '/',
      element: <EnterpriseDashboard />,
    },
    {
      path: '/dashboard',
      element: <EnterpriseDashboard />,
    },
    {
      path: '/customer',
      element: <CustomerWorkbench />,
    },
    {
      path: '/people',
      element: <PeopleWorkbench />,
    },
    {
      path: '/company',
      element: <CompanyWorkbench />,
    },
    {
      path: '/lead',
      element: <LeadWorkbench />,
    },
    {
      path: '/offers',
      element: <OfferWorkbench />,
    },

    {
      path: '/invoice',
      element: <Invoice />,
    },
    {
      path: '/payment',
      element: <Navigate to="/finance" replace />,
    },
    {
      path: '/quote',
      element: <Quote />,
    },
    {
      path: '/deals',
      element: <DealPipeline />,
    },

    {
      path: '/settings',
      element: <Settings />,
    },
    {
      path: '/settings/edit/:settingsKey',
      element: <Settings />,
    },
    {
      path: '/payment/mode',
      element: <PaymentModeWorkbench />,
    },
    {
      path: '/taxes',
      element: <TaxWorkbench />,
    },
    {
      path: '/crm',
      element: <WorkspaceRoute moduleKey="crm" />,
    },
    {
      path: '/sales',
      element: <WorkspaceRoute moduleKey="sales" />,
    },
    {
      path: '/marketing',
      element: <CampaignComposer />,
    },
    {
      path: '/finance',
      element: <FinanceManagement />,
    },
    {
      path: '/vendors',
      element: <WorkspaceRoute moduleKey="vendors" />,
    },
    {
      path: '/catalog',
      element: <WorkspaceRoute moduleKey="inventory" />,
    },
    {
      path: '/products',
      element: <ProductsWorkbench />,
    },
    {
      path: '/product-categories',
      element: <ProductCategoriesWorkbench />,
    },
    {
      path: '/orders',
      element: <OrderWorkbench />,
    },
    {
      path: '/purchases',
      element: <PurchaseOrder />,
    },
    {
      path: '/suppliers',
      element: <SupplierWorkbench />,
    },
    {
      path: '/expenses',
      element: <ExpensesWorkbench />,
    },
    {
      path: '/expense-categories',
      element: <ExpenseCategoriesWorkbench />,
    },
    {
      path: '/currencies',
      element: <CurrencyWorkbench />,
    },
    {
      path: '/pdf-settings',
      element: <PdfSettingsWorkbench />,
    },
    {
      path: '/operations',
      element: <WorkspaceRoute moduleKey="operations" />,
    },
    {
      path: '/branches',
      element: <BranchWorkbench />,
    },
    {
      path: '/company-workspace',
      element: <CompanyWorkspaceWorkbench />,
    },
    {
      path: '/booking',
      element: <BookingWorkbench />,
    },
    {
      path: '/appointment',
      element: <AppointmentWorkbench />,
    },
    {
      path: '/documents',
      element: <DocumentsWorkbench />,
    },
    {
      path: '/contracts',
      element: <ContractsWorkbench />,
    },
    {
      path: '/platform',
      element: <WorkspaceRoute moduleKey="platform" />,
    },
    {
      path: '/reports',
      element: <ReportsOverview />,
    },
    {
      path: '/forms',
      element: <PublicFormsWorkbench />,
    },
    {
      path: '/forms/public/:slug',
      element: <PublicFormPage />,
    },
    {
      path: '/developer/api-keys',
      element: <ApiKeysWorkbench />,
    },
    {
      path: '/automations',
      element: <AutomationConsole />,
    },
    {
      path: '/automations/history',
      element: <AutomationConsole />,
    },
    {
      path: '/automations/rules-jobs',
      element: <AutomationConsole />,
    },
    {
      path: '/tasks',
      element: <TaskBoard />,
    },
    {
      path: '/ai-studio',
      element: <AgentStudio />,
    },
    {
      path: '/ai-studio/library',
      element: <AgentStudio />,
    },
    {
      path: '/ai-studio/builder',
      element: <AgentStudio />,
    },
    {
      path: '/ai-studio/runs',
      element: <AgentStudio />,
    },
    {
      path: '/ai-studio/knowledge',
      element: <AgentStudio />,
    },
    {
      path: '/ai-studio/tools',
      element: <AgentStudio />,
    },
    {
      path: '/ai-studio/brand',
      element: <AgentStudio />,
    },
    {
      path: '/ai-studio/social',
      element: <AgentStudio />,
    },
    {
      path: '/ai-studio/budgets',
      element: <AgentStudio />,
    },
    {
      path: '/ai-studio/providers',
      element: <AgentStudio />,
    },
    {
      path: '/ai-studio/content',
      element: <AIStudio />,
    },
    {
      path: '/ai-studio/assets',
      element: <AIStudio />,
    },
    {
      path: '/records/:entity',
      element: <GenericEntityManager />,
    },

    {
      path: '/profile',
      element: <Profile />,
    },
    {
      path: '*',
      element: <NotFound />,
    },
  ],
};

export default routes;
