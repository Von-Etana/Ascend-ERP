import { useEffect, useState } from 'react';
import { Alert, Card, Col, Row, Space, Statistic, Table, Tag, Typography } from 'antd';
import { Link } from 'react-router-dom';
import { request } from '@/request';

const { Title, Text } = Typography;

export default function ReportsOverview() {
  const [report, setReport] = useState(null);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');

  useEffect(() => {
    const load = async () => {
      setLoading(true);
      setError('');
      const response = await request.get({ entity: 'reports/overview' });
      if (response?.success) {
        setReport(response.result);
      } else {
        setError(response?.message || 'Unable to load report overview.');
      }
      setLoading(false);
    };

    load();
  }, []);

  const finance = report?.finance || {
    invoices: { count: 0, total: 0, outstanding: 0, paidCount: 0, partialCount: 0, unpaidCount: 0 },
    payments: { count: 0, total: 0, reconciledCount: 0, unreconciledCount: 0 },
  };
  const operations = report?.operations || {
    orders: { total: 0, statusCounts: [] },
    purchases: { total: 0, statusCounts: [] },
  };
  const conversions = report?.conversions || {
    publicFormSubmissions: 0,
    publishedForms: 0,
    convertedOffers: 0,
    paymentsRecorded: 0,
    reconciledPayments: 0,
  };

  return (
    <div style={{ padding: '24px 32px', width: '100%' }}>
      <Space direction="vertical" size={24} style={{ width: '100%' }}>
        <div>
          <Title level={2} style={{ marginBottom: 4 }}>
            Reports
          </Title>
          <Text type="secondary">
            Live enterprise overview for finance, operations, and conversion activity across the unified ERP/CRM platform.
          </Text>
        </div>

        {error && <Alert type="warning" showIcon message={error} />}

        <Row gutter={[16, 16]}>
          <Col xs={24} sm={12} xl={6}>
            <Card loading={loading}>
              <Statistic title="Invoices" value={finance.invoices.count} />
            </Card>
          </Col>
          <Col xs={24} sm={12} xl={6}>
            <Card loading={loading}>
              <Statistic title="Invoice Total" value={finance.invoices.total} />
            </Card>
          </Col>
          <Col xs={24} sm={12} xl={6}>
            <Card loading={loading}>
              <Statistic title="Outstanding" value={finance.invoices.outstanding} />
            </Card>
          </Col>
          <Col xs={24} sm={12} xl={6}>
            <Card loading={loading}>
              <Statistic title="Payments" value={finance.payments.count} />
            </Card>
          </Col>
        </Row>

        <Row gutter={[16, 16]}>
          <Col xs={24} sm={12} xl={6}>
            <Card loading={loading}>
              <Statistic title="Paid Invoices" value={finance.invoices.paidCount} />
            </Card>
          </Col>
          <Col xs={24} sm={12} xl={6}>
            <Card loading={loading}>
              <Statistic title="Partial Invoices" value={finance.invoices.partialCount} />
            </Card>
          </Col>
          <Col xs={24} sm={12} xl={6}>
            <Card loading={loading}>
              <Statistic title="Unpaid Invoices" value={finance.invoices.unpaidCount} />
            </Card>
          </Col>
          <Col xs={24} sm={12} xl={6}>
            <Card loading={loading}>
              <Statistic title="Reconciled Payments" value={finance.payments.reconciledCount} />
            </Card>
          </Col>
        </Row>

        <Row gutter={[16, 16]}>
          <Col xs={24} lg={12}>
            <Card title="Order Status Rollup" extra={<Link to="/orders">Open Orders</Link>} loading={loading}>
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
          <Col xs={24} lg={12}>
            <Card title="Purchase Status Rollup" extra={<Link to="/purchases">Open Purchases</Link>} loading={loading}>
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
        </Row>

        <Row gutter={[16, 16]}>
          <Col xs={24} md={12} xl={8}>
            <Card title="Public Form Visibility" extra={<Link to="/forms">Manage Forms</Link>} loading={loading}>
              <Space direction="vertical" size={8}>
                <Text>Published Forms: {conversions.publishedForms}</Text>
                <Text>Submissions: {conversions.publicFormSubmissions}</Text>
              </Space>
            </Card>
          </Col>
          <Col xs={24} md={12} xl={8}>
            <Card title="Sales Conversion Visibility" extra={<Link to="/offers">Open Offers</Link>} loading={loading}>
              <Space direction="vertical" size={8}>
                <Text>Converted Offers: {conversions.convertedOffers}</Text>
                <Text>Payments Recorded: {conversions.paymentsRecorded}</Text>
              </Space>
            </Card>
          </Col>
          <Col xs={24} md={12} xl={8}>
            <Card title="Finance Operations" extra={<Link to="/invoice">Open Invoices</Link>} loading={loading}>
              <Space direction="vertical" size={8}>
                <Text>Payment Total: {finance.payments.total}</Text>
                <Text>Unreconciled Payments: {finance.payments.unreconciledCount}</Text>
                <Text>Reconciled Payments: {conversions.reconciledPayments}</Text>
              </Space>
            </Card>
          </Col>
        </Row>
      </Space>
    </div>
  );
}
