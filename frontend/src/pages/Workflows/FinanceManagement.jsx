import { useEffect, useState, useMemo } from 'react';
import {
  Card, Col, Row, Space, Statistic, Table, Tag, Typography, Tabs, Button, Form,
  Input, InputNumber, Select, Switch, message, Modal, Progress, Alert, Divider,
  Badge
} from 'antd';
import {
  WalletOutlined, BankOutlined, PlusOutlined, FileTextOutlined,
  DollarOutlined, SettingOutlined, SwapOutlined, PieChartOutlined
} from '@ant-design/icons';
import { Link } from 'react-router-dom';
import dayjs from 'dayjs';
import { request } from '@/request';
import useMoney from '@/settings/useMoney';
import { DEFAULT_CURRENCY_CODE } from '@/constants/platformDefaults';
import LiveChart from '@/components/LiveChart';

const { Title, Text, Paragraph } = Typography;

export default function FinanceManagement() {
  const [activeTab, setActiveTab] = useState('overview');
  const [loading, setLoading] = useState(false);
  const [data, setData] = useState({
    invoices: [],
    payments: [],
    expenses: [],
    budgets: [],
  });
  const [currencies, setCurrencies] = useState([]);
  const [error, setError] = useState('');
  const { moneyFormatter } = useMoney();

  // Dialog states
  const [budgetModalVisible, setBudgetModalVisible] = useState(false);
  const [budgetForm] = Form.useForm();
  const [budgetBusy, setBudgetBusy] = useState(false);

  const load = async () => {
    setLoading(true);
    setError('');
    try {
      const [invoiceRes, paymentRes, expenseRes, budgetRes, currencyRes] = await Promise.all([
        request.listAll({ entity: 'invoice' }),
        request.listAll({ entity: 'payment' }),
        request.listAll({ entity: 'expense' }),
        request.listAll({ entity: 'agentbudget' }),
        request.listAll({ entity: 'currency' }),
      ]);

      const responses = [invoiceRes, paymentRes, expenseRes, budgetRes, currencyRes];
      const failure = responses.find((r) => r && r.success === false);
      if (failure) {
        setError(failure.message || 'Unable to retrieve financial data.');
      }

      setData({
        invoices: invoiceRes?.result || [],
        payments: paymentRes?.result || [],
        expenses: expenseRes?.result || [],
        budgets: budgetRes?.result || [],
      });
      setCurrencies(currencyRes?.result || []);
    } catch (err) {
      setError(err?.message || 'Error occurred while loading data.');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    load();
  }, []);

  // Summary Metrics
  const summary = useMemo(() => {
    const totalInvoiced = data.invoices.reduce((sum, inv) => sum + Number(inv.total || 0), 0);
    const totalCollected = data.payments.reduce((sum, p) => sum + Number(p.amount || 0), 0);
    const totalExpenses = data.expenses.reduce((sum, exp) => sum + Number(exp.amount?.amount || 0), 0);
    const netCashFlow = totalCollected - totalExpenses;
    const outstanding = data.invoices.reduce((sum, inv) => {
      if (inv.status !== 'paid') {
        return sum + Number(inv.total - (inv.credit || 0));
      }
      return sum;
    }, 0);

    return {
      totalInvoiced,
      totalCollected,
      totalExpenses,
      netCashFlow,
      outstanding,
    };
  }, [data]);

  // Unified Transactions Ledger list
  const transactions = useMemo(() => {
    const list = [];

    // Add Invoices as receivables
    data.invoices.forEach((inv) => {
      list.push({
        key: `inv-${inv._id}`,
        id: inv._id,
        ref: inv.number ? `INV-${inv.number}` : 'Invoice',
        type: 'sale',
        category: 'Sales Invoice',
        amount: inv.total || 0,
        status: inv.status || 'draft',
        date: inv.date || inv.created,
        detail: `Client invoice draft/issue`,
      });
    });

    // Add Payments as income collected
    data.payments.forEach((p) => {
      list.push({
        key: `pay-${p._id}`,
        id: p._id,
        ref: p.number ? `PAY-${p.number}` : 'Payment',
        type: 'receipt',
        category: 'Cash Collection',
        amount: p.amount || 0,
        status: p.reconciled ? 'reconciled' : 'unreconciled',
        date: p.date || p.created,
        detail: `Collected payment reference: ${p.reference || '-'}`,
      });
    });

    // Add Expenses
    data.expenses.forEach((exp) => {
      list.push({
        key: `exp-${exp._id}`,
        id: exp._id,
        ref: exp.title || 'Expense',
        type: 'expense',
        category: 'Operating Expense',
        amount: exp.amount?.amount || 0,
        status: exp.status || 'paid',
        date: exp.date || exp.created,
        detail: `Operational spend log`,
      });
    });

    // Sort chronologically (newest first)
    return list.sort((a, b) => new Date(b.date) - new Date(a.date));
  }, [data]);

  // Trend Grouping (by month)
  const chartData = useMemo(() => {
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    const groups = {};

    data.invoices.forEach((inv) => {
      const d = dayjs(inv.date || inv.created);
      const label = d.isValid() ? d.format('MMM') : 'Unknown';
      if (!groups[label]) groups[label] = { label, revenue: 0, expenses: 0, collected: 0 };
      groups[label].revenue += Number(inv.total || 0);
    });

    data.expenses.forEach((exp) => {
      const d = dayjs(exp.date || exp.created);
      const label = d.isValid() ? d.format('MMM') : 'Unknown';
      if (!groups[label]) groups[label] = { label, revenue: 0, expenses: 0, collected: 0 };
      groups[label].expenses += Number(exp.amount?.amount || 0);
    });

    data.payments.forEach((p) => {
      const d = dayjs(p.date || p.created);
      const label = d.isValid() ? d.format('MMM') : 'Unknown';
      if (!groups[label]) groups[label] = { label, revenue: 0, expenses: 0, collected: 0 };
      groups[label].collected += Number(p.amount || 0);
    });

    const sortedKeys = Object.keys(groups).sort((a, b) => {
      return months.indexOf(a) - months.indexOf(b);
    });

    return sortedKeys.map((k) => groups[k]);
  }, [data]);

  // Budget submission handler
  const handleCreateBudget = async (values) => {
    setBudgetBusy(true);
    try {
      const response = await request.create({
        entity: 'agentbudget',
        jsonData: {
          name: values.name,
          allocated: { amount: values.allocated, currency: DEFAULT_CURRENCY_CODE },
          actual: { amount: 0, currency: DEFAULT_CURRENCY_CODE },
          approvalThreshold: { amount: values.approvalThreshold, currency: DEFAULT_CURRENCY_CODE },
          status: 'active',
        },
      });
      if (response?.success) {
        message.success('Budget category created successfully');
        setBudgetModalVisible(false);
        budgetForm.resetFields();
        load();
      } else {
        message.error(response?.message || 'Failed to create budget.');
      }
    } catch (err) {
      message.error(err.message || 'Error occurred while saving budget.');
    }
    setBudgetBusy(false);
  };

  const statusColors = {
    paid: 'green',
    reconciled: 'green',
    sent: 'blue',
    unreconciled: 'orange',
    pending: 'orange',
    overdue: 'red',
    expense: 'red',
    sale: 'blue',
    receipt: 'green',
  };

  return (
    <div style={{ padding: '24px 32px', width: '100%', background: '#f8fafc', minHeight: '100vh' }}>
      <Space direction="vertical" size={24} style={{ width: '100%' }}>
        {/* Header */}
        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', flexWrap: 'wrap', gap: 16 }}>
          <div>
            <Title level={2} style={{ marginBottom: 4 }}>
              Finance Management
            </Title>
            <Text type="secondary">
              Professional accounting, ledger reconciliation, budget allocations, and performance analysis.
            </Text>
          </div>
          <Button type="primary" icon={<PlusOutlined />} onClick={() => setBudgetModalVisible(true)}>
            Add Budget Limit
          </Button>
        </div>

        {error && <Alert type="warning" showIcon message={error} />}

        {/* Tab Controls */}
        <Tabs
          activeKey={activeTab}
          onChange={setActiveTab}
          items={[
            { key: 'overview', label: 'Console Dashboard' },
            { key: 'ledger', label: 'Unified Transaction Ledger' },
            { key: 'budgets', label: 'Budget Management' },
            { key: 'config', label: 'Financial Setup Parameters' },
          ]}
        />

        {/* Tab Content 1: Dashboard Overview */}
        {activeTab === 'overview' && (
          <Space direction="vertical" size={24} style={{ width: '100%' }}>
            {/* Metric Blocks */}
            <Row gutter={[16, 16]}>
              <Col xs={24} sm={12} xl={6}>
                <Card loading={loading}>
                  <Statistic
                    title="Outstanding Receivables"
                    value={summary.outstanding}
                    formatter={(val) => moneyFormatter({ amount: val, currency_code: DEFAULT_CURRENCY_CODE })}
                    valueStyle={{ color: '#ff4d4f' }}
                  />
                </Card>
              </Col>
              <Col xs={24} sm={12} xl={6}>
                <Card loading={loading}>
                  <Statistic
                    title="Revenue Issued"
                    value={summary.totalInvoiced}
                    formatter={(val) => moneyFormatter({ amount: val, currency_code: DEFAULT_CURRENCY_CODE })}
                    valueStyle={{ color: '#1677ff' }}
                  />
                </Card>
              </Col>
              <Col xs={24} sm={12} xl={6}>
                <Card loading={loading}>
                  <Statistic
                    title="Cash Collected"
                    value={summary.totalCollected}
                    formatter={(val) => moneyFormatter({ amount: val, currency_code: DEFAULT_CURRENCY_CODE })}
                    valueStyle={{ color: '#52c41a' }}
                  />
                </Card>
              </Col>
              <Col xs={24} sm={12} xl={6}>
                <Card loading={loading}>
                  <Statistic
                    title="Net Performance Flow"
                    value={summary.netCashFlow}
                    formatter={(val) => moneyFormatter({ amount: val, currency_code: DEFAULT_CURRENCY_CODE })}
                    valueStyle={{ color: summary.netCashFlow >= 0 ? '#52c41a' : '#ff4d4f' }}
                  />
                </Card>
              </Col>
            </Row>

            {/* Performance Trend Chart */}
            <Row gutter={[16, 16]}>
              <Col span={24}>
                <Card title="Operational Revenue vs Expense Trends" loading={loading}>
                  <LiveChart
                    type="line"
                    data={chartData}
                    series={[
                      { key: 'revenue', label: 'Revenue Invoiced', color: '#1677ff' },
                      { key: 'collected', label: 'Cash Collection', color: '#52c41a' },
                      { key: 'expenses', label: 'Expenses Outflow', color: '#faad14' },
                    ]}
                    height={280}
                    currencySymbol="NGN "
                  />
                </Card>
              </Col>
            </Row>
          </Space>
        )}

        {/* Tab Content 2: Unified Transactions Ledger */}
        {activeTab === 'ledger' && (
          <Card title="Unified Ledger of Receipts & Operational Outflows" loading={loading}>
            <Table
              dataSource={transactions}
              columns={[
                {
                  title: 'Transaction Date',
                  dataIndex: 'date',
                  render: (val) => dayjs(val).format('YYYY-MM-DD HH:mm'),
                },
                {
                  title: 'Category',
                  dataIndex: 'category',
                  render: (val, r) => (
                    <Space>
                      <Badge status={r.type === 'expense' ? 'error' : r.type === 'receipt' ? 'success' : 'processing'} />
                      <Text strong>{val}</Text>
                    </Space>
                  ),
                },
                {
                  title: 'Reference / Title',
                  dataIndex: 'ref',
                  render: (val) => <Text>{val}</Text>,
                },
                {
                  title: 'Description',
                  dataIndex: 'detail',
                  ellipsis: true,
                },
                {
                  title: 'Transaction Type',
                  dataIndex: 'type',
                  render: (val) => <Tag color={statusColors[val]}>{val.toUpperCase()}</Tag>,
                },
                {
                  title: 'Amount',
                  dataIndex: 'amount',
                  render: (val) => moneyFormatter({ amount: val, currency_code: DEFAULT_CURRENCY_CODE }),
                  align: 'right',
                },
                {
                  title: 'Status',
                  dataIndex: 'status',
                  render: (val) => <Tag color={statusColors[val] || 'default'}>{val.toUpperCase()}</Tag>,
                },
              ]}
              pagination={{ pageSize: 10 }}
            />
          </Card>
        )}

        {/* Tab Content 3: Budget Management */}
        {activeTab === 'budgets' && (
          <Space direction="vertical" size={24} style={{ width: '100%' }}>
            <Row gutter={[16, 16]}>
              {data.budgets.length === 0 ? (
                <Col span={24}>
                  <Card style={{ textAlign: 'center', padding: '40px 0' }}>
                    <Text type="secondary">No budget limits configured. Add one to track allocation spending.</Text>
                  </Card>
                </Col>
              ) : (
                data.budgets.map((b) => {
                  const limit = b.allocated?.amount || 0;
                  const actual = b.actual?.amount || 0;
                  const progressPct = limit > 0 ? Math.round((actual / limit) * 100) : 0;
                  const remaining = Math.max(0, limit - actual);

                  return (
                    <Col xs={24} lg={12} key={b._id}>
                      <Card
                        title={b.name}
                        extra={<Tag color={b.status === 'active' ? 'green' : 'default'}>{b.status}</Tag>}
                        style={{ border: '1px solid #e2e8f0', borderRadius: '10px' }}
                      >
                        <Row gutter={12} style={{ marginBottom: 16 }}>
                          <Col span={8}>
                            <Statistic
                              title="Allocated Limit"
                              value={moneyFormatter({ amount: limit, currency_code: DEFAULT_CURRENCY_CODE })}
                              valueStyle={{ fontSize: '16px', fontWeight: 600 }}
                            />
                          </Col>
                          <Col span={8}>
                            <Statistic
                              title="Spent Actual"
                              value={moneyFormatter({ amount: actual, currency_code: DEFAULT_CURRENCY_CODE })}
                              valueStyle={{ fontSize: '16px', fontWeight: 600, color: progressPct > 90 ? '#ff4d4f' : '#faad14' }}
                            />
                          </Col>
                          <Col span={8}>
                            <Statistic
                              title="Remaining Limit"
                              value={moneyFormatter({ amount: remaining, currency_code: DEFAULT_CURRENCY_CODE })}
                              valueStyle={{ fontSize: '16px', fontWeight: 600, color: '#52c41a' }}
                            />
                          </Col>
                        </Row>
                        <Progress
                          percent={progressPct}
                          status={progressPct > 90 ? 'exception' : 'active'}
                          strokeColor={{ '0%': '#108ee9', '100%': progressPct > 90 ? '#f5222d' : '#87d068' }}
                        />
                        <div style={{ marginTop: 12 }}>
                          <Text type="secondary" style={{ fontSize: '12px' }}>
                            Bookkeeping threshold warning limit: {moneyFormatter({ amount: b.approvalThreshold?.amount || 0, currency_code: DEFAULT_CURRENCY_CODE })}
                          </Text>
                        </div>
                      </Card>
                    </Col>
                  );
                })
              )}
            </Row>
          </Space>
        )}

        {/* Tab Content 4: System Setup Parameters */}
        {activeTab === 'config' && (
          <Card title="Ledger Accounts, Currencies, Taxes & Settings Setup Grid">
            <Row gutter={[16, 16]}>
              <Col xs={24} md={12} lg={8}>
                <Card hoverable size="small" style={{ borderRadius: 8, height: '100%' }}>
                  <Space direction="vertical" size={10} style={{ width: '100%' }}>
                    <Space>
                      <BankOutlined style={{ fontSize: 24, color: '#8b5cf6' }} />
                      <Text strong style={{ fontSize: 16 }}>Ledger Accounts</Text>
                    </Space>
                    <Paragraph type="secondary" style={{ fontSize: 13, margin: 0 }}>
                      General ledger charts, bank accounts, asset definitions, and bookkeeping codes.
                    </Paragraph>
                    <Link to="/records/account" style={{ fontWeight: 600 }}>Manage Accounts &rarr;</Link>
                  </Space>
                </Card>
              </Col>
              <Col xs={24} md={12} lg={8}>
                <Card hoverable size="small" style={{ borderRadius: 8, height: '100%' }}>
                  <Space direction="vertical" size={10} style={{ width: '100%' }}>
                    <Space>
                      <SwapOutlined style={{ fontSize: 24, color: '#8b5cf6' }} />
                      <Text strong style={{ fontSize: 16 }}>Currencies</Text>
                    </Space>
                    <Paragraph type="secondary" style={{ fontSize: 13, margin: 0 }}>
                      Multi-currency configurations, exchange rates, and base operational currencies.
                    </Paragraph>
                    <Link to="/currencies" style={{ fontWeight: 600 }}>Configure Currencies &rarr;</Link>
                  </Space>
                </Card>
              </Col>
              <Col xs={24} md={12} lg={8}>
                <Card hoverable size="small" style={{ borderRadius: 8, height: '100%' }}>
                  <Space direction="vertical" size={10} style={{ width: '100%' }}>
                    <Space>
                      <SettingOutlined style={{ fontSize: 24, color: '#8b5cf6' }} />
                      <Text strong style={{ fontSize: 16 }}>PDF Output Settings</Text>
                    </Space>
                    <Paragraph type="secondary" style={{ fontSize: 13, margin: 0 }}>
                      Regional letterheads, billing addresses, invoice templates, and PDF formats.
                    </Paragraph>
                    <Link to="/pdf-settings" style={{ fontWeight: 600 }}>Setup Output Styles &rarr;</Link>
                  </Space>
                </Card>
              </Col>
              <Col xs={24} md={12} lg={8}>
                <Card hoverable size="small" style={{ borderRadius: 8, height: '100%' }}>
                  <Space direction="vertical" size={10} style={{ width: '100%' }}>
                    <Space>
                      <DollarOutlined style={{ fontSize: 24, color: '#8b5cf6' }} />
                      <Text strong style={{ fontSize: 16 }}>Taxes & Regional Profiles</Text>
                    </Space>
                    <Paragraph type="secondary" style={{ fontSize: 13, margin: 0 }}>
                      VAT, local sales tax profiles, tax brackets, and calculation rules.
                    </Paragraph>
                    <Link to="/taxes" style={{ fontWeight: 600 }}>Manage Taxes &rarr;</Link>
                  </Space>
                </Card>
              </Col>
              <Col xs={24} md={12} lg={8}>
                <Card hoverable size="small" style={{ borderRadius: 8, height: '100%' }}>
                  <Space direction="vertical" size={10} style={{ width: '100%' }}>
                    <Space>
                      <WalletOutlined style={{ fontSize: 24, color: '#8b5cf6' }} />
                      <Text strong style={{ fontSize: 16 }}>Payment Modes</Text>
                    </Space>
                    <Paragraph type="secondary" style={{ fontSize: 13, margin: 0 }}>
                      Bookkeeping banks, cash drawers, POS systems, and card collection providers.
                    </Paragraph>
                    <Link to="/payment/mode" style={{ fontWeight: 600 }}>Manage Payment Modes &rarr;</Link>
                  </Space>
                </Card>
              </Col>
            </Row>
          </Card>
        )}
      </Space>

      {/* Add Budget Modal */}
      <Modal
        title="Configure Budget Allocation Limit"
        open={budgetModalVisible}
        onCancel={() => { setBudgetModalVisible(false); budgetForm.resetFields(); }}
        footer={null}
        width={480}
      >
        <Form form={budgetForm} layout="vertical" onFinish={handleCreateBudget}>
          <Form.Item name="name" label="Budget Category Title" rules={[{ required: true, message: 'Please input budget name' }]}>
            <Input placeholder="Operations Allocation Q3" />
          </Form.Item>
          <Form.Item name="allocated" label="Allocation Limit Amount (NGN)" rules={[{ required: true, message: 'Please set budget limit' }]}>
            <InputNumber style={{ width: '100%' }} min={1} placeholder="500000" />
          </Form.Item>
          <Form.Item name="approvalThreshold" label="Bookkeeping Threshold Warning Limit (NGN)" rules={[{ required: true, message: 'Please set approval threshold' }]}>
            <InputNumber style={{ width: '100%' }} min={1} placeholder="50000" />
          </Form.Item>
          <Space style={{ display: 'flex', justifyContent: 'flex-end', width: '100%', marginTop: 20 }}>
            <Button onClick={() => setBudgetModalVisible(false)}>Cancel</Button>
            <Button type="primary" htmlType="submit" loading={budgetBusy}>Create Budget</Button>
          </Space>
        </Form>
      </Modal>
    </div>
  );
}
