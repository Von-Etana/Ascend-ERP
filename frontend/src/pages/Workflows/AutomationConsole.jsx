import { useEffect, useMemo, useState } from 'react';
import {
  Alert,
  Button,
  Card,
  Col,
  Drawer,
  Form,
  Input,
  InputNumber,
  List,
  Row,
  Select,
  Space,
  Statistic,
  Table,
  Tag,
  message,
} from 'antd';
import { PlayCircleOutlined, ReloadOutlined } from '@ant-design/icons';
import { Link, useLocation } from 'react-router-dom';
import { useSelector } from 'react-redux';
import { request } from '@/request';
import { selectCurrentAdmin } from '@/redux/auth/selectors';
import { canAccessPermission } from '@/utils/permissions';
import GrowthWorkspaceShell from './GrowthWorkspaceShell';
import {
  AUTOMATION_TEMPLATES,
  serializeAutomationDraft,
  summarizeAction,
  summarizeAutomationRule,
} from './growthWorkspace';

const STEP_ITEMS = [
  { title: 'Trigger', description: 'Choose what starts the workflow' },
  { title: 'Conditions', description: 'Limit when it should run' },
  { title: 'Actions', description: 'Define follow-up behavior' },
  { title: 'Review & Activate', description: 'Preview and save the rule' },
  { title: 'Operations', description: 'Monitor runs and failures' },
];

const TRIGGER_TYPES = [
  { value: 'event', label: 'Event' },
  { value: 'field_change', label: 'Field Change' },
  { value: 'time', label: 'Time Schedule' },
];

const EVENT_TYPES = [
  { value: 'crm.deal.won', label: 'CRM · Deal Won' },
  { value: 'crm.lead.updated', label: 'CRM · Lead Updated' },
  { value: 'marketing.campaign.clicked', label: 'Marketing · Campaign Clicked' },
  { value: 'finance.invoice.payment_recorded', label: 'Finance · Invoice Payment Recorded' },
];

const ACTION_TYPES = [
  { value: 'create_task', label: 'Create Task' },
  { value: 'send_email', label: 'Send Email' },
  { value: 'send_whatsapp', label: 'Send WhatsApp' },
  { value: 'generate_content', label: 'Generate Content' },
  { value: 'schedule_social_post', label: 'Schedule Social Post' },
  { value: 'create_record', label: 'Create Record' },
  { value: 'update_record', label: 'Update Record' },
];

const statusColor = {
  queued: 'blue',
  running: 'gold',
  succeeded: 'green',
  failed: 'red',
};

export default function AutomationConsole() {
  const [form] = Form.useForm();
  const [summary, setSummary] = useState(null);
  const [history, setHistory] = useState([]);
  const [preview, setPreview] = useState(null);
  const [loading, setLoading] = useState(false);
  const [running, setRunning] = useState(false);
  const [currentStep, setCurrentStep] = useState(0);
  const [selectedRun, setSelectedRun] = useState(null);
  const currentAdmin = useSelector(selectCurrentAdmin);
  const canRunJobs = canAccessPermission(currentAdmin, 'automations.runner.create');
  const location = useLocation();
  const handoff = location.state?.handoff;
  const values = Form.useWatch([], form) || {};

  const load = async () => {
    setLoading(true);
    const [summaryResponse, historyResponse] = await Promise.all([
      request.get({ entity: 'automation/workspace/summary' }),
      request.get({ entity: 'automation/run-history?limit=20' }),
    ]);
    setSummary(summaryResponse?.result || null);
    setHistory(historyResponse?.result || []);
    setLoading(false);
  };

  useEffect(() => {
    load();
  }, []);

  useEffect(() => {
    if (location.pathname.endsWith('/history')) {
      setCurrentStep(4);
    } else if (location.pathname.endsWith('/rules-jobs')) {
      setCurrentStep(3);
    } else if (!handoff) {
      setCurrentStep(0);
    }
  }, [handoff, location.pathname]);

  useEffect(() => {
    if (!handoff) return;
    form.setFieldsValue({
      name: handoff.campaign?.name ? `${handoff.campaign.name} follow-up` : 'Growth follow-up',
      description: 'Generated from a cross-module handoff.',
      triggerType: 'event',
      eventType: handoff.campaign ? 'marketing.campaign.clicked' : 'crm.deal.won',
      actions: [
        {
          type: 'generate_content',
          config: {
            type: handoff.asset?.type || 'email',
            prompt: handoff.content || handoff.asset?.content || '',
          },
        },
        {
          type: 'create_task',
          config: {
            title: handoff.campaign?.name ? `Review ${handoff.campaign.name} response` : 'Review AI handoff',
          },
        },
      ],
      conditions: [],
    });
    setCurrentStep(2);
  }, [handoff, form]);

  const applyTemplate = (template) => {
    form.setFieldsValue({
      ...template.draft,
      conditions: template.draft.conditions || [],
      actions: template.draft.actions || [],
    });
    setPreview(null);
    setCurrentStep(0);
  };

  const generatePreview = async () => {
    const draft = serializeAutomationDraft(form.getFieldsValue(true));
    const eventId = values.previewEventId || summary?.recentEvents?.[0]?._id;
    const response = await request.post({
      entity: 'automation/preview',
      jsonData: {
        rule: draft,
        eventId,
      },
    });
    setPreview(response?.result || null);
    setCurrentStep(3);
    message.success('Automation preview updated');
  };

  const saveRule = async () => {
    const draft = serializeAutomationDraft(await form.validateFields());
    const response = await request.create({
      entity: 'automationrule',
      jsonData: draft,
    });
    if (response?.success) {
      message.success('Automation rule activated');
      load();
    }
  };

  const runDue = async () => {
    setRunning(true);
    const response = await request.post({ entity: 'automation/run-due', jsonData: { limit: 10 } });
    setRunning(false);
    if (response?.success) {
      message.success(response?.message || 'Automation jobs processed');
      load();
    }
  };

  const currentRuleSummary = useMemo(() => summarizeAutomationRule(serializeAutomationDraft(values)), [values]);

  const main = (
    <Space direction="vertical" size={16} style={{ width: '100%' }}>
      <Card title="Starter Templates">
        <Space wrap>
          {AUTOMATION_TEMPLATES.map((template) => (
            <Button key={template.key} onClick={() => applyTemplate(template)}>
              {template.label}
            </Button>
          ))}
        </Space>
      </Card>

      <Card title="Automation Builder">
        <Form form={form} layout="vertical" initialValues={{ triggerType: 'event', conditions: [], actions: [{ type: 'create_task', config: {} }] }}>
          <Row gutter={12}>
            <Col xs={24} md={12}>
              <Form.Item name="name" label="Rule Name" rules={[{ required: true }]}>
                <Input />
              </Form.Item>
            </Col>
            <Col xs={24} md={12}>
              <Form.Item name="triggerType" label="Trigger Type" rules={[{ required: true }]}>
                <Select options={TRIGGER_TYPES} onChange={() => setCurrentStep(0)} />
              </Form.Item>
            </Col>
            {values.triggerType === 'time' ? (
              <Col span={24}>
                <Form.Item name="schedule" label="Schedule">
                  <Input placeholder="Every weekday at 09:00" />
                </Form.Item>
              </Col>
            ) : (
              <Col span={24}>
                <Form.Item name="eventType" label="Event Source" rules={[{ required: true }]}>
                  <Select options={EVENT_TYPES} onChange={() => setCurrentStep(0)} />
                </Form.Item>
              </Col>
            )}
            <Col span={24}>
              <Form.Item name="description" label="Rule Description">
                <Input.TextArea rows={2} />
              </Form.Item>
            </Col>
          </Row>

          <Card size="small" title="Conditions" style={{ marginBottom: 16 }}>
            <Form.List name="conditions">
              {(fields, { add, remove }) => (
                <Space direction="vertical" style={{ width: '100%' }}>
                  {fields.map((field) => (
                    <Row gutter={12} key={field.key}>
                      <Col xs={24} md={8}>
                        <Form.Item name={[field.name, 'field']} label="Field">
                          <Input placeholder="payload.amount" />
                        </Form.Item>
                      </Col>
                      <Col xs={24} md={8}>
                        <Form.Item name={[field.name, 'operator']} label="Operator">
                          <Select
                            options={['eq', 'ne', 'gt', 'gte', 'lt', 'lte', 'contains', 'in'].map((value) => ({
                              value,
                              label: value,
                            }))}
                          />
                        </Form.Item>
                      </Col>
                      <Col xs={24} md={6}>
                        <Form.Item name={[field.name, 'value']} label="Value">
                          <Input />
                        </Form.Item>
                      </Col>
                      <Col xs={24} md={2}>
                        <Button danger style={{ marginTop: 30 }} onClick={() => remove(field.name)}>
                          Remove
                        </Button>
                      </Col>
                    </Row>
                  ))}
                  <Button onClick={() => add({ operator: 'eq' })}>Add Condition</Button>
                </Space>
              )}
            </Form.List>
          </Card>

          <Card size="small" title="Actions">
            <Form.List name="actions">
              {(fields, { add, remove }) => (
                <Space direction="vertical" style={{ width: '100%' }}>
                  {fields.map((field) => (
                    <Card key={field.key} size="small">
                      <Row gutter={12}>
                        <Col xs={24} md={8}>
                          <Form.Item name={[field.name, 'type']} label="Action Type" rules={[{ required: true }]}>
                            <Select options={ACTION_TYPES} onChange={() => setCurrentStep(2)} />
                          </Form.Item>
                        </Col>
                        <Col xs={24} md={8}>
                          <Form.Item name={[field.name, 'config', 'title']} label="Title / Subject">
                            <Input placeholder="Task title or email subject" />
                          </Form.Item>
                        </Col>
                        <Col xs={24} md={8}>
                          <Form.Item name={[field.name, 'config', 'type']} label="Subtype">
                            <Input placeholder="email, caption, invoice..." />
                          </Form.Item>
                        </Col>
                        <Col xs={24} md={12}>
                          <Form.Item name={[field.name, 'config', 'model']} label="Target Model">
                            <Input placeholder="Task, Invoice, SocialPost..." />
                          </Form.Item>
                        </Col>
                        <Col xs={24} md={12}>
                          <Form.Item name={[field.name, 'config', 'provider']} label="Provider">
                            <Input placeholder="facebook, instagram, resend..." />
                          </Form.Item>
                        </Col>
                        <Col span={24}>
                          <Form.Item name={[field.name, 'config', 'prompt']} label="Prompt / Message">
                            <Input.TextArea rows={3} />
                          </Form.Item>
                        </Col>
                      </Row>
                      <Button danger onClick={() => remove(field.name)}>
                        Remove Action
                      </Button>
                    </Card>
                  ))}
                  <Button onClick={() => add({ type: 'create_task', config: {} })}>Add Action</Button>
                </Space>
              )}
            </Form.List>
          </Card>
        </Form>

        <Space style={{ marginTop: 16 }}>
          <Button onClick={generatePreview}>Preview Rule</Button>
          <Button type="primary" onClick={saveRule}>
            Activate Rule
          </Button>
        </Space>
      </Card>

      {preview ? (
        <Alert
          showIcon
          type={preview.matched ? 'success' : 'warning'}
          message={preview.matched ? 'Rule matched selected event' : 'Rule did not match selected event'}
          description={`Actions: ${(preview.actionSummaries || []).join(', ') || 'No actions'}`}
        />
      ) : null}

      <Card title="Operations">
        <Table
          rowKey="_id"
          dataSource={history}
          loading={loading}
          pagination={false}
          columns={[
            {
              title: 'Status',
              dataIndex: 'status',
              render: (value) => <Tag color={statusColor[value] || 'default'}>{value}</Tag>,
            },
            {
              title: 'Rule',
              render: (_, record) => record.rule?.name || record.rule?._id || '-',
            },
            {
              title: 'Event',
              render: (_, record) => record.event?.type || record.event?._id || '-',
            },
            {
              title: 'Actions',
              render: (_, record) => `${record.actionResults?.length || 0} action(s)`,
            },
            {
              title: 'Inspect',
              render: (_, record) => (
                <Button size="small" onClick={() => setSelectedRun(record)}>
                  Details
                </Button>
              ),
            },
          ]}
        />
      </Card>
    </Space>
  );

  const sidebar = (
    <>
      <Card title="Rule Summary" size="small">
        <List
          size="small"
          dataSource={[
            ['Trigger', currentRuleSummary.triggerSummary || 'Not defined'],
            ['Conditions', (values.conditions || []).length || 0],
            ['Actions', (currentRuleSummary.actionSummaries || []).length || 0],
          ]}
          renderItem={([label, value]) => (
            <List.Item>
              <strong>{label}:</strong> {value}
            </List.Item>
          )}
        />
      </Card>

      <Card title="Action Stack" size="small">
        <List
          size="small"
          dataSource={(serializeAutomationDraft(values).actions || []).map(summarizeAction)}
          renderItem={(item) => <List.Item>{item}</List.Item>}
          locale={{ emptyText: 'No actions yet.' }}
        />
      </Card>

      <Card title="Operations Snapshot" size="small">
        <Row gutter={[12, 12]}>
          <Col span={12}>
            <Statistic title="Queued Jobs" value={summary?.metrics?.queuedJobs || 0} />
          </Col>
          <Col span={12}>
            <Statistic title="Failed Runs" value={summary?.metrics?.failedRuns || 0} />
          </Col>
          <Col span={12}>
            <Statistic title="Running" value={summary?.metrics?.runningRuns || 0} />
          </Col>
          <Col span={12}>
            <Statistic title="Active Rules" value={summary?.metrics?.activeRules || 0} />
          </Col>
        </Row>
      </Card>

      <Card title="Recent Events for Preview" size="small">
        <Select
          allowClear
          placeholder="Choose recent event"
          value={values.previewEventId}
          onChange={(value) => form.setFieldValue('previewEventId', value)}
          options={(summary?.recentEvents || []).map((event) => ({
            value: event._id,
            label: `${event.type}${event.entity ? ` · ${event.entity}` : ''}`,
          }))}
        />
      </Card>

      <Card title="Admin / Debug" size="small">
        <Space direction="vertical" style={{ width: '100%' }}>
          <Button>
            <Link to="/records/automationrule">Rules</Link>
          </Button>
          <Button>
            <Link to="/records/automationevent">Events</Link>
          </Button>
          <Button>
            <Link to="/records/automationrun">Runs</Link>
          </Button>
          <Button>
            <Link to="/records/job">Jobs</Link>
          </Button>
        </Space>
      </Card>
    </>
  );

  return (
    <>
      <GrowthWorkspaceShell
        title="Automation Workspace"
        description="Build structured growth automations, preview them against recent events, and monitor runs from one console."
        permission="automations.runner.read"
        currentStep={currentStep}
        stepItems={STEP_ITEMS}
        statusTags={[
          { label: preview?.matched ? 'Ready for Review' : 'Draft', color: preview?.matched ? 'green' : 'blue' },
          { label: summary?.metrics?.failedRuns ? 'Needs Attention' : 'Healthy', color: summary?.metrics?.failedRuns ? 'red' : 'green' },
        ]}
        linkedRecords={[
          { label: 'Campaign', value: handoff?.campaign?.name },
          { label: 'Asset', value: handoff?.asset?.title },
        ]}
        extra={
          <Space>
            <Button icon={<ReloadOutlined />} onClick={load}>
              Refresh
            </Button>
            <Button icon={<PlayCircleOutlined />} disabled={!canRunJobs} loading={running} onClick={runDue}>
              Run Due Jobs
            </Button>
          </Space>
        }
        main={main}
        sidebar={sidebar}
      />

      <Drawer
        title={selectedRun?.rule?.name || 'Automation Run Details'}
        open={Boolean(selectedRun)}
        onClose={() => setSelectedRun(null)}
        width={520}
      >
        {selectedRun ? (
          <Space direction="vertical" size={16} style={{ width: '100%' }}>
            <Tag color={statusColor[selectedRun.status] || 'default'}>{selectedRun.status}</Tag>
            <Card size="small" title="Event">
              <div>{selectedRun.event?.type || '-'}</div>
            </Card>
            <Card size="small" title="Action Results">
              <List
                size="small"
                dataSource={selectedRun.actionResults || []}
                renderItem={(item, index) => (
                  <List.Item>
                    <Space direction="vertical" size={0}>
                      <strong>Action {index + 1}</strong>
                      <span>{item.type || item.status || JSON.stringify(item)}</span>
                    </Space>
                  </List.Item>
                )}
                locale={{ emptyText: 'No action result details stored.' }}
              />
            </Card>
            <Card size="small" title="Provider Error">
              {selectedRun.error || 'No provider error recorded.'}
            </Card>
          </Space>
        ) : null}
      </Drawer>
    </>
  );
}
