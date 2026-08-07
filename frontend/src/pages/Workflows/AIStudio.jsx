import { useEffect, useMemo, useState } from 'react';
import {
  Button,
  Card,
  Col,
  Form,
  Input,
  List,
  Row,
  Select,
  Space,
  Table,
  Tag,
  message,
} from 'antd';
import { Link, useLocation, useNavigate } from 'react-router-dom';
import { ReloadOutlined } from '@ant-design/icons';
import { useSelector } from 'react-redux';
import { request } from '@/request';
import { selectCurrentAdmin } from '@/redux/auth/selectors';
import { canAccessPermission } from '@/utils/permissions';
import GrowthWorkspaceShell from './GrowthWorkspaceShell';
import {
  CHANNEL_OPTIONS,
  CONTENT_TYPE_OPTIONS,
  buildAiBriefPayload,
  buildAudienceSummary,
  createHandoffState,
} from './growthWorkspace';

const STEP_ITEMS = [
  { title: 'Brief', description: 'Set campaign and brand context' },
  { title: 'Generate', description: 'Draft AI content' },
  { title: 'Variants', description: 'Compare and edit outputs' },
  { title: 'Brand Assets', description: 'Generate visual support' },
  { title: 'Publish / Handoff', description: 'Send work into campaigns or automations' },
];

export default function AIStudio() {
  const [currentStep, setCurrentStep] = useState(0);
  const [form] = Form.useForm();
  const [campaigns, setCampaigns] = useState([]);
  const [segments, setSegments] = useState([]);
  const [assets, setAssets] = useState([]);
  const [variants, setVariants] = useState([]);
  const [selectedVariant, setSelectedVariant] = useState(0);
  const [brandAsset, setBrandAsset] = useState(null);
  const [loading, setLoading] = useState(false);
  const [assetTypeFilter, setAssetTypeFilter] = useState();
  const [providerFilter, setProviderFilter] = useState();
  const navigate = useNavigate();
  const location = useLocation();
  const currentAdmin = useSelector(selectCurrentAdmin);
  const canGenerate = canAccessPermission(currentAdmin, 'ai.studio.create');
  const handoff = location.state?.handoff;

  const briefValues = Form.useWatch([], form) || {};

  const load = async () => {
    setLoading(true);
    const [campaignResponse, segmentResponse, assetResponse] = await Promise.all([
      request.list({ entity: 'campaign', options: { page: 1, items: 100 } }),
      request.list({ entity: 'audiencesegment', options: { page: 1, items: 100 } }),
      request.get({ entity: 'ai/workspace/assets?limit=20' }),
    ]);
    setCampaigns(campaignResponse?.result || []);
    setSegments(segmentResponse?.result || []);
    setAssets(assetResponse?.result || []);
    setLoading(false);
  };

  useEffect(() => {
    load();
  }, []);

  useEffect(() => {
    if (location.pathname.endsWith('/assets')) {
      setCurrentStep(2);
    } else if (location.pathname.endsWith('/providers')) {
      setCurrentStep(4);
    } else if (!handoff) {
      setCurrentStep(0);
    }
  }, [handoff, location.pathname]);

  useEffect(() => {
    if (!handoff) return;
    form.setFieldsValue({
      brandName: handoff.campaign?.name || handoff.asset?.brandContext?.brandName || '',
      linkedCampaignId: handoff.campaign?._id,
      linkedSegmentId: handoff.segment?._id,
      prompt: handoff.content || handoff.asset?.content || '',
      type: handoff.asset?.type || 'email',
      audience: handoff.segment?.name,
    });
  }, [handoff, form]);

  const selectedSegment = useMemo(
    () => segments.find((segment) => segment._id === briefValues.linkedSegmentId),
    [segments, briefValues.linkedSegmentId]
  );
  const selectedCampaign = useMemo(
    () => campaigns.find((campaign) => campaign._id === briefValues.linkedCampaignId),
    [campaigns, briefValues.linkedCampaignId]
  );
  const activeVariant = variants[selectedVariant];

  const generateVariants = async () => {
    const values = await form.validateFields([
      'brandName',
      'type',
      'prompt',
      'goal',
      'channel',
      'audience',
      'brandVoice',
      'cta',
      'offerContext',
    ]);
    const payload = buildAiBriefPayload(values);
    const [primary, alternate] = await Promise.all([
      request.post({ entity: 'ai/content/generate', jsonData: payload }),
      request.post({
        entity: 'ai/content/generate',
        jsonData: {
          ...payload,
          prompt: `${values.prompt}\n\nCreate an alternate angle with a different opening and CTA framing.`,
        },
      }),
    ]);
    const nextVariants = [primary, alternate].map((response, index) => ({
      key: `variant-${index + 1}`,
      label: `Variant ${index + 1}`,
      content:
        response?.result?.asset?.content ||
        response?.result?.provider?.content ||
        JSON.stringify(response?.result, null, 2),
      asset: response?.result?.asset || null,
    }));
    setVariants(nextVariants);
    setSelectedVariant(0);
    setCurrentStep(2);
    load();
    message.success('AI variants generated');
  };

  const generateBrandAsset = async () => {
    const values = await form.validateFields(['brandName', 'prompt']);
    const response = await request.post({
      entity: 'ai/brand-asset/generate',
      jsonData: {
        prompt: `${values.prompt}\n\nMatch this visual brief to the generated campaign copy.`,
        brandContext: {
          brandName: values.brandName,
          campaignId: values.linkedCampaignId,
          audienceSegment: values.linkedSegmentId,
        },
      },
    });
    setBrandAsset(response?.result?.asset || response?.result?.provider || null);
    setCurrentStep(3);
    load();
    message.success('Brand asset request sent');
  };

  const createSocialDraft = async () => {
    if (!activeVariant?.content) {
      message.warning('Generate a content variant first.');
      return;
    }
    await request.create({
      entity: 'socialpost',
      jsonData: {
        provider: ['facebook', 'instagram'].includes(briefValues.channel) ? briefValues.channel : 'facebook',
        caption: activeVariant.content,
        campaign: briefValues.linkedCampaignId,
        status: 'draft',
      },
    });
    message.success('Social post draft created');
  };

  const handoffToMarketing = () => {
    navigate('/marketing', {
      state: {
        handoff: createHandoffState({
          source: 'ai-studio',
          campaign: selectedCampaign,
          asset: activeVariant?.asset || brandAsset,
          segment: selectedSegment,
          content: activeVariant?.content,
        }),
      },
    });
  };

  const handoffToAutomations = () => {
    navigate('/automations', {
      state: {
        handoff: createHandoffState({
          source: 'ai-studio',
          campaign: selectedCampaign,
          asset: activeVariant?.asset || brandAsset,
          segment: selectedSegment,
          content: activeVariant?.content,
        }),
      },
    });
  };

  const filteredAssets = useMemo(() => {
    return assets.filter((asset) => {
      if (assetTypeFilter && asset.type !== assetTypeFilter) return false;
      if (providerFilter && asset.provider !== providerFilter) return false;
      return true;
    });
  }, [assets, assetTypeFilter, providerFilter]);

  const main = (
    <Space direction="vertical" size={16} style={{ width: '100%' }}>
      <Card title="Brief">
        <Form form={form} layout="vertical" initialValues={{ type: 'newsletter', channel: 'email' }}>
          <Row gutter={12}>
            <Col xs={24} md={12}>
              <Form.Item name="brandName" label="Brand Name" rules={[{ required: true }]}>
                <Input />
              </Form.Item>
            </Col>
            <Col xs={24} md={12}>
              <Form.Item name="goal" label="Campaign Goal" rules={[{ required: true }]}>
                <Input placeholder="Increase replies, book demos, nurture leads..." />
              </Form.Item>
            </Col>
            <Col xs={24} md={12}>
              <Form.Item name="channel" label="Primary Channel" rules={[{ required: true }]}>
                <Select options={CHANNEL_OPTIONS} />
              </Form.Item>
            </Col>
            <Col xs={24} md={12}>
              <Form.Item name="type" label="Asset Type" rules={[{ required: true }]}>
                <Select options={CONTENT_TYPE_OPTIONS} />
              </Form.Item>
            </Col>
            <Col xs={24} md={12}>
              <Form.Item name="audience" label="Audience" rules={[{ required: true }]}>
                <Input placeholder="Warm leads, new customers, dormant buyers..." />
              </Form.Item>
            </Col>
            <Col xs={24} md={12}>
              <Form.Item name="brandVoice" label="Brand Voice">
                <Input placeholder="Direct, premium, playful..." />
              </Form.Item>
            </Col>
            <Col xs={24} md={12}>
              <Form.Item name="cta" label="Primary CTA">
                <Input placeholder="Book a demo, reply now, shop today..." />
              </Form.Item>
            </Col>
            <Col xs={24} md={12}>
              <Form.Item name="offerContext" label="Offer / Product Context">
                <Input placeholder="Starter plan, launch promo, webinar..." />
              </Form.Item>
            </Col>
            <Col xs={24} md={12}>
              <Form.Item name="linkedCampaignId" label="Linked Campaign">
                <Select allowClear options={campaigns.map((campaign) => ({ value: campaign._id, label: campaign.name }))} />
              </Form.Item>
            </Col>
            <Col xs={24} md={12}>
              <Form.Item name="linkedSegmentId" label="Linked Audience Segment">
                <Select allowClear options={segments.map((segment) => ({ value: segment._id, label: segment.name }))} />
              </Form.Item>
            </Col>
            <Col span={24}>
              <Form.Item name="prompt" label="Prompt" rules={[{ required: true }]}>
                <Input.TextArea rows={5} />
              </Form.Item>
            </Col>
          </Row>
        </Form>
        <Space>
          <Button type="primary" disabled={!canGenerate} onClick={generateVariants}>
            Generate Variants
          </Button>
          <Button disabled={!canGenerate} onClick={generateBrandAsset}>
            Generate Brand Asset
          </Button>
        </Space>
      </Card>

      <Card title="Generated Variants">
        <Space direction="vertical" size={12} style={{ width: '100%' }}>
          <Space wrap>
            {variants.map((variant, index) => (
              <Button
                key={variant.key}
                type={selectedVariant === index ? 'primary' : 'default'}
                onClick={() => {
                  setSelectedVariant(index);
                  setCurrentStep(2);
                }}
              >
                {variant.label}
              </Button>
            ))}
          </Space>
          <Input.TextArea
            rows={10}
            value={activeVariant?.content || ''}
            onChange={(event) =>
              setVariants((current) =>
                current.map((variant, index) =>
                  index === selectedVariant ? { ...variant, content: event.target.value } : variant
                )
              )
            }
            placeholder="Generate content to review and edit it here."
          />
        </Space>
      </Card>

      <Card title="Recent Reusable Assets">
        <Space direction="vertical" size={12} style={{ width: '100%' }}>
          <Space wrap>
            <Select
              allowClear
              placeholder="Filter by type"
              style={{ minWidth: 160 }}
              value={assetTypeFilter}
              onChange={setAssetTypeFilter}
              options={CONTENT_TYPE_OPTIONS}
            />
            <Select
              allowClear
              placeholder="Filter by provider"
              style={{ minWidth: 160 }}
              value={providerFilter}
              onChange={setProviderFilter}
              options={[
                { value: 'kimi', label: 'Kimi' },
                { value: 'fal', label: 'Fal' },
              ]}
            />
          </Space>
          <Table
            rowKey="_id"
            loading={loading}
            dataSource={filteredAssets}
            pagination={false}
            columns={[
              { title: 'Title', dataIndex: 'title' },
              { title: 'Type', dataIndex: 'type', render: (value) => <Tag>{value}</Tag> },
              { title: 'Provider', dataIndex: 'provider', render: (value) => value || '-' },
              {
                title: 'Use',
                render: (_, record) => (
                  <Button
                    size="small"
                    onClick={() => {
                      form.setFieldsValue({
                        type: record.type,
                        prompt: record.prompt,
                        brandName: record.brandContext?.brandName,
                        linkedCampaignId: record.brandContext?.campaignId,
                        linkedSegmentId: record.brandContext?.audienceSegment,
                      });
                      setVariants([{ key: record._id, label: 'Imported Asset', content: record.content, asset: record }]);
                      setSelectedVariant(0);
                      setCurrentStep(4);
                    }}
                  >
                    Import
                  </Button>
                ),
              },
            ]}
          />
        </Space>
      </Card>
    </Space>
  );

  const sidebar = (
    <>
      <Card title="Linked Context" size="small">
        <List
          size="small"
          dataSource={[
            ['Campaign', selectedCampaign?.name || 'Not linked'],
            ['Audience Segment', selectedSegment?.name || 'Not linked'],
            ['Audience Summary', buildAudienceSummary(selectedSegment)],
            ['Current Variant', activeVariant?.label || 'None'],
          ]}
          renderItem={([label, value]) => (
            <List.Item>
              <Space direction="vertical" size={0}>
                <strong>{label}</strong>
                <span>{value}</span>
              </Space>
            </List.Item>
          )}
        />
      </Card>

      <Card title="Brand Asset" size="small">
        <div style={{ whiteSpace: 'pre-wrap' }}>
          {brandAsset?.content || brandAsset?.message || brandAsset?.mediaUrl || 'Generate a brand asset to see the response here.'}
        </div>
      </Card>

      <Card title="Publish / Handoff" size="small">
        <Space direction="vertical" style={{ width: '100%' }}>
          <Button type="primary" onClick={handoffToMarketing} disabled={!activeVariant?.content}>
            Use in Campaign
          </Button>
          <Button onClick={createSocialDraft} disabled={!activeVariant?.content}>
            Create Social Post
          </Button>
          <Button onClick={handoffToAutomations} disabled={!activeVariant?.content}>
            Attach to Automation
          </Button>
          <Button icon={<ReloadOutlined />} onClick={load}>
            Refresh Assets
          </Button>
        </Space>
      </Card>

      <Card title="Next Actions" size="small">
        <Space direction="vertical" style={{ width: '100%' }}>
          <Button>
            <Link to="/marketing">Open Marketing Workspace</Link>
          </Button>
          <Button>
            <Link to="/automations">Open Automations Workspace</Link>
          </Button>
        </Space>
      </Card>
    </>
  );

  return (
    <GrowthWorkspaceShell
      title="AI Studio"
      description="Create content, compare variants, pair it with brand assets, and hand it directly into growth workflows."
      permission="ai.studio.read"
      currentStep={currentStep}
      stepItems={STEP_ITEMS}
      statusTags={[
        { label: variants.length ? 'Draft Ready' : 'Draft', color: variants.length ? 'green' : 'blue' },
        { label: brandAsset ? 'Brand Asset Ready' : 'Needs Asset', color: brandAsset ? 'purple' : 'default' },
      ]}
      linkedRecords={[
        { label: 'Campaign', value: selectedCampaign?.name },
        { label: 'Segment', value: selectedSegment?.name },
      ]}
      extra={
        <Space>
          <Button icon={<ReloadOutlined />} onClick={load}>
            Refresh
          </Button>
          <Button disabled={!canGenerate} onClick={generateVariants}>
            Generate
          </Button>
          <Button type="primary" disabled={!canGenerate} onClick={generateBrandAsset}>
            Brand Asset
          </Button>
        </Space>
      }
      main={main}
      sidebar={sidebar}
    />
  );
}
