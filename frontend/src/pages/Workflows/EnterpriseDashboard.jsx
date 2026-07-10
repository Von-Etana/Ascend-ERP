import { useEffect, useMemo, useState } from 'react';
import { Alert, Button, Card, Col, Row, Segmented, Space, Statistic, Table, Tag, Typography } from 'antd';
import { Link } from 'react-router-dom';
import dayjs from 'dayjs';
import { request } from '@/request';
import useMoney from '@/settings/useMoney';
import { DEFAULT_CURRENCY_CODE } from '@/constants/platformDefaults';

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
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');
  const { moneyFormatter } = useMoney();

  useEffect(() => {
    const load = async () => {
      setLoading(true);
      setError('');
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
      setLoading(false);
    };

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

  return (
    <div style={{ padding: '24px 32px', width: '100%' }}>
      <Space direction="vertical" size={24} style={{ width: '100%' }}>
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

        {error && <Alert type="warning" showIcon message={error} />}

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
              <Statistic title="Outstanding" value={moneyFormatter({ amount: finance.invoices.outstanding || 0, currency_code: DEFAULT_CURRENCY_CODE })} />
            </Card>
          </Col>
          <Col xs={24} sm={12} xl={6}>
            <Card loading={loading}>
              <Statistic title="Expense Total" value={moneyFormatter({ amount: summary.expenseTotal || 0, currency_code: DEFAULT_CURRENCY_CODE })} />
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
    </div>
  );
}
