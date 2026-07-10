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
const STATUS_COLORS = {
  draft: 'default',
  pending_approval: 'gold',
  approved: 'blue',
  processing: 'gold',
  received: 'green',
  returned: 'orange',
  refunded: 'purple',
  cancelled: 'red',
};

export default function PurchaseOrderWorkbench() {
  const [purchaseOrders, setPurchaseOrders] = useState([]);
  const [vendors, setVendors] = useState([]);
  const [open, setOpen] = useState(false);
  const [loading, setLoading] = useState(false);
  const [form] = Form.useForm();
  const currentAdmin = useSelector(selectCurrentAdmin);
  const { moneyFormatter } = useMoney();
  const canCreatePurchaseOrders = canAccessEntityAction(currentAdmin, 'purchaseorder', 'create');
  const canUpdatePurchaseOrders = canAccessEntityAction(currentAdmin, 'purchaseorder', 'update');
  const statusOptions = ['draft', 'pending_approval', 'approved', 'processing', 'received', 'returned', 'refunded', 'cancelled'];

  const load = async () => {
    setLoading(true);
    const [poResponse, vendorResponse] = await Promise.all([
      request.list({ entity: 'purchaseorder', options: { page: 1, items: 100 } }),
      request.list({ entity: 'vendor', options: { page: 1, items: 100 } }),
    ]);
    setPurchaseOrders(poResponse?.result || []);
    setVendors(vendorResponse?.result || []);
    setLoading(false);
  };

  useEffect(() => {
    load();
  }, []);

  const save = async () => {
    const values = await form.validateFields();
    await request.create({
      entity: 'purchaseorder',
      jsonData: {
        vendor: values.vendor,
        paymentTerms: values.paymentTerms,
        number: values.number,
        items: [{ itemName: values.itemName, quantity: values.quantity, price: values.price, total: values.quantity * values.price }],
        total: { amount: values.quantity * values.price, currency: values.currency || DEFAULT_CURRENCY_CODE },
      },
    });
    message.success('Purchase order created');
    setOpen(false);
    form.resetFields();
    load();
  };

  const updateStatus = async (record, status) => {
    const response = await request.patch({
      entity: `purchaseorder/${record._id}/status`,
      jsonData: { status },
    });
    if (response?.success) {
      message.success('Purchase order status updated');
      load();
    }
  };

  return (
    <WorkflowPage
      title="Purchase Order Workbench"
      description="Handle vendor purchase orders and surface procurement status."
      permission="vendors.purchaseorder.read"
      extra={
        <Space>
          <Button icon={<ReloadOutlined />} onClick={load}>Refresh</Button>
          <Button type="primary" disabled={!canCreatePurchaseOrders} onClick={() => setOpen(true)}>New Purchase Order</Button>
        </Space>
      }
    >
      <Table
        rowKey="_id"
        loading={loading}
        dataSource={purchaseOrders}
        pagination={false}
        columns={[
          { title: 'Number', dataIndex: 'number' },
          { title: 'Vendor', dataIndex: ['vendor', 'name'] },
          { title: 'Payment Terms', dataIndex: 'paymentTerms' },
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
                disabled={!canUpdatePurchaseOrders}
                style={{ width: 170 }}
                onChange={(value) => updateStatus(record, value)}
                options={statusOptions.map((value) => ({ value, label: value }))}
              />
            ),
          },
        ]}
      />

      <Modal title="New Purchase Order" open={open} onCancel={() => setOpen(false)} onOk={save} okButtonProps={{ disabled: !canCreatePurchaseOrders }}>
        <Form form={form} layout="vertical">
          <Form.Item name="vendor" label="Vendor" rules={[{ required: true }]}>
            <Select options={vendors.map((vendor) => ({ value: vendor._id, label: vendor.name }))} />
          </Form.Item>
          <Form.Item name="number" label="Number" rules={[{ required: true }]}>
            <InputNumber min={1} style={{ width: '100%' }} />
          </Form.Item>
          <Form.Item name="itemName" label="Item" rules={[{ required: true }]}>
            <Input />
          </Form.Item>
          <Form.Item name="quantity" label="Quantity" initialValue={1}>
            <InputNumber min={1} style={{ width: '100%' }} />
          </Form.Item>
          <Form.Item name="price" label="Price" initialValue={0}>
            <InputNumber min={0} style={{ width: '100%' }} />
          </Form.Item>
          <Form.Item name="currency" label="Currency" initialValue={DEFAULT_CURRENCY_CODE}>
            <Select options={CURRENCY_OPTIONS} showSearch />
          </Form.Item>
          <Form.Item name="paymentTerms" label="Payment Terms" initialValue="Net 30">
            <Input />
          </Form.Item>
        </Form>
      </Modal>
    </WorkflowPage>
  );
}
