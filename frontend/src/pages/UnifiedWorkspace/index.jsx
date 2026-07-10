import { Button, Card, Col, Row, Space, Statistic, Table, Tag, Typography } from 'antd';
import {
  ApiOutlined,
  CalendarOutlined,
  CheckCircleOutlined,
  ExperimentOutlined,
  ThunderboltOutlined,
} from '@ant-design/icons';
import { Link } from 'react-router-dom';
import { moduleRegistry } from './entityRegistry';

const { Title, Text } = Typography;

const columns = [
  {
    title: 'Entity',
    dataIndex: 'entity',
  },
  {
    title: 'API',
    dataIndex: 'api',
    render: (api) => <Tag color="blue">{api}</Tag>,
  },
  {
    title: 'Status',
    dataIndex: 'status',
    render: (status) => <Tag color="green">{status}</Tag>,
  },
  {
    title: '',
    dataIndex: 'entity',
    render: (entity) => (
      <Button size="small">
        <Link to={`/records/${entity}`}>Open records</Link>
      </Button>
    ),
  },
];

export default function UnifiedWorkspace({ moduleKey }) {
  const config = moduleRegistry[moduleKey] || moduleRegistry.crm;
  const dataSource = config.entities.map(({ entity, label }) => ({
    key: entity,
    entity,
    label,
    api: `/api/${entity}`,
    status: 'Tenant scoped',
  }));

  return (
    <div style={{ padding: '24px 32px', width: '100%' }}>
      <Space direction="vertical" size={24} style={{ width: '100%' }}>
        <div>
          <Title level={2} style={{ marginBottom: 4 }}>
            {config.title}
          </Title>
          <Text type="secondary">{config.description}</Text>
        </div>

        <Row gutter={[16, 16]}>
          {config.stats.map(([label, value]) => (
            <Col xs={24} sm={8} key={label}>
              <Card size="small">
                <Statistic title={label} value={value} />
              </Card>
            </Col>
          ))}
        </Row>

        <Row gutter={[16, 16]}>
          <Col xs={24} lg={15}>
            <Card title="Module Data Contracts" extra={<ApiOutlined />}>
              <Table
                columns={[
                  { title: 'Entity', dataIndex: 'label' },
                  ...columns.slice(1),
                ]}
                dataSource={dataSource}
                pagination={false}
                size="middle"
              />
            </Card>
          </Col>
          <Col xs={24} lg={9}>
            <Card title="Workflow Readiness" extra={<ThunderboltOutlined />}>
              <Space direction="vertical" size={12}>
                {config.workflows.map((workflow) => (
                  <Space key={workflow} align="start">
                    <CheckCircleOutlined style={{ color: '#1677ff', marginTop: 4 }} />
                    <Text>{workflow}</Text>
                  </Space>
                ))}
              </Space>
            </Card>
          </Col>
        </Row>

        <Card title="Operator Actions">
          <Space wrap>
            <Button icon={<ThunderboltOutlined />} type="primary">
              <Link to="/records/automationrule">Create automation</Link>
            </Button>
            <Button icon={<ExperimentOutlined />}>
              <Link to="/records/contentasset">Generate AI draft</Link>
            </Button>
            <Button icon={<CalendarOutlined />}>
              <Link to="/records/task">Schedule follow-up</Link>
            </Button>
          </Space>
        </Card>
      </Space>
    </div>
  );
}
