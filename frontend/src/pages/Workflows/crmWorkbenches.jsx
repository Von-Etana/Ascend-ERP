import { Button, Select, Space, Tag } from 'antd';
import { Link } from 'react-router-dom';
import { request } from '@/request';

export const CUSTOMER_STAGE_OPTIONS = [
  { value: 'lead', label: 'Lead' },
  { value: 'customer', label: 'Customer' },
  { value: 'vendor_contact', label: 'Vendor Contact' },
  { value: 'partner', label: 'Partner' },
];

export const COMPANY_TYPE_OPTIONS = [
  { value: 'customer', label: 'Customer' },
  { value: 'lead', label: 'Lead' },
  { value: 'vendor', label: 'Vendor' },
  { value: 'partner', label: 'Partner' },
];

export const LEAD_STATUS_OPTIONS = [
  { value: 'new', label: 'New' },
  { value: 'contacted', label: 'Contacted' },
  { value: 'qualified', label: 'Qualified' },
  { value: 'disqualified', label: 'Disqualified' },
];

export const updateEntityRecord = async ({ entity, id, jsonData, onSuccess }) => {
  const response = await request.update({ entity, id, jsonData });
  if (response?.success && onSuccess) onSuccess();
  return response;
};

export const customerColumns = ({ reload }) => [
  { title: 'Name', dataIndex: 'name' },
  { title: 'Email', dataIndex: 'email' },
  { title: 'Phone', dataIndex: 'phone' },
  { title: 'Company', dataIndex: ['company', 'name'], render: (value) => value || '-' },
  { title: 'Lead Score', dataIndex: 'leadScore', render: (value) => value || 0 },
  {
    title: 'Stage',
    render: (_, record) => (
      <Select
        value={record.lifecycleStage}
        style={{ width: 150 }}
        options={CUSTOMER_STAGE_OPTIONS}
        onChange={(value) =>
          updateEntityRecord({
            entity: 'client',
            id: record._id,
            jsonData: { lifecycleStage: value },
            onSuccess: reload,
          })
        }
      />
    ),
  },
];

export const peopleColumns = () => [
  { title: 'Name', dataIndex: 'name' },
  { title: 'Email', dataIndex: 'email' },
  { title: 'Phone', dataIndex: 'phone' },
  { title: 'WhatsApp', dataIndex: 'whatsapp', render: (value) => value || '-' },
  { title: 'Company', dataIndex: ['company', 'name'], render: (value) => value || '-' },
  {
    title: 'Stage',
    dataIndex: 'lifecycleStage',
    render: (value) => <Tag>{value}</Tag>,
  },
];

export const companyColumns = ({ reload }) => [
  { title: 'Name', dataIndex: 'name' },
  { title: 'Type', dataIndex: 'type', render: (value) => <Tag>{value}</Tag> },
  { title: 'Email', dataIndex: 'email' },
  { title: 'Phone', dataIndex: 'phone' },
  { title: 'Industry', dataIndex: 'industry', render: (value) => value || '-' },
  {
    title: 'Actions',
    render: (_, record) => (
      <Select
        value={record.type}
        style={{ width: 140 }}
        options={COMPANY_TYPE_OPTIONS}
        onChange={(value) =>
          updateEntityRecord({
            entity: 'company',
            id: record._id,
            jsonData: { type: value },
            onSuccess: reload,
          })
        }
      />
    ),
  },
];

export const leadColumns = ({ reload }) => [
  { title: 'Title', dataIndex: 'title' },
  { title: 'Contact', dataIndex: ['contact', 'name'], render: (value) => value || '-' },
  { title: 'Company', dataIndex: ['company', 'name'], render: (value) => value || '-' },
  { title: 'Source', dataIndex: 'source', render: (value) => value || '-' },
  { title: 'Score', dataIndex: 'score', render: (value) => value || 0 },
  {
    title: 'Status',
    render: (_, record) => (
      <Select
        value={record.status}
        style={{ width: 160 }}
        options={LEAD_STATUS_OPTIONS}
        onChange={(value) =>
          updateEntityRecord({
            entity: 'lead',
            id: record._id,
            jsonData: { status: value },
            onSuccess: reload,
          })
        }
      />
    ),
  },
  {
    title: 'Actions',
    render: () => (
      <Space>
        <Button size="small">
          <Link to="/deals">Open Deal Pipeline</Link>
        </Button>
      </Space>
    ),
  },
];
