import { Card, Empty, Space, Typography } from 'antd';
import PermissionGuard from '@/components/PermissionGuard';

const { Title, Text } = Typography;

export function WorkflowPage({ title, description, children, extra, permission }) {
  const content = (
    <div style={{ padding: '24px 32px', width: '100%' }}>
      <Space direction="vertical" size={20} style={{ width: '100%' }}>
        <div>
          <Title level={2} style={{ marginBottom: 4 }}>
            {title}
          </Title>
          <Text type="secondary">{description}</Text>
        </div>
        <Card extra={extra}>{children || <Empty image={Empty.PRESENTED_IMAGE_SIMPLE} />}</Card>
      </Space>
    </div>
  );

  if (!permission) {
    return content;
  }

  return (
    <PermissionGuard permission={permission}>
      {content}
    </PermissionGuard>
  );
}
