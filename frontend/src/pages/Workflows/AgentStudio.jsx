import { useEffect, useMemo, useState } from 'react';
import {
  Alert, Badge, Button, Card, Col, DatePicker, Descriptions, Divider, Drawer, Empty, Form,
  Input, InputNumber, List, Modal, Progress, Radio, Row, Segmented, Select, Space, Spin,
  Statistic, Steps, Switch, Table, Tabs, Tag, Timeline, Tooltip, Typography, message,
} from 'antd';
import {
  ApiOutlined, AuditOutlined, BookOutlined, BulbOutlined, CalendarOutlined, CheckCircleOutlined,
  ClockCircleOutlined, DollarOutlined, ExperimentOutlined, GlobalOutlined, PlayCircleOutlined,
  PlusOutlined, ReloadOutlined, RobotOutlined, SafetyCertificateOutlined, SendOutlined,
  SettingOutlined, TeamOutlined, ToolOutlined,
} from '@ant-design/icons';
import { useLocation, useNavigate } from 'react-router-dom';
import { useSelector } from 'react-redux';
import dayjs from 'dayjs';
import { request } from '@/request';
import { selectCurrentAdmin } from '@/redux/auth/selectors';
import { canAccessPermission } from '@/utils/permissions';
import { WorkflowPage } from './shared';
import useMoney from '@/settings/useMoney';
import './agentStudio.css';

const { Paragraph, Text, Title } = Typography;
const STUDIO_NAV = [
  ['library', 'Agent Library', RobotOutlined], ['builder', 'Builder', SettingOutlined],
  ['runs', 'Runs & Approvals', AuditOutlined], ['knowledge', 'Knowledge', BookOutlined],
  ['tools', 'Tools', ToolOutlined], ['brand', 'Brand Center', BulbOutlined],
  ['social', 'Social Scheduler', CalendarOutlined], ['budgets', 'Budgets', DollarOutlined],
  ['providers', 'Provider Accounts', ApiOutlined],
];
const BUILDER_STEPS = ['Purpose', 'Knowledge', 'Tools', 'Guardrails', 'Test & Publish'];
const CHANNELS = ['facebook', 'instagram', 'linkedin', 'x', 'whatsapp'];
const riskColor = { low: 'green', medium: 'gold', high: 'orange', critical: 'red' };
const statusColor = { published: 'green', succeeded: 'green', running: 'blue', queued: 'cyan', needs_approval: 'orange', failed: 'red', cancelled: 'default', draft: 'default', ready: 'green' };

const viewFromPath = (pathname) => pathname.split('/').filter(Boolean)[1] || 'library';
const listResult = (response) => response?.result || [];

function MarkdownOutput({ children }) {
  if (!children) return <Text type="secondary">No output yet.</Text>;
  return <div className="agent-markdown">{String(children).split('\n').map((line, index) => {
    if (line.startsWith('### ')) return <h4 key={index}>{line.slice(4)}</h4>;
    if (line.startsWith('## ')) return <h3 key={index}>{line.slice(3)}</h3>;
    if (/^\d+\. /.test(line)) return <div key={index} className="agent-list-line">{line}</div>;
    return line ? <p key={index}>{line.replace(/\*\*/g, '')}</p> : <br key={index} />;
  })}</div>;
}

function StudioRail({ view, onChange, pendingApprovals }) {
  return <aside className="agent-studio-rail">
    <div className="agent-studio-rail-title">AI Studio</div>
    {STUDIO_NAV.map(([key, label, Icon]) => (
      <button key={key} className={view === key ? 'active' : ''} onClick={() => onChange(key)}>
        <Icon /><span>{label}</span>{key === 'runs' && pendingApprovals ? <Badge count={pendingApprovals} size="small" /> : null}
      </button>
    ))}
  </aside>;
}

export default function AgentStudio() {
  const location = useLocation();
  const navigate = useNavigate();
  const currentAdmin = useSelector(selectCurrentAdmin);
  const money = useMoney();
  const view = viewFromPath(location.pathname);
  const [loading, setLoading] = useState(true);
  const [templates, setTemplates] = useState([]);
  const [tools, setTools] = useState([]);
  const [agents, setAgents] = useState([]);
  const [runs, setRuns] = useState([]);
  const [approvals, setApprovals] = useState([]);
  const [knowledge, setKnowledge] = useState([]);
  const [brands, setBrands] = useState([]);
  const [posts, setPosts] = useState([]);
  const [budgets, setBudgets] = useState([]);
  const [providerCatalog, setProviderCatalog] = useState([]);
  const [selectedAgentId, setSelectedAgentId] = useState();
  const [selectedRun, setSelectedRun] = useState();
  const [builderStep, setBuilderStep] = useState(0);
  const [assistantMessages, setAssistantMessages] = useState([{ role: 'assistant', content: 'Tell me the outcome you want. I can draft the purpose, tools, knowledge, and safe guardrails.' }]);
  const [assistantInput, setAssistantInput] = useState('');
  const [busy, setBusy] = useState(false);
  const [form] = Form.useForm();
  const selectedAgent = agents.find((item) => item._id === selectedAgentId) || agents[0];
  const canBuild = canAccessPermission(currentAdmin, 'ai.agent.create') || canAccessPermission(currentAdmin, 'ai.studio.create');

  const load = async () => {
    setLoading(true);
    const calls = await Promise.allSettled([
      request.get({ entity: 'agent/templates' }), request.get({ entity: 'agent/tools' }),
      request.list({ entity: 'agentdefinition', options: { page: 1, items: 100 } }),
      request.get({ entity: 'agent/run/history?limit=50' }), request.get({ entity: 'agent/approval' }),
      request.list({ entity: 'knowledgesource', options: { page: 1, items: 100 } }),
      request.list({ entity: 'brandprofile', options: { page: 1, items: 100 } }),
      request.list({ entity: 'socialpost', options: { page: 1, items: 100 } }),
      request.list({ entity: 'agentbudget', options: { page: 1, items: 100 } }),
      request.get({ entity: 'integrationaccount/providers' }),
    ]);
    const value = (index) => calls[index].status === 'fulfilled' ? listResult(calls[index].value) : [];
    setTemplates(value(0)); setTools(value(1)); setAgents(value(2)); setRuns(value(3));
    setApprovals(value(4)); setKnowledge(value(5)); setBrands(value(6)); setPosts(value(7)); setBudgets(value(8));
    setProviderCatalog(value(9));
    if (!selectedAgentId && value(2)[0]) setSelectedAgentId(value(2)[0]._id);
    setLoading(false);
  };

  useEffect(() => { load(); }, []);
  useEffect(() => {
    if (selectedAgent) form.setFieldsValue({
      name: selectedAgent.name, description: selectedAgent.description, specialistType: selectedAgent.specialistType,
      instructions: selectedAgent.instructions, provider: selectedAgent.modelPolicy?.provider || 'openai',
      fallbackProviders: selectedAgent.modelPolicy?.fallbackProviders || ['kimi'], temperature: selectedAgent.modelPolicy?.temperature ?? 0.3,
      maxOutputTokens: selectedAgent.modelPolicy?.maxOutputTokens || 2048, maxSteps: selectedAgent.modelPolicy?.maxSteps || 8,
      knowledgeSources: (selectedAgent.knowledgeSources || []).map((item) => item?._id || item), tools: selectedAgent.tools || [],
      approvalMode: selectedAgent.approvalPolicy?.mode || 'risk_based', approvalFor: selectedAgent.approvalPolicy?.approvalFor || [],
      scheduleEnabled: selectedAgent.schedule?.enabled, scheduleExpression: selectedAgent.schedule?.expression,
      tokenBudget: selectedAgent.limits?.tokenBudget || 50000, scrapedPagesPerRun: selectedAgent.limits?.scrapedPagesPerRun || 20,
    });
  }, [selectedAgentId, selectedAgent?._id]);

  const openView = (key) => navigate(key === 'library' ? '/ai-studio' : `/ai-studio/${key}`);
  const createAgent = async (templateKey = 'content_production') => {
    setBusy(true);
    const template = templates.find((item) => item.key === templateKey);
    const response = await request.post({ entity: 'agent/from-template', jsonData: { templateKey, name: template?.name } });
    await load(); setSelectedAgentId(response?.result?._id); openView('builder'); setBusy(false);
    message.success('Agent draft created');
  };
  const saveAgent = async () => {
    const values = await form.validateFields(); setBusy(true);
    const payload = {
      name: values.name, description: values.description, specialistType: values.specialistType, instructions: values.instructions,
      modelPolicy: { provider: values.provider, fallbackProviders: values.fallbackProviders, temperature: values.temperature, maxOutputTokens: values.maxOutputTokens, maxSteps: values.maxSteps },
      knowledgeSources: values.knowledgeSources || [], tools: values.tools || [],
      approvalPolicy: { mode: values.approvalMode, approvalFor: values.approvalFor || [] },
      schedule: { enabled: values.scheduleEnabled, expression: values.scheduleExpression, timezone: 'Africa/Lagos' },
      limits: { tokenBudget: values.tokenBudget, scrapedPagesPerRun: values.scrapedPagesPerRun, costBudget: { amount: 0, currency: 'NGN' } },
    };
    let response;
    if (selectedAgent?._id) response = await request.update({ entity: 'agentdefinition', id: selectedAgent._id, jsonData: payload });
    else response = await request.post({ entity: 'agent/from-template', jsonData: payload });
    await load(); setSelectedAgentId(response?.result?._id || selectedAgent?._id); setBusy(false); message.success('Agent saved');
  };
  const testAgent = async () => {
    if (!selectedAgent?._id) return message.warning('Save the agent before testing.');
    setBusy(true); const response = await request.post({ entity: `agent/${selectedAgent._id}/test`, jsonData: { objective: form.getFieldValue('description') || 'Validate this agent configuration', sample: true } });
    setSelectedRun(response?.result); setBuilderStep(4); await load(); setBusy(false); message.success('Safe test run completed');
  };
  const publishAgent = async () => {
    if (!selectedAgent?._id) return; setBusy(true); await request.post({ entity: `agent/${selectedAgent._id}/publish`, jsonData: {} }); await load(); setBusy(false); message.success('Agent published');
  };
  const sendAssistant = async () => {
    const text = assistantInput.trim(); if (!text) return;
    setAssistantMessages((items) => [...items, { role: 'user', content: text }]); setAssistantInput(''); setBusy(true);
    const response = await request.post({ entity: 'agent/assistant/chat', jsonData: { message: text, context: form.getFieldsValue(true) } });
    setAssistantMessages((items) => [...items, { role: 'assistant', content: response?.result?.message || 'I prepared a safe recommendation for this agent.' }]); setBusy(false);
  };
  const decide = async (approval, decision) => { await request.post({ entity: `agent/approval/${approval._id}/decide`, jsonData: { decision } }); await load(); message.success(`Action ${decision}`); };

  const pendingApprovals = approvals.filter((item) => item.status === 'pending').length;
  const content = loading ? <div className="agent-loading"><Spin size="large" /></div> : (
    view === 'library' ? <LibraryView {...{ templates, agents, runs, createAgent, busy, openBuilder: (id) => { setSelectedAgentId(id); openView('builder'); } }} /> :
    view === 'builder' ? <BuilderView {...{ form, templates, tools, knowledge, brands, selectedAgent, agents, setSelectedAgentId, builderStep, setBuilderStep, saveAgent, testAgent, publishAgent, busy, selectedRun, assistantMessages, assistantInput, setAssistantInput, sendAssistant }} /> :
    view === 'runs' ? <RunsView {...{ runs, approvals, selectedRun, setSelectedRun, decide, money }} /> :
    view === 'knowledge' ? <KnowledgeView items={knowledge} reload={load} /> :
    view === 'tools' ? <ToolsView tools={tools} /> :
    view === 'brand' ? <BrandView items={brands} reload={load} /> :
    view === 'social' ? <SocialView items={posts} reload={load} /> :
    view === 'providers' ? <ProvidersView items={providerCatalog} reload={load} currentAdmin={currentAdmin} /> :
    <BudgetsView items={budgets} money={money} reload={load} />
  );

  return <WorkflowPage title="AI Studio" description="Build, govern, run, and monitor custom AI agents across your business." permission="ai.studio.read" extra={<Space><Tag color="green">Provider neutral</Tag><Button icon={<ReloadOutlined />} onClick={load}>Refresh</Button></Space>}>
    <div className="agent-studio-shell"><StudioRail view={view} onChange={openView} pendingApprovals={pendingApprovals} /><main className="agent-studio-main">{content}</main></div>
  </WorkflowPage>;
}

function LibraryView({ templates, agents, runs, createAgent, busy, openBuilder }) {
  return <Space direction="vertical" size={20} style={{ width: '100%' }}>
    <div className="agent-view-heading"><div><Title level={3}>Agent Library</Title><Paragraph type="secondary">Start from a specialist or create a governed agent for a specific business outcome.</Paragraph></div><Button type="primary" icon={<PlusOutlined />} loading={busy} onClick={() => createAgent('content_production')}>Create agent</Button></div>
    <Row gutter={[12, 12]}>{templates.map((template) => <Col xs={24} md={12} xl={8} key={template.key}><Card className="agent-template" hoverable onClick={() => createAgent(template.key)}><RobotOutlined className="agent-template-icon" /><Title level={5}>{template.name}</Title><Paragraph ellipsis={{ rows: 2 }}>{template.description}</Paragraph><Space wrap>{template.tools.slice(0, 3).map((tool) => <Tag key={tool}>{tool.split('.').pop()}</Tag>)}</Space></Card></Col>)}</Row>
    <Card title="Your agents" extra={<Text type="secondary">{agents.length} agents</Text>}><Table rowKey="_id" dataSource={agents} pagination={false} columns={[
      { title: 'Agent', render: (_, item) => <Space><RobotOutlined /><div><Text strong>{item.name}</Text><br/><Text type="secondary">{item.description || item.specialistType}</Text></div></Space> },
      { title: 'Status', dataIndex: 'status', render: (value) => <Tag color={statusColor[value]}>{value}</Tag> },
      { title: 'Version', dataIndex: 'version', render: (value) => `v${value || 0}` },
      { title: 'Recent runs', render: (_, item) => runs.filter((run) => (run.agent?._id || run.agent) === item._id).length },
      { title: '', render: (_, item) => <Button onClick={() => openBuilder(item._id)}>Open builder</Button> },
    ]}/></Card>
  </Space>;
}

function BuilderView(props) {
  const { form, templates, tools, knowledge, selectedAgent, agents, setSelectedAgentId, builderStep, setBuilderStep, saveAgent, testAgent, publishAgent, busy, selectedRun, assistantMessages, assistantInput, setAssistantInput, sendAssistant } = props;
  const selectedTools = Form.useWatch('tools', form) || [];
  return <div className="agent-builder-grid">
    <section className="agent-builder-canvas">
      <div className="agent-view-heading"><div><Space><Select value={selectedAgent?._id} placeholder="New agent" style={{ minWidth: 220 }} onChange={setSelectedAgentId} options={agents.map((item) => ({ value: item._id, label: item.name }))}/><Tag color={statusColor[selectedAgent?.status]}>{selectedAgent?.status || 'new'}</Tag></Space><Title level={3}>Build an AI Agent</Title></div><Space><Button onClick={saveAgent} loading={busy}>Save</Button><Button type="primary" onClick={publishAgent} disabled={!selectedAgent}>Publish</Button></Space></div>
      <Steps current={builderStep} onChange={setBuilderStep} responsive items={BUILDER_STEPS.map((title) => ({ title }))}/>
      <Form form={form} layout="vertical" className="agent-builder-form" initialValues={{ provider: 'openai', fallbackProviders: ['kimi'], temperature: 0.3, maxOutputTokens: 2048, maxSteps: 8, approvalMode: 'risk_based', tokenBudget: 50000, scrapedPagesPerRun: 20 }}>
        <Row gutter={14}>
          <Col xs={24} lg={14}><Card title="Agent identity" size="small"><Form.Item name="name" label="Agent name" rules={[{ required: true }]}><Input placeholder="Growth Strategist" /></Form.Item><Form.Item name="specialistType" label="Specialist template"><Select options={templates.map((item) => ({ value: item.key, label: item.name }))}/></Form.Item><Form.Item name="description" label="Primary outcome"><Input placeholder="Analyze markets and propose actionable growth strategies" /></Form.Item><Form.Item name="instructions" label="Role and instructions" rules={[{ required: true }]}><Input.TextArea rows={6} /></Form.Item></Card></Col>
          <Col xs={24} lg={10}><Card title="Model" size="small"><Form.Item name="provider" label="Primary provider"><Select options={[{ value: 'openai', label: 'OpenAI' }, { value: 'kimi', label: 'Kimi' }, { value: 'hermes', label: 'Hermes' }]}/></Form.Item><Form.Item name="fallbackProviders" label="Fallback providers"><Select mode="multiple" options={[{ value: 'openai', label: 'OpenAI' }, { value: 'kimi', label: 'Kimi' }, { value: 'hermes', label: 'Hermes' }]}/></Form.Item><Form.Item name="temperature" label="Creativity"><InputNumber min={0} max={2} step={0.1} style={{ width: '100%' }}/></Form.Item><Row gutter={8}><Col span={12}><Form.Item name="maxOutputTokens" label="Output tokens"><InputNumber min={256} style={{ width: '100%' }}/></Form.Item></Col><Col span={12}><Form.Item name="maxSteps" label="Max steps"><InputNumber min={1} max={30} style={{ width: '100%' }}/></Form.Item></Col></Row></Card></Col>
        </Row>
        <Row gutter={14}>
          <Col xs={24} lg={8}><Card title="Knowledge sources" size="small"><Form.Item name="knowledgeSources"><Select mode="multiple" placeholder="Attach sources" options={knowledge.map((item) => ({ value: item._id, label: item.name }))}/></Form.Item><List size="small" dataSource={knowledge.slice(0, 3)} renderItem={(item) => <List.Item><Space><BookOutlined/><span>{item.name}</span></Space><Tag color={statusColor[item.status]}>{item.status}</Tag></List.Item>}/></Card></Col>
          <Col xs={24} lg={8}><Card title="Allowed tools" size="small"><Form.Item name="tools"><Select mode="multiple" placeholder="Add tools" options={tools.map((item) => ({ value: item.key, label: item.label }))}/></Form.Item><Space wrap>{selectedTools.map((key) => { const item = tools.find((tool) => tool.key === key); return <Tag color={riskColor[item?.riskLevel]} key={key}>{item?.label || key}</Tag>; })}</Space></Card></Col>
          <Col xs={24} lg={8}><Card title="Execution & guardrails" size="small"><Form.Item name="approvalMode" label="Approval policy"><Select options={[{ value: 'risk_based', label: 'Approval by risk' }, { value: 'always', label: 'Always approve writes' }, { value: 'autonomous', label: 'Autonomous within limits' }]}/></Form.Item><Form.Item name="approvalFor" label="Additional approval tools"><Select mode="multiple" options={tools.filter((item) => item.riskLevel !== 'low').map((item) => ({ value: item.key, label: item.label }))}/></Form.Item><Form.Item name="scheduleEnabled" label="Scheduled runs" valuePropName="checked"><Switch /></Form.Item><Form.Item name="scheduleExpression" label="Schedule"><Input placeholder="Every Monday at 09:00" /></Form.Item><Row gutter={8}><Col span={12}><Form.Item name="tokenBudget" label="Token limit"><InputNumber min={1000} style={{ width: '100%' }}/></Form.Item></Col><Col span={12}><Form.Item name="scrapedPagesPerRun" label="Page limit"><InputNumber min={0} max={100} style={{ width: '100%' }}/></Form.Item></Col></Row></Card></Col>
        </Row>
      </Form>
      <Card className="agent-test-console" title={<Space><ExperimentOutlined/>Test run</Space>} extra={<Button type="primary" icon={<PlayCircleOutlined />} loading={busy} onClick={testAgent}>Run safe test</Button>}>
        {selectedRun ? <RunTimeline run={selectedRun} /> : <Empty image={Empty.PRESENTED_IMAGE_SIMPLE} description="Test mode simulates external writes and shows approval gates before publishing." />}
      </Card>
    </section>
    <aside className="agent-assistant"><div className="agent-assistant-header"><RobotOutlined/><div><Text strong>AI Assistant</Text><br/><Text type="secondary">Agent setup copilot</Text></div></div><div className="agent-assistant-messages">{assistantMessages.map((item, index) => <div key={index} className={`agent-chat ${item.role}`}><MarkdownOutput>{item.content}</MarkdownOutput></div>)}</div><div className="agent-assistant-input"><Input.TextArea autoSize={{ minRows: 2, maxRows: 5 }} value={assistantInput} onChange={(event) => setAssistantInput(event.target.value)} placeholder="Ask for an agent, tools, or guardrails..."/><Button type="primary" icon={<SendOutlined />} loading={busy} onClick={sendAssistant}>Send</Button></div></aside>
  </div>;
}

function RunTimeline({ run }) { return <><Space wrap><Tag color={statusColor[run.status]}>{run.status}</Tag><Text>Step {run.currentStep || 0}</Text><Text>{run.usage?.totalTokens || 0} tokens</Text></Space><Divider/><MarkdownOutput>{run.output?.content || 'The test run validated configured tools. Sensitive actions remain simulated.'}</MarkdownOutput></>; }

function RunsView({ runs, approvals, selectedRun, setSelectedRun, decide, money }) {
  return <Space direction="vertical" size={16} style={{ width: '100%' }}><div className="agent-view-heading"><div><Title level={3}>Runs & Approvals</Title><Paragraph type="secondary">Inspect execution steps, costs, errors, and sensitive actions waiting for a person.</Paragraph></div></div><Row gutter={12}><Col span={6}><Card><Statistic title="Running" value={runs.filter((r) => r.status === 'running').length}/></Card></Col><Col span={6}><Card><Statistic title="Needs approval" value={approvals.filter((a) => a.status === 'pending').length}/></Card></Col><Col span={6}><Card><Statistic title="Succeeded" value={runs.filter((r) => r.status === 'succeeded').length}/></Card></Col><Col span={6}><Card><Statistic title="Failed" value={runs.filter((r) => r.status === 'failed').length}/></Card></Col></Row>
  {approvals.filter((item) => item.status === 'pending').map((item) => <Alert key={item._id} type="warning" showIcon message={`${item.tool} requires approval`} description={<Space direction="vertical"><Text>{item.riskLevel} risk action requested by an agent.</Text><Space><Button type="primary" onClick={() => decide(item, 'approved')}>Approve</Button><Button danger onClick={() => decide(item, 'rejected')}>Reject</Button></Space></Space>}/>) }
  <Card title="Run history"><Table rowKey="_id" dataSource={runs} pagination={{ pageSize: 10 }} onRow={(record) => ({ onClick: () => setSelectedRun(record) })} columns={[{ title: 'Agent', dataIndex: 'agent', render: (value) => value?.name || value || '-' }, { title: 'Status', dataIndex: 'status', render: (value) => <Tag color={statusColor[value]}>{value}</Tag> }, { title: 'Trigger', dataIndex: 'trigger' }, { title: 'Tokens', render: (_, item) => item.usage?.totalTokens || 0 }, { title: 'Cost', render: (_, item) => money.moneyFormatter({ amount: item.cost?.amount || 0, currency_code: item.cost?.currency || 'NGN' }) }, { title: 'Started', dataIndex: 'startedAt', render: (value) => value ? dayjs(value).format('DD MMM, HH:mm') : '-' }]}/></Card>
  <Drawer title="Run detail" open={Boolean(selectedRun)} onClose={() => setSelectedRun(null)} width={620}>{selectedRun && <RunTimeline run={selectedRun}/>}</Drawer></Space>;
}

function KnowledgeView({ items, reload }) {
  const add = () => Modal.confirm({ title: 'Add knowledge source', content: <KnowledgeForm onDone={reload}/>, footer: null, width: 560, icon: null });
  return <ResourceView title="Knowledge" description="Ground agents in tenant ERP records, uploaded files, and compliant public websites." action={<Button type="primary" icon={<PlusOutlined/>} onClick={add}>Add source</Button>}><Table rowKey="_id" dataSource={items} columns={[{ title: 'Source', render: (_, item) => <Space><BookOutlined/><div><Text strong>{item.name}</Text><br/><Text type="secondary">{item.type}</Text></div></Space> }, { title: 'Status', dataIndex: 'status', render: (value) => <Tag color={statusColor[value]}>{value}</Tag> }, { title: 'Documents', dataIndex: 'documentCount' }, { title: 'Chunks', dataIndex: 'chunkCount' }, { title: 'Last refreshed', dataIndex: 'lastIngestedAt', render: (value) => value ? dayjs(value).fromNow?.() || dayjs(value).format('DD MMM') : 'Not ingested' }]}/></ResourceView>;
}
function KnowledgeForm({ onDone }) { const [form] = Form.useForm(); const submit = async () => { const values = await form.validateFields(); await request.create({ entity: 'knowledgesource', jsonData: { ...values, status: 'draft', config: values.type === 'website' ? { url: values.url } : { entity: values.entity } } }); Modal.destroyAll(); onDone(); message.success('Knowledge source added'); }; return <Form form={form} layout="vertical"><Form.Item name="name" label="Name" rules={[{ required: true }]}><Input/></Form.Item><Form.Item name="type" label="Source type" rules={[{ required: true }]}><Select options={[{ value: 'erp', label: 'ERP records' }, { value: 'file', label: 'Uploaded file' }, { value: 'website', label: 'Approved website' }]}/></Form.Item><Form.Item name="url" label="Public URL"><Input placeholder="https://example.com"/></Form.Item><Form.Item name="entity" label="ERP entity"><Select options={['Lead','Contact','Company','Campaign','Expense'].map((value) => ({ value, label: value }))}/></Form.Item><Button type="primary" onClick={submit}>Add source</Button></Form>; }

function ToolsView({ tools }) { return <ResourceView title="Tools" description="Capabilities are permission checked at execution time. High-risk tools always require approval."><Row gutter={[12,12]}>{tools.map((item) => <Col xs={24} md={12} xl={8} key={item.key}><Card size="small"><Space align="start"><ApiOutlined className="agent-template-icon"/><div><Text strong>{item.label}</Text><Paragraph type="secondary">{item.key}</Paragraph><Space><Tag color={riskColor[item.riskLevel]}>{item.riskLevel} risk</Tag><Tag color={item.available ? 'green' : 'default'}>{item.available ? 'Available' : 'Unavailable'}</Tag></Space></div></Space></Card></Col>)}</Row></ResourceView>; }

function BrandView({ items, reload }) { const create = async () => { await request.create({ entity: 'brandprofile', jsonData: { name: 'Primary Brand', voice: 'Clear, confident, practical', audience: 'Business decision makers', vocabulary: ['trusted', 'measurable'], prohibitedClaims: ['guaranteed results'], isDefault: items.length === 0 } }); await reload(); message.success('Brand profile created'); }; return <ResourceView title="Brand Center" description="Give every agent one consistent voice, vocabulary, visual identity, and claim policy." action={<Button type="primary" icon={<PlusOutlined/>} onClick={create}>New brand profile</Button>}><Row gutter={[12,12]}>{items.map((item) => <Col xs={24} lg={12} key={item._id}><Card title={item.name} extra={item.isDefault && <Tag color="green">Default</Tag>}><Descriptions column={1} size="small" items={[{ key: 'voice', label: 'Voice', children: item.voice || '-' }, { key: 'audience', label: 'Audience', children: item.audience || '-' }, { key: 'words', label: 'Vocabulary', children: <Space wrap>{(item.vocabulary || []).map((word) => <Tag key={word}>{word}</Tag>)}</Space> }, { key: 'claims', label: 'Prohibited claims', children: <Space wrap>{(item.prohibitedClaims || []).map((word) => <Tag color="red" key={word}>{word}</Tag>)}</Space> }]}/></Card></Col>)}</Row></ResourceView>; }

function SocialView({ items, reload }) { const [mode, setMode] = useState('Calendar'); const create = async () => { await request.create({ entity: 'socialpost', jsonData: { caption: 'A new AI-assisted campaign draft ready for review.', channels: ['facebook','instagram','linkedin'], provider: 'facebook', status: 'pending_approval', approvalStatus: 'pending', scheduledAt: dayjs().add(1,'day').toISOString(), timezone: 'Africa/Lagos' } }); await reload(); message.success('Social draft scheduled for approval'); }; return <ResourceView title="Social Scheduler" description="Plan, approve, and publish channel-native content across Facebook, Instagram, LinkedIn, X, and WhatsApp Business." action={<Space><Segmented value={mode} onChange={setMode} options={['Calendar','List']}/><Button type="primary" icon={<PlusOutlined/>} onClick={create}>Schedule post</Button></Space>}><Row gutter={12}>{CHANNELS.map((channel) => <Col flex="1" key={channel}><Card size="small"><Statistic title={channel.toUpperCase()} value={items.filter((item) => (item.channels || [item.provider]).includes(channel)).length}/></Card></Col>)}</Row><Card className="agent-resource-card" title={`${mode} view`}><Table rowKey="_id" dataSource={items} columns={[{ title: 'Content', dataIndex: 'caption', ellipsis: true }, { title: 'Channels', render: (_, item) => <Space wrap>{(item.channels?.length ? item.channels : [item.provider]).map((value) => <Tag key={value}>{value}</Tag>)}</Space> }, { title: 'Schedule', dataIndex: 'scheduledAt', render: (value) => value ? dayjs(value).format('DD MMM YYYY, HH:mm') : 'Draft' }, { title: 'Approval', dataIndex: 'approvalStatus', render: (value) => <Tag color={value === 'approved' ? 'green' : 'orange'}>{value || 'pending'}</Tag> }, { title: 'Status', dataIndex: 'status', render: (value) => <Tag color={statusColor[value]}>{value}</Tag> }]}/></Card></ResourceView>; }

function BudgetsView({ items, money, reload }) { const create = async () => { await request.create({ entity: 'agentbudget', jsonData: { name: 'Growth Agent Budget', allocated: { amount: 250000, currency: 'NGN' }, actual: { amount: 0, currency: 'NGN' }, approvalThreshold: { amount: 50000, currency: 'NGN' }, status: 'active' } }); await reload(); message.success('Agent budget created'); }; return <ResourceView title="Agent Budgets" description="Control NGN allocation, provider usage, channel spend, and approval thresholds." action={<Button type="primary" icon={<PlusOutlined/>} onClick={create}>Create budget</Button>}><Row gutter={[12,12]}>{items.map((item) => { const pct = item.allocated?.amount ? Math.round((item.actual?.amount || 0) / item.allocated.amount * 100) : 0; return <Col xs={24} lg={12} key={item._id}><Card title={item.name} extra={<Tag color={statusColor[item.status]}>{item.status}</Tag>}><Row gutter={12}><Col span={8}><Statistic title="Allocated" value={money.moneyFormatter({ amount: item.allocated?.amount || 0, currency_code: 'NGN' })}/></Col><Col span={8}><Statistic title="Actual" value={money.moneyFormatter({ amount: item.actual?.amount || 0, currency_code: 'NGN' })}/></Col><Col span={8}><Statistic title="Remaining" value={money.moneyFormatter({ amount: Math.max(0,(item.allocated?.amount || 0)-(item.actual?.amount || 0)), currency_code: 'NGN' })}/></Col></Row><Progress percent={pct} status={pct > 90 ? 'exception' : 'active'}/><Text type="secondary">Changes above {money.moneyFormatter({ amount: item.approvalThreshold?.amount || 0, currency_code: 'NGN' })} require approval.</Text></Card></Col>; })}</Row></ResourceView>; }

function ProvidersView({ items, reload, currentAdmin }) {
  const [drawerOpen, setDrawerOpen] = useState(false);
  const [selectedProvider, setSelectedProvider] = useState(null);
  const [form] = Form.useForm();
  const canEdit = canAccessPermission(currentAdmin, 'automations.integrationaccount.update');

  const openProvider = (provider) => {
    setSelectedProvider(provider);
    form.resetFields();
    const publicValues = provider.account?.publicConfig || {};
    const nextValues = { name: provider.account?.name || provider.label, enabled: provider.account?.enabled ?? true };
    for (const field of provider.publicFields || []) nextValues[field.key] = publicValues[field.key];
    form.setFieldsValue(nextValues);
    setDrawerOpen(true);
  };

  const saveProvider = async () => {
    const values = await form.validateFields();
    if (!selectedProvider) return;
    const publicConfig = {};
    const secretConfig = {};
    for (const field of selectedProvider.publicFields || []) if (values[field.key]) publicConfig[field.key] = values[field.key];
    for (const field of selectedProvider.secretFields || []) if (values[field.key]) secretConfig[field.key] = values[field.key];

    const accountId = selectedProvider.account?._id;
    const payload = {
      provider: selectedProvider.key,
      name: values.name || selectedProvider.label,
      enabled: values.enabled,
      status: values.enabled ? 'active' : 'disabled',
      publicConfig,
      ...(Object.keys(secretConfig).length ? { secretConfig } : {}),
    };

    if (accountId) {
      await request.update({ entity: 'integrationaccount', id: accountId, jsonData: payload });
    } else {
      await request.create({ entity: 'integrationaccount', jsonData: payload });
    }
    await reload();
    setDrawerOpen(false);
    message.success('Provider account saved');
  };

  const testProvider = async (providerKey) => {
    await request.post({ entity: `integrationaccount/test/${providerKey}`, jsonData: {} });
    await reload();
  };

  const removeSecret = async (providerKey) => {
    await request.post({ entity: `integrationaccount/secret/${providerKey}`, jsonData: {} });
    await reload();
    message.success('Stored secret removed');
  };

  return <ResourceView title="Provider Accounts" description="Admins can connect LLM, email, and social providers here. Secrets are stored encrypted and only shown back as masked status.">
    <Row gutter={[12, 12]}>
      {items.map((provider) => (
        <Col xs={24} md={12} xl={8} key={provider.key}>
          <Card
            title={provider.label}
            extra={<Tag color={provider.account?.status === 'active' ? 'green' : provider.account?.status === 'error' ? 'red' : 'default'}>{provider.account?.status || 'not configured'}</Tag>}
            actions={[
              <Button type="link" onClick={() => openProvider(provider)} disabled={!canEdit}>Configure</Button>,
              <Button type="link" onClick={() => testProvider(provider.key)} disabled={!provider.account}>Test</Button>,
              <Button type="link" danger onClick={() => removeSecret(provider.key)} disabled={!provider.account?.secretConfigured}>Clear secret</Button>,
            ]}
          >
            <Space direction="vertical" size={10} style={{ width: '100%' }}>
              <Space wrap>
                <Tag>{provider.category}</Tag>
                {provider.supportsLiveTest ? <Tag color="blue">Live test</Tag> : <Tag>Saved only</Tag>}
                {provider.envFallbackActive ? <Tag color="gold">Env fallback</Tag> : null}
              </Space>
              <Text type="secondary">
                {provider.account?.lastTestMessage || 'No connection test has been run yet.'}
              </Text>
              <List
                size="small"
                dataSource={provider.account?.secretPreview || []}
                locale={{ emptyText: 'No stored secret yet' }}
                renderItem={(item) => (
                  <List.Item>
                    <Space direction="vertical" size={0}>
                      <Text strong>{item.key}</Text>
                      <Text type="secondary">{item.maskedValue}</Text>
                    </Space>
                  </List.Item>
                )}
              />
            </Space>
          </Card>
        </Col>
      ))}
    </Row>
    <Drawer
      title={selectedProvider ? `${selectedProvider.label} Provider Account` : 'Provider Account'}
      open={drawerOpen}
      width={520}
      onClose={() => setDrawerOpen(false)}
      extra={<Space><Button onClick={() => setDrawerOpen(false)}>Cancel</Button><Button type="primary" onClick={saveProvider} disabled={!canEdit}>Save</Button></Space>}
    >
      {selectedProvider ? <Form form={form} layout="vertical">
        <Form.Item name="name" label="Account name" rules={[{ required: true }]}><Input /></Form.Item>
        <Form.Item name="enabled" label="Enabled" valuePropName="checked"><Switch /></Form.Item>
        {(selectedProvider.publicFields || []).map((field) => (
          <Form.Item key={field.key} name={field.key} label={field.label}>
            <Input placeholder={field.placeholder} />
          </Form.Item>
        ))}
        <Divider>Secret credentials</Divider>
        <Alert type="info" showIcon message="Secrets are stored encrypted on the server and only returned to the UI as masked previews." style={{ marginBottom: 16 }} />
        {(selectedProvider.secretFields || []).map((field) => (
          <Form.Item key={field.key} name={field.key} label={field.label} extra="Leave blank to keep the current stored secret.">
            <Input.Password placeholder={`Enter ${field.label.toLowerCase()}`} />
          </Form.Item>
        ))}
      </Form> : null}
    </Drawer>
  </ResourceView>;
}

function ResourceView({ title, description, action, children }) { return <Space direction="vertical" size={18} style={{ width:'100%' }}><div className="agent-view-heading"><div><Title level={3}>{title}</Title><Paragraph type="secondary">{description}</Paragraph></div>{action}</div>{children}</Space>; }
