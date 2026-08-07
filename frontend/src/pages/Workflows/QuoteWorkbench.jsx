import { useEffect, useState } from 'react';
import { Button, Form, Input, InputNumber, Modal, Select, Space, Table, Tag, message } from 'antd';
import { ReloadOutlined } from '@ant-design/icons';
import { useSelector } from 'react-redux';
import { request } from '@/request';
import { WorkflowPage } from './shared';
import { selectCurrentAdmin } from '@/redux/auth/selectors';
import { canAccessEntityAction } from '@/utils/permissions';
import { currencyOptions } from '@/utils/currencyList';
import useMoney from '@/settings/useMoney';
import { DEFAULT_CURRENCY_CODE } from '@/constants/platformDefaults';

const CURRENCY_OPTIONS = currencyOptions();

export default function QuoteWorkbench() {
  const [quotes, setQuotes] = useState([]);
  const [clients, setClients] = useState([]);
  const [loading, setLoading] = useState(false);
  const [open, setOpen] = useState(false);
  const [form] = Form.useForm();
  const currentAdmin = useSelector(selectCurrentAdmin);
  const { moneyFormatter } = useMoney();
  const canCreateQuotes = canAccessEntityAction(currentAdmin, 'quote', 'create');

  const load = async () => {
    setLoading(true);
    const [quoteResponse, clientResponse] = await Promise.all([
      request.list({ entity: 'quote', options: { page: 1, items: 100 } }),
      request.list({ entity: 'client', options: { page: 1, items: 100 } }),
    ]);
    setQuotes(quoteResponse?.result || []);
    setClients(clientResponse?.result || []);
    setLoading(false);
  };

  useEffect(() => {
    load();
  }, []);

  const save = async () => {
    const values = await form.validateFields();
    await request.create({
      entity: 'quote',
      jsonData: {
        ...values,
        items: [
          {
            itemName: values.itemName,
            quantity: values.quantity,
            price: values.price,
            total: values.quantity * values.price,
          },
        ],
        total: values.quantity * values.price,
        subTotal: values.quantity * values.price,
        currency: values.currency || DEFAULT_CURRENCY_CODE,
      },
    });
    message.success('Quote created');
    setOpen(false);
    form.resetFields();
    load();
  };

  return (
    <WorkflowPage
      title="Quote Workbench"
      description="Create and manage quotes without leaving the sales flow."
      permission="sales.quote.read"
      extra={
        <Space>
          <Button icon={<ReloadOutlined />} onClick={load}>Refresh</Button>
          <Button type="primary" disabled={!canCreateQuotes} onClick={() => setOpen(true)}>New Quote</Button>
        </Space>
      }
    >
      <Table
        rowKey="_id"
        loading={loading}
        dataSource={quotes}
        pagination={false}
        columns={[
          { title: 'Number', dataIndex: 'number' },
          { title: 'Client', dataIndex: ['client', 'name'] },
          {
            title: 'Total',
            render: (_, record) =>
              moneyFormatter({ amount: record.total || 0, currency_code: record.currency || DEFAULT_CURRENCY_CODE }),
          },
          { title: 'Status', dataIndex: 'status', render: (value) => <Tag>{value}</Tag> },
        ]}
      />

      <Modal title="New Quote" open={open} onCancel={() => setOpen(false)} onOk={save} okButtonProps={{ disabled: !canCreateQuotes }}>
        <Form form={form} layout="vertical">
          <Form.Item name="client" label="Client" rules={[{ required: true }]}>
            <Select
              showSearch
              placeholder="Select client"
              options={clients.map((client) => ({ value: client._id, label: client.name }))}
            />
          </Form.Item>
          <Form.Item name="itemName" label="Item" rules={[{ required: true }]}>
            <Input />
          </Form.Item>
          <Form.Item name="quantity" label="Quantity" initialValue={1} rules={[{ required: true }]}>
            <InputNumber min={1} style={{ width: '100%' }} />
          </Form.Item>
          <Form.Item name="price" label="Price" initialValue={0} rules={[{ required: true }]}>
            <InputNumber min={0} style={{ width: '100%' }} />
          </Form.Item>
          <Form.Item name="currency" label="Currency" initialValue={DEFAULT_CURRENCY_CODE}>
            <Select options={CURRENCY_OPTIONS} showSearch />
          </Form.Item>
        </Form>
      </Modal>
    </WorkflowPage>
  );
}
