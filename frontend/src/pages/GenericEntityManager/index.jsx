import { useEffect, useMemo, useState } from 'react';
import { Alert, Button, Card, Form, Input, Modal, Space, Table, Tag, Typography } from 'antd';
import { DeleteOutlined, EditOutlined, PlusOutlined, ReloadOutlined } from '@ant-design/icons';
import { useParams } from 'react-router-dom';
import { request } from '@/request';
import { allEntityConfigs } from '@/pages/UnifiedWorkspace/entityRegistry';

const { Text, Title } = Typography;

const formatValue = (value) => {
  if (value == null) return '';
  if (typeof value === 'object') return JSON.stringify(value);
  return String(value);
};

const buildColumns = (items, onEdit, onDelete) => {
  const keys = Array.from(
    new Set(items.flatMap((item) => Object.keys(item).filter((key) => !['_id', '__v'].includes(key))))
  ).slice(0, 6);

  return [
    ...keys.map((key) => ({
      title: key,
      dataIndex: key,
      ellipsis: true,
      render: (value) =>
        key === 'status' || key === 'type' ? <Tag>{formatValue(value)}</Tag> : formatValue(value),
    })),
    {
      title: 'Actions',
      key: 'actions',
      fixed: 'right',
      render: (_, record) => (
        <Space>
          <Button size="small" icon={<EditOutlined />} onClick={() => onEdit(record)}>
            Edit
          </Button>
          <Button size="small" danger icon={<DeleteOutlined />} onClick={() => onDelete(record)}>
            Delete
          </Button>
        </Space>
      ),
    },
  ];
};

export default function GenericEntityManager({
  entity: entityProp,
  title,
  description,
  sampleOverride,
}) {
  const { entity } = useParams();
  const resolvedEntity = entityProp || entity;
  const baseConfig = allEntityConfigs[resolvedEntity] || {
    entity: resolvedEntity,
    label: resolvedEntity,
    sample: { name: '' },
  };
  const config = {
    ...baseConfig,
    sample: sampleOverride || baseConfig.sample,
    label: title || baseConfig.label,
    description,
  };
  const [items, setItems] = useState([]);
  const [pagination, setPagination] = useState({ current: 1, pageSize: 10 });
  const [loading, setLoading] = useState(false);
  const [modalOpen, setModalOpen] = useState(false);
  const [editing, setEditing] = useState(null);
  const [jsonValue, setJsonValue] = useState(JSON.stringify(config.sample, null, 2));
  const [error, setError] = useState('');

  const load = async (page = 1, pageSize = 10) => {
    setLoading(true);
    setError('');
    const response = await request.list({ entity: resolvedEntity, options: { page, items: pageSize } });
    if (response?.success) {
      setItems(response.result || []);
      setPagination({
        current: Number(response.pagination?.page || page),
        pageSize,
        total: response.pagination?.count || 0,
      });
    } else {
      setItems([]);
      setError(response?.message || 'Unable to load records.');
    }
    setLoading(false);
  };

  useEffect(() => {
    setEditing(null);
    setJsonValue(JSON.stringify(config.sample, null, 2));
    load();
  }, [resolvedEntity]);

  const columns = useMemo(
    () =>
      buildColumns(
        items.length ? items : [{ ...config.sample, status: config.sample.status || 'example' }],
        (record) => {
          setEditing(record);
          const { _id, __v, ...editable } = record;
          setJsonValue(JSON.stringify(editable, null, 2));
          setModalOpen(true);
        },
        async (record) => {
          await request.delete({ entity: resolvedEntity, id: record._id });
          load(pagination.current, pagination.pageSize);
        }
      ),
    [items, resolvedEntity, pagination.current, pagination.pageSize]
  );

  const openCreate = () => {
    setEditing(null);
    setJsonValue(JSON.stringify(config.sample, null, 2));
    setError('');
    setModalOpen(true);
  };

  const save = async () => {
    try {
      const jsonData = JSON.parse(jsonValue);
      if (editing?._id) {
        await request.update({ entity: resolvedEntity, id: editing._id, jsonData });
      } else {
        await request.create({ entity: resolvedEntity, jsonData });
      }
      setModalOpen(false);
      load(pagination.current, pagination.pageSize);
    } catch (err) {
      setError(err.message || 'JSON payload is invalid.');
    }
  };

  return (
    <div style={{ padding: '24px 32px', width: '100%' }}>
      <Space direction="vertical" size={16} style={{ width: '100%' }}>
        <div>
          <Title level={2} style={{ marginBottom: 4 }}>
            {config.label}
          </Title>
          <Text type="secondary">
            {config.description || `Generic REST manager for /api/${resolvedEntity}/list, create, update, and delete.`}
          </Text>
        </div>

        {error && <Alert type="warning" showIcon message={error} />}

        <Card
          extra={
            <Space>
              <Button icon={<ReloadOutlined />} onClick={() => load(pagination.current, pagination.pageSize)}>
                Refresh
              </Button>
              <Button type="primary" icon={<PlusOutlined />} onClick={openCreate}>
                New record
              </Button>
            </Space>
          }
        >
          <Table
            rowKey={(record) => record._id || JSON.stringify(record)}
            columns={columns}
            dataSource={items}
            loading={loading}
            pagination={pagination}
            onChange={(nextPagination) => load(nextPagination.current, nextPagination.pageSize)}
            scroll={{ x: true }}
          />
        </Card>
      </Space>

      <Modal
        title={editing ? `Edit ${config.label}` : `Create ${config.label}`}
        open={modalOpen}
        onCancel={() => setModalOpen(false)}
        onOk={save}
        width={760}
        okText={editing ? 'Save changes' : 'Create record'}
      >
        <Form layout="vertical">
          <Form.Item label="JSON payload">
            <Input.TextArea
              value={jsonValue}
              onChange={(event) => setJsonValue(event.target.value)}
              rows={14}
              style={{ fontFamily: 'monospace' }}
            />
          </Form.Item>
        </Form>
      </Modal>
    </div>
  );
}
