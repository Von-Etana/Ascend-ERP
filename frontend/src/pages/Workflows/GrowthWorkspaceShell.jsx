import { Card, Col, Row, Space, Steps, Tag, Typography } from 'antd';
import { WorkflowPage } from './shared';

const { Text } = Typography;

export default function GrowthWorkspaceShell({
  title,
  description,
  permission,
  extra,
  stepItems = [],
  currentStep = 0,
  statusTags = [],
  linkedRecords = [],
  main,
  sidebar,
}) {
  return (
    <WorkflowPage
      title={title}
      description={description}
      permission={permission}
      extra={extra}
    >
      <Space direction="vertical" size={20} style={{ width: '100%' }}>
        {(statusTags.length || linkedRecords.length) && (
          <Card size="small">
            <Space direction="vertical" size={10} style={{ width: '100%' }}>
              {statusTags.length ? (
                <Space wrap>
                  {statusTags.map((tag) => (
                    <Tag key={tag.label} color={tag.color || 'blue'}>
                      {tag.label}
                    </Tag>
                  ))}
                </Space>
              ) : null}
              {linkedRecords.length ? (
                <Space wrap>
                  {linkedRecords.map((item) => (
                    <Tag key={`${item.label}-${item.value}`} color="default">
                      {item.label}: {item.value || 'Not linked'}
                    </Tag>
                  ))}
                </Space>
              ) : null}
            </Space>
          </Card>
        )}

        {stepItems.length ? (
          <Steps
            current={currentStep}
            items={stepItems.map((item) => ({
              title: item.title,
              description: item.description,
            }))}
          />
        ) : null}

        <Row gutter={[16, 16]} align="top">
          <Col xs={24} xl={16}>
            {main}
          </Col>
          <Col xs={24} xl={8}>
            <Space direction="vertical" size={16} style={{ width: '100%' }}>
              {sidebar}
              {!sidebar ? (
                <Card size="small">
                  <Text type="secondary">No sidebar details available yet.</Text>
                </Card>
              ) : null}
            </Space>
          </Col>
        </Row>
      </Space>
    </WorkflowPage>
  );
}
