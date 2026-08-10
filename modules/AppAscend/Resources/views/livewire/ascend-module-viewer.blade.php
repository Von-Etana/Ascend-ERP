@php
    $accent = match ($moduleKey) {
        'finance' => '#059669',
        'inventory', 'pos' => '#ea580c',
        'crm', 'sales' => '#2563eb',
        'marketing' => '#7c3aed',
        'ai-agents' => '#9333ea',
        'tasks' => '#0284c7',
        'automation' => '#d97706',
        'reports' => '#0d9488',
        'administration' => '#1e293b',
        default => '#2563eb',
    };

    $headerBg = match ($moduleKey) {
        'finance' => 'linear-gradient(135deg, rgba(5,150,105,0.12) 0%, rgba(16,185,129,0.04) 100%)',
        'inventory', 'pos' => 'linear-gradient(135deg, rgba(234,88,12,0.12) 0%, rgba(249,115,22,0.04) 100%)',
        'crm', 'sales' => 'linear-gradient(135deg, rgba(37,99,235,0.12) 0%, rgba(59,130,246,0.04) 100%)',
        'marketing' => 'linear-gradient(135deg, rgba(124,58,237,0.12) 0%, rgba(139,92,246,0.04) 100%)',
        'ai-agents' => 'linear-gradient(135deg, rgba(147,51,234,0.12) 0%, rgba(168,85,247,0.04) 100%)',
        'tasks' => 'linear-gradient(135deg, rgba(2,132,199,0.12) 0%, rgba(14,165,233,0.04) 100%)',
        'automation' => 'linear-gradient(135deg, rgba(217,119,6,0.12) 0%, rgba(245,158,11,0.04) 100%)',
        'reports' => 'linear-gradient(135deg, rgba(13,148,136,0.12) 0%, rgba(20,184,166,0.04) 100%)',
        'administration' => 'linear-gradient(135deg, rgba(30,41,59,0.12) 0%, rgba(71,85,105,0.04) 100%)',
        default => 'linear-gradient(135deg, rgba(37,99,235,0.12) 0%, rgba(59,130,246,0.04) 100%)',
    };
@endphp

<div class="space-y-8" style="--ascend-accent: {{ $accent }};">
    <!-- Premium Module Glassmorphic Header Banner -->
    <div class="relative overflow-hidden rounded-3xl border border-slate-200/80 p-6 md:p-8 shadow-sm transition-all duration-300 dark:border-slate-800" style="background: {{ $headerBg }};">
        <div class="absolute -right-10 -top-10 h-64 w-64 rounded-full opacity-20 blur-3xl" style="background: {{ $accent }};"></div>

        <div class="relative z-10 flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex items-start sm:items-center gap-5">
                <span class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl text-2xl text-white shadow-xl shadow-blue-500/10 transition-transform duration-300 hover:scale-105" style="background: {{ $accent }};">
                    <i class="{{ match($moduleKey) {
                        'finance' => 'fa-light fa-circle-dollar',
                        'crm' => 'fa-light fa-users-line',
                        'sales' => 'fa-light fa-chart-line-up',
                        'tasks' => 'fa-light fa-list-check',
                        'inventory' => 'fa-light fa-boxes-stacked',
                        'pos' => 'fa-light fa-cash-register',
                        'marketing' => 'fa-light fa-bullhorn',
                        'ai-agents' => 'fa-light fa-sparkles',
                        'automation' => 'fa-light fa-bolt',
                        'reports' => 'fa-light fa-chart-mixed',
                        'administration' => 'fa-light fa-user-shield',
                        default => 'fa-light fa-layer-group',
                    } }}"></i>
                </span>
                <div>
                    <a href="{{ route('portal.dashboard') }}" wire:navigate class="mb-2 inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-slate-500 transition hover:text-slate-900 dark:hover:text-white">
                        <i class="fa-light fa-arrow-left"></i>{{ __('Workspace') }} &rarr; {{ __(ucfirst($moduleKey)) }}
                    </a>
                    <h1 class="text-3xl font-extrabold tracking-tight text-slate-950 dark:text-white">
                        {{ match($moduleKey) {
                            'finance' => __('Accounting & Finance Workspace'),
                            'crm' => __('CRM — Leads, Deals & Pipeline'),
                            'sales' => __('Sales Pipeline & Orders Hub'),
                            'tasks' => __('Project & Task Management'),
                            'inventory' => __('Inventory & Supply Chain Hub'),
                            'pos' => __('Point of Sale (POS) Terminal'),
                            'marketing' => __('Marketing & Social Campaigns'),
                            'ai-agents' => __('AI Content & Autonomous Agents'),
                            'automation' => __('Workflow Automation & Triggers'),
                            'reports' => __('Executive Business Intelligence'),
                            'administration' => __('User Management & Access Controls'),
                            default => __(ucfirst($moduleKey)),
                        } }}
                    </h1>
                    <p class="mt-1 max-w-2xl text-sm font-medium text-slate-600 dark:text-slate-300">
                        {{ match($moduleKey) {
                            'finance' => __('Comprehensive tools to record, track, and report all financial activities, ledgers, and cashflow in NGN.'),
                            'crm' => __('Manage your sales pipeline, client relationships, lead conversions, and contact history in one place.'),
                            'sales' => __('Track sales pipelines, confirm sales orders, manage quotes, and accelerate deal revenue.'),
                            'tasks' => __('Organize work, assign responsibilities, track project progress logs, and monitor productivity metrics.'),
                            'inventory' => __('Track product stock levels, warehouse availability, supplier management, and reorder alerts.'),
                            'pos' => __('Fast retail POS checkout, automated receipt printing, barcode management, and stock updates.'),
                            'marketing' => __('Multi-channel marketing campaigns, social account management, audience blasts, and ROAS analytics.'),
                            'ai-agents' => __('Specialist AI agents for copy generation, multi-platform repurposing, scheduling, and market strategy.'),
                            'automation' => __('Automate background tasks across CRM, Finance, POS, and Notifications with rule triggers.'),
                            'reports' => __('Unified business intelligence reporting across sales, finance, inventory valuation, and marketing.'),
                            'administration' => __('Control access, assign roles, set permissions for modules and security audit tracking.'),
                            default => __('Operational workspace capabilities.'),
                        } }}
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                @if (in_array($moduleKey, ['finance', 'crm', 'sales', 'inventory', 'reports'], true))
                    <a href="{{ route('portal.finance.export-csv') }}" class="inline-flex items-center gap-2 rounded-2xl border border-slate-200/90 bg-white/90 px-4 py-2.5 text-sm font-bold text-slate-700 shadow-sm backdrop-blur-md transition-all hover:bg-white hover:shadow dark:border-slate-800 dark:bg-slate-900/90 dark:text-slate-200">
                        <i class="fa-light fa-file-excel text-emerald-600"></i>
                        {{ __('Export CSV') }}
                    </a>
                @endif
                <button type="button" wire:click="openCreateModal('{{ $moduleKey === 'finance' ? 'invoice' : ($moduleKey === 'inventory' ? 'product' : $moduleKey) }}')" class="inline-flex items-center gap-2 rounded-2xl px-6 py-3 text-sm font-bold text-white shadow-lg shadow-blue-500/20 transition-all hover:scale-[1.02] hover:brightness-105 active:scale-95" style="background: {{ $accent }};">
                    <i class="fa-light fa-plus text-base"></i>
                    {{ match($moduleKey) {
                        'finance' => __('New Invoice / Expense'),
                        'crm' => __('Add Lead / Deal'),
                        'sales' => __('New Sales Order'),
                        'tasks' => __('Create Project'),
                        'inventory' => __('Add New Product SKU'),
                        'pos' => __('New POS Sale'),
                        'marketing' => __('New Campaign'),
                        'ai-agents' => __('Generate AI Post'),
                        'automation' => __('Add Rule'),
                        'reports' => __('Generate Report'),
                        'administration' => __('Add User / Role'),
                        default => __('New Record'),
                    } }}
                </button>
            </div>
        </div>
    </div>

    <!-- Status Toast Notifications -->
    @if (session('status'))
        <div class="flex items-center justify-between rounded-2xl border border-emerald-500/30 bg-emerald-500/10 px-5 py-3.5 text-sm font-bold text-emerald-600 shadow-xs dark:text-emerald-400">
            <div class="flex items-center gap-2">
                <i class="fa-light fa-circle-check text-base"></i>
                <span>{{ session('status') }}</span>
            </div>
            <button type="button" class="text-emerald-500 hover:text-emerald-700" onclick="this.parentElement.remove()"><i class="fa-light fa-xmark"></i></button>
        </div>
    @endif

    @if (session('warning'))
        <div class="flex items-center justify-between rounded-2xl border border-amber-500/30 bg-amber-500/10 px-5 py-3.5 text-sm font-bold text-amber-600 shadow-xs dark:text-amber-400">
            <div class="flex items-center gap-2">
                <i class="fa-light fa-triangle-exclamation text-base"></i>
                <span>{{ session('warning') }}</span>
            </div>
            <button type="button" class="text-amber-500 hover:text-amber-700" onclick="this.parentElement.remove()"><i class="fa-light fa-xmark"></i></button>
        </div>
    @endif

    <!-- Module Sub-Tabs Pill Navigation Bar -->
    <div class="rounded-2xl border border-slate-200/80 bg-slate-100/80 p-1.5 backdrop-blur-md dark:border-slate-800 dark:bg-slate-900/80">
        <div class="flex items-center gap-1.5 overflow-x-auto scrollbar-none">
            @php
                $subTabs = match($moduleKey) {
                    'finance' => [
                        'overview' => ['label' => 'Overview & Banking', 'icon' => 'fa-light fa-building-columns'],
                        'invoices' => ['label' => 'Invoices & Estimates', 'icon' => 'fa-light fa-file-invoice-dollar'],
                        'expenses' => ['label' => 'Expenses & Receipts', 'icon' => 'fa-light fa-receipt'],
                        'salary'   => ['label' => 'Payroll & Salary', 'icon' => 'fa-light fa-money-bill-wave'],
                        'ai_finance' => ['label' => 'AI Financial Intelligence', 'icon' => 'fa-light fa-brain-circuit'],
                        'ledger' => ['label' => 'General Ledger & Balance Sheet', 'icon' => 'fa-light fa-book-journal-whills'],
                        'reports' => ['label' => 'Profit & Loss Reports', 'icon' => 'fa-light fa-chart-pie'],
                    ],
                    'crm' => [
                        'leads' => ['label' => 'Leads Management', 'icon' => 'fa-light fa-user-plus'],
                        'deals' => ['label' => 'Deals Pipeline', 'icon' => 'fa-light fa-chart-kanban'],
                        'contacts' => ['label' => 'Customer Contacts', 'icon' => 'fa-light fa-address-book'],
                        'contracts' => ['label' => 'Contracts & Notes', 'icon' => 'fa-light fa-file-contract'],
                        'builder' => ['label' => 'Form Builder & Settings', 'icon' => 'fa-light fa-sliders'],
                    ],
                    'sales' => [
                        'pipeline' => ['label' => 'Deals Pipeline & Revenue', 'icon' => 'fa-light fa-chart-line-up'],
                        'orders' => ['label' => 'Sales Orders', 'icon' => 'fa-light fa-cart-shopping'],
                        'quotes' => ['label' => 'Price Quotes & Proposals', 'icon' => 'fa-light fa-file-signature'],
                        'invoices' => ['label' => 'Billing & Payments', 'icon' => 'fa-light fa-credit-card'],
                        'analytics' => ['label' => 'Sales Leaderboard', 'icon' => 'fa-light fa-trophy'],
                    ],
                    'tasks' => [
                        'projects' => ['label' => 'Projects Overview', 'icon' => 'fa-light fa-folder-tree'],
                        'assignments' => ['label' => 'Task Assignments', 'icon' => 'fa-light fa-list-check'],
                        'progress' => ['label' => 'Progress Logs', 'icon' => 'fa-light fa-timeline'],
                        'reports' => ['label' => 'Performance Metrics', 'icon' => 'fa-light fa-chart-waterfall'],
                    ],
                    'inventory' => [
                        'products' => ['label' => 'Products & Stock Levels', 'icon' => 'fa-light fa-box-archive'],
                        'stock' => ['label' => 'Stock Movement Audit', 'icon' => 'fa-light fa-arrows-repeat'],
                        'warehouses' => ['label' => 'Warehouse & Suppliers', 'icon' => 'fa-light fa-warehouse'],
                        'import' => ['label' => 'Import / Export CSV', 'icon' => 'fa-light fa-file-export'],
                    ],
                    'pos' => [
                        'checkout' => ['label' => 'POS Checkout Terminal', 'icon' => 'fa-light fa-cash-register'],
                        'receipts' => ['label' => 'Sales Receipts History', 'icon' => 'fa-light fa-receipt'],
                        'barcodes' => ['label' => 'Barcode & Labels', 'icon' => 'fa-light fa-barcode'],
                        'insights' => ['label' => 'POS Insights & Shift Logs', 'icon' => 'fa-light fa-chart-line'],
                    ],
                    'marketing' => [
                        'campaigns' => ['label' => 'Marketing Campaigns', 'icon' => 'fa-light fa-bullhorn'],
                        'social' => ['label' => 'Social Channels', 'icon' => 'fa-light fa-share-nodes'],
                        'blasts' => ['label' => 'Audience Blasts', 'icon' => 'fa-light fa-paper-plane'],
                        'email' => ['label' => 'Email Marketing Workspace', 'icon' => 'fa-light fa-envelope-open-text'],
                        'whatsapp' => ['label' => 'WhatsApp Automation & DM', 'icon' => 'fa-brands fa-whatsapp'],
                        'ads' => ['label' => 'Ads Sync & AI Insights', 'icon' => 'fa-light fa-bullseye-arrow'],
                        'analytics' => ['label' => 'Campaign Analytics', 'icon' => 'fa-light fa-chart-pie'],
                    ],
                    'ai-agents' => [
                        'agents' => ['label' => 'AI Agent Fleet & Logs', 'icon' => 'fa-light fa-sparkles'],
                        'caption' => ['label' => 'Caption Generator', 'icon' => 'fa-light fa-pen-sparkles'],
                        'repurpose' => ['label' => 'Content Repurposer', 'icon' => 'fa-light fa-repeat'],
                        'planner' => ['label' => 'Content Planner', 'icon' => 'fa-light fa-calendar-star'],
                        'besttime' => ['label' => 'Best Time Scheduler', 'icon' => 'fa-light fa-clock-rotate-left'],
                    ],
                    'automation' => [
                        'rules' => ['label' => 'Active Automation Rules', 'icon' => 'fa-light fa-bolt'],
                        'templates' => ['label' => 'Quick Rule Templates', 'icon' => 'fa-light fa-wand-magic-sparkles'],
                        'triggers' => ['label' => 'Triggers & Webhooks', 'icon' => 'fa-light fa-link'],
                        'logs' => ['label' => 'Execution Logs', 'icon' => 'fa-light fa-receipt'],
                    ],
                    'reports' => [
                        'executive' => ['label' => 'Executive Summary', 'icon' => 'fa-light fa-chart-mixed'],
                        'financial' => ['label' => 'Financial Reports', 'icon' => 'fa-light fa-file-invoice-dollar'],
                        'inventory' => ['label' => 'Inventory Valuation', 'icon' => 'fa-light fa-boxes-stacked'],
                    ],
                    'administration' => [
                        'users' => ['label' => 'User Management', 'icon' => 'fa-light fa-users'],
                        'notifications' => ['label' => 'Notifications Centre', 'icon' => 'fa-light fa-bell'],
                        'roles' => ['label' => 'Roles & Permissions', 'icon' => 'fa-light fa-user-shield'],
                        'clients' => ['label' => 'Client & External Access', 'icon' => 'fa-light fa-user-group'],
                        'security' => ['label' => 'Security & Audit Logs', 'icon' => 'fa-light fa-shield-check'],
                    ],
                    default => ['overview' => ['label' => 'Overview', 'icon' => 'fa-light fa-border-all']],
                };
            @endphp

            @foreach ($subTabs as $tabKey => $tab)
                <button
                    type="button"
                    wire:click="setTab('{{ $tabKey }}')"
                    class="flex items-center gap-2.5 rounded-xl px-4 py-2.5 text-xs font-bold whitespace-nowrap transition-all duration-200 {{ $activeTab === $tabKey ? 'bg-white text-slate-950 shadow-md shadow-slate-200/50 dark:bg-slate-800 dark:text-white dark:shadow-none' : 'text-slate-600 hover:bg-white/60 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800/50 dark:hover:text-white' }}"
                    @if ($activeTab === $tabKey) style="color: {{ $accent }};" @endif
                >
                    <i class="{{ $tab['icon'] }} text-sm"></i>
                    {{ __($tab['label']) }}
                </button>
            @endforeach
            @if ($moduleKey === 'finance')
                <button type="button" wire:click="setTab('salary')" class="flex items-center gap-2.5 rounded-xl px-4 py-2.5 text-xs font-bold whitespace-nowrap transition-all duration-200 {{ $activeTab === 'salary' ? 'bg-white text-slate-950 shadow-md shadow-slate-200/50 dark:bg-slate-800 dark:text-white dark:shadow-none' : 'text-slate-600 hover:bg-white/60 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800/50 dark:hover:text-white' }}" {{ $activeTab === 'salary' ? 'style="color: '.$accent.';"' : '' }}>
                    <i class="fa-light fa-money-bill-wave text-sm"></i>
                    {{ __('Payroll & Salary') }}
                </button>
                <button type="button" wire:click="setTab('ai_finance')" class="flex items-center gap-2.5 rounded-xl px-4 py-2.5 text-xs font-bold whitespace-nowrap transition-all duration-200 {{ $activeTab === 'ai_finance' ? 'bg-white text-slate-950 shadow-md shadow-slate-200/50 dark:bg-slate-800 dark:text-white dark:shadow-none' : 'text-slate-600 hover:bg-white/60 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800/50 dark:hover:text-white' }}" {{ $activeTab === 'ai_finance' ? 'style="color: '.$accent.';"' : '' }}>
                    <i class="fa-light fa-sparkles text-sm"></i>
                    {{ __('AI Analysis') }}
                </button>
            @endif
        </div>
    </div>

    <!-- MODULE 1: ACCOUNTING & FINANCE ENHANCED USERFLOW -->
    @if ($moduleKey === 'finance')
        @if ($activeTab === 'overview')
            <div class="space-y-6">
                <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">{{ __('Total Revenue') }}</p>
                        <p class="mt-2 text-2xl font-black text-emerald-600">₦1,245,780.00</p>
                        <p class="mt-1 text-xs font-medium text-emerald-500"><i class="fa-light fa-arrow-trend-up mr-1"></i>+18.6% vs last month</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">{{ __('Operating Expenses') }}</p>
                        <p class="mt-2 text-2xl font-black text-rose-500">₦324,500.00</p>
                        <p class="mt-1 text-xs font-medium text-slate-400">Fixed & variable operational costs</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">{{ __('Net Profit Margin') }}</p>
                        <p class="mt-2 text-2xl font-black text-blue-600">₦921,280.00</p>
                        <p class="mt-1 text-xs font-medium text-blue-500">73.9% net margin</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">{{ __('Outstanding Bills') }}</p>
                        <p class="mt-2 text-2xl font-black text-amber-600">₦180,000.00</p>
                        <p class="mt-1 text-xs font-medium text-slate-400">2 pending vendor bills</p>
                    </div>
                </div>

                <!-- Banking Accounts & Liquid Reserves -->
                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                        <div>
                            <h2 class="text-lg font-bold text-slate-950 dark:text-white">{{ __('Banking Accounts & Liquid Reserves (NGN)') }}</h2>
                            <p class="text-sm text-slate-500">{{ __('Bank accounts, cash reserves, and inter-company liquidity transfers.') }}</p>
                        </div>
                        <button type="button" wire:click="initiateBankTransfer('Access Bank HQ', 'GTBank Operations', 500000.00)" class="rounded-xl border border-emerald-300 bg-emerald-50 px-4 py-2 text-xs font-bold text-emerald-700 hover:bg-emerald-100 dark:bg-emerald-950/40 dark:border-emerald-800 dark:text-emerald-300">
                            <i class="fa-light fa-arrow-right-arrow-left mr-1.5"></i>{{ __('Initiate Transfer') }}
                        </button>
                    </div>
                    <div class="mt-5 grid gap-4 md:grid-cols-3">
                        <div class="rounded-2xl border border-slate-200 p-5 dark:border-slate-800">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold uppercase text-slate-400">Access Bank HQ</span>
                                <span class="rounded-full bg-emerald-500/10 px-2.5 py-0.5 text-[10px] font-bold text-emerald-600">Active Account</span>
                            </div>
                            <p class="mt-3 text-2xl font-black text-slate-900 dark:text-white">₦4,850,000.00</p>
                            <p class="mt-1 text-xs text-slate-400">Acc: 0129481029 · Lagos HQ</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 p-5 dark:border-slate-800">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold uppercase text-slate-400">GTBank Operations</span>
                                <span class="rounded-full bg-emerald-500/10 px-2.5 py-0.5 text-[10px] font-bold text-emerald-600">Active Account</span>
                            </div>
                            <p class="mt-3 text-2xl font-black text-slate-900 dark:text-white">₦2,140,500.00</p>
                            <p class="mt-1 text-xs text-slate-400">Acc: 0548102941 · Abuja Branch</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 p-5 dark:border-slate-800">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold uppercase text-slate-400">Zenith Petty Cash</span>
                                <span class="rounded-full bg-blue-500/10 px-2.5 py-0.5 text-[10px] font-bold text-blue-600">Cash Reserve</span>
                            </div>
                            <p class="mt-3 text-2xl font-black text-slate-900 dark:text-white">₦350,000.00</p>
                            <p class="mt-1 text-xs text-slate-400">Acc: 1019482014 · Operations</p>
                        </div>
                    </div>
                </section>
            </div>
        @elseif ($activeTab === 'invoices')
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                    <div>
                        <h2 class="text-lg font-bold text-slate-950 dark:text-white">{{ __('Invoices, Estimates & Billing') }}</h2>
                        <p class="text-sm text-slate-500">{{ __('Issue customer invoices, track billing statuses, and dispatch payment reminders.') }}</p>
                    </div>
                    <button type="button" wire:click="openCreateModal('invoice')" class="rounded-xl bg-emerald-600 px-4 py-2.5 text-xs font-bold text-white hover:bg-emerald-700 transition">
                        <i class="fa-light fa-plus mr-1.5"></i>{{ __('Create New Invoice') }}
                    </button>
                </div>
                <div class="mt-5 overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50 text-xs uppercase text-slate-400 dark:bg-slate-800">
                            <tr>
                                <th class="px-4 py-3.5">Invoice #</th>
                                <th class="px-4 py-3.5">Customer / Client</th>
                                <th class="px-4 py-3.5">Issue Date</th>
                                <th class="px-4 py-3.5">Amount (NGN)</th>
                                <th class="px-4 py-3.5">Status</th>
                                <th class="px-4 py-3.5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @foreach ($dbInvoices as $inv)
                                <tr class="transition hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                                    <td class="px-4 py-3.5 font-bold font-mono text-slate-900 dark:text-white">{{ $inv->invoice_number }}</td>
                                    <td class="px-4 py-3.5 font-bold">{{ $inv->client_name }}</td>
                                    <td class="px-4 py-3.5 text-slate-500 text-xs">{{ $inv->issue_date?->format('Y-m-d') ?: now()->format('Y-m-d') }}</td>
                                    <td class="px-4 py-3.5 font-black text-slate-900 dark:text-white">₦{{ number_format($inv->total, 2) }}</td>
                                    <td class="px-4 py-3.5">
                                        <span class="rounded-full px-3 py-1 text-xs font-bold {{ $inv->status === 'paid' ? 'bg-emerald-500/10 text-emerald-600 border border-emerald-500/20' : 'bg-amber-500/10 text-amber-600 border border-amber-500/20' }}">
                                            {{ ucfirst($inv->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3.5 text-right space-x-2">
                                        @if ($inv->status !== 'paid')
                                            <button type="button" wire:click="markInvoicePaid({{ $inv->id }})" class="text-xs font-bold text-emerald-600 hover:underline">
                                                Mark Paid
                                            </button>
                                            <button type="button" wire:click="generatePaystackPaymentLink({{ $inv->id }})" class="rounded-lg bg-blue-500/10 px-2.5 py-1 text-xs font-bold text-blue-600 hover:bg-blue-500/20 transition">
                                                <i class="fa-brands fa-paystack mr-1 text-blue-500"></i>Paystack NGN
                                            </button>
                                            <button type="button" wire:click="sendWhatsAppInvoiceNotice({{ $inv->id }})" class="text-xs font-bold text-emerald-600 hover:underline" title="Send WhatsApp Payment Notice">
                                                <i class="fa-brands fa-whatsapp mr-1 text-emerald-500"></i>WhatsApp
                                            </button>
                                            <button type="button" wire:click="sendInvoiceReminder({{ $inv->id }})" class="text-xs font-bold text-slate-500 hover:text-slate-900 dark:hover:text-white">
                                                <i class="fa-light fa-bell mr-1"></i>Reminder
                                            </button>
                                        @endif
                                        <a href="{{ route('portal.invoice.pdf', $inv) }}" target="_blank" class="inline-flex items-center gap-1 font-bold text-rose-600 hover:underline text-xs">
                                            <i class="fa-light fa-file-pdf"></i>PDF
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        @elseif ($activeTab === 'expenses')
            <div class="space-y-6">
                <div class="grid gap-6 lg:grid-cols-[1fr_360px]">
                    <!-- Expense Log Table -->
                    <section class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <div class="flex items-center justify-between border-b p-5 dark:border-slate-800">
                            <div>
                                <h2 class="text-base font-bold text-slate-950 dark:text-white">{{ __('Expense & Receipt Log') }}</h2>
                                <p class="text-xs text-slate-500">{{ __('All vendor expenses with approval status and receipt attachments') }}</p>
                            </div>
                            <span class="rounded-full bg-orange-500/10 px-3 py-1 text-xs font-bold text-orange-600">{{ count($expenseRecords) }} Records</span>
                        </div>
                        @if (empty($expenseRecords))
                            <div class="flex flex-col items-center py-12 text-center">
                                <i class="fa-light fa-receipt text-4xl text-slate-300 dark:text-slate-600"></i>
                                <p class="mt-3 text-sm font-semibold text-slate-500">{{ __('No expenses logged yet') }}</p>
                            </div>
                        @else
                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-xs">
                                    <thead class="border-b bg-slate-50 dark:border-slate-800 dark:bg-slate-900/50">
                                        <tr>
                                            <th class="px-5 py-3 font-bold text-slate-500">{{ __('Vendor / Category') }}</th>
                                            <th class="px-4 py-3 font-bold text-slate-500">{{ __('Amount') }}</th>
                                            <th class="px-4 py-3 font-bold text-slate-500">{{ __('Date') }}</th>
                                            <th class="px-4 py-3 font-bold text-slate-500">{{ __('Receipt') }}</th>
                                            <th class="px-4 py-3 font-bold text-slate-500">{{ __('Status') }}</th>
                                            <th class="px-4 py-3 font-bold text-slate-500">{{ __('Actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                        @foreach ($expenseRecords as $exp)
                                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40">
                                                <td class="px-5 py-3">
                                                    <p class="font-bold text-slate-900 dark:text-white">{{ $exp['vendor'] }}</p>
                                                    <p class="text-slate-400">{{ $exp['category'] }}</p>
                                                </td>
                                                <td class="px-4 py-3 font-black text-slate-900 dark:text-white">₦{{ number_format($exp['amount'], 2) }}</td>
                                                <td class="px-4 py-3 text-slate-500">{{ $exp['expense_date'] }}</td>
                                                <td class="px-4 py-3">
                                                    @if ($exp['receipt_path'])
                                                        <a href="/storage/{{ $exp['receipt_path'] }}" target="_blank" class="text-blue-600 hover:underline text-[10px] font-bold"><i class="fa-light fa-paperclip mr-1"></i>View</a>
                                                    @else
                                                        <span class="text-slate-300 text-[10px]">No receipt</span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3">
                                                    <span class="rounded-full px-2.5 py-1 text-[10px] font-bold
                                                        {{ ($exp['approval_status'] ?? 'pending') === 'approved' ? 'bg-emerald-500/10 text-emerald-600' : (($exp['approval_status'] ?? 'pending') === 'rejected' ? 'bg-rose-500/10 text-rose-600' : 'bg-amber-500/10 text-amber-600') }}">
                                                        {{ ucfirst($exp['approval_status'] ?? 'pending') }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3">
                                                    <div class="flex gap-2">
                                                        @if (($exp['approval_status'] ?? 'pending') === 'pending')
                                                            <button type="button" wire:click="approveExpense({{ $exp['id'] }})" class="rounded-lg bg-emerald-500/10 px-2.5 py-1 text-[10px] font-bold text-emerald-600 hover:bg-emerald-500/20">✓ Approve</button>
                                                            <button type="button" wire:click="rejectExpense({{ $exp['id'] }})" class="rounded-lg bg-rose-500/10 px-2.5 py-1 text-[10px] font-bold text-rose-600 hover:bg-rose-500/20">✗ Reject</button>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </section>

                    <!-- Log Expense Form -->
                    <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 space-y-4">
                        <h3 class="text-sm font-bold text-slate-950 dark:text-white border-b pb-3 dark:border-slate-800">{{ __('Log New Expense') }}</h3>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1">{{ __('Category') }}</label>
                            <select wire:model="expenseForm.category" class="w-full rounded-xl border border-slate-200 p-2.5 text-sm outline-none focus:border-orange-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white">
                                @foreach (['Office Supplies', 'Cloud & SaaS', 'Travel & Transport', 'Utilities', 'Marketing & Ads', 'Salaries', 'Equipment', 'Maintenance', 'Legal & Compliance', 'Miscellaneous'] as $cat)
                                    <option value="{{ $cat }}">{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1">{{ __('Vendor / Payee') }}</label>
                            <input wire:model="expenseForm.vendor" type="text" placeholder="e.g. AWS Nigeria" class="w-full rounded-xl border border-slate-200 p-2.5 text-sm outline-none focus:border-orange-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 mb-1">{{ __('Amount (₦)') }}</label>
                                <input wire:model="expenseForm.amount" type="number" placeholder="45000" class="w-full rounded-xl border border-slate-200 p-2.5 text-sm outline-none focus:border-orange-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 mb-1">{{ __('Date') }}</label>
                                <input wire:model="expenseForm.expense_date" type="date" class="w-full rounded-xl border border-slate-200 p-2.5 text-sm outline-none focus:border-orange-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1">{{ __('Payment Method') }}</label>
                            <select wire:model="expenseForm.payment_method" class="w-full rounded-xl border border-slate-200 p-2.5 text-sm outline-none focus:border-orange-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white">
                                @foreach (['Bank Transfer', 'Cash', 'POS Terminal', 'Mobile Money', 'Cheque', 'Corporate Card'] as $method)
                                    <option value="{{ $method }}">{{ $method }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1">{{ __('Description / Notes') }}</label>
                            <textarea wire:model="expenseForm.description" rows="2" placeholder="Brief description of expense..." class="w-full rounded-xl border border-slate-200 p-2.5 text-sm outline-none focus:border-orange-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white resize-none"></textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1">{{ __('Reference / Invoice #') }}</label>
                            <input wire:model="expenseForm.reference" type="text" placeholder="EXP-2026-001" class="w-full rounded-xl border border-slate-200 p-2.5 text-sm outline-none focus:border-orange-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white">
                        </div>
                        <div class="rounded-xl border-2 border-dashed border-slate-200 p-4 text-center dark:border-slate-700">
                            <i class="fa-light fa-cloud-arrow-up text-2xl text-slate-400"></i>
                            <p class="mt-2 text-xs font-semibold text-slate-500">{{ __('Receipt Upload') }}</p>
                            <p class="text-[10px] text-slate-400">{{ __('PDF, JPG, PNG up to 5MB') }}</p>
                            <input type="file" class="mt-2 w-full text-xs text-slate-400" accept=".pdf,.jpg,.png,.jpeg">
                        </div>
                        <button type="button" wire:click="saveExpense" class="w-full rounded-xl bg-orange-600 py-3 text-sm font-bold text-white hover:bg-orange-700 transition">
                            <i class="fa-light fa-plus mr-1.5"></i> {{ __('Log Expense') }}
                        </button>
                    </section>
                </div>
            </div>
        @elseif ($activeTab === 'ledger')
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                    <div>
                        <h2 class="text-lg font-bold text-slate-950 dark:text-white">{{ __('General Ledger & Double-Entry Trial Balance Sheet') }}</h2>
                        <p class="text-sm text-slate-500">{{ __('Complete trial balance audit of chart of accounts, debits, and credits in NGN.') }}</p>
                    </div>
                    <a href="{{ route('portal.finance.export-csv') }}" class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-2 text-xs font-bold text-emerald-700 hover:bg-emerald-100 dark:bg-emerald-950/40 dark:border-emerald-800 dark:text-emerald-300">
                        <i class="fa-light fa-file-excel mr-1.5"></i>{{ __('Export Ledger CSV') }}
                    </a>
                </div>

                <div class="mt-5 overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50 text-xs uppercase text-slate-400 dark:bg-slate-800">
                            <tr>
                                <th class="px-4 py-3.5">Account Code</th>
                                <th class="px-4 py-3.5">Account Name</th>
                                <th class="px-4 py-3.5">Type</th>
                                <th class="px-4 py-3.5">Debit (NGN)</th>
                                <th class="px-4 py-3.5">Credit (NGN)</th>
                                <th class="px-4 py-3.5 text-right">Net Balance (NGN)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @foreach ($generalLedger as $acc)
                                <tr class="transition hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                                    <td class="px-4 py-3.5 font-mono font-bold text-slate-900 dark:text-white">{{ $acc['code'] }}</td>
                                    <td class="px-4 py-3.5 font-bold">{{ $acc['account'] }}</td>
                                    <td class="px-4 py-3.5">
                                        <span class="rounded-full px-2.5 py-0.5 text-xs font-bold bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300">
                                            {{ $acc['type'] }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3.5 font-mono text-slate-700 dark:text-slate-300">₦{{ number_format($acc['debit'], 2) }}</td>
                                    <td class="px-4 py-3.5 font-mono text-slate-700 dark:text-slate-300">₦{{ number_format($acc['credit'], 2) }}</td>
                                    <td class="px-4 py-3.5 font-mono font-black text-right {{ $acc['balance'] >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                        ₦{{ number_format($acc['balance'], 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        @elseif ($activeTab === 'reports')
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                    <div>
                        <h2 class="text-lg font-bold text-slate-950 dark:text-white">{{ __('Profit & Loss (P&L) Income Statement Report') }}</h2>
                        <p class="text-sm text-slate-500">{{ __('Net profit performance breakdown, gross margins, and EBITDA.') }}</p>
                    </div>
                    <a href="{{ route('portal.finance.export-csv') }}" class="rounded-xl bg-emerald-600 px-4 py-2 text-xs font-bold text-white hover:bg-emerald-700">
                        <i class="fa-light fa-download mr-1.5"></i>{{ __('Download CSV Statement') }}
                    </a>
                </div>

                <div class="mt-6 grid gap-4 md:grid-cols-2">
                    <div class="rounded-2xl border border-slate-200 p-5 dark:border-slate-800 space-y-3">
                        <h3 class="text-base font-bold text-slate-900 dark:text-white border-b pb-2 dark:border-slate-800">Revenue & Gross Margin</h3>
                        <div class="flex justify-between text-sm"><span>Gross Sales Revenue</span><span class="font-bold text-emerald-600">₦1,245,780.00</span></div>
                        <div class="flex justify-between text-sm"><span>Cost of Goods Sold (COGS)</span><span class="font-bold text-rose-500">-₦324,500.00</span></div>
                        <div class="flex justify-between text-base font-black border-t pt-2 dark:border-slate-800"><span>Gross Profit Margin</span><span class="text-blue-600">₦921,280.00 (73.9%)</span></div>
                    </div>

                    <div class="rounded-2xl border border-slate-200 p-5 dark:border-slate-800 space-y-3">
                        <h3 class="text-base font-bold text-slate-950 dark:text-white border-b pb-2 dark:border-slate-800">Operating Expenses & Net Income</h3>
                        <div class="flex justify-between text-sm"><span>Operating Expenses</span><span class="font-bold text-slate-700 dark:text-slate-300">₦324,500.00</span></div>
                        <div class="flex justify-between text-sm"><span>Taxes & Provisions (7.5% VAT)</span><span class="font-bold text-slate-700 dark:text-slate-300">₦93,433.00</span></div>
                        <div class="flex justify-between text-base font-black border-t pt-2 dark:border-slate-800"><span>Net EBITDA Income</span><span class="text-emerald-600">₦921,280.00</span></div>
                    </div>
                </div>
            </section>
        {{-- SALARY & PAYROLL MANAGEMENT TAB --}}
        @elseif ($activeTab === 'salary')
            <div class="space-y-6">
                <!-- Payroll KPI Summary -->
                <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">{{ __('Total Payroll (This Month)') }}</p>
                        <p class="mt-2 text-2xl font-black text-emerald-600">₦{{ number_format(collect($salaryRecords)->where('status', 'paid')->sum('net_salary'), 0) }}</p>
                        <p class="mt-1 text-xs text-slate-400">{{ collect($salaryRecords)->where('status', 'paid')->count() }} payslips processed</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">{{ __('Pending Payroll') }}</p>
                        <p class="mt-2 text-2xl font-black text-amber-600">₦{{ number_format(collect($salaryRecords)->where('status', 'pending')->sum('net_salary'), 0) }}</p>
                        <p class="mt-1 text-xs text-slate-400">{{ collect($salaryRecords)->where('status', 'pending')->count() }} awaiting disbursement</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">{{ __('Total Employees') }}</p>
                        <p class="mt-2 text-2xl font-black text-blue-600">{{ collect($salaryRecords)->unique('employee_name')->count() }}</p>
                        <p class="mt-1 text-xs text-slate-400">Across all departments</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">{{ __('PAYE Tax Deducted') }}</p>
                        <p class="mt-2 text-2xl font-black text-rose-600">₦{{ number_format(collect($salaryRecords)->sum('paye_tax'), 0) }}</p>
                        <p class="mt-1 text-xs text-slate-400">Nigeria PAYE compliance</p>
                    </div>
                </div>

                <div class="grid gap-6 lg:grid-cols-[1fr_380px]">
                    <!-- Salary Records Table -->
                    <section class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <div class="flex items-center justify-between border-b p-5 dark:border-slate-800">
                            <div>
                                <h2 class="text-base font-bold text-slate-950 dark:text-white">{{ __('Payroll Register') }}</h2>
                                <p class="text-xs text-slate-500">{{ __('All employee salary records and payslip history') }}</p>
                            </div>
                            <button type="button" wire:click="runPayroll" class="rounded-xl bg-emerald-600 px-4 py-2.5 text-xs font-bold text-white hover:bg-emerald-700 transition flex items-center gap-2">
                                <i class="fa-light fa-play"></i> {{ __('Run Payroll') }}
                            </button>
                        </div>
                        @if (empty($salaryRecords))
                            <div class="flex flex-col items-center justify-center py-16 text-center">
                                <i class="fa-light fa-money-bill-wave text-4xl text-slate-300 dark:text-slate-600"></i>
                                <p class="mt-4 text-sm font-semibold text-slate-500">{{ __('No salary records yet') }}</p>
                                <p class="mt-1 text-xs text-slate-400">{{ __('Add employee records using the form to start tracking payroll') }}</p>
                            </div>
                        @else
                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-xs">
                                    <thead class="border-b bg-slate-50 dark:border-slate-800 dark:bg-slate-900/50">
                                        <tr>
                                            <th class="px-5 py-3 font-bold text-slate-500">{{ __('Employee') }}</th>
                                            <th class="px-4 py-3 font-bold text-slate-500">{{ __('Department') }}</th>
                                            <th class="px-4 py-3 font-bold text-slate-500">{{ __('Gross') }}</th>
                                            <th class="px-4 py-3 font-bold text-slate-500">{{ __('PAYE') }}</th>
                                            <th class="px-4 py-3 font-bold text-slate-500">{{ __('Net Pay') }}</th>
                                            <th class="px-4 py-3 font-bold text-slate-500">{{ __('Period') }}</th>
                                            <th class="px-4 py-3 font-bold text-slate-500">{{ __('Status') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                        @foreach ($salaryRecords as $rec)
                                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40">
                                                <td class="px-5 py-3">
                                                    <p class="font-bold text-slate-900 dark:text-white">{{ $rec['employee_name'] }}</p>
                                                    <p class="text-slate-400">{{ $rec['role'] ?? '' }}</p>
                                                </td>
                                                <td class="px-4 py-3 text-slate-600 dark:text-slate-300">{{ $rec['department'] ?? '—' }}</td>
                                                <td class="px-4 py-3 font-bold text-slate-900 dark:text-white">₦{{ number_format($rec['gross_salary'] ?? 0, 0) }}</td>
                                                <td class="px-4 py-3 text-rose-600">₦{{ number_format($rec['paye_tax'] ?? 0, 0) }}</td>
                                                <td class="px-4 py-3 font-black text-emerald-600">₦{{ number_format($rec['net_salary'] ?? 0, 0) }}</td>
                                                <td class="px-4 py-3 text-slate-500">{{ $rec['pay_period'] ?? '—' }}</td>
                                                <td class="px-4 py-3">
                                                    <span class="rounded-full px-2.5 py-1 text-[10px] font-bold {{ ($rec['status'] ?? '') === 'paid' ? 'bg-emerald-500/10 text-emerald-600' : 'bg-amber-500/10 text-amber-600' }}">
                                                        {{ ucfirst($rec['status'] ?? 'pending') }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </section>

                    <!-- Add Salary Record Form -->
                    <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 space-y-4">
                        <h3 class="text-sm font-bold text-slate-950 dark:text-white border-b pb-3 dark:border-slate-800">{{ __('Add Employee Payroll Record') }}</h3>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1">{{ __('Employee Name') }}</label>
                            <input wire:model="salaryForm.employee_name" type="text" placeholder="e.g. Babatunde Adeleke" class="w-full rounded-xl border border-slate-200 p-2.5 text-sm outline-none focus:border-emerald-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 mb-1">{{ __('Department') }}</label>
                                <input wire:model="salaryForm.department" type="text" placeholder="Finance" class="w-full rounded-xl border border-slate-200 p-2.5 text-sm outline-none focus:border-emerald-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 mb-1">{{ __('Role') }}</label>
                                <input wire:model="salaryForm.role" type="text" placeholder="Manager" class="w-full rounded-xl border border-slate-200 p-2.5 text-sm outline-none focus:border-emerald-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1">{{ __('Gross Salary (₦)') }}</label>
                            <input wire:model="salaryForm.gross_salary" type="number" placeholder="350000" class="w-full rounded-xl border border-slate-200 p-2.5 text-sm outline-none focus:border-emerald-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white">
                            @if ($salaryForm['gross_salary'])
                                <p class="mt-1 text-xs text-slate-400">Net (est.): ₦{{ number_format((float)$salaryForm['gross_salary'] * 0.85, 0) }} after PAYE + Pension</p>
                            @endif
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 mb-1">{{ __('Pay Period') }}</label>
                                <input wire:model="salaryForm.pay_period" type="month" class="w-full rounded-xl border border-slate-200 p-2.5 text-sm outline-none focus:border-emerald-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 mb-1">{{ __('Bank Name') }}</label>
                                <input wire:model="salaryForm.bank_name" type="text" placeholder="Access Bank" class="w-full rounded-xl border border-slate-200 p-2.5 text-sm outline-none focus:border-emerald-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1">{{ __('Account Number') }}</label>
                            <input wire:model="salaryForm.account_number" type="text" placeholder="0123456789" class="w-full rounded-xl border border-slate-200 p-2.5 text-sm outline-none focus:border-emerald-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white">
                        </div>
                        <button type="button" wire:click="saveSalaryRecord" class="w-full rounded-xl bg-emerald-600 py-3 text-sm font-bold text-white hover:bg-emerald-700 transition">
                            <i class="fa-light fa-plus mr-1.5"></i> {{ __('Add to Payroll') }}
                        </button>
                    </section>
                </div>
            </div>

        {{-- AI FINANCIAL ANALYSIS TAB --}}
        @elseif ($activeTab === 'ai_finance')
            <div class="space-y-6">
                <!-- AI Header -->
                <div class="rounded-2xl border border-emerald-200 bg-gradient-to-r from-emerald-50 to-teal-50 p-6 dark:border-emerald-900/40 dark:from-emerald-950/30 dark:to-teal-950/20">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-600 text-white text-xl shadow-lg">
                                <i class="fa-light fa-brain-circuit"></i>
                            </div>
                            <div>
                                <h2 class="text-lg font-black text-slate-950 dark:text-white">{{ __('AI Financial Intelligence') }}</h2>
                                <p class="text-xs text-slate-500">{{ __('Real-time analysis, anomaly detection and cash flow forecasting powered by AI') }}</p>
                            </div>
                        </div>
                        <button type="button" wire:click="generateAiFinanceInsights" class="rounded-xl bg-emerald-600 px-5 py-2.5 text-xs font-bold text-white shadow-md hover:bg-emerald-700 transition flex items-center gap-2">
                            <i class="fa-light fa-rotate"></i> {{ __('Refresh Analysis') }}
                        </button>
                    </div>
                </div>

                <!-- AI Insight Cards -->
                @if (empty($aiFinanceInsights))
                    <div class="flex flex-col items-center justify-center py-16">
                        <i class="fa-light fa-brain-circuit text-5xl text-slate-300 dark:text-slate-600"></i>
                        <p class="mt-4 font-semibold text-slate-500">{{ __('Click Refresh Analysis to generate AI insights') }}</p>
                        <button type="button" wire:click="generateAiFinanceInsights" class="mt-4 rounded-xl bg-emerald-600 px-6 py-3 text-sm font-bold text-white hover:bg-emerald-700 transition">
                            <i class="fa-light fa-sparkles mr-2"></i>{{ __('Generate Now') }}
                        </button>
                    </div>
                @else
                    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                        @foreach ($aiFinanceInsights as $insight)
                            <div class="rounded-2xl border p-5 shadow-sm transition-all hover:shadow-md {{ ($insight['severity'] ?? 'info') === 'critical' ? 'border-rose-200 bg-rose-50 dark:border-rose-900/40 dark:bg-rose-950/20' : (($insight['severity'] ?? 'info') === 'warning' ? 'border-amber-200 bg-amber-50 dark:border-amber-900/40 dark:bg-amber-950/20' : 'border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900') }}">
                                <div class="flex items-start gap-3">
                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl text-sm {{ ($insight['severity'] ?? 'info') === 'critical' ? 'bg-rose-500/10 text-rose-600' : (($insight['severity'] ?? 'info') === 'warning' ? 'bg-amber-500/10 text-amber-600' : 'bg-emerald-500/10 text-emerald-600') }}">
                                        <i class="{{ $insight['icon'] ?? 'fa-light fa-lightbulb' }}"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs font-black text-slate-900 dark:text-white">{{ $insight['title'] }}</p>
                                        <p class="mt-1 text-xs text-slate-500 leading-relaxed">{{ $insight['body'] }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Suggested Financial Features -->
                    <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <h3 class="text-sm font-bold text-slate-950 dark:text-white border-b pb-3 dark:border-slate-800 mb-4">{{ __('Additional Financial Modules — Recommended') }}</h3>
                        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach ([
                                ['icon' => 'fa-light fa-chart-gantt', 'name' => 'Budget & Forecast', 'desc' => 'Set department budgets with variance alerts'],
                                ['icon' => 'fa-light fa-coins', 'name' => 'Multi-Currency', 'desc' => 'USD/GBP/NGN with live exchange rates'],
                                ['icon' => 'fa-light fa-file-certificate', 'name' => 'Tax Filing Assistant', 'desc' => 'VAT/PAYE reminders and filing prep'],
                                ['icon' => 'fa-light fa-building-columns', 'name' => 'Vendor Payments', 'desc' => 'Bulk payment batches with bank integration'],
                                ['icon' => 'fa-light fa-chart-tree-map', 'name' => 'Profit Centre', 'desc' => 'P&L by branch and department'],
                                ['icon' => 'fa-light fa-warehouse', 'name' => 'Fixed Asset Register', 'desc' => 'Depreciation tracking per asset'],
                            ] as $feat)
                                <div class="flex items-start gap-3 rounded-xl border border-slate-100 p-4 dark:border-slate-800">
                                    <i class="{{ $feat['icon'] }} text-lg text-emerald-600 mt-0.5"></i>
                                    <div>
                                        <p class="text-xs font-bold text-slate-900 dark:text-white">{{ $feat['name'] }}</p>
                                        <p class="mt-0.5 text-[10px] text-slate-400">{{ $feat['desc'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif
            </div>
        @endif
    @endif

    <!-- MODULE 2: CRM — LEADS, DEALS & PIPELINE WORKSPACE -->
    @if ($moduleKey === 'crm')
        @if ($activeTab === 'leads')
            <div class="space-y-6">
                <!-- CRM Pipeline KPI Summary Cards -->
                <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">{{ __('Total Pipeline Value') }}</p>
                        <p class="mt-2 text-2xl font-black text-blue-600">₦{{ number_format($dbDeals->sum('value'), 2) }}</p>
                        <p class="mt-1 text-xs font-medium text-blue-500"><i class="fa-light fa-chart-line-up mr-1"></i>{{ $dbDeals->count() }} Active Deals</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">{{ __('Qualified Leads') }}</p>
                        <p class="mt-2 text-2xl font-black text-emerald-600">{{ $dbLeads->where('status', 'qualified')->count() ?: $dbLeads->count() }} Leads</p>
                        <p class="mt-1 text-xs font-medium text-emerald-500"><i class="fa-light fa-user-check mr-1"></i>Ready for conversion</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">{{ __('Win Rate') }}</p>
                        <p class="mt-2 text-2xl font-black text-slate-900 dark:text-white">68.5%</p>
                        <p class="mt-1 text-xs font-medium text-emerald-500"><i class="fa-light fa-arrow-trend-up mr-1"></i>+4.2% vs last quarter</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">{{ __('Avg Deal Size') }}</p>
                        <p class="mt-2 text-2xl font-black text-purple-600">₦{{ $dbDeals->count() > 0 ? number_format($dbDeals->avg('value'), 2) : '3,500,000.00' }}</p>
                        <p class="mt-1 text-xs font-medium text-slate-400">Per closed deal</p>
                    </div>
                </div>

                <!-- Leads Management Table -->
                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between border-b pb-4 dark:border-slate-800">
                        <div>
                            <h2 class="text-lg font-bold text-slate-950 dark:text-white">{{ __('Lead Acquisition & Qualification Pipeline') }}</h2>
                            <p class="text-sm text-slate-500">{{ __('Manage inbound leads, qualify prospects, and convert to active deals.') }}</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="relative">
                                <i class="fa-light fa-search absolute left-3 top-2.5 text-slate-400"></i>
                                <input type="text" wire:model.live.debounce.300ms="searchQuery" placeholder="Search leads..." class="rounded-xl border border-slate-200 py-2 pl-9 pr-4 text-xs font-semibold outline-none focus:border-blue-500 dark:border-slate-700 dark:bg-slate-800 dark:text-white w-48">
                            </div>
                            <button type="button" wire:click="openCreateModal('lead')" class="rounded-xl bg-blue-600 px-4 py-2.5 text-xs font-bold text-white shadow-md hover:bg-blue-700 transition">
                                <i class="fa-light fa-plus mr-1.5"></i>{{ __('Add New Lead') }}
                            </button>
                        </div>
                    </div>

                    <div class="mt-5 overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-slate-50 text-xs uppercase text-slate-400 dark:bg-slate-800">
                                <tr>
                                    <th class="px-4 py-3.5">Company / Contact</th>
                                    <th class="px-4 py-3.5">Email & Phone</th>
                                    <th class="px-4 py-3.5">Deal Value (NGN)</th>
                                    <th class="px-4 py-3.5">Status</th>
                                    <th class="px-4 py-3.5 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                @forelse ($dbLeads as $lead)
                                    <tr class="transition hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                                        <td class="px-4 py-3.5">
                                            <p class="font-bold text-slate-900 dark:text-white">{{ $lead->company_name }}</p>
                                            <p class="text-xs text-slate-400">{{ $lead->contact_person }}</p>
                                        </td>
                                        <td class="px-4 py-3.5 text-xs">
                                            <p class="font-semibold text-slate-600 dark:text-slate-300">{{ $lead->email }}</p>
                                            <p class="text-slate-400">{{ $lead->phone }}</p>
                                        </td>
                                        <td class="px-4 py-3.5 font-black text-slate-900 dark:text-white">₦{{ number_format($lead->deal_value, 2) }}</td>
                                        <td class="px-4 py-3.5">
                                            <span class="rounded-full px-3 py-1 text-xs font-bold {{ match($lead->status) {
                                                'new' => 'bg-blue-500/10 text-blue-600 border border-blue-500/20',
                                                'contacted' => 'bg-amber-500/10 text-amber-600 border border-amber-500/20',
                                                'qualified' => 'bg-emerald-500/10 text-emerald-600 border border-emerald-500/20',
                                                'converted' => 'bg-purple-500/10 text-purple-600 border border-purple-500/20',
                                                default => 'bg-slate-100 text-slate-600',
                                            } }}">
                                                {{ ucfirst($lead->status) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3.5 text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                @if ($lead->status !== 'converted')
                                                    <button type="button" wire:click="convertLeadToDeal({{ $lead->id }})" class="rounded-lg bg-blue-500/10 px-2.5 py-1 text-xs font-bold text-blue-600 hover:bg-blue-500/20 transition">
                                                        <i class="fa-light fa-arrow-right mr-1"></i>Convert
                                                    </button>
                                                @endif
                                                <button type="button" wire:click="archiveLead({{ $lead->id }})" class="text-xs font-bold text-slate-400 hover:text-rose-500 transition">
                                                    <i class="fa-light fa-archive"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-12 text-center">
                                            <i class="fa-light fa-user-plus text-4xl text-slate-300"></i>
                                            <p class="mt-3 text-sm font-medium text-slate-400">{{ __('No leads yet. Add your first lead to get started.') }}</p>
                                            <button type="button" wire:click="openCreateModal('lead')" class="mt-4 rounded-xl bg-blue-600 px-5 py-2 text-xs font-bold text-white shadow-md hover:bg-blue-700">{{ __('Add First Lead') }}</button>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        @elseif ($activeTab === 'deals')
            <div class="space-y-6">
                <!-- Pipeline Stage Visualization -->
                <div class="grid gap-4 sm:grid-cols-4">
                    @php
                        $stages = [
                            'prospecting' => ['label' => 'Prospecting', 'icon' => 'fa-light fa-magnifying-glass', 'color' => 'slate'],
                            'proposal' => ['label' => 'Proposal', 'icon' => 'fa-light fa-file-signature', 'color' => 'blue'],
                            'negotiation' => ['label' => 'Negotiation', 'icon' => 'fa-light fa-handshake', 'color' => 'amber'],
                            'closed_won' => ['label' => 'Closed Won', 'icon' => 'fa-light fa-trophy', 'color' => 'emerald'],
                        ];
                    @endphp
                    @foreach ($stages as $stageKey => $stageMeta)
                        @php $stageDeals = $dbDeals->where('stage', $stageKey); @endphp
                        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                            <div class="flex items-center justify-between">
                                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-{{ $stageMeta['color'] }}-500/10 text-{{ $stageMeta['color'] }}-600">
                                    <i class="{{ $stageMeta['icon'] }} text-lg"></i>
                                </span>
                                <span class="text-xs font-bold text-slate-400 uppercase">{{ $stageDeals->count() }} Deals</span>
                            </div>
                            <h3 class="mt-3 text-base font-bold text-slate-900 dark:text-white">{{ $stageMeta['label'] }}</h3>
                            <p class="mt-1 text-lg font-black text-{{ $stageMeta['color'] }}-600">₦{{ number_format($stageDeals->sum('value'), 2) }}</p>
                        </div>
                    @endforeach
                </div>

                <!-- Deals Table -->
                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                        <div>
                            <h2 class="text-lg font-bold text-slate-950 dark:text-white">{{ __('Active Deals & Revenue Pipeline') }}</h2>
                            <p class="text-sm text-slate-500">{{ __('Track deal stages, expected close dates, and projected revenue.') }}</p>
                        </div>
                        <button type="button" wire:click="openCreateModal('deal')" class="rounded-xl bg-blue-600 px-4 py-2.5 text-xs font-bold text-white shadow-md hover:bg-blue-700 transition">
                            <i class="fa-light fa-plus mr-1.5"></i>{{ __('New Deal') }}
                        </button>
                    </div>

                    <div class="mt-5 overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-slate-50 text-xs uppercase text-slate-400 dark:bg-slate-800">
                                <tr>
                                    <th class="px-4 py-3.5">Deal Name</th>
                                    <th class="px-4 py-3.5">Pipeline Stage</th>
                                    <th class="px-4 py-3.5">Value (NGN)</th>
                                    <th class="px-4 py-3.5">Expected Close</th>
                                    <th class="px-4 py-3.5">Probability</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                @forelse ($dbDeals as $deal)
                                    <tr class="transition hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                                        <td class="px-4 py-3.5 font-bold text-slate-900 dark:text-white">{{ $deal->deal_name }}</td>
                                        <td class="px-4 py-3.5">
                                            <span class="rounded-full px-3 py-1 text-xs font-bold {{ match($deal->stage) {
                                                'prospecting' => 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300',
                                                'proposal' => 'bg-blue-500/10 text-blue-600 border border-blue-500/20',
                                                'negotiation' => 'bg-amber-500/10 text-amber-600 border border-amber-500/20',
                                                'closed_won' => 'bg-emerald-500/10 text-emerald-600 border border-emerald-500/20',
                                                default => 'bg-slate-100 text-slate-600',
                                            } }}">
                                                {{ ucfirst(str_replace('_', ' ', $deal->stage)) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3.5 font-black text-slate-900 dark:text-white">₦{{ number_format($deal->value, 2) }}</td>
                                        <td class="px-4 py-3.5 text-xs text-slate-500">{{ $deal->expected_close?->format('Y-m-d') ?: 'TBD' }}</td>
                                        <td class="px-4 py-3.5">
                                            @php $prob = match($deal->stage) { 'prospecting' => 20, 'proposal' => 50, 'negotiation' => 75, 'closed_won' => 100, default => 30 }; @endphp
                                            <div class="flex items-center gap-2">
                                                <div class="h-1.5 w-16 overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800">
                                                    <div class="h-full rounded-full bg-blue-500 transition-all" style="width: {{ $prob }}%;"></div>
                                                </div>
                                                <span class="text-xs font-bold text-slate-600 dark:text-slate-300">{{ $prob }}%</span>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-12 text-center">
                                            <i class="fa-light fa-chart-kanban text-4xl text-slate-300"></i>
                                            <p class="mt-3 text-sm font-medium text-slate-400">{{ __('No deals in pipeline yet.') }}</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        @elseif ($activeTab === 'contacts')
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between border-b pb-4 dark:border-slate-800">
                    <div>
                        <h2 class="text-lg font-bold text-slate-950 dark:text-white">{{ __('Customer Contact Directory') }}</h2>
                        <p class="text-sm text-slate-500">{{ __('Centralized client contact records, deal history, and communication log.') }}</p>
                    </div>
                    <div class="relative">
                        <i class="fa-light fa-search absolute left-3 top-2.5 text-slate-400"></i>
                        <input type="text" wire:model.live.debounce.300ms="searchQuery" placeholder="Search contacts..." class="rounded-xl border border-slate-200 py-2 pl-9 pr-4 text-xs font-semibold outline-none focus:border-blue-500 dark:border-slate-700 dark:bg-slate-800 dark:text-white w-56">
                    </div>
                </div>

                <div class="mt-5 grid gap-4 md:grid-cols-2">
                    @foreach ($crmContacts as $contact)
                        <div class="rounded-2xl border border-slate-200 p-5 transition hover:border-blue-500/40 hover:shadow-md dark:border-slate-800 dark:hover:border-blue-500/40">
                            <div class="flex items-start justify-between">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-blue-500/10 text-sm font-black text-blue-600">{{ strtoupper(substr($contact['name'], 0, 2)) }}</span>
                                    <div>
                                        <h3 class="text-sm font-bold text-slate-900 dark:text-white">{{ $contact['name'] }}</h3>
                                        <p class="text-xs text-slate-400">{{ $contact['company'] }}</p>
                                    </div>
                                </div>
                                <span class="rounded-full bg-emerald-500/10 border border-emerald-500/20 px-2.5 py-0.5 text-[10px] font-bold text-emerald-600">{{ $contact['deals'] }} Deals</span>
                            </div>
                            <div class="mt-4 grid grid-cols-2 gap-3 text-xs">
                                <div>
                                    <p class="font-bold uppercase text-slate-400" style="font-size: 10px;">Email</p>
                                    <p class="font-semibold text-slate-600 dark:text-slate-300">{{ $contact['email'] }}</p>
                                </div>
                                <div>
                                    <p class="font-bold uppercase text-slate-400" style="font-size: 10px;">Phone</p>
                                    <p class="font-semibold text-slate-600 dark:text-slate-300">{{ $contact['phone'] }}</p>
                                </div>
                                <div>
                                    <p class="font-bold uppercase text-slate-400" style="font-size: 10px;">Total Value</p>
                                    <p class="font-black text-blue-600">₦{{ number_format($contact['value'], 2) }}</p>
                                </div>
                                <div>
                                    <p class="font-bold uppercase text-slate-400" style="font-size: 10px;">Last Contact</p>
                                    <p class="font-semibold text-slate-600 dark:text-slate-300">{{ $contact['last_contact'] }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @elseif ($activeTab === 'contracts')
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                    <div>
                        <h2 class="text-lg font-bold text-slate-950 dark:text-white">{{ __('Client Contracts & Service Agreements') }}</h2>
                        <p class="text-sm text-slate-500">{{ __('Track active contracts, renewal dates, and service agreement values.') }}</p>
                    </div>
                </div>

                <div class="mt-5 overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50 text-xs uppercase text-slate-400 dark:bg-slate-800">
                            <tr>
                                <th class="px-4 py-3.5">Contract ID</th>
                                <th class="px-4 py-3.5">Client</th>
                                <th class="px-4 py-3.5">Type</th>
                                <th class="px-4 py-3.5">Value (NGN)</th>
                                <th class="px-4 py-3.5">Duration</th>
                                <th class="px-4 py-3.5">Status</th>
                                <th class="px-4 py-3.5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @foreach ($crmContracts as $index => $contract)
                                <tr class="transition hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                                    <td class="px-4 py-3.5 font-mono font-bold text-slate-900 dark:text-white">{{ $contract['id'] }}</td>
                                    <td class="px-4 py-3.5 font-bold">{{ $contract['client'] }}</td>
                                    <td class="px-4 py-3.5 text-xs font-semibold text-slate-500">{{ $contract['type'] }}</td>
                                    <td class="px-4 py-3.5 font-black text-slate-900 dark:text-white">₦{{ number_format($contract['value'], 2) }}</td>
                                    <td class="px-4 py-3.5 text-xs text-slate-500">{{ $contract['start'] }} → {{ $contract['end'] }}</td>
                                    <td class="px-4 py-3.5">
                                        <span class="rounded-full px-3 py-1 text-xs font-bold {{ $contract['status'] === 'Active' ? 'bg-emerald-500/10 text-emerald-600 border border-emerald-500/20' : 'bg-amber-500/10 text-amber-600 border border-amber-500/20' }}">
                                            {{ $contract['status'] }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3.5 text-right">
                                        @if ($contract['status'] !== 'Active')
                                            <button type="button" wire:click="updateContractStatus({{ $index }}, 'Active')" class="text-xs font-bold text-emerald-600 hover:underline">Activate</button>
                                        @else
                                            <button type="button" wire:click="updateContractStatus({{ $index }}, 'Expired')" class="text-xs font-bold text-slate-400 hover:text-rose-500">Expire</button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        @else
            <section class="rounded-2xl border border-slate-200 bg-white p-8 text-center shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <i class="fa-light fa-sliders text-5xl text-blue-400"></i>
                <h3 class="mt-3 text-lg font-bold text-slate-950 dark:text-white">{{ __('CRM Form Builder & Settings') }}</h3>
                <p class="mt-1 text-sm text-slate-500">{{ __('Customize lead capture fields, pipeline stages, and CRM workflow preferences.') }}</p>
                <div class="mt-6 grid gap-4 md:grid-cols-3 max-w-2xl mx-auto">
                    <div class="rounded-2xl border border-slate-200 p-4 dark:border-slate-800">
                        <p class="text-xs font-bold uppercase text-slate-400">Lead Stages</p>
                        <p class="mt-2 text-xl font-black text-blue-600">4 Stages</p>
                        <p class="mt-1 text-xs text-slate-500">New → Contacted → Qualified → Converted</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 p-4 dark:border-slate-800">
                        <p class="text-xs font-bold uppercase text-slate-400">Deal Stages</p>
                        <p class="mt-2 text-xl font-black text-purple-600">4 Stages</p>
                        <p class="mt-1 text-xs text-slate-500">Prospecting → Proposal → Negotiation → Won</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 p-4 dark:border-slate-800">
                        <p class="text-xs font-bold uppercase text-slate-400">Custom Fields</p>
                        <p class="mt-2 text-xl font-black text-amber-600">12 Fields</p>
                        <p class="mt-1 text-xs text-slate-500">Contact, company, value, notes, etc.</p>
                    </div>
                </div>
            </section>
        @endif
    @endif

    <!-- MODULE 5: INVENTORY & SUPPLY CHAIN HUB ENHANCED USERFLOW -->
    @if ($moduleKey === 'inventory')
        @if ($activeTab === 'products')
            <div class="space-y-6">
                <!-- Inventory KPI Summary Cards -->
                <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">{{ __('Total Product SKUs') }}</p>
                        <p class="mt-2 text-2xl font-black text-orange-600">{{ $dbProducts->count() }} SKUs</p>
                        <p class="mt-1 text-xs font-medium text-slate-400"><i class="fa-light fa-boxes-stacked mr-1"></i>Active catalog items</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">{{ __('Total Stock Valuation') }}</p>
                        <p class="mt-2 text-2xl font-black text-slate-900 dark:text-white">₦{{ number_format($dbProducts->sum(fn($p) => $p->unit_price * $p->stock_quantity), 2) }}</p>
                        <p class="mt-1 text-xs font-medium text-emerald-500"><i class="fa-light fa-chart-line-up mr-1"></i>Asset liquid valuation</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">{{ __('Low Stock Alerts') }}</p>
                        <p class="mt-2 text-2xl font-black text-rose-500">{{ $dbProducts->filter(fn ($p) => $p->stock_quantity <= $p->reorder_level)->count() }} SKUs</p>
                        <p class="mt-1 text-xs font-medium text-rose-500"><i class="fa-light fa-triangle-exclamation mr-1"></i>Needs replenishment</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">{{ __('Warehouse Utilization') }}</p>
                        <p class="mt-2 text-2xl font-black text-blue-600">82.4%</p>
                        <p class="mt-1 text-xs font-medium text-slate-400"><i class="fa-light fa-warehouse mr-1"></i>3 Active Distribution Hubs</p>
                    </div>
                </div>

                <!-- Reorder Alerts Header Notification Banner -->
                @php
                    $lowStockItems = $dbProducts->filter(fn ($p) => $p->stock_quantity <= $p->reorder_level);
                @endphp
                @if ($lowStockItems->isNotEmpty())
                    <div class="flex items-center justify-between rounded-2xl border border-rose-500/30 bg-rose-500/10 p-5 text-sm font-bold text-rose-600 dark:text-rose-400 shadow-xs">
                        <div class="flex items-center gap-3">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-rose-500 text-white">
                                <i class="fa-light fa-triangle-exclamation text-lg"></i>
                            </span>
                            <div>
                                <p class="text-base font-extrabold">{{ __('Low Stock Reorder Alert!') }}</p>
                                <p class="text-xs text-rose-600/80 dark:text-rose-300 font-medium">
                                    {{ __(':count product SKU(s) have dropped below their reorder threshold.', ['count' => $lowStockItems->count()]) }}
                                </p>
                            </div>
                        </div>
                        <button type="button" wire:click="reorderStock('{{ $lowStockItems->first()->sku }}')" class="rounded-xl bg-rose-600 px-4 py-2 text-xs font-bold text-white shadow-md hover:bg-rose-700 transition">
                            <i class="fa-light fa-cart-circle-plus mr-1.5"></i>{{ __('Issue Reorder PO') }}
                        </button>
                    </div>
                @endif

                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between border-b pb-4 dark:border-slate-800">
                        <div>
                            <h2 class="text-lg font-bold text-slate-950 dark:text-white">{{ __('Products & SKU Inventory Stock Control') }}</h2>
                            <p class="text-sm text-slate-500">{{ __('Track product stock levels across warehouses, set reorder alerts, and add new SKUs.') }}</p>
                        </div>
                        <button type="button" wire:click="openCreateModal('product')" class="inline-flex items-center gap-2 rounded-xl bg-orange-600 px-4 py-2.5 text-xs font-bold text-white shadow-md hover:bg-orange-700 transition">
                            <i class="fa-light fa-plus text-sm"></i>{{ __('Add New Product SKU') }}
                        </button>
                    </div>

                    <div class="mt-5 overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-slate-50 text-xs uppercase text-slate-400 dark:bg-slate-800">
                                <tr>
                                    <th class="px-4 py-3.5">SKU Code</th>
                                    <th class="px-4 py-3.5">Product Name & Category</th>
                                    <th class="px-4 py-3.5">Warehouse Location</th>
                                    <th class="px-4 py-3.5">Selling Price</th>
                                    <th class="px-4 py-3.5">Current Stock</th>
                                    <th class="px-4 py-3.5">Adjust Stock</th>
                                    <th class="px-4 py-3.5 text-right">Reorder Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                @foreach ($dbProducts as $prod)
                                    <tr class="transition hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                                        <td class="px-4 py-3.5 font-mono font-bold text-slate-900 dark:text-white">{{ $prod->sku }}</td>
                                        <td class="px-4 py-3.5 font-bold">
                                            {{ $prod->name }}
                                            <span class="block text-xs font-normal text-slate-400">{{ $prod->category }}</span>
                                        </td>
                                        <td class="px-4 py-3.5 text-slate-500 text-xs font-semibold">{{ $prod->location ?: 'Lagos HQ Central Warehouse' }}</td>
                                        <td class="px-4 py-3.5 font-black text-slate-900 dark:text-white">₦{{ number_format($prod->unit_price, 2) }}</td>
                                        <td class="px-4 py-3.5">
                                            <span class="font-bold text-slate-800 dark:text-slate-200">{{ $prod->stock_quantity }} units</span>
                                            @if ($prod->stock_quantity <= $prod->reorder_level)
                                                <span class="ml-2 inline-block rounded-full bg-rose-500/10 text-rose-600 border border-rose-500/20 px-2 py-0.5 text-[10px] font-bold">Low Stock Alert</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3.5 flex items-center gap-1.5">
                                            <button type="button" wire:click="adjustStockQuantity({{ $prod->id }}, 10)" class="rounded-lg bg-emerald-50 px-2 py-1 text-xs font-bold text-emerald-600 hover:bg-emerald-100 dark:bg-emerald-950/40 dark:text-emerald-300">+10</button>
                                            <button type="button" wire:click="adjustStockQuantity({{ $prod->id }}, -1)" class="rounded-lg bg-rose-50 px-2 py-1 text-xs font-bold text-rose-600 hover:bg-rose-100 dark:bg-rose-950/40 dark:text-rose-300">-1</button>
                                        </td>
                                        <td class="px-4 py-3.5 text-right">
                                            <button type="button" wire:click="orderSupplierStock('Apex Hardware Supplies Ltd', '{{ $prod->sku }}')" class="inline-flex items-center gap-1 text-xs font-bold text-orange-600 hover:underline">
                                                <i class="fa-light fa-cart-circle-plus"></i>Issue PO
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        @elseif ($activeTab === 'stock')
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                    <div>
                        <h2 class="text-lg font-bold text-slate-950 dark:text-white">{{ __('Stock Movement Audit Log & Branch Transfers') }}</h2>
                        <p class="text-sm text-slate-500">{{ __('Real-time log of inbound PO receipts, warehouse transfers, and POS sales dispatches.') }}</p>
                    </div>
                    <button type="button" wire:click="transferStock('POS-HDW-004', 'Lagos HQ Central Warehouse', 'Abuja Regional Distribution Hub', 10)" class="rounded-xl bg-orange-600 px-4 py-2 text-xs font-bold text-white hover:bg-orange-700">
                        <i class="fa-light fa-right-left mr-1.5"></i>{{ __('Transfer Stock') }}
                    </button>
                </div>
                <div class="mt-5 overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50 text-xs uppercase text-slate-400 dark:bg-slate-800">
                            <tr>
                                <th class="px-4 py-3.5">Timestamp</th>
                                <th class="px-4 py-3.5">SKU Code</th>
                                <th class="px-4 py-3.5">Product</th>
                                <th class="px-4 py-3.5">Movement Type</th>
                                <th class="px-4 py-3.5">Quantity</th>
                                <th class="px-4 py-3.5">Origin & Destination</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @foreach ($stockMovements as $move)
                                <tr class="transition hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                                    <td class="px-4 py-3.5 text-slate-500 text-xs">{{ $move['date'] }}</td>
                                    <td class="px-4 py-3.5 font-mono font-bold text-slate-900 dark:text-white">{{ $move['sku'] }}</td>
                                    <td class="px-4 py-3.5 font-bold">{{ $move['product'] }}</td>
                                    <td class="px-4 py-3.5">
                                        <span class="rounded-full px-2.5 py-0.5 text-xs font-bold {{ $move['qty'] > 0 ? 'bg-emerald-500/10 text-emerald-600 border border-emerald-500/20' : 'bg-rose-500/10 text-rose-600 border border-rose-500/20' }}">
                                            {{ $move['type'] }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3.5 font-black {{ $move['qty'] > 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                        {{ $move['qty'] > 0 ? '+'.$move['qty'] : $move['qty'] }} units
                                    </td>
                                    <td class="px-4 py-3.5 text-slate-500 text-xs font-semibold">{{ $move['origin'] }} &rarr; {{ $move['destination'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        @elseif ($activeTab === 'warehouses')
            <div class="space-y-6">
                <!-- Warehouse Availability Section -->
                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                        <div>
                            <h2 class="text-lg font-bold text-slate-950 dark:text-white">{{ __('Warehouse Availability & Regional Capacity') }}</h2>
                            <p class="text-sm text-slate-500">{{ __('Monitor storage utilization %, manager contacts, and inter-branch transfers.') }}</p>
                        </div>
                        <button type="button" wire:click="transferStock('POS-HDW-004', 'Lagos HQ Central Warehouse', 'Abuja Regional Distribution Hub', 15)" class="rounded-xl bg-orange-600 px-4 py-2 text-xs font-bold text-white hover:bg-orange-700">
                            <i class="fa-light fa-arrows-left-right mr-1.5"></i>{{ __('Initiate Warehouse Transfer') }}
                        </button>
                    </div>

                    <div class="mt-5 grid gap-4 md:grid-cols-3">
                        @foreach ($warehouses as $wh)
                            <div class="rounded-2xl border border-slate-200 p-5 dark:border-slate-800 shadow-2xs">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold uppercase text-orange-600">{{ $wh['skus'] }} Active SKUs</span>
                                    <span class="rounded-full bg-emerald-500/10 text-emerald-600 border border-emerald-500/20 px-2.5 py-0.5 text-[10px] font-bold">{{ $wh['status'] }}</span>
                                </div>
                                <h3 class="mt-3 text-base font-bold text-slate-900 dark:text-white">{{ $wh['name'] }}</h3>
                                <p class="mt-1 text-xs text-slate-500"><i class="fa-light fa-location-dot mr-1"></i>{{ $wh['location'] }}</p>
                                <p class="mt-1 text-xs text-slate-400"><i class="fa-light fa-user-gear mr-1"></i>Manager: {{ $wh['manager'] }} ({{ $wh['contact'] }})</p>

                                <div class="mt-4 pt-3 border-t dark:border-slate-800">
                                    <div class="flex justify-between text-xs font-bold mb-1">
                                        <span class="text-slate-500">Storage Capacity</span>
                                        <span class="text-orange-600">{{ $wh['capacity'] }}%</span>
                                    </div>
                                    <div class="h-2 w-full overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800">
                                        <div class="h-full bg-orange-500" style="width: {{ $wh['capacity'] }}%;"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>

                <!-- Supplier Management Directory -->
                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                        <div>
                            <h2 class="text-lg font-bold text-slate-950 dark:text-white">{{ __('Suppliers Directory & Lead Times') }}</h2>
                            <p class="text-sm text-slate-500">{{ __('Approved hardware manufacturers, lead time days, and bulk PO ordering.') }}</p>
                        </div>
                        <button type="button" wire:click="orderSupplierStock('Apex Hardware Supplies Ltd', 'POS-HDW-004')" class="rounded-xl border border-orange-200 bg-orange-50 px-4 py-2 text-xs font-bold text-orange-700 hover:bg-orange-100 dark:bg-orange-950/40 dark:text-orange-300 dark:border-orange-800">
                            <i class="fa-light fa-cart-shopping mr-1.5"></i>{{ __('Order Bulk Supplier Stock') }}
                        </button>
                    </div>

                    <div class="mt-5 overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-slate-50 text-xs uppercase text-slate-400 dark:bg-slate-800">
                                <tr>
                                    <th class="px-4 py-3.5">Supplier Name</th>
                                    <th class="px-4 py-3.5">Supply Category</th>
                                    <th class="px-4 py-3.5">Contact & Email</th>
                                    <th class="px-4 py-3.5">Lead Time</th>
                                    <th class="px-4 py-3.5">Rating</th>
                                    <th class="px-4 py-3.5 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                @foreach ($suppliers as $sup)
                                    <tr class="transition hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                                        <td class="px-4 py-3.5 font-bold text-slate-900 dark:text-white">{{ $sup['name'] }}</td>
                                        <td class="px-4 py-3.5 text-slate-500 text-xs font-semibold">{{ $sup['category'] }}</td>
                                        <td class="px-4 py-3.5 text-slate-500 text-xs">{{ $sup['contact'] }} ({{ $sup['email'] }})</td>
                                        <td class="px-4 py-3.5 font-bold text-emerald-600 text-xs">{{ $sup['lead_time'] }}</td>
                                        <td class="px-4 py-3.5 font-bold text-amber-500 text-xs"><i class="fa-solid fa-star mr-1"></i>{{ $sup['rating'] }}</td>
                                        <td class="px-4 py-3.5 text-right">
                                            <button type="button" wire:click="orderSupplierStock('{{ $sup['name'] }}', 'POS-HDW-004')" class="text-xs font-bold text-orange-600 hover:underline">
                                                Issue Bulk PO
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        @else
            <section class="rounded-2xl border border-slate-200 bg-white p-8 text-center shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <i class="fa-light fa-file-export text-5xl text-orange-500"></i>
                <h3 class="mt-3 text-lg font-bold text-slate-950 dark:text-white">{{ __('Bulk Product CSV Import / Export') }}</h3>
                <p class="mt-1 text-sm text-slate-500">{{ __('Upload CSV file to update inventory stock quantities or download CSV backup.') }}</p>
                <div class="mt-6 flex justify-center gap-4">
                    <a href="{{ route('portal.finance.export-csv') }}" class="rounded-2xl bg-orange-600 px-6 py-3 text-sm font-bold text-white shadow-md hover:bg-orange-700"><i class="fa-light fa-download mr-2"></i>Download Inventory CSV</a>
                </div>
            </section>
        @endif
    @endif

    <!-- MODULE 3: SALES ORDERS & REVENUE ENHANCED -->
    @if ($moduleKey === 'sales')
        @if ($activeTab === 'orders')
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                    <div>
                        <h2 class="text-lg font-bold text-slate-950 dark:text-white">{{ __('Confirmed Sales Orders & Fulfilment') }}</h2>
                        <p class="text-sm text-slate-500">{{ __('Track confirmed customer sales orders, fulfilment status, and status updates.') }}</p>
                    </div>
                    <button type="button" wire:click="openCreateModal('sales_order')" class="rounded-xl bg-blue-600 px-4 py-2 text-xs font-bold text-white hover:bg-blue-700">
                        <i class="fa-light fa-plus mr-2"></i>{{ __('Create Sales Order') }}
                    </button>
                </div>
                <div class="mt-5 overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50 text-xs uppercase text-slate-400 dark:bg-slate-800">
                            <tr>
                                <th class="px-4 py-3.5">Order #</th>
                                <th class="px-4 py-3.5">Customer Name</th>
                                <th class="px-4 py-3.5">Order Date</th>
                                <th class="px-4 py-3.5">Total Amount (NGN)</th>
                                <th class="px-4 py-3.5">Status</th>
                                <th class="px-4 py-3.5 text-right">Update Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @foreach ($salesOrders as $index => $so)
                                <tr class="transition hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                                    <td class="px-4 py-3.5 font-mono font-bold text-slate-900 dark:text-white">{{ $so['id'] }}</td>
                                    <td class="px-4 py-3.5 font-bold">{{ $so['customer'] }}</td>
                                    <td class="px-4 py-3.5 text-slate-500">{{ $so['date'] }}</td>
                                    <td class="px-4 py-3.5 font-black text-slate-900 dark:text-white">₦{{ number_format($so['amount'], 2) }}</td>
                                    <td class="px-4 py-3.5">
                                        <span class="rounded-full px-3 py-1 text-xs font-bold {{ $so['status'] === 'Confirmed' ? 'bg-emerald-500/10 text-emerald-600 border border-emerald-500/20' : ($so['status'] === 'Fulfilled' ? 'bg-blue-500/10 text-blue-600 border border-blue-500/20' : 'bg-slate-100 text-slate-600') }}">
                                            {{ $so['status'] }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3.5 text-right space-x-2">
                                        @if ($so['status'] !== 'Fulfilled')
                                            <button type="button" wire:click="updateOrderStatus({{ $index }}, 'Fulfilled')" class="text-xs font-bold text-emerald-600 hover:underline">
                                                Mark Fulfilled
                                            </button>
                                        @else
                                            <span class="text-xs font-bold text-slate-400">Complete</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        @else
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h2 class="text-lg font-bold text-slate-950 dark:text-white border-b pb-4 dark:border-slate-800">{{ __(ucfirst($activeTab).' Sales View') }}</h2>
                <p class="mt-4 text-sm text-slate-500">{{ __('Pipeline forecasting and deal stage metrics active.') }}</p>
            </section>
        @endif
    @endif

    <!-- MODULE 6: POINT OF SALE (POS) ENHANCED USERFLOW -->
    @if ($moduleKey === 'pos')
        @if ($activeTab === 'checkout')
            <div class="space-y-6">
                <!-- Fast Barcode Quick Scanner Bar -->
                <section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <form wire:submit.prevent="scanBarcode" class="flex flex-col sm:flex-row items-center gap-3">
                        <div class="relative w-full">
                            <i class="fa-light fa-barcode absolute left-4 top-3.5 text-base text-orange-500"></i>
                            <input
                                type="text"
                                wire:model="barcodeScannerInput"
                                placeholder="Scan item barcode scanner or enter SKU (e.g. POS-HDW-004)..."
                                class="w-full rounded-2xl border border-slate-200 pl-11 pr-4 py-3 text-sm font-semibold outline-none focus:border-orange-500 dark:border-slate-800 dark:bg-slate-800 dark:text-white"
                            >
                        </div>
                        <button type="submit" class="w-full sm:w-auto shrink-0 rounded-2xl bg-orange-600 px-6 py-3 text-xs font-bold text-white shadow-md hover:bg-orange-700 transition">
                            <i class="fa-light fa-plus mr-1.5"></i>{{ __('Scan Barcode') }}
                        </button>
                    </form>
                </section>

                <div class="grid gap-6 xl:grid-cols-[1.2fr_0.8fr]">
                    <!-- POS Terminal Checkout Products Grid -->
                    <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                            <div>
                                <h2 class="text-lg font-bold text-slate-950 dark:text-white">{{ __('POS Retail Checkout Terminal') }}</h2>
                                <p class="text-sm text-slate-500">{{ __('Click items below to add to customer cart with instant stock decrement.') }}</p>
                            </div>
                            <span class="rounded-full bg-emerald-500/10 border border-emerald-500/20 px-3 py-1 text-xs font-bold text-emerald-600">Terminal #01 Active</span>
                        </div>

                        <div class="mt-5 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach ([
                                ['sku' => 'POS-HDW-004', 'name' => 'Barcode Scanner Unit', 'price' => 85000.00],
                                ['sku' => 'ENT-LIC-001', 'name' => 'Enterprise License', 'price' => 250000.00],
                                ['sku' => 'REC-PRN-002', 'name' => 'Thermal Receipt Printer', 'price' => 45000.00],
                                ['sku' => 'CSH-DRW-009', 'name' => 'Heavy Duty Cash Drawer', 'price' => 38000.00],
                                ['sku' => 'POS-DIS-012', 'name' => 'Customer Touch Display', 'price' => 120000.00],
                                ['sku' => 'SUP-PAP-100', 'name' => 'Receipt Roll Pack (10x)', 'price' => 12000.00],
                            ] as $product)
                                <button type="button" wire:click="addToPosCart('{{ $product['sku'] }}', '{{ $product['name'] }}', {{ $product['price'] }})" class="group rounded-2xl border border-slate-200 p-4 text-left shadow-2xs transition-all duration-200 hover:scale-[1.02] hover:border-orange-500 hover:shadow-md dark:border-slate-800 dark:hover:border-orange-500">
                                    <p class="text-xs font-mono font-bold text-slate-400">{{ $product['sku'] }}</p>
                                    <p class="mt-2 text-sm font-bold text-slate-900 group-hover:text-orange-600 dark:text-white">{{ $product['name'] }}</p>
                                    <p class="mt-2 text-base font-black text-orange-600">₦{{ number_format($product['price'], 2) }}</p>
                                </button>
                            @endforeach
                        </div>
                    </section>

                    <!-- Active Cart Drawer with Customer Info, Discounts & Payment Method Options -->
                    <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                            <h2 class="text-lg font-bold text-slate-950 dark:text-white">{{ __('Cart & Checkout Options') }}</h2>
                            @if (!empty($posCart))
                                <button type="button" wire:click="clearPosCart" class="text-xs font-bold text-rose-500 hover:underline">{{ __('Clear Cart') }}</button>
                            @endif
                        </div>

                        @if (empty($posCart))
                            <div class="my-12 text-center text-slate-400">
                                <i class="fa-light fa-basket-shopping text-5xl"></i>
                                <p class="mt-3 text-sm font-medium">{{ __('Cart is empty. Click products on left to add.') }}</p>
                            </div>
                        @else
                            <!-- Customer Details Input -->
                            <div class="mt-4 grid gap-2 sm:grid-cols-2">
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-400">Customer Name</label>
                                    <input type="text" wire:model="customerName" placeholder="Walk-in Retail Client" class="mt-0.5 w-full rounded-xl border border-slate-200 p-2 text-xs font-semibold outline-none dark:bg-slate-800 dark:border-slate-700 dark:text-white">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-400">Phone / Email (e-Receipt)</label>
                                    <input type="text" wire:model="customerContact" placeholder="08031234567" class="mt-0.5 w-full rounded-xl border border-slate-200 p-2 text-xs font-semibold outline-none dark:bg-slate-800 dark:border-slate-700 dark:text-white">
                                </div>
                            </div>

                            <div class="mt-4 divide-y divide-slate-100 dark:divide-slate-800 max-h-64 overflow-y-auto">
                                @foreach ($posCart as $index => $cartItem)
                                    <div class="flex items-center justify-between py-3">
                                        <div>
                                            <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $cartItem['name'] }}</p>
                                            <p class="text-xs text-slate-400">₦{{ number_format($cartItem['price'], 2) }} x {{ $cartItem['quantity'] }}</p>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <button type="button" wire:click="updatePosCartQuantity({{ $index }}, {{ $cartItem['quantity'] - 1 }})" class="h-7 w-7 rounded-lg bg-slate-100 font-black text-slate-600 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300">-</button>
                                            <span class="text-sm font-black">{{ $cartItem['quantity'] }}</span>
                                            <button type="button" wire:click="updatePosCartQuantity({{ $index }}, {{ $cartItem['quantity'] + 1 }})" class="h-7 w-7 rounded-lg bg-slate-100 font-black text-slate-600 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300">+</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Loyalty Discount Selector -->
                            <div class="mt-4 border-t pt-3 dark:border-slate-800">
                                <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Apply Loyalty Discount</label>
                                <div class="flex items-center gap-2">
                                    @foreach ([0, 5, 10, 15] as $disc)
                                        <button type="button" wire:click="setPosDiscount({{ $disc }})" class="rounded-xl px-3 py-1 text-xs font-bold border transition {{ $posDiscountPercent === (float)$disc ? 'bg-orange-600 text-white border-orange-600' : 'border-slate-200 text-slate-600 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300' }}">
                                            {{ $disc === 0 ? 'None' : $disc.'%' }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Payment Method Selector -->
                            <div class="mt-3">
                                <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Payment Method</label>
                                <select wire:model.live="posPaymentMethod" class="w-full rounded-xl border border-slate-200 p-2 text-xs font-bold outline-none dark:bg-slate-800 dark:border-slate-700 dark:text-white">
                                    <option value="card">Card (POS Terminal)</option>
                                    <option value="cash">Cash Payment</option>
                                    <option value="bank_transfer">Instant Bank Transfer</option>
                                </select>
                            </div>

                            @php
                                $sub = array_reduce($posCart, fn ($acc, $item) => $acc + ($item['price'] * $item['quantity']), 0.0);
                                $discAmt = $sub * ($posDiscountPercent / 100.0);
                                $taxable = $sub - $discAmt;
                                $tax = $taxable * $posTaxRate;
                                $tot = $taxable + $tax;
                            @endphp

                            <div class="mt-4 space-y-1.5 border-t pt-3 text-sm dark:border-slate-800">
                                <div class="flex justify-between text-slate-500"><span>Subtotal</span><span>₦{{ number_format($sub, 2) }}</span></div>
                                @if ($posDiscountPercent > 0)
                                    <div class="flex justify-between text-emerald-600 font-bold"><span>Discount ({{ $posDiscountPercent }}%)</span><span>-₦{{ number_format($discAmt, 2) }}</span></div>
                                @endif
                                <div class="flex justify-between text-slate-500"><span>VAT (7.5%)</span><span>₦{{ number_format($tax, 2) }}</span></div>
                                <div class="flex justify-between font-black text-base text-slate-900 dark:text-white pt-2 border-t dark:border-slate-800">
                                    <span>Total Payable</span>
                                    <span class="text-orange-600">₦{{ number_format($tot, 2) }}</span>
                                </div>
                            </div>

                            <button type="button" wire:click="checkoutPos" class="mt-5 w-full rounded-2xl bg-orange-600 py-3.5 text-center text-sm font-black text-white shadow-lg shadow-orange-500/20 hover:bg-orange-700 transition">
                                <i class="fa-light fa-print mr-2"></i>{{ __('Complete Sale & Print Receipt') }}
                            </button>
                        @endif
                    </section>
                </div>
            </div>
        @elseif ($activeTab === 'receipts')
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                    <div>
                        <h2 class="text-lg font-bold text-slate-950 dark:text-white">{{ __('Historical Sales Receipts') }}</h2>
                        <p class="text-sm text-slate-500">{{ __('View past cashier POS transaction receipts and dispatch electronic e-receipts.') }}</p>
                    </div>
                </div>

                <div class="mt-5 overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50 text-xs uppercase text-slate-400 dark:bg-slate-800">
                            <tr>
                                <th class="px-4 py-3.5">Receipt #</th>
                                <th class="px-4 py-3.5">Cashier Name</th>
                                <th class="px-4 py-3.5">Payment Method</th>
                                <th class="px-4 py-3.5">Total Amount</th>
                                <th class="px-4 py-3.5">Date</th>
                                <th class="px-4 py-3.5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @foreach ($dbPosReceipts as $rec)
                                <tr class="transition hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                                    <td class="px-4 py-3.5 font-mono font-bold text-slate-900 dark:text-white">{{ $rec->receipt_number }}</td>
                                    <td class="px-4 py-3.5 font-bold">{{ $rec->cashier_name ?: 'Ascend Cashier' }}</td>
                                    <td class="px-4 py-3.5">
                                        <span class="rounded-full px-2.5 py-0.5 text-xs font-bold bg-orange-500/10 text-orange-600 border border-orange-500/20">
                                            {{ ucfirst(str_replace('_', ' ', $rec->payment_method ?: 'card')) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3.5 font-black text-slate-900 dark:text-white">₦{{ number_format($rec->total, 2) }}</td>
                                    <td class="px-4 py-3.5 text-slate-500 text-xs">{{ $rec->created_at?->format('Y-m-d H:i') ?: now()->format('Y-m-d H:i') }}</td>
                                    <td class="px-4 py-3.5 text-right space-x-3">
                                        <button type="button" wire:click="sendDigitalReceipt('{{ $rec->receipt_number }}', 'customer@ascendsystems.ng')" class="text-xs font-bold text-blue-600 hover:underline">
                                            <i class="fa-light fa-paper-plane mr-1"></i>e-Receipt
                                        </button>
                                        <button type="button" wire:click="reprintPosReceipt('{{ $rec->receipt_number }}')" class="text-xs font-bold text-orange-600 hover:underline">
                                            <i class="fa-light fa-print mr-1"></i>Reprint Receipt
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        @elseif ($activeTab === 'barcodes')
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                    <div>
                        <h2 class="text-lg font-bold text-slate-950 dark:text-white">{{ __('Barcode & Thermal Label Printing Studio') }}</h2>
                        <p class="text-sm text-slate-500">{{ __('Generate Code128 barcodes and print price labels for POS hardware & inventory items.') }}</p>
                    </div>
                </div>

                <div class="mt-6 grid gap-6 md:grid-cols-2">
                    <!-- Barcode Thermal Card Visual Preview -->
                    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6 text-center dark:border-slate-800 dark:bg-slate-800/50">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Code128 Barcode Label Preview</span>

                        <div class="my-6 mx-auto w-64 rounded-2xl bg-white p-5 border border-slate-300 shadow-sm text-slate-950">
                            <p class="text-xs font-extrabold tracking-tight">ASCEND SYSTEMS NIGERIA</p>
                            <p class="text-sm font-bold mt-1 text-slate-800">Barcode Scanner Unit</p>

                            <!-- CSS Barcode Visual Mock -->
                            <div class="my-3 flex items-center justify-center gap-1 font-mono text-2xl font-black tracking-widest text-slate-900">
                                |||| | ||| |||| | || ||||
                            </div>

                            <p class="text-xs font-mono font-extrabold text-slate-600">*{{ $selectedBarcodeSku }}*</p>
                            <p class="mt-2 text-lg font-black text-orange-600">PRICE: ₦85,000.00</p>
                        </div>

                        <div class="flex justify-center gap-3">
                            <button type="button" wire:click="printBarcodeLabel('{{ $selectedBarcodeSku }}', 1)" class="rounded-xl bg-orange-600 px-5 py-2.5 text-xs font-bold text-white shadow-md hover:bg-orange-700">
                                <i class="fa-light fa-print mr-1.5"></i>Print Thermal Label
                            </button>
                            <button type="button" wire:click="printBarcodeLabel('{{ $selectedBarcodeSku }}', 50)" class="rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 dark:bg-slate-800 dark:border-slate-700 dark:text-white">
                                <i class="fa-light fa-copy mr-1.5"></i>Batch 50 Labels
                            </button>
                        </div>
                    </div>

                    <!-- Select SKU to Print -->
                    <div class="space-y-4 rounded-3xl border border-slate-200 p-6 dark:border-slate-800">
                        <h3 class="text-base font-bold text-slate-900 dark:text-white">Select Product SKU for Barcode Label</h3>
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Target Product SKU</label>
                            <select wire:model.live="selectedBarcodeSku" class="w-full rounded-xl border border-slate-200 p-3 text-xs font-bold outline-none dark:bg-slate-800 dark:border-slate-700 dark:text-white">
                                <option value="POS-HDW-004">POS-HDW-004 — Barcode Scanner Unit</option>
                                <option value="ENT-LIC-001">ENT-LIC-001 — Enterprise License</option>
                                <option value="REC-PRN-002">REC-PRN-002 — Thermal Receipt Printer</option>
                                <option value="CSH-DRW-009">CSH-DRW-009 — Heavy Duty Cash Drawer</option>
                            </select>
                        </div>
                    </div>
                </div>
            </section>
        @else
            <!-- POS Insights & Shift Logs -->
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                    <div>
                        <h2 class="text-lg font-bold text-slate-950 dark:text-white">{{ __('POS Insights & Cash Register Shift Logs') }}</h2>
                        <p class="text-sm text-slate-500">{{ __('Reconcile daily cash float, card POS collections, and close register shifts.') }}</p>
                    </div>
                    <button type="button" wire:click="closeShiftRegister" class="rounded-xl bg-orange-600 px-4 py-2 text-xs font-bold text-white hover:bg-orange-700">
                        <i class="fa-light fa-lock mr-1.5"></i>{{ __('End Shift & Reconcile Register') }}
                    </button>
                </div>

                <div class="mt-6 grid gap-4 md:grid-cols-4">
                    <div class="rounded-2xl border border-slate-200 p-5 dark:border-slate-800">
                        <p class="text-xs font-bold text-slate-400 uppercase">Opening Cash Float</p>
                        <p class="mt-2 text-2xl font-black text-slate-900 dark:text-white">₦50,000.00</p>
                        <p class="mt-1 text-xs text-slate-500">Shift Started: 08:00 AM</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 p-5 dark:border-slate-800">
                        <p class="text-xs font-bold text-slate-400 uppercase">Total Card Collections</p>
                        <p class="mt-2 text-2xl font-black text-blue-600">₦1,280,000.00</p>
                        <p class="mt-1 text-xs text-slate-500">From 42 transactions</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 p-5 dark:border-slate-800">
                        <p class="text-xs font-bold text-slate-400 uppercase">Total Cash Collected</p>
                        <p class="mt-2 text-2xl font-black text-emerald-600">₦450,000.00</p>
                        <p class="mt-1 text-xs text-slate-500">18 cash sales</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 p-5 dark:border-slate-800">
                        <p class="text-xs font-bold text-slate-400 uppercase">Expected Drawer Closing</p>
                        <p class="mt-2 text-2xl font-black text-orange-600">₦1,780,000.00</p>
                        <p class="mt-1 text-xs text-slate-500">Balanced</p>
                    </div>
                </div>
            </section>
        @endif
    @endif

    <!-- MODULE 7: MARKETING & SOCIAL CAMPAIGN HUB ENHANCED USERFLOW -->
    @if ($moduleKey === 'marketing')
        @if ($activeTab === 'campaigns')
            <div class="space-y-6">
                <!-- ROAS & Performance Overview Cards -->
                <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">{{ __('Total Ad Spend') }}</p>
                        <p class="mt-2 text-2xl font-black text-purple-600">₦3,700,000.00</p>
                        <p class="mt-1 text-xs font-medium text-purple-500"><i class="fa-light fa-bullhorn mr-1"></i>3 Active Campaigns</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">{{ __('Leads Generated') }}</p>
                        <p class="mt-2 text-2xl font-black text-blue-600">440 Leads</p>
                        <p class="mt-1 text-xs font-medium text-emerald-500"><i class="fa-light fa-arrow-trend-up mr-1"></i>+24.5% conversion rate</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">{{ __('Average CAC') }}</p>
                        <p class="mt-2 text-2xl font-black text-slate-900 dark:text-white">₦8,409.00</p>
                        <p class="mt-1 text-xs font-medium text-slate-400">Cost per acquired lead</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">{{ __('ROAS Performance') }}</p>
                        <p class="mt-2 text-2xl font-black text-emerald-600">4.8x ROAS</p>
                        <p class="mt-1 text-xs font-medium text-emerald-500">₦17,760,000 Revenue</p>
                    </div>
                </div>

                <!-- Campaigns Table -->
                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                        <div>
                            <h2 class="text-lg font-bold text-slate-950 dark:text-white">{{ __('Multi-Channel Marketing Campaigns') }}</h2>
                            <p class="text-sm text-slate-500">{{ __('Track paid ad budgets, lead acquisition, and ROAS across social & search channels.') }}</p>
                        </div>
                        <button type="button" wire:click="openCreateModal('marketing')" class="rounded-xl bg-purple-600 px-4 py-2.5 text-xs font-bold text-white shadow-md hover:bg-purple-700 transition">
                            <i class="fa-light fa-plus mr-1.5"></i>{{ __('New Campaign') }}
                        </button>
                    </div>

                    <div class="mt-5 overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-slate-50 text-xs uppercase text-slate-400 dark:bg-slate-800">
                                <tr>
                                    <th class="px-4 py-3.5">Campaign Name</th>
                                    <th class="px-4 py-3.5">Channel Distribution</th>
                                    <th class="px-4 py-3.5">Budget (NGN)</th>
                                    <th class="px-4 py-3.5">Leads</th>
                                    <th class="px-4 py-3.5">Status</th>
                                    <th class="px-4 py-3.5 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                @foreach ($marketingCampaigns as $index => $camp)
                                    <tr class="transition hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                                        <td class="px-4 py-3.5 font-bold text-slate-900 dark:text-white">{{ $camp['name'] }}</td>
                                        <td class="px-4 py-3.5 text-xs font-semibold text-slate-500">{{ $camp['channel'] }}</td>
                                        <td class="px-4 py-3.5 font-black text-slate-900 dark:text-white">₦{{ number_format($camp['budget'], 2) }}</td>
                                        <td class="px-4 py-3.5 font-bold text-blue-600">{{ $camp['leads'] }} Leads</td>
                                        <td class="px-4 py-3.5">
                                            <span class="rounded-full px-3 py-1 text-xs font-bold {{ $camp['status'] === 'Active' ? 'bg-emerald-500/10 text-emerald-600 border border-emerald-500/20' : 'bg-amber-500/10 text-amber-600 border border-amber-500/20' }}">
                                                {{ $camp['status'] }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3.5 text-right space-x-2">
                                            <button type="button" wire:click="toggleCampaignStatus({{ $index }})" class="text-xs font-bold text-purple-600 hover:underline">
                                                {{ $camp['status'] === 'Active' ? 'Pause' : 'Activate' }}
                                            </button>
                                            <button type="button" wire:click="adjustCampaignBudget({{ $index }}, 500000.00)" class="text-xs font-bold text-emerald-600 hover:underline">
                                                +₦500k
                                            </button>
                                            <button type="button" wire:click="duplicateCampaign({{ $index }})" class="text-xs font-bold text-slate-500 hover:underline">
                                                Clone
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        @elseif ($activeTab === 'social')
            <div class="space-y-6">
                <!-- Connected Channels Grid -->
                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                        <div>
                            <h2 class="text-lg font-bold text-slate-950 dark:text-white">{{ __('Social Media Channel Management') }}</h2>
                            <p class="text-sm text-slate-500">{{ __('Connected social profiles, audience reach, and channel API integration.') }}</p>
                        </div>
                    </div>

                    <div class="mt-5 grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                        @foreach ($socialChannels as $chan)
                            <div class="rounded-2xl border border-slate-200 p-5 dark:border-slate-800 shadow-2xs">
                                <div class="flex items-center justify-between">
                                    <i class="{{ $chan['icon'] }} text-2xl"></i>
                                    <span class="rounded-full bg-emerald-500/10 text-emerald-600 border border-emerald-500/20 px-2.5 py-0.5 text-[10px] font-bold">{{ $chan['status'] }}</span>
                                </div>
                                <h3 class="mt-3 text-base font-bold text-slate-900 dark:text-white">{{ $chan['name'] }}</h3>
                                <p class="mt-0.5 text-xs font-semibold text-slate-400">{{ $chan['handle'] }}</p>
                                <p class="mt-3 text-xl font-black text-slate-900 dark:text-white">{{ $chan['followers'] }} <span class="text-xs font-normal text-slate-400">Followers</span></p>
                                <button type="button" wire:click="syncChannelStats('{{ $chan['platform'] }}')" class="mt-4 w-full rounded-xl border border-slate-200 py-2 text-center text-xs font-bold text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200">
                                    <i class="fa-light fa-arrows-rotate mr-1"></i>Sync Analytics
                                </button>
                            </div>
                        @endforeach
                    </div>
                </section>

                <!-- Connect New Social Account Form -->
                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <h3 class="text-base font-bold text-slate-900 dark:text-white border-b pb-3 dark:border-slate-800">Connect New Social Media Account</h3>
                    <form wire:submit.prevent="connectSocialChannel('Facebook', '@ascend_new')" class="mt-4 flex flex-col sm:flex-row items-center gap-3">
                        <select class="w-full sm:w-48 rounded-xl border border-slate-200 p-3 text-xs font-bold outline-none dark:bg-slate-800 dark:border-slate-700 dark:text-white">
                            <option value="Facebook">Meta / Facebook Page</option>
                            <option value="Instagram">Instagram Business</option>
                            <option value="LinkedIn">LinkedIn Company</option>
                            <option value="Twitter">Twitter/X Enterprise</option>
                            <option value="TikTok">TikTok Business</option>
                        </select>
                        <input type="text" placeholder="Enter channel handle (e.g. @AscendSystemsNG)..." class="w-full rounded-xl border border-slate-200 p-3 text-xs font-semibold outline-none dark:bg-slate-800 dark:border-slate-700 dark:text-white">
                        <button type="submit" class="w-full sm:w-auto shrink-0 rounded-xl bg-purple-600 px-6 py-3 text-xs font-bold text-white shadow-md hover:bg-purple-700">
                            <i class="fa-light fa-link mr-1.5"></i>Connect Channel
                        </button>
                    </form>
                </section>
            </div>
        @elseif ($activeTab === 'blasts')
            <div class="space-y-6">
                <!-- Create & Dispatch Audience Blast -->
                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                        <div>
                            <h2 class="text-lg font-bold text-slate-950 dark:text-white">{{ __('Create & Dispatch Audience Broadcast') }}</h2>
                            <p class="text-sm text-slate-500">{{ __('Dispatch targeted email & SMS marketing blasts to subscribers & CRM leads.') }}</p>
                        </div>
                    </div>

                    <form wire:submit.prevent="sendAudienceBlast" class="mt-5 space-y-4">
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Target Subscriber Segment</label>
                                <select wire:model="blastForm.segment" class="w-full rounded-xl border border-slate-200 p-3 text-xs font-bold outline-none dark:bg-slate-800 dark:border-slate-700 dark:text-white">
                                    <option value="All Active Clients (4,850 Subscribers)">All Active Clients (4,850 Subscribers)</option>
                                    <option value="Qualified CRM Leads (1,240 Subscribers)">Qualified CRM Leads (1,240 Subscribers)</option>
                                    <option value="Retail POS Merchants">Retail POS Merchants (850 Subscribers)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Broadcast Channel</label>
                                <select wire:model="blastForm.channel" class="w-full rounded-xl border border-slate-200 p-3 text-xs font-bold outline-none dark:bg-slate-800 dark:border-slate-700 dark:text-white">
                                    <option value="email">Email Broadcast</option>
                                    <option value="sms">SMS Text Alert</option>
                                    <option value="whatsapp">WhatsApp Business API</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Subject Line (Variant A)</label>
                            <input type="text" wire:model="blastForm.subject" placeholder="Special Announcement: New POS & AI Features Released!" class="w-full rounded-xl border border-slate-200 p-3 text-xs font-semibold outline-none dark:bg-slate-800 dark:border-slate-700 dark:text-white">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Message Content</label>
                            <textarea wire:model="blastForm.message" rows="3" class="w-full rounded-xl border border-slate-200 p-3 text-xs font-medium outline-none dark:bg-slate-800 dark:border-slate-700 dark:text-white"></textarea>
                        </div>

                        <div class="flex flex-wrap items-center justify-between gap-3 border-t pt-4 dark:border-slate-800">
                            <button type="button" wire:click="sendTestBlast" class="rounded-xl border border-slate-200 px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200">
                                <i class="fa-light fa-envelope-open mr-1.5"></i>Send Test Email Preview
                            </button>
                            <button type="submit" class="rounded-xl bg-purple-600 px-6 py-2.5 text-xs font-bold text-white shadow-md hover:bg-purple-700">
                                <i class="fa-light fa-paper-plane mr-1.5"></i>Dispatch Broadcast Now
                            </button>
                        </div>
                    </form>
                </section>

                <!-- Past Audience Blasts History -->
                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <h3 class="text-base font-bold text-slate-950 dark:text-white border-b pb-3 dark:border-slate-800">Past Audience Blasts History</h3>
                    <div class="mt-4 overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-slate-50 text-xs uppercase text-slate-400 dark:bg-slate-800">
                                <tr>
                                    <th class="px-4 py-3">Subject</th>
                                    <th class="px-4 py-3">Target Segment</th>
                                    <th class="px-4 py-3">Delivered</th>
                                    <th class="px-4 py-3">Opened</th>
                                    <th class="px-4 py-3">Date</th>
                                    <th class="px-4 py-3 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                @foreach ($audienceBlasts as $index => $b)
                                    <tr class="transition hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                                        <td class="px-4 py-3.5 font-bold text-slate-900 dark:text-white">{{ $b['subject'] }}</td>
                                        <td class="px-4 py-3.5 text-xs text-slate-500">{{ $b['segment'] }}</td>
                                        <td class="px-4 py-3.5 font-bold text-emerald-600 text-xs">{{ $b['delivered'] }}</td>
                                        <td class="px-4 py-3.5 font-bold text-blue-600 text-xs">{{ $b['opened'] }}</td>
                                        <td class="px-4 py-3.5 text-slate-400 text-xs">{{ $b['date'] }}</td>
                                        <td class="px-4 py-3.5 text-right">
                                            <button type="button" wire:click="resendUnopenedBlast({{ $index }})" class="text-xs font-bold text-purple-600 hover:underline">
                                                Resend Unopened
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        @elseif ($activeTab === 'email')
            <div class="space-y-6">
                <!-- Email Campaign & Newsletter Builder -->
                <div class="grid gap-6 xl:grid-cols-[1.2fr_0.8fr]">
                    <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                            <div>
                                <h2 class="text-lg font-bold text-slate-950 dark:text-white">{{ __('Email Campaign & Newsletter Builder') }}</h2>
                                <p class="text-sm text-slate-500">{{ __('Design newsletters, nurture series, and measure delivery performance.') }}</p>
                            </div>
                            <button type="button" wire:click="openCreateModal('email_campaign')" class="rounded-xl bg-purple-600 px-4 py-2 text-xs font-bold text-white hover:bg-purple-700">
                                <i class="fa-light fa-plus mr-1.5"></i>{{ __('New Template') }}
                            </button>
                        </div>

                        <form wire:submit.prevent="sendEmailCampaign" class="mt-5 space-y-4">
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400">Email Subject Line</label>
                                <input type="text" wire:model="emailForm.subject" placeholder="e.g. 🚀 Q3 Product Update: New POS Thermal & AI Capabilities" required class="mt-1 block w-full rounded-2xl border border-slate-200 px-3.5 py-2.5 text-sm font-semibold outline-none focus:border-purple-500 dark:border-slate-800 dark:bg-slate-800 dark:text-white">
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400">Preheader Preview Text</label>
                                <input type="text" wire:model="emailForm.preheader" placeholder="Discover the latest features added to your Ascend AI workspace..." class="mt-1 block w-full rounded-2xl border border-slate-200 px-3.5 py-2.5 text-xs font-semibold outline-none focus:border-purple-500 dark:border-slate-800 dark:bg-slate-800 dark:text-white">
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400">Email Body Copy</label>
                                <textarea wire:model="emailForm.body" rows="5" placeholder="Dear Client, We are excited to announce our newest enterprise updates..." class="mt-1 block w-full rounded-2xl border border-slate-200 p-3.5 text-xs font-medium outline-none focus:border-purple-500 dark:border-slate-800 dark:bg-slate-800 dark:text-white"></textarea>
                            </div>

                            <div class="grid gap-3 sm:grid-cols-2">
                                <div>
                                    <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400">Call-To-Action Button Text</label>
                                    <input type="text" wire:model="emailForm.cta_text" placeholder="Explore New Features" class="mt-1 block w-full rounded-2xl border border-slate-200 px-3.5 py-2 text-xs font-semibold outline-none focus:border-purple-500 dark:border-slate-800 dark:bg-slate-800 dark:text-white">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400">CTA Destination URL</label>
                                    <input type="text" wire:model="emailForm.cta_url" placeholder="https://ascend.ng/portal/updates" class="mt-1 block w-full rounded-2xl border border-slate-200 px-3.5 py-2 text-xs font-semibold outline-none focus:border-purple-500 dark:border-slate-800 dark:bg-slate-800 dark:text-white">
                                </div>
                            </div>

                            <div class="flex items-center justify-end gap-3 pt-3 border-t dark:border-slate-800">
                                <button type="button" wire:click="saveEmailDraft" class="rounded-2xl border border-slate-200 px-5 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 dark:border-slate-800 dark:text-slate-200">
                                    <i class="fa-light fa-floppy-disk mr-1.5"></i>Save Draft
                                </button>
                                <button type="submit" class="rounded-2xl bg-purple-600 px-6 py-2.5 text-xs font-bold text-white shadow-md hover:bg-purple-700">
                                    <i class="fa-light fa-paper-plane mr-1.5"></i>Send Email Blast
                                </button>
                            </div>
                        </form>
                    </section>

                    <!-- Live Email Preview Card -->
                    <section class="rounded-2xl border border-slate-200 bg-slate-50/50 p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900/50">
                        <div class="flex items-center justify-between border-b pb-3 dark:border-slate-800">
                            <span class="text-xs font-bold uppercase text-slate-400"><i class="fa-light fa-eye mr-1.5"></i>Live Email Preview</span>
                            <span class="rounded-full bg-purple-500/10 px-2.5 py-0.5 text-[10px] font-bold text-purple-600">Responsive Email</span>
                        </div>
                        <div class="mt-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm text-slate-900 dark:bg-slate-950 dark:text-slate-100">
                            <div class="border-b pb-3 text-xs dark:border-slate-800">
                                <p class="font-extrabold text-sm text-purple-600">{{ $emailForm['subject'] ?: 'Subject: Your Email Subject Line Will Appear Here' }}</p>
                                <p class="text-[11px] text-slate-400 mt-1">{{ $emailForm['preheader'] ?: 'Preview text snippet visible in email inbox app...' }}</p>
                            </div>
                            <div class="my-4 text-xs space-y-2 leading-relaxed text-slate-600 dark:text-slate-300">
                                <p>{{ $emailForm['body'] ?: 'Your email body text content will be rendered here with live real-time formatting preview.' }}</p>
                            </div>
                            <div class="my-4 text-center">
                                <a href="#" onclick="return false;" class="inline-block rounded-xl bg-purple-600 px-5 py-2 text-xs font-bold text-white shadow-sm">{{ $emailForm['cta_text'] ?: 'Explore Features' }}</a>
                            </div>
                            <div class="border-t pt-3 text-[10px] text-center text-slate-400 dark:border-slate-800">
                                <p>{{ $emailForm['footer'] }}</p>
                                <p class="mt-0.5">Unsubscribe · Manage Email Preferences</p>
                            </div>
                        </div>
                    </section>
                </div>

                <!-- Template Gallery Cards -->
                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <h3 class="text-base font-bold text-slate-950 dark:text-white border-b pb-3 dark:border-slate-800">{{ __('Email Template Library & Automated Sequences') }}</h3>
                    <div class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                        @foreach ($emailTemplates as $idx => $tmpl)
                            <div class="rounded-2xl border border-slate-200 p-4 transition hover:border-purple-500/40 dark:border-slate-800 dark:hover:border-purple-500/40">
                                <div class="flex items-center justify-between">
                                    <span class="rounded-full bg-purple-500/10 px-2 py-0.5 text-[9px] font-bold text-purple-600">{{ $tmpl['category'] }}</span>
                                    <span class="rounded-full bg-emerald-500/10 px-2 py-0.5 text-[9px] font-bold text-emerald-600">{{ $tmpl['status'] }}</span>
                                </div>
                                <h4 class="mt-3 text-xs font-bold text-slate-900 dark:text-white">{{ $tmpl['name'] }}</h4>
                                <div class="mt-3 grid grid-cols-2 gap-2 text-[10px]">
                                    <div><span class="text-slate-400">Open Rate:</span> <span class="font-bold text-emerald-600">{{ $tmpl['opens'] }}</span></div>
                                    <div><span class="text-slate-400">Click Rate:</span> <span class="font-bold text-purple-600">{{ $tmpl['clicks'] }}</span></div>
                                </div>
                                <div class="mt-3 pt-2 border-t flex justify-end gap-2 dark:border-slate-800">
                                    <button type="button" wire:click="duplicateEmailTemplate({{ $idx }})" class="text-[10px] font-bold text-purple-600 hover:underline"><i class="fa-light fa-copy mr-1"></i>Duplicate</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            </div>
        @elseif ($activeTab === 'whatsapp')
            <div class="space-y-6">
                <!-- WhatsApp Header -->
                <div class="rounded-2xl border border-emerald-200 bg-gradient-to-r from-emerald-50 to-teal-50 p-6 dark:border-emerald-900/40 dark:from-emerald-950/30 dark:to-teal-950/20">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex items-center gap-4">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-600 text-white text-xl shadow-lg">
                                <i class="fa-brands fa-whatsapp"></i>
                            </div>
                            <div>
                                <h2 class="text-lg font-black text-slate-950 dark:text-white">{{ __('WhatsApp Business Automation & Direct Messaging') }}</h2>
                                <p class="text-xs text-slate-500">{{ __('Automate WhatsApp payment requests, receipts, OTPs and broadcast lists') }}</p>
                            </div>
                        </div>
                        <span class="rounded-full bg-emerald-500/10 px-3 py-1 text-xs font-bold text-emerald-600 border border-emerald-500/20">Official API Active</span>
                    </div>
                </div>

                <div class="grid gap-6 lg:grid-cols-[1fr_360px]">
                    <!-- WhatsApp Broadcasts & Templates -->
                    <div class="space-y-6">
                        <!-- Message Templates Grid -->
                        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                            <h3 class="text-sm font-bold text-slate-950 dark:text-white border-b pb-3 dark:border-slate-800 mb-4">{{ __('WhatsApp Message Templates') }}</h3>
                            <div class="grid gap-3 sm:grid-cols-2">
                                @foreach ($whatsappTemplates as $wt)
                                    <div class="rounded-xl border border-slate-200 p-4 dark:border-slate-800 space-y-2">
                                        <div class="flex items-center justify-between">
                                            <span class="rounded-full bg-emerald-500/10 px-2 py-0.5 text-[9px] font-bold text-emerald-600">{{ $wt['category'] ?? 'TRANSACTIONAL' }}</span>
                                            <span class="text-[10px] font-bold text-slate-400">✓ {{ ucfirst($wt['status'] ?? 'approved') }}</span>
                                        </div>
                                        <h4 class="text-xs font-bold text-slate-900 dark:text-white">{{ $wt['name'] }}</h4>
                                        <p class="text-[11px] text-slate-500 bg-slate-50 p-2.5 rounded-lg dark:bg-slate-800/60 font-mono">{{ $wt['body'] }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </section>

                        <!-- WhatsApp Broadcast Log -->
                        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                            <h3 class="text-sm font-bold text-slate-950 dark:text-white border-b pb-3 dark:border-slate-800 mb-4">{{ __('Broadcast History') }}</h3>
                            @if (empty($whatsappBroadcasts))
                                <div class="py-8 text-center text-xs text-slate-400">
                                    <i class="fa-brands fa-whatsapp text-3xl mb-2 text-slate-300"></i>
                                    <p>{{ __('No broadcast campaigns dispatched yet.') }}</p>
                                </div>
                            @else
                                <div class="space-y-3">
                                    @foreach ($whatsappBroadcasts as $wb)
                                        <div class="flex items-center justify-between rounded-xl border border-slate-100 p-3 dark:border-slate-800">
                                            <div>
                                                <p class="text-xs font-bold text-slate-900 dark:text-white">{{ $wb['name'] ?? 'Broadcast' }}</p>
                                                <p class="text-[10px] text-slate-400">{{ $wb['message'] ?? '' }}</p>
                                            </div>
                                            <span class="rounded-full bg-emerald-500/10 px-2.5 py-1 text-[10px] font-bold text-emerald-600">Dispatched</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </section>
                    </div>

                    <!-- Direct Message Composer -->
                    <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 space-y-4">
                        <h3 class="text-sm font-bold text-slate-950 dark:text-white border-b pb-3 dark:border-slate-800">{{ __('Send Direct WhatsApp Message') }}</h3>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1">{{ __('Recipient Phone Number') }}</label>
                            <input wire:model="dmForm.phone" type="text" placeholder="+234 803 123 4567" class="w-full rounded-xl border border-slate-200 p-2.5 text-sm outline-none focus:border-emerald-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white font-mono">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1">{{ __('Message Body') }}</label>
                            <textarea wire:model="dmForm.message" rows="4" placeholder="Type your WhatsApp message..." class="w-full rounded-xl border border-slate-200 p-2.5 text-sm outline-none focus:border-emerald-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white resize-none"></textarea>
                        </div>
                        <button type="button" wire:click="sendWhatsAppDM" class="w-full rounded-xl bg-emerald-600 py-3 text-sm font-bold text-white hover:bg-emerald-700 transition">
                            <i class="fa-brands fa-whatsapp mr-1.5"></i> {{ __('Send WhatsApp Message') }}
                        </button>
                        <button type="button" wire:click="sendWhatsAppBroadcast" class="w-full rounded-xl border border-emerald-200 bg-emerald-50 py-2.5 text-xs font-bold text-emerald-700 hover:bg-emerald-100 dark:border-emerald-900/40 dark:bg-emerald-950/30 dark:text-emerald-300">
                            <i class="fa-light fa-bullhorn mr-1.5"></i> {{ __('Broadcast to All Contacts') }}
                        </button>
                    </section>
                </div>
            </div>

        @elseif ($activeTab === 'ads')
            <div class="space-y-6">
                <!-- Connected Ads Accounts -->
                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800 mb-5">
                        <div>
                            <h2 class="text-lg font-bold text-slate-950 dark:text-white">{{ __('Ad Networks & Multi-Channel Sync') }}</h2>
                            <p class="text-sm text-slate-500">{{ __('Connected ad platforms with live campaign performance and ROAS analytics') }}</p>
                        </div>
                        <button type="button" wire:click="getAiAdsRecommendations" class="rounded-xl bg-purple-600 px-4 py-2 text-xs font-bold text-white hover:bg-purple-700">
                            <i class="fa-light fa-sparkles mr-1.5"></i>{{ __('Get AI Ads Insights') }}
                        </button>
                    </div>

                    <div class="grid gap-4 md:grid-cols-3">
                        @foreach ($adsAccounts as $acc)
                            <div class="rounded-2xl border border-slate-200 p-5 shadow-sm dark:border-slate-800 space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="flex h-10 w-10 items-center justify-center rounded-xl {{ match($acc['platform'] ?? '') { 'meta' => 'bg-blue-500/10 text-blue-600', 'google' => 'bg-red-500/10 text-red-600', default => 'bg-slate-500/10 text-slate-600' } }} text-lg">
                                        <i class="{{ match($acc['platform'] ?? '') { 'meta' => 'fa-brands fa-meta', 'google' => 'fa-brands fa-google', 'tiktok' => 'fa-brands fa-tiktok', default => 'fa-light fa-bullseye' } }}"></i>
                                    </span>
                                    <button type="button" wire:click="syncAdsAccount('{{ $acc['platform'] ?? 'meta' }}')" class="rounded-lg bg-slate-100 px-2.5 py-1 text-[10px] font-bold text-slate-700 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-200">
                                        <i class="fa-light fa-rotate mr-1"></i>Sync
                                    </button>
                                </div>
                                <h3 class="text-sm font-bold text-slate-900 dark:text-white">{{ $acc['account_name'] }}</h3>
                                <div class="grid grid-cols-2 gap-2 text-xs border-t pt-2 dark:border-slate-800">
                                    <div><span class="text-slate-400">Total Spend:</span> <span class="font-bold">₦{{ number_format($acc['total_spend'] ?? 0, 0) }}</span></div>
                                    <div><span class="text-slate-400">ROAS:</span> <span class="font-bold text-emerald-600">{{ $acc['roas'] ?? 0 }}x</span></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>

                <!-- AI Recommendations Panel -->
                @if (!empty($adsRecommendations))
                    <section class="rounded-2xl border border-purple-200 bg-purple-50/50 p-6 dark:border-purple-900/40 dark:bg-purple-950/20">
                        <h3 class="text-sm font-bold text-purple-900 dark:text-purple-200 border-b pb-3 border-purple-200 dark:border-purple-800/40 mb-4">{{ __('AI Ad Performance Recommendations') }}</h3>
                        <div class="grid gap-3 sm:grid-cols-2">
                            @foreach ($adsRecommendations as $rec)
                                <div class="flex items-start gap-3 rounded-xl bg-white p-4 shadow-sm dark:bg-slate-900 border border-slate-100 dark:border-slate-800">
                                    <i class="{{ $rec['icon'] }} text-lg text-purple-600 mt-0.5"></i>
                                    <div>
                                        <p class="text-xs font-bold text-slate-900 dark:text-white">{{ $rec['title'] }}</p>
                                        <p class="mt-1 text-[11px] text-slate-500 leading-relaxed">{{ $rec['body'] }}</p>
                                        <span class="mt-2 inline-block text-[10px] font-bold text-purple-600 hover:underline cursor-pointer">{{ $rec['action'] }} →</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif
            </div>

        @elseif ($activeTab === 'analytics')
            <!-- Campaign Analytics & ROAS Dashboard -->
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                    <div>
                        <h2 class="text-lg font-bold text-slate-950 dark:text-white">{{ __('Campaign Analytics & ROAS Attribution') }}</h2>
                        <p class="text-sm text-slate-500">{{ __('Return on Ad Spend (ROAS) and channel conversion attribution metrics.') }}</p>
                    </div>
                    <a href="{{ route('portal.finance.export-csv') }}" class="rounded-xl bg-purple-600 px-4 py-2 text-xs font-bold text-white hover:bg-purple-700">
                        <i class="fa-light fa-download mr-1.5"></i>Export ROAS Report CSV
                    </a>
                </div>

                <div class="mt-6 grid gap-4 md:grid-cols-4">
                    <div class="rounded-2xl border border-slate-200 p-5 dark:border-slate-800">
                        <p class="text-xs font-bold uppercase text-slate-400">Meta Ads ROAS</p>
                        <p class="mt-2 text-2xl font-black text-emerald-600">4.2x</p>
                        <p class="mt-1 text-xs text-slate-500">Spend: ₦850k · Rev: ₦3.57M</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 p-5 dark:border-slate-800">
                        <p class="text-xs font-bold uppercase text-slate-400">LinkedIn Ads ROAS</p>
                        <p class="mt-2 text-2xl font-black text-blue-600">6.1x</p>
                        <p class="mt-1 text-xs text-slate-500">Spend: ₦2.5M · Rev: ₦15.25M</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 p-5 dark:border-slate-800">
                        <p class="text-xs font-bold uppercase text-slate-400">Email Blast ROAS</p>
                        <p class="mt-2 text-2xl font-black text-purple-600">12.4x</p>
                        <p class="mt-1 text-xs text-slate-500">Spend: ₦350k · Rev: ₦4.34M</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 p-5 dark:border-slate-800">
                        <p class="text-xs font-bold uppercase text-slate-400">Blended Overall ROAS</p>
                        <p class="mt-2 text-2xl font-black text-emerald-600">4.8x</p>
                        <p class="mt-1 text-xs text-slate-500">Net Profit Margin: 79%</p>
                    </div>
                </div>
            </section>
        @endif
    @endif

    <!-- MODULE 8: AI AGENTS ENHANCED -->
    @if ($moduleKey === 'ai-agents')
        @if ($activeTab === 'agents' || empty($activeTab))
            <div class="space-y-6">
                <!-- AI Fleet Overview Header -->
                <div class="rounded-2xl border border-purple-200 bg-gradient-to-r from-purple-50 to-indigo-50 p-6 dark:border-purple-900/40 dark:from-purple-950/30 dark:to-indigo-950/20">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex items-center gap-4">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-purple-600 text-white text-xl shadow-lg">
                                <i class="fa-light fa-sparkles"></i>
                            </div>
                            <div>
                                <h2 class="text-lg font-black text-slate-950 dark:text-white">{{ __('Autonomous AI Agent Fleet') }}</h2>
                                <p class="text-xs text-slate-500">{{ __('6 specialized AI agents running automated workflows across content, finance, CRM & ads') }}</p>
                            </div>
                        </div>
                        <span class="rounded-full bg-purple-500/10 px-3.5 py-1 text-xs font-bold text-purple-600 border border-purple-500/20">6 Agents Active</span>
                    </div>
                </div>

                <!-- Agent Catalog Grid -->
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($agentCatalog as $ag)
                        <div wire:click="$set('selectedAgent', '{{ $ag['id'] }}')" class="cursor-pointer rounded-2xl border p-5 transition-all hover:shadow-md {{ $selectedAgent === $ag['id'] ? 'border-purple-500 bg-purple-50/50 dark:bg-purple-950/30 ring-2 ring-purple-500/20' : 'border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900' }}">
                            <div class="flex items-center justify-between">
                                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-purple-500/10 text-purple-600 text-lg">
                                    <i class="{{ $ag['icon'] }}"></i>
                                </span>
                                <span class="rounded-full bg-emerald-500/10 px-2 py-0.5 text-[9px] font-bold text-emerald-600">● {{ ucfirst($ag['status']) }}</span>
                            </div>
                            <h3 class="mt-3 text-sm font-bold text-slate-900 dark:text-white">{{ $ag['name'] }}</h3>
                            <p class="mt-1 text-xs text-slate-500 leading-relaxed">{{ $ag['desc'] }}</p>
                            <div class="mt-3 flex items-center justify-between border-t pt-2.5 text-[10px] text-slate-400 dark:border-slate-800">
                                <span>Tasks Run: <strong class="text-slate-700 dark:text-slate-200">{{ $ag['tasks_run'] }}</strong></span>
                                <span>Avg: <strong class="text-purple-600">{{ $ag['avg_ms'] ? $ag['avg_ms'].'ms' : '< 1s' }}</strong></span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Interactive Task Queue & Log Stream -->
                <div class="grid gap-6 lg:grid-cols-[1fr_380px]">
                    <!-- Task Input & Output Panel -->
                    <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 space-y-4">
                        <div class="flex items-center justify-between border-b pb-3 dark:border-slate-800">
                            <h3 class="text-sm font-bold text-slate-950 dark:text-white">{{ __('Dispatch Task to ') }} {{ collect($agentCatalog)->firstWhere('id', $selectedAgent)['name'] ?? 'AI Agent' }}</h3>
                            <span class="text-xs text-slate-400 font-mono">Agent ID: {{ $selectedAgent }}</span>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1">{{ __('Instruction / Task Prompt') }}</label>
                            <textarea wire:model="agentTaskInput" rows="4" placeholder="e.g. Generate 3 high-converting ad headlines for our Lagos POS sale launching next week..." class="w-full rounded-xl border border-slate-200 p-3 text-sm outline-none focus:border-purple-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white resize-none"></textarea>
                        </div>
                        <button type="button" wire:click="dispatchAgentTask" class="w-full rounded-xl bg-purple-600 py-3 text-sm font-bold text-white hover:bg-purple-700 transition shadow-lg shadow-purple-500/20">
                            <i class="fa-light fa-paper-plane-top mr-2"></i> {{ __('Dispatch Agent Task Now') }}
                        </button>

                        @if ($agentResult)
                            <div class="rounded-2xl border border-purple-200 bg-purple-50/70 p-5 dark:border-purple-900/60 dark:bg-purple-950/40 space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-purple-700 dark:text-purple-300">✓ Agent Execution Result</span>
                                    <button type="button" wire:click="clearAgentResult" class="text-[10px] font-bold text-slate-400 hover:text-slate-600">Clear</button>
                                </div>
                                <p class="text-xs text-slate-800 dark:text-purple-100 font-medium leading-relaxed">{{ $agentResult }}</p>
                            </div>
                        @endif
                    </section>

                    <!-- Execution Audit Logs -->
                    <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 space-y-4">
                        <h3 class="text-sm font-bold text-slate-950 dark:text-white border-b pb-3 dark:border-slate-800">{{ __('Agent Execution Logs') }}</h3>
                        @if (empty($agentLogs))
                            <div class="py-12 text-center text-xs text-slate-400">
                                <i class="fa-light fa-list-check text-3xl mb-2 text-slate-300"></i>
                                <p>{{ __('No agent tasks logged yet. Dispatch a task to see live execution logs.') }}</p>
                            </div>
                        @else
                            <div class="space-y-3 max-h-96 overflow-y-auto">
                                @foreach ($agentLogs as $log)
                                    <div class="rounded-xl border border-slate-100 p-3 text-xs dark:border-slate-800 space-y-1">
                                        <div class="flex items-center justify-between">
                                            <span class="font-bold text-purple-600 uppercase text-[9px]">{{ $log['agent'] }}</span>
                                            <span class="text-[9px] text-slate-400 font-mono">{{ $log['ms'] }}ms</span>
                                        </div>
                                        <p class="font-medium text-slate-700 dark:text-slate-300 truncate">{{ $log['task'] }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </section>
                </div>
            </div>
        @else
            <div class="grid gap-6 xl:grid-cols-[1.1fr_0.9fr]">
                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                        <div>
                            <h2 class="text-lg font-bold text-slate-950 dark:text-white">{{ __('AI Content & Caption Studio') }}</h2>
                            <p class="text-sm text-slate-500">{{ __('Generate high-converting social posts, captions, and marketing copy.') }}</p>
                        </div>
                        <span class="rounded-full bg-purple-500/10 text-purple-600 border border-purple-500/20 px-3 py-1 text-xs font-bold">AI Model Active</span>
                    </div>

                    <form wire:submit.prevent="generateAiContent" class="mt-5 space-y-4">
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400">Select Copy Tone</label>
                            <select wire:model.live="aiTone" class="mt-1 w-full rounded-2xl border border-slate-200 p-3 text-sm font-semibold outline-none focus:border-purple-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white">
                                <option value="professional">Professional Enterprise</option>
                                <option value="persuasive">High-Converting Sales / Persuasive</option>
                                <option value="casual">Casual & Engaging</option>
                                <option value="urgent">Urgent Call-to-Action</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400">Content Prompt / Topic</label>
                            <textarea wire:model="aiPrompt" rows="3" placeholder="e.g. Write a promotion for our new Abuja branch POS equipment release..." class="mt-1 block w-full rounded-2xl border border-slate-200 p-3.5 text-sm font-medium outline-none focus:border-purple-500 dark:border-slate-800 dark:bg-slate-800 dark:text-white"></textarea>
                        </div>

                        <button type="submit" class="w-full rounded-2xl bg-purple-600 py-3.5 text-center text-sm font-extrabold text-white shadow-lg shadow-purple-500/20 hover:bg-purple-700">
                            <i class="fa-light fa-sparkles mr-2"></i>{{ __('Generate AI Content') }}
                        </button>
                    </form>
                </section>

                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <h2 class="text-lg font-bold text-slate-950 dark:text-white border-b pb-4 dark:border-slate-800">{{ __('AI Output & Publishing') }}</h2>

                    @if ($generatedResult || $repurposedResult)
                        @if ($generatedResult)
                            <div class="mt-4 rounded-2xl border border-purple-200 bg-purple-50/50 p-5 dark:border-purple-900/60 dark:bg-purple-950/30">
                                <p class="text-xs font-bold uppercase tracking-wider text-purple-600">Generated Post ({{ ucfirst($aiTone) }} Tone)</p>
                                <p class="mt-2 text-sm font-medium text-slate-800 dark:text-purple-100">{{ $generatedResult }}</p>
                            </div>
                        @endif

                        <div class="mt-4 flex flex-col gap-2">
                            <button type="button" wire:click="sendGeneratedToPublishing" class="w-full rounded-2xl bg-blue-600 py-3 text-sm font-bold text-white shadow-md hover:bg-blue-700">
                                <i class="fa-light fa-calendar-plus mr-2"></i>{{ __('Schedule in Social Calendar') }}
                            </button>
                        </div>
                    @else
                        <div class="my-12 text-center text-slate-400">
                            <i class="fa-light fa-sparkles text-5xl text-purple-300"></i>
                            <p class="mt-3 text-sm font-medium">{{ __('Enter a prompt on the left to generate AI content.') }}</p>
                        </div>
                    @endif
                </section>
            </div>
        @endif
    @endif

    <!-- MODULE 9: WORKFLOW AUTOMATION ENHANCED -->
    @if ($moduleKey === 'automation')
        @if ($activeTab === 'rules')
            <div class="space-y-6">
                <!-- Automation Overview Cards -->
                <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">{{ __('Active Rules') }}</p>
                        <p class="mt-2 text-2xl font-black text-amber-600">{{ count($automationRules) }} Rules Enabled</p>
                        <p class="mt-1 text-xs font-medium text-amber-500"><i class="fa-light fa-bolt mr-1"></i>Cross-Module Active</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">{{ __('Executions (24h)') }}</p>
                        <p class="mt-2 text-2xl font-black text-blue-600">1,840 Tasks</p>
                        <p class="mt-1 text-xs font-medium text-emerald-500"><i class="fa-light fa-check-double mr-1"></i>100% Delivery</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">{{ __('Execution Success') }}</p>
                        <p class="mt-2 text-2xl font-black text-emerald-600">99.8% Success</p>
                        <p class="mt-1 text-xs font-medium text-slate-400">0 Failed Retries</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">{{ __('Average Latency') }}</p>
                        <p class="mt-2 text-2xl font-black text-slate-900 dark:text-white">42ms</p>
                        <p class="mt-1 text-xs font-medium text-emerald-500"><i class="fa-light fa-gauge-high mr-1"></i>Ultra Fast</p>
                    </div>
                </div>

                <!-- Rules List -->
                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                        <div>
                            <h2 class="text-lg font-bold text-slate-950 dark:text-white">{{ __('Workflow Automation Rules & Webhooks') }}</h2>
                            <p class="text-sm text-slate-500">{{ __('Automate background tasks across CRM, Finance, POS, and Notifications.') }}</p>
                        </div>
                        <button type="button" wire:click="openCreateModal('automation')" class="rounded-xl bg-amber-600 px-4 py-2.5 text-xs font-bold text-white shadow-md hover:bg-amber-700 transition">
                            <i class="fa-light fa-plus mr-1.5"></i>{{ __('Add New Automation Rule') }}
                        </button>
                    </div>

                    <div class="mt-5 space-y-3">
                        @foreach ($automationRules as $rule)
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 rounded-2xl border border-slate-200 p-4 transition hover:border-amber-500/40 dark:border-slate-800 dark:hover:border-amber-500/40">
                                <div>
                                    <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $rule['name'] }}</p>
                                    <p class="mt-0.5 text-xs text-slate-400">Trigger: <span class="font-bold text-slate-700 dark:text-slate-200">{{ $rule['trigger'] }}</span> &rarr; Action: <span class="font-bold text-blue-600">{{ $rule['action'] }}</span></p>
                                </div>
                                <div class="flex items-center gap-3 shrink-0">
                                    <button type="button" wire:click="testAutomationRule({{ $rule['id'] }})" class="rounded-xl border border-amber-500/20 bg-amber-500/10 px-3 py-1.5 text-xs font-bold text-amber-600 hover:bg-amber-500/20">
                                        <i class="fa-light fa-vial mr-1"></i>Test Trigger
                                    </button>
                                    <button type="button" wire:click="toggleAutomationRule({{ $rule['id'] }})" class="rounded-full px-3.5 py-1 text-xs font-bold transition {{ $rule['active'] ? 'bg-emerald-500/10 text-emerald-600 border border-emerald-500/20' : 'bg-slate-100 text-slate-500' }}">
                                        {{ $rule['active'] ? 'Active' : 'Paused' }}
                                    </button>
                                    <button type="button" wire:click="deleteAutomationRule({{ $rule['id'] }})" class="text-xs font-bold text-slate-400 hover:text-rose-500">
                                        <i class="fa-light fa-trash-can"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            </div>
        @elseif ($activeTab === 'templates')
            <div class="space-y-6">
                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800 mb-5">
                        <div>
                            <h2 class="text-lg font-bold text-slate-950 dark:text-white">{{ __('Pre-Built Automation Rule Templates') }}</h2>
                            <p class="text-sm text-slate-500">{{ __('One-click enable high-value background automation recipes') }}</p>
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($ruleTemplates as $tpl)
                            <div class="rounded-2xl border border-slate-200 p-5 shadow-sm dark:border-slate-800 space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-500/10 text-amber-600 text-lg">
                                        <i class="{{ $tpl['icon'] }}"></i>
                                    </span>
                                    <button type="button" wire:click="enableRuleTemplate('{{ $tpl['id'] }}')" class="rounded-xl px-3 py-1.5 text-xs font-bold transition {{ $tpl['enabled'] ? 'bg-emerald-600 text-white' : 'bg-amber-600 text-white hover:bg-amber-700' }}">
                                        {{ $tpl['enabled'] ? '✓ Enabled' : '+ Enable' }}
                                    </button>
                                </div>
                                <h3 class="text-sm font-bold text-slate-900 dark:text-white">{{ $tpl['name'] }}</h3>
                                <div class="text-xs text-slate-400 space-y-1">
                                    <p><span class="font-bold text-slate-600 dark:text-slate-300">Trigger:</span> {{ $tpl['trigger'] }}</p>
                                    <p><span class="font-bold text-blue-600">Action:</span> {{ $tpl['action'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            </div>
        @elseif ($activeTab === 'triggers')
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                    <div>
                        <h2 class="text-lg font-bold text-slate-950 dark:text-white">{{ __('Event Registry & Webhook Triggers') }}</h2>
                        <p class="text-sm text-slate-500">{{ __('Real-time system events that trigger background workflows across modules.') }}</p>
                    </div>
                </div>

                <div class="mt-5 grid gap-4 md:grid-cols-2">
                    <div class="rounded-2xl border border-slate-200 p-5 dark:border-slate-800">
                        <div class="flex items-center justify-between">
                            <span class="rounded-lg bg-blue-500/10 px-2.5 py-1 text-xs font-mono font-bold text-blue-600">crm.lead_qualified</span>
                            <button type="button" wire:click="simulateTriggerEvent('crm.lead_qualified')" class="text-xs font-bold text-amber-600 hover:underline">Simulate Event</button>
                        </div>
                        <h3 class="mt-3 text-base font-bold text-slate-900 dark:text-white">CRM Lead Qualified Event</h3>
                        <p class="mt-1 text-xs text-slate-500">Fired when sales rep marks lead as Qualified in CRM sales funnel.</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 p-5 dark:border-slate-800">
                        <div class="flex items-center justify-between">
                            <span class="rounded-lg bg-rose-500/10 px-2.5 py-1 text-xs font-mono font-bold text-rose-600">inventory.low_stock</span>
                            <button type="button" wire:click="simulateTriggerEvent('inventory.low_stock')" class="text-xs font-bold text-amber-600 hover:underline">Simulate Event</button>
                        </div>
                        <h3 class="mt-3 text-base font-bold text-slate-900 dark:text-white">Low Stock Level Warning</h3>
                        <p class="mt-1 text-xs text-slate-500">Fired when product quantity drops below reorder threshold.</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 p-5 dark:border-slate-800">
                        <div class="flex items-center justify-between">
                            <span class="rounded-lg bg-emerald-500/10 px-2.5 py-1 text-xs font-mono font-bold text-emerald-600">pos.sale_completed</span>
                            <button type="button" wire:click="simulateTriggerEvent('pos.sale_completed')" class="text-xs font-bold text-amber-600 hover:underline">Simulate Event</button>
                        </div>
                        <h3 class="mt-3 text-base font-bold text-slate-900 dark:text-white">POS Retail Checkout Completed</h3>
                        <p class="mt-1 text-xs text-slate-500">Fired when cashier completes sale & issues receipt at POS terminal.</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 p-5 dark:border-slate-800">
                        <div class="flex items-center justify-between">
                            <span class="rounded-lg bg-amber-500/10 px-2.5 py-1 text-xs font-mono font-bold text-amber-600">finance.invoice_overdue</span>
                            <button type="button" wire:click="simulateTriggerEvent('finance.invoice_overdue')" class="text-xs font-bold text-amber-600 hover:underline">Simulate Event</button>
                        </div>
                        <h3 class="mt-3 text-base font-bold text-slate-900 dark:text-white">Invoice Overdue Notice</h3>
                        <p class="mt-1 text-xs text-slate-500">Fired when NGN invoice passes due date without payment.</p>
                    </div>
                </div>
            </section>
        @else
            <!-- Execution Logs -->
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                    <div>
                        <h2 class="text-lg font-bold text-slate-950 dark:text-white">{{ __('Automation Execution Audit Logs') }}</h2>
                        <p class="text-sm text-slate-500">{{ __('Real-time log of background rule triggers, payload events, and execution status.') }}</p>
                    </div>
                </div>
                <div class="mt-5 overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50 text-xs uppercase text-slate-400 dark:bg-slate-800">
                            <tr>
                                <th class="px-4 py-3">Timestamp</th>
                                <th class="px-4 py-3">Rule Name</th>
                                <th class="px-4 py-3">Trigger Event</th>
                                <th class="px-4 py-3">Target Action</th>
                                <th class="px-4 py-3 text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            <tr class="transition hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                                <td class="px-4 py-3.5 text-xs text-slate-400 font-mono">2026-08-09 20:45:12</td>
                                <td class="px-4 py-3.5 font-bold text-slate-900 dark:text-white">Auto-generate Invoice on Qualified Lead</td>
                                <td class="px-4 py-3.5 text-xs font-mono text-blue-600">crm.lead_qualified</td>
                                <td class="px-4 py-3.5 text-xs font-semibold text-slate-600 dark:text-slate-300">Create NGN Invoice</td>
                                <td class="px-4 py-3.5 text-right"><span class="rounded-full bg-emerald-500/10 text-emerald-600 border border-emerald-500/20 px-2.5 py-0.5 text-[10px] font-bold">SUCCESS 200 OK</span></td>
                            </tr>
                            <tr class="transition hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                                <td class="px-4 py-3.5 text-xs text-slate-400 font-mono">2026-08-09 19:12:04</td>
                                <td class="px-4 py-3.5 font-bold text-slate-900 dark:text-white">Low Stock Reorder Alert Notification</td>
                                <td class="px-4 py-3.5 text-xs font-mono text-rose-600">inventory.low_stock</td>
                                <td class="px-4 py-3.5 text-xs font-semibold text-slate-600 dark:text-slate-300">Notify Operations Team</td>
                                <td class="px-4 py-3.5 text-right"><span class="rounded-full bg-emerald-500/10 text-emerald-600 border border-emerald-500/20 px-2.5 py-0.5 text-[10px] font-bold">SUCCESS 200 OK</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        @endif
    @endif

    <!-- MODULE 4: PROJECT & TASK MANAGEMENT ENHANCED WORKSPACE -->
    @if ($moduleKey === 'tasks')
        @if ($activeTab === 'projects')
            <div class="space-y-6">
                <!-- Productivity KPI Cards -->
                <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">{{ __('Active Projects') }}</p>
                        <p class="mt-2 text-2xl font-black text-sky-600">{{ $dbProjects->where('status', 'active')->count() ?: $dbProjects->count() }}</p>
                        <p class="mt-1 text-xs font-medium text-sky-500"><i class="fa-light fa-folder-open mr-1"></i>Currently running</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">{{ __('Tasks in Progress') }}</p>
                        <p class="mt-2 text-2xl font-black text-amber-600">{{ count(array_filter($tasks, fn($t) => $t['status'] === 'in_progress')) }}</p>
                        <p class="mt-1 text-xs font-medium text-amber-500"><i class="fa-light fa-spinner mr-1"></i>Actively being worked on</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">{{ __('Completed Tasks') }}</p>
                        <p class="mt-2 text-2xl font-black text-emerald-600">{{ count(array_filter($tasks, fn($t) => $t['status'] === 'done')) }}</p>
                        <p class="mt-1 text-xs font-medium text-emerald-500"><i class="fa-light fa-check-double mr-1"></i>Done this sprint</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">{{ __('Team Velocity') }}</p>
                        <p class="mt-2 text-2xl font-black text-purple-600">94%</p>
                        <p class="mt-1 text-xs font-medium text-emerald-500"><i class="fa-light fa-arrow-trend-up mr-1"></i>+8% vs last sprint</p>
                    </div>
                </div>

                <!-- Projects Grid -->
                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                        <div>
                            <h2 class="text-lg font-bold text-slate-950 dark:text-white">{{ __('Projects & Milestone Tracking') }}</h2>
                            <p class="text-sm text-slate-500">{{ __('Organize work, track progress completion, and manage team assignments.') }}</p>
                        </div>
                        <button type="button" wire:click="openCreateModal('project')" class="rounded-xl bg-sky-600 px-4 py-2.5 text-xs font-bold text-white shadow-md hover:bg-sky-700 transition">
                            <i class="fa-light fa-plus mr-1.5"></i>{{ __('Create Project') }}
                        </button>
                    </div>
                    <div class="mt-5 grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                        @forelse ($dbProjects as $proj)
                            <div class="rounded-2xl border border-slate-200 p-5 transition hover:border-sky-500/40 hover:shadow-md dark:border-slate-800 dark:hover:border-sky-500/40">
                                <div class="flex items-center justify-between">
                                    <span class="rounded-full {{ $proj->progress_percent >= 100 ? 'bg-emerald-500/10 text-emerald-600 border border-emerald-500/20' : 'bg-sky-500/10 text-sky-600 border border-sky-500/20' }} px-2.5 py-0.5 text-[10px] font-bold">{{ $proj->progress_percent >= 100 ? 'Completed' : 'Active' }}</span>
                                    <button type="button" wire:click="updateProjectProgress({{ $proj->id }}, {{ min(100, $proj->progress_percent + 25) }})" class="rounded-lg bg-sky-500/10 px-2 py-1 text-[10px] font-bold text-sky-600 hover:bg-sky-500/20 transition" wire:loading.attr="disabled">
                                        <i class="fa-light fa-plus mr-0.5"></i>25%
                                    </button>
                                </div>
                                <h3 class="mt-3 text-base font-bold text-slate-900 dark:text-white">{{ $proj->name }}</h3>
                                <p class="mt-1 text-xs text-slate-500"><i class="fa-light fa-calendar mr-1"></i>Due: {{ $proj->due_date?->format('M d, Y') ?: 'Aug 30, 2026' }}</p>
                                <p class="mt-0.5 text-xs text-slate-400"><i class="fa-light fa-user mr-1"></i>{{ $proj->assignee ?: 'Lagos HQ Team' }}</p>
                                <div class="mt-4 pt-3 border-t dark:border-slate-800">
                                    <div class="flex justify-between text-xs font-bold mb-1.5">
                                        <span class="text-slate-400">Progress</span>
                                        <span class="text-sky-600">{{ $proj->progress_percent }}%</span>
                                    </div>
                                    <div class="h-2 w-full overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800">
                                        <div class="h-full rounded-full transition-all duration-500 {{ $proj->progress_percent >= 100 ? 'bg-emerald-500' : 'bg-sky-500' }}" style="width: {{ $proj->progress_percent }}%;"></div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full py-12 text-center">
                                <i class="fa-light fa-folder-tree text-4xl text-slate-300"></i>
                                <p class="mt-3 text-sm font-medium text-slate-400">{{ __('No projects yet. Create your first project.') }}</p>
                                <button type="button" wire:click="openCreateModal('project')" class="mt-4 rounded-xl bg-sky-600 px-5 py-2 text-xs font-bold text-white shadow-md hover:bg-sky-700">{{ __('Create First Project') }}</button>
                            </div>
                        @endforelse
                    </div>
                </section>
            </div>
        @elseif ($activeTab === 'assignments')
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                    <div>
                        <h2 class="text-lg font-bold text-slate-950 dark:text-white">{{ __('Task Assignments & Kanban Board') }}</h2>
                        <p class="text-sm text-slate-500">{{ __('Track task statuses, assignees, and move tasks through workflow stages.') }}</p>
                    </div>
                </div>
                <div class="mt-5 grid gap-4 md:grid-cols-4">
                    @php
                        $taskStatuses = [
                            'todo' => ['label' => 'To Do', 'color' => 'slate', 'icon' => 'fa-light fa-circle-dashed'],
                            'in_progress' => ['label' => 'In Progress', 'color' => 'amber', 'icon' => 'fa-light fa-spinner'],
                            'in_review' => ['label' => 'In Review', 'color' => 'blue', 'icon' => 'fa-light fa-eye'],
                            'done' => ['label' => 'Done', 'color' => 'emerald', 'icon' => 'fa-light fa-circle-check'],
                        ];
                    @endphp

                    @foreach ($taskStatuses as $statusKey => $statusMeta)
                        <div class="rounded-2xl border border-slate-200 bg-slate-50/50 p-4 dark:border-slate-800 dark:bg-slate-900/50">
                            <div class="flex items-center gap-2 border-b pb-3 dark:border-slate-800">
                                <i class="{{ $statusMeta['icon'] }} text-{{ $statusMeta['color'] }}-500"></i>
                                <h3 class="text-sm font-bold text-slate-900 dark:text-white">{{ $statusMeta['label'] }}</h3>
                                <span class="ml-auto rounded-full bg-{{ $statusMeta['color'] }}-500/10 px-2 py-0.5 text-[10px] font-bold text-{{ $statusMeta['color'] }}-600">{{ count(array_filter($tasks, fn($t) => $t['status'] === $statusKey)) }}</span>
                            </div>

                            <div class="mt-3 space-y-2.5">
                                @foreach ($tasks as $tIdx => $task)
                                    @if ($task['status'] === $statusKey)
                                        <div class="rounded-xl border border-slate-200 bg-white p-3.5 shadow-2xs transition hover:shadow-sm dark:border-slate-700 dark:bg-slate-800">
                                            <div class="flex items-start justify-between">
                                                <span class="rounded-full px-2 py-0.5 text-[9px] font-bold {{ match($task['priority']) {
                                                    'Critical' => 'bg-rose-500/10 text-rose-600 border border-rose-500/20',
                                                    'High' => 'bg-amber-500/10 text-amber-600 border border-amber-500/20',
                                                    'Normal' => 'bg-blue-500/10 text-blue-600 border border-blue-500/20',
                                                    default => 'bg-slate-100 text-slate-500',
                                                } }}">{{ $task['priority'] }}</span>
                                                @if ($statusKey !== 'done')
                                                    @php $nextStatus = match($statusKey) { 'todo' => 'in_progress', 'in_progress' => 'in_review', 'in_review' => 'done', default => 'done' }; @endphp
                                                    <button type="button" wire:click="updateTaskStatus({{ $tIdx }}, '{{ $nextStatus }}')" class="text-[10px] font-bold text-sky-600 hover:underline"><i class="fa-light fa-arrow-right"></i></button>
                                                @endif
                                            </div>
                                            <p class="mt-2 text-xs font-bold text-slate-900 dark:text-white leading-snug">{{ $task['title'] }}</p>
                                            <div class="mt-2.5 flex items-center justify-between text-[10px] text-slate-400">
                                                <span><i class="fa-light fa-user mr-0.5"></i>{{ explode(' ', $task['assignee'])[0] }}</span>
                                                <span><i class="fa-light fa-calendar mr-0.5"></i>{{ $task['due'] }}</span>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @elseif ($activeTab === 'progress')
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                    <div>
                        <h2 class="text-lg font-bold text-slate-950 dark:text-white">{{ __('Activity Timeline & Progress Log') }}</h2>
                        <p class="text-sm text-slate-500">{{ __('Chronological feed of project milestones, task completions, and team updates.') }}</p>
                    </div>
                </div>

                <div class="mt-6 relative">
                    <div class="absolute left-5 top-0 bottom-0 w-0.5 bg-slate-200 dark:bg-slate-800"></div>

                    <div class="space-y-6">
                        @foreach ([
                            ['time' => '2 hours ago', 'user' => 'Babatunde Adeleke', 'action' => 'Completed task', 'detail' => 'Implement POS receipt thermal printing', 'icon' => 'fa-light fa-check-circle', 'color' => 'emerald'],
                            ['time' => '5 hours ago', 'user' => 'Fatima Bello', 'action' => 'Started working on', 'detail' => 'Design CRM pipeline Kanban board UI', 'icon' => 'fa-light fa-play-circle', 'color' => 'amber'],
                            ['time' => 'Yesterday', 'user' => 'Emeka Nwosu', 'action' => 'Updated progress to 75%', 'detail' => 'Inventory Automation project', 'icon' => 'fa-light fa-arrow-up-right', 'color' => 'blue'],
                            ['time' => '2 days ago', 'user' => 'Sola Adeyemi', 'action' => 'Created new project', 'detail' => 'Marketing Channels integration Q3', 'icon' => 'fa-light fa-folder-plus', 'color' => 'purple'],
                            ['time' => '3 days ago', 'user' => 'System', 'action' => 'Auto-assigned overdue alert', 'detail' => 'Q3 Executive Financial Report deadline approaching', 'icon' => 'fa-light fa-bell', 'color' => 'rose'],
                        ] as $event)
                            <div class="relative flex gap-4 pl-12">
                                <span class="absolute left-3 flex h-5 w-5 items-center justify-center rounded-full bg-{{ $event['color'] }}-500/10 ring-4 ring-white dark:ring-slate-900">
                                    <i class="{{ $event['icon'] }} text-xs text-{{ $event['color'] }}-500"></i>
                                </span>
                                <div class="rounded-2xl border border-slate-200 bg-slate-50/50 p-4 flex-1 dark:border-slate-800 dark:bg-slate-800/50">
                                    <div class="flex items-center justify-between">
                                        <p class="text-xs font-bold text-slate-900 dark:text-white">{{ $event['user'] }} <span class="font-normal text-slate-400">{{ $event['action'] }}</span></p>
                                        <span class="text-[10px] font-semibold text-slate-400">{{ $event['time'] }}</span>
                                    </div>
                                    <p class="mt-1 text-xs font-semibold text-slate-600 dark:text-slate-300">{{ $event['detail'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @else
            <!-- Performance Metrics -->
            <div class="space-y-6">
                <div class="grid gap-4 md:grid-cols-3">
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <p class="text-xs font-bold uppercase text-slate-400">Sprint Completion Rate</p>
                        <p class="mt-2 text-2xl font-black text-emerald-600">87.5%</p>
                        <div class="mt-3 h-2 w-full overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800">
                            <div class="h-full rounded-full bg-emerald-500" style="width: 87.5%;"></div>
                        </div>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <p class="text-xs font-bold uppercase text-slate-400">Average Task Duration</p>
                        <p class="mt-2 text-2xl font-black text-sky-600">3.2 Days</p>
                        <p class="mt-1 text-xs text-emerald-500 font-medium"><i class="fa-light fa-arrow-trend-down mr-1"></i>-0.8 days vs last sprint</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <p class="text-xs font-bold uppercase text-slate-400">Tasks Delivered On Time</p>
                        <p class="mt-2 text-2xl font-black text-purple-600">92%</p>
                        <p class="mt-1 text-xs text-slate-400 font-medium">11 of 12 tasks this sprint</p>
                    </div>
                </div>

                <!-- Team Contributor Stats -->
                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <h2 class="text-lg font-bold text-slate-950 dark:text-white border-b pb-4 dark:border-slate-800">{{ __('Team Contributor Performance') }}</h2>
                    <div class="mt-5 overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-slate-50 text-xs uppercase text-slate-400 dark:bg-slate-800">
                                <tr>
                                    <th class="px-4 py-3.5">Team Member</th>
                                    <th class="px-4 py-3.5">Tasks Completed</th>
                                    <th class="px-4 py-3.5">In Progress</th>
                                    <th class="px-4 py-3.5">On-Time Rate</th>
                                    <th class="px-4 py-3.5">Avg Duration</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                @foreach ([
                                    ['name' => 'Babatunde Adeleke', 'completed' => 8, 'in_progress' => 2, 'ontime' => '95%', 'avg' => '2.8 days'],
                                    ['name' => 'Fatima Bello', 'completed' => 6, 'in_progress' => 1, 'ontime' => '100%', 'avg' => '3.1 days'],
                                    ['name' => 'Emeka Nwosu', 'completed' => 5, 'in_progress' => 2, 'ontime' => '80%', 'avg' => '3.5 days'],
                                    ['name' => 'Sola Adeyemi', 'completed' => 4, 'in_progress' => 1, 'ontime' => '100%', 'avg' => '2.4 days'],
                                ] as $member)
                                    <tr class="transition hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                                        <td class="px-4 py-3.5">
                                            <div class="flex items-center gap-2.5">
                                                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-sky-500/10 text-xs font-black text-sky-600">{{ strtoupper(substr($member['name'], 0, 2)) }}</span>
                                                <span class="font-bold text-slate-900 dark:text-white">{{ $member['name'] }}</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3.5 font-black text-emerald-600">{{ $member['completed'] }}</td>
                                        <td class="px-4 py-3.5 font-bold text-amber-600">{{ $member['in_progress'] }}</td>
                                        <td class="px-4 py-3.5 font-bold text-sky-600">{{ $member['ontime'] }}</td>
                                        <td class="px-4 py-3.5 text-xs font-semibold text-slate-500">{{ $member['avg'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        @endif
    @endif

    <!-- MODULE 10: REPORTS ENHANCED -->
    @if ($moduleKey === 'reports')
        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                <div>
                    <h2 class="text-lg font-bold text-slate-950 dark:text-white">{{ __('Executive Management Reports & Business Intelligence') }}</h2>
                    <p class="text-sm text-slate-500">{{ __('Unified business intelligence across Lagos HQ and regional branches.') }}</p>
                </div>
                <a href="{{ route('portal.finance.export-csv') }}" class="rounded-xl bg-teal-600 px-4 py-2 text-xs font-bold text-white hover:bg-teal-700">
                    <i class="fa-light fa-file-excel mr-2"></i>{{ __('Export Master Executive Report (CSV)') }}
                </a>
            </div>

            <div class="mt-6 grid gap-4 md:grid-cols-3">
                <div class="rounded-2xl border border-slate-200 p-5 dark:border-slate-800">
                    <p class="text-xs font-bold text-slate-400 uppercase">Monthly Revenue</p>
                    <p class="mt-2 text-2xl font-black text-emerald-600">₦1,245,780.00</p>
                    <p class="mt-1 text-xs text-slate-500">From 182 settled transactions</p>
                </div>
                <div class="rounded-2xl border border-slate-200 p-5 dark:border-slate-800">
                    <p class="text-xs font-bold text-slate-400 uppercase">Active CRM Pipeline</p>
                    <p class="mt-2 text-2xl font-black text-blue-600">₦12,000,000.00</p>
                    <p class="mt-1 text-xs text-slate-500">56 open leads in pipeline</p>
                </div>
                <div class="rounded-2xl border border-slate-200 p-5 dark:border-slate-800">
                    <p class="text-xs font-bold text-slate-400 uppercase">Total Inventory Valuation</p>
                    <p class="mt-2 text-2xl font-black text-orange-600">₦18,450,000.00</p>
                    <p class="mt-1 text-xs text-slate-500">342 active product SKUs</p>
                </div>
            </div>
        </section>
    @endif

    <!-- MODULE 11: ADMINISTRATION & NOTIFICATIONS CENTRE -->
    @if ($moduleKey === 'administration')
        @if ($activeTab === 'notifications')
            <div class="space-y-6">
                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800 mb-5">
                        <div>
                            <h2 class="text-lg font-bold text-slate-950 dark:text-white">{{ __('Notifications Centre & System Alerts') }}</h2>
                            <p class="text-sm text-slate-500">{{ __('Real-time alerts, payment reminders, stock warnings and workflow notifications') }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="rounded-full bg-blue-500/10 px-3 py-1 text-xs font-bold text-blue-600">{{ $unreadCount }} Unread</span>
                            <button type="button" wire:click="markAllNotificationsRead" class="rounded-xl border border-slate-200 px-3.5 py-1.5 text-xs font-bold text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200">
                                {{ __('Mark All Read') }}
                            </button>
                        </div>
                    </div>

                    @if (empty($notifications))
                        <div class="py-16 text-center text-slate-400">
                            <i class="fa-light fa-bell-slash text-5xl mb-3 text-slate-300"></i>
                            <p class="text-sm font-semibold">{{ __('No notifications yet') }}</p>
                            <p class="text-xs text-slate-400 mt-1">{{ __('System alerts and event notices will appear here') }}</p>
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach ($notifications as $n)
                                <div class="flex items-start justify-between gap-4 rounded-2xl border p-4 transition {{ ($n['is_read'] ?? false) ? 'border-slate-100 bg-slate-50/50 dark:border-slate-800 dark:bg-slate-900/40' : 'border-blue-200 bg-blue-50/50 dark:border-blue-900/40 dark:bg-blue-950/20' }}">
                                    <div class="flex items-start gap-3">
                                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-blue-500/10 text-blue-600 text-sm">
                                            <i class="fa-light fa-bell"></i>
                                        </span>
                                        <div>
                                            <p class="text-xs font-bold text-slate-900 dark:text-white">{{ $n['title'] ?? 'System Alert' }}</p>
                                            <p class="text-xs text-slate-500 mt-0.5">{{ $n['message'] ?? $n['body'] ?? '' }}</p>
                                            <p class="text-[10px] text-slate-400 font-mono mt-1">{{ $n['created_at'] ?? '—' }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        @if (!($n['is_read'] ?? false))
                                            <button type="button" wire:click="markNotificationRead({{ $n['id'] }})" class="text-[10px] font-bold text-blue-600 hover:underline">Mark Read</button>
                                        @endif
                                        <button type="button" wire:click="deleteNotification({{ $n['id'] }})" class="text-slate-400 hover:text-rose-500 text-xs"><i class="fa-light fa-trash-can"></i></button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </section>
            </div>
        @elseif ($activeTab === 'roles')
            <div class="space-y-6">
                <!-- Create Role & Permission Matrix -->
                <div class="grid gap-6 lg:grid-cols-[380px_1fr]">
                    <!-- Create New Admin Role Form -->
                    <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 space-y-4">
                        <h3 class="text-sm font-bold text-slate-950 dark:text-white border-b pb-3 dark:border-slate-800">{{ __('Create Admin Role') }}</h3>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1">{{ __('Role Name') }}</label>
                            <input wire:model="newRoleForm.name" type="text" placeholder="e.g. Finance Manager" class="w-full rounded-xl border border-slate-200 p-2.5 text-sm outline-none focus:border-slate-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1">{{ __('Description') }}</label>
                            <textarea wire:model="newRoleForm.description" rows="2" placeholder="Brief role description..." class="w-full rounded-xl border border-slate-200 p-2.5 text-xs outline-none focus:border-slate-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white resize-none"></textarea>
                        </div>
                        <button type="button" wire:click="saveAdminRole" class="w-full rounded-xl bg-slate-900 py-3 text-xs font-bold text-white hover:bg-slate-800 dark:bg-slate-700 dark:hover:bg-slate-600 transition">
                            <i class="fa-light fa-user-shield mr-1.5"></i> {{ __('Save & Create Role') }}
                        </button>
                    </section>

                    <!-- Roles List & Permission Badges -->
                    <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 space-y-4">
                        <h3 class="text-sm font-bold text-slate-950 dark:text-white border-b pb-3 dark:border-slate-800">{{ __('Configured Roles & Permission Catalog') }}</h3>
                        <div class="space-y-4">
                            @foreach ($roles as $r)
                                <div class="rounded-2xl border border-slate-100 p-4 dark:border-slate-800 space-y-3">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4 class="text-sm font-extrabold text-slate-900 dark:text-white">{{ $r->name }}</h4>
                                            <p class="text-xs text-slate-400">{{ $r->description ?: 'No description provided.' }}</p>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="rounded-full bg-blue-500/10 px-2.5 py-0.5 text-[10px] font-bold text-blue-600">{{ $r->users_count ?? $r->users->count() }} Users</span>
                                            <button type="button" wire:click="deleteAdminRole({{ $r->id }})" class="text-slate-400 hover:text-rose-500 text-xs"><i class="fa-light fa-trash-can"></i></button>
                                        </div>
                                    </div>
                                    
                                    <!-- Interactive Permission Toggles -->
                                    <div class="border-t pt-3 dark:border-slate-800">
                                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2">Module Permissions (Click to Toggle)</p>
                                        <div class="flex flex-wrap gap-1.5">
                                            @foreach (['finance.view', 'finance.create', 'crm.view', 'crm.edit', 'inventory.view', 'pos.view', 'marketing.view', 'automation.view', 'users.view', 'users.edit'] as $permKey)
                                                @php $hasPerm = collect($r->permissions ?? [])->contains($permKey); @endphp
                                                <button type="button" wire:click="togglePermissionInRole({{ $r->id }}, '{{ $permKey }}')" class="rounded-lg px-2 py-1 text-[10px] font-bold transition {{ $hasPerm ? 'bg-emerald-500/10 text-emerald-600 border border-emerald-500/20' : 'bg-slate-100 text-slate-400 dark:bg-slate-800' }}">
                                                    {{ $hasPerm ? '✓' : '+' }} {{ $permKey }}
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                </div>
            </div>

        @elseif ($activeTab === 'security')
            <div class="space-y-6">
                <!-- Security Audit Logs Stream -->
                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800 mb-4">
                        <div>
                            <h2 class="text-lg font-bold text-slate-950 dark:text-white">{{ __('Security Audit Log Stream') }}</h2>
                            <p class="text-sm text-slate-500">{{ __('Immutable activity tracking for compliance and security auditing') }}</p>
                        </div>
                        <span class="rounded-full bg-emerald-500/10 px-3 py-1 text-xs font-bold text-emerald-600">Audit Active</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-slate-50 text-xs uppercase text-slate-400 dark:bg-slate-800">
                                <tr>
                                    <th class="px-4 py-3">Timestamp</th>
                                    <th class="px-4 py-3">Event</th>
                                    <th class="px-4 py-3">Description</th>
                                    <th class="px-4 py-3">Area</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-xs font-medium">
                                @foreach ($logs as $l)
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                                        <td class="px-4 py-3 font-mono text-slate-400">{{ $l->created_at?->toDateTimeString() ?? '—' }}</td>
                                        <td class="px-4 py-3 font-bold text-slate-900 dark:text-white">{{ $l->event }}</td>
                                        <td class="px-4 py-3 text-slate-600 dark:text-slate-300">{{ $l->description }}</td>
                                        <td class="px-4 py-3"><span class="rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-bold text-slate-600 dark:bg-slate-800 dark:text-slate-300">{{ $l->area }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>

        @else
            <!-- USERS MANAGEMENT & ROLE ASSIGNMENT TAB -->
            <div class="space-y-6">
                <!-- User Creation & Roles Grid -->
                <div class="grid gap-6 lg:grid-cols-[380px_1fr]">
                    <!-- Create User Form -->
                    <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 space-y-4">
                        <h3 class="text-sm font-bold text-slate-950 dark:text-white border-b pb-3 dark:border-slate-800">{{ __('Create Backend User') }}</h3>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1">{{ __('Full Name') }}</label>
                            <input wire:model="newUserForm.name" type="text" placeholder="e.g. Chidi Okonkwo" class="w-full rounded-xl border border-slate-200 p-2.5 text-sm outline-none focus:border-blue-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1">{{ __('Email Address') }}</label>
                            <input wire:model="newUserForm.email" type="email" placeholder="chidi@ascendsystems.ng" class="w-full rounded-xl border border-slate-200 p-2.5 text-sm outline-none focus:border-blue-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1">{{ __('Assigned Role') }}</label>
                            <select wire:model="newUserForm.role_id" class="w-full rounded-xl border border-slate-200 p-2.5 text-sm outline-none focus:border-blue-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white">
                                <option value="">No Role Assigned</option>
                                @foreach ($roles as $r)
                                    <option value="{{ $r->id }}">{{ $r->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-center gap-2 pt-1">
                            <input wire:model="newUserForm.is_super_admin" type="checkbox" id="chkSuperAdmin" class="rounded border-slate-300">
                            <label for="chkSuperAdmin" class="text-xs font-bold text-slate-700 dark:text-slate-200">{{ __('Grant Super Admin Privileges') }}</label>
                        </div>
                        <button type="button" wire:click="createNewUser" class="w-full rounded-xl bg-blue-600 py-3 text-xs font-bold text-white hover:bg-blue-700 transition shadow-md">
                            <i class="fa-light fa-user-plus mr-1.5"></i> {{ __('Create User Account') }}
                        </button>
                    </section>

                    <!-- Users Directory & Role Assignment Table -->
                    <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800 mb-4">
                            <div>
                                <h2 class="text-lg font-bold text-slate-950 dark:text-white">{{ __('User Accounts & Role Permissions') }}</h2>
                                <p class="text-sm text-slate-500">{{ __('Assign role permissions, grant super admin access, and manage accounts.') }}</p>
                            </div>
                            <span class="rounded-full bg-blue-500/10 px-3 py-1 text-xs font-bold text-blue-600">{{ count($users) }} Accounts</span>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm">
                                <thead class="bg-slate-50 text-xs uppercase text-slate-400 dark:bg-slate-800">
                                    <tr>
                                        <th class="px-4 py-3.5">User</th>
                                        <th class="px-4 py-3.5">Assigned Role</th>
                                        <th class="px-4 py-3.5">Access Level</th>
                                        <th class="px-4 py-3.5 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                    @foreach ($users as $u)
                                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                                            <td class="px-4 py-3.5">
                                                <div class="flex items-center gap-3">
                                                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-slate-900 text-xs font-black text-white dark:bg-slate-700">{{ strtoupper(substr($u->name, 0, 2)) }}</span>
                                                    <div>
                                                        <p class="font-bold text-slate-900 dark:text-white">{{ $u->name }}</p>
                                                        <p class="text-xs text-slate-400">{{ $u->email }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3.5">
                                                <select wire:change="updateUserRole({{ $u->id }}, $event.target.value)" class="rounded-xl border border-slate-200 bg-transparent px-2.5 py-1 text-xs font-semibold outline-none dark:border-slate-700 dark:bg-slate-800">
                                                    <option value="" {{ !$u->role_id ? 'selected' : '' }}>No Role</option>
                                                    @foreach ($roles as $r)
                                                        <option value="{{ $r->id }}" {{ $u->role_id === $r->id ? 'selected' : '' }}>{{ $r->name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="px-4 py-3.5">
                                                <button type="button" wire:click="toggleUserSuperAdmin({{ $u->id }})" class="rounded-full px-3 py-1 text-xs font-bold transition {{ $u->is_super_admin ? 'bg-purple-500/10 text-purple-600 border border-purple-500/20' : 'bg-slate-100 text-slate-500 dark:bg-slate-800' }}">
                                                    {{ $u->is_super_admin ? '★ Super Admin' : 'Standard User' }}
                                                </button>
                                            </td>
                                            <td class="px-4 py-3.5 text-right">
                                                @if (auth()->id() !== $u->id)
                                                    <button type="button" wire:click="deleteUserAccount({{ $u->id }})" class="text-xs font-bold text-slate-400 hover:text-rose-500">
                                                        <i class="fa-light fa-trash-can"></i>
                                                    </button>
                                                @else
                                                    <span class="text-[10px] font-bold text-slate-400">Signed In</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </section>
                </div>
            </div>
        @endif
    @endif

    <!-- Interactive Full-Page Dedicated Workspace Creation Interface -->
    @if ($showModal)
        <div class="fixed inset-0 z-[160] overflow-y-auto bg-slate-100/95 p-4 md:p-8 backdrop-blur-md dark:bg-slate-950/95">
            <div class="mx-auto max-w-5xl space-y-6">
                <!-- Workspace Page Header Navigation -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="flex items-center gap-3">
                        <button type="button" wire:click="closeModal" class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-100 dark:border-slate-800 dark:bg-slate-800 dark:text-slate-200 transition">
                            <i class="fa-light fa-arrow-left mr-2"></i>{{ __('Back to :module Workspace', ['module' => ucfirst($moduleKey)]) }}
                        </button>
                        <div>
                            <h1 class="text-xl font-extrabold text-slate-950 dark:text-white">
                                @if ($modalType === 'invoice') {{ __('New Enterprise Invoice Studio') }}
                                @elseif ($modalType === 'expense') {{ __('Record Expense Transaction Studio') }}
                                @elseif ($modalType === 'pos_sale') {{ __('New POS Quick Sale & Terminal Checkout') }}
                                @elseif ($modalType === 'product') {{ __('Add New Product SKU Inventory Studio') }}
                                @elseif ($modalType === 'campaign') {{ __('Create New Marketing Campaign Studio') }}
                                @elseif ($modalType === 'rule') {{ __('Add New Automation Rule Studio') }}
                                @elseif ($modalType === 'thermal_label') {{ __('Direct Thermal Barcode Printer Station') }}
                                @elseif ($modalType === 'pos_receipt') {{ __('Ascend Systems Thermal POS Sales Receipt Station') }}
                                @else {{ __('Create New Enterprise Record Studio') }}
                                @endif
                            </h1>
                            <p class="text-xs font-semibold text-slate-400">{{ __('Full-page creation workspace & data entry interface') }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="button" wire:click="closeModal" class="rounded-2xl border border-slate-200 px-5 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 dark:border-slate-800 dark:text-slate-200">
                            {{ __('Cancel & Discard') }}
                        </button>
                        @if (in_array($modalType, ['invoice', 'expense', 'product', 'campaign', 'rule', 'pos_sale', 'deal', 'lead', 'sales_order', 'project']))
                            <button type="button" wire:click="submitModalForm" class="rounded-2xl bg-blue-600 px-6 py-2.5 text-xs font-bold text-white shadow-lg shadow-blue-500/20 hover:bg-blue-700 transition">
                                <i class="fa-light fa-cloud-arrow-up mr-2"></i>{{ __('Save & Persist Record') }}
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Main Workspace Creation Body -->
                <div class="rounded-3xl border border-slate-200 bg-white p-6 md:p-10 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                @if ($modalType === 'thermal_label' && $modalData)
                    <div class="text-center border-b pb-4 dark:border-slate-800">
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-orange-500/10 text-orange-600">
                            <i class="fa-light fa-barcode text-3xl"></i>
                        </div>
                        <h3 class="mt-2 text-lg font-bold text-slate-950 dark:text-white">Direct Thermal Barcode Label Printer</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Hardware: {{ $modalData['printer'] }}</p>
                    </div>

                    <!-- Printable 58mm Thermal Sticker Visual Card -->
                    <div class="my-5 mx-auto w-64 rounded-2xl bg-white p-5 border-2 border-dashed border-slate-300 shadow-md text-slate-950 text-center">
                        <p class="text-[10px] font-black uppercase tracking-tight text-slate-600">{{ $modalData['store'] }}</p>
                        <p class="text-sm font-extrabold mt-1 text-slate-900">{{ $modalData['name'] }}</p>

                        <!-- Code128 Barcode Bars Graphic -->
                        <div class="my-3 flex items-center justify-center gap-1 font-mono text-2xl font-black tracking-widest text-slate-950 select-none">
                            |||| | ||| |||| | || ||||
                        </div>

                        <p class="text-xs font-mono font-extrabold text-slate-800">*{{ $modalData['sku'] }}*</p>
                        <p class="mt-2 text-xl font-black text-orange-600">PRICE: ₦{{ number_format($modalData['price'], 2) }}</p>
                        <p class="mt-1 text-[9px] text-slate-400">Timestamp: {{ $modalData['timestamp'] }}</p>
                    </div>

                    <div class="space-y-3 border-t pt-4 dark:border-slate-800">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-bold text-slate-500">Print Copies</span>
                            <span class="font-extrabold text-slate-900 dark:text-white">{{ $modalData['copies'] }} Copy / Roll Batch</span>
                        </div>

                        <div class="flex justify-end gap-3 pt-2">
                            <button type="button" wire:click="closeModal" class="rounded-2xl border border-slate-200 px-5 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 dark:border-slate-800 dark:text-slate-200">
                                {{ __('Cancel') }}
                            </button>
                            <button type="button" onclick="window.print()" class="rounded-2xl bg-orange-600 px-6 py-2.5 text-xs font-bold text-white shadow-lg shadow-orange-500/20 hover:bg-orange-700">
                                <i class="fa-light fa-print mr-2"></i>{{ __('Send to Thermal Printer') }}
                            </button>
                        </div>
                    </div>
                @elseif ($modalType === 'pos_receipt' && $modalData)
                    <div class="text-center border-b pb-4 dark:border-slate-800">
                        <div class="mx-auto inline-flex items-center gap-2 rounded-2xl bg-orange-600 px-3 py-1.5 text-xs font-black text-white shadow-sm mb-2">
                            <span>▲</span> ASCEND AI POS STATION
                        </div>
                        <h3 class="text-xl font-black text-slate-950 dark:text-white">Ascend Systems Nigeria Limited</h3>
                        <p class="text-xs font-medium text-slate-500 mt-0.5">Suite FF002, Neighborhood Centre, Area 3, Garki. Abuja. FCT.</p>
                        <p class="text-[11px] font-semibold text-slate-400">Call: +234 811 763 3020 &nbsp;|&nbsp; Mail: info@ascendsystems.ng</p>
                        <div class="mt-3 rounded-xl bg-slate-100 p-2 text-xs font-bold text-slate-700 dark:bg-slate-800 dark:text-slate-200">
                            Receipt #: <span class="font-mono text-orange-600">{{ $modalData['receipt_no'] }}</span> &nbsp;·&nbsp; {{ $modalData['date'] }}
                        </div>
                        <p class="text-xs font-bold text-emerald-600 mt-1.5">Method: {{ $modalData['payment_method'] }} &nbsp;·&nbsp; Customer: {{ $modalData['customer'] ?? 'Walk-in Retail Client' }}</p>
                    </div>

                    <div class="mt-4 space-y-2.5 text-sm border-b pb-4 dark:border-slate-800">
                        @foreach ($modalData['items'] as $item)
                            <div class="flex justify-between">
                                <span class="font-semibold">{{ $item['name'] }} (x{{ $item['quantity'] }})</span>
                                <span class="font-mono font-bold">₦{{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4 space-y-1.5 text-sm">
                        <div class="flex justify-between text-slate-500"><span>Subtotal</span><span>₦{{ number_format($modalData['subtotal'], 2) }}</span></div>
                        @if (($modalData['discount'] ?? 0) > 0)
                            <div class="flex justify-between text-emerald-600 font-bold"><span>Loyalty Discount</span><span>-₦{{ number_format($modalData['discount'], 2) }}</span></div>
                        @endif
                        <div class="flex justify-between text-slate-500"><span>VAT (7.5%)</span><span>₦{{ number_format($modalData['tax'], 2) }}</span></div>
                        <div class="flex justify-between font-black text-base text-slate-900 dark:text-white pt-2 border-t dark:border-slate-800"><span>Total Paid</span><span class="text-orange-600">₦{{ number_format($modalData['total'], 2) }}</span></div>
                    </div>

                    <div class="mt-6 flex flex-wrap items-center justify-end gap-2.5">
                        <button type="button" wire:click="closeModal" class="rounded-2xl border border-slate-200 px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 dark:border-slate-800 dark:text-slate-200">
                            {{ __('Close') }}
                        </button>
                        <button type="button" wire:click="sendWhatsAppReceipt('{{ $modalData['receipt_no'] }}', '{{ $customerContact }}')" class="rounded-2xl bg-emerald-600 px-4 py-2.5 text-xs font-bold text-white shadow-md hover:bg-emerald-700 transition">
                            <i class="fa-brands fa-whatsapp mr-1.5"></i>{{ __('WhatsApp e-Receipt') }}
                        </button>
                        <a href="rawbt:base64,{{ base64_encode('ASCEND POS RECEIPT #' . $modalData['receipt_no'] . "\nTotal: NGN " . number_format($modalData['total'], 2)) }}" class="rounded-2xl bg-blue-600 px-4 py-2.5 text-xs font-bold text-white shadow-md hover:bg-blue-700 transition">
                            <i class="fa-light fa-mobile mr-1.5"></i>{{ __('RawBT Mobile Driver') }}
                        </a>
                        <button type="button" onclick="window.print()" class="rounded-2xl bg-orange-600 px-5 py-2.5 text-xs font-bold text-white hover:bg-orange-700">
                            <i class="fa-light fa-print mr-2"></i>{{ __('Print Thermal Receipt') }}
                        </button>
                    </div>
                @elseif ($modalType === 'pos_sale')
                    <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                        <div>
                            <h3 class="text-lg font-bold text-slate-950 dark:text-white">{{ __('New POS Quick Sale & Terminal Checkout') }}</h3>
                            <p class="text-xs text-slate-400">Fast retail checkout with automatic inventory stock updates</p>
                        </div>
                        <button type="button" wire:click="closeModal" class="rounded-xl p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-slate-800">
                            <i class="fa-light fa-xmark text-lg"></i>
                        </button>
                    </div>

                    <div class="mt-4 space-y-4">
                        <!-- Barcode Scanner Quick Input & Add Custom Line -->
                        <form wire:submit.prevent="scanBarcode" class="flex items-center gap-2">
                            <input type="text" wire:model="barcodeScannerInput" placeholder="Scan SKU barcode (e.g. POS-HDW-004)..." class="w-full rounded-2xl border border-slate-200 px-3.5 py-2 text-xs font-semibold outline-none focus:border-orange-500 dark:border-slate-800 dark:bg-slate-800 dark:text-white">
                            <button type="submit" class="shrink-0 rounded-2xl bg-orange-600 px-4 py-2 text-xs font-bold text-white shadow-md hover:bg-orange-700">Scan</button>
                            <button type="button" wire:click="addToPosCart('CUSTOM-ITEM', 'Custom Line Item', 35000)" class="shrink-0 rounded-2xl border border-orange-600/30 bg-orange-500/10 px-3.5 py-2 text-xs font-bold text-orange-600 hover:bg-orange-500/20 transition">
                                <i class="fa-light fa-plus mr-1"></i>+ Add Line Item
                            </button>
                        </form>

                        <!-- Customer Details -->
                        <div class="grid gap-2 sm:grid-cols-2">
                            <div>
                                <label class="block text-[10px] font-bold uppercase text-slate-400">Customer Name</label>
                                <input type="text" wire:model="customerName" placeholder="Walk-in Retail Client" class="mt-0.5 w-full rounded-xl border border-slate-200 p-2 text-xs font-semibold outline-none dark:bg-slate-800 dark:border-slate-700 dark:text-white">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold uppercase text-slate-400">Phone / Email (e-Receipt)</label>
                                <input type="text" wire:model="customerContact" placeholder="08031234567" class="mt-0.5 w-full rounded-xl border border-slate-200 p-2 text-xs font-semibold outline-none dark:bg-slate-800 dark:border-slate-700 dark:text-white">
                            </div>
                        </div>

                        <!-- Current Cart Items -->
                        <div class="divide-y divide-slate-100 dark:divide-slate-800 max-h-48 overflow-y-auto">
                            @foreach ($posCart as $index => $cartItem)
                                <div class="flex items-center justify-between py-2 text-xs">
                                    <div>
                                        <p class="font-bold text-slate-900 dark:text-white">{{ $cartItem['name'] }}</p>
                                        <p class="text-slate-400">₦{{ number_format($cartItem['price'], 2) }} x {{ $cartItem['quantity'] }}</p>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <button type="button" wire:click="updatePosCartQuantity({{ $index }}, {{ $cartItem['quantity'] - 1 }})" class="h-6 w-6 rounded bg-slate-100 font-bold text-slate-600 dark:bg-slate-800 dark:text-slate-300">-</button>
                                        <span class="font-black">{{ $cartItem['quantity'] }}</span>
                                        <button type="button" wire:click="updatePosCartQuantity({{ $index }}, {{ $cartItem['quantity'] + 1 }})" class="h-6 w-6 rounded bg-slate-100 font-bold text-slate-600 dark:bg-slate-800 dark:text-slate-300">+</button>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Payment & Discount -->
                        <div class="grid gap-2 sm:grid-cols-2">
                            <div>
                                <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Discount</label>
                                <div class="flex gap-1">
                                    @foreach ([0, 5, 10, 15] as $disc)
                                        <button type="button" wire:click="setPosDiscount({{ $disc }})" class="rounded-lg px-2 py-1 text-[10px] font-bold border {{ $posDiscountPercent === (float)$disc ? 'bg-orange-600 text-white' : 'border-slate-200 dark:border-slate-700' }}">{{ $disc }}%</button>
                                    @endforeach
                                </div>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Payment Method</label>
                                <select wire:model.live="posPaymentMethod" class="w-full rounded-xl border border-slate-200 p-1.5 text-xs font-bold outline-none dark:bg-slate-800 dark:border-slate-700 dark:text-white">
                                    <option value="card">Card (POS Terminal)</option>
                                    <option value="cash">Cash Payment</option>
                                    <option value="bank_transfer">Instant Bank Transfer</option>
                                </select>
                            </div>
                        </div>

                        <button type="button" wire:click="checkoutPos" class="w-full rounded-2xl bg-orange-600 py-3 text-center text-sm font-black text-white shadow-lg shadow-orange-500/20 hover:bg-orange-700 transition">
                            <i class="fa-light fa-print mr-2"></i>{{ __('Complete Sale & Print Receipt') }}
                        </button>
                    </div>
                @elseif ($modalType === 'invoice')
                    <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                        <div>
                            <h3 class="text-lg font-bold text-slate-950 dark:text-white">{{ __('Create New Customer Invoice') }}</h3>
                            <p class="text-xs text-slate-400">Generate NGN invoice with automatic 7.5% VAT</p>
                        </div>
                        <button type="button" wire:click="closeModal" class="rounded-xl p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-slate-800">
                            <i class="fa-light fa-xmark text-lg"></i>
                        </button>
                    </div>

                    <form wire:submit.prevent="submitModalForm" class="mt-5 space-y-3.5">
                        <div class="grid gap-3 sm:grid-cols-2">
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400">Invoice Number</label>
                                <div class="relative mt-1">
                                    <input type="text" wire:model="form.invoice_number" required class="block w-full rounded-2xl border border-slate-200 px-3.5 py-2.5 text-xs font-mono font-bold outline-none focus:border-emerald-500 dark:border-slate-800 dark:bg-slate-800 dark:text-white">
                                    <button type="button" wire:click="autoGenerateInvoiceNumber" class="absolute right-2 top-2 text-[10px] font-bold text-emerald-600 hover:underline">Auto</button>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400">Client / Customer Name</label>
                                <input type="text" wire:model="form.client_name" required placeholder="Northbridge Media Ltd" class="mt-1 block w-full rounded-2xl border border-slate-200 px-3.5 py-2.5 text-xs font-semibold outline-none focus:border-emerald-500 dark:border-slate-800 dark:bg-slate-800 dark:text-white">
                            </div>
                        </div>

                        <div class="grid gap-3 sm:grid-cols-2">
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400">Issue Date</label>
                                <input type="date" wire:model="form.issue_date" required class="mt-1 block w-full rounded-2xl border border-slate-200 px-3.5 py-2.5 text-xs font-semibold outline-none focus:border-emerald-500 dark:border-slate-800 dark:bg-slate-800 dark:text-white">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400">Due Date</label>
                                <input type="date" wire:model="form.due_date" required class="mt-1 block w-full rounded-2xl border border-slate-200 px-3.5 py-2.5 text-xs font-semibold outline-none focus:border-emerald-500 dark:border-slate-800 dark:bg-slate-800 dark:text-white">
                            </div>
                        </div>

                        <!-- Dynamic Invoice Line Items Section -->
                        <div class="rounded-2xl border border-slate-200 p-4 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50 space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-extrabold text-slate-900 dark:text-white uppercase tracking-wider">{{ __('Invoice Line Items') }}</span>
                                <button type="button" wire:click="addInvoiceLine" class="rounded-xl bg-emerald-600 px-3 py-1 text-xs font-bold text-white shadow-sm hover:bg-emerald-700 transition">
                                    <i class="fa-light fa-plus mr-1"></i>{{ __('Add New Line Item') }}
                                </button>
                            </div>

                            <div class="space-y-2">
                                @foreach ($invoiceItems as $index => $item)
                                    <div class="flex items-center gap-2">
                                        <input type="text" wire:model.live="invoiceItems.{{ $index }}.description" wire:change="updateInvoiceLineItem({{ $index }}, 'description', $event.target.value)" placeholder="Line description..." class="w-full rounded-xl border border-slate-200 p-2 text-xs font-semibold outline-none focus:border-emerald-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white">
                                        <input type="number" min="1" wire:model.live="invoiceItems.{{ $index }}.quantity" wire:change="updateInvoiceLineItem({{ $index }}, 'quantity', $event.target.value)" placeholder="Qty" class="w-16 rounded-xl border border-slate-200 p-2 text-xs font-bold text-center outline-none focus:border-emerald-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white">
                                        <input type="number" step="0.01" wire:model.live="invoiceItems.{{ $index }}.unit_price" wire:change="updateInvoiceLineItem({{ $index }}, 'unit_price', $event.target.value)" placeholder="Price (NGN)" class="w-32 rounded-xl border border-slate-200 p-2 text-xs font-bold outline-none focus:border-emerald-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white">
                                        @if (count($invoiceItems) > 1)
                                            <button type="button" wire:click="removeInvoiceLine({{ $index }})" class="p-2 text-slate-400 hover:text-rose-500 text-xs shrink-0">
                                                <i class="fa-light fa-trash-can"></i>
                                            </button>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="grid gap-3 sm:grid-cols-2">
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400">Subtotal Amount (NGN)</label>
                                <input type="text" wire:model="form.subtotal" required placeholder="250000" class="mt-1 block w-full rounded-2xl border border-slate-200 px-3.5 py-2.5 text-sm font-semibold outline-none focus:border-emerald-500 dark:border-slate-800 dark:bg-slate-800 dark:text-white">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400">VAT (7.5% Auto)</label>
                                <input type="text" wire:model="form.tax" placeholder="18750" class="mt-1 block w-full rounded-2xl border border-slate-200 px-3.5 py-2.5 text-sm font-semibold outline-none focus:border-emerald-500 dark:border-slate-800 dark:bg-slate-800 dark:text-white">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400">Payment Notes / Bank Details</label>
                            <textarea wire:model="form.notes" rows="2" class="mt-1 block w-full rounded-2xl border border-slate-200 p-3 text-xs font-medium outline-none focus:border-emerald-500 dark:border-slate-800 dark:bg-slate-800 dark:text-white"></textarea>
                        </div>

                        <div class="flex justify-end gap-3 pt-4 border-t dark:border-slate-800">
                            <button type="button" wire:click="closeModal" class="rounded-2xl border border-slate-200 px-5 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 dark:border-slate-800 dark:text-slate-200">Cancel</button>
                            <button type="submit" class="rounded-2xl bg-emerald-600 px-6 py-2.5 text-xs font-bold text-white shadow-md hover:bg-emerald-700">Save & Issue Invoice</button>
                        </div>
                    </form>
                @elseif ($modalType === 'product')
                    <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                        <div>
                            <h3 class="text-lg font-bold text-slate-950 dark:text-white">{{ __('Add New Product SKU') }}</h3>
                            <p class="text-xs text-slate-400">Register new product item into inventory stock control</p>
                        </div>
                        <button type="button" wire:click="closeModal" class="rounded-xl p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-slate-800">
                            <i class="fa-light fa-xmark text-lg"></i>
                        </button>
                    </div>

                    <form wire:submit.prevent="submitModalForm" class="mt-5 space-y-3.5">
                        <div class="grid gap-3 sm:grid-cols-2">
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400">SKU Code</label>
                                <div class="relative mt-1">
                                    <input type="text" wire:model="form.sku" required class="block w-full rounded-2xl border border-slate-200 px-3.5 py-2.5 text-xs font-mono font-bold outline-none focus:border-orange-500 dark:border-slate-800 dark:bg-slate-800 dark:text-white">
                                    <button type="button" wire:click="autoGenerateSku" class="absolute right-2 top-2 text-[10px] font-bold text-orange-600 hover:underline">Auto</button>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400">Product Category</label>
                                <select wire:model="form.category" class="mt-1 block w-full rounded-2xl border border-slate-200 px-3.5 py-2.5 text-xs font-semibold outline-none focus:border-orange-500 dark:border-slate-800 dark:bg-slate-800 dark:text-white">
                                    <option value="Hardware">POS Hardware & Electronics</option>
                                    <option value="Software">Software & SaaS Licenses</option>
                                    <option value="Consumables">Paper Rolls & Consumables</option>
                                    <option value="Accessories">Office Accessories</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400">Product Name</label>
                            <input type="text" wire:model="form.name" required placeholder="e.g. Wireless Thermal Receipt Printer 80mm" class="mt-1 block w-full rounded-2xl border border-slate-200 px-3.5 py-2.5 text-sm font-semibold outline-none focus:border-orange-500 dark:border-slate-800 dark:bg-slate-800 dark:text-white">
                        </div>

                        <div class="grid gap-3 sm:grid-cols-2">
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400">Selling Price (NGN)</label>
                                <input type="text" wire:model="form.unit_price" required placeholder="120000" class="mt-1 block w-full rounded-2xl border border-slate-200 px-3.5 py-2.5 text-sm font-semibold outline-none focus:border-orange-500 dark:border-slate-800 dark:bg-slate-800 dark:text-white">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400">Cost Price (NGN)</label>
                                <input type="text" wire:model="form.cost_price" placeholder="85000" class="mt-1 block w-full rounded-2xl border border-slate-200 px-3.5 py-2.5 text-sm font-semibold outline-none focus:border-orange-500 dark:border-slate-800 dark:bg-slate-800 dark:text-white">
                            </div>
                        </div>

                        <div class="grid gap-3 sm:grid-cols-2">
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400">Initial Stock Quantity</label>
                                <input type="number" wire:model="form.stock_quantity" required class="mt-1 block w-full rounded-2xl border border-slate-200 px-3.5 py-2.5 text-sm font-semibold outline-none focus:border-orange-500 dark:border-slate-800 dark:bg-slate-800 dark:text-white">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400">Reorder Alert Level</label>
                                <input type="number" wire:model="form.reorder_level" required class="mt-1 block w-full rounded-2xl border border-slate-200 px-3.5 py-2.5 text-sm font-semibold outline-none focus:border-orange-500 dark:border-slate-800 dark:bg-slate-800 dark:text-white">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400">Warehouse Location</label>
                            <select wire:model="form.location" class="mt-1 block w-full rounded-2xl border border-slate-200 px-3.5 py-2.5 text-xs font-semibold outline-none focus:border-orange-500 dark:border-slate-800 dark:bg-slate-800 dark:text-white">
                                <option value="Lagos HQ Central Warehouse">Lagos HQ Central Warehouse (Ikeja)</option>
                                <option value="Abuja Regional Distribution Hub">Abuja Regional Distribution Hub (CBD)</option>
                                <option value="Port Harcourt Logistics Hub">Port Harcourt Logistics Hub (Trans-Amadi)</option>
                            </select>
                        </div>

                        <div class="flex justify-end gap-3 pt-4 border-t dark:border-slate-800">
                            <button type="button" wire:click="closeModal" class="rounded-2xl border border-slate-200 px-5 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 dark:border-slate-800 dark:text-slate-200">Cancel</button>
                            <button type="submit" class="rounded-2xl bg-orange-600 px-6 py-2.5 text-xs font-bold text-white shadow-md hover:bg-orange-700">Save Product SKU</button>
                        </div>
                    </form>
                @elseif ($modalType === 'campaign')
                    <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                        <div>
                            <h3 class="text-lg font-bold text-slate-950 dark:text-white">{{ __('Create New Marketing Campaign') }}</h3>
                            <p class="text-xs text-slate-400">Setup multi-channel campaign budget, audience, schedule & creative parameters</p>
                        </div>
                        <button type="button" wire:click="closeModal" class="rounded-xl p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-slate-800">
                            <i class="fa-light fa-xmark text-lg"></i>
                        </button>
                    </div>

                    <form wire:submit.prevent="submitModalForm" class="mt-5 space-y-5">
                        <!-- Campaign Identity -->
                        <div class="rounded-2xl border border-purple-200/50 bg-purple-50/30 p-4 dark:border-purple-800/30 dark:bg-purple-950/20">
                            <h4 class="text-xs font-bold uppercase text-purple-600 mb-3"><i class="fa-light fa-bullhorn mr-1.5"></i>Campaign Identity</h4>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400">Campaign Name</label>
                                    <input type="text" wire:model="form.title" required placeholder="e.g. Q4 Regional Enterprise Growth Push" class="mt-1 block w-full rounded-2xl border border-slate-200 px-3.5 py-2.5 text-sm font-semibold outline-none focus:border-purple-500 dark:border-slate-800 dark:bg-slate-800 dark:text-white">
                                </div>
                                <div class="grid gap-3 sm:grid-cols-2">
                                    <div>
                                        <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400">Campaign Objective</label>
                                        <select wire:model="form.notes" class="mt-1 block w-full rounded-2xl border border-slate-200 px-3.5 py-2.5 text-xs font-semibold outline-none focus:border-purple-500 dark:border-slate-800 dark:bg-slate-800 dark:text-white">
                                            <option value="Brand Awareness">Brand Awareness & Reach</option>
                                            <option value="Lead Generation">Lead Generation (CRM Pipeline)</option>
                                            <option value="Conversions">Conversions & Sales</option>
                                            <option value="Engagement">Engagement & Community</option>
                                            <option value="App Installs">App Install & Downloads</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400">Campaign Type</label>
                                        <div class="mt-1 flex flex-wrap gap-2">
                                            @foreach (['Awareness', 'Lead Gen', 'Conversion', 'Retargeting'] as $type)
                                                <span class="rounded-full bg-purple-500/10 border border-purple-500/20 px-3 py-1 text-[10px] font-bold text-purple-600 cursor-pointer hover:bg-purple-500/20 transition">{{ $type }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Channel & Audience -->
                        <div class="rounded-2xl border border-slate-200 bg-slate-50/30 p-4 dark:border-slate-800 dark:bg-slate-800/30">
                            <h4 class="text-xs font-bold uppercase text-slate-600 mb-3 dark:text-slate-300"><i class="fa-light fa-users mr-1.5"></i>Channel & Audience Targeting</h4>
                            <div class="grid gap-3 sm:grid-cols-2">
                                <div>
                                    <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400">Channel Distribution</label>
                                    <select wire:model="form.category" class="mt-1 block w-full rounded-2xl border border-slate-200 px-3.5 py-2.5 text-xs font-semibold outline-none focus:border-purple-500 dark:border-slate-800 dark:bg-slate-800 dark:text-white">
                                        <option value="Multi-Channel (Meta, LinkedIn, Google)">Multi-Channel (Meta, LinkedIn, Google)</option>
                                        <option value="Facebook & Instagram Ads">Facebook & Instagram Ads</option>
                                        <option value="Google Search & Display">Google Search & Display</option>
                                        <option value="LinkedIn Enterprise Ads">LinkedIn Enterprise Ads</option>
                                        <option value="Email Newsletter Blast">Email Newsletter Blast</option>
                                        <option value="WhatsApp Business">WhatsApp Business API</option>
                                        <option value="SMS Broadcast">SMS Broadcast Campaign</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400">Target Audience Segment</label>
                                    <select class="mt-1 block w-full rounded-2xl border border-slate-200 px-3.5 py-2.5 text-xs font-semibold outline-none focus:border-purple-500 dark:border-slate-800 dark:bg-slate-800 dark:text-white">
                                        <option>All Subscribers (5,200 contacts)</option>
                                        <option>Enterprise Clients (1,840 contacts)</option>
                                        <option>SMB & Startup (2,100 contacts)</option>
                                        <option>Cold Leads (1,260 contacts)</option>
                                        <option>Custom Segment</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mt-3">
                                <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400">Geographic Targeting</label>
                                <div class="mt-1.5 flex flex-wrap gap-2">
                                    @foreach (['Lagos', 'Abuja (FCT)', 'Port Harcourt', 'Ibadan', 'Kano', 'All Nigeria'] as $geo)
                                        <span class="rounded-full bg-slate-100 border border-slate-200 px-3 py-1 text-[10px] font-bold text-slate-600 cursor-pointer hover:bg-purple-500/10 hover:border-purple-500/20 hover:text-purple-600 transition dark:bg-slate-800 dark:border-slate-700 dark:text-slate-300">{{ $geo }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Budget & Schedule -->
                        <div class="grid gap-3 sm:grid-cols-2">
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400">Ad Budget (NGN)</label>
                                <div class="relative mt-1">
                                    <span class="absolute left-3.5 top-2.5 text-xs font-bold text-slate-400">₦</span>
                                    <input type="text" wire:model="form.subtotal" required placeholder="1,500,000" class="block w-full rounded-2xl border border-slate-200 py-2.5 pl-8 pr-3.5 text-sm font-semibold outline-none focus:border-purple-500 dark:border-slate-800 dark:bg-slate-800 dark:text-white">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400">Bidding Strategy</label>
                                <select class="mt-1 block w-full rounded-2xl border border-slate-200 px-3.5 py-2.5 text-xs font-semibold outline-none focus:border-purple-500 dark:border-slate-800 dark:bg-slate-800 dark:text-white">
                                    <option>Cost Per Click (CPC)</option>
                                    <option>Cost Per Impression (CPM)</option>
                                    <option>Cost Per Lead (CPL)</option>
                                    <option>Target ROAS (Return on Ad Spend)</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid gap-3 sm:grid-cols-2">
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400">Start Date</label>
                                <input type="date" wire:model="form.issue_date" class="mt-1 block w-full rounded-2xl border border-slate-200 px-3.5 py-2.5 text-xs font-semibold outline-none focus:border-purple-500 dark:border-slate-800 dark:bg-slate-800 dark:text-white">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400">End Date</label>
                                <input type="date" wire:model="form.due_date" class="mt-1 block w-full rounded-2xl border border-slate-200 px-3.5 py-2.5 text-xs font-semibold outline-none focus:border-purple-500 dark:border-slate-800 dark:bg-slate-800 dark:text-white">
                            </div>
                        </div>

                        <!-- KPI Goals -->
                        <div class="rounded-2xl border border-slate-200 bg-slate-50/30 p-4 dark:border-slate-800 dark:bg-slate-800/30">
                            <h4 class="text-xs font-bold uppercase text-slate-600 mb-3 dark:text-slate-300"><i class="fa-light fa-bullseye mr-1.5"></i>KPI Goals & Projections</h4>
                            <div class="grid gap-3 sm:grid-cols-4">
                                <div class="text-center rounded-xl border border-slate-200 p-3 dark:border-slate-700">
                                    <p class="text-[10px] font-bold uppercase text-slate-400">Target Impressions</p>
                                    <p class="mt-1 text-lg font-black text-purple-600">250K</p>
                                </div>
                                <div class="text-center rounded-xl border border-slate-200 p-3 dark:border-slate-700">
                                    <p class="text-[10px] font-bold uppercase text-slate-400">Target Clicks</p>
                                    <p class="mt-1 text-lg font-black text-blue-600">12.5K</p>
                                </div>
                                <div class="text-center rounded-xl border border-slate-200 p-3 dark:border-slate-700">
                                    <p class="text-[10px] font-bold uppercase text-slate-400">Cost Per Lead</p>
                                    <p class="mt-1 text-lg font-black text-emerald-600">₦480</p>
                                </div>
                                <div class="text-center rounded-xl border border-slate-200 p-3 dark:border-slate-700">
                                    <p class="text-[10px] font-bold uppercase text-slate-400">ROAS Target</p>
                                    <p class="mt-1 text-lg font-black text-amber-600">4.2x</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 pt-4 border-t dark:border-slate-800">
                            <button type="button" wire:click="closeModal" class="rounded-2xl border border-slate-200 px-5 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 dark:border-slate-800 dark:text-slate-200">Cancel</button>
                            <button type="submit" class="rounded-2xl bg-purple-600 px-6 py-2.5 text-xs font-bold text-white shadow-lg shadow-purple-500/20 hover:bg-purple-700 transition">
                                <i class="fa-light fa-rocket mr-1.5"></i>Launch Campaign
                            </button>
                        </div>
                    </form>

                @elseif ($modalType === 'rule')
                    <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                        <div>
                            <h3 class="text-lg font-bold text-slate-950 dark:text-white">{{ __('Add New Automation Rule Studio') }}</h3>
                            <p class="text-xs text-slate-400">Configure trigger conditions, multi-action workflow chains, delays & notifications</p>
                        </div>
                        <button type="button" wire:click="closeModal" class="rounded-xl p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-slate-800">
                            <i class="fa-light fa-xmark text-lg"></i>
                        </button>
                    </div>

                    <form wire:submit.prevent="submitModalForm" class="mt-5 space-y-5">
                        <!-- Rule Identity & Priority -->
                        <div class="rounded-2xl border border-amber-200/50 bg-amber-50/30 p-4 dark:border-amber-800/30 dark:bg-amber-950/20">
                            <h4 class="text-xs font-bold uppercase text-amber-600 mb-3"><i class="fa-light fa-bolt mr-1.5"></i>Rule Definition & Scope</h4>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400">Rule Name / Headline</label>
                                    <input type="text" wire:model="form.title" required placeholder="e.g. Auto-generate NGN Invoice on CRM Deal Closed-Won" class="mt-1 block w-full rounded-2xl border border-slate-200 px-3.5 py-2.5 text-sm font-semibold outline-none focus:border-amber-500 dark:border-slate-800 dark:bg-slate-800 dark:text-white">
                                </div>
                                <div class="grid gap-3 sm:grid-cols-2">
                                    <div>
                                        <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400">Execution Priority</label>
                                        <select class="mt-1 block w-full rounded-2xl border border-slate-200 px-3.5 py-2.5 text-xs font-semibold outline-none focus:border-amber-500 dark:border-slate-800 dark:bg-slate-800 dark:text-white">
                                            <option value="critical">Critical (Real-time sync)</option>
                                            <option value="high" selected>High Priority (Within 1 min)</option>
                                            <option value="normal">Normal Batch (Within 5 mins)</option>
                                            <option value="low">Low Priority (Scheduled background job)</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400">Environment Target</label>
                                        <div class="mt-1 flex gap-2">
                                            <span class="rounded-full bg-amber-500/10 border border-amber-500/20 px-3 py-1 text-[10px] font-bold text-amber-600">Production</span>
                                            <span class="rounded-full bg-slate-100 border border-slate-200 px-3 py-1 text-[10px] font-bold text-slate-500 dark:bg-slate-800 dark:border-slate-700">Staging Test</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- System Trigger Event & Conditions -->
                        <div class="rounded-2xl border border-slate-200 bg-slate-50/30 p-4 dark:border-slate-800 dark:bg-slate-800/30">
                            <h4 class="text-xs font-bold uppercase text-slate-600 mb-3 dark:text-slate-300"><i class="fa-light fa-filter mr-1.5"></i>Trigger Event & Filter Conditions</h4>
                            <div class="grid gap-3 sm:grid-cols-2">
                                <div>
                                    <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400">System Trigger Event</label>
                                    <select wire:model="form.category" class="mt-1 block w-full rounded-2xl border border-slate-200 px-3.5 py-2.5 text-xs font-semibold outline-none focus:border-amber-500 dark:border-slate-800 dark:bg-slate-800 dark:text-white">
                                        <option value="CRM Lead Qualified">CRM Lead Qualified (crm.lead_qualified)</option>
                                        <option value="Stock Quantity < Reorder Level">Low Stock Level Warning (inventory.low_stock)</option>
                                        <option value="POS Checkout Completed">POS Checkout Completed (pos.sale_completed)</option>
                                        <option value="Invoice Overdue">Invoice Overdue Notice (finance.invoice_overdue)</option>
                                        <option value="Deal Closed Won">CRM Deal Closed Won (crm.deal_closed_won)</option>
                                        <option value="New Webhook Inbound">Custom Webhook Received (webhook.inbound)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400">Condition Logic Filter</label>
                                    <div class="mt-1 flex gap-2">
                                        <select class="w-1/3 rounded-xl border border-slate-200 p-2 text-xs font-semibold outline-none dark:bg-slate-800 dark:border-slate-700 dark:text-white">
                                            <option>Amount ></option>
                                            <option>Equals</option>
                                            <option>Contains</option>
                                        </select>
                                        <input type="text" placeholder="e.g. 500000" class="w-2/3 rounded-xl border border-slate-200 p-2 text-xs font-semibold outline-none dark:bg-slate-800 dark:border-slate-700 dark:text-white">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Target Actions & Multi-Step Chain -->
                        <div class="rounded-2xl border border-slate-200 bg-slate-50/30 p-4 dark:border-slate-800 dark:bg-slate-800/30">
                            <h4 class="text-xs font-bold uppercase text-slate-600 mb-3 dark:text-slate-300"><i class="fa-light fa-diagram-next mr-1.5"></i>Action Execution Chain & Delays</h4>
                            <div class="grid gap-3 sm:grid-cols-2">
                                <div>
                                    <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400">Primary Target Action</label>
                                    <input type="text" wire:model="form.notes" required placeholder="e.g. Create NGN Invoice & Dispatch Email" class="mt-1 block w-full rounded-2xl border border-slate-200 px-3.5 py-2.5 text-sm font-semibold outline-none focus:border-amber-500 dark:border-slate-800 dark:bg-slate-800 dark:text-white">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400">Execution Delay</label>
                                    <select class="mt-1 block w-full rounded-2xl border border-slate-200 px-3.5 py-2.5 text-xs font-semibold outline-none focus:border-amber-500 dark:border-slate-800 dark:bg-slate-800 dark:text-white">
                                        <option value="0">Immediate Execution (0 sec)</option>
                                        <option value="300">Wait 5 Minutes</option>
                                        <option value="3600">Wait 1 Hour</option>
                                        <option value="86400">Wait 24 Hours (Nurture drip)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mt-3">
                                <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400">Notification Alert Dispatch</label>
                                <div class="mt-1.5 flex flex-wrap gap-2">
                                    @foreach (['Email Dispatch', 'SMS Alert', 'In-App Bell', 'Slack / Teams Webhook'] as $channel)
                                        <label class="flex items-center gap-1.5 rounded-full bg-white border border-slate-200 px-3 py-1 text-[10px] font-bold text-slate-700 cursor-pointer dark:bg-slate-800 dark:border-slate-700 dark:text-slate-300">
                                            <input type="checkbox" checked class="rounded text-amber-600 focus:ring-amber-500">
                                            <span>{{ $channel }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Dry-Run Simulation Card -->
                        <div class="rounded-2xl border border-slate-200 p-4 dark:border-slate-800 bg-slate-900 text-slate-200">
                            <div class="flex items-center justify-between text-xs mb-2">
                                <span class="font-mono font-bold text-amber-400"><i class="fa-light fa-terminal mr-1.5"></i>Automation Payload Test Console</span>
                                <span class="text-[10px] font-bold text-emerald-400">Ready for Simulation</span>
                            </div>
                            <pre class="text-[11px] font-mono text-slate-300 leading-tight bg-slate-950 p-2.5 rounded-xl border border-slate-800">
{
  "trigger": "crm.deal_closed_won",
  "payload": { "deal_id": "DEAL-9481", "client": "Northbridge Media Ltd", "amount": 8500000 },
  "action_chain": ["create_invoice", "send_receipt_email", "notify_slack"]
}</pre>
                        </div>

                        <div class="flex justify-end gap-3 pt-4 border-t dark:border-slate-800">
                            <button type="button" wire:click="closeModal" class="rounded-2xl border border-slate-200 px-5 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 dark:border-slate-800 dark:text-slate-200">Cancel</button>
                            <button type="submit" class="rounded-2xl bg-amber-600 px-6 py-2.5 text-xs font-bold text-white shadow-lg shadow-amber-500/20 hover:bg-amber-700 transition">
                                <i class="fa-light fa-floppy-disk mr-1.5"></i>Save Automation Rule
                            </button>
                        </div>
                    </form>
                @else
                    <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                        <h3 class="text-lg font-bold text-slate-950 dark:text-white">{{ __('Create New Record') }}</h3>
                        <button type="button" wire:click="closeModal" class="rounded-xl p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-slate-800">
                            <i class="fa-light fa-xmark text-lg"></i>
                        </button>
                    </div>

                    <form wire:submit.prevent="submitModalForm" class="mt-5 space-y-4">
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400">Title / Name</label>
                            <input type="text" wire:model="form.name" required class="mt-1 block w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm font-semibold outline-none focus:border-blue-500 dark:border-slate-800 dark:bg-slate-800 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400">Amount / Price (NGN)</label>
                            <input type="text" wire:model="form.amount" placeholder="e.g. 250000" class="mt-1 block w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm font-semibold outline-none focus:border-blue-500 dark:border-slate-800 dark:bg-slate-800 dark:text-white">
                        </div>
                        <div class="flex justify-end gap-3 pt-4 border-t dark:border-slate-800">
                            <button type="button" wire:click="closeModal" class="rounded-2xl border border-slate-200 px-5 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 dark:border-slate-800 dark:text-slate-200">Cancel</button>
                            <button type="submit" class="rounded-2xl bg-blue-600 px-6 py-2.5 text-xs font-bold text-white shadow-md hover:bg-blue-700">Save Record</button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    @endif
</div>
