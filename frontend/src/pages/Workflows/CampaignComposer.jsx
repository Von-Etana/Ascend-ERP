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
  Typography,
  message,
  Descriptions,
  Badge,
  Alert,
} from 'antd';
import { ReloadOutlined, ArrowLeftOutlined, ArrowRightOutlined, RocketOutlined } from '@ant-design/icons';
import { Link, useLocation, useNavigate } from 'react-router-dom';
import { useSelector } from 'react-redux';
import { request } from '@/request';
import { selectCurrentAdmin } from '@/redux/auth/selectors';
import { canAccessEntityAction, canAccessPermission } from '@/utils/permissions';
import GrowthWorkspaceShell from './GrowthWorkspaceShell';
import dayjs from 'dayjs';
import {
  buildCampaignChecklist,
  buildCampaignLaunchPayload,
  buildAudienceSummary,
  createHandoffState,
} from './growthWorkspace';

const { Text } = Typography;

const STEP_ITEMS = [
  { title: 'Goal & Channel', description: 'Set purpose and delivery path' },
  { title: 'Audience', description: 'Pick segment and review fit' },
  { title: 'Content', description: 'Draft, import, and edit content' },
  { title: 'Schedule', description: 'Choose when and where to launch' },
  { title: 'Review & Launch', description: 'Validate readiness and launch' },
  { title: 'Performance', description: 'Monitor results and next actions' },
];

const CHANNELS_LIST = [
  { value: 'email', label: 'Email Newsletter', icon: '✉️', color: '#1890ff' },
  { value: 'facebook', label: 'Facebook Post', icon: '👥', color: '#3b5998' },
  { value: 'instagram', label: 'Instagram Feed', icon: '📸', color: '#e1306c' },
  { value: 'whatsapp', label: 'WhatsApp Alert', icon: '💬', color: '#25d366' },
  { value: 'multi', label: 'Multi-channel', icon: '🌐', color: '#722ed1' },
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
    try {
      const [workspaceResponse, segmentResponse, assetResponse] = await Promise.all([
        request.get({ entity: 'marketing/workspace/summary' }),
        request.list({ entity: 'audiencesegment', options: { page: 1, items: 100 } }),
        request.get({ entity: 'ai/workspace/assets?limit=20' }),
      ]);
      setSummary(workspaceResponse?.result || null);
      setSegments(segmentResponse?.result || []);
      setAssets(assetResponse?.result || []);
    } catch (err) {
      message.error('Failed to load workspace parameters.');
    } finally {
      setLoading(false);
    }
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
    setLoading(true);
    try {
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
      load();
      message.success('Campaign draft generated');
    } catch (err) {
      message.error('AI generation failed.');
    } finally {
      setLoading(false);
    }
  };

  const launchCampaign = async () => {
    const draft = await form.validateFields(['name', 'channel', 'audienceSegment', 'assetType']);
    setLoading(true);
    try {
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
        message.success('Campaign launched successfully');
        setCurrentStep(5);
        load();
      }
    } catch (err) {
      message.error('Failed to launch campaign.');
    } finally {
      setLoading(false);
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

  const handleNext = async () => {
    let fieldsToValidate = [];
    if (currentStep === 0) fieldsToValidate = ['name', 'brandName', 'channel'];
    else if (currentStep === 1) fieldsToValidate = ['audienceSegment'];
    else if (currentStep === 2) fieldsToValidate = ['prompt', 'body'];
    else if (currentStep === 3) {
      fieldsToValidate = ['scheduleMode'];
      if (values.scheduleMode === 'scheduled') fieldsToValidate.push('scheduledAt');
    }

    try {
      if (fieldsToValidate.length > 0) {
        await form.validateFields(fieldsToValidate);
      }
      setCurrentStep((prev) => Math.min(prev + 1, STEP_ITEMS.length - 1));
    } catch (err) {
      message.error('Please complete the required fields.');
    }
  };

  const handleBack = () => {
    setCurrentStep((prev) => Math.max(0, prev - 1));
  };

  const getCountdownText = (scheduledAt) => {
    if (!scheduledAt) return null;
    const diffMs = dayjs(scheduledAt).diff(dayjs());
    if (diffMs <= 0) return 'Launch date is in the past! It will launch immediately.';
    const minutes = Math.floor((diffMs / 1000 / 60) % 60);
    const hours = Math.floor((diffMs / 1000 / 60 / 60) % 24);
    const days = Math.floor(diffMs / 1000 / 60 / 60 / 24);
    return `Campaign will launch in ${days} days, ${hours} hours, and ${minutes} minutes.`;
  };

  const renderStepContent = () => {
    switch (currentStep) {
      case 0:
        return (
          <Row gutter={[16, 16]}>
            <Col xs={24} md={12}>
              <Form.Item name="name" label="Campaign Name" rules={[{ required: true, message: 'Campaign name is required' }]}>
                <Input placeholder="E.g. July Product Launch Promo" size="large" />
              </Form.Item>
            </Col>
            <Col xs={24} md={12}>
              <Form.Item name="brandName" label="Brand Context Name" rules={[{ required: true, message: 'Brand name is required' }]}>
                <Input placeholder="E.g. Acme Corporation" size="large" />
              </Form.Item>
            </Col>
            <Col span={24}>
              <Form.Item name="goal" label="Campaign Goal / Target Purpose">
                <Input placeholder="E.g. Re-engage cold leads, announce new checkout feature..." size="large" />
              </Form.Item>
            </Col>
            <Col span={24}>
              <Form.Item name="channel" label="Primary Channel" rules={[{ required: true, message: 'Please select a delivery channel' }]}>
                <div style={{ display: 'flex', gap: '12px', flexWrap: 'wrap', marginTop: '4px' }}>
                  {CHANNELS_LIST.map((ch) => {
                    const selected = values.channel === ch.value;
                    return (
                      <div
                        key={ch.value}
                        onClick={() => {
                          form.setFieldsValue({ channel: ch.value });
                          // Force watch update
                          setCurrentStep(0);
                        }}
                        style={{
                          flex: '1 1 140px',
                          padding: '16px',
                          border: `2px solid ${selected ? ch.color : '#f0f0f0'}`,
                          borderRadius: '12px',
                          textAlign: 'center',
                          cursor: 'pointer',
                          background: selected ? `${ch.color}0a` : '#fff',
                          transition: 'all 0.25s ease',
                          boxShadow: selected ? `0 4px 12px ${ch.color}20` : 'none',
                        }}
                      >
                        <div style={{ fontSize: '28px', marginBottom: '8px' }}>{ch.icon}</div>
                        <Text strong style={{ color: selected ? ch.color : 'inherit', fontSize: '14px' }}>
                          {ch.label}
                        </Text>
                      </div>
                    );
                  })}
                </div>
              </Form.Item>
            </Col>
          </Row>
        );

      case 1:
        return (
          <Space direction="vertical" size={16} style={{ width: '100%' }}>
            <Form.Item name="audienceSegment" label="Target Audience Segment" rules={[{ required: true, message: 'Audience segment is required' }]}>
              <Select
                size="large"
                placeholder="Select audience segment..."
                options={segments.map((segment) => ({ value: segment._id, label: segment.name }))}
              />
            </Form.Item>
            {selectedSegment && (
              <Card style={{ background: '#fafafa', border: '1px solid #f0f0f0', borderRadius: '8px' }}>
                <Space direction="vertical" size={10} style={{ width: '100%' }}>
                  <div style={{ display: 'flex', justify: 'space-between', alignItems: 'center' }}>
                    <Text strong style={{ fontSize: '16px' }}>{selectedSegment.name}</Text>
                    <Tag color="blue" style={{ padding: '4px 8px', fontSize: '13px' }}>
                      Estimated Reach: ~{selectedSegment.recentEngagements || 42} Contacts
                    </Tag>
                  </div>
                  <Text type="secondary">{selectedSegment.description}</Text>
                  {selectedSegment.filters && selectedSegment.filters.length > 0 && (
                    <div style={{ marginTop: 8 }}>
                      <Text strong style={{ display: 'block', marginBottom: 6, fontSize: '12px' }}>CRITERIA FILTERS:</Text>
                      <Space wrap>
                        {selectedSegment.filters.map((filter, idx) => (
                          <Tag color="orange" key={idx}>
                            {filter.field} {filter.operator} {filter.value}
                          </Tag>
                        ))}
                      </Space>
                    </div>
                  )}
                </Space>
              </Card>
            )}
          </Space>
        );

      case 2:
        return (
          <Row gutter={24}>
            <Col xs={24} lg={12}>
              <Space direction="vertical" size={16} style={{ width: '100%' }}>
                <Form.Item name="assetType" label="Content Asset Layout Type">
                  <Select
                    options={[
                      { value: 'newsletter', label: 'Newsletter layout' },
                      { value: 'email', label: 'Simple email copy' },
                      { value: 'caption', label: 'Social media caption' },
                      { value: 'blog', label: 'Blog text draft' },
                    ]}
                  />
                </Form.Item>
                <Form.Item name="prompt" label="AI Creative Prompt Instructions" rules={[{ required: true, message: 'AI instructions prompt is required' }]}>
                  <Input.TextArea
                    rows={4}
                    placeholder="E.g. Write a premium announcement for our July product launch. Voice should be bold and professional..."
                  />
                </Form.Item>
                <Button type="dashed" size="large" onClick={draftWithAI} style={{ width: '100%' }}>
                  🪄 Autodraft with AI
                </Button>
                {values.channel === 'email' || values.channel === 'multi' ? (
                  <Form.Item name="subject" label="Subject / Headline">
                    <Input placeholder="Enter email subject header..." />
                  </Form.Item>
                ) : null}
                <Form.Item name="body" label="Draft Editor Content" rules={[{ required: true, message: 'Draft content is required' }]}>
                  <Input.TextArea
                    rows={8}
                    value={draftContent}
                    onChange={(e) => setDraftContent(e.target.value)}
                    placeholder="Enter or modify your campaign text copy here..."
                  />
                </Form.Item>

                {assets.length > 0 && (
                  <Card title="Quick Import Brand Asset" size="small">
                    <List
                      dataSource={assets.slice(0, 3)}
                      renderItem={(asset) => (
                        <List.Item
                          actions={[
                            <Button
                              size="small"
                              type="link"
                              onClick={() => {
                                setDraftContent(asset.content || '');
                                form.setFieldsValue({
                                  body: asset.content,
                                  prompt: asset.prompt,
                                  assetType: asset.type,
                                });
                                setSelectedAssetId(asset._id);
                              }}
                            >
                              Import
                            </Button>
                          ]}
                        >
                          <List.Item.Meta title={asset.title} description={asset.type} />
                        </List.Item>
                      )}
                    />
                  </Card>
                )}
              </Space>
            </Col>
            <Col xs={24} lg={12}>
              <div style={{ padding: '20px', background: '#f5f7fa', border: '1px solid #e8e8e8', borderRadius: '12px', minHeight: '400px' }}>
                <Text strong style={{ display: 'block', marginBottom: 16, color: '#434343', letterSpacing: '0.05em' }}>
                  🔴 LIVE COMPOSER PREVIEW
                </Text>
                {values.channel === 'email' || values.channel === 'multi' ? (
                  <div style={{ background: '#fff', borderRadius: '8px', border: '1px solid #dcdcdc', boxShadow: '0 4px 12px rgba(0,0,0,0.03)', overflow: 'hidden' }}>
                    <div style={{ background: '#f5f5f5', padding: '12px 16px', borderBottom: '1px solid #e8e8e8' }}>
                      <Space direction="vertical" size={4} style={{ width: '100%' }}>
                        <div>
                          <Text type="secondary" style={{ fontSize: '12px' }}>From: </Text>
                          <Text strong style={{ fontSize: '13px' }}>{values.brandName || 'Brand Context'} &lt;newsletters@acme.com&gt;</Text>
                        </div>
                        <div>
                          <Text type="secondary" style={{ fontSize: '12px' }}>Subject: </Text>
                          <Text strong style={{ fontSize: '13px' }}>{values.subject || '(Enter subject line)'}</Text>
                        </div>
                      </Space>
                    </div>
                    <div style={{ padding: '20px', minHeight: '260px', whiteSpace: 'pre-wrap', color: '#262626', fontSize: '14px', lineHeight: '1.6' }}>
                      {draftContent || 'Type in the editor or click AI draft to view mockup...'}
                    </div>
                  </div>
                ) : values.channel === 'whatsapp' ? (
                  <div style={{ background: '#efe9e2', padding: '16px', borderRadius: '12px', minHeight: '300px', display: 'flex', flexDirection: 'column', justifyContent: 'flex-start' }}>
                    <div style={{
                      background: '#fff',
                      borderRadius: '8px',
                      padding: '12px',
                      maxWidth: '85%',
                      boxShadow: '0 1px 2px rgba(0,0,0,0.12)',
                      whiteSpace: 'pre-wrap',
                      fontSize: '14px',
                      lineHeight: '1.5',
                      position: 'relative'
                    }}>
                      <Text strong style={{ color: '#075e54', display: 'block', marginBottom: 4 }}>{values.brandName || 'Acme'}</Text>
                      <Text>{draftContent || 'Your WhatsApp campaign text...'}</Text>
                      <span style={{ fontSize: '10px', color: '#8c8c8c', float: 'right', marginTop: '6px' }}>12:00 PM</span>
                    </div>
                  </div>
                ) : (
                  <div style={{ background: '#fff', border: '1px solid #e1e8ed', borderRadius: '12px', overflow: 'hidden', boxShadow: '0 4px 12px rgba(0,0,0,0.03)' }}>
                    <div style={{ padding: '12px', display: 'flex', alignItems: 'center', gap: '10px' }}>
                      <div style={{ width: '36px', height: '36px', borderRadius: '50%', background: '#722ed1', color: '#fff', display: 'flex', alignItems: 'center', justify: 'center', fontWeight: 'bold' }}>
                        {(values.brandName || 'A').charAt(0).toUpperCase()}
                      </div>
                      <div>
                        <Text strong style={{ display: 'block', fontSize: '13px' }}>{values.brandName || 'Acme Brand'}</Text>
                        <Text type="secondary" style={{ fontSize: '11px' }}>Sponsored • {values.channel || 'social'}</Text>
                      </div>
                    </div>
                    <div style={{ padding: '12px', whiteSpace: 'pre-wrap', color: '#292f33', fontSize: '13.5px', lineHeight: '1.5' }}>
                      {draftContent || 'Social post text...'}
                    </div>
                    <div style={{ background: '#f5f8fa', height: '180px', display: 'flex', alignItems: 'center', justifyContent: 'center', borderTop: '1px solid #e1e8ed', borderBottom: '1px solid #e1e8ed' }}>
                      <Text type="secondary">📸 Visual Content Asset Area</Text>
                    </div>
                    <div style={{ padding: '8px 12px', display: 'flex', justifyContent: 'space-around', borderTop: '1px solid #e1e8ed' }}>
                      <Text type="secondary" style={{ fontSize: '12px', cursor: 'pointer' }}>👍 Like</Text>
                      <Text type="secondary" style={{ fontSize: '12px', cursor: 'pointer' }}>💬 Comment</Text>
                      <Text type="secondary" style={{ fontSize: '12px', cursor: 'pointer' }}>🔗 Share</Text>
                    </div>
                  </div>
                )}
              </div>
            </Col>
          </Row>
        );

      case 3:
        return (
          <Row gutter={[16, 16]}>
            <Col xs={24} md={12}>
              <Form.Item name="scheduleMode" label="Launch Send Timing" rules={[{ required: true }]}>
                <Select
                  size="large"
                  options={[
                    { value: 'now', label: 'Launch Immediately (Now)' },
                    { value: 'scheduled', label: 'Schedule for a future date' },
                  ]}
                />
              </Form.Item>
            </Col>
            {values.scheduleMode === 'scheduled' ? (
              <Col xs={24} md={12}>
                <Form.Item name="scheduledAt" label="Launch Date & Time" rules={[{ required: true, message: 'Please select a date' }]}>
                  <DatePicker showTime style={{ width: '100%' }} size="large" />
                </Form.Item>
              </Col>
            ) : null}
            {values.scheduleMode === 'scheduled' && values.scheduledAt ? (
              <Col span={24}>
                <Alert
                  type="info"
                  showIcon
                  message="Campaign Scheduler Countdown"
                  description={getCountdownText(values.scheduledAt)}
                />
              </Col>
            ) : null}
            <Col xs={24} md={12}>
              <Form.Item name="followUpMode" label="Automation Follow-up Link">
                <Select
                  size="large"
                  options={[
                    { value: 'skip', label: 'Skip follow-up rule' },
                    { value: 'create-rule', label: 'Attach follow-up task rules' },
                  ]}
                />
              </Form.Item>
            </Col>
            {values.followUpMode === 'create-rule' ? (
              <Col xs={24} md={12}>
                <Form.Item name="followUpTaskTitle" label="Follow-up Rule Task Title" rules={[{ required: true, message: 'Follow-up title is required' }]}>
                  <Input placeholder="E.g. Check non-openers response, follow up hot leads..." size="large" />
                </Form.Item>
              </Col>
            ) : null}
          </Row>
        );

      case 4:
        return (
          <Space direction="vertical" size={16} style={{ width: '100%' }}>
            <Card title="Launch Specs Overview" size="small">
              <Descriptions bordered column={1} size="small">
                <Descriptions.Item label="Campaign Name"><Text strong>{values.name}</Text></Descriptions.Item>
                <Descriptions.Item label="Brand Context Name">{values.brandName}</Descriptions.Item>
                <Descriptions.Item label="Target Goal">{values.goal || 'None specified'}</Descriptions.Item>
                <Descriptions.Item label="Delivery Channel"><Tag color="blue">{values.channel?.toUpperCase()}</Tag></Descriptions.Item>
                <Descriptions.Item label="Audience Segment">{selectedSegment?.name || 'Not linked'}</Descriptions.Item>
                <Descriptions.Item label="Send Timing">
                  {values.scheduleMode === 'scheduled'
                    ? `Scheduled for: ${dayjs(values.scheduledAt).format('YYYY-MM-DD HH:mm')}`
                    : 'Launch immediately now'}
                </Descriptions.Item>
              </Descriptions>
            </Card>

            <Card title="Launch Readiness Check" size="small">
              <List
                size="small"
                dataSource={checklist}
                renderItem={(item) => (
                  <List.Item>
                    <Badge status={item.done ? 'success' : 'error'} text={item.label} />
                  </List.Item>
                )}
              />
            </Card>
          </Space>
        );

      case 5:
        return (
          <Space direction="vertical" size={16} style={{ width: '100%' }}>
            <Card title="Campaign Performance Summary">
              <Row gutter={[16, 16]}>
                <Col span={6}>
                  <Statistic title="Total Contacts Sent" value={metrics.sent} />
                </Col>
                <Col span={6}>
                  <Statistic
                    title="Opens Rate"
                    value={metrics.sent ? `${Math.round((metrics.opened / metrics.sent) * 100)}%` : '0%'}
                    suffix={`(${metrics.opened})`}
                  />
                </Col>
                <Col span={6}>
                  <Statistic
                    title="Clicks Rate"
                    value={metrics.opened ? `${Math.round((metrics.clicked / metrics.opened) * 100)}%` : '0%'}
                    suffix={`(${metrics.clicked})`}
                  />
                </Col>
                <Col span={6}>
                  <Statistic title="Conversions" value={metrics.converted} />
                </Col>
              </Row>
            </Card>

            <Card title="Recent Active Campaigns" extra={<Button icon={<ReloadOutlined />} onClick={load}>Refresh</Button>}>
              <Table
                rowKey="_id"
                loading={loading}
                dataSource={summary?.campaigns || []}
                pagination={false}
                columns={[
                  { title: 'Name', dataIndex: 'name' },
                  { title: 'Channel', dataIndex: 'channel', render: (val) => <Tag>{val}</Tag> },
                  { title: 'Status', dataIndex: 'status', render: (val) => <Tag color={val === 'running' ? 'green' : 'blue'}>{val}</Tag> },
                  {
                    title: 'Performance Stats',
                    render: (_, record) =>
                      `${record.metrics?.sent || 0} sent • ${record.metrics?.opened || 0} opens • ${record.metrics?.clicked || 0} clicks`,
                  },
                ]}
              />
            </Card>
          </Space>
        );

      default:
        return null;
    }
  };

  const main = (
    <Space direction="vertical" size={16} style={{ width: '100%' }}>
      <Card
        title={`Step ${currentStep + 1} of ${STEP_ITEMS.length}: ${STEP_ITEMS[currentStep].title}`}
        extra={
          currentStep < 5 && (
            <Space>
              <Button disabled={currentStep === 0} onClick={handleBack} icon={<ArrowLeftOutlined />}>
                Back
              </Button>
              {currentStep < 4 ? (
                <Button type="primary" onClick={handleNext} icon={<ArrowRightOutlined />}>
                  Next
                </Button>
              ) : (
                <Button
                  type="primary"
                  onClick={launchCampaign}
                  icon={<RocketOutlined />}
                  style={{ background: '#52c41a', borderColor: '#52c41a' }}
                >
                  Launch Campaign
                </Button>
              )}
            </Space>
          )
        }
      >
        <Form
          form={form}
          layout="vertical"
          initialValues={{ channel: 'email', assetType: 'newsletter', scheduleMode: 'now', followUpMode: 'skip' }}
        >
          {renderStepContent()}
        </Form>
      </Card>
    </Space>
  );

  const sidebar = (
    <>
      <Card title="Audience Snapshot" size="small">
        <Space direction="vertical" style={{ width: '100%' }}>
          <Tag color="blue">{selectedSegment?.name || 'No audience segment'}</Tag>
          <div>{buildAudienceSummary(selectedSegment)}</div>
          <div>Estimated reach: <Text strong>{selectedSegment?.recentEngagements || 0}</Text> subscribers</div>
        </Space>
      </Card>

      <Card title="Launch Checklist" size="small">
        <List
          size="small"
          dataSource={checklist}
          renderItem={(item) => (
            <List.Item>
              <Badge status={item.done ? 'success' : 'default'} text={item.label} />
            </List.Item>
          )}
        />
      </Card>

      <Card title="Next Actions" size="small">
        <Space direction="vertical" style={{ width: '100%' }}>
          <Button onClick={handoffToAutomations} style={{ width: '100%' }}>Create Follow-up Automation</Button>
          <Button onClick={handoffToAi} style={{ width: '100%' }}>Optimize with AI Writer</Button>
          <Button style={{ width: '100%' }}>
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
        { label: checklist.every((item) => item.done) ? 'Ready for Review' : 'Drafting', color: checklist.every((item) => item.done) ? 'green' : 'blue' },
        { label: values.scheduleMode === 'scheduled' ? 'Scheduled' : 'Instant Launch', color: values.scheduleMode === 'scheduled' ? 'purple' : 'gold' },
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
        </Space>
      }
      main={main}
      sidebar={currentStep < 5 ? sidebar : null}
    />
  );
}
