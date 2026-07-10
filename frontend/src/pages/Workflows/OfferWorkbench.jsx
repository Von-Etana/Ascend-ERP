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

const KIND_OPTIONS = [
  { value: 'customer_offer', label: 'Customer Offer' },
  { value: 'lead_offer', label: 'Lead Offer' },
  { value: 'proforma', label: 'Proforma' },
];
const CURRENCY_OPTIONS = currencyOptions();

export default function OfferWorkbench() {
  const [offers, setOffers] = useState([]);
  const [contacts, setContacts] = useState([]);
  const [loading, setLoading] = useState(false);
  const [open, setOpen] = useState(false);
  const [form] = Form.useForm();
  const currentAdmin = useSelector(selectCurrentAdmin);
  const { moneyFormatter } = useMoney();
  const canCreateOffers = canAccessEntityAction(currentAdmin, 'offer', 'create');
  const canConvertOffers =
    canAccessEntityAction(currentAdmin, 'offer', 'update') &&
    canAccessEntityAction(currentAdmin, 'invoice', 'create');

  const load = async () => {
    setLoading(true);
    const [offerResponse, contactResponse] = await Promise.all([
      request.list({ entity: 'offer', options: { page: 1, items: 100 } }),
      request.list({ entity: 'contact', options: { page: 1, items: 100 } }),
    ]);
    setOffers(offerResponse?.result || []);
    setContacts(contactResponse?.result || []);
    setLoading(false);
  };

  useEffect(() => {
    load();
  }, []);

  const save = async () => {
    const values = await form.validateFields();
    const total = values.quantity * values.price;
    await request.create({
      entity: 'offer',
      jsonData: {
        title: values.title,
        kind: values.kind,
        contact: values.contact,
        items: [{ itemName: values.itemName, quantity: values.quantity, price: values.price, total }],
        total,
        value: { amount: total, currency: values.currency || DEFAULT_CURRENCY_CODE },
        notes: values.notes,
      },
    });
    message.success('Offer created');
    setOpen(false);
    form.resetFields();
    load();
  };

  const convertOffer = async (offer) => {
    const response = await request.post({ entity: `offer/convert/${offer._id}`, jsonData: {} });
    if (response?.success) {
      message.success('Offer converted to invoice');
      load();
    }
  };

  return (
    <WorkflowPage
      title="Offer Workbench"
      description="Manage customer offers, lead offers, and proforma drafts with invoice conversion from the same sales surface."
      permission="sales.offer.read"
      extra={
        <Space>
          <Button icon={<ReloadOutlined />} onClick={load}>
            Refresh
          </Button>
          <Button type="primary" disabled={!canCreateOffers} onClick={() => setOpen(true)}>
            New Offer
          </Button>
        </Space>
      }
    >
      <Table
        rowKey="_id"
        loading={loading}
        dataSource={offers}
        pagination={false}
        columns={[
          { title: 'Title', dataIndex: 'title' },
          { title: 'Kind', dataIndex: 'kind', render: (value) => <Tag>{value}</Tag> },
          { title: 'Contact', dataIndex: ['contact', 'name'] },
          {
            title: 'Total',
            render: (_, record) =>
              moneyFormatter({
                amount: record.value?.amount ?? record.total ?? 0,
                currency_code: record.value?.currency || record.currency || DEFAULT_CURRENCY_CODE,
              }),
          },
          { title: 'Status', dataIndex: 'status', render: (value) => <Tag>{value}</Tag> },
          {
            title: 'Action',
            render: (_, record) => (
              <Button
                size="small"
                disabled={!canConvertOffers || record.status === 'converted'}
                onClick={() => convertOffer(record)}
              >
                Convert to Invoice
              </Button>
            ),
          },
        ]}
      />

      <Modal
        title="New Offer"
        open={open}
        onCancel={() => setOpen(false)}
        onOk={save}
        okButtonProps={{ disabled: !canCreateOffers }}
      >
        <Form form={form} layout="vertical">
          <Form.Item name="title" label="Title" rules={[{ required: true }]}>
            <Input />
          </Form.Item>
          <Form.Item name="kind" label="Kind" initialValue="customer_offer" rules={[{ required: true }]}>
            <Select options={KIND_OPTIONS} />
          </Form.Item>
          <Form.Item name="contact" label="Contact">
            <Select
              showSearch
              placeholder="Select contact"
              options={contacts.map((contact) => ({ value: contact._id, label: contact.name }))}
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
          <Form.Item name="notes" label="Notes">
            <Input.TextArea rows={3} />
          </Form.Item>
        </Form>
      </Modal>
    </WorkflowPage>
  );
}
