import { useEffect, useMemo, useState } from 'react';
import { Button, DatePicker, Form, Input, InputNumber, Modal, Select, Space, Switch, Table, message } from 'antd';
import { ReloadOutlined } from '@ant-design/icons';
import { request } from '@/request';
import { WorkflowPage } from './shared';

const buildOptions = (field, resources) => {
  if (field.options) return field.options;
  if (!field.resourceKey) return [];
  const items = resources[field.resourceKey] || [];
  const labelKey = field.optionLabel || 'name';
  const valueKey = field.optionValue || '_id';
  return items.map((item) => ({
    value: item[valueKey],
    label: item[labelKey] || item.name || item.title || item.email || item._id,
  }));
};

const renderField = (field, resources) => {
  switch (field.type) {
    case 'select':
      return <Select options={buildOptions(field, resources)} showSearch={field.showSearch !== false} placeholder={field.placeholder} />;
    case 'number':
      return <InputNumber min={field.min} max={field.max} style={{ width: '100%' }} placeholder={field.placeholder} />;
    case 'datetime':
      return <DatePicker showTime style={{ width: '100%' }} />;
    case 'switch':
      return <Switch />;
    case 'tags':
      return <Select mode="tags" tokenSeparators={[',']} options={field.options} placeholder={field.placeholder} />;
    case 'textarea':
      return <Input.TextArea rows={field.rows || 3} placeholder={field.placeholder} />;
    default:
      return <Input placeholder={field.placeholder} />;
  }
};

export default function EntityListWorkbench({
  title,
  description,
  entity,
  permission,
  createLabel,
  resourceDefs = [],
  formFields = [],
  columns = [],
  buildPayload = (values) => values,
  afterLoad,
}) {
  const [items, setItems] = useState([]);
  const [resources, setResources] = useState({});
  const [open, setOpen] = useState(false);
  const [loading, setLoading] = useState(false);
  const [form] = Form.useForm();

  const load = async () => {
    setLoading(true);
    const [mainResponse, ...resourceResponses] = await Promise.all([
      request.list({ entity, options: { page: 1, items: 100 } }),
      ...resourceDefs.map((def) => request.list({ entity: def.entity, options: { page: 1, items: 100 } })),
    ]);

    const nextItems = mainResponse?.result || [];
    const nextResources = {};
    resourceDefs.forEach((def, index) => {
      nextResources[def.key] = resourceResponses[index]?.result || [];
    });

    setItems(afterLoad ? afterLoad(nextItems) : nextItems);
    setResources(nextResources);
    setLoading(false);
  };

  useEffect(() => {
    load();
  }, [entity]);

  const save = async () => {
    const values = await form.validateFields();
    const response = await request.create({
      entity,
      jsonData: buildPayload(values),
    });
    if (response?.success) {
      message.success(`${title} record created`);
      setOpen(false);
      form.resetFields();
      load();
    }
  };

  const resolvedColumns = useMemo(
    () => columns.map((column) => (typeof column === 'function' ? column({ resources, reload: load }) : column)),
    [columns, resources]
  );

  return (
    <WorkflowPage
      title={title}
      description={description}
      permission={permission}
      extra={
        <Space>
          <Button icon={<ReloadOutlined />} onClick={load}>
            Refresh
          </Button>
          <Button type="primary" onClick={() => setOpen(true)}>
            {createLabel}
          </Button>
        </Space>
      }
    >
      <Table rowKey="_id" loading={loading} dataSource={items} pagination={false} columns={resolvedColumns} scroll={{ x: true }} />

      <Modal title={createLabel} open={open} onCancel={() => setOpen(false)} onOk={save} width={720}>
        <Form form={form} layout="vertical">
          {formFields.map((field) => (
            <Form.Item
              key={field.name}
              name={field.name}
              label={field.label}
              rules={field.required ? [{ required: true, message: `${field.label} is required` }] : undefined}
              initialValue={field.initialValue}
              valuePropName={field.type === 'switch' ? 'checked' : 'value'}
            >
              {renderField(field, resources)}
            </Form.Item>
          ))}
        </Form>
      </Modal>
    </WorkflowPage>
  );
}
