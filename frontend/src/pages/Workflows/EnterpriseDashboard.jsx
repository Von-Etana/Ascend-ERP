import { useEffect, useMemo, useState } from 'react';
import {
  Alert,
  Button,
  Card,
  Col,
  Row,
  Segmented,
  Space,
  Statistic,
  Table,
  Tag,
  Typography,
  Tabs,
  Progress,
  Badge,
  Modal,
  Form,
  Input,
  InputNumber,
  DatePicker,
  Select,
  message,
} from 'antd';
import { Link } from 'react-router-dom';
import dayjs from 'dayjs';
import { request } from '@/request';
import useMoney from '@/settings/useMoney';
import { DEFAULT_CURRENCY_CODE } from '@/constants/platformDefaults';
import LiveChart from '@/components/LiveChart';

const { Title, Text } = Typography;

const FILTERS = [
  { label: 'From beginning', value: 'all', days: null },
  { label: 'Yesterday', value: '1d', days: 1 },
  { label: 'Last week', value: '7d', days: 7 },
  { label: 'Last month', value: '30d', days: 30 },
  { label: 'Last year', value: '365d', days: 365 },
];

const isWithinWindow = (record, days) => {
  if (!days) return true;
  const value = record?.date || record?.created || record?.updated || record?.startDate || record?.startsAt;
  if (!value) return false;
  return dayjs(value).isAfter(dayjs().subtract(days, 'day'));
};

const countBy = (items, predicate) => items.filter(predicate).length;
const sumBy = (items, selector) => items.reduce((total, item) => total + (selector(item) || 0), 0);

export default function EnterpriseDashboard() {
  const [range, setRange] = useState('all');
  const [activeTab, setActiveTab] = useState('overview');
  const [report, setReport] = useState(null);
  const [data, setData] = useState({
    invoices: [],
    quotes: [],
    payments: [],
    customers: [],
    offers: [],
    products: [],
    expenses: [],
    suppliers: [],
    orders: [],
    purchases: [],
  });
  const [paymentModes, setPaymentModes] = useState([]);
  const [expenseCategories, setExpenseCategories] = useState([]);
  const [loading, setLoading] = useState(false);
  const [reconcileLoading, setReconcileLoading] = useState(null);
  const [recordPaymentVisible, setRecordPaymentVisible] = useState(false);
  const [logExpenseVisible, setLogExpenseVisible] = useState(false);
  const [selectedInvoice, setSelectedInvoice] = useState(null);
  const [error, setError] = useState('');
  const { moneyFormatter } = useMoney();

  const [recordPaymentForm] = Form.useForm();
  const [logExpenseForm] = Form.useForm();

  const load = async () => {
    setLoading(true);
    setError('');
    try {
      const [
        reportRes,
        invoiceRes,
        quoteRes,
        paymentRes,
        customerRes,
        offerRes,
        productRes,
        expenseRes,
        supplierRes,
        orderRes,
        purchaseRes,
        paymentModeRes,
        expenseCategoryRes,
      ] = await Promise.all([
        request.get({ entity: 'reports/overview' }),
        request.listAll({ entity: 'invoice' }),
        request.listAll({ entity: 'quote' }),
        request.listAll({ entity: 'payment' }),
        request.listAll({ entity: 'client' }),
        request.listAll({ entity: 'offer' }),
        request.listAll({ entity: 'product' }),
        request.listAll({ entity: 'expense' }),
        request.listAll({ entity: 'supplier' }),
        request.listAll({ entity: 'order' }),
        request.listAll({ entity: 'purchaseorder' }),
        request.listAll({ entity: 'paymentmode' }),
        request.listAll({ entity: 'expensecategory' }),
      ]);

      const responses = [
        reportRes,
        invoiceRes,
        quoteRes,
        paymentRes,
        customerRes,
        offerRes,
        productRes,
        expenseRes,
        supplierRes,
        orderRes,
        purchaseRes,
      ];
      const hasFailure = responses.find((response) => response && response.success === false);

      if (hasFailure) {
        setError(hasFailure.message || 'Unable to load dashboard data.');
      }

      setReport(reportRes?.result || null);
      setPaymentModes(paymentModeRes?.result || []);
      setExpenseCategories(expenseCategoryRes?.result || []);
      setData({
        invoices: invoiceRes?.result || [],
        quotes: quoteRes?.result || [],
        payments: paymentRes?.result || [],
        customers: customerRes?.result || [],
        offers: offerRes?.result || [],
        products: productRes?.result || [],
        expenses: expenseRes?.result || [],
        suppliers: supplierRes?.result || [],
        orders: orderRes?.result || [],
        purchases: purchaseRes?.result || [],
      });
    } catch (err) {
      setError(err?.message || 'Error occurred while loading dashboard.');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    load();
  }, []);

  const filtered = useMemo(() => {
    const selected = FILTERS.find((item) => item.value === range);
    const days = selected?.days;

    return Object.fromEntries(
      Object.entries(data).map(([key, items]) => [key, items.filter((item) => isWithinWindow(item, days))])
    );
  }, [data, range]);

  const summary = useMemo(
    () => ({
      customers: filtered.customers.length,
      invoices: filtered.invoices.length,
      quotes: filtered.quotes.length,
      payments: filtered.payments.length,
      products: filtered.products.length,
      suppliers: filtered.suppliers.length,
      orders: filtered.orders.length,
      purchases: filtered.purchases.length,
      expenses: filtered.expenses.length,
      paid: countBy(filtered.invoices, (invoice) => invoice.status === 'paid'),
      unpaid: countBy(filtered.invoices, (invoice) => ['pending', 'draft', 'sent'].includes(invoice.status)),
      overdue: countBy(filtered.invoices, (invoice) => invoice.status === 'overdue'),
      proforma: countBy(filtered.offers, (offer) => offer.kind === 'proforma'),
      lowStock: countBy(
        filtered.products,
        (product) => Number(product.stockQuantity || 0) <= Number(product.reorderLevel || 0)
      ),
      expenseTotal: sumBy(filtered.expenses, (expense) => expense.amount?.amount),
    }),
    [filtered]
  );

  const chartTrends = useMemo(() => {
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    const groups = {};

    filtered.invoices.forEach((inv) => {
      const d = dayjs(inv.date || inv.created);
      const label = d.isValid() ? d.format(range === '7d' || range === '1d' ? 'DD MMM' : 'MMM') : 'Unknown';
      if (!groups[label]) groups[label] = { label, revenue: 0, expenses: 0, collected: 0 };
      groups[label].revenue += Number(inv.total || 0);
    });

    filtered.expenses.forEach((exp) => {
      const d = dayjs(exp.date || exp.created);
      const label = d.isValid() ? d.format(range === '7d' || range === '1d' ? 'DD MMM' : 'MMM') : 'Unknown';
      if (!groups[label]) groups[label] = { label, revenue: 0, expenses: 0, collected: 0 };
      groups[label].expenses += Number(exp.amount?.amount || 0);
    });

    filtered.payments.forEach((p) => {
      const d = dayjs(p.date || p.created);
      const label = d.isValid() ? d.format(range === '7d' || range === '1d' ? 'DD MMM' : 'MMM') : 'Unknown';
      if (!groups[label]) groups[label] = { label, revenue: 0, expenses: 0, collected: 0 };
      groups[label].collected += Number(p.amount || 0);
    });

    const sortedKeys = Object.keys(groups).sort((a, b) => {
      const idxA = months.indexOf(a);
      const idxB = months.indexOf(b);
      if (idxA !== -1 && idxB !== -1) return idxA - idxB;
      return a.localeCompare(b);
    });

    return sortedKeys.map((k) => groups[k]);
  }, [filtered, range]);

  const finance = report?.finance || {
    invoices: { total: 0, outstanding: 0 },
    payments: { total: 0, reconciledCount: 0, unreconciledCount: 0 },
  };
  const operations = report?.operations || {
    orders: { statusCounts: [] },
    purchases: { statusCounts: [] },
  };
  const conversions = report?.conversions || {
    publicFormSubmissions: 0,
    publishedForms: 0,
    convertedOffers: 0,
    paymentsRecorded: 0,
  };

  const recentInvoices = filtered.invoices.slice(0, 5);
  const recentExpenses = filtered.expenses.slice(0, 5);
  const lowStockProducts = filtered.products
    .filter((product) => Number(product.stockQuantity || 0) <= Number(product.reorderLevel || 0))
    .slice(0, 5);
  const invoiceStatusColor = {
    paid: 'green',
    sent: 'blue',
    overdue: 'red',
    draft: 'default',
  };

  const handleReconcile = async (paymentId) => {
    setReconcileLoading(paymentId);
    try {
      const response = await request.patch({ entity: `payment/${paymentId}/reconcile` });
      if (response?.success) {
        message.success('Payment successfully reconciled!');
        load();
      } else {
        message.error(response?.message || 'Failed to reconcile payment.');
      }
    } catch (err) {
      message.error(err?.message || 'Error occurred during reconciliation.');
    } finally {
      setReconcileLoading(null);
    }
  };

  const handleRecordPaymentSubmit = async (values) => {
    try {
      const response = await request.post({
        entity: `invoice/${values.invoiceId}/record-payment`,
        jsonData: {
          amount: values.amount,
          paymentMode: values.paymentMode,
          ref: values.ref,
          description: values.description,
          date: values.date ? values.date.toISOString() : undefined,
        },
      });
      if (response?.success) {
        message.success('Invoice payment recorded successfully!');
        setRecordPaymentVisible(false);
        recordPaymentForm.resetFields();
        setSelectedInvoice(null);
        load();
      } else {
        message.error(response?.message || 'Failed to record payment.');
      }
    } catch (err) {
      message.error(err?.message || 'Error occurred while recording payment.');
    }
  };

  const handleLogExpenseSubmit = async (values) => {
    try {
      const response = await request.create({
        entity: 'expense',
        jsonData: {
          title: values.title,
          amount: { amount: values.amount, currency: DEFAULT_CURRENCY_CODE },
          date: values.date ? values.date.toISOString() : undefined,
          status: 'paid',
          account: values.account,
        },
      });
      if (response?.success) {
        message.success('Operating expense logged successfully!');
        setLogExpenseVisible(false);
        logExpenseForm.resetFields();
        load();
      } else {
        message.error(response?.message || 'Failed to log expense.');
      }
    } catch (err) {
      message.error(err?.message || 'Error occurred while logging expense.');
    }
  };

  const netCashFlowVal = sumBy(filtered.payments, (p) => p.amount) - summary.expenseTotal;

  return (
    <div style={{ padding: '24px 32px', width: '100%' }}>
      <Space direction="vertical" size={24} style={{ width: '100%' }}>
        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', flexWrap: 'wrap', gap: '16px' }}>
          <div>
            <Title level={2} style={{ marginBottom: 4 }}>
              Enterprise Dashboard
            </Title>
            <Text type="secondary">
              Live customer, finance, inventory, procurement, and conversion visibility across the unified ERP/CRM workspace.
            </Text>
          </div>
          <Segmented
            options={FILTERS.map(({ label, value }) => ({ label, value }))}
            value={range}
            onChange={setRange}
          />
        </div>

        <Tabs
          activeKey={activeTab}
          onChange={setActiveTab}
          style={{ marginBottom: 12 }}
          items={[
            { key: 'overview', label: 'General Overview' },
            { key: 'finance', label: 'Financial Console (Accountant View)' },
          ]}
        />

        {error && <Alert type="warning" showIcon message={error} />}

        {activeTab === 'overview' ? (
          <Space direction="vertical" size={24} style={{ width: '100%' }}>
            <Row gutter={[16, 16]}>
              <Col xs={24} sm={12} xl={6}>
                <Card loading={loading}>
                  <Statistic title="Customers" value={summary.customers} />
                </Card>
              </Col>
              <Col xs={24} sm={12} xl={6}>
                <Card loading={loading}>
                  <Statistic title="Invoices" value={summary.invoices} />
                </Card>
              </Col>
              <Col xs={24} sm={12} xl={6}>
                <Card loading={loading}>
                  <Statistic title="Products" value={summary.products} />
                </Card>
              </Col>
              <Col xs={24} sm={12} xl={6}>
                <Card loading={loading}>
                  <Statistic title="Expenses" value={summary.expenses} />
                </Card>
              </Col>
            </Row>

            <Row gutter={[16, 16]}>
              <Col xs={24} sm={12} xl={6}>
                <Card loading={loading}>
                  <Statistic title="Low Stock Items" value={summary.lowStock} />
                </Card>
              </Col>
              <Col xs={24} sm={12} xl={6}>
                <Card loading={loading}>
                  <Statistic title="Suppliers" value={summary.suppliers} />
                </Card>
              </Col>
              <Col xs={24} sm={12} xl={6}>
                <Card loading={loading}>
                  <Statistic title="Orders" value={summary.orders} />
                </Card>
              </Col>
              <Col xs={24} sm={12} xl={6}>
                <Card loading={loading}>
                  <Statistic title="Purchases" value={summary.purchases} />
                </Card>
              </Col>
            </Row>

            <Row gutter={[16, 16]}>
              <Col xs={24} sm={12} xl={6}>
                <Card loading={loading}>
                  <Statistic title="Paid Invoices" value={summary.paid} />
                </Card>
              </Col>
              <Col xs={24} sm={12} xl={6}>
                <Card loading={loading}>
                  <Statistic
                    title="Outstanding"
                    value={finance.invoices.outstanding || 0}
                    formatter={(val) => moneyFormatter({ amount: val, currency_code: DEFAULT_CURRENCY_CODE })}
                  />
                </Card>
              </Col>
              <Col xs={24} sm={12} xl={6}>
                <Card loading={loading}>
                  <Statistic
                    title="Expense Total"
                    value={summary.expenseTotal || 0}
                    formatter={(val) => moneyFormatter({ amount: val, currency_code: DEFAULT_CURRENCY_CODE })}
                  />
                </Card>
              </Col>
              <Col xs={24} sm={12} xl={6}>
                <Card loading={loading}>
                  <Statistic title="Proforma Offers" value={summary.proforma} />
                </Card>
              </Col>
            </Row>

            <Card
              title="Quick Actions"
              extra={
                <Space wrap>
                  <Button type="primary">
                    <Link to="/quote">Open Quote Workbench</Link>
                  </Button>
                  <Button>
                    <Link to="/invoice">Open Invoice Workbench</Link>
                  </Button>
                  <Button>
                    <Link to="/products">Manage Products</Link>
                  </Button>
                  <Button>
                    <Link to="/expenses">Open Expenses</Link>
                  </Button>
                </Space>
              }
            />

            <Row gutter={[16, 16]}>
              <Col xs={24} lg={8}>
                <Card title="Recent Invoices" loading={loading} extra={<Link to="/invoice">Open Invoices</Link>}>
                  <Table
                    rowKey="_id"
                    pagination={false}
                    dataSource={recentInvoices}
                    size="small"
                    columns={[
                      { title: 'Number', dataIndex: 'number', render: (value) => value || '-' },
                      {
                        title: 'Status',
                        dataIndex: 'status',
                        render: (value) => <Tag color={invoiceStatusColor[value] || 'default'}>{value || 'draft'}</Tag>,
                      },
                      {
                        title: 'Total',
                        render: (_, record) =>
                          moneyFormatter({
                            amount: record.total || record.amount || 0,
                            currency_code: record.currency || DEFAULT_CURRENCY_CODE,
                          }),
                      },
                    ]}
                    locale={{ emptyText: 'No invoice activity in this range.' }}
                  />
                </Card>
              </Col>
              <Col xs={24} lg={8}>
                <Card title="Recent Expenses" loading={loading} extra={<Link to="/expenses">Open Expenses</Link>}>
                  <Table
                    rowKey="_id"
                    pagination={false}
                    dataSource={recentExpenses}
                    size="small"
                    columns={[
                      { title: 'Title', dataIndex: 'title' },
                      {
                        title: 'Status',
                        dataIndex: 'status',
                        render: (value) => <Tag color={value === 'paid' ? 'green' : value === 'approved' ? 'blue' : 'gold'}>{value || 'draft'}</Tag>,
                      },
                      {
                        title: 'Amount',
                        render: (_, record) =>
                          moneyFormatter({
                            amount: record.amount?.amount || 0,
                            currency_code: record.amount?.currency || DEFAULT_CURRENCY_CODE,
                          }),
                      },
                    ]}
                    locale={{ emptyText: 'No expense activity in this range.' }}
                  />
                </Card>
              </Col>
              <Col xs={24} lg={8}>
                <Card title="Low Stock Watchlist" loading={loading} extra={<Link to="/products">Open Products</Link>}>
                  <Table
                    rowKey="_id"
                    pagination={false}
                    dataSource={lowStockProducts}
                    size="small"
                    columns={[
                      { title: 'Product', dataIndex: 'name' },
                      { title: 'Stock', dataIndex: 'stockQuantity', render: (value) => value ?? 0 },
                      { title: 'Reorder', dataIndex: 'reorderLevel', render: (value) => value ?? 0 },
                    ]}
                    locale={{ emptyText: 'No low-stock products in this range.' }}
                  />
                </Card>
              </Col>
            </Row>

            <Row gutter={[16, 16]}>
              <Col xs={24} lg={8}>
                <Card title="Order Status Rollup" loading={loading} extra={<Link to="/orders">Open Orders</Link>}>
                  {operations.orders.statusCounts.length > 0 && (
                    <div style={{ marginBottom: 16 }}>
                      <LiveChart
                        type="bar"
                        data={operations.orders.statusCounts.map(item => ({ label: item.status, count: item.count }))}
                        series={[{ key: 'count', label: 'Orders', color: '#1677ff' }]}
                        height={120}
                      />
                    </div>
                  )}
                  <Table
                    rowKey="status"
                    size="small"
                    pagination={false}
                    dataSource={operations.orders.statusCounts}
                    columns={[
                      { title: 'Status', dataIndex: 'status', render: (value) => <Tag>{value}</Tag> },
                      { title: 'Count', dataIndex: 'count' },
                    ]}
                    locale={{ emptyText: 'No order activity yet.' }}
                  />
                </Card>
              </Col>
              <Col xs={24} lg={8}>
                <Card title="Purchase Status Rollup" loading={loading} extra={<Link to="/purchases">Open Purchases</Link>}>
                  {operations.purchases.statusCounts.length > 0 && (
                    <div style={{ marginBottom: 16 }}>
                      <LiveChart
                        type="bar"
                        data={operations.purchases.statusCounts.map(item => ({ label: item.status, count: item.count }))}
                        series={[{ key: 'count', label: 'Purchases', color: '#722ed1' }]}
                        height={120}
                      />
                    </div>
                  )}
                  <Table
                    rowKey="status"
                    size="small"
                    pagination={false}
                    dataSource={operations.purchases.statusCounts}
                    columns={[
                      { title: 'Status', dataIndex: 'status', render: (value) => <Tag>{value}</Tag> },
                      { title: 'Count', dataIndex: 'count' },
                    ]}
                    locale={{ emptyText: 'No purchase activity yet.' }}
                  />
                </Card>
              </Col>
              <Col xs={24} lg={8}>
                <Card title="Platform Conversion" loading={loading} extra={<Link to="/reports">Open Reports</Link>}>
                  <Space direction="vertical" size={10}>
                    <Text>Published Forms: {conversions.publishedForms}</Text>
                    <Text>Form Submissions: {conversions.publicFormSubmissions}</Text>
                    <Text>Converted Offers: {conversions.convertedOffers}</Text>
                    <Text>Payments Recorded: {conversions.paymentsRecorded}</Text>
                    <Text>Unreconciled Payments: {finance.payments.unreconciledCount}</Text>
                  </Space>
                </Card>
              </Col>
            </Row>
          </Space>
        ) : (
          <Space direction="vertical" size={24} style={{ width: '100%' }}>
            {/* Row 1: Financial Health Cards */}
            <Row gutter={[16, 16]}>
              <Col xs={24} sm={12} xl={6}>
                <Card loading={loading} bodyStyle={{ padding: '20px 24px' }}>
                  <Statistic
                    title="Invoiced Revenue"
                    value={sumBy(filtered.invoices, (inv) => inv.total)}
                    formatter={(val) => moneyFormatter({ amount: val, currency_code: DEFAULT_CURRENCY_CODE })}
                    valueStyle={{ color: '#1677ff' }}
                  />
                </Card>
              </Col>
              <Col xs={24} sm={12} xl={6}>
                <Card loading={loading} bodyStyle={{ padding: '20px 24px' }}>
                  <Statistic
                    title="Cash Collected"
                    value={sumBy(filtered.payments, (p) => p.amount)}
                    formatter={(val) => moneyFormatter({ amount: val, currency_code: DEFAULT_CURRENCY_CODE })}
                    valueStyle={{ color: '#52c41a' }}
                  />
                </Card>
              </Col>
              <Col xs={24} sm={12} xl={6}>
                <Card loading={loading} bodyStyle={{ padding: '20px 24px' }}>
                  <Statistic
                    title="Operating Expenses"
                    value={summary.expenseTotal}
                    formatter={(val) => moneyFormatter({ amount: val, currency_code: DEFAULT_CURRENCY_CODE })}
                    valueStyle={{ color: '#faad14' }}
                  />
                </Card>
              </Col>
              <Col xs={24} sm={12} xl={6}>
                <Card loading={loading} bodyStyle={{ padding: '20px 24px' }}>
                  <Statistic
                    title="Net Cash Flow"
                    value={netCashFlowVal}
                    formatter={(val) => moneyFormatter({ amount: val, currency_code: DEFAULT_CURRENCY_CODE })}
                    valueStyle={{
                      color: netCashFlowVal >= 0 ? '#52c41a' : '#ff4d4f',
                    }}
                  />
                </Card>
              </Col>
            </Row>

            {/* Live Performance Trend Chart */}
            <Row gutter={[16, 16]}>
              <Col span={24}>
                <Card title="Financial Performance Trend" loading={loading}>
                  <LiveChart
                    type="line"
                    data={chartTrends}
                    series={[
                      { key: 'revenue', label: 'Invoiced Revenue', color: '#1677ff' },
                      { key: 'collected', label: 'Cash Collected', color: '#52c41a' },
                      { key: 'expenses', label: 'Operating Expenses', color: '#faad14' },
                    ]}
                    height={280}
                    currencySymbol="NGN "
                  />
                </Card>
              </Col>
            </Row>

            {/* Quick Action & Reconciliation Status */}
            <Row gutter={[16, 16]}>
              {/* Reconciliation Progress */}
              <Col xs={24} lg={12}>
                <Card title="Payment Reconciliation Status" loading={loading}>
                  <Row align="middle" justify="space-around">
                    <Col span={10} style={{ textAlign: 'center' }}>
                      <Progress
                        type="dashboard"
                        percent={Math.round(
                          (finance.payments.reconciledCount / (finance.payments.count || 1)) * 100
                        )}
                        strokeColor={{ '0%': '#108ee9', '100%': '#87d068' }}
                      />
                      <div style={{ marginTop: 8 }}>
                        <Text strong>Reconciliation Rate</Text>
                      </div>
                    </Col>
                    <Col span={14}>
                      <Space direction="vertical" size={10} style={{ width: '100%' }}>
                        <div>
                          <Badge status="success" /> Reconciled Transactions: <Text strong>{finance.payments.reconciledCount}</Text>
                        </div>
                        <div>
                          <Badge status="processing" /> Unreconciled Queue: <Text strong>{finance.payments.unreconciledCount}</Text>
                        </div>
                        <div>
                          <Badge status="warning" /> Total Transaction Count: <Text strong>{finance.payments.count}</Text>
                        </div>
                      </Space>
                    </Col>
                  </Row>
                </Card>
              </Col>

              {/* Accountant Quick Actions */}
              <Col xs={24} lg={12}>
                <Card title="Accountant Actions Control" bodyStyle={{ height: 165, display: 'flex', flexDirection: 'column', justify: 'center' }}>
                  <Space direction="vertical" size={16} style={{ width: '100%' }}>
                    <Text type="secondary">
                      Execute essential bookkeeping actions directly from this dashboard:
                    </Text>
                    <Space wrap size={12}>
                      <Button type="primary" size="large" onClick={() => setLogExpenseVisible(true)}>
                        Log Expense
                      </Button>
                      <Button type="primary" ghost size="large" onClick={() => setRecordPaymentVisible(true)}>
                        Record Payment
                      </Button>
                    </Space>
                  </Space>
                </Card>
              </Col>
            </Row>

            <Row gutter={[16, 16]}>
              {/* Unreconciled Queue List */}
              <Col xs={24} lg={12}>
                <Card title="Unreconciled Payments Queue" loading={loading}>
                  <Table
                    rowKey="_id"
                    pagination={{ pageSize: 5 }}
                    dataSource={filtered.payments.filter((p) => !p.reconciled)}
                    size="small"
                    columns={[
                      { title: 'Payment #', dataIndex: 'number' },
                      {
                        title: 'Amount',
                        dataIndex: 'amount',
                        render: (val) => moneyFormatter({ amount: val, currency_code: DEFAULT_CURRENCY_CODE }),
                      },
                      {
                        title: 'Date',
                        dataIndex: 'date',
                        render: (val) => dayjs(val).format('YYYY-MM-DD'),
                      },
                      {
                        title: 'Action',
                        render: (_, record) => (
                          <Button
                            type="primary"
                            size="small"
                            onClick={() => handleReconcile(record._id)}
                            loading={reconcileLoading === record._id}
                          >
                            Reconcile
                          </Button>
                        ),
                      },
                    ]}
                    locale={{ emptyText: 'All payments are fully reconciled!' }}
                  />
                </Card>
              </Col>

              {/* Aging Receivables & Overdue Invoices */}
              <Col xs={24} lg={12}>
                <Card title="Aging Receivables (AR)" loading={loading}>
                  <Table
                    rowKey="_id"
                    pagination={{ pageSize: 5 }}
                    dataSource={filtered.invoices.filter((inv) => inv.status !== 'paid')}
                    size="small"
                    columns={[
                      { title: 'Invoice #', dataIndex: 'number', render: (val) => `INV-${val}` },
                      {
                        title: 'Outstanding Balance',
                        render: (_, record) => {
                          const bal = record.total - (record.credit || 0);
                          return moneyFormatter({ amount: bal, currency_code: record.currency || DEFAULT_CURRENCY_CODE });
                        },
                      },
                      {
                        title: 'Status',
                        dataIndex: 'status',
                        render: (val) => (
                          <Tag color={val === 'overdue' ? 'red' : val === 'sent' ? 'blue' : 'orange'}>
                            {val}
                          </Tag>
                        ),
                      },
                      {
                        title: 'Action',
                        render: (_, record) => (
                          <Button
                            size="small"
                            type="primary"
                            ghost
                            onClick={() => {
                              setSelectedInvoice(record);
                              recordPaymentForm.setFieldsValue({
                                invoiceId: record._id,
                                amount: record.total - (record.credit || 0),
                              });
                              setRecordPaymentVisible(true);
                            }}
                          >
                            Record Payment
                          </Button>
                        ),
                      },
                    ]}
                    locale={{ emptyText: 'No outstanding receivables!' }}
                  />
                </Card>
              </Col>
            </Row>
          </Space>
        )}
      </Space>

      {/* Record Payment Modal */}
      <Modal
        title="Record Invoice Payment"
        visible={recordPaymentVisible}
        onCancel={() => {
          setRecordPaymentVisible(false);
          recordPaymentForm.resetFields();
          setSelectedInvoice(null);
        }}
        onOk={() => recordPaymentForm.submit()}
        destroyOnClose
      >
        <Form form={recordPaymentForm} layout="vertical" onFinish={handleRecordPaymentSubmit}>
          <Form.Item
            name="invoiceId"
            label="Select Outstanding Invoice"
            rules={[{ required: true, message: 'Please select an invoice' }]}
          >
            <Select
              placeholder="Choose invoice"
              onChange={(val) => {
                const inv = filtered.invoices.find((i) => i._id === val);
                if (inv) {
                  recordPaymentForm.setFieldsValue({ amount: inv.total - (inv.credit || 0) });
                }
              }}
            >
              {filtered.invoices
                .filter((inv) => inv.status !== 'paid')
                .map((inv) => (
                  <Select.Option key={inv._id} value={inv._id}>
                    INV-{inv.number} - (Outstanding:{' '}
                    {moneyFormatter({ amount: inv.total - (inv.credit || 0), currency_code: inv.currency || DEFAULT_CURRENCY_CODE })})
                  </Select.Option>
                ))}
            </Select>
          </Form.Item>

          <Form.Item
            name="amount"
            label="Payment Amount"
            rules={[{ required: true, message: 'Please enter payment amount' }]}
          >
            <InputNumber style={{ width: '100%' }} min={1} />
          </Form.Item>

          <Form.Item
            name="paymentMode"
            label="Payment Mode"
            rules={[{ required: true, message: 'Please select payment mode' }]}
          >
            <Select placeholder="Choose mode">
              {paymentModes.map((mode) => (
                <Select.Option key={mode._id} value={mode._id || mode.name}>
                  {mode.name}
                </Select.Option>
              ))}
              {paymentModes.length === 0 && (
                <>
                  <Select.Option value="Bank Transfer">Bank Transfer</Select.Option>
                  <Select.Option value="Card">Card</Select.Option>
                  <Select.Option value="Cash">Cash</Select.Option>
                </>
              )}
            </Select>
          </Form.Item>

          <Form.Item name="ref" label="Reference / Transaction ID">
            <Input placeholder="Txn ID, Check number, etc." />
          </Form.Item>

          <Form.Item name="description" label="Notes">
            <Input.TextArea placeholder="Enter notes..." />
          </Form.Item>

          <Form.Item name="date" label="Payment Date">
            <DatePicker style={{ width: '100%' }} />
          </Form.Item>
        </Form>
      </Modal>

      {/* Log Expense Modal */}
      <Modal
        title="Log Operating Expense"
        visible={logExpenseVisible}
        onCancel={() => {
          setLogExpenseVisible(false);
          logExpenseForm.resetFields();
        }}
        onOk={() => logExpenseForm.submit()}
        destroyOnClose
      >
        <Form form={logExpenseForm} layout="vertical" onFinish={handleLogExpenseSubmit}>
          <Form.Item
            name="title"
            label="Expense Title"
            rules={[{ required: true, message: 'Please enter expense description' }]}
          >
            <Input placeholder="Meta Ads, Office rent, etc." />
          </Form.Item>

          <Form.Item
            name="amount"
            label="Expense Amount"
            rules={[{ required: true, message: 'Please enter expense amount' }]}
          >
            <InputNumber style={{ width: '100%' }} min={1} />
          </Form.Item>

          <Form.Item
            name="account"
            label="Expense Category"
            rules={[{ required: true, message: 'Please select category' }]}
          >
            <Select placeholder="Choose category">
              {expenseCategories.map((cat) => (
                <Select.Option key={cat._id} value={cat._id}>
                  {cat.name}
                </Select.Option>
              ))}
              {expenseCategories.length === 0 && (
                <>
                  <Select.Option value="Marketing Expense">Marketing Expense</Select.Option>
                  <Select.Option value="Office Operations">Office Operations</Select.Option>
                  <Select.Option value="Staff Payroll">Staff Payroll</Select.Option>
                </>
              )}
            </Select>
          </Form.Item>

          <Form.Item name="date" label="Expense Date">
            <DatePicker style={{ width: '100%' }} />
          </Form.Item>
        </Form>
      </Modal>
    </div>
  );
}
