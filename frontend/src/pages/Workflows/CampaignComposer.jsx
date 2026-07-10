import { useEffect, useMemo, useState } from 'react';
import {
  Button,
  Card,
  Col,
  DatePicker,
  Form,
  Input,
  List,
  Row,
  Select,
  Space,
  Statistic,
  Table,
  Tag,
  message,
} from 'antd';
import { ReloadOutlined } from '@ant-design/icons';
import { Link, useLocation, useNavigate } from 'react-router-dom';
import { useSelector } from 'react-redux';
import { request } from '@/request';
import { selectCurrentAdmin } from '@/redux/auth/selectors';
import { canAccessEntityAction, canAccessPermission } from '@/utils/permissions';
import GrowthWorkspaceShell from './GrowthWorkspaceShell';
import {
  CHANNEL_OPTIONS,
  CONTENT_TYPE_OPTIONS,
  buildCampaignChecklist,
  buildCampaignLaunchPayload,
  buildAudienceSummary,
  createHandoffState,
} from './growthWorkspace';

const STEP_ITEMS = [
  { title: 'Goal & Channel', description: 'Set purpose and delivery path' },
  { title: 'Audience', description: 'Pick segment and review fit' },
  { title: 'Content', description: 'Draft, import, and edit content' },
  { title: 'Schedule', description: 'Choose when and where to launch' },
  { title: 'Review & Launch', description: 'Validate readiness and launch' },
  { title: 'Performance', description: 'Monitor results and next actions' },
];

export default function CampaignComposer() {
  const [form] = Form.useForm();
  const [currentStep, setCurrentStep] = useState(0);
  const [summary, setSummary] = useState(null);
  const [segments, setSegments] = useState([]);
  const [assets, setAssets] = useState([]);
  const [draftContent, setDraftContent] = useState('');
  const [selectedAssetId, setSelectedAssetId] = useState(null);
  const [loading, setLoading] = useState(false);
  const currentAdmin = useSelector(selectCurrentAdmin);
  const canDraft = canAccessPermission(currentAdmin, 'ai.studio.create');
  const canSchedule = canAccessEntityAction(currentAdmin, 'campaign', 'create');
  const location = useLocation();
  const navigate = useNavigate();
  const handoff = location.state?.handoff;
  const values = Form.useWatch([], form) || {};

  const load = async () => {
    setLoading(true);
    const [workspaceResponse, segmentResponse, assetResponse] = await Promise.all([
      request.get({ entity: 'marketing/workspace/summary' }),
      request.list({ entity: 'audiencesegment', options: { page: 1, items: 100 } }),
      request.get({ entity: 'ai/workspace/assets?limit=20' }),
    ]);
    setSummary(workspaceResponse?.result || null);
    setSegments(segmentResponse?.result || []);
    setAssets(assetResponse?.result || []);
    setLoading(false);
  };

  useEffect(() => {
    load();
  }, []);

  useEffect(() => {
    if (!handoff) return;
    form.setFieldsValue({
      name: handoff.campaign?.name || 'AI Studio Campaign',
      brandName: handoff.asset?.brandContext?.brandName || handoff.campaign?.name || '',
      channel: handoff.campaign?.channel || 'email',
      audienceSegment: handoff.segment?._id,
      assetType: handoff.asset?.type || 'email',
      prompt: handoff.content || handoff.asset?.content || '',
      body: handoff.content || handoff.asset?.content || '',
    });
    setDraftContent(handoff.content || handoff.asset?.content || '');
    setSelectedAssetId(handoff.asset?._id || null);
    setCurrentStep(2);
  }, [handoff, form]);

  const selectedSegment = useMemo(
    () => segments.find((segment) => segment._id === values.audienceSegment) || summary?.segments?.find((segment) => segment.id === values.audienceSegment),
    [segments, summary, values.audienceSegment]
  );
  const selectedAsset = useMemo(
    () => assets.find((asset) => asset._id === selectedAssetId),
    [assets, selectedAssetId]
  );

  const checklist = useMemo(
    () => buildCampaignChecklist(values, { draftContent }),
    [values, draftContent]
  );

  const draftWithAI = async () => {
    const promptFields = await form.validateFields(['brandName', 'prompt', 'channel', 'assetType']);
    const response = await request.post({
      entity: 'ai/campaign/draft',
      jsonData: {
        type: promptFields.assetType || 'newsletter',
        prompt: promptFields.prompt,
        brandContext: {
          brandName: promptFields.brandName,
          audience: selectedSegment?.name || values.audience,
          channel: promptFields.channel,
          campaignId: handoff?.campaign?._id,
        },
      },
    });
    const content =
      response?.result?.asset?.content ||
      response?.result?.provider?.content ||
      JSON.stringify(response?.result, null, 2);
    setDraftContent(content);
    setSelectedAssetId(response?.result?.asset?._id || null);
    form.setFieldsValue({
      body: content,
      caption: content,
    });
    setCurrentStep(2);
    load();
    message.success('Campaign draft generated');
  };

  const launchCampaign = async () => {
    const draft = await form.validateFields(['name', 'channel', 'audienceSegment', 'assetType']);
    const payload = buildCampaignLaunchPayload(
      {
        ...values,
        ...draft,
        scheduledAt: values.scheduledAt,
      },
      {
        draftContent,
        mediaUrl: selectedAsset?.mediaUrl,
      }
    );
    const response = await request.post({ entity: 'campaign/launch', jsonData: payload });
    if (response?.success) {
      message.success('Campaign launched');
      setCurrentStep(5);
      load();
    }
  };

  const handoffToAutomations = () => {
    navigate('/automations', {
      state: {
        handoff: createHandoffState({
          source: 'marketing',
          campaign: { _id: values.linkedCampaignId, name: values.name, channel: values.channel },
          asset: selectedAsset,
          segment: selectedSegment,
          content: draftContent,
        }),
      },
    });
  };

  const handoffToAi = () => {
    navigate('/ai-studio', {
      state: {
        handoff: createHandoffState({
          source: 'marketing',
          campaign: { _id: values.linkedCampaignId, name: values.name, channel: values.channel },
          asset: selectedAsset,
          segment: selectedSegment,
          content: draftContent,
        }),
      },
    });
  };

  const metrics = summary?.metrics || {
    totalCampaigns: 0,
    scheduledCampaigns: 0,
    runningCampaigns: 0,
    completedCampaigns: 0,
    sent: 0,
    opened: 0,
    clicked: 0,
    converted: 0,
  };

  const main = (
    <Space direction="vertical" size={16} style={{ width: '100%' }}>
      <Card title="Campaign Builder">
        <Form form={form} layout="vertical" initialValues={{ channel: 'email', assetType: 'newsletter', scheduleMode: 'now', followUpMode: 'skip' }}>
          <Row gutter={12}>
            <Col xs={24} md={12}>
              <Form.Item name="name" label="Campaign Name" rules={[{ required: true }]}>
                <Input />
              </Form.Item>
            </Col>
            <Col xs={24} md={12}>
              <Form.Item name="brandName" label="Brand Name" rules={[{ required: true }]}>
                <Input />
              </Form.Item>
            </Col>
            <Col xs={24} md={12}>
              <Form.Item name="goal" label="Goal">
                <Input placeholder="Nurture, convert, re-engage..." />
              </Form.Item>
            </Col>
            <Col xs={24} md={12}>
              <Form.Item name="channel" label="Channel" rules={[{ required: true }]}>
                <Select options={CHANNEL_OPTIONS} onChange={() => setCurrentStep(0)} />
              </Form.Item>
            </Col>
            <Col xs={24} md={12}>
              <Form.Item name="audienceSegment" label="Audience Segment" rules={[{ required: true }]}>
                <Select
                  options={segments.map((segment) => ({ value: segment._id, label: segment.name }))}
                  onChange={() => setCurrentStep(1)}
                />
              </Form.Item>
            </Col>
            <Col xs={24} md={12}>
              <Form.Item name="assetType" label="Content Type">
                <Select options={CONTENT_TYPE_OPTIONS} />
              </Form.Item>
            </Col>
            <Col span={24}>
              <Form.Item name="prompt" label="Prompt" rules={[{ required: true }]}>
                <Input.TextArea rows={4} placeholder="Describe the message, angle, and audience." />
              </Form.Item>
            </Col>
            <Col xs={24} md={12}>
              <Form.Item name="subject" label="Subject / Headline">
                <Input />
              </Form.Item>
            </Col>
            <Col xs={24} md={12}>
              <Form.Item name="scheduleMode" label="Send Timing">
                <Select
                  options={[
                    { value: 'now', label: 'Launch now' },
                    { value: 'scheduled', label: 'Schedule later' },
                  ]}
                  onChange={() => setCurrentStep(3)}
                />
              </Form.Item>
            </Col>
            {values.scheduleMode === 'scheduled' ? (
              <Col xs={24} md={12}>
                <Form.Item name="scheduledAt" label="Scheduled At">
                  <DatePicker showTime style={{ width: '100%' }} />
                </Form.Item>
              </Col>
            ) : null}
            <Col xs={24} md={12}>
              <Form.Item name="followUpMode" label="Automation Follow-up">
                <Select
                  options={[
                    { value: 'skip', label: 'Skip for now' },
                    { value: 'create-rule', label: 'Create follow-up automation' },
                  ]}
                />
              </Form.Item>
            </Col>
            {values.followUpMode === 'create-rule' ? (
              <Col xs={24} md={12}>
                <Form.Item name="followUpTaskTitle" label="Follow-up Task Title">
                  <Input placeholder="Review non-openers, call hot leads..." />
                </Form.Item>
              </Col>
            ) : null}
            <Col span={24}>
              <Form.Item name="body" label="Campaign Body">
                <Input.TextArea rows={8} onChange={(event) => setDraftContent(event.target.value)} />
              </Form.Item>
            </Col>
          </Row>
        </Form>
        <Space>
          <Button disabled={!canDraft} onClick={draftWithAI}>
            Draft with AI
          </Button>
          <Button type="primary" disabled={!canSchedule} onClick={launchCampaign}>
            Review & Launch
          </Button>
        </Space>
      </Card>

      <Card title="Reusable AI Assets">
        <Table
          rowKey="_id"
          loading={loading}
          dataSource={assets}
          pagination={false}
          columns={[
            { title: 'Title', dataIndex: 'title' },
            { title: 'Type', dataIndex: 'type', render: (value) => <Tag>{value}</Tag> },
            { title: 'Provider', dataIndex: 'provider' },
            {
              title: 'Import',
              render: (_, record) => (
                <Button
                  size="small"
                  onClick={() => {
                    setSelectedAssetId(record._id);
                    setDraftContent(record.content || '');
                    form.setFieldsValue({
                      assetType: record.type,
                      body: record.content,
                      prompt: record.prompt,
                      brandName: record.brandContext?.brandName,
                    });
                    setCurrentStep(2);
                  }}
                >
                  Use Asset
                </Button>
              ),
            },
          ]}
        />
      </Card>

      <Card title="Recent Campaigns">
        <Table
          rowKey="_id"
          loading={loading}
          dataSource={summary?.campaigns || []}
          pagination={false}
          columns={[
            { title: 'Name', dataIndex: 'name' },
            { title: 'Channel', dataIndex: 'channel' },
            { title: 'Status', dataIndex: 'status', render: (value) => <Tag>{value}</Tag> },
            {
              title: 'Performance',
              render: (_, record) =>
                `${record.metrics?.opened || 0} opens / ${record.metrics?.clicked || 0} clicks / ${record.metrics?.converted || 0} conversions`,
            },
          ]}
        />
      </Card>
    </Space>
  );

  const sidebar = (
    <>
      <Card title="Audience Summary" size="small">
        <Space direction="vertical" style={{ width: '100%' }}>
          <Tag color="blue">{selectedSegment?.name || 'No segment selected'}</Tag>
          <div>{buildAudienceSummary(selectedSegment)}</div>
          <div>Estimated audience size: {selectedSegment?.recentEngagements || 0}</div>
        </Space>
      </Card>

      <Card title="Launch Checklist" size="small">
        <List
          size="small"
          dataSource={checklist}
          renderItem={(item) => (
            <List.Item>
              <Tag color={item.done ? 'green' : 'default'}>{item.done ? 'Ready' : 'Pending'}</Tag>
              {item.label}
            </List.Item>
          )}
        />
      </Card>

      <Card title="Performance Snapshot" size="small">
        <Row gutter={[12, 12]}>
          <Col span={12}>
            <Statistic title="Campaigns" value={metrics.totalCampaigns} />
          </Col>
          <Col span={12}>
            <Statistic title="Running" value={metrics.runningCampaigns} />
          </Col>
          <Col span={12}>
            <Statistic title="Sent" value={metrics.sent} />
          </Col>
          <Col span={12}>
            <Statistic title="Clicks" value={metrics.clicked} />
          </Col>
        </Row>
      </Card>

      <Card title="Linked Records" size="small">
        <List
          size="small"
          dataSource={[
            ['Imported Asset', selectedAsset?.title || 'None'],
            ['Audience Segment', selectedSegment?.name || 'None'],
            ['Scheduled Posts', summary?.scheduledPosts?.length || 0],
          ]}
          renderItem={([label, value]) => (
            <List.Item>
              <strong>{label}:</strong> {value}
            </List.Item>
          )}
        />
      </Card>

      <Card title="Next Actions" size="small">
        <Space direction="vertical" style={{ width: '100%' }}>
          <Button onClick={handoffToAutomations}>Create Follow-up Automation</Button>
          <Button onClick={handoffToAi}>Rewrite in AI Studio</Button>
          <Button>
            <Link to="/records/campaign">Open Raw Campaign Records</Link>
          </Button>
        </Space>
      </Card>
    </>
  );

  return (
    <GrowthWorkspaceShell
      title="Marketing Workspace"
      description="Launch campaigns with guided audience, content, scheduling, and follow-up workflows."
      permission="marketing.campaign.read"
      currentStep={currentStep}
      stepItems={STEP_ITEMS}
      statusTags={[
        { label: checklist.every((item) => item.done) ? 'Ready for Review' : 'Draft', color: checklist.every((item) => item.done) ? 'green' : 'blue' },
        { label: values.scheduleMode === 'scheduled' ? 'Scheduled' : 'Launch Now', color: values.scheduleMode === 'scheduled' ? 'purple' : 'gold' },
      ]}
      linkedRecords={[
        { label: 'Segment', value: selectedSegment?.name },
        { label: 'Asset', value: selectedAsset?.title },
      ]}
      extra={
        <Space>
          <Button icon={<ReloadOutlined />} onClick={load}>
            Refresh
          </Button>
          <Button disabled={!canDraft} onClick={draftWithAI}>
            Draft with AI
          </Button>
          <Button type="primary" disabled={!canSchedule} onClick={launchCampaign}>
            Launch Campaign
          </Button>
        </Space>
      }
      main={main}
      sidebar={sidebar}
    />
  );
}
