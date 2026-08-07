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
const PAYMENT_STATUS_COLORS = {
  paid: 'green',
  partially: 'gold',
  pending: 'blue',
  overdue: 'red',
  draft: 'default',
};

export default function InvoiceWorkbench() {
  const [invoices, setInvoices] = useState([]);
  const [clients, setClients] = useState([]);
  const [payments, setPayments] = useState([]);
  const [paymentModes, setPaymentModes] = useState([]);
  const [open, setOpen] = useState(false);
  const [paymentOpen, setPaymentOpen] = useState(false);
  const [activeInvoice, setActiveInvoice] = useState(null);
  const [loading, setLoading] = useState(false);
  const [form] = Form.useForm();
  const [paymentForm] = Form.useForm();
  const currentAdmin = useSelector(selectCurrentAdmin);
  const { moneyFormatter } = useMoney();
  const canCreateInvoices = canAccessEntityAction(currentAdmin, 'invoice', 'create');
  const canCreatePayments = canAccessEntityAction(currentAdmin, 'payment', 'create');
  const canUpdatePayments = canAccessEntityAction(currentAdmin, 'payment', 'update');

  const load = async () => {
    setLoading(true);
    const [invoiceResponse, clientResponse, paymentResponse, paymentModeResponse] = await Promise.all([
      request.list({ entity: 'invoice', options: { page: 1, items: 100 } }),
      request.list({ entity: 'client', options: { page: 1, items: 100 } }),
      request.list({ entity: 'payment', options: { page: 1, items: 100 } }),
      request.list({ entity: 'paymentmode', options: { page: 1, items: 100 } }),
    ]);
    setInvoices(invoiceResponse?.result || []);
    setClients(clientResponse?.result || []);
    setPayments(paymentResponse?.result || []);
    setPaymentModes(paymentModeResponse?.result || []);
    setLoading(false);
  };

  useEffect(() => {
    load();
  }, []);

  const save = async () => {
    const values = await form.validateFields();
    await request.create({
      entity: 'invoice',
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
        date: new Date().toISOString(),
        expiredDate: new Date(Date.now() + 14 * 86400000).toISOString(),
      },
    });
    message.success('Invoice created');
    setOpen(false);
    form.resetFields();
    load();
  };

  const openPaymentModal = (invoice) => {
    setActiveInvoice(invoice);
    paymentForm.setFieldsValue({
      amount: Math.max((invoice.total || 0) - (invoice.discount || 0) - (invoice.credit || 0), 0),
      currency: invoice.currency || DEFAULT_CURRENCY_CODE,
    });
    setPaymentOpen(true);
  };

  const savePayment = async () => {
    const values = await paymentForm.validateFields();
    const response = await request.post({
      entity: `invoice/${activeInvoice._id}/record-payment`,
      jsonData: values,
    });
    if (response?.success) {
      message.success('Payment recorded');
      setPaymentOpen(false);
      setActiveInvoice(null);
      paymentForm.resetFields();
      load();
    }
  };

  const reconcilePayment = async (payment) => {
    const response = await request.patch({
      entity: `payment/${payment._id}/reconcile`,
      jsonData: {},
    });
    if (response?.success) {
      message.success('Payment reconciled');
      load();
    }
  };

  const paymentsByInvoice = payments.reduce((acc, payment) => {
    const invoiceId = payment.invoice?._id || payment.invoice;
    if (!invoiceId) return acc;
    acc[invoiceId] = acc[invoiceId] || [];
    acc[invoiceId].push(payment);
    return acc;
  }, {});

  return (
    <WorkflowPage
      title="Invoice Workbench"
      description="Create invoices, record payments, and reconcile finance activity without opening raw JSON records."
      permission="finance.invoice.read"
      extra={
        <Space>
          <Button icon={<ReloadOutlined />} onClick={load}>Refresh</Button>
          <Button type="primary" disabled={!canCreateInvoices} onClick={() => setOpen(true)}>New Invoice</Button>
        </Space>
      }
    >
      <Table
        rowKey="_id"
        loading={loading}
        dataSource={invoices}
        pagination={false}
        expandable={{
          expandedRowRender: (invoice) => {
            const invoicePayments = paymentsByInvoice[invoice._id] || [];
            return (
              <Table
                rowKey="_id"
                size="small"
                pagination={false}
                dataSource={invoicePayments}
                locale={{ emptyText: 'No payments recorded yet.' }}
                columns={[
                  { title: 'Number', dataIndex: 'number' },
                  {
                    title: 'Amount',
                    render: (_, payment) =>
                      moneyFormatter({
                        amount: payment.amount?.amount || payment.amount || 0,
                        currency_code: payment.currency || payment.amount?.currency || record.currency || DEFAULT_CURRENCY_CODE,
                      }),
                  },
                  { title: 'Mode', dataIndex: ['paymentMode', 'name'] },
                  { title: 'Ref', dataIndex: 'ref' },
                  { title: 'Reconciled', dataIndex: 'reconciled', render: (value) => <Tag color={value ? 'green' : 'default'}>{value ? 'yes' : 'no'}</Tag> },
                  {
                    title: 'Action',
                    render: (_, payment) => (
                      <Button
                        size="small"
                        disabled={!canUpdatePayments || payment.reconciled}
                        onClick={() => reconcilePayment(payment)}
                      >
                        Reconcile
                      </Button>
                    ),
                  },
                ]}
              />
            );
          },
        }}
        columns={[
          { title: 'Number', dataIndex: 'number' },
          { title: 'Client', dataIndex: ['client', 'name'] },
          {
            title: 'Total',
            render: (_, record) =>
              moneyFormatter({ amount: record.total || 0, currency_code: record.currency || DEFAULT_CURRENCY_CODE }),
          },
          {
            title: 'Credit',
            render: (_, record) =>
              moneyFormatter({ amount: record.credit || 0, currency_code: record.currency || DEFAULT_CURRENCY_CODE }),
          },
          {
            title: 'Outstanding',
            render: (_, record) =>
              moneyFormatter({
                amount: Math.max((record.total || 0) - (record.discount || 0) - (record.credit || 0), 0),
                currency_code: record.currency || DEFAULT_CURRENCY_CODE,
              }),
          },
          {
            title: 'Payment',
            dataIndex: 'paymentStatus',
            render: (value) => <Tag color={PAYMENT_STATUS_COLORS[value] || 'default'}>{value || 'draft'}</Tag>,
          },
          {
            title: 'Action',
            render: (_, record) => (
              <Button
                size="small"
                disabled={!canCreatePayments || Math.max((record.total || 0) - (record.discount || 0) - (record.credit || 0), 0) === 0}
                onClick={() => openPaymentModal(record)}
              >
                Record Payment
              </Button>
            ),
          },
        ]}
      />

      <Modal title="New Invoice" open={open} onCancel={() => setOpen(false)} onOk={save} okButtonProps={{ disabled: !canCreateInvoices }}>
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
          <Form.Item name="quantity" label="Quantity" initialValue={1}>
            <InputNumber min={1} style={{ width: '100%' }} />
          </Form.Item>
          <Form.Item name="price" label="Price" initialValue={0}>
            <InputNumber min={0} style={{ width: '100%' }} />
          </Form.Item>
          <Form.Item name="currency" label="Currency" initialValue={DEFAULT_CURRENCY_CODE}>
            <Select options={CURRENCY_OPTIONS} showSearch />
          </Form.Item>
        </Form>
      </Modal>

      <Modal
        title={activeInvoice ? `Record Payment for Invoice #${activeInvoice.number || activeInvoice._id}` : 'Record Payment'}
        open={paymentOpen}
        onCancel={() => {
          setPaymentOpen(false);
          setActiveInvoice(null);
        }}
        onOk={savePayment}
        okButtonProps={{ disabled: !canCreatePayments }}
      >
        <Form form={paymentForm} layout="vertical">
          <Form.Item name="amount" label="Amount" rules={[{ required: true }]}>
            <InputNumber min={0.01} style={{ width: '100%' }} />
          </Form.Item>
          <Form.Item name="paymentMode" label="Payment Mode" rules={[{ required: true }]}>
            <Select options={paymentModes.map((item) => ({ value: item._id, label: item.name }))} />
          </Form.Item>
          <Form.Item name="currency" label="Currency">
            <Select options={CURRENCY_OPTIONS} showSearch />
          </Form.Item>
          <Form.Item name="ref" label="Reference">
            <Input />
          </Form.Item>
          <Form.Item name="description" label="Description">
            <Input.TextArea rows={3} />
          </Form.Item>
        </Form>
      </Modal>
    </WorkflowPage>
  );
}
