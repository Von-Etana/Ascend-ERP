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

const STATUS_OPTIONS = ['draft', 'confirmed', 'processing', 'delivered', 'returned', 'refunded', 'cancelled'];
const CURRENCY_OPTIONS = currencyOptions();
const STATUS_COLORS = {
  draft: 'default',
  confirmed: 'blue',
  processing: 'gold',
  delivered: 'green',
  returned: 'orange',
  refunded: 'purple',
  cancelled: 'red',
};

export default function OrderWorkbench() {
  const [orders, setOrders] = useState([]);
  const [clients, setClients] = useState([]);
  const [open, setOpen] = useState(false);
  const [loading, setLoading] = useState(false);
  const [form] = Form.useForm();
  const currentAdmin = useSelector(selectCurrentAdmin);
  const { moneyFormatter } = useMoney();
  const canCreateOrders = canAccessEntityAction(currentAdmin, 'order', 'create');
  const canUpdateOrders = canAccessEntityAction(currentAdmin, 'order', 'update');

  const load = async () => {
    setLoading(true);
    const [orderResponse, clientResponse] = await Promise.all([
      request.list({ entity: 'order', options: { page: 1, items: 100 } }),
      request.list({ entity: 'client', options: { page: 1, items: 100 } }),
    ]);
    setOrders(orderResponse?.result || []);
    setClients(clientResponse?.result || []);
    setLoading(false);
  };

  useEffect(() => {
    load();
  }, []);

  const save = async () => {
    const values = await form.validateFields();
    const total = values.quantity * values.price;
    await request.create({
      entity: 'order',
      jsonData: {
        client: values.client,
        number: values.number,
        items: [{ itemName: values.itemName, quantity: values.quantity, unitPrice: values.price, total }],
        total: { amount: total, currency: values.currency || DEFAULT_CURRENCY_CODE },
        status: 'draft',
      },
    });
    message.success('Order created');
    setOpen(false);
    form.resetFields();
    load();
  };

  const updateStatus = async (record, status) => {
    const response = await request.patch({
      entity: `order/${record._id}/status`,
      jsonData: { status },
    });
    if (response?.success) {
      message.success('Order status updated');
      load();
    }
  };

  return (
    <WorkflowPage
      title="Order Workbench"
      description="Track enterprise order lifecycle states from confirmation through fulfillment and returns."
      permission="inventory.order.read"
      extra={
        <Space>
          <Button icon={<ReloadOutlined />} onClick={load}>
            Refresh
          </Button>
          <Button type="primary" disabled={!canCreateOrders} onClick={() => setOpen(true)}>
            New Order
          </Button>
        </Space>
      }
    >
      <Table
        rowKey="_id"
        loading={loading}
        dataSource={orders}
        pagination={false}
        columns={[
          { title: 'Number', dataIndex: 'number' },
          { title: 'Client', dataIndex: ['client', 'name'] },
          {
            title: 'Total',
            render: (_, record) =>
              moneyFormatter({
                amount: record.total?.amount || record.total || 0,
                currency_code: record.total?.currency || record.currency || DEFAULT_CURRENCY_CODE,
              }),
          },
          {
            title: 'Status',
            dataIndex: 'status',
            render: (value) => <Tag color={STATUS_COLORS[value] || 'default'}>{value || 'draft'}</Tag>,
          },
          {
            title: 'Action',
            render: (_, record) => (
              <Select
                size="small"
                value={record.status}
                disabled={!canUpdateOrders}
                style={{ width: 150 }}
                onChange={(value) => updateStatus(record, value)}
                options={STATUS_OPTIONS.map((value) => ({ value, label: value }))}
              />
            ),
          },
        ]}
      />

      <Modal title="New Order" open={open} onCancel={() => setOpen(false)} onOk={save}>
        <Form form={form} layout="vertical">
          <Form.Item name="client" label="Client" rules={[{ required: true }]}>
            <Select options={clients.map((client) => ({ value: client._id, label: client.name }))} />
          </Form.Item>
          <Form.Item name="number" label="Number" rules={[{ required: true }]}>
            <Input />
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
