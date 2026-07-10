import { useEffect, useMemo, useState } from 'react';
import { Button, Col, DatePicker, Form, Input, Modal, Row, Select, Space, Table, Tag, message } from 'antd';
import { CheckOutlined, ReloadOutlined } from '@ant-design/icons';
import { useSelector } from 'react-redux';
import { request } from '@/request';
import { WorkflowPage } from './shared';
import { selectCurrentAdmin } from '@/redux/auth/selectors';
import { canAccessEntityAction, canAccessPermission } from '@/utils/permissions';

const statusColor = {
  open: 'blue',
  won: 'green',
  lost: 'red',
};

export default function DealPipeline() {
  const [deals, setDeals] = useState([]);
  const [stages, setStages] = useState([]);
  const [loading, setLoading] = useState(false);
  const [form] = Form.useForm();
  const [selectedDeal, setSelectedDeal] = useState(null);
  const [followUpOpen, setFollowUpOpen] = useState(false);
  const [followUpSubmitting, setFollowUpSubmitting] = useState(false);
  const currentAdmin = useSelector(selectCurrentAdmin);
  const canUpdateDeals = canAccessEntityAction(currentAdmin, 'deal', 'update');
  const canRunWorkflow = canAccessPermission(currentAdmin, 'automations.workflow.create');
  const canCreateTasks = canAccessEntityAction(currentAdmin, 'task', 'create');

  const load = async () => {
    setLoading(true);
    const [dealResponse, stageResponse] = await Promise.all([
      request.list({ entity: 'deal', options: { page: 1, items: 100 } }),
      request.list({ entity: 'pipelinestage', options: { page: 1, items: 100, sortBy: 'order', sortValue: 1 } }),
    ]);
    setDeals(dealResponse?.result || []);
    setStages(stageResponse?.result || []);
    setLoading(false);
  };

  useEffect(() => {
    load();
  }, []);

  const stageOptions = useMemo(
    () => stages.map((stage) => ({ label: stage.name, value: stage._id, stage })),
    [stages]
  );

  const updateStage = async (deal, stageId) => {
    await request.patch({
      entity: `deal/${deal._id}/stage`,
      jsonData: { stageId },
    });
    message.success('Deal stage updated');
    load();
  };

  const markWon = async (deal) => {
    await request.post({
      entity: 'workflows/deal-won',
      jsonData: {
        dealId: deal._id,
        procurementNeeded: false,
        thankYouCampaignName: `${deal.name} thank-you campaign`,
      },
    });
    message.success('Won workflow completed');
    load();
  };

  const openFollowUp = (deal) => {
    setSelectedDeal(deal);
    setFollowUpOpen(true);
    form.setFieldsValue({
      title: `Follow up ${deal.name}`,
      dueAt: undefined,
      priority: 'normal',
    });
  };

  const createFollowUp = async () => {
    const values = await form.validateFields();
    setFollowUpSubmitting(true);
    await request.create({
      entity: 'task',
      jsonData: {
        title: values.title,
        priority: values.priority,
        dueAt: values.dueAt ? values.dueAt.toISOString() : undefined,
        relatedTo: { entityType: 'Deal', entityId: selectedDeal._id },
      },
    });
    message.success('Follow-up task created');
    setFollowUpSubmitting(false);
    setFollowUpOpen(false);
  };

  const columns = [
    { title: 'Deal', dataIndex: 'name' },
    { title: 'Value', dataIndex: ['value', 'amount'] },
    {
      title: 'Stage',
      dataIndex: ['stage', 'name'],
      render: (_, record) => (
        <Select
          value={record.stage?._id}
          options={stageOptions}
          style={{ width: 180 }}
          disabled={!canUpdateDeals}
          onChange={(value) => updateStage(record, value)}
        />
      ),
    },
    {
      title: 'Status',
      dataIndex: 'status',
      render: (value) => <Tag color={statusColor[value] || 'default'}>{value}</Tag>,
    },
    {
      title: 'Actions',
      render: (_, record) => (
        <Space>
          <Button icon={<CheckOutlined />} type="primary" disabled={!canRunWorkflow} onClick={() => markWon(record)}>
            Mark Won
          </Button>
          <Button disabled={!canCreateTasks} onClick={() => openFollowUp(record)}>Create Follow-up Task</Button>
        </Space>
      ),
    },
  ];

  return (
    <WorkflowPage
      title="Deal Pipeline"
      description="Advance stages, mark deals won, generate downstream orders and invoices, and create follow-up tasks."
      permission="crm.deal.read"
      extra={<Button icon={<ReloadOutlined />} onClick={load}>Refresh</Button>}
    >
      <Table
        rowKey="_id"
        columns={columns}
        dataSource={deals}
        loading={loading}
        pagination={false}
        scroll={{ x: true }}
      />

      <Modal
        title="Create Follow-up Task"
        open={followUpOpen}
        onCancel={() => setFollowUpOpen(false)}
        onOk={createFollowUp}
        confirmLoading={followUpSubmitting}
        okButtonProps={{ disabled: !canCreateTasks }}
      >
        <Form form={form} layout="vertical">
          <Form.Item name="title" label="Task title" rules={[{ required: true, message: 'Enter a title' }]}>
            <Input />
          </Form.Item>
          <Row gutter={12}>
            <Col span={12}>
              <Form.Item name="priority" label="Priority" initialValue="normal">
                <Select
                  options={[
                    { value: 'low', label: 'Low' },
                    { value: 'normal', label: 'Normal' },
                    { value: 'high', label: 'High' },
                    { value: 'urgent', label: 'Urgent' },
                  ]}
                />
              </Form.Item>
            </Col>
          <Col span={12}>
            <Form.Item name="dueAt" label="Due date">
              <DatePicker style={{ width: '100%' }} />
            </Form.Item>
          </Col>
        </Row>
      </Form>
    </Modal>
    </WorkflowPage>
  );
}
