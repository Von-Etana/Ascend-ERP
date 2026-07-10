import { useEffect, useMemo, useState } from 'react';
import { Alert, Button, Form, Input, Modal, Select, Space, Table, Tag, Typography, message } from 'antd';
import { CopyOutlined, EyeOutlined, PlusOutlined, ReloadOutlined } from '@ant-design/icons';
import { Link } from 'react-router-dom';
import { request } from '@/request';
import { WorkflowPage } from './shared';
import { buildPublicFormUrl, parseFieldList } from './publicForms';

const { Paragraph, Text } = Typography;

const TARGET_OPTIONS = [
  { value: 'lead', label: 'Lead' },
  { value: 'contact', label: 'Contact' },
  { value: 'client', label: 'Customer' },
];

const STATUS_OPTIONS = [
  { value: 'draft', label: 'Draft' },
  { value: 'published', label: 'Published' },
  { value: 'archived', label: 'Archived' },
];

export default function PublicFormsWorkbench() {
  const [forms, setForms] = useState([]);
  const [open, setOpen] = useState(false);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');
  const [form] = Form.useForm();

  const origin = useMemo(() => window.location.origin, []);

  const load = async () => {
    setLoading(true);
    setError('');
    const response = await request.list({ entity: 'publicform', options: { page: 1, items: 100 } });
    if (response?.success) {
      setForms(response.result || []);
    } else {
      setError(response?.message || 'Unable to load public forms.');
      setForms([]);
    }
    setLoading(false);
  };

  useEffect(() => {
    load();
  }, []);

  const save = async () => {
    const values = await form.validateFields();
    const response = await request.create({
      entity: 'publicform',
      jsonData: {
        name: values.name,
        slug: values.slug,
        description: values.description,
        targetEntity: values.targetEntity,
        fields: parseFieldList(values.fields),
        autoReplyEnabled: values.autoReplyEnabled === 'yes',
        autoReplySubject: values.autoReplySubject,
        autoReplyBody: values.autoReplyBody,
        successMessage: values.successMessage,
        status: values.status,
      },
    });

    if (response?.success) {
      message.success('Public form created');
      setOpen(false);
      form.resetFields();
      load();
    }
  };

  const copyUrl = async (slug) => {
    const url = buildPublicFormUrl(origin, slug);
    await navigator.clipboard.writeText(url);
    message.success('Public form URL copied');
  };

  return (
    <WorkflowPage
      title="Public Forms"
      description="Create, publish, and test public CRM capture forms with hosted links and optional auto-replies."
      permission="platform.publicform.read"
      extra={
        <Space>
          <Button icon={<ReloadOutlined />} onClick={load}>
            Refresh
          </Button>
          <Button type="primary" icon={<PlusOutlined />} onClick={() => setOpen(true)}>
            New Public Form
          </Button>
        </Space>
      }
    >
      <Space direction="vertical" size={16} style={{ width: '100%' }}>
        {error && <Alert type="warning" showIcon message={error} />}
        <Paragraph type="secondary" style={{ marginBottom: 0 }}>
          Published forms are available without auth at their hosted URL and submit into the tenant-scoped CRM workflow.
        </Paragraph>

        <Table
          rowKey="_id"
          loading={loading}
          dataSource={forms}
          pagination={false}
          columns={[
            { title: 'Name', dataIndex: 'name' },
            { title: 'Slug', dataIndex: 'slug', render: (value) => <Text code>{value}</Text> },
            { title: 'Target', dataIndex: 'targetEntity', render: (value) => <Tag>{value}</Tag> },
            { title: 'Status', dataIndex: 'status', render: (value) => <Tag color={value === 'published' ? 'green' : 'default'}>{value}</Tag> },
            {
              title: 'Fields',
              dataIndex: 'fields',
              render: (value = []) => value.join(', ') || '-',
            },
            {
              title: 'Public URL',
              render: (_, record) =>
                record.status === 'published' ? (
                  <Space>
                    <Button size="small" icon={<EyeOutlined />}>
                      <Link to={`/forms/public/${record.slug}`}>Open</Link>
                    </Button>
                    <Button size="small" icon={<CopyOutlined />} onClick={() => copyUrl(record.slug)}>
                      Copy
                    </Button>
                  </Space>
                ) : (
                  <Text type="secondary">Publish to enable</Text>
                ),
            },
          ]}
        />
      </Space>

      <Modal title="New Public Form" open={open} onCancel={() => setOpen(false)} onOk={save} width={720}>
        <Form
          form={form}
          layout="vertical"
          initialValues={{
            targetEntity: 'lead',
            status: 'draft',
            autoReplyEnabled: 'no',
            fields: 'firstName,email,phone,message',
          }}
        >
          <Form.Item name="name" label="Name" rules={[{ required: true }]}>
            <Input />
          </Form.Item>
          <Form.Item name="slug" label="Slug" rules={[{ required: true }]}>
            <Input />
          </Form.Item>
          <Form.Item name="description" label="Description">
            <Input.TextArea rows={2} />
          </Form.Item>
          <Form.Item name="targetEntity" label="Target Entity" rules={[{ required: true }]}>
            <Select options={TARGET_OPTIONS} />
          </Form.Item>
          <Form.Item name="status" label="Status" rules={[{ required: true }]}>
            <Select options={STATUS_OPTIONS} />
          </Form.Item>
          <Form.Item name="fields" label="Fields (comma separated)" rules={[{ required: true }]}>
            <Input />
          </Form.Item>
          <Form.Item name="successMessage" label="Success Message">
            <Input />
          </Form.Item>
          <Form.Item name="autoReplyEnabled" label="Auto Reply">
            <Select
              options={[
                { value: 'no', label: 'Disabled' },
                { value: 'yes', label: 'Enabled' },
              ]}
            />
          </Form.Item>
          <Form.Item name="autoReplySubject" label="Auto Reply Subject">
            <Input />
          </Form.Item>
          <Form.Item name="autoReplyBody" label="Auto Reply Body">
            <Input.TextArea rows={3} />
          </Form.Item>
        </Form>
      </Modal>
    </WorkflowPage>
  );
}
