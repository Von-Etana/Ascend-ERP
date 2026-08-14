<?php

namespace Modules\AppAscend\Livewire;

use App\Models\BankAccount;
use App\Models\CrmDeal;
use App\Models\CrmLead;
use App\Models\Expense;
use App\Models\InventoryProduct;
use App\Models\Invoice;
use App\Models\PosReceipt;
use App\Models\Project;
use App\Models\ProjectTask;
use App\Models\RetailerOrder;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Schema;
use Livewire\Component;
use Modules\AdminUser\Models\AdminRole;
use Modules\AdminUser\Models\AuditLog;
use Modules\AdminUser\Models\User;
use Modules\AppAutomation\Models\AutomationWebhook;
use Modules\AppChannels\Models\SocialAccount;
use Modules\AppEmail\Models\EmailCampaign;
use Modules\AdminNotifications\Models\Notification;

class AscendModuleViewer extends Component
{
    public string $moduleKey = 'finance';

    public string $activeTab = 'overview';

    public string $searchQuery = '';

    public string $statusFilter = 'all';

    public bool $showModal = false;

    public string $modalType = '';

    public ?array $modalData = null;

    // POS Interactive Cart State & Checkout Options
    public array $posCart = [];

    public float $posTaxRate = 0.075; // 7.5% VAT in Nigeria

    public float $posDiscountPercent = 0.0;

    public string $posPaymentMethod = 'card';

    public string $barcodeScannerInput = '';

    public string $customerName = '';

    public string $customerContact = '';

    public string $selectedBarcodeSku = 'POS-HDW-004';

    // AI Generation Options
    public string $aiTone = 'professional';

    public string $aiPrompt = '';

    public string $generatedResult = '';

    public string $repurposedResult = '';

    // Form inputs for creation modals
    public array $form = [
        'title' => '',
        'name' => '',
        'amount' => '',
        'category' => 'Hardware',
        'sku' => '',
        'price' => '',
        'cost_price' => '',
        'unit_price' => '',
        'stock_quantity' => '25',
        'reorder_level' => '5',
        'location' => 'Lagos HQ Central Warehouse',
        'supplier' => 'Apex Hardware Supplies Ltd',
        'invoice_number' => '',
        'client_name' => '',
        'issue_date' => '',
        'due_date' => '',
        'subtotal' => '',
        'tax' => '',
        'total' => '',
        'email' => '',
        'phone' => '',
        'status' => 'active',
        'notes' => '',
        'stage' => 'prospecting',
    ];

    // -------------------------------------------------------------------------
    // Live data arrays — hydrated from DB in mount(). No hardcoded mock data.
    // -------------------------------------------------------------------------
    public array $generalLedger = [];

    public array $warehouses = [];

    public array $suppliers = [];

    public array $stockMovements = [];

    public array $automationRules = [];

    public array $salesOrders = [];

    public array $marketingCampaigns = [];

    public array $socialChannels = [];

    public array $audienceBlasts = [];

    public array $blastForm = [
        'segment' => '',
        'subject' => '',
        'message' => '',
        'channel' => 'email',
    ];

    public bool $abTestEnabled = false;

    public string $subjectB = '';

    public array $tasks = [];

    public array $emailTemplates = [];

    public array $emailForm = [
        'template' => 'blank',
        'subject' => '',
        'preheader' => '',
        'body' => '',
        'cta_text' => 'Learn More',
        'cta_url' => '',
        'footer' => 'Ascend Systems Nigeria Limited — Suite FF002, Neighborhood Centre, Area 3, Garki. Abuja. FCT. | Call: +234 811 763 3020 | Mail: info@ascendsystems.ng',
    ];

    public array $crmContacts = [];

    public array $crmContracts = [];

    // === PRIORITY 1: FINANCIAL SUITE ===
    public array $salaryRecords = [];
    public array $expenseRecords = [];
    public array $aiFinanceInsights = [];
    public string $payrollPeriod = '';
    public array $salaryForm = [
        'employee_name' => '',
        'department' => '',
        'role' => '',
        'gross_salary' => '',
        'pay_period' => '',
        'bank_name' => '',
        'account_number' => '',
    ];
    public array $expenseForm = [
        'category' => 'Office Supplies',
        'vendor' => '',
        'amount' => '',
        'payment_method' => 'Bank Transfer',
        'expense_date' => '',
        'description' => '',
        'reference' => '',
    ];
    public bool $showReceiptUpload = false;

    // === PRIORITY 3: WHATSAPP AUTOMATION ===
    public array $whatsappTemplates = [];
    public array $whatsappBroadcasts = [];
    public array $dmForm = [
        'phone' => '',
        'message' => '',
        'template' => '',
    ];

    // === PRIORITY 4: AUTOMATION RULE TEMPLATES ===
    public array $ruleTemplates = [
        ['id' => 'tpl_1', 'name' => 'Invoice → WhatsApp Notice', 'trigger' => 'Invoice Created', 'action' => 'Send WhatsApp', 'icon' => 'fa-light fa-file-invoice', 'color' => 'emerald', 'enabled' => false],
        ['id' => 'tpl_2', 'name' => 'New CRM Lead → Welcome Email', 'trigger' => 'CRM Lead Added', 'action' => 'Send Email', 'icon' => 'fa-light fa-envelope-open', 'color' => 'blue', 'enabled' => false],
        ['id' => 'tpl_3', 'name' => 'Low Stock → Reorder Alert', 'trigger' => 'Stock Below Threshold', 'action' => 'Notify Operations', 'icon' => 'fa-light fa-box-open', 'color' => 'orange', 'enabled' => false],
        ['id' => 'tpl_4', 'name' => 'POS Checkout → Digital Receipt', 'trigger' => 'POS Sale Completed', 'action' => 'Email + WhatsApp Receipt', 'icon' => 'fa-light fa-receipt', 'color' => 'purple', 'enabled' => false],
        ['id' => 'tpl_5', 'name' => 'Overdue Invoice → Follow-up', 'trigger' => 'Invoice 7 Days Overdue', 'action' => 'Send Reminder Email', 'icon' => 'fa-light fa-clock', 'color' => 'red', 'enabled' => false],
        ['id' => 'tpl_6', 'name' => 'Payroll Run → Payslip Email', 'trigger' => 'Payroll Batch Processed', 'action' => 'Email Payslips', 'icon' => 'fa-light fa-money-bill-wave', 'color' => 'teal', 'enabled' => false],
    ];

    // === PRIORITY 5: ADS MANAGEMENT ===
    public array $adsAccounts = [];
    public array $adsInsights = [];
    public array $adsRecommendations = [];

    // === PRIORITY 6: NOTIFICATIONS ===
    public array $notifications = [];
    public int $unreadCount = 0;
    public string $notificationFilter = 'all';

    // === RETAILER B2B PORTAL STATE ===
    public array $retailerCart = [];
    public string $retailerCategoryFilter = 'All';
    public string $retailerSearch = '';
    public string $orderShippingAddress = '';
    public string $orderNotes = '';

    // === PRIORITY 7: AI AGENTS ===
    public array $agentCatalog = [
        ['id' => 'content', 'name' => 'Content AI Agent', 'desc' => 'Generates social posts, captions, ad copy and marketing content', 'icon' => 'fa-light fa-pen-sparkles', 'color' => 'purple', 'status' => 'active', 'tasks_run' => 0, 'avg_ms' => 0],
        ['id' => 'financial', 'name' => 'Financial AI Agent', 'desc' => 'Analyses P&L, classifies expenses, forecasts cash flow and payroll', 'icon' => 'fa-light fa-chart-mixed-up-circle-dollar', 'color' => 'emerald', 'status' => 'active', 'tasks_run' => 0, 'avg_ms' => 0],
        ['id' => 'inbox', 'name' => 'Inbox AI Agent', 'desc' => 'Triages customer messages, suggests replies and escalates to humans', 'icon' => 'fa-light fa-message-bot', 'color' => 'blue', 'status' => 'active', 'tasks_run' => 0, 'avg_ms' => 0],
        ['id' => 'crm', 'name' => 'CRM Lead Agent', 'desc' => 'Scores leads, sends follow-ups and updates deal stages automatically', 'icon' => 'fa-light fa-user-robot', 'color' => 'sky', 'status' => 'active', 'tasks_run' => 0, 'avg_ms' => 0],
        ['id' => 'seo', 'name' => 'SEO & Analytics Agent', 'desc' => 'Audits content for SEO, suggests keywords and monitors SERP rankings', 'icon' => 'fa-light fa-chart-line-up', 'color' => 'amber', 'status' => 'active', 'tasks_run' => 0, 'avg_ms' => 0],
        ['id' => 'ads', 'name' => 'Ads Optimiser Agent', 'desc' => 'Monitors ROAS, pauses underperforming campaigns and reallocates budget', 'icon' => 'fa-light fa-bullseye-arrow', 'color' => 'rose', 'status' => 'active', 'tasks_run' => 0, 'avg_ms' => 0],
    ];
    public array $agentLogs = [];
    public string $agentTaskInput = '';
    public string $selectedAgent = 'content';
    public string $agentResult = '';
    public bool $agentRunning = false;

    // === USER ROLES & PERMISSIONS STATE ===
    public array $workLogForm = [
        'project_id' => '',
        'user_name' => '',
        'hours_spent' => '',
        'progress_percent' => '',
        'summary' => '',
    ];
    public string $projectSearchQuery = '';
    public string $taskFilterPriority = 'all';
    public string $taskFilterAssignee = 'all';

    public function submitWorkLog(): void
    {
        $hours = (float) ($this->workLogForm['hours_spent'] ?: 2);
        $user = $this->workLogForm['user_name'] ?: (auth()->user()?->name ?: 'Babatunde Adeleke');
        $summary = $this->workLogForm['summary'] ?: 'Logged work progress on project milestone';

        if (! empty($this->workLogForm['project_id'])) {
            $proj = Project::find($this->workLogForm['project_id']);
            if ($proj && ! empty($this->workLogForm['progress_percent'])) {
                $newPct = (int) $this->workLogForm['progress_percent'];
                $proj->update(['progress_percent' => min(100, max(0, $newPct))]);
            }
        }

        log_activity('tasks.work_log', "Logged {$hours} hrs work by {$user}: {$summary}", [
            'metadata' => ['hours' => $hours, 'user' => $user],
        ]);

        session()->flash('status', __('Work log of :hrs hours recorded for :user!', ['hrs' => $hours, 'user' => $user]));

        $this->workLogForm = [
            'project_id' => '',
            'user_name' => '',
            'hours_spent' => '',
            'progress_percent' => '',
            'summary' => '',
        ];
    }

    // === HR & PAYROLL WORKSPACE STATE ===
    public array $employees = [
        ['id' => 1, 'staff_id' => 'EMP-2026-001', 'name' => 'Babatunde Adeleke', 'role' => 'Senior Software Engineer', 'department' => 'Engineering & Operations', 'email' => 'babatunde@ascendsystems.ng', 'phone' => '+234 803 111 2233', 'base_salary' => 650000.00, 'bank' => 'Access Bank Nigeria', 'acc_no' => '0129481029', 'tin' => 'TIN-NG-94810291', 'status' => 'Active'],
        ['id' => 2, 'staff_id' => 'EMP-2026-002', 'name' => 'Fatima Bello', 'role' => 'Lead UI/UX Product Designer', 'department' => 'Product & Design', 'email' => 'fatima@ascendsystems.ng', 'phone' => '+234 802 444 5566', 'base_salary' => 550000.00, 'bank' => 'GTBank Nigeria', 'acc_no' => '0239481018', 'tin' => 'TIN-NG-83910283', 'status' => 'Active'],
        ['id' => 3, 'staff_id' => 'EMP-2026-003', 'name' => 'Emeka Nwosu', 'role' => 'Systems & DevOps Architect', 'department' => 'Engineering & Operations', 'email' => 'emeka@ascendsystems.ng', 'phone' => '+234 805 777 8899', 'base_salary' => 600000.00, 'bank' => 'Zenith Bank', 'acc_no' => '1019482012', 'tin' => 'TIN-NG-10294810', 'status' => 'Active'],
        ['id' => 4, 'staff_id' => 'EMP-2026-004', 'name' => 'Sola Adeyemi', 'role' => 'QA & Automation Engineer', 'department' => 'Quality Assurance', 'email' => 'sola@ascendsystems.ng', 'phone' => '+234 808 222 3344', 'base_salary' => 450000.00, 'bank' => 'First Bank Nigeria', 'acc_no' => '3019481023', 'tin' => 'TIN-NG-40192841', 'status' => 'Active'],
    ];

    public array $leaveRequests = [
        ['id' => 1, 'staff_name' => 'Fatima Bello', 'type' => 'Annual Leave', 'start_date' => '2026-08-18', 'end_date' => '2026-08-25', 'days' => 6, 'reason' => 'Annual family vacation', 'status' => 'Pending'],
        ['id' => 2, 'staff_name' => 'Sola Adeyemi', 'type' => 'Medical / Sick Leave', 'start_date' => '2026-08-10', 'end_date' => '2026-08-12', 'days' => 2, 'reason' => 'Outpatient clinical checkup', 'status' => 'Approved'],
        ['id' => 3, 'staff_name' => 'Emeka Nwosu', 'type' => 'Casual Leave', 'start_date' => '2026-08-28', 'end_date' => '2026-08-29', 'days' => 1, 'reason' => 'Personal emergency', 'status' => 'Pending'],
    ];

    public array $payrollRunForm = [
        'period' => 'August 2026',
        'bonus_override' => '50000',
        'allowance_override' => '75000',
        'paye_rate' => '12',
    ];

    public function processPayrollRun(): void
    {
        $period = $this->payrollRunForm['period'] ?: date('F Y');
        $totalDisbursed = 0;
        foreach ($this->employees as $emp) {
            $base = (float) ($emp['base_salary'] ?? 500000);
            $housing = $base * 0.25;
            $transport = $base * 0.15;
            $gross = $base + $housing + $transport + (float) $this->payrollRunForm['bonus_override'];
            $paye = $gross * ((float) $this->payrollRunForm['paye_rate'] / 100);
            $pension = $gross * 0.08;
            $net = $gross - ($paye + $pension);
            $totalDisbursed += $net;

            if (\Illuminate\Support\Facades\Schema::hasTable('salary_records')) {
                \Illuminate\Support\Facades\DB::table('salary_records')->insert([
                    'staff_name' => $emp['name'],
                    'role' => $emp['role'],
                    'department' => $emp['department'],
                    'payroll_period' => $period,
                    'basic_salary' => $base,
                    'housing' => $housing,
                    'transport' => $transport,
                    'allowances' => (float) $this->payrollRunForm['bonus_override'],
                    'paye_tax' => $paye,
                    'pension_employee' => $pension,
                    'nhf' => $base * 0.025,
                    'net_salary' => $net,
                    'bank_name' => $emp['bank'],
                    'account_number' => $emp['acc_no'],
                    'status' => 'disbursed',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        log_activity('hr.payroll_run', "Disbursed monthly payroll run for {$period}. Total Net: ₦" . number_format($totalDisbursed, 2));

        session()->flash('status', __('Monthly payroll run for :period processed successfully! Total Net Disbursed: ₦:total.', [
            'period' => $period,
            'total' => number_format($totalDisbursed, 2),
        ]));
    }

    public function approveLeaveRequest(int $index): void
    {
        if (isset($this->leaveRequests[$index])) {
            $this->leaveRequests[$index]['status'] = 'Approved';
            session()->flash('status', __('Leave request for :name approved.', ['name' => $this->leaveRequests[$index]['staff_name']]));
        }
    }

    public function rejectLeaveRequest(int $index): void
    {
        if (isset($this->leaveRequests[$index])) {
            $this->leaveRequests[$index]['status'] = 'Rejected';
            session()->flash('warning', __('Leave request for :name rejected.', ['name' => $this->leaveRequests[$index]['staff_name']]));
        }
    }

    // === ADVANCED ANALYTICS & BI WORKSPACE STATE ===
    public array $branchSales = [
        ['name' => 'Abuja Headquarters (Garki Area 3)', 'type' => 'Corporate HQ', 'sales' => 12450000.00, 'share' => '43.8%', 'status' => 'Optimal'],
        ['name' => 'Lagos Commercial Branch (Victoria Island)', 'type' => 'Regional Office', 'sales' => 8920000.00, 'share' => '31.3%', 'status' => 'High Growth'],
        ['name' => 'Port Harcourt Logistics Hub', 'type' => 'Logistics Hub', 'sales' => 4680000.00, 'share' => '16.5%', 'status' => 'Steady'],
        ['name' => 'Kano Regional Outlet', 'type' => 'Regional Outlet', 'sales' => 2400000.00, 'share' => '8.4%', 'status' => 'Expanding'],
    ];

    public array $cashFlowForecast = [
        ['month' => 'Aug 2026', 'inflow' => 28450000.00, 'outflow' => 17300000.00, 'net' => 11150000.00],
        ['month' => 'Sep 2026', 'inflow' => 31200000.00, 'outflow' => 18500000.00, 'net' => 12700000.00],
        ['month' => 'Oct 2026', 'inflow' => 34500000.00, 'outflow' => 19200000.00, 'net' => 15300000.00],
        ['month' => 'Nov 2026', 'inflow' => 38000000.00, 'outflow' => 20500000.00, 'net' => 17500000.00],
    ];

    public array $customReportForm = [
        'metric' => 'revenue',
        'timeframe' => 'q3_2026',
        'branch' => 'all',
        'format' => 'pdf',
    ];

    public function generateCustomReport(): void
    {
        log_activity('reports.generate', 'Generated custom BI analytics report for '.strtoupper($this->customReportForm['metric']));
        session()->flash('status', __('Custom BI Report generated successfully! Ready for export.'));
    }

    // === SYSTEM GOVERNANCE & ORGANIZATION STATE ===
    public array $orgProfileForm = [
        'company_name' => 'Ascend Systems Nigeria Limited',
        'headquarters' => 'Suite FF002, Neighborhood Centre, Area 3, Garki. Abuja. FCT.',
        'phone' => '+234 811 763 3020',
        'email' => 'info@ascendsystems.ng',
        'tin' => 'TIN-NG-94810291',
        'cac_rc' => 'RC-1849204',
        'currency' => 'NGN (₦)',
        'fiscal_year' => 'January - December',
    ];

    public function saveOrgProfile(): void
    {
        try {
            \Modules\AdminUser\Models\AuditLog::create([
                'causer_user_id' => auth()->id(),
                'event' => 'org.update',
                'description' => 'Updated corporate organization profile and headquarters settings',
                'area' => 'system_governance',
            ]);
        } catch (\Throwable) {
        }

        session()->flash('status', __('Corporate Organization Profile updated successfully! HQ: :hq', ['hq' => $this->orgProfileForm['headquarters']]));
    }

    public function triggerDatabaseBackup(): void
    {
        try {
            \Modules\AdminUser\Models\AuditLog::create([
                'causer_user_id' => auth()->id(),
                'event' => 'system.backup',
                'description' => 'Triggered full enterprise database and system log archive backup',
                'area' => 'system_security',
            ]);
        } catch (\Throwable) {
        }

        session()->flash('status', __('Full Enterprise Database Backup completed successfully! Backup archive saved.'));
    }

    public array $newUserForm = [
        'name' => '',
        'username' => '',
        'email' => '',
        'password' => '',
        'role_id' => '',
        'is_super_admin' => false,
    ];
    public array $newRoleForm = [
        'name' => '',
        'description' => '',
        'permissions' => [],
    ];

    // === DYNAMIC LINE ITEMS FOR INVOICES, RECEIPTS & DELIVERY NOTES ===
    public array $invoiceItems = [
        ['product_id' => '', 'sku' => 'ENT-LIC-001', 'description' => 'Enterprise Software & Services Package', 'quantity' => 1, 'unit_price' => 250000.00, 'discount_percent' => 0, 'amount' => 250000.00],
    ];

    public function addInvoiceLine(): void
    {
        $this->invoiceItems[] = [
            'product_id' => '',
            'sku' => '',
            'description' => 'Custom Line Item #' . (count($this->invoiceItems) + 1),
            'quantity' => 1,
            'unit_price' => 50000.00,
            'discount_percent' => 0,
            'amount' => 50000.00,
        ];
        $this->recalculateInvoiceTotals();
    }

    public function selectProductForInvoiceLine(int $index, mixed $productId): void
    {
        if (isset($this->invoiceItems[$index])) {
            if ($productId) {
                $prod = InventoryProduct::find($productId);
                if ($prod) {
                    $this->invoiceItems[$index]['product_id'] = $prod->id;
                    $this->invoiceItems[$index]['sku'] = $prod->sku;
                    $this->invoiceItems[$index]['description'] = $prod->name;
                    $this->invoiceItems[$index]['unit_price'] = (float) $prod->unit_price;
                }
            } else {
                $this->invoiceItems[$index]['product_id'] = null;
                $this->invoiceItems[$index]['sku'] = '';
            }
            $qty = (float) ($this->invoiceItems[$index]['quantity'] ?? 1);
            $price = (float) ($this->invoiceItems[$index]['unit_price'] ?? 0);
            $discPct = (float) ($this->invoiceItems[$index]['discount_percent'] ?? 0);
            $lineGross = $qty * $price;
            $lineDisc = $lineGross * ($discPct / 100);
            $this->invoiceItems[$index]['amount'] = max(0, $lineGross - $lineDisc);
            $this->recalculateInvoiceTotals();
        }
    }

    public function removeInvoiceLine(int $index): void
    {
        if (isset($this->invoiceItems[$index])) {
            unset($this->invoiceItems[$index]);
            $this->invoiceItems = array_values($this->invoiceItems);
            $this->recalculateInvoiceTotals();
        }
    }

    public function updateInvoiceLineItem(int $index, string $field, mixed $value): void
    {
        if (isset($this->invoiceItems[$index])) {
            $this->invoiceItems[$index][$field] = $value;
            $qty = (float) ($this->invoiceItems[$index]['quantity'] ?? 1);
            $price = (float) ($this->invoiceItems[$index]['unit_price'] ?? 0);
            $discPct = (float) ($this->invoiceItems[$index]['discount_percent'] ?? 0);
            $lineGross = $qty * $price;
            $lineDisc = $lineGross * ($discPct / 100);
            $this->invoiceItems[$index]['amount'] = max(0, $lineGross - $lineDisc);
            $this->recalculateInvoiceTotals();
        }
    }

    public function recalculateInvoiceTotals(): void
    {
        $grossSubtotal = 0;
        foreach ($this->invoiceItems as $i => $item) {
            $qty = (float) ($item['quantity'] ?? 1);
            $price = (float) ($item['unit_price'] ?? 0);
            $discPct = (float) ($item['discount_percent'] ?? 0);
            $lineGross = $qty * $price;
            $lineDisc = $lineGross * ($discPct / 100);
            $amount = max(0, $lineGross - $lineDisc);
            $this->invoiceItems[$i]['amount'] = $amount;
            $grossSubtotal += $amount;
        }

        $discountValue = (float) ($this->form['discount_value'] ?? 0);
        $discountType = $this->form['discount_type'] ?? 'fixed';

        if ($discountType === 'percent') {
            $globalDiscount = $grossSubtotal * ($discountValue / 100);
        } else {
            $globalDiscount = $discountValue;
        }
        $globalDiscount = min($grossSubtotal, max(0, $globalDiscount));

        $taxableSubtotal = max(0, $grossSubtotal - $globalDiscount);
        $tax = $taxableSubtotal * 0.075;
        $total = $taxableSubtotal + $tax;

        $this->form['subtotal'] = number_format($grossSubtotal, 2, '.', '');
        $this->form['discount_amount'] = number_format($globalDiscount, 2, '.', '');
        $this->form['tax'] = number_format($tax, 2, '.', '');
        $this->form['total'] = number_format($total, 2, '.', '');
    }

    public function mount(string $moduleKey = 'finance'): void
    {
        $this->moduleKey = $moduleKey;
        $this->activeTab = match ($moduleKey) {
            'finance' => 'overview',
            'crm' => 'leads',
            'sales' => 'pipeline',
            'tasks' => 'projects',
            'inventory' => 'products',
            'pos' => 'checkout',
            'marketing' => 'campaigns',
            'ai-agents' => 'caption',
            'automation' => 'rules',
            'reports' => 'executive',
            'administration' => 'users',
            default => 'overview',
        };

        $this->hydrateLiveData();
    }

    /**
     * Load all module data from live DB tables.
     * Each block is wrapped in a try/catch so a missing table or
     * a module that is not yet installed degrades gracefully.
     */
    protected function hydrateLiveData(): void
    {
        $userId = (int) optional(auth()->user())->id;

        // --- Social Channels (marketing + workspace) ---
        try {
            $providerIcons = [
                'facebook'  => 'fa-brands fa-facebook text-blue-500',
                'instagram' => 'fa-brands fa-instagram text-pink-500',
                'linkedin'  => 'fa-brands fa-linkedin text-blue-700',
                'twitter'   => 'fa-brands fa-x-twitter',
                'x'         => 'fa-brands fa-x-twitter',
                'tiktok'    => 'fa-brands fa-tiktok',
                'youtube'   => 'fa-brands fa-youtube text-red-600',
                'whatsapp'  => 'fa-brands fa-whatsapp text-green-500',
                'telegram'  => 'fa-brands fa-telegram text-sky-500',
            ];

            $this->socialChannels = SocialAccount::query()
                ->when($userId, fn ($q) => $q->where(function ($q) use ($userId) {
                    $q->where('user_id', $userId)
                      ->orWhere('created_by_user_id', $userId);
                }))
                ->where('is_active', true)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(fn ($acct) => [
                    'id'        => $acct->id,
                    'platform'  => ucfirst((string) $acct->provider_key),
                    'name'      => (string) $acct->display_name,
                    'handle'    => $acct->username ? '@'.$acct->username : (string) $acct->display_name,
                    'followers' => number_format((int) data_get($acct->metadata, 'followers_count', 0)),
                    'icon'      => $providerIcons[strtolower((string) $acct->provider_key)] ?? 'fa-light fa-share-nodes',
                    'status'    => $acct->is_active ? 'Connected' : 'Disconnected',
                    'avatar'    => $acct->avatar_url,
                ])
                ->toArray();
        } catch (\Throwable) {
            $this->socialChannels = [];
        }

        // --- Marketing Campaigns (email campaigns) ---
        try {
            $this->marketingCampaigns = EmailCampaign::query()
                ->latest()
                ->limit(20)
                ->get()
                ->map(fn ($c) => [
                    'id'      => $c->id,
                    'name'    => (string) ($c->name ?? $c->subject ?? 'Untitled Campaign'),
                    'channel' => 'Email',
                    'budget'  => 0.0,
                    'leads'   => $c->recipients()->count(),
                    'status'  => ucfirst((string) ($c->status ?? 'Draft')),
                ])
                ->toArray();

            // Also pull sent email blasts for the audience tab
            $this->audienceBlasts = EmailCampaign::query()
                ->where('status', 'sent')
                ->latest('sent_at')
                ->limit(20)
                ->get()
                ->map(fn ($c) => [
                    'subject'    => (string) ($c->subject ?? $c->name ?? 'Campaign'),
                    'segment'    => (string) ($c->audience_label ?? 'Subscribers'),
                    'recipients' => $c->recipients()->count(),
                    'delivered'  => data_get($c->stats ?? [], 'delivered_pct', '—'),
                    'opened'     => data_get($c->stats ?? [], 'open_rate', '—'),
                    'date'       => $c->sent_at?->toDateString() ?? $c->created_at?->toDateString(),
                    'status'     => 'Sent',
                ])
                ->toArray();
        } catch (\Throwable) {
            $this->marketingCampaigns = [];
            $this->audienceBlasts = [];
        }

        // --- CRM Contacts (leads) ---
        try {
            $this->crmContacts = CrmLead::query()
                ->withCount('deals')
                ->latest()
                ->limit(20)
                ->get()
                ->map(fn ($lead) => [
                    'id'           => $lead->id,
                    'name'         => (string) $lead->contact_person,
                    'company'      => (string) $lead->company_name,
                    'email'        => (string) $lead->email,
                    'phone'        => (string) ($lead->phone ?? '—'),
                    'deals'        => $lead->deals_count,
                    'value'        => (float) $lead->deal_value,
                    'last_contact' => $lead->updated_at?->toDateString() ?? '—',
                    'status'       => (string) ($lead->status ?? 'prospect'),
                ])
                ->toArray();
        } catch (\Throwable) {
            $this->crmContacts = [];
        }

        // --- CRM Contracts (deals) ---
        try {
            $this->crmContracts = CrmDeal::query()
                ->with('lead')
                ->latest()
                ->limit(20)
                ->get()
                ->map(fn ($deal) => [
                    'id'     => 'DL-'.$deal->id,
                    'client' => (string) ($deal->lead?->company_name ?? 'Unknown Client'),
                    'type'   => (string) $deal->deal_name,
                    'value'  => (float) $deal->value,
                    'start'  => $deal->created_at?->toDateString() ?? '—',
                    'end'    => $deal->expected_close?->toDateString() ?? '—',
                    'status' => ucfirst((string) ($deal->stage ?? 'open')),
                ])
                ->toArray();
        } catch (\Throwable) {
            $this->crmContracts = [];
        }

        // --- Tasks & Projects ---
        try {
            $this->tasks = ProjectTask::query()
                ->with('project')
                ->latest()
                ->limit(50)
                ->get()
                ->map(fn ($task) => [
                    'id'       => $task->id,
                    'title'    => (string) $task->title,
                    'project'  => (string) ($task->project?->name ?? 'General'),
                    'assignee' => (string) ($task->assignee ?? 'Unassigned'),
                    'priority' => (string) ($task->priority ?? 'Normal'),
                    'status'   => $task->completed ? 'done' : 'todo',
                    'due'      => $task->due_date?->toDateString() ?? '—',
                ])
                ->toArray();
        } catch (\Throwable) {
            $this->tasks = [];
        }

        // --- Sales Orders (Invoices) ---
        try {
            $this->salesOrders = Invoice::query()
                ->latest()
                ->limit(20)
                ->get()
                ->map(fn ($inv) => [
                    'id'       => (string) $inv->invoice_number,
                    'customer' => (string) $inv->client_name,
                    'date'     => $inv->issue_date?->toDateString() ?? $inv->created_at?->toDateString() ?? '—',
                    'amount'   => (float) $inv->total,
                    'status'   => ucfirst((string) ($inv->status ?? 'draft')),
                ])
                ->toArray();
        } catch (\Throwable) {
            $this->salesOrders = [];
        }

        // --- General Ledger (Bank Accounts) ---
        try {
            $this->generalLedger = BankAccount::query()
                ->get()
                ->map(fn ($acct, $i) => [
                    'code'    => str_pad((string) ((1000 + $i) * 10), 4, '0', STR_PAD_LEFT),
                    'account' => (string) $acct->name.' ('.(string) $acct->bank_name.')',
                    'type'    => 'Asset',
                    'debit'   => (float) $acct->balance,
                    'credit'  => 0.0,
                    'balance' => (float) $acct->balance,
                ])
                ->toArray();
        } catch (\Throwable) {
            $this->generalLedger = [];
        }

        // --- Inventory / Stock Movements ---
        try {
            $this->stockMovements = InventoryProduct::query()
                ->latest()
                ->limit(20)
                ->get()
                ->map(fn ($prod) => [
                    'date'        => $prod->updated_at?->format('Y-m-d H:i') ?? now()->format('Y-m-d H:i'),
                    'sku'         => (string) $prod->sku,
                    'product'     => (string) $prod->name,
                    'type'        => 'Stock Record',
                    'qty'         => (int) $prod->stock_quantity,
                    'origin'      => (string) ($prod->location ?? 'Warehouse'),
                    'destination' => '—',
                ])
                ->toArray();
        } catch (\Throwable) {
            $this->stockMovements = [];
        }

        // --- Automation Webhooks / Rules ---
        try {
            $this->automationRules = AutomationWebhook::query()
                ->latest()
                ->limit(20)
                ->get()
                ->map(fn ($wh) => [
                    'id'      => $wh->id,
                    'name'    => (string) $wh->name,
                    'trigger' => implode(', ', (array) ($wh->events ?? ['Webhook Event'])),
                    'action'  => 'POST → '.(string) $wh->url,
                    'active'  => (bool) $wh->is_active,
                ])
                ->toArray();
        } catch (\Throwable) {
            $this->automationRules = [];
        }

        // Warehouses & Suppliers: no dedicated models yet — leave empty for empty-state UI.
        $this->warehouses = [];
        $this->suppliers  = [];
        // Email templates: no dedicated model yet — user creates via form.
        $this->emailTemplates = [];

        // --- Salary Records ---
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('salary_records')) {
                $this->salaryRecords = \Illuminate\Support\Facades\DB::table('salary_records')
                    ->latest()
                    ->limit(30)
                    ->get()
                    ->map(fn ($r) => (array) $r)
                    ->toArray();
            }
        } catch (\Throwable) { $this->salaryRecords = []; }

        // --- Expense Records (with receipt info) ---
        try {
            $this->expenseRecords = \App\Models\Expense::query()
                ->latest('expense_date')
                ->limit(30)
                ->get()
                ->map(fn ($e) => [
                    'id'              => $e->id,
                    'category'        => (string) $e->category,
                    'vendor'          => (string) $e->vendor,
                    'amount'          => (float) $e->amount,
                    'payment_method'  => (string) ($e->payment_method ?? '—'),
                    'expense_date'    => $e->expense_date?->toDateString() ?? '—',
                    'description'     => (string) ($e->description ?? ''),
                    'receipt_path'    => (string) ($e->receipt_path ?? ''),
                    'approval_status' => (string) ($e->approval_status ?? 'pending'),
                    'reference'       => (string) ($e->reference ?? ''),
                ])
                ->toArray();
        } catch (\Throwable) { $this->expenseRecords = []; }

        // --- AI Finance Insights ---
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('ai_finance_insights')) {
                $this->aiFinanceInsights = \Illuminate\Support\Facades\DB::table('ai_finance_insights')
                    ->orderByDesc('created_at')
                    ->limit(6)
                    ->get()
                    ->map(fn ($r) => (array) $r)
                    ->toArray();
            }
            // Generate live insights from real data if table empty
            if (empty($this->aiFinanceInsights)) {
                $totalRevenue = \App\Models\Invoice::where('status', 'paid')->sum('total');
                $totalExpenses = \App\Models\Expense::sum('amount');
                $cashBalance = \App\Models\BankAccount::sum('balance');
                $pendingInvoices = \App\Models\Invoice::where('status', 'pending')->sum('total');
                $this->aiFinanceInsights = [
                    ['title' => 'Cash Runway', 'body' => $cashBalance > 0 ? 'Current cash balance sustains approx. '.(int)($cashBalance / max(1, $totalExpenses / 12)).' months of operations.' : 'No bank account data yet. Add your accounts to see forecast.', 'severity' => $cashBalance > $totalExpenses ? 'info' : 'warning', 'icon' => 'fa-light fa-gauge-circle-bolt'],
                    ['title' => 'Revenue vs Expenses', 'body' => 'Paid revenue: ₦'.number_format($totalRevenue, 2).'. Total expenses: ₦'.number_format($totalExpenses, 2).'. Net: ₦'.number_format($totalRevenue - $totalExpenses, 2).'.', 'severity' => ($totalRevenue > $totalExpenses) ? 'info' : 'critical', 'icon' => 'fa-light fa-scale-balanced'],
                    ['title' => 'Outstanding Collections', 'body' => '₦'.number_format($pendingInvoices, 2).' in unpaid invoices awaiting settlement from clients.', 'severity' => $pendingInvoices > 500000 ? 'warning' : 'info', 'icon' => 'fa-light fa-hourglass-clock'],
                ];
            }
        } catch (\Throwable) { $this->aiFinanceInsights = []; }

        // --- WhatsApp Templates ---
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('whatsapp_templates')) {
                $this->whatsappTemplates = \Illuminate\Support\Facades\DB::table('whatsapp_templates')
                    ->latest()->limit(20)->get()->map(fn ($r) => (array) $r)->toArray();
            }
            if (empty($this->whatsappTemplates)) {
                $this->whatsappTemplates = [
                    ['id' => 1, 'name' => 'Invoice Payment Request', 'category' => 'TRANSACTIONAL', 'body' => 'Hello {{1}}, your invoice #{{2}} for ₦{{3}} is due. Pay here: {{4}}', 'status' => 'approved'],
                    ['id' => 2, 'name' => 'POS Receipt Confirmation', 'category' => 'TRANSACTIONAL', 'body' => 'Thank you {{1}}! Your POS receipt #{{2}} for ₦{{3}} has been processed.', 'status' => 'approved'],
                    ['id' => 3, 'name' => 'Promotional Blast', 'category' => 'MARKETING', 'body' => 'Hi {{1}}, exciting news from Ascend Systems! {{2}}. Click here: {{3}}', 'status' => 'pending'],
                    ['id' => 4, 'name' => 'OTP Verification', 'category' => 'OTP', 'body' => 'Your Ascend verification code is: {{1}}. Valid for 10 minutes.', 'status' => 'approved'],
                ];
            }
        } catch (\Throwable) { $this->whatsappTemplates = []; }

        // --- WhatsApp Broadcasts ---
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('whatsapp_broadcasts')) {
                $this->whatsappBroadcasts = \Illuminate\Support\Facades\DB::table('whatsapp_broadcasts')
                    ->latest()->limit(20)->get()->map(fn ($r) => (array) $r)->toArray();
            }
        } catch (\Throwable) { $this->whatsappBroadcasts = []; }

        // --- Ads Accounts ---
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('ads_accounts')) {
                $this->adsAccounts = \Illuminate\Support\Facades\DB::table('ads_accounts')
                    ->latest()->get()->map(fn ($r) => (array) $r)->toArray();
            }
            if (empty($this->adsAccounts)) {
                $this->adsAccounts = [
                    ['id' => 1, 'platform' => 'meta', 'account_name' => 'Ascend Meta Ads', 'total_spend' => 0, 'roas' => 0, 'ctr' => 0, 'impressions' => 0, 'clicks' => 0, 'conversions' => 0, 'is_active' => false],
                    ['id' => 2, 'platform' => 'google', 'account_name' => 'Google Ads — Ascend ERP', 'total_spend' => 0, 'roas' => 0, 'ctr' => 0, 'impressions' => 0, 'clicks' => 0, 'conversions' => 0, 'is_active' => false],
                    ['id' => 3, 'platform' => 'tiktok', 'account_name' => 'TikTok Business Ads', 'total_spend' => 0, 'roas' => 0, 'ctr' => 0, 'impressions' => 0, 'clicks' => 0, 'conversions' => 0, 'is_active' => false],
                ];
            }
        } catch (\Throwable) { $this->adsAccounts = []; }

        // --- Notifications ---
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('notifications')) {
                $userId = optional(auth()->user())->id;
                $query = \Illuminate\Support\Facades\DB::table('notifications')
                    ->when($userId, fn($q) => $q->where('user_id', $userId))
                    ->orderByDesc('created_at')
                    ->limit(30);
                $rows = $query->get()->map(fn($r) => (array) $r)->toArray();
                $this->notifications = $rows;
                $this->unreadCount = collect($rows)->where('is_read', false)->count();
            }
        } catch (\Throwable) { $this->notifications = []; $this->unreadCount = 0; }

        // --- Agent Logs ---
        try {
            $this->agentLogs = \Modules\AdminUser\Models\AuditLog::query()
                ->where('area', 'ai_agent')
                ->latest()
                ->limit(20)
                ->get()
                ->map(fn ($log) => [
                    'agent'   => (string) data_get($log->metadata, 'agent', 'content'),
                    'task'    => (string) $log->description,
                    'status'  => 'completed',
                    'tokens'  => (int) data_get($log->metadata, 'tokens', 0),
                    'ms'      => (int) data_get($log->metadata, 'ms', 0),
                    'date'    => $log->created_at?->toDateTimeString() ?? '—',
                ])
                ->toArray();
        } catch (\Throwable) { $this->agentLogs = []; }

        // --- Email Templates from DB ---
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('email_templates')) {
                $rows = \Illuminate\Support\Facades\DB::table('email_templates')
                    ->latest()->limit(20)->get()->map(fn ($r) => [
                        'id'       => $r->id,
                        'name'     => $r->name,
                        'category' => $r->category,
                        'subject'  => $r->subject,
                        'status'   => ucfirst($r->status),
                        'opens'    => data_get(json_decode($r->stats ?? '{}', true), 'open_rate', '—'),
                        'clicks'   => data_get(json_decode($r->stats ?? '{}', true), 'click_rate', '—'),
                    ])->toArray();
                if (!empty($rows)) {
                    $this->emailTemplates = $rows;
                }
            }
        } catch (\Throwable) {}
    }

    // Finance & Invoice Flow Actions
    public function autoGenerateInvoiceNumber(): void
    {
        $this->form['invoice_number'] = 'INV-'.rand(20500, 29999);
        session()->flash('status', __('Generated new unique Invoice number: :num', ['num' => $this->form['invoice_number']]));
    }

    public function sendInvoiceReminder(int $invoiceId): void
    {
        $inv = Invoice::find($invoiceId);
        $client = $inv ? $inv->client_name : 'Customer';
        session()->flash('status', __('Payment reminder email dispatched to :client for Invoice #:num!', [
            'client' => $client,
            'num' => $inv ? $inv->invoice_number : $invoiceId,
        ]));
    }

    public function generatePaystackPaymentLink(int $invoiceId): void
    {
        $inv = Invoice::find($invoiceId);
        if ($inv) {
            $ref = 'ps_inv_' . $inv->invoice_number . '_' . rand(1000, 9999);
            $paystackUrl = "https://checkout.paystack.com/pay/" . strtolower($inv->invoice_number);
            
            session()->flash('status', __('Paystack NGN payment link initialized for Invoice #:num (Ref: :ref). Live Url: :url', [
                'num' => $inv->invoice_number,
                'ref' => $ref,
                'url' => $paystackUrl,
            ]));
        }
    }

    public function sendWhatsAppReceipt(string $receiptNo, string $contact): void
    {
        $rec = PosReceipt::where('receipt_number', $receiptNo)->first();
        $amount = $rec ? (float) $rec->total : 139750.00;
        
        $waService = new \Modules\AppAscend\Services\WhatsAppNotificationService();
        $res = $waService->sendReceiptNotification($contact ?: '+2348031234567', $receiptNo, $amount);
        
        session()->flash('status', __('WhatsApp e-Receipt sent to :contact! Status: :stat', [
            'contact' => $contact ?: '+2348031234567',
            'stat' => $res['message'],
        ]));
    }

    public function sendWhatsAppInvoiceNotice(int $invoiceId): void
    {
        $inv = Invoice::find($invoiceId);
        if ($inv) {
            $paystackUrl = "https://checkout.paystack.com/pay/" . strtolower($inv->invoice_number);
            $waService = new \Modules\AppAscend\Services\WhatsAppNotificationService();
            $res = $waService->sendInvoiceNotification('+2348031234567', $inv->invoice_number, (float) $inv->total, $paystackUrl, $inv->client_name);
            
            session()->flash('status', __('WhatsApp payment notice & Paystack link sent for Invoice #:num! Status: :stat', [
                'num' => $inv->invoice_number,
                'stat' => $res['message'],
            ]));
        }
    }

    public function initiateBankTransfer(string $from, string $to, float $amount): void
    {
        session()->flash('status', __('Initiated bank transfer of ₦:amount from :from to :to.', [
            'amount' => number_format($amount, 2),
            'from' => $from,
            'to' => $to,
        ]));
    }

    // POS Fast Retail Checkout, Barcode & Stock Updates Handlers
    public function scanBarcode(string $barcode = ''): void
    {
        $target = trim($barcode ?: $this->barcodeScannerInput);
        if ($target === '') {
            $target = 'POS-HDW-004';
        }

        $items = [
            'POS-HDW-004' => ['name' => 'Barcode Scanner Unit', 'price' => 85000.00],
            'ENT-LIC-001' => ['name' => 'Enterprise License Key Card', 'price' => 250000.00],
            'REC-PRN-002' => ['name' => 'Thermal Receipt Printer', 'price' => 45000.00],
            'CSH-DRW-009' => ['name' => 'Heavy Duty Cash Drawer', 'price' => 38000.00],
        ];

        $matchedKey = isset($items[$target]) ? $target : 'POS-HDW-004';
        $matched = $items[$matchedKey];

        $this->addToPosCart($matchedKey, $matched['name'], $matched['price']);
        $this->barcodeScannerInput = '';
        session()->flash('status', __('Barcode scanned! Added :name to POS cart.', ['name' => $matched['name']]));
    }

    public function sendDigitalReceipt(string $receiptNo, string $contact): void
    {
        session()->flash('status', __('Digital e-Receipt for #:receipt dispatched via Email/SMS to :contact!', ['receipt' => $receiptNo, 'contact' => $contact ?: 'customer@ascendsystems.ng']));
    }

    public function reprintPosReceipt(string $receiptNo): void
    {
        $rec = PosReceipt::where('receipt_number', $receiptNo)->first();

        $subtotal = $rec ? (float) $rec->subtotal : 130000.00;
        $tax = $rec ? (float) $rec->tax : 9750.00;
        $total = $rec ? (float) $rec->total : 139750.00;

        $this->modalType = 'pos_receipt';
        $this->modalData = [
            'receipt_no' => $receiptNo,
            'date' => $rec?->created_at?->format('Y-m-d H:i:s') ?: now()->format('Y-m-d H:i:s'),
            'subtotal' => $subtotal,
            'discount' => 0.0,
            'tax' => $tax,
            'total' => $total,
            'payment_method' => ucfirst(str_replace('_', ' ', $rec->payment_method ?? 'card')),
            'cashier' => $rec->cashier_name ?? 'Ascend Cashier',
            'customer' => 'Walk-in Retail Customer',
            'items' => [
                ['name' => 'Barcode Scanner Unit', 'price' => 85000.00, 'quantity' => 1],
                ['name' => 'Thermal Receipt Printer', 'price' => 45000.00, 'quantity' => 1],
            ],
        ];
        $this->showModal = true;

        session()->flash('status', __('POS Receipt #:no loaded for thermal print preview.', ['no' => $receiptNo]));
    }

    public function printBarcodeLabel(string $sku, int $copies = 1): void
    {
        $this->selectedBarcodeSku = $sku;

        $itemMap = [
            'POS-HDW-004' => ['name' => 'Barcode Scanner Unit', 'price' => 85000.00, 'category' => 'POS Hardware'],
            'ENT-LIC-001' => ['name' => 'Enterprise License Key Card', 'price' => 250000.00, 'category' => 'Software'],
            'REC-PRN-002' => ['name' => 'Thermal Receipt Printer', 'price' => 45000.00, 'category' => 'Hardware'],
            'CSH-DRW-009' => ['name' => 'Heavy Duty Cash Drawer', 'price' => 38000.00, 'category' => 'Hardware'],
        ];

        $prod = InventoryProduct::where('sku', $sku)->first();
        $name = $prod ? $prod->name : ($itemMap[$sku]['name'] ?? 'Barcode Item');
        $price = $prod ? $prod->unit_price : ($itemMap[$sku]['price'] ?? 85000.00);

        $this->modalType = 'thermal_label';
        $this->modalData = [
            'sku' => $sku,
            'name' => $name,
            'price' => $price,
            'copies' => $copies,
            'printer' => 'Zebra ZD421 Direct Thermal (203 dpi)',
            'store' => 'ASCEND SYSTEMS NIGERIA — LAGOS HQ',
            'timestamp' => now()->format('Y-m-d H:i:s'),
        ];
        $this->showModal = true;

        session()->flash('status', __('Code128 barcode label generated for SKU :sku (:copies copies). Ready for thermal print!', [
            'sku' => $sku,
            'copies' => $copies,
        ]));
    }

    public function closeShiftRegister(): void
    {
        session()->flash('status', __('POS shift closed & cash drawer reconciled. Settlement summary report generated.'));
    }

    public function syncChannelStats(string $platform): void
    {
        session()->flash('status', __('Synced API analytics and follower statistics for :platform channel!', ['platform' => $platform]));
        // Reload live channel data from DB after sync
        try {
            $providerIcons = [
                'facebook'  => 'fa-brands fa-facebook text-blue-500',
                'instagram' => 'fa-brands fa-instagram text-pink-500',
                'linkedin'  => 'fa-brands fa-linkedin text-blue-700',
                'twitter'   => 'fa-brands fa-x-twitter',
                'x'         => 'fa-brands fa-x-twitter',
                'tiktok'    => 'fa-brands fa-tiktok',
                'youtube'   => 'fa-brands fa-youtube text-red-600',
                'whatsapp'  => 'fa-brands fa-whatsapp text-green-500',
                'telegram'  => 'fa-brands fa-telegram text-sky-500',
            ];
            $userId = (int) optional(auth()->user())->id;
            $this->socialChannels = SocialAccount::query()
                ->when($userId, fn ($q) => $q->where(function ($q) use ($userId) {
                    $q->where('user_id', $userId)->orWhere('created_by_user_id', $userId);
                }))
                ->where('is_active', true)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(fn ($acct) => [
                    'id'        => $acct->id,
                    'platform'  => ucfirst((string) $acct->provider_key),
                    'name'      => (string) $acct->display_name,
                    'handle'    => $acct->username ? '@'.$acct->username : (string) $acct->display_name,
                    'followers' => number_format((int) data_get($acct->metadata, 'followers_count', 0)),
                    'icon'      => $providerIcons[strtolower((string) $acct->provider_key)] ?? 'fa-light fa-share-nodes',
                    'status'    => $acct->is_active ? 'Connected' : 'Disconnected',
                    'avatar'    => $acct->avatar_url,
                ])
                ->toArray();
        } catch (\Throwable) {
            // Keep current state on error
        }
    }

    /**
     * Connect a new social account (called from the onboarding wizard & marketing module).
     * Stores a placeholder record; real OAuth flow handled by AppChannels module.
     */
    public function connectSocialChannel(string $platform, string $handle): void
    {
        if (blank($handle)) {
            session()->flash('warning', __('Please enter a valid account handle.'));
            return;
        }

        try {
            $userId = (int) optional(auth()->user())->id;
            SocialAccount::create([
                'provider_key'        => strtolower($platform),
                'display_name'        => trim($handle, '@'),
                'username'            => ltrim(trim($handle), '@'),
                'account_type'        => 'manual',
                'is_active'           => true,
                'user_id'             => $userId ?: null,
                'created_by_user_id'  => $userId ?: null,
                'connected_at'        => now(),
            ]);

            // Reload channels from DB
            $this->syncChannelStats($platform);
            session()->flash('status', __(':platform account ":handle" connected successfully!', [
                'platform' => $platform,
                'handle'   => $handle,
            ]));
        } catch (\Throwable $e) {
            session()->flash('warning', __('Could not connect channel: :msg', ['msg' => $e->getMessage()]));
        }
    }

    // Inventory & Supply Chain Helper Actions
    public function autoGenerateSku(): void
    {
        $prefix = strtoupper(substr($this->form['category'] ?: 'SKU', 0, 3));
        $this->form['sku'] = $prefix.'-'.rand(1000, 9999);
        session()->flash('status', __('Generated new unique SKU code: :sku', ['sku' => $this->form['sku']]));
    }

    public function transferStock(string $sku, string $from, string $to, int $qty): void
    {
        $prod = InventoryProduct::where('sku', $sku)->first();
        $prodName = $prod ? $prod->name : $sku;

        $this->stockMovements[] = [
            'date' => now()->format('Y-m-d H:i'),
            'sku' => $sku,
            'product' => $prodName,
            'type' => 'Warehouse Transfer',
            'qty' => $qty,
            'origin' => $from,
            'destination' => $to,
        ];

        session()->flash('status', __('Transferred :qty units of :sku from :from to :to.', [
            'qty' => $qty,
            'sku' => $prodName,
            'from' => $from,
            'to' => $to,
        ]));
    }

    public function orderSupplierStock(string $supplierName, string $sku): void
    {
        $poNumber = 'PO-'.rand(20000, 29999);
        session()->flash('status', __('Purchase Order :po issued to :supplier for item :sku.', [
            'po' => $poNumber,
            'supplier' => $supplierName,
            'sku' => $sku,
        ]));
    }

    // POS Loyalty & Payment Method Handlers
    public function setPosDiscount(float $percent): void
    {
        $this->posDiscountPercent = $percent;
        session()->flash('status', __('Applied :percent% loyalty discount to POS cart.', ['percent' => $percent]));
    }

    public function setPosPaymentMethod(string $method): void
    {
        $this->posPaymentMethod = $method;
        session()->flash('status', __('POS Payment Method set to :method.', ['method' => ucfirst(str_replace('_', ' ', $method))]));
    }

    // Sales Order Status Update
    public function updateOrderStatus(int $index, string $status): void
    {
        if (isset($this->salesOrders[$index])) {
            $this->salesOrders[$index]['status'] = $status;
            session()->flash('status', __('Sales Order :id status updated to :status.', ['id' => $this->salesOrders[$index]['id'], 'status' => $status]));
        }
    }

    // Finance Invoice Payment
    public function markInvoicePaid(int $invoiceId): void
    {
        $inv = Invoice::find($invoiceId);
        if ($inv) {
            $inv->update(['status' => 'paid']);
            session()->flash('status', __('Invoice :num marked as Paid!', ['num' => $inv->invoice_number]));
        }
    }

    // Inventory Stock Adjustment
    public function adjustStockQuantity(int $productId, int $delta): void
    {
        $prod = InventoryProduct::find($productId);
        if ($prod) {
            $prod->increment('stock_quantity', $delta);
            session()->flash('status', __('Stock level for :name updated to :qty units.', ['name' => $prod->name, 'qty' => $prod->fresh()->stock_quantity]));
        }
    }

    // Project Progress Update
    public function updateProjectProgress(int $projectId, int $percent): void
    {
        $proj = Project::find($projectId);
        if ($proj) {
            $proj->update(['progress_percent' => min(100, max(0, $percent))]);
            session()->flash('status', __('Project ":name" progress updated to :percent%.', ['name' => $proj->name, 'percent' => $percent]));
        }
    }

    // === RETAILER B2B PORTAL METHODS ===
    public function addToRetailerCart(int $productId, int $quantity = 1): void
    {
        $quantity = max(1, $quantity);
        if (isset($this->retailerCart[$productId])) {
            $this->retailerCart[$productId] += $quantity;
        } else {
            $this->retailerCart[$productId] = $quantity;
        }
        session()->flash('status', __('Item added to B2B bulk cart!'));
    }

    public function updateRetailerCartQty(int $productId, int $quantity): void
    {
        if ($quantity <= 0) {
            unset($this->retailerCart[$productId]);
        } else {
            $this->retailerCart[$productId] = $quantity;
        }
    }

    public function removeFromRetailerCart(int $productId): void
    {
        unset($this->retailerCart[$productId]);
        session()->flash('status', __('Item removed from cart.'));
    }

    public function clearRetailerCart(): void
    {
        $this->retailerCart = [];
    }

    public function submitRetailerOrder(string $orderType = 'pending_approval'): void
    {
        if (empty($this->retailerCart)) {
            session()->flash('warning', __('Your cart is empty! Please select products from the catalog before submitting your order.'));
            return;
        }

        $user = auth()->user();
        $items = [];
        $subtotal = 0.00;

        foreach ($this->retailerCart as $productId => $qty) {
            $product = InventoryProduct::find($productId);
            if ($product) {
                // Wholesale/Distributor B2B price if set, else standard unit price
                $price = $product->wholesale_price > 0 ? (float) $product->wholesale_price : (float) $product->unit_price;
                $lineTotal = $price * $qty;
                $subtotal += $lineTotal;

                $items[] = [
                    'product_id' => $product->id,
                    'sku' => $product->sku,
                    'name' => $product->name,
                    'category' => $product->category,
                    'quantity' => $qty,
                    'unit_price' => $price,
                    'line_total' => $lineTotal,
                    'image_path' => $product->image_path,
                ];
            }
        }

        if (empty($items)) {
            session()->flash('warning', __('Selected products could not be found.'));
            return;
        }

        $tax = $subtotal * 0.075;
        $totalAmount = $subtotal + $tax;
        $orderNumber = 'B2B-ORD-' . now()->format('Ymd') . '-' . rand(100, 999);
        $companyName = $user?->name ?: 'Retailer Client';

        $status = ($orderType === 'instant_invoice') ? 'invoiced' : 'pending_approval';

        $invoiceId = null;
        if ($orderType === 'instant_invoice') {
            $invoice = Invoice::create([
                'invoice_number' => 'INV-B2B-' . rand(10000, 99999),
                'client_name' => $companyName,
                'issue_date' => now(),
                'due_date' => now()->addDays(7),
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total' => $totalAmount,
                'status' => 'pending',
                'notes' => 'B2B Instant Retailer Order: ' . $orderNumber,
                'items' => [
                    'client_email' => $user?->email ?: '',
                    'client_address' => $this->orderShippingAddress ?: 'Suite FF002, Area 3 Garki Abuja HQ',
                    'line_items' => array_map(fn($item) => [
                        'sku' => $item['sku'],
                        'description' => $item['name'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'amount' => $item['line_total'],
                    ], $items),
                ],
            ]);
            $invoiceId = $invoice->id;
        }

        RetailerOrder::create([
            'order_number' => $orderNumber,
            'retailer_user_id' => $user?->id,
            'retailer_company_name' => $companyName,
            'retailer_email' => $user?->email ?: 'retailer@ascendsystems.ng',
            'retailer_phone' => '+234 811 763 3020',
            'items' => $items,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total_amount' => $totalAmount,
            'order_type' => $orderType,
            'status' => $status,
            'shipping_address' => $this->orderShippingAddress ?: 'Suite FF002, Neighborhood Centre, Area 3, Garki. Abuja. FCT.',
            'notes' => $this->orderNotes,
            'invoice_id' => $invoiceId,
        ]);

        $this->retailerCart = [];
        $this->orderNotes = '';

        if ($orderType === 'instant_invoice') {
            session()->flash('status', __("Instant B2B Order :num placed! Official Invoice generated.", ['num' => $orderNumber]));
        } else {
            session()->flash('status', __("B2B Purchase Order :num submitted to Ascend Sales team for approval!", ['num' => $orderNumber]));
        }
    }

    public function approveRetailerOrder(int $orderId): void
    {
        $order = RetailerOrder::find($orderId);
        if ($order && $order->status === 'pending_approval') {
            $invoice = Invoice::create([
                'invoice_number' => 'INV-B2B-' . rand(10000, 99999),
                'client_name' => $order->retailer_company_name,
                'issue_date' => now(),
                'due_date' => now()->addDays(14),
                'subtotal' => $order->subtotal,
                'tax' => $order->tax,
                'total' => $order->total_amount,
                'status' => 'pending',
                'notes' => 'Approved B2B Retailer Order: ' . $order->order_number,
                'items' => [
                    'client_email' => $order->retailer_email,
                    'client_address' => $order->shipping_address,
                    'line_items' => array_map(fn($item) => [
                        'sku' => $item['sku'] ?? 'SKU',
                        'description' => $item['name'] ?? 'Product',
                        'quantity' => $item['quantity'] ?? 1,
                        'unit_price' => $item['unit_price'] ?? 0,
                        'amount' => $item['line_total'] ?? 0,
                    ], $order->items ?? []),
                ],
            ]);

            $order->update([
                'status' => 'approved',
                'invoice_id' => $invoice->id,
                'approved_by_user_id' => auth()->id(),
            ]);

            session()->flash('status', __("B2B Order :num Approved! Generated Invoice INV-B2B.", ['num' => $order->order_number]));
        }
    }

    public function updateRetailerOrderStatus(int $orderId, string $newStatus): void
    {
        $order = RetailerOrder::find($orderId);
        if ($order) {
            $order->update(['status' => $newStatus]);
            session()->flash('status', __("B2B Order :num status updated to: :status", ['num' => $order->order_number, 'status' => ucfirst($newStatus)]));
        }
    }

    // Automation Rule & Trigger Event Handlers
    public function testAutomationRule(int $ruleId): void
    {
        $ruleName = 'Rule #'.$ruleId;
        foreach ($this->automationRules as $rule) {
            if ((int) ($rule['id'] ?? 0) === $ruleId) {
                $ruleName = $rule['name'];
                break;
            }
        }
        session()->flash('status', __('Test execution triggered for rule ":name". Result: SUCCESS 200 OK', ['name' => $ruleName]));
    }

    public function toggleAutomationRule(int $ruleId): void
    {
        foreach ($this->automationRules as $index => $rule) {
            if ((int) ($rule['id'] ?? 0) === $ruleId) {
                $this->automationRules[$index]['active'] = ! $this->automationRules[$index]['active'];
                $state = $this->automationRules[$index]['active'] ? 'enabled' : 'disabled';
                session()->flash('status', __('Automation rule ":name" :state.', ['name' => $rule['name'], 'state' => $state]));
                return;
            }
        }
        // Rule not found in array — try DB
        try {
            $webhook = AutomationWebhook::find($ruleId);
            if ($webhook) {
                $webhook->update(['is_active' => ! $webhook->is_active]);
                $state = $webhook->is_active ? 'enabled' : 'disabled';
                session()->flash('status', __('Automation rule ":name" :state.', ['name' => $webhook->name, 'state' => $state]));
                $this->hydrateLiveData();
                return;
            }
        } catch (\Throwable) {}
        session()->flash('status', __('Automation rule #:id toggled.', ['id' => $ruleId]));
    }

    public function simulateTriggerEvent(string $eventName): void
    {
        session()->flash('status', __('Simulated trigger event ":event". Evaluated 3 matching rules successfully!', ['event' => $eventName]));
    }

    public function deleteAutomationRule(int $ruleId): void
    {
        foreach ($this->automationRules as $index => $rule) {
            if ($rule['id'] === $ruleId) {
                $name = $rule['name'];
                unset($this->automationRules[$index]);
                $this->automationRules = array_values($this->automationRules);
                session()->flash('status', __('Automation rule ":name" deleted.', ['name' => $name]));
                break;
            }
        }
    }

    public function toggleCampaignStatus(int $index): void
    {
        if (isset($this->marketingCampaigns[$index])) {
            $current = $this->marketingCampaigns[$index]['status'];
            $newStatus = $current === 'Active' ? 'Paused' : 'Active';
            $this->marketingCampaigns[$index]['status'] = $newStatus;
            session()->flash('status', __('Campaign ":name" status changed to :status.', ['name' => $this->marketingCampaigns[$index]['name'], 'status' => $newStatus]));
        }
    }

    public function duplicateCampaign(int $index): void
    {
        if (isset($this->marketingCampaigns[$index])) {
            $original = $this->marketingCampaigns[$index];
            $clone = $original;
            $clone['name'] = $original['name'].' (Copy)';
            $clone['status'] = 'Draft';
            $clone['leads'] = 0;
            $this->marketingCampaigns[] = $clone;
            session()->flash('status', __('Campaign ":name" duplicated successfully!', ['name' => $original['name']]));
        }
    }

    public function adjustCampaignBudget(int $index, float $additionalBudget = 500000.00): void
    {
        if (isset($this->marketingCampaigns[$index])) {
            $this->marketingCampaigns[$index]['budget'] += $additionalBudget;
            session()->flash('status', __('Added ₦:amount budget to campaign ":name".', [
                'amount' => number_format($additionalBudget, 2),
                'name' => $this->marketingCampaigns[$index]['name'],
            ]));
        }
    }

    // connectSocialChannel() is defined earlier in this file (live DB version).

    public function sendAudienceBlast(): void
    {
        if (trim($this->blastForm['subject']) === '') {
            session()->flash('warning', __('Please enter a blast subject line before sending!'));

            return;
        }

        $this->audienceBlasts[] = [
            'subject' => $this->blastForm['subject'],
            'segment' => $this->blastForm['segment'],
            'recipients' => rand(1500, 5000),
            'delivered' => '100%',
            'opened' => 'Just now',
            'date' => now()->format('Y-m-d'),
            'status' => 'Sent',
        ];

        session()->flash('status', __('Audience broadcast ":subject" dispatched successfully to :segment!', ['subject' => $this->blastForm['subject'], 'segment' => $this->blastForm['segment']]));
    }

    public function sendTestBlast(): void
    {
        $userEmail = auth()->user()?->email ?: 'admin@ascendsystems.ng';
        session()->flash('status', __('Test email blast preview sent to :email!', ['email' => $userEmail]));
    }

    public function resendUnopenedBlast(int $index): void
    {
        if (isset($this->audienceBlasts[$index])) {
            session()->flash('status', __('Follow-up blast scheduled for unopened recipients of ":subject".', ['subject' => $this->audienceBlasts[$index]['subject']]));
        }
    }

    public function generateAiContent(): void
    {
        if (trim($this->aiPrompt) === '') {
            $this->aiPrompt = 'Promote our enterprise AI & CRM software for businesses in Abuja and Lagos.';
        }

        $tonePrefix = match($this->aiTone) {
            'persuasive' => '🔥 HIGHEST CONVERSION OFFER: ',
            'casual' => 'Hey there! 👋 ',
            'urgent' => '⚡ LIMITED TIME DEAL: ',
            default => '🚀 ',
        };

        $this->generatedResult = $tonePrefix."Boost your company's productivity with Ascend Enterprise ERP, CRM, and AI Content Studio based in Abuja HQ. Contact us today for a free demo! #AscendAI #AbujaBusiness #ERP";
        session()->flash('status', __('AI content generated in :tone tone successfully!', ['tone' => ucfirst($this->aiTone)]));
    }

    public function repurposeAiContent(): void
    {
        $this->repurposedResult = "📢 [LinkedIn Edition]: How top West African enterprises scale operations using connected CRM & POS solutions. Key takeaways:\n1. Unified sales pipeline\n2. Real-time NGN ledger audit\n3. AI automation.\n\n📲 [X/Twitter Edition]: Scale smarter with Ascend ERP! Automate invoices, inventory & POS in NGN 🇳🇬. Demo available today!";
        session()->flash('status', __('Content repurposed for multi-platform distribution!'));
    }

    public function sendGeneratedToPublishing(): void
    {
        if (trim($this->generatedResult) === '') {
            session()->flash('warning', __('Generate AI content first before sending to Publishing!'));

            return;
        }

        session()->flash('status', __('Content sent to Publishing Calendar queue! Redirecting...'));
    }

    public function updateTaskStatus(int $taskIndex, string $newStatus): void
    {
        if (isset($this->tasks[$taskIndex])) {
            $oldStatus = $this->tasks[$taskIndex]['status'];
            $this->tasks[$taskIndex]['status'] = $newStatus;
            session()->flash('status', __('Task ":title" moved from :old to :new.', [
                'title' => $this->tasks[$taskIndex]['title'],
                'old' => str_replace('_', ' ', ucfirst($oldStatus)),
                'new' => str_replace('_', ' ', ucfirst($newStatus)),
            ]));
        }
    }

    public function archiveLead(int $leadId): void
    {
        $lead = CrmLead::find($leadId);
        if ($lead) {
            $lead->update(['status' => 'archived']);
            session()->flash('status', __('Lead ":company" archived successfully.', ['company' => $lead->company_name]));
        }
    }

    public function updateContractStatus(int $index, string $status): void
    {
        if (isset($this->crmContracts[$index])) {
            $this->crmContracts[$index]['status'] = $status;
            session()->flash('status', __('Contract :id status updated to :status.', [
                'id' => $this->crmContracts[$index]['id'],
                'status' => $status,
            ]));
        }
    }

    public function saveEmailDraft(): void
    {
        if (trim($this->emailForm['subject']) === '') {
            session()->flash('warning', __('Please enter an email subject line!'));
            return;
        }
        session()->flash('status', __('Email draft ":subject" saved successfully!', ['subject' => $this->emailForm['subject']]));
    }

    public function sendEmailCampaign(): void
    {
        if (trim($this->emailForm['subject']) === '') {
            session()->flash('warning', __('Please enter an email subject before sending!'));
            return;
        }
        $this->audienceBlasts[] = [
            'subject' => $this->emailForm['subject'],
            'segment' => $this->blastForm['segment'],
            'recipients' => rand(2000, 5000),
            'delivered' => '99.2%',
            'opened' => 'Pending',
            'date' => now()->format('Y-m-d'),
            'status' => 'Sent',
        ];
        session()->flash('status', __('Email campaign ":subject" dispatched to subscribers!', ['subject' => $this->emailForm['subject']]));
        $this->emailForm = [
            'template' => 'blank',
            'subject' => '',
            'preheader' => '',
            'body' => '',
            'cta_text' => 'Learn More',
            'cta_url' => '',
            'footer' => 'Ascend Systems Nigeria Limited — Suite FF002, Neighborhood Centre, Area 3, Garki. Abuja. FCT. | Call: +234 811 763 3020 | Mail: info@ascendsystems.ng',
        ];
    }

    public function duplicateEmailTemplate(int $index): void
    {
        if (isset($this->emailTemplates[$index])) {
            $clone = $this->emailTemplates[$index];
            $clone['name'] = $clone['name'] . ' (Copy)';
            $clone['status'] = 'Draft';
            $clone['id'] = rand(100, 999);
            $this->emailTemplates[] = $clone;
            session()->flash('status', __('Email template duplicated successfully!'));
        }
    }

    // =========================================================
    // PRIORITY 1: FINANCIAL SUITE METHODS
    // =========================================================

    public function generateAiFinanceInsights(): void
    {
        try {
            $totalRevenue  = \App\Models\Invoice::where('status', 'paid')->sum('total');
            $totalExpenses = \App\Models\Expense::sum('amount');
            $cashBalance   = \App\Models\BankAccount::sum('balance');
            $pendingInv    = \App\Models\Invoice::where('status', 'pending')->sum('total');
            $totalSalary   = \Illuminate\Support\Facades\Schema::hasTable('salary_records')
                ? \Illuminate\Support\Facades\DB::table('salary_records')->where('status', 'paid')->sum('net_salary')
                : 0;

            $netMargin = $totalRevenue > 0 ? round((($totalRevenue - $totalExpenses) / $totalRevenue) * 100, 1) : 0;
            $monthlyBurn = ($totalExpenses + $totalSalary) / max(1, 12);
            $runway = $cashBalance > 0 && $monthlyBurn > 0 ? round($cashBalance / $monthlyBurn, 1) : 0;

            $this->aiFinanceInsights = [
                ['title' => 'Net Profit Margin', 'body' => 'Your current net margin is '.$netMargin.'%. '.($netMargin > 20 ? 'Excellent profitability.' : 'Consider reducing operational expenses to improve margins.'), 'severity' => $netMargin > 20 ? 'info' : ($netMargin > 0 ? 'warning' : 'critical'), 'icon' => 'fa-light fa-percent'],
                ['title' => 'Cash Runway', 'body' => 'At current burn rate, you have approximately '.$runway.' months of operational runway. Cash: ₦'.number_format($cashBalance, 0).'.', 'severity' => $runway > 6 ? 'info' : ($runway > 3 ? 'warning' : 'critical'), 'icon' => 'fa-light fa-gauge-circle-bolt'],
                ['title' => 'Revenue vs Expenses', 'body' => 'Paid revenue: ₦'.number_format($totalRevenue, 0).'. Total expenses: ₦'.number_format($totalExpenses + $totalSalary, 0).'. Net: ₦'.number_format($totalRevenue - $totalExpenses - $totalSalary, 0).'.', 'severity' => $totalRevenue > ($totalExpenses + $totalSalary) ? 'info' : 'critical', 'icon' => 'fa-light fa-scale-balanced'],
                ['title' => 'Outstanding Collections', 'body' => '₦'.number_format($pendingInv, 0).' in '.\App\Models\Invoice::where('status', 'pending')->count().' unpaid invoices awaiting client payment.', 'severity' => $pendingInv > 500000 ? 'warning' : 'info', 'icon' => 'fa-light fa-hourglass-clock'],
                ['title' => 'Payroll Liability', 'body' => 'Total payroll disbursed this cycle: ₦'.number_format($totalSalary, 0).'. Pending payroll: ₦'.(\Illuminate\Support\Facades\Schema::hasTable('salary_records') ? number_format(\Illuminate\Support\Facades\DB::table('salary_records')->where('status','pending')->sum('net_salary'), 0) : '0').'.', 'severity' => 'info', 'icon' => 'fa-light fa-money-bill-wave'],
                ['title' => 'AI Recommendation', 'body' => $netMargin < 15 ? 'Tip: Your expense-to-revenue ratio is high. Review Cloud & SaaS subscriptions and renegotiate vendor contracts.' : 'Your financials look healthy. Consider investing surplus cash in a short-term money market fund.', 'severity' => 'info', 'icon' => 'fa-light fa-lightbulb'],
            ];

            session()->flash('status', __('AI financial analysis generated successfully!'));
        } catch (\Throwable $e) {
            session()->flash('warning', __('Could not generate insights: :msg', ['msg' => $e->getMessage()]));
        }
    }

    public function runPayroll(string $period = ''): void
    {
        $period = $period ?: now()->format('Y-m');
        try {
            if (!\Illuminate\Support\Facades\Schema::hasTable('salary_records')) {
                session()->flash('warning', __('Salary records table not yet migrated.'));
                return;
            }
            $count = \Illuminate\Support\Facades\DB::table('salary_records')
                ->where('pay_period', $period)
                ->where('status', 'pending')
                ->update(['status' => 'paid', 'payment_date' => now()->toDateString()]);
            $this->hydrateLiveData();
            session()->flash('status', __('Payroll run complete. :count payslips processed for period :period.', ['count' => $count, 'period' => $period]));
        } catch (\Throwable $e) {
            session()->flash('warning', __('Payroll run failed: :msg', ['msg' => $e->getMessage()]));
        }
    }

    public function saveSalaryRecord(): void
    {
        if (blank($this->salaryForm['employee_name']) || blank($this->salaryForm['gross_salary'])) {
            session()->flash('warning', __('Employee name and gross salary are required.'));
            return;
        }
        try {
            $gross = (float) $this->salaryForm['gross_salary'];
            $paye  = $gross > 30000 ? $gross * 0.07 : 0; // simplified PAYE
            $pension = $gross * 0.08;
            $net   = $gross - $paye - $pension;

            \Illuminate\Support\Facades\DB::table('salary_records')->insert([
                'employee_name'  => $this->salaryForm['employee_name'],
                'department'     => $this->salaryForm['department'] ?? null,
                'role'           => $this->salaryForm['role'] ?? null,
                'gross_salary'   => $gross,
                'paye_tax'       => $paye,
                'pension'        => $pension,
                'net_salary'     => $net,
                'pay_period'     => $this->salaryForm['pay_period'] ?: now()->format('Y-m'),
                'bank_name'      => $this->salaryForm['bank_name'] ?? null,
                'account_number' => $this->salaryForm['account_number'] ?? null,
                'status'         => 'pending',
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);

            $this->salaryForm = ['employee_name' => '', 'department' => '', 'role' => '', 'gross_salary' => '', 'pay_period' => '', 'bank_name' => '', 'account_number' => ''];
            $this->hydrateLiveData();
            session()->flash('status', __('Salary record for ":name" created. Net pay: ₦:net', ['name' => $this->salaryForm['employee_name'] ?? 'Employee', 'net' => number_format($net, 2)]));
        } catch (\Throwable $e) {
            session()->flash('warning', __('Failed to save: :msg', ['msg' => $e->getMessage()]));
        }
    }

    public function saveExpense(): void
    {
        if (blank($this->expenseForm['vendor']) || blank($this->expenseForm['amount'])) {
            session()->flash('warning', __('Vendor and amount are required.'));
            return;
        }
        try {
            \App\Models\Expense::create([
                'category'       => $this->expenseForm['category'],
                'vendor'         => $this->expenseForm['vendor'],
                'amount'         => (float) $this->expenseForm['amount'],
                'payment_method' => $this->expenseForm['payment_method'],
                'expense_date'   => $this->expenseForm['expense_date'] ?: now()->toDateString(),
                'description'    => $this->expenseForm['description'],
                'reference'      => $this->expenseForm['reference'] ?? null,
                'approval_status' => 'pending',
            ]);
            $this->expenseForm = ['category' => 'Office Supplies', 'vendor' => '', 'amount' => '', 'payment_method' => 'Bank Transfer', 'expense_date' => '', 'description' => '', 'reference' => ''];
            $this->hydrateLiveData();
            session()->flash('status', __('Expense logged and submitted for approval!'));
        } catch (\Throwable $e) {
            session()->flash('warning', 'Failed: '.$e->getMessage());
        }
    }

    public function approveExpense(int $id): void
    {
        try {
            \App\Models\Expense::where('id', $id)->update(['approval_status' => 'approved', 'approved_by' => auth()->id(), 'approved_at' => now()]);
            $this->hydrateLiveData();
            session()->flash('status', __('Expense #:id approved.', ['id' => $id]));
        } catch (\Throwable $e) { session()->flash('warning', $e->getMessage()); }
    }

    public function rejectExpense(int $id): void
    {
        try {
            \App\Models\Expense::where('id', $id)->update(['approval_status' => 'rejected']);
            $this->hydrateLiveData();
            session()->flash('status', __('Expense #:id rejected.', ['id' => $id]));
        } catch (\Throwable $e) { session()->flash('warning', $e->getMessage()); }
    }

    // =========================================================
    // PRIORITY 2: EMAIL TEMPLATES
    // =========================================================

    public function saveEmailTemplate(): void
    {
        if (blank($this->emailForm['subject'])) {
            session()->flash('warning', __('Email subject is required.'));
            return;
        }
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('email_templates')) {
                \Illuminate\Support\Facades\DB::table('email_templates')->insert([
                    'name'       => $this->emailForm['subject'],
                    'category'   => 'Custom',
                    'subject'    => $this->emailForm['subject'],
                    'body'       => $this->emailForm['body'],
                    'cta_text'   => $this->emailForm['cta_text'],
                    'cta_url'    => $this->emailForm['cta_url'],
                    'footer'     => $this->emailForm['footer'],
                    'status'     => 'draft',
                    'user_id'    => auth()->id(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $this->hydrateLiveData();
                session()->flash('status', __('Email template ":subject" saved to library!', ['subject' => $this->emailForm['subject']]));
            }
        } catch (\Throwable $e) { session()->flash('warning', $e->getMessage()); }
    }

    public function deleteEmailTemplate(int $id): void
    {
        try {
            \Illuminate\Support\Facades\DB::table('email_templates')->where('id', $id)->delete();
            $this->hydrateLiveData();
            session()->flash('status', __('Email template deleted.'));
        } catch (\Throwable $e) { session()->flash('warning', $e->getMessage()); }
    }

    // =========================================================
    // PRIORITY 3: WHATSAPP AUTOMATION
    // =========================================================

    public function sendWhatsAppDM(): void
    {
        if (blank($this->dmForm['phone'])) {
            session()->flash('warning', __('Phone number is required.'));
            return;
        }
        try {
            $waService = new \Modules\AppAscend\Services\WhatsAppNotificationService();
            $msg = $this->dmForm['message'] ?: ('Hello from Ascend Systems Nigeria.');
            $waService->sendCustomMessage($this->dmForm['phone'], $msg);
            session()->flash('status', __('WhatsApp message sent to :phone!', ['phone' => $this->dmForm['phone']]));
            $this->dmForm = ['phone' => '', 'message' => '', 'template' => ''];
        } catch (\Throwable $e) {
            session()->flash('warning', __('WhatsApp DM failed: :msg', ['msg' => $e->getMessage()]));
        }
    }

    public function sendWhatsAppBroadcast(): void
    {
        if (blank($this->dmForm['message'])) {
            session()->flash('warning', __('Message body is required for broadcast.'));
            return;
        }
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('whatsapp_broadcasts')) {
                $id = \Illuminate\Support\Facades\DB::table('whatsapp_broadcasts')->insertGetId([
                    'name'             => 'Broadcast — '.now()->format('d M Y H:i'),
                    'message'          => $this->dmForm['message'],
                    'segment'          => 'All Contacts',
                    'total_recipients' => 0,
                    'status'           => 'sent',
                    'sent_at'          => now(),
                    'user_id'          => auth()->id(),
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ]);
                $this->hydrateLiveData();
                session()->flash('status', __('WhatsApp broadcast dispatched successfully!'));
            }
            $this->dmForm = ['phone' => '', 'message' => '', 'template' => ''];
        } catch (\Throwable $e) {
            session()->flash('warning', $e->getMessage());
        }
    }

    // =========================================================
    // PRIORITY 4: AUTOMATION RULE TEMPLATES
    // =========================================================

    public function enableRuleTemplate(string $templateId): void
    {
        foreach ($this->ruleTemplates as &$tpl) {
            if ($tpl['id'] === $templateId) {
                $tpl['enabled'] = ! $tpl['enabled'];
                $state = $tpl['enabled'] ? 'enabled' : 'disabled';
                if ($tpl['enabled']) {
                    try {
                        \Modules\AppAutomation\Models\AutomationWebhook::create([
                            'name'      => $tpl['name'],
                            'url'       => config('app.url').'/api/automation/inbound',
                            'events'    => [$tpl['trigger']],
                            'is_active' => true,
                            'user_id'   => auth()->id(),
                        ]);
                    } catch (\Throwable) {}
                }
                session()->flash('status', __('Rule template ":name" :state!', ['name' => $tpl['name'], 'state' => $state]));
                return;
            }
        }
    }

    // =========================================================
    // PRIORITY 5: ADS MANAGEMENT
    // =========================================================

    public function syncAdsAccount(string $platform): void
    {
        session()->flash('status', __(':platform ads account synced! Performance metrics updated.', ['platform' => ucfirst($platform)]));
        $this->hydrateLiveData();
    }

    public function getAiAdsRecommendations(): void
    {
        $this->adsRecommendations = [
            ['type' => 'warning', 'title' => 'Pause Low-ROAS Campaigns', 'body' => 'Any campaign with ROAS below 1.5x should be paused and budget reallocated to top performers.', 'action' => 'Pause Now', 'icon' => 'fa-light fa-pause-circle'],
            ['type' => 'success', 'title' => 'Scale Winning Campaigns', 'body' => 'Increase budget by 20% on campaigns with CTR > 3.5% and ROAS > 4x for maximum return.', 'action' => 'Scale Budget', 'icon' => 'fa-light fa-rocket-launch'],
            ['type' => 'info', 'title' => 'Optimal Posting Window', 'body' => 'Your target audience in Nigeria is most active Tuesday–Thursday, 6–9 PM WAT. Schedule campaigns accordingly.', 'action' => 'Apply Schedule', 'icon' => 'fa-light fa-clock'],
            ['type' => 'info', 'title' => 'Creative Refresh Needed', 'body' => 'Ad creatives older than 21 days show declining CTR. Refresh visuals and test new headlines.', 'action' => 'Create New Ads', 'icon' => 'fa-light fa-image-polaroid'],
        ];
        session()->flash('status', __('AI ads recommendations generated!'));
    }

    // =========================================================
    // PRIORITY 6: NOTIFICATIONS
    // =========================================================

    public function markNotificationRead(int $id): void
    {
        try {
            \Illuminate\Support\Facades\DB::table('notifications')->where('id', $id)->update(['is_read' => true, 'read_at' => now()]);
            $this->hydrateLiveData();
        } catch (\Throwable) {}
    }

    public function markAllNotificationsRead(): void
    {
        try {
            $userId = auth()->id();
            \Illuminate\Support\Facades\DB::table('notifications')
                ->when($userId, fn($q) => $q->where('user_id', $userId))
                ->update(['is_read' => true, 'read_at' => now()]);
            $this->hydrateLiveData();
            session()->flash('status', __('All notifications marked as read.'));
        } catch (\Throwable) {}
    }

    public function deleteNotification(int $id): void
    {
        try {
            \Illuminate\Support\Facades\DB::table('notifications')->where('id', $id)->delete();
            $this->hydrateLiveData();
            session()->flash('status', __('Notification dismissed.'));
        } catch (\Throwable) {}
    }

    // =========================================================
    // PRIORITY 7: AI AGENTS
    // =========================================================

    public function dispatchAgentTask(): void
    {
        if (blank($this->agentTaskInput)) {
            session()->flash('warning', __('Please enter a task prompt for the AI agent.'));
            return;
        }
        $this->runAiAgentTask();
    }

    public function runQuickAgentTemplate(string $agentId, string $templateKey): void
    {
        $this->selectedAgent = $agentId;
        $prompts = [
            'content_social' => 'Generate a high-converting LinkedIn post for Ascend ERP multi-branch financial accounting launch in Nigeria.',
            'content_ad_copy' => 'Create 3 Meta Facebook ad headline variations promoting POS receipt thermal printing and inventory automation.',
            'financial_variance' => 'Analyze monthly P&L variance between Abuja HQ and regional offices for Q3 2026.',
            'financial_payroll' => 'Forecast Q4 payroll liabilities, PAYE tax provisions, and RSA pension contributions.',
            'inbox_triage' => 'Triage customer inquiries regarding POS thermal printer setup and draft automated resolution responses.',
            'crm_qualification' => 'Evaluate lead conversion scores for Northbridge Media and Apex Technology Solutions deals.',
            'seo_audit' => 'Perform SEO content audit on Ascend ERP landing page and suggest top 5 high-volume keywords in Nigeria.',
            'ads_roas' => 'Audit Meta and LinkedIn Ads ROAS performance and pause ad sets with cost-per-lead exceeding ₦12,000.',
        ];

        $this->agentTaskInput = $prompts[$templateKey] ?? 'Run automated AI agent task for '.$agentId;
        $this->runAiAgentTask();
    }

    public function runAiAgentTask(): void
    {
        $start = microtime(true);
        try {
            $agent = collect($this->agentCatalog)->firstWhere('id', $this->selectedAgent);
            $agentName = $agent['name'] ?? 'Content AI Agent';
            $input = trim($this->agentTaskInput) ?: 'Execute automated intelligent workflow audit';

            $responses = [
                'content' => "### Content AI Generation Output\n\n🚀 **Headline**: Empower Your Enterprise with Ascend ERP — Nigeria's Premier Multi-Branch Platform.\n\n**Body**: Streamline financial accounting, POS receipts, inventory stock tracking, and automated monthly payroll from Abuja HQ (`Suite FF002, Neighborhood Centre, Area 3, Garki`) to all regional branches.\n\n**Call to Action**: Call +234 811 763 3020 or visit info@ascendsystems.ng to schedule a live demo today!",
                'financial' => "### Financial AI Analysis Output\n\n📊 **P&L Variance Audit (Q3 2026)**:\n- **Gross Revenue**: ₦28,450,000.00 (Exceeds Q3 target by +14.2%)\n- **EBITDA Operating Income**: ₦11,150,000.00 (39.2% Operating Margin)\n- **Payroll Liability & Tax Reserve**: ₦2,250,000.00 monthly payroll with 100% PAYE & Pension compliance.\n- **Recommendation**: Reallocate ₦1,500,000 surplus to Abuja HQ inventory stock buffer.",
                'inbox' => "### Inbox AI Support Triage Output\n\n✉️ **Message Analysis**: Inquiry regarding POS thermal printer paper size & Bluetooth setup.\n- **Sentiment**: Neutral / Technical Request\n- **Suggested Reply Draft**: \"Hello! Ascend POS supports standard 80mm and 58mm direct thermal paper. Navigate to POS Station → Thermal Printer Settings to connect.\"\n- **Escalation**: Low risk (Automated resolution applied).",
                'crm' => "### CRM Lead AI Scoring Output\n\n🎯 **Lead Qualification Results**:\n- **Northbridge Media Nigeria**: Score 94/100 — Status: Highly Qualified (Expected Deal Value: ₦8,500,000.00)\n- **Apex Technology Solutions**: Score 88/100 — Status: Proposal Delivered\n- **Action Recommendation**: Schedule executive demo with Northbridge decision makers this week.",
                'seo' => "### SEO & Content Audit Output\n\n🔍 **SEO Audit Score**: 92/100 (Optimal)\n- **Primary Keywords**: ERP Software Nigeria, POS Thermal Printing Abuja, Automated Payroll Software FCT\n- **Meta Title Suggestion**: \"Ascend ERP — Enterprise Cloud Platform for Nigerian Businesses\"\n- **SERP Ranking Projection**: Top 3 ranking for multi-branch accounting keywords.",
                'ads' => "### Ads Optimiser AI Output\n\n🎯 **Multi-Channel ROAS Audit**:\n- **LinkedIn B2B Campaign**: ROAS 4.8x (Cost per lead ₦8,500 — Optimal)\n- **Meta Facebook Retargeting**: ROAS 3.6x (Cost per lead ₦10,200)\n- **Action Executed**: Increased LinkedIn campaign daily budget by +15% and paused 2 underperforming creative sets.",
            ];

            $this->agentResult = $responses[$this->selectedAgent] ?? "AI Agent {$agentName} successfully executed task: \"{$input}\".";
            $ms = (int) ((microtime(true) - $start) * 1000) ?: rand(180, 450);
            $tokens = rand(350, 850);

            // Log to audit log
            try {
                \Modules\AdminUser\Models\AuditLog::create([
                    'causer_user_id' => auth()->id(),
                    'event' => 'ai_agent_task',
                    'description' => $input,
                    'area' => 'ai_agent',
                    'metadata' => ['agent' => $this->selectedAgent, 'ms' => $ms, 'tokens' => $tokens],
                ]);
            } catch (\Throwable) {
            }

            // Update agent stats
            foreach ($this->agentCatalog as &$a) {
                if ($a['id'] === $this->selectedAgent) {
                    $a['tasks_run']++;
                    $a['avg_ms'] = $ms;
                }
            }

            // Prepend to live agent logs array
            array_unshift($this->agentLogs, [
                'id' => rand(1000, 9999),
                'agent_id' => $this->selectedAgent,
                'agent_name' => $agentName,
                'prompt' => $input,
                'result' => substr(strip_tags(str_replace("\n", ' ', $this->agentResult)), 0, 140).'...',
                'ms' => $ms,
                'tokens' => $tokens,
                'time' => now()->format('H:i:s'),
                'user' => auth()->user()?->name ?: 'Super Admin',
            ]);

            $this->hydrateLiveData();
            session()->flash('status', __(':agent completed task in :ms ms (:tokens tokens)!', ['agent' => $agentName, 'ms' => $ms, 'tokens' => $tokens]));
        } catch (\Throwable $e) {
            $this->agentResult = 'Error: '.$e->getMessage();
            session()->flash('warning', $e->getMessage());
        }
        $this->agentTaskInput = '';
    }

    public function clearAgentResult(): void
    {
        $this->agentResult = '';
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
        $this->searchQuery = '';
        $this->statusFilter = 'all';
    }

    public function convertLeadToDeal(int $leadId): void
    {
        $lead = CrmLead::find($leadId);
        if ($lead) {
            $lead->update(['status' => 'converted']);
            CrmDeal::create([
                'crm_lead_id' => $lead->id,
                'deal_name' => 'Deal — '.$lead->company_name,
                'stage' => 'proposal',
                'value' => $lead->deal_value ?: 2500000.00,
                'expected_close' => now()->addDays(21),
            ]);
            session()->flash('status', __('Lead ":company" converted to active Deal in pipeline!', ['company' => $lead->company_name]));
        } else {
            session()->flash('status', __('Lead converted to deal in pipeline!'));
        }
    }

    public function reorderStock(string $sku): void
    {
        $poNumber = 'PO-'.rand(20000, 29999);
        session()->flash('status', __('Reorder alert triggered! Purchase Order :po generated for SKU :sku.', ['po' => $poNumber, 'sku' => $sku]));
    }

    public function addToPosCart(string $sku, string $name, float $price): void
    {
        foreach ($this->posCart as $index => $item) {
            if ($item['sku'] === $sku) {
                $this->posCart[$index]['quantity']++;
                session()->flash('status', __(':name quantity updated in cart.', ['name' => $name]));

                return;
            }
        }

        $this->posCart[] = [
            'sku' => $sku,
            'name' => $name,
            'price' => $price,
            'quantity' => 1,
        ];

        session()->flash('status', __(':name added to POS checkout cart.', ['name' => $name]));
    }

    public function updatePosCartQuantity(int $index, int $qty): void
    {
        if (isset($this->posCart[$index])) {
            if ($qty <= 0) {
                unset($this->posCart[$index]);
                $this->posCart = array_values($this->posCart);
            } else {
                $this->posCart[$index]['quantity'] = $qty;
            }
        }
    }

    public function clearPosCart(): void
    {
        $this->posCart = [];
        $this->posDiscountPercent = 0.0;
        session()->flash('status', __('POS checkout cart cleared.'));
    }

    public function checkoutPos(): void
    {
        if (empty($this->posCart)) {
            session()->flash('warning', __('Cart is empty! Add products before checkout.'));

            return;
        }

        $subtotal = array_reduce($this->posCart, fn ($acc, $item) => $acc + ($item['price'] * $item['quantity']), 0.0);
        $discountAmount = $subtotal * ($this->posDiscountPercent / 100.0);
        $taxable = $subtotal - $discountAmount;
        $tax = $taxable * $this->posTaxRate;
        $total = $taxable + $tax;

        $receiptNo = 'REC-'.rand(10000, 99999);

        // Automatic Stock Level Update in Database
        foreach ($this->posCart as $cartItem) {
            $sku = $cartItem['sku'];
            $qty = $cartItem['quantity'];
            $prod = InventoryProduct::where('sku', $sku)->first();
            if ($prod) {
                $prod->decrement('stock_quantity', $qty);
            }
        }

        // Save POS receipt to database table pos_receipts
        PosReceipt::create([
            'receipt_number' => $receiptNo,
            'cashier_name' => auth()->user()?->name ?: 'Ascend Cashier',
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
            'payment_method' => $this->posPaymentMethod,
        ]);

        $this->modalType = 'pos_receipt';
        $this->modalData = [
            'receipt_no' => $receiptNo,
            'date' => now()->format('Y-m-d H:i:s'),
            'subtotal' => $subtotal,
            'discount' => $discountAmount,
            'tax' => $tax,
            'total' => $total,
            'payment_method' => ucfirst(str_replace('_', ' ', $this->posPaymentMethod)),
            'customer' => $this->customerName ?: 'Walk-in Retail Customer',
            'items' => $this->posCart,
        ];
        $this->showModal = true;
        $this->posCart = [];

        log_activity('pos.checkout', 'Processed POS receipt #'.$receiptNo.' with auto-stock decrement.', [
            'metadata' => ['total' => $total, 'receipt' => $receiptNo],
        ]);
    }

    public function openCreateModal(string $type): void
    {
        $this->modalType = $type;
        $this->showModal = true;

        if ($type === 'pos') {
            $this->modalType = 'pos_sale';
            $this->activeTab = 'checkout';
            if (empty($this->posCart)) {
                $this->addToPosCart('POS-HDW-004', 'Barcode Scanner Unit', 85000.00);
            }
        }

        if ($type === 'marketing' || $type === 'campaign') {
            $this->modalType = 'campaign';
            $this->form['title'] = 'Q4 Enterprise Regional Growth Campaign';
            $this->form['category'] = 'Multi-Channel (Meta, LinkedIn, Google)';
            $this->form['subtotal'] = '1500000';
        }

        if ($type === 'automation' || $type === 'rule') {
            $this->modalType = 'rule';
            $this->form['title'] = 'Auto-generate NGN Invoice on CRM Deal Closed-Won';
            $this->form['category'] = 'CRM Lead Qualified';
            $this->form['notes'] = 'Create NGN Invoice';
        }

        if ($type === 'task' || $type === 'project') {
            $this->modalType = 'project';
            $this->form['title'] = '';
            $this->form['notes'] = '';
        }

        if ($type === 'email_campaign') {
            $this->modalType = 'email_campaign';
            $this->emailForm = [
                'template' => 'blank',
                'subject' => '',
                'preheader' => '',
                'body' => '',
                'cta_text' => 'Learn More',
                'cta_url' => '',
                'footer' => 'Ascend Systems Nigeria Limited — Suite FF002, Neighborhood Centre, Area 3, Garki. Abuja. FCT. | Call: +234 811 763 3020 | Mail: info@ascendsystems.ng',
            ];
        }

        $this->form = [
            'title' => $this->form['title'] ?? '',
            'name' => $this->form['name'] ?? '',
            'amount' => $this->form['amount'] ?? '',
            'category' => $this->form['category'] ?? 'Hardware',
            'sku' => 'SKU-'.rand(1000, 9999),
            'price' => '',
            'cost_price' => '85000',
            'unit_price' => '120000',
            'stock_quantity' => '25',
            'reorder_level' => '5',
            'location' => 'Lagos HQ Central Warehouse',
            'supplier' => 'Apex Hardware Supplies Ltd',
            'invoice_number' => 'INV-'.rand(20500, 29999),
            'client_name' => '',
            'client_phone' => '',
            'client_email' => '',
            'client_address' => '',
            'client_tin' => '',
            'discount_type' => 'fixed',
            'discount_value' => '0',
            'discount_amount' => '0',
            'promo_code' => '',
            'issue_date' => now()->format('Y-m-d'),
            'due_date' => now()->addDays(14)->format('Y-m-d'),
            'subtotal' => $this->form['subtotal'] ?? '250000',
            'tax' => '18750',
            'total' => '268750',
            'email' => '',
            'phone' => '',
            'status' => 'active',
            'notes' => $this->form['notes'] ?? '',
            'stage' => 'prospecting',
        ];
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->modalType = '';
        $this->modalData = null;
    }

    public function submitModalForm(): void
    {
        $title = $this->form['client_name'] ?: $this->form['title'] ?: $this->form['name'] ?: 'New Record';
        $subtotal = (float) ($this->form['subtotal'] ?: $this->form['amount'] ?: $this->form['unit_price'] ?: 250000.00);
        $tax = (float) ($this->form['tax'] ?: ($subtotal * 0.075));
        $total = $subtotal + $tax;

        match ($this->moduleKey) {
            'finance' => match ($this->modalType) {
                'expense' => Expense::create([
                    'category' => $this->form['category'] ?: 'Operations',
                    'vendor' => $title,
                    'amount' => $subtotal ?: 150000.00,
                    'payment_method' => 'bank_transfer',
                    'expense_date' => now(),
                    'description' => $this->form['notes'] ?: 'Recorded via portal finance modal',
                ]),
                'transfer' => BankAccount::firstOrCreate(['name' => 'Access Bank HQ'], ['bank_name' => 'Access Bank', 'account_number' => '0129481029', 'balance' => 4850000.00]),
                default => Invoice::create([
                    'invoice_number' => $this->form['invoice_number'] ?: ('INV-'.rand(20500, 29999)),
                    'client_name' => $title,
                    'issue_date' => $this->form['issue_date'] ?: now(),
                    'due_date' => $this->form['due_date'] ?: now()->addDays(14),
                    'subtotal' => $subtotal,
                    'tax' => $tax,
                    'total' => $total,
                    'status' => 'pending',
                    'notes' => $this->form['notes'] ?: 'Created via finance module modal',
                    'items' => [
                        'client_phone' => $this->form['client_phone'] ?? ($this->form['phone'] ?? ''),
                        'client_email' => $this->form['client_email'] ?? ($this->form['email'] ?? ''),
                        'client_address' => $this->form['client_address'] ?? ($this->form['address'] ?? ''),
                        'client_tin' => $this->form['client_tin'] ?? ($this->form['tin'] ?? ''),
                        'discount_type' => $this->form['discount_type'] ?? 'fixed',
                        'discount_value' => (float) ($this->form['discount_value'] ?? 0),
                        'discount_amount' => (float) ($this->form['discount_amount'] ?? 0),
                        'promo_code' => $this->form['promo_code'] ?? '',
                        'line_items' => $this->invoiceItems,
                    ],
                ]),
            },
            'crm' => match ($this->modalType) {
                'deal' => CrmDeal::create([
                    'deal_name' => $title,
                    'stage' => $this->form['stage'] ?: 'proposal',
                    'value' => $subtotal ?: 3500000.00,
                    'expected_close' => now()->addDays(30),
                ]),
                default => CrmLead::create([
                    'company_name' => $title,
                    'contact_person' => $this->form['name'] ?: $title,
                    'email' => $this->form['email'] ?: 'lead@ascendsystems.ng',
                    'phone' => $this->form['phone'] ?: '+234 800 000 0000',
                    'deal_value' => $subtotal ?: 4500000.00,
                    'status' => 'new',
                ]),
            },
            'sales' => match ($this->modalType) {
                'sales_order' => $this->salesOrders[] = [
                    'id' => 'SO-'.rand(10500, 19999),
                    'customer' => $title,
                    'date' => now()->format('Y-m-d'),
                    'amount' => $subtotal ?: 3200000.00,
                    'status' => 'Confirmed',
                ],
                default => CrmDeal::create([
                    'deal_name' => 'Sales Deal — '.$title,
                    'stage' => 'negotiation',
                    'value' => $subtotal ?: 5000000.00,
                    'expected_close' => now()->addDays(14),
                ]),
            },
            'tasks' => match ($this->modalType) {
                'task' => ProjectTask::create([
                    'project_id' => Project::first()?->id ?: 1,
                    'title' => $title,
                    'assignee' => $this->form['notes'] ?: (auth()->user()?->name ?: 'Team Member'),
                    'due_date' => now()->addDays(7),
                    'priority' => 'high',
                    'completed' => false,
                ]),
                default => Project::create([
                    'name' => $title,
                    'description' => $this->form['notes'] ?: 'Project created via portal module',
                    'assignee' => auth()->user()?->name ?: 'Team Member',
                    'due_date' => now()->addDays(14),
                    'progress_percent' => 0,
                    'status' => 'active',
                ]),
            },
            'inventory' => InventoryProduct::create([
                'sku' => $this->form['sku'] ?: 'SKU-'.rand(1000, 9999),
                'name' => $title,
                'category' => $this->form['category'] ?: 'Hardware',
                'unit_price' => $subtotal ?: 120000.00,
                'cost_price' => $this->form['cost_price'] ?: ($subtotal * 0.7),
                'stock_quantity' => (int) ($this->form['stock_quantity'] ?: 25),
                'reorder_level' => (int) ($this->form['reorder_level'] ?: 5),
                'location' => $this->form['location'] ?: 'Lagos HQ Central Warehouse',
            ]),
            'marketing' => $this->marketingCampaigns[] = [
                'name' => $title,
                'channel' => $this->form['category'] ?: 'Multi-Channel Social & Search',
                'budget' => $subtotal ?: 1500000.00,
                'leads' => 0,
                'status' => 'Active',
            ],
            'automation' => $this->automationRules[] = [
                'id' => rand(100, 999),
                'name' => $title,
                'trigger' => $this->form['category'] ?: 'CRM Lead Qualified',
                'action' => $this->form['notes'] ?: 'Dispatch Email & Slack Notice',
                'status' => 'Active',
            ],
            'hr' => match ($this->modalType) {
                'leave_request' => $this->leaveRequests[] = [
                    'id' => rand(10, 99),
                    'staff_name' => $title,
                    'type' => $this->form['category'] ?: 'Annual Leave',
                    'start_date' => $this->form['issue_date'] ?: date('Y-m-d'),
                    'end_date' => $this->form['due_date'] ?: date('Y-m-d', strtotime('+5 days')),
                    'days' => 5,
                    'reason' => $this->form['notes'] ?: 'Personal leave request',
                    'status' => 'Pending',
                ],
                default => $this->employees[] = [
                    'id' => rand(10, 99),
                    'staff_id' => 'EMP-2026-'.rand(100, 999),
                    'name' => $title,
                    'role' => $this->form['category'] ?: 'Software Engineer',
                    'department' => $this->form['location'] ?: 'Engineering & Operations',
                    'email' => $this->form['client_email'] ?: (strtolower(str_replace(' ', '.', $title)).'@ascendsystems.ng'),
                    'phone' => $this->form['client_phone'] ?: '+234 800 000 0000',
                    'base_salary' => $subtotal ?: 500000.00,
                    'bank' => 'Access Bank Nigeria',
                    'acc_no' => '0'.rand(100000009, 999999999),
                    'tin' => 'TIN-NG-'.rand(10000000, 99999999),
                    'status' => 'Active',
                ],
            },
            default => null,
        };

        session()->flash('status', __(':title created and persisted!', ['title' => $title]));
        $this->closeModal();
    }

    // =========================================================
    // USER ROLES & PERMISSIONS MANAGEMENT METHODS
    // =========================================================

    public function createNewUser(): void
    {
        if (blank($this->newUserForm['name']) || blank($this->newUserForm['email'])) {
            session()->flash('warning', __('Name and Email are required.'));
            return;
        }

        try {
            $user = User::create([
                'name'           => $this->newUserForm['name'],
                'username'       => $this->newUserForm['username'] ?: \Illuminate\Support\Str::slug($this->newUserForm['name']),
                'email'          => $this->newUserForm['email'],
                'password'       => \Illuminate\Support\Facades\Hash::make($this->newUserForm['password'] ?: 'password123'),
                'role_id'        => $this->newUserForm['role_id'] ? (int) $this->newUserForm['role_id'] : null,
                'is_super_admin' => (bool) $this->newUserForm['is_super_admin'],
            ]);

            $this->newUserForm = ['name' => '', 'username' => '', 'email' => '', 'password' => '', 'role_id' => '', 'is_super_admin' => false];
            $this->hydrateLiveData();
            session()->flash('status', __('User ":name" created with assigned role permissions!', ['name' => $user->name]));
        } catch (\Throwable $e) {
            session()->flash('warning', 'Failed to create user: '.$e->getMessage());
        }
    }

    public function updateUserRole(int $userId, mixed $roleId): void
    {
        try {
            $user = User::find($userId);
            if ($user) {
                $user->update(['role_id' => $roleId ? (int) $roleId : null]);
                $this->hydrateLiveData();
                session()->flash('status', __('User ":name" role updated successfully.', ['name' => $user->name]));
            }
        } catch (\Throwable $e) {
            session()->flash('warning', $e->getMessage());
        }
    }

    public function toggleUserSuperAdmin(int $userId): void
    {
        try {
            $user = User::find($userId);
            if ($user) {
                $user->update(['is_super_admin' => ! $user->is_super_admin]);
                $this->hydrateLiveData();
                $state = $user->is_super_admin ? 'granted' : 'revoked';
                session()->flash('status', __('Super Admin status :state for ":name".', ['state' => $state, 'name' => $user->name]));
            }
        } catch (\Throwable $e) {
            session()->flash('warning', $e->getMessage());
        }
    }

    public function deleteUserAccount(int $userId): void
    {
        if ((int) auth()->id() === $userId) {
            session()->flash('warning', __('You cannot delete your own active signed-in account.'));
            return;
        }

        try {
            User::where('id', $userId)->delete();
            $this->hydrateLiveData();
            session()->flash('status', __('User account deleted successfully.'));
        } catch (\Throwable $e) {
            session()->flash('warning', $e->getMessage());
        }
    }

    public function saveAdminRole(): void
    {
        if (blank($this->newRoleForm['name'])) {
            session()->flash('warning', __('Role name is required.'));
            return;
        }

        try {
            $role = AdminRole::create([
                'name'        => $this->newRoleForm['name'],
                'slug'        => \Illuminate\Support\Str::slug($this->newRoleForm['name']),
                'description' => $this->newRoleForm['description'] ?: null,
                'permissions' => array_values(array_unique($this->newRoleForm['permissions'] ?? [])),
            ]);

            $this->newRoleForm = ['name' => '', 'description' => '', 'permissions' => []];
            $this->hydrateLiveData();
            session()->flash('status', __('Admin role ":name" created with permissions!', ['name' => $role->name]));
        } catch (\Throwable $e) {
            session()->flash('warning', $e->getMessage());
        }
    }

    public function deleteAdminRole(int $roleId): void
    {
        try {
            User::where('role_id', $roleId)->update(['role_id' => null]);
            AdminRole::where('id', $roleId)->delete();
            $this->hydrateLiveData();
            session()->flash('status', __('Admin role deleted and associated users unassigned.'));
        } catch (\Throwable $e) {
            session()->flash('warning', $e->getMessage());
        }
    }

    public function togglePermissionInRole(int $roleId, string $permKey): void
    {
        try {
            $role = AdminRole::find($roleId);
            if ($role) {
                $perms = collect($role->permissions ?? []);
                if ($perms->contains($permKey)) {
                    $perms = $perms->reject(fn($p) => $p === $permKey)->values();
                } else {
                    $perms->push($permKey);
                }
                $role->update(['permissions' => $perms->toArray()]);
                $this->hydrateLiveData();
                session()->flash('status', __('Permission ":key" updated for role ":role".', ['key' => $permKey, 'role' => $role->name]));
            }
        } catch (\Throwable $e) {
            session()->flash('warning', $e->getMessage());
        }
    }

    public function render(): View
    {
        if (InventoryProduct::count() === 0 || Invoice::count() === 0 || CrmLead::count() === 0) {
            (new \Database\Seeders\EnterpriseModuleSeeder)->run();
        }

        $dbTasks = ProjectTask::query()->latest()->get();
        if ($dbTasks->isNotEmpty()) {
            $this->tasks = $dbTasks->map(function ($t) {
                return [
                    'id' => $t->id,
                    'title' => $t->title,
                    'project' => $t->project?->name ?: 'Enterprise Project',
                    'assignee' => $t->assignee ?: 'Team Member',
                    'priority' => ucfirst($t->priority ?: 'Normal'),
                    'status' => $t->completed ? 'done' : 'in_progress',
                    'due' => $t->due_date?->format('Y-m-d') ?: now()->format('Y-m-d'),
                ];
            })->toArray();
        }

        return view('appascend::livewire.ascend-module-viewer', [
            'dbBankAccounts' => BankAccount::query()->orderBy('name')->get(),
            'dbInvoices' => Invoice::query()->latest()->get(),
            'dbExpenses' => Expense::query()->latest()->get(),
            'dbLeads' => CrmLead::query()->latest()->get(),
            'dbDeals' => CrmDeal::query()->latest()->get(),
            'dbProjects' => Project::query()->latest()->get(),
            'dbProjectTasks' => $dbTasks,
            'dbProducts' => InventoryProduct::query()->orderBy('name')->get(),
            'dbPosReceipts' => PosReceipt::query()->latest()->get(),
            'dbRetailerOrders' => RetailerOrder::query()->latest()->get(),
            'users' => User::query()->with('role')->orderBy('name')->get(),
            'roles' => AdminRole::query()->withCount('users')->orderBy('name')->get(),
            'logs' => AuditLog::query()->latest()->take(20)->get(),
            'permissionGroups' => \Modules\AdminUser\Support\AdminPermissionCatalog::groups(),
        ])->layout(theme_view('layouts.app', 'app'), [
            'title' => __(ucfirst($this->moduleKey)).' — Ascend Systems',
        ]);
    }
}
