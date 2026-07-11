import { useState, useEffect, useMemo } from 'react';
import { Link, useLocation, useNavigate } from 'react-router-dom';
import { Button, Drawer, Layout, Menu } from 'antd';
import { useSelector } from 'react-redux';

import { useAppContext } from '@/context/appContext';

import useLanguage from '@/locale/useLanguage';
import { selectCurrentAdmin } from '@/redux/auth/selectors';
import logo from '@/style/images/logo-19.png';
import { canAccessEntityAction, canAccessPermission } from '@/utils/permissions';

import useResponsive from '@/hooks/useResponsive';

import {
  DashboardOutlined,
  TagOutlined,
  TagsOutlined,
  MenuOutlined,
  FilterOutlined,
  WalletOutlined,
  ReconciliationOutlined,
  ApartmentOutlined,
  DatabaseOutlined,
  AppstoreOutlined,
  RobotOutlined,
  ThunderboltOutlined,
} from '@ant-design/icons';

const { Sider } = Layout;
const pathToKey = (pathname = '/') => {
  const normalized = pathname.replace(/^\/+/, '').replace(/\//g, '-');
  return normalized || 'dashboard';
};

const buildParentMap = (items = []) =>
  items.reduce((acc, item) => {
    if (item.children) {
      item.children.forEach((child) => {
        acc[child.key] = item.key;
      });
    }
    return acc;
  }, {});

export default function Navigation() {
  const { isMobile } = useResponsive();

  return isMobile ? <MobileSidebar /> : <Sidebar collapsible={false} />;
}

function Sidebar({ collapsible, isMobile = false }) {
  let location = useLocation();

  const { state: stateApp, appContextAction } = useAppContext();
  const { isNavMenuClose } = stateApp;
  const { navMenu } = appContextAction;
  const [showLogoApp, setLogoApp] = useState(isNavMenuClose);
  const [currentPath, setCurrentPath] = useState(pathToKey(location.pathname));
  const [openKeys, setOpenKeys] = useState([]);
  const currentAdmin = useSelector(selectCurrentAdmin);

  const translate = useLanguage();
  const navigate = useNavigate();

  const items = useMemo(() => [
    {
      key: 'dashboard',
      icon: <DashboardOutlined />,
      label: <Link to={'/dashboard'}>{translate('dashboard')}</Link>,
    },
    {
      key: 'crm-group',
      icon: <FilterOutlined />,
      label: <Link to={'/crm'}>CRM</Link>,
      children: [
        {
          key: 'crm',
          label: <Link to={'/crm'}>Workspace</Link>,
          disabled: !canAccessEntityAction(currentAdmin, 'deal', 'read'),
        },
        {
          key: 'customer',
          label: <Link to={'/customer'}>Customer</Link>,
          disabled: !canAccessEntityAction(currentAdmin, 'client', 'read'),
        },
        {
          key: 'people',
          label: <Link to={'/people'}>People</Link>,
          disabled: !canAccessEntityAction(currentAdmin, 'contact', 'read'),
        },
        {
          key: 'company',
          label: <Link to={'/company'}>Company</Link>,
          disabled: !canAccessEntityAction(currentAdmin, 'company', 'read'),
        },
        {
          key: 'lead',
          label: <Link to={'/lead'}>Lead</Link>,
          disabled: !canAccessEntityAction(currentAdmin, 'lead', 'read'),
        },
        {
          key: 'deals',
          label: <Link to={'/deals'}>Deal Pipeline</Link>,
          disabled: !canAccessEntityAction(currentAdmin, 'deal', 'read'),
        },
        {
          key: 'offers',
          label: <Link to={'/offers'}>Offers</Link>,
          disabled: !canAccessEntityAction(currentAdmin, 'offer', 'read'),
        },
      ],
    },
    {
      key: 'sales-group',
      icon: <TagsOutlined />,
      label: <Link to={'/sales'}>Sales</Link>,
      children: [
        {
          key: 'sales',
          label: <Link to={'/sales'}>Workspace</Link>,
          disabled: !canAccessEntityAction(currentAdmin, 'quote', 'read'),
        },
        {
          key: 'quote',
          label: <Link to={'/quote'}>{translate('quote')}</Link>,
          disabled: !canAccessEntityAction(currentAdmin, 'quote', 'read'),
        },
        {
          key: 'invoice',
          label: <Link to={'/invoice'}>{translate('invoices')}</Link>,
          disabled: !canAccessEntityAction(currentAdmin, 'invoice', 'read'),
        },
        {
          key: 'payment',
          label: <Link to={'/payment'}>{translate('payments')}</Link>,
          disabled: !canAccessEntityAction(currentAdmin, 'payment', 'read'),
        },
      ],
    },
    {
      key: 'catalog-group',
      icon: <AppstoreOutlined />,
      label: <Link to={'/catalog'}>Catalog / Inventory</Link>,
      children: [
        {
          key: 'catalog',
          label: <Link to={'/catalog'}>Workspace</Link>,
          disabled: !canAccessEntityAction(currentAdmin, 'product', 'read'),
        },
        {
          key: 'products',
          label: <Link to={'/products'}>Product</Link>,
          disabled: !canAccessEntityAction(currentAdmin, 'product', 'read'),
        },
        {
          key: 'product-categories',
          label: <Link to={'/product-categories'}>Product Category</Link>,
          disabled: !canAccessEntityAction(currentAdmin, 'productcategory', 'read'),
        },
        {
          key: 'orders',
          label: <Link to={'/orders'}>Order</Link>,
          disabled: !canAccessEntityAction(currentAdmin, 'order', 'read'),
        },
        {
          key: 'purchases',
          label: <Link to={'/purchases'}>Purchase</Link>,
          disabled: !canAccessEntityAction(currentAdmin, 'purchaseorder', 'read'),
        },
        {
          key: 'suppliers',
          label: <Link to={'/suppliers'}>Supplier</Link>,
          disabled: !canAccessEntityAction(currentAdmin, 'supplier', 'read'),
        },
        {
          key: 'vendors',
          label: <Link to={'/vendors'}>Vendor</Link>,
          disabled: !canAccessEntityAction(currentAdmin, 'vendor', 'read'),
        },
      ],
    },
    {
      key: 'marketing',
      icon: <TagOutlined />,
      label: <Link to={'/marketing'}>Marketing</Link>,
      disabled: !canAccessEntityAction(currentAdmin, 'campaign', 'read'),
    },
    {
      key: 'automations-group',
      icon: <ThunderboltOutlined />,
      label: <Link to={'/automations'}>Automations</Link>,
      children: [
        {
          key: 'automations',
          label: <Link to={'/automations'}>Workspace</Link>,
          disabled: !canAccessPermission(currentAdmin, 'automations.runner.read'),
        },
        {
          key: 'automations-history',
          label: <Link to={'/automations/history'}>Run History</Link>,
          disabled: !canAccessPermission(currentAdmin, 'automations.runner.read'),
        },
        {
          key: 'automations-rules-jobs',
          label: <Link to={'/automations/rules-jobs'}>Rules & Jobs</Link>,
          disabled: !canAccessPermission(currentAdmin, 'automations.runner.read'),
        },
      ],
    },
    {
      key: 'ai-studio-group',
      icon: <RobotOutlined />,
      label: <Link to={'/ai-studio'}>AI Studio</Link>,
      children: [
        {
          key: 'ai-studio',
          label: <Link to={'/ai-studio'}>Agent Library</Link>,
          disabled: !canAccessPermission(currentAdmin, 'ai.studio.read'),
        },
        {
          key: 'ai-studio-builder',
          label: <Link to={'/ai-studio/builder'}>Agent Builder</Link>,
          disabled: !canAccessPermission(currentAdmin, 'ai.studio.read'),
        },
        {
          key: 'ai-studio-runs',
          label: <Link to={'/ai-studio/runs'}>Runs & Approvals</Link>,
          disabled: !canAccessPermission(currentAdmin, 'ai.studio.read'),
        },
        {
          key: 'ai-studio-social',
          label: <Link to={'/ai-studio/social'}>Social Scheduler</Link>,
          disabled: !canAccessPermission(currentAdmin, 'ai.studio.read'),
        },
        {
          key: 'ai-studio-providers',
          label: <Link to={'/ai-studio/providers'}>Provider Accounts</Link>,
          disabled: !canAccessEntityAction(currentAdmin, 'integrationaccount', 'read'),
        },
        {
          key: 'ai-studio-content',
          label: <Link to={'/ai-studio/content'}>Content Workspace</Link>,
          disabled: !canAccessPermission(currentAdmin, 'ai.studio.read'),
        },
      ],
    },
    {
      key: 'finance-group',
      icon: <WalletOutlined />,
      label: <Link to={'/finance'}>Finance / Settings</Link>,
      children: [
        {
          key: 'finance',
          label: <Link to={'/finance'}>Workspace</Link>,
          disabled: !canAccessEntityAction(currentAdmin, 'invoice', 'read'),
        },
        {
          key: 'expenses',
          label: <Link to={'/expenses'}>Expense</Link>,
          disabled: !canAccessEntityAction(currentAdmin, 'expense', 'read'),
        },
        {
          key: 'expense-categories',
          label: <Link to={'/expense-categories'}>Expense Category</Link>,
          disabled: !canAccessEntityAction(currentAdmin, 'expensecategory', 'read'),
        },
        {
          key: 'currencies',
          label: <Link to={'/currencies'}>Currency</Link>,
          disabled: !canAccessEntityAction(currentAdmin, 'currency', 'read'),
        },
        {
          key: 'payment-mode',
          label: <Link to={'/payment/mode'}>{translate('payments_mode')}</Link>,
          disabled: !canAccessEntityAction(currentAdmin, 'paymentmode', 'read'),
        },
        {
          key: 'taxes',
          label: <Link to={'/taxes'}>{translate('taxes')}</Link>,
          disabled: !canAccessEntityAction(currentAdmin, 'taxes', 'read'),
        },
        {
          key: 'pdf-settings',
          label: <Link to={'/pdf-settings'}>PDF Settings</Link>,
          disabled: !canAccessEntityAction(currentAdmin, 'pdfsetting', 'read'),
        },
      ],
    },
    {
      key: 'operations-group',
      icon: <ApartmentOutlined />,
      label: <Link to={'/operations'}>Operations</Link>,
      children: [
        {
          key: 'operations',
          label: <Link to={'/operations'}>Workspace</Link>,
          disabled: !canAccessEntityAction(currentAdmin, 'branch', 'read'),
        },
        {
          key: 'branches',
          label: <Link to={'/branches'}>Branch</Link>,
          disabled: !canAccessEntityAction(currentAdmin, 'branch', 'read'),
        },
        {
          key: 'company-workspace',
          label: <Link to={'/company-workspace'}>Company Workspace</Link>,
          disabled: !canAccessEntityAction(currentAdmin, 'companyworkspace', 'read'),
        },
        {
          key: 'booking',
          label: <Link to={'/booking'}>Booking</Link>,
          disabled: !canAccessEntityAction(currentAdmin, 'calendarbooking', 'read'),
        },
        {
          key: 'appointment',
          label: <Link to={'/appointment'}>Appointment</Link>,
          disabled: !canAccessEntityAction(currentAdmin, 'appointment', 'read'),
        },
        {
          key: 'documents',
          label: <Link to={'/documents'}>Documents</Link>,
          disabled: !canAccessEntityAction(currentAdmin, 'document', 'read'),
        },
        {
          key: 'contracts',
          label: <Link to={'/contracts'}>Contract</Link>,
          disabled: !canAccessEntityAction(currentAdmin, 'contract', 'read'),
        },
      ],
    },
    {
      key: 'platform-group',
      icon: <DatabaseOutlined />,
      label: <Link to={'/reports'}>Platform</Link>,
      children: [
        {
          key: 'reports',
          label: <Link to={'/reports'}>Reports</Link>,
        },
        {
          key: 'forms',
          label: <Link to={'/forms'}>Public Forms</Link>,
          disabled: !canAccessEntityAction(currentAdmin, 'publicform', 'read'),
        },
        {
          key: 'developer-api-keys',
          label: <Link to={'/developer/api-keys'}>Developer API Keys</Link>,
          disabled: !canAccessEntityAction(currentAdmin, 'apikey', 'read'),
        },
        {
          key: 'tasks',
          label: <Link to={'/tasks'}>Tasks</Link>,
          disabled: !canAccessEntityAction(currentAdmin, 'task', 'read'),
        },
        {
          key: 'settings',
          label: <Link to={'/settings'}>{translate('settings')}</Link>,
        },
      ],
    },
    {
      key: 'about',
      label: <Link to={'/about'}>{translate('about')}</Link>,
      icon: <ReconciliationOutlined />,
    },
  ], [currentAdmin, translate]);
  const parentMap = useMemo(() => buildParentMap(items), [items]);

  useEffect(() => {
    if (location) {
      const nextKey = pathToKey(location.pathname);
      if (currentPath !== nextKey) {
        setCurrentPath(nextKey);
      }
      const parentKey = parentMap[nextKey];
      setOpenKeys(parentKey ? [parentKey] : []);
    }
  }, [location, currentPath, parentMap]);

  useEffect(() => {
    if (isNavMenuClose) {
      setLogoApp(isNavMenuClose);
    }
    const timer = setTimeout(() => {
      if (!isNavMenuClose) {
        setLogoApp(isNavMenuClose);
      }
    }, 200);
    return () => clearTimeout(timer);
  }, [isNavMenuClose]);
  const onCollapse = () => {
    navMenu.collapse();
  };

  return (
    <Sider
      collapsible={collapsible}
      collapsed={collapsible ? isNavMenuClose : collapsible}
      onCollapse={onCollapse}
      className="navigation"
      width={256}
      style={{
        overflow: 'auto',
        height: '100vh',

        position: isMobile ? 'absolute' : 'relative',
        bottom: '20px',
        ...(!isMobile && {
          // border: 'none',
          ['left']: '20px',
          top: '20px',
          // borderRadius: '8px',
        }),
      }}
      theme={'light'}
    >
      <div
        className="logo"
        onClick={() => navigate('/')}
        style={{
          cursor: 'pointer',
          display: 'flex',
          justifyContent: isNavMenuClose ? 'center' : 'flex-start',
          alignItems: 'center',
          height: '40px',
          margin: isNavMenuClose ? '15px auto 30px' : '15px 15px 30px 30px',
          width: isNavMenuClose ? '40px' : '160px',
        }}
      >
        <img
          src={logo}
          alt="Logo"
          style={{
            height: '40px',
            width: isNavMenuClose ? '40px' : '100%',
            objectFit: isNavMenuClose ? 'cover' : 'contain',
            objectPosition: 'left',
          }}
        />
      </div>
      <Menu
        items={items}
        mode="inline"
        theme={'light'}
        selectedKeys={[currentPath]}
        openKeys={openKeys}
        onOpenChange={(keys) => setOpenKeys(keys)}
        style={{
          width: 256,
        }}
      />
    </Sider>
  );
}

function MobileSidebar() {
  const [visible, setVisible] = useState(false);
  const showDrawer = () => {
    setVisible(true);
  };
  const onClose = () => {
    setVisible(false);
  };

  return (
    <>
      <Button
        type="text"
        size="large"
        onClick={showDrawer}
        className="mobile-sidebar-btn"
        style={{ ['marginLeft']: 25 }}
      >
        <MenuOutlined style={{ fontSize: 18 }} />
      </Button>
      <Drawer
        width={250}
        // style={{ backgroundColor: 'rgba(255, 255, 255, 1)' }}
        placement={'left'}
        closable={false}
        onClose={onClose}
        open={visible}
      >
        <Sidebar collapsible={false} isMobile={true} />
      </Drawer>
    </>
  );
}
