import { useEffect, useState, useMemo, useCallback } from 'react';
import {
  Card, Col, Row, Space, Statistic, Table, Tag, Typography, Tabs, Button, Form,
  Input, InputNumber, Select, message, Modal, Progress, Alert, Divider,
  Badge, Upload, DatePicker, Tooltip, Empty, Descriptions, Popconfirm
} from 'antd';
import {
  WalletOutlined, BankOutlined, PlusOutlined, FileTextOutlined,
  DollarOutlined, SettingOutlined, SwapOutlined, PieChartOutlined,
  UploadOutlined, InboxOutlined, ShoppingOutlined, BarChartOutlined,
  FundOutlined, FileProtectOutlined, TeamOutlined, CarOutlined,
  CheckCircleOutlined, CloseCircleOutlined, SyncOutlined,
  DownloadOutlined, DeleteOutlined, EyeOutlined, FilterOutlined,
  AppstoreOutlined
} from '@ant-design/icons';
import { Link } from 'react-router-dom';
import dayjs from 'dayjs';
import { request } from '@/request';
import useMoney from '@/settings/useMoney';
import { DEFAULT_CURRENCY_CODE } from '@/constants/platformDefaults';
import LiveChart from '@/components/LiveChart';

const { Title, Text, Paragraph } = Typography;
const { Dragger } = Upload;
const { RangePicker } = DatePicker;

const EXPENSE_CATEGORIES = [
  { label: 'Vendor Payment', value: 'vendor_payment', color: '#2563eb' },
  { label: 'Staff Reimbursement', value: 'staff_reimbursement', color: '#7c3aed' },
  { label: 'Travel & Transport', value: 'travel', color: '#0891b2' },
  { label: 'Utilities', value: 'utilities', color: '#16a34a' },
  { label: 'Capital Expenditure', value: 'capital', color: '#b45309' },
  { label: 'Marketing', value: 'marketing', color: '#dc2626' },
  { label: 'Operations', value: 'operations', color: '#475569' },
  { label: 'Other', value: 'other', color: '#64748b' },
];

const BUDGET_CATEGORIES = [
  'operational', 'capital', 'marketing', 'hr', 'it', 'research', 'other'
];

const DOC_TYPES = [
  { label: 'Contract', value: 'contract' },
  { label: 'Receipt', value: 'receipt' },
  { label: 'Invoice Attachment', value: 'invoice_attachment' },
  { label: 'Agreement', value: 'agreement' },
  { label: 'Report', value: 'report' },
  { label: 'Other', value: 'other' },
];

const STATUS_COLORS = {
  paid: '#16a34a', reconciled: '#16a34a', sent: '#2563eb', approved: '#16a34a',
  unreconciled: '#d97706', pending: '#d97706', overdue: '#dc2626', rejected: '#dc2626',
  draft: '#64748b', active: '#16a34a', exhausted: '#dc2626', closed: '#1e293b', archived: '#94a3b8',
  expense: '#dc2626', sale: '#2563eb', receipt: '#16a34a',
};

const pillStyle = (color) => ({
  display: 'inline-flex', alignItems: 'center', gap: 6,
  padding: '4px 12px', borderRadius: 20, fontSize: 12, fontWeight: 600,
  background: color + '18', color: color, border: `1px solid ${color}33`,
});

export default function FinanceManagement() {
  const [activeTab, setActiveTab] = useState('overview');
  const [loading, setLoading] = useState(false);
  const [data, setData] = useState({ invoices: [], payments: [], expenses: [], budgets: [], products: [], documents: [] });
  const [error, setError] = useState('');
  const { moneyFormatter } = useMoney();

  // Modals
  const [budgetModal, setBudgetModal] = useState(false);
  const [expenseModal, setExpenseModal] = useState(false);
  const [docModal, setDocModal] = useState(false);
  const [productModal, setProductModal] = useState(false);
  const [budgetForm] = Form.useForm();
  const [expenseForm] = Form.useForm();
  const [docForm] = Form.useForm();
  const [productForm] = Form.useForm();
  const [busy, setBusy] = useState(false);

  // Expense filter
  const [expenseFilter, setExpenseFilter] = useState('all');
  const [productSearch, setProductSearch] = useState('');

  const load = useCallback(async () => {
    setLoading(true);
    setError('');
    try {
      const [invR, payR, expR, budR, prodR, docR] = await Promise.all([
        request.listAll({ entity: 'invoice' }),
        request.listAll({ entity: 'payment' }),
        request.listAll({ entity: 'expense' }),
        request.listAll({ entity: 'budget' }),
        request.listAll({ entity: 'product' }),
        request.listAll({ entity: 'document' }),
      ]);
      setData({
        invoices: invR?.result || [],
        payments: payR?.result || [],
        expenses: expR?.result || [],
        budgets: budR?.result || [],
        products: prodR?.result || [],
        documents: docR?.result || [],
      });
    } catch (err) {
      setError(err?.message || 'Error loading financial data.');
    } finally {
      setLoading(false);
    }
  }, []);

  useEffect(() => { load(); }, [load]);

  // === COMPUTED ===
  const summary = useMemo(() => {
    const totalInvoiced = data.invoices.reduce((s, i) => s + Number(i.total || 0), 0);
    const totalCollected = data.payments.reduce((s, p) => s + Number(p.amount || 0), 0);
    const totalExpenses = data.expenses.reduce((s, e) => s + Number(e.amount?.amount || 0), 0);
    const netCashFlow = totalCollected - totalExpenses;
    const outstanding = data.invoices.reduce((s, i) => {
      if (i.status !== 'paid') return s + Number(i.total - (i.credit || 0));
      return s;
    }, 0);
    const totalBudgeted = data.budgets.reduce((s, b) => s + Number(b.planned?.amount || 0), 0);
    const totalBudgetSpent = data.budgets.reduce((s, b) => s + Number(b.actual?.amount || 0), 0);
    return { totalInvoiced, totalCollected, totalExpenses, netCashFlow, outstanding, totalBudgeted, totalBudgetSpent };
  }, [data]);

  const chartData = useMemo(() => {
    const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    const groups = {};
    data.invoices.forEach(inv => {
      const lbl = dayjs(inv.date || inv.created).format('MMM');
      if (!groups[lbl]) groups[lbl] = { label: lbl, revenue: 0, expenses: 0, collected: 0 };
      groups[lbl].revenue += Number(inv.total || 0);
    });
    data.expenses.forEach(exp => {
      const lbl = dayjs(exp.date || exp.created).format('MMM');
      if (!groups[lbl]) groups[lbl] = { label: lbl, revenue: 0, expenses: 0, collected: 0 };
      groups[lbl].expenses += Number(exp.amount?.amount || 0);
    });
    data.payments.forEach(p => {
      const lbl = dayjs(p.date || p.created).format('MMM');
      if (!groups[lbl]) groups[lbl] = { label: lbl, revenue: 0, expenses: 0, collected: 0 };
      groups[lbl].collected += Number(p.amount || 0);
    });
    return Object.keys(groups).sort((a, b) => months.indexOf(a) - months.indexOf(b)).map(k => groups[k]);
  }, [data]);

  const transactions = useMemo(() => {
    const list = [];
    data.invoices.forEach(inv => list.push({
      key: `inv-${inv._id}`, ref: inv.number ? `INV-${inv.number}` : 'Invoice',
      type: 'sale', category: 'Sales Invoice', amount: inv.total || 0,
      status: inv.status || 'draft', date: inv.date || inv.created,
    }));
    data.payments.forEach(p => list.push({
      key: `pay-${p._id}`, ref: p.number ? `PAY-${p.number}` : 'Payment',
      type: 'receipt', category: 'Cash Collection', amount: p.amount || 0,
      status: p.reconciled ? 'reconciled' : 'unreconciled', date: p.date || p.created,
    }));
    data.expenses.forEach(exp => list.push({
      key: `exp-${exp._id}`, ref: exp.title || 'Expense',
      type: 'expense', category: exp.category || 'Operations', amount: exp.amount?.amount || 0,
      status: exp.status || 'paid', date: exp.date || exp.created,
    }));
    return list.sort((a, b) => new Date(b.date) - new Date(a.date));
  }, [data]);

  const filteredExpenses = useMemo(() => {
    if (expenseFilter === 'all') return data.expenses;
    return data.expenses.filter(e => e.category === expenseFilter);
  }, [data.expenses, expenseFilter]);

  const filteredProducts = useMemo(() => {
    if (!productSearch) return data.products;
    return data.products.filter(p =>
      p.name?.toLowerCase().includes(productSearch.toLowerCase()) ||
      p.reference?.toLowerCase().includes(productSearch.toLowerCase())
    );
  }, [data.products, productSearch]);

  // === HANDLERS ===
  const createBudget = async (vals) => {
    setBusy(true);
    const res = await request.create({
      entity: 'budget',
      jsonData: {
        name: vals.name, description: vals.description, category: vals.category,
        department: vals.department, notes: vals.notes,
        periodStart: vals.period?.[0], periodEnd: vals.period?.[1],
        planned: { amount: vals.planned, currency: DEFAULT_CURRENCY_CODE },
        allocated: { amount: vals.allocated || 0, currency: DEFAULT_CURRENCY_CODE },
        actual: { amount: 0, currency: DEFAULT_CURRENCY_CODE },
        approvalRequired: true,
        approvalThreshold: { amount: vals.threshold || 0, currency: DEFAULT_CURRENCY_CODE },
        status: 'active',
      }
    });
    if (res?.success) { message.success('Budget created'); setBudgetModal(false); budgetForm.resetFields(); load(); }
    else message.error(res?.message || 'Failed to create budget');
    setBusy(false);
  };

  const createExpense = async (vals) => {
    setBusy(true);
    const res = await request.create({
      entity: 'expense',
      jsonData: {
        title: vals.title, category: vals.category, spendingType: vals.spendingType,
        department: vals.department, paidBy: vals.paidBy, description: vals.description,
        amount: { amount: vals.amount, currency: DEFAULT_CURRENCY_CODE },
        date: vals.date || new Date(), status: vals.status || 'draft',
      }
    });
    if (res?.success) { message.success('Expense logged'); setExpenseModal(false); expenseForm.resetFields(); load(); }
    else message.error(res?.message || 'Failed to log expense');
    setBusy(false);
  };

  const deleteDocument = async (id) => {
    const res = await request.delete({ entity: 'document', id });
    if (res?.success) { message.success('Document removed'); load(); }
    else message.error('Failed to delete document');
  };

  // ============================
  //           RENDER
  // ============================

  const kpiCard = (title, value, color, prefix) => (
    <Card style={{ borderRadius: 10, border: '1px solid #e2e8f0' }} loading={loading}>
      <Statistic
        title={<Text style={{ fontSize: 12, color: '#64748b', fontWeight: 600, textTransform: 'uppercase', letterSpacing: '0.05em' }}>{title}</Text>}
        value={value}
        formatter={v => moneyFormatter({ amount: v, currency_code: DEFAULT_CURRENCY_CODE })}
        valueStyle={{ color, fontSize: 22, fontWeight: 700 }}
        prefix={prefix}
      />
    </Card>
  );

  return (
    <div style={{ padding: '24px 32px', background: '#f8fafc', minHeight: '100vh' }}>
      <Space direction="vertical" size={20} style={{ width: '100%' }}>

        {/* ── HEADER ── */}
        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start', flexWrap: 'wrap', gap: 12 }}>
          <div>
            <Title level={2} style={{ margin: 0, color: '#0f172a' }}>Finance Management</Title>
            <Text style={{ color: '#64748b' }}>Accounts · Budgets · Expenses · Inventory · Documents</Text>
          </div>
          <Space wrap>
            {activeTab === 'budgets' && <Button type="primary" icon={<PlusOutlined />} onClick={() => setBudgetModal(true)}>New Budget</Button>}
            {activeTab === 'expenses' && <Button type="primary" icon={<PlusOutlined />} onClick={() => setExpenseModal(true)}>Log Expense</Button>}
            {activeTab === 'documents' && <Button type="primary" icon={<UploadOutlined />} onClick={() => setDocModal(true)}>Upload Document</Button>}
            {activeTab === 'inventory' && <Button type="primary" icon={<PlusOutlined />} onClick={() => setProductModal(true)}>Add Product</Button>}
            <Button icon={<SyncOutlined />} onClick={load} loading={loading}>Refresh</Button>
          </Space>
        </div>

        {error && <Alert type="warning" showIcon message={error} />}

        {/* ── TABS ── */}
        <Tabs
          activeKey={activeTab}
          onChange={setActiveTab}
          type="card"
          items={[
            { key: 'overview', label: <><BarChartOutlined /> Overview</> },
            { key: 'budgets', label: <><FundOutlined /> Budget Planning</> },
            { key: 'expenses', label: <><DollarOutlined /> Expenses</> },
            { key: 'ledger', label: <><SwapOutlined /> Transaction Ledger</> },
            { key: 'documents', label: <><FileProtectOutlined /> Document Vault</> },
            { key: 'inventory', label: <><AppstoreOutlined /> Inventory</> },
            { key: 'config', label: <><SettingOutlined /> Setup</> },
          ]}
        />

        {/* ════════════════════════════════════════ */}
        {/* TAB 1: OVERVIEW */}
        {/* ════════════════════════════════════════ */}
        {activeTab === 'overview' && (
          <Space direction="vertical" size={20} style={{ width: '100%' }}>
            <Row gutter={[16, 16]}>
              <Col xs={24} sm={12} xl={8}>{kpiCard('Outstanding Receivables', summary.outstanding, '#dc2626')}</Col>
              <Col xs={24} sm={12} xl={8}>{kpiCard('Total Revenue Invoiced', summary.totalInvoiced, '#2563eb')}</Col>
              <Col xs={24} sm={12} xl={8}>{kpiCard('Cash Collected', summary.totalCollected, '#16a34a')}</Col>
              <Col xs={24} sm={12} xl={8}>{kpiCard('Total Expenses', summary.totalExpenses, '#d97706')}</Col>
              <Col xs={24} sm={12} xl={8}>{kpiCard('Net Cash Flow', summary.netCashFlow, summary.netCashFlow >= 0 ? '#16a34a' : '#dc2626')}</Col>
              <Col xs={24} sm={12} xl={8}>{kpiCard('Total Budgeted', summary.totalBudgeted, '#7c3aed')}</Col>
            </Row>

            <Card title="Revenue vs Expenses vs Collections — Monthly Trend" loading={loading} style={{ borderRadius: 10 }}>
              <LiveChart
                type="line"
                data={chartData}
                series={[
                  { key: 'revenue', label: 'Revenue Invoiced', color: '#2563eb' },
                  { key: 'collected', label: 'Cash Collected', color: '#16a34a' },
                  { key: 'expenses', label: 'Expenses Outflow', color: '#d97706' },
                ]}
                height={300}
                currencySymbol="NGN "
              />
            </Card>

            <Row gutter={[16, 16]}>
              <Col xs={24} lg={12}>
                <Card title="Budget Utilization" loading={loading} style={{ borderRadius: 10 }}>
                  {data.budgets.length === 0
                    ? <Empty description="No budgets configured" />
                    : data.budgets.slice(0, 5).map(b => {
                      const pct = b.planned?.amount > 0 ? Math.round((b.actual?.amount / b.planned?.amount) * 100) : 0;
                      return (
                        <div key={b._id} style={{ marginBottom: 16 }}>
                          <div style={{ display: 'flex', justifyContent: 'space-between', marginBottom: 4 }}>
                            <Text strong style={{ fontSize: 13 }}>{b.name}</Text>
                            <Text style={{ fontSize: 12, color: '#64748b' }}>{pct}% used</Text>
                          </div>
                          <Progress percent={pct} status={pct > 90 ? 'exception' : 'active'}
                            strokeColor={pct > 90 ? '#dc2626' : pct > 70 ? '#d97706' : '#16a34a'} showInfo={false} />
                        </div>
                      );
                    })}
                </Card>
              </Col>
              <Col xs={24} lg={12}>
                <Card title="Expense Breakdown by Category" loading={loading} style={{ borderRadius: 10 }}>
                  {EXPENSE_CATEGORIES.map(cat => {
                    const total = data.expenses.filter(e => e.category === cat.value).reduce((s, e) => s + Number(e.amount?.amount || 0), 0);
                    return (
                      <div key={cat.value} style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: 10 }}>
                        <span style={pillStyle(cat.color)}>{cat.label}</span>
                        <Text strong style={{ color: '#0f172a' }}>{moneyFormatter({ amount: total, currency_code: DEFAULT_CURRENCY_CODE })}</Text>
                      </div>
                    );
                  })}
                </Card>
              </Col>
            </Row>
          </Space>
        )}

        {/* ════════════════════════════════════════ */}
        {/* TAB 2: BUDGET PLANNING */}
        {/* ════════════════════════════════════════ */}
        {activeTab === 'budgets' && (
          <Space direction="vertical" size={16} style={{ width: '100%' }}>
            {data.budgets.length === 0 ? (
              <Empty
                description="No budgets configured yet."
                image={Empty.PRESENTED_IMAGE_SIMPLE}
              >
                <Button type="primary" icon={<PlusOutlined />} onClick={() => setBudgetModal(true)}>Create First Budget</Button>
              </Empty>
            ) : (
              <Row gutter={[16, 16]}>
                {data.budgets.map(b => {
                  const limit = b.planned?.amount || 0;
                  const actual = b.actual?.amount || 0;
                  const pct = limit > 0 ? Math.round((actual / limit) * 100) : 0;
                  const remaining = Math.max(0, limit - actual);
                  const statusColor = STATUS_COLORS[b.status] || '#64748b';
                  return (
                    <Col xs={24} lg={12} key={b._id}>
                      <Card
                        style={{ borderRadius: 12, border: '1px solid #e2e8f0' }}
                        title={
                          <Space>
                            <FundOutlined style={{ color: '#7c3aed' }} />
                            <Text strong style={{ fontSize: 15 }}>{b.name}</Text>
                          </Space>
                        }
                        extra={
                          <Space>
                            <span style={pillStyle(statusColor)}>{b.status?.toUpperCase()}</span>
                            <Tag color="blue">{b.category}</Tag>
                          </Space>
                        }
                      >
                        {b.department && <Text type="secondary" style={{ fontSize: 12 }}>Department: {b.department}</Text>}
                        {b.periodStart && (
                          <div style={{ marginTop: 4, marginBottom: 12 }}>
                            <Text type="secondary" style={{ fontSize: 12 }}>
                              Period: {dayjs(b.periodStart).format('DD MMM YYYY')} → {dayjs(b.periodEnd).format('DD MMM YYYY')}
                            </Text>
                          </div>
                        )}
                        <Row gutter={12} style={{ marginBottom: 16 }}>
                          <Col span={8}>
                            <Statistic title="Planned" value={moneyFormatter({ amount: limit, currency_code: DEFAULT_CURRENCY_CODE })} valueStyle={{ fontSize: 14, fontWeight: 700 }} />
                          </Col>
                          <Col span={8}>
                            <Statistic title="Spent" value={moneyFormatter({ amount: actual, currency_code: DEFAULT_CURRENCY_CODE })} valueStyle={{ fontSize: 14, fontWeight: 700, color: pct > 90 ? '#dc2626' : '#d97706' }} />
                          </Col>
                          <Col span={8}>
                            <Statistic title="Remaining" value={moneyFormatter({ amount: remaining, currency_code: DEFAULT_CURRENCY_CODE })} valueStyle={{ fontSize: 14, fontWeight: 700, color: '#16a34a' }} />
                          </Col>
                        </Row>
                        <Progress
                          percent={pct}
                          status={pct >= 100 ? 'exception' : 'active'}
                          strokeColor={pct > 90 ? '#dc2626' : pct > 70 ? '#d97706' : '#16a34a'}
                        />
                        {b.approvalThreshold?.amount > 0 && (
                          <Text type="secondary" style={{ fontSize: 11, marginTop: 8, display: 'block' }}>
                            ⚠ Approval required above {moneyFormatter({ amount: b.approvalThreshold.amount, currency_code: DEFAULT_CURRENCY_CODE })}
                          </Text>
                        )}
                        {b.notes && <Paragraph type="secondary" style={{ marginTop: 8, fontSize: 12 }}>{b.notes}</Paragraph>}
                      </Card>
                    </Col>
                  );
                })}
              </Row>
            )}
          </Space>
        )}

        {/* ════════════════════════════════════════ */}
        {/* TAB 3: EXPENSE MANAGEMENT */}
        {/* ════════════════════════════════════════ */}
        {activeTab === 'expenses' && (
          <Space direction="vertical" size={16} style={{ width: '100%' }}>
            {/* Category Filter */}
            <Card size="small" style={{ borderRadius: 10 }}>
              <Space wrap>
                <Text strong style={{ fontSize: 12 }}>Filter by Category:</Text>
                <Button size="small" type={expenseFilter === 'all' ? 'primary' : 'default'} onClick={() => setExpenseFilter('all')}>All</Button>
                {EXPENSE_CATEGORIES.map(cat => (
                  <Button key={cat.value} size="small"
                    style={expenseFilter === cat.value ? { background: cat.color, color: '#fff', border: 'none' } : {}}
                    onClick={() => setExpenseFilter(cat.value)}
                  >{cat.label}</Button>
                ))}
              </Space>
            </Card>

            <Card loading={loading} style={{ borderRadius: 10 }}>
              <Table
                dataSource={filteredExpenses.map(e => ({ ...e, key: e._id }))}
                pagination={{ pageSize: 12 }}
                columns={[
                  {
                    title: 'Date', dataIndex: 'date', width: 110,
                    render: v => dayjs(v).format('DD/MM/YYYY'),
                    sorter: (a, b) => new Date(a.date) - new Date(b.date),
                  },
                  {
                    title: 'Title', dataIndex: 'title',
                    render: v => <Text strong>{v}</Text>,
                  },
                  {
                    title: 'Category', dataIndex: 'category',
                    render: v => {
                      const cat = EXPENSE_CATEGORIES.find(c => c.value === v);
                      return cat ? <span style={pillStyle(cat.color)}>{cat.label}</span> : <Tag>{v}</Tag>;
                    },
                  },
                  {
                    title: 'Type', dataIndex: 'spendingType',
                    render: v => <Tag color={v === 'internal' ? 'blue' : 'purple'}>{v?.toUpperCase() || 'INTERNAL'}</Tag>,
                  },
                  {
                    title: 'Department', dataIndex: 'department',
                    render: v => v || <Text type="secondary">—</Text>,
                  },
                  {
                    title: 'Amount', dataIndex: 'amount', align: 'right',
                    render: v => <Text strong style={{ color: '#dc2626' }}>{moneyFormatter({ amount: v?.amount || v || 0, currency_code: DEFAULT_CURRENCY_CODE })}</Text>,
                    sorter: (a, b) => (a.amount?.amount || 0) - (b.amount?.amount || 0),
                  },
                  {
                    title: 'Status', dataIndex: 'status',
                    render: v => <span style={pillStyle(STATUS_COLORS[v] || '#64748b')}>{v?.toUpperCase()}</span>,
                  },
                ]}
                locale={{ emptyText: <Empty description={`No ${expenseFilter === 'all' ? '' : expenseFilter.replace('_', ' ')} expenses found`} /> }}
              />
            </Card>
          </Space>
        )}

        {/* ════════════════════════════════════════ */}
        {/* TAB 4: TRANSACTION LEDGER */}
        {/* ════════════════════════════════════════ */}
        {activeTab === 'ledger' && (
          <Card title="Unified Transaction Ledger" loading={loading} style={{ borderRadius: 10 }}>
            <Table
              dataSource={transactions}
              pagination={{ pageSize: 15 }}
              columns={[
                { title: 'Date', dataIndex: 'date', width: 140, render: v => dayjs(v).format('DD MMM YYYY') },
                { title: 'Reference', dataIndex: 'ref', render: v => <Text strong>{v}</Text> },
                { title: 'Category', dataIndex: 'category' },
                {
                  title: 'Type', dataIndex: 'type',
                  render: v => <span style={pillStyle(STATUS_COLORS[v] || '#64748b')}>{v?.toUpperCase()}</span>,
                },
                {
                  title: 'Amount', dataIndex: 'amount', align: 'right',
                  render: (v, r) => (
                    <Text strong style={{ color: r.type === 'expense' ? '#dc2626' : '#16a34a' }}>
                      {r.type === 'expense' ? '−' : '+'} {moneyFormatter({ amount: v, currency_code: DEFAULT_CURRENCY_CODE })}
                    </Text>
                  ),
                  sorter: (a, b) => a.amount - b.amount,
                },
                {
                  title: 'Status', dataIndex: 'status',
                  render: v => <span style={pillStyle(STATUS_COLORS[v] || '#64748b')}>{v?.toUpperCase()}</span>,
                },
              ]}
            />
          </Card>
        )}

        {/* ════════════════════════════════════════ */}
        {/* TAB 5: DOCUMENT VAULT */}
        {/* ════════════════════════════════════════ */}
        {activeTab === 'documents' && (
          <Space direction="vertical" size={16} style={{ width: '100%' }}>
            {/* Upload zone */}
            <Card style={{ borderRadius: 10 }}>
              <Dragger
                name="file"
                action={`${import.meta.env.VITE_BACKEND_SERVER || ''}/api/finance/document/upload`}
                headers={{ Authorization: `Bearer ${localStorage.getItem('token')}` }}
                accept=".pdf,.docx,.xlsx,.jpg,.jpeg,.png"
                onChange={info => {
                  if (info.file.status === 'done') { message.success(`${info.file.name} uploaded`); load(); }
                  else if (info.file.status === 'error') { message.error(`${info.file.name} upload failed`); }
                }}
                style={{ background: '#f8fafc', border: '2px dashed #cbd5e1' }}
              >
                <p className="ant-upload-drag-icon"><InboxOutlined style={{ fontSize: 32, color: '#7c3aed' }} /></p>
                <p style={{ fontWeight: 600, color: '#0f172a' }}>Drop files here to upload</p>
                <p style={{ color: '#64748b', fontSize: 13 }}>Supports PDF, DOCX, XLSX, JPG, PNG — Max 10MB</p>
              </Dragger>
            </Card>

            <Card title="Uploaded Documents" loading={loading} style={{ borderRadius: 10 }}>
              <Table
                dataSource={data.documents.map(d => ({ ...d, key: d._id }))}
                pagination={{ pageSize: 12 }}
                columns={[
                  {
                    title: 'Document Name', dataIndex: 'title',
                    render: v => <Text strong>{v}</Text>,
                  },
                  {
                    title: 'Type', dataIndex: 'docType',
                    render: v => {
                      const dt = DOC_TYPES.find(t => t.value === v);
                      return <Tag color="blue">{dt?.label || v || 'Other'}</Tag>;
                    },
                  },
                  {
                    title: 'File', dataIndex: 'fileUrl',
                    render: v => v ? (
                      <a href={v} target="_blank" rel="noopener noreferrer"><DownloadOutlined /> View / Download</a>
                    ) : <Text type="secondary">—</Text>,
                  },
                  {
                    title: 'Uploaded', dataIndex: 'created',
                    render: v => dayjs(v).format('DD MMM YYYY'),
                  },
                  {
                    title: 'Status', dataIndex: 'status',
                    render: v => <Tag color={v === 'active' ? 'green' : 'default'}>{v?.toUpperCase()}</Tag>,
                  },
                  {
                    title: 'Actions', key: 'actions',
                    render: (_, r) => (
                      <Space>
                        {r.fileUrl && <Tooltip title="View"><a href={r.fileUrl} target="_blank" rel="noopener noreferrer"><EyeOutlined /></a></Tooltip>}
                        <Popconfirm title="Delete this document?" onConfirm={() => deleteDocument(r._id)} okText="Delete" okType="danger">
                          <Tooltip title="Delete"><DeleteOutlined style={{ color: '#dc2626', cursor: 'pointer' }} /></Tooltip>
                        </Popconfirm>
                      </Space>
                    ),
                  },
                ]}
                locale={{ emptyText: <Empty description="No documents uploaded yet" image={Empty.PRESENTED_IMAGE_SIMPLE} /> }}
              />
            </Card>
          </Space>
        )}

        {/* ════════════════════════════════════════ */}
        {/* TAB 6: INVENTORY MANAGEMENT */}
        {/* ════════════════════════════════════════ */}
        {activeTab === 'inventory' && (
          <Space direction="vertical" size={16} style={{ width: '100%' }}>
            {/* Inventory KPIs */}
            <Row gutter={[16, 16]}>
              <Col xs={24} sm={8}>
                <Card style={{ borderRadius: 10, border: '1px solid #e2e8f0' }}>
                  <Statistic
                    title={<Text style={{ fontSize: 12, color: '#64748b', fontWeight: 600, textTransform: 'uppercase' }}>Total Products</Text>}
                    value={data.products.length}
                    valueStyle={{ color: '#2563eb', fontSize: 28, fontWeight: 700 }}
                    prefix={<AppstoreOutlined />}
                  />
                </Card>
              </Col>
              <Col xs={24} sm={8}>
                <Card style={{ borderRadius: 10, border: '1px solid #e2e8f0' }}>
                  <Statistic
                    title={<Text style={{ fontSize: 12, color: '#64748b', fontWeight: 600, textTransform: 'uppercase' }}>Low Stock Items</Text>}
                    value={data.products.filter(p => Number(p.stock || p.quantity || 0) <= 5).length}
                    valueStyle={{ color: '#dc2626', fontSize: 28, fontWeight: 700 }}
                    prefix={<ShoppingOutlined />}
                  />
                </Card>
              </Col>
              <Col xs={24} sm={8}>
                <Card style={{ borderRadius: 10, border: '1px solid #e2e8f0' }}>
                  <Statistic
                    title={<Text style={{ fontSize: 12, color: '#64748b', fontWeight: 600, textTransform: 'uppercase' }}>Inventory Value</Text>}
                    value={data.products.reduce((s, p) => s + (Number(p.price || 0) * Number(p.stock || p.quantity || 0)), 0)}
                    formatter={v => moneyFormatter({ amount: v, currency_code: DEFAULT_CURRENCY_CODE })}
                    valueStyle={{ color: '#16a34a', fontSize: 22, fontWeight: 700 }}
                  />
                </Card>
              </Col>
            </Row>

            {/* Search + Table */}
            <Card style={{ borderRadius: 10 }}>
              <div style={{ display: 'flex', justifyContent: 'space-between', marginBottom: 16, flexWrap: 'wrap', gap: 8 }}>
                <Input.Search
                  placeholder="Search products by name or reference…"
                  value={productSearch}
                  onChange={e => setProductSearch(e.target.value)}
                  allowClear
                  style={{ width: 320 }}
                />
                <Space>
                  <Link to="/product"><Button icon={<AppstoreOutlined />}>Manage Products</Button></Link>
                  <Link to="/supplier"><Button icon={<TeamOutlined />}>Suppliers</Button></Link>
                </Space>
              </div>
              <Table
                dataSource={filteredProducts.map(p => ({ ...p, key: p._id }))}
                loading={loading}
                pagination={{ pageSize: 15 }}
                columns={[
                  { title: 'Reference', dataIndex: 'reference', width: 120, render: v => <Text type="secondary">{v || '—'}</Text> },
                  {
                    title: 'Product / Service Name', dataIndex: 'name',
                    render: v => <Text strong>{v}</Text>,
                  },
                  { title: 'Category', dataIndex: 'category', render: v => v?.name || v || '—' },
                  {
                    title: 'Unit Price', dataIndex: 'price', align: 'right',
                    render: v => moneyFormatter({ amount: v || 0, currency_code: DEFAULT_CURRENCY_CODE }),
                    sorter: (a, b) => (a.price || 0) - (b.price || 0),
                  },
                  {
                    title: 'Stock Level', dataIndex: 'stock',
                    render: (v, r) => {
                      const qty = Number(v || r.quantity || 0);
                      const color = qty <= 0 ? '#dc2626' : qty <= 5 ? '#d97706' : '#16a34a';
                      return <span style={{ ...pillStyle(color) }}>{qty} units</span>;
                    },
                    sorter: (a, b) => (a.stock || a.quantity || 0) - (b.stock || b.quantity || 0),
                  },
                  {
                    title: 'Status', dataIndex: 'enabled',
                    render: v => <Tag color={v !== false ? 'green' : 'red'}>{v !== false ? 'Active' : 'Inactive'}</Tag>,
                  },
                  {
                    title: 'Actions', key: 'actions',
                    render: (_, r) => (
                      <Link to={`/product/read/${r._id}`}>
                        <Button size="small" icon={<EyeOutlined />}>View</Button>
                      </Link>
                    ),
                  },
                ]}
                locale={{ emptyText: <Empty description="No products found" image={Empty.PRESENTED_IMAGE_SIMPLE} /> }}
              />
            </Card>

            {/* Quick Actions */}
            <Row gutter={[16, 16]}>
              {[
                { icon: <ShoppingOutlined />, title: 'Products & Services', desc: 'Add, edit and manage your product catalog with pricing and stock levels.', link: '/product', color: '#2563eb' },
                { icon: <TeamOutlined />, title: 'Supplier Management', desc: 'Manage supplier contacts, contracts and payment terms.', link: '/supplier', color: '#7c3aed' },
                { icon: <FundOutlined />, title: 'Purchase Orders', desc: 'Create and track purchase orders to suppliers for stock replenishment.', link: '/purchaseorder', color: '#16a34a' },
                { icon: <BarChartOutlined />, title: 'Stock Analytics', desc: 'Monitor inventory movement, turnover rates and reorder points.', link: '/product', color: '#d97706' },
              ].map(item => (
                <Col xs={24} sm={12} lg={6} key={item.link}>
                  <Card hoverable style={{ borderRadius: 12, border: '1px solid #e2e8f0', height: '100%' }}>
                    <Space direction="vertical" size={10} style={{ width: '100%' }}>
                      <div style={{ width: 44, height: 44, borderRadius: 10, background: item.color + '18', display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
                        {item.icon && <span style={{ color: item.color, fontSize: 22 }}>{item.icon}</span>}
                      </div>
                      <Text strong style={{ fontSize: 14 }}>{item.title}</Text>
                      <Text type="secondary" style={{ fontSize: 12, lineHeight: 1.5 }}>{item.desc}</Text>
                      <Link to={item.link} style={{ color: item.color, fontWeight: 600, fontSize: 13 }}>Open Module →</Link>
                    </Space>
                  </Card>
                </Col>
              ))}
            </Row>
          </Space>
        )}

        {/* ════════════════════════════════════════ */}
        {/* TAB 7: SETUP */}
        {/* ════════════════════════════════════════ */}
        {activeTab === 'config' && (
          <Card title="Financial Setup Parameters" style={{ borderRadius: 10 }}>
            <Row gutter={[16, 16]}>
              {[
                { icon: <BankOutlined />, title: 'Ledger Accounts', desc: 'Chart of accounts, bank accounts, asset definitions and bookkeeping codes.', link: '/records/account', color: '#7c3aed' },
                { icon: <SwapOutlined />, title: 'Currencies', desc: 'Multi-currency configurations, exchange rates and base operational currency.', link: '/currencies', color: '#2563eb' },
                { icon: <SettingOutlined />, title: 'PDF Output Settings', desc: 'Letterheads, billing addresses, invoice templates and PDF formats.', link: '/pdf-settings', color: '#0891b2' },
                { icon: <DollarOutlined />, title: 'Taxes & Regional Profiles', desc: 'VAT, local sales tax profiles, tax brackets and calculation rules.', link: '/taxes', color: '#16a34a' },
                { icon: <WalletOutlined />, title: 'Payment Modes', desc: 'Banks, cash drawers, POS systems and card collection providers.', link: '/payment/mode', color: '#d97706' },
                { icon: <FileTextOutlined />, title: 'Invoice Settings', desc: 'Invoice numbering, due date defaults and credit terms configuration.', link: '/invoice', color: '#dc2626' },
              ].map(item => (
                <Col xs={24} md={12} lg={8} key={item.link}>
                  <Card hoverable size="small" style={{ borderRadius: 10, height: '100%' }}>
                    <Space direction="vertical" size={8} style={{ width: '100%' }}>
                      <Space>
                        <span style={{ color: item.color, fontSize: 22 }}>{item.icon}</span>
                        <Text strong style={{ fontSize: 14 }}>{item.title}</Text>
                      </Space>
                      <Text type="secondary" style={{ fontSize: 12 }}>{item.desc}</Text>
                      <Link to={item.link} style={{ fontWeight: 600, color: item.color }}>Configure →</Link>
                    </Space>
                  </Card>
                </Col>
              ))}
            </Row>
          </Card>
        )}

      </Space>

      {/* ══════════════════ MODALS ══════════════════ */}

      {/* Budget Modal */}
      <Modal title="Create Budget Plan" open={budgetModal} onCancel={() => { setBudgetModal(false); budgetForm.resetFields(); }} footer={null} width={560}>
        <Form form={budgetForm} layout="vertical" onFinish={createBudget}>
          <Row gutter={16}>
            <Col span={14}>
              <Form.Item name="name" label="Budget Name" rules={[{ required: true }]}>
                <Input placeholder="e.g. Q3 Operations Budget" />
              </Form.Item>
            </Col>
            <Col span={10}>
              <Form.Item name="category" label="Category" rules={[{ required: true }]}>
                <Select options={BUDGET_CATEGORIES.map(c => ({ label: c.charAt(0).toUpperCase() + c.slice(1), value: c }))} />
              </Form.Item>
            </Col>
          </Row>
          <Row gutter={16}>
            <Col span={12}>
              <Form.Item name="department" label="Department">
                <Input placeholder="e.g. Finance, HR" />
              </Form.Item>
            </Col>
            <Col span={12}>
              <Form.Item name="period" label="Budget Period">
                <RangePicker style={{ width: '100%' }} />
              </Form.Item>
            </Col>
          </Row>
          <Row gutter={16}>
            <Col span={12}>
              <Form.Item name="planned" label="Total Planned Amount (NGN)" rules={[{ required: true }]}>
                <InputNumber style={{ width: '100%' }} min={1} placeholder="5000000" formatter={v => `${v}`.replace(/\B(?=(\d{3})+(?!\d))/g, ',')} />
              </Form.Item>
            </Col>
            <Col span={12}>
              <Form.Item name="threshold" label="Approval Threshold (NGN)">
                <InputNumber style={{ width: '100%' }} min={0} placeholder="500000" formatter={v => `${v}`.replace(/\B(?=(\d{3})+(?!\d))/g, ',')} />
              </Form.Item>
            </Col>
          </Row>
          <Form.Item name="notes" label="Notes">
            <Input.TextArea rows={2} placeholder="Additional notes or objectives" />
          </Form.Item>
          <div style={{ display: 'flex', justifyContent: 'flex-end', gap: 8 }}>
            <Button onClick={() => setBudgetModal(false)}>Cancel</Button>
            <Button type="primary" htmlType="submit" loading={busy}>Create Budget</Button>
          </div>
        </Form>
      </Modal>

      {/* Expense Modal */}
      <Modal title="Log New Expense" open={expenseModal} onCancel={() => { setExpenseModal(false); expenseForm.resetFields(); }} footer={null} width={560}>
        <Form form={expenseForm} layout="vertical" onFinish={createExpense}>
          <Form.Item name="title" label="Expense Title" rules={[{ required: true }]}>
            <Input placeholder="e.g. Office Supplies Purchase" />
          </Form.Item>
          <Row gutter={16}>
            <Col span={12}>
              <Form.Item name="category" label="Expense Category" rules={[{ required: true }]}>
                <Select options={EXPENSE_CATEGORIES.map(c => ({ label: c.label, value: c.value }))} />
              </Form.Item>
            </Col>
            <Col span={12}>
              <Form.Item name="spendingType" label="Spending Type" rules={[{ required: true }]}>
                <Select options={[{ label: 'Internal', value: 'internal' }, { label: 'External', value: 'external' }]} />
              </Form.Item>
            </Col>
          </Row>
          <Row gutter={16}>
            <Col span={12}>
              <Form.Item name="amount" label="Amount (NGN)" rules={[{ required: true }]}>
                <InputNumber style={{ width: '100%' }} min={1} placeholder="10000" formatter={v => `${v}`.replace(/\B(?=(\d{3})+(?!\d))/g, ',')} />
              </Form.Item>
            </Col>
            <Col span={12}>
              <Form.Item name="department" label="Department">
                <Input placeholder="e.g. Operations" />
              </Form.Item>
            </Col>
          </Row>
          <Row gutter={16}>
            <Col span={12}>
              <Form.Item name="paidBy" label="Paid By">
                <Input placeholder="Name / Team" />
              </Form.Item>
            </Col>
            <Col span={12}>
              <Form.Item name="status" label="Status" initialValue="draft">
                <Select options={[
                  { label: 'Draft', value: 'draft' },
                  { label: 'Pending Approval', value: 'pending' },
                  { label: 'Approved', value: 'approved' },
                  { label: 'Paid', value: 'paid' },
                ]} />
              </Form.Item>
            </Col>
          </Row>
          <Form.Item name="description" label="Description">
            <Input.TextArea rows={2} placeholder="What was this expense for?" />
          </Form.Item>
          <div style={{ display: 'flex', justifyContent: 'flex-end', gap: 8 }}>
            <Button onClick={() => setExpenseModal(false)}>Cancel</Button>
            <Button type="primary" htmlType="submit" loading={busy}>Log Expense</Button>
          </div>
        </Form>
      </Modal>

    </div>
  );
}
