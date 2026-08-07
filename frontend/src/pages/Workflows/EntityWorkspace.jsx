import { Button, Card, Space, Typography } from 'antd';
import { Link } from 'react-router-dom';
import GenericEntityManager from '@/pages/GenericEntityManager';

const { Paragraph } = Typography;

export default function EntityWorkspace({ entity, title, description, sample, links = [] }) {
  return (
    <div style={{ width: '100%' }}>
      <div style={{ padding: '24px 32px 0' }}>
        <Card size="small" bodyStyle={{ padding: 16 }}>
          <Space direction="vertical" size={12} style={{ width: '100%' }}>
            <Paragraph type="secondary" style={{ margin: 0 }}>
              {description}
            </Paragraph>
            {links.length > 0 && (
              <Space wrap>
                {links.map((link) => (
                  <Button key={link.to} size="small">
                    <Link to={link.to}>{link.label}</Link>
                  </Button>
                ))}
              </Space>
            )}
          </Space>
        </Card>
      </div>
      <GenericEntityManager
        entity={entity}
        title={title}
        description={description}
        sampleOverride={sample}
      />
    </div>
  );
}
