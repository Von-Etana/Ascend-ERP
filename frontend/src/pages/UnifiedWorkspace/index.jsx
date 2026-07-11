import { useState } from 'react';
import { Button, Card, Col, Row, Space, Statistic, Tag, Typography, Divider, Badge } from 'antd';
import {
  UserOutlined,
  ContactsOutlined,
  ApartmentOutlined,
  UserAddOutlined,
  DollarOutlined,
  GiftOutlined,
  OrderedListOutlined,
  NotificationOutlined,
  MessageOutlined,
  FileTextOutlined,
  FileDoneOutlined,
  WalletOutlined,
  ShoppingCartOutlined,
  AimOutlined,
  CheckSquareOutlined,
  BoxPlotOutlined,
  AppstoreOutlined,
  ShoppingOutlined,
  AuditOutlined,
  TeamOutlined,
  ShopOutlined,
  RocketOutlined,
  FormOutlined,
  GlobalOutlined,
  ShareAltOutlined,
  BankOutlined,
  CalculatorOutlined,
  FolderOpenOutlined,
  DashboardOutlined,
  CreditCardOutlined,
  PercentageOutlined,
  SettingOutlined,
  EnvironmentOutlined,
  CalendarOutlined,
  ScheduleOutlined,
  FileProtectOutlined,
  SafetyCertificateOutlined,
  KeyOutlined,
  ThunderboltOutlined,
  HistoryOutlined,
  ApiOutlined,
  LockOutlined,
  SafetyOutlined,
  PlusOutlined,
  RightOutlined,
  ArrowRightOutlined,
  PlayCircleOutlined,
  ExperimentOutlined,
  ReloadOutlined,
  CheckCircleOutlined,
} from '@ant-design/icons';
import { Link, useNavigate } from 'react-router-dom';
import { moduleRegistry } from './entityRegistry';

const { Title, Text } = Typography;

const ICON_MAP = {
  UserOutlined: <UserOutlined />,
  ContactsOutlined: <ContactsOutlined />,
  ApartmentOutlined: <ApartmentOutlined />,
  UserAddOutlined: <UserAddOutlined />,
  DollarOutlined: <DollarOutlined />,
  GiftOutlined: <GiftOutlined />,
  OrderedListOutlined: <OrderedListOutlined />,
  NotificationOutlined: <NotificationOutlined />,
  MessageOutlined: <MessageOutlined />,
  FileTextOutlined: <FileTextOutlined />,
  FileDoneOutlined: <FileDoneOutlined />,
  WalletOutlined: <WalletOutlined />,
  ShoppingCartOutlined: <ShoppingCartOutlined />,
  AimOutlined: <AimOutlined />,
  CheckSquareOutlined: <CheckSquareOutlined />,
  BoxPlotOutlined: <BoxPlotOutlined />,
  AppstoreOutlined: <AppstoreOutlined />,
  ShoppingOutlined: <ShoppingOutlined />,
  AuditOutlined: <AuditOutlined />,
  TeamOutlined: <TeamOutlined />,
  ShopOutlined: <ShopOutlined />,
  RocketOutlined: <RocketOutlined />,
  FormOutlined: <FormOutlined />,
  GlobalOutlined: <GlobalOutlined />,
  ShareAltOutlined: <ShareAltOutlined />,
  BankOutlined: <BankOutlined />,
  CalculatorOutlined: <CalculatorOutlined />,
  FolderOpenOutlined: <FolderOpenOutlined />,
  DashboardOutlined: <DashboardOutlined />,
  CreditCardOutlined: <CreditCardOutlined />,
  PercentageOutlined: <PercentageOutlined />,
  SettingOutlined: <SettingOutlined />,
  EnvironmentOutlined: <EnvironmentOutlined />,
  CalendarOutlined: <CalendarOutlined />,
  ScheduleOutlined: <ScheduleOutlined />,
  FileProtectOutlined: <FileProtectOutlined />,
  SafetyCertificateOutlined: <SafetyCertificateOutlined />,
  KeyOutlined: <KeyOutlined />,
  ThunderboltOutlined: <ThunderboltOutlined />,
  HistoryOutlined: <HistoryOutlined />,
  ApiOutlined: <ApiOutlined />,
  LockOutlined: <LockOutlined />,
  SafetyOutlined: <SafetyOutlined />,
};

const getEntityRoute = (entity) => {
  const routesMap = {
    client: '/customer',
    contact: '/people',
    company: '/company',
    lead: '/lead',
    deal: '/deals',
    offer: '/offers',
    quote: '/quote',
    invoice: '/invoice',
    payment: '/finance',
    product: '/products',
    productcategory: '/product-categories',
    order: '/orders',
    purchaseorder: '/purchases',
    supplier: '/suppliers',
    expense: '/expenses',
    expensecategory: '/expense-categories',
    currency: '/currencies',
    paymentmode: '/payment/mode',
    taxes: '/taxes',
    pdfsetting: '/pdf-settings',
    branch: '/branches',
    companyworkspace: '/company-workspace',
    calendarbooking: '/booking',
    appointment: '/appointment',
    document: '/documents',
    contract: '/contracts',
    apikey: '/developer/api-keys',
    automationrule: '/automations',
    publicform: '/forms',
  };
  return routesMap[entity] || `/records/${entity}`;
};

export default function UnifiedWorkspace({ moduleKey }) {
  const navigate = useNavigate();
  const config = moduleRegistry[moduleKey] || moduleRegistry.crm;
  const themeColor = config.themeColor || '#4f46e5';

  return (
    <div style={{ padding: '28px 36px', width: '100%', background: '#f8fafc', minHeight: '100vh' }}>
      <Space direction="vertical" size={28} style={{ width: '100%' }}>
        {/* Flat Modern Hero Banner */}
        <div
          style={{
            background: '#ffffff',
            padding: '24px 32px',
            borderRadius: '12px',
            borderLeft: `6px solid ${themeColor}`,
            boxShadow: '0 1px 3px 0 rgba(0, 0, 0, 0.05), 0 1px 2px 0 rgba(0, 0, 0, 0.03)',
          }}
        >
          <Row justify="space-between" align="middle" gutter={[16, 16]}>
            <Col xs={24} md={18}>
              <Title level={2} style={{ margin: 0, fontWeight: 700, color: '#1e293b' }}>
                {config.title}
              </Title>
              <Text type="secondary" style={{ fontSize: '15px', display: 'block', marginTop: 6, color: '#64748b' }}>
                {config.description}
              </Text>
            </Col>
            <Col>
              <Tag color="blue" style={{ margin: 0, padding: '4px 12px', fontSize: '13px', borderRadius: '4px', fontWeight: 600 }}>
                {moduleKey.toUpperCase()}
              </Tag>
            </Col>
          </Row>
        </div>

        {/* Stats Section with clean outline style */}
        <Row gutter={[20, 20]}>
          {config.stats.map(([label, value]) => (
            <Col xs={24} sm={8} key={label}>
              <Card
                size="small"
                style={{
                  borderRadius: '10px',
                  border: '1px solid #e2e8f0',
                  boxShadow: '0 1px 2px 0 rgba(0, 0, 0, 0.02)',
                }}
              >
                <Statistic
                  title={<Text type="secondary" style={{ fontSize: '13px', fontWeight: 500 }}>{label}</Text>}
                  value={value}
                  valueStyle={{ color: themeColor, fontWeight: 700, fontSize: '24px' }}
                />
              </Card>
            </Col>
          ))}
        </Row>

        {/* Two-Column Workspace Layout */}
        <Row gutter={[24, 24]}>
          {/* Main Actionable Entities Card Grid */}
          <Col xs={24} lg={16}>
            <Card
              title={<span style={{ fontWeight: 700, color: '#0f172a' }}>Operator Entities Catalog</span>}
              extra={<ApiOutlined style={{ color: themeColor, fontSize: '18px' }} />}
              style={{ borderRadius: '12px', border: '1px solid #e2e8f0' }}
            >
              <Row gutter={[16, 16]}>
                {config.entities.map((ent) => {
                  const route = getEntityRoute(ent.entity);
                  return (
                    <Col xs={24} sm={12} key={ent.entity}>
                      <Card
                        hoverable
                        style={{
                          borderRadius: '8px',
                          border: '1px solid #e2e8f0',
                          height: '100%',
                          display: 'flex',
                          flexDirection: 'column',
                          justifyContent: 'space-between',
                        }}
                        bodyStyle={{ padding: '16px', display: 'flex', flexDirection: 'column', height: '100%' }}
                      >
                        <div style={{ flexGrow: 1 }}>
                          <Space style={{ marginBottom: 10 }}>
                            <span style={{ fontSize: '20px', color: themeColor, display: 'inline-flex', alignItems: 'center' }}>
                              {ICON_MAP[ent.icon] || <FileTextOutlined />}
                            </span>
                            <span style={{ fontWeight: 600, fontSize: '15px', color: '#1e293b' }}>{ent.label}</span>
                          </Space>
                          <p style={{ color: '#64748b', fontSize: '13px', lineHeight: '18px', margin: '0 0 12px 0' }}>
                            {ent.description || 'Manage parameters, options and listings.'}
                          </p>

                          {/* Interactive Live Sample Inspector Box */}
                          {ent.sample && (
                            <div
                              style={{
                                background: '#f1f5f9',
                                border: '1px solid #e2e8f0',
                                borderRadius: '6px',
                                padding: '10px',
                                marginBottom: '14px',
                                fontFamily: 'Courier New, monospace',
                                fontSize: '11px',
                                color: '#334155',
                              }}
                            >
                              <div style={{ fontWeight: 600, color: '#475569', borderBottom: '1px solid #cbd5e1', paddingBottom: '4px', marginBottom: '6px' }}>
                                Data preview sample
                              </div>
                              {Object.entries(ent.sample).map(([k, v]) => (
                                <div key={k} style={{ whiteSpace: 'nowrap', overflow: 'hidden', textOverflow: 'ellipsis' }}>
                                  <span style={{ color: '#0284c7' }}>{k}</span>: {typeof v === 'object' ? JSON.stringify(v) : String(v)}
                                </div>
                              ))}
                            </div>
                          )}
                        </div>

                        <div>
                          <Divider style={{ margin: '12px 0' }} />
                          <div style={{ display: 'flex', justifyContent: 'space-between', gap: '8px' }}>
                            <Button
                              type="primary"
                              ghost
                              size="small"
                              style={{ flex: 1, borderRadius: '4px', borderColor: themeColor, color: themeColor }}
                              icon={<RightOutlined />}
                              onClick={() => navigate(route)}
                            >
                              View List
                            </Button>
                            <Button
                              size="small"
                              style={{ flex: 1, borderRadius: '4px' }}
                              icon={<PlusOutlined />}
                              onClick={() => navigate(route, { state: { openCreateDrawer: true } })}
                            >
                              Quick Add
                            </Button>
                          </div>
                        </div>
                      </Card>
                    </Col>
                  );
                })}
              </Row>
            </Card>
          </Col>

          {/* Sidebar Section: Guided Workflows & Panel */}
          <Col xs={24} lg={8}>
            <Space direction="vertical" size={20} style={{ width: '100%' }}>
              {/* Interactive Guided Workflow Stepper */}
              <Card
                title={<span style={{ fontWeight: 700, color: '#0f172a' }}>Guided Workflows</span>}
                extra={<ThunderboltOutlined style={{ color: themeColor, fontSize: '18px' }} />}
                style={{ borderRadius: '12px', border: '1px solid #e2e8f0' }}
              >
                <div style={{ display: 'flex', flexDirection: 'column', gap: '16px' }}>
                  {config.workflows.map((flow, index) => (
                    <div
                      key={flow.name}
                      style={{
                        padding: '14px',
                        border: '1px solid #e2e8f0',
                        borderRadius: '8px',
                        background: '#ffffff',
                        cursor: 'pointer',
                        transition: 'border-color 0.2s',
                      }}
                      onClick={() => navigate(flow.link)}
                    >
                      <Space align="start">
                        <Badge
                          count={index + 1}
                          style={{
                            backgroundColor: themeColor,
                            color: '#ffffff',
                            fontWeight: 700,
                            marginRight: 4,
                          }}
                        />
                        <div>
                          <div style={{ fontWeight: 600, fontSize: '14px', color: '#1e293b' }}>
                            {flow.name}
                          </div>
                          <Text type="secondary" style={{ fontSize: '12px', display: 'block', marginTop: 4 }}>
                            Click to launch this workflow route
                          </Text>
                        </div>
                      </Space>
                    </div>
                  ))}
                </div>
              </Card>

              {/* Console Quick Actions */}
              <Card
                title={<span style={{ fontWeight: 700, color: '#0f172a' }}>Global Shortcuts</span>}
                style={{ borderRadius: '12px', border: '1px solid #e2e8f0' }}
              >
                <Space direction="vertical" style={{ width: '100%' }} size={10}>
                  <Button
                    icon={<ThunderboltOutlined />}
                    type="primary"
                    style={{ width: '100%', background: themeColor, borderColor: themeColor, borderRadius: '6px' }}
                    onClick={() => navigate('/automations')}
                  >
                    Manage Rules Engine
                  </Button>
                  <Button
                    icon={<ExperimentOutlined />}
                    style={{ width: '100%', borderRadius: '6px' }}
                    onClick={() => navigate('/ai-studio/content')}
                  >
                    Open AI Writer
                  </Button>
                  <Button
                    icon={<CalendarOutlined />}
                    style={{ width: '100%', borderRadius: '6px' }}
                    onClick={() => navigate('/tasks')}
                  >
                    View Task Board
                  </Button>
                </Space>
              </Card>
            </Space>
          </Col>
        </Row>
      </Space>
    </div>
  );
}
