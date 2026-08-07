@php
    $accent = match ($moduleKey) {
        'finance' => '#0f9f6e',
        'inventory', 'pos' => '#ea580c',
        'crm' => '#2563eb',
        'tasks' => '#0284c7',
        'administration' => '#1e293b',
        default => '#2563eb',
    };
@endphp

<div class="space-y-8" style="--ascend-accent: {{ $accent }};">
    <!-- Module Header -->
    <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <a href="{{ route('portal.dashboard') }}" wire:navigate class="mb-4 inline-flex items-center gap-2 text-sm font-semibold text-slate-500 transition hover:text-slate-900">
                <i class="fa-light fa-arrow-left"></i>{{ __('Back to overview') }}
            </a>
            <div class="flex items-center gap-4">
                <span class="flex h-14 w-14 items-center justify-center rounded-2xl text-xl text-white shadow-lg" style="background: var(--ascend-accent);">
                    <i class="{{ match($moduleKey) {
                        'finance' => 'fa-light fa-circle-dollar',
                        'crm' => 'fa-light fa-users-line',
                        'tasks' => 'fa-light fa-list-check',
                        'inventory' => 'fa-light fa-boxes-stacked',
                        'pos' => 'fa-light fa-cash-register',
                        'administration' => 'fa-light fa-user-shield',
                        default => 'fa-light fa-layer-group',
                    } }}"></i>
                </span>
                <div>
                    <h1 class="text-3xl font-bold tracking-tight text-slate-950 dark:text-white">
                        {{ match($moduleKey) {
                            'finance' => __('Accounting & Finance'),
                            'crm' => __('CRM — Leads, Deals & Contracts'),
                            'tasks' => __('Project & Task Management'),
                            'inventory' => __('Products & Inventory'),
                            'pos' => __('Point of Sale (POS)'),
                            'administration' => __('User Management & Security'),
                            default => __(ucfirst($moduleKey)),
                        } }}
                    </h1>
                    <p class="mt-1 max-w-2xl text-sm text-slate-500 dark:text-slate-400">
                        {{ match($moduleKey) {
                            'finance' => __('Comprehensive tools to record, track, and report all financial activities, ledgers, and cashflow.'),
                            'crm' => __('Manage your sales pipeline, client relationships, lead conversions, and custom fields in one place.'),
                            'tasks' => __('Organize work, assign responsibilities, track project progress logs, and monitor productivity metrics.'),
                            'inventory' => __('Track product stock levels, warehouse availability, supplier management, and reorder alerts.'),
                            'pos' => __('Fast retail POS checkout, automated receipt printing, barcode management, and stock updates.'),
                            'administration' => __('Control access, assign roles, set permissions for modules and security audit tracking.'),
                            default => __('Operational workspace capabilities.'),
                        } }}
                    </p>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap gap-3">
            @if (in_array($moduleKey, ['finance', 'crm', 'inventory'], true))
                <a href="{{ route('portal.'.$moduleKey.'.export-csv') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-200">
                    <i class="fa-light fa-file-excel text-emerald-600"></i>
                    {{ __('Export CSV') }}
                </a>
            @endif
            <button type="button" wire:click="openCreateModal('{{ $moduleKey }}')" class="inline-flex items-center gap-2 rounded-xl px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:brightness-95" style="background: var(--ascend-accent);">
                <i class="fa-light fa-plus"></i>
                {{ match($moduleKey) {
                    'finance' => __('New Invoice / Expense'),
                    'crm' => __('Add Lead / Deal'),
                    'tasks' => __('Create Project / Task'),
                    'inventory' => __('Add Product SKU'),
                    'pos' => __('New Sale Checkout'),
                    'administration' => __('Add User / Role'),
                    default => __('New Record'),
                } }}
            </button>
        </div>
    </div>

    <!-- Status Toast Notification -->
    @if (session('status'))
        <div class="rounded-xl border border-emerald-500/20 bg-emerald-500/10 px-4 py-3 text-sm font-semibold text-emerald-600 dark:text-emerald-400">
            <i class="fa-light fa-circle-check mr-2"></i> {{ session('status') }}
        </div>
    @endif

    @if (session('warning'))
        <div class="rounded-xl border border-amber-500/20 bg-amber-500/10 px-4 py-3 text-sm font-semibold text-amber-600 dark:text-amber-400">
            <i class="fa-light fa-triangle-exclamation mr-2"></i> {{ session('warning') }}
        </div>
    @endif

    <!-- Module Sub-Tabs Navigation -->
    <div class="flex border-b border-slate-200 overflow-x-auto dark:border-slate-800">
        @php
            $subTabs = match($moduleKey) {
                'finance' => [
                    'overview' => ['label' => 'Overview & Banking', 'icon' => 'fa-light fa-building-columns'],
                    'invoices' => ['label' => 'Invoices & Estimates', 'icon' => 'fa-light fa-file-invoice-dollar'],
                    'expenses' => ['label' => 'Expenses & Receipts', 'icon' => 'fa-light fa-receipt'],
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
                'tasks' => [
                    'projects' => ['label' => 'Projects Overview', 'icon' => 'fa-light fa-folder-tree'],
                    'assignments' => ['label' => 'Task Assignments', 'icon' => 'fa-light fa-list-check'],
                    'progress' => ['label' => 'Progress Logs', 'icon' => 'fa-light fa-timeline'],
                    'reports' => ['label' => 'Performance Metrics', 'icon' => 'fa-light fa-chart-waterfall'],
                ],
                'inventory' => [
                    'products' => ['label' => 'Products & Services', 'icon' => 'fa-light fa-box-archive'],
                    'stock' => ['label' => 'Stock Movement', 'icon' => 'fa-light fa-arrows-repeat'],
                    'warehouses' => ['label' => 'Warehouses & Suppliers', 'icon' => 'fa-light fa-warehouse'],
                    'import' => ['label' => 'Import / Export', 'icon' => 'fa-light fa-file-export'],
                ],
                'pos' => [
                    'checkout' => ['label' => 'POS Checkout Terminal', 'icon' => 'fa-light fa-cash-register'],
                    'receipts' => ['label' => 'Sales Receipts', 'icon' => 'fa-light fa-receipt'],
                    'barcodes' => ['label' => 'Barcode & Purchases', 'icon' => 'fa-light fa-barcode'],
                    'insights' => ['label' => 'POS Insights & Reports', 'icon' => 'fa-light fa-chart-line'],
                ],
                'marketing' => [
                    'campaigns' => ['label' => 'Marketing Campaigns', 'icon' => 'fa-light fa-bullhorn'],
                    'social' => ['label' => 'Social Channels', 'icon' => 'fa-light fa-share-nodes'],
                    'blasts' => ['label' => 'Audience Blasts', 'icon' => 'fa-light fa-paper-plane'],
                    'analytics' => ['label' => 'Campaign Analytics', 'icon' => 'fa-light fa-chart-line'],
                ],
                'ai-agents' => [
                    'caption' => ['label' => 'Caption Generator', 'icon' => 'fa-light fa-sparkles'],
                    'repurpose' => ['label' => 'Content Repurposer', 'icon' => 'fa-light fa-repeat'],
                    'planner' => ['label' => 'Content Planner', 'icon' => 'fa-light fa-calendar-star'],
                    'besttime' => ['label' => 'Best Time Scheduler', 'icon' => 'fa-light fa-clock-rotate-left'],
                ],
                'automation' => [
                    'rules' => ['label' => 'Active Automation Rules', 'icon' => 'fa-light fa-bolt'],
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
                    'roles' => ['label' => 'Roles & Permissions', 'icon' => 'fa-light fa-user-shield'],
                    'clients' => ['label' => 'Client & External Access', 'icon' => 'fa-light fa-user-group'],
                    'security' => ['label' => 'Security & Audit Logs', 'icon' => 'fa-light fa-shield-check'],
                ],
                default => ['overview' => ['label' => 'Overview', 'icon' => 'fa-light fa-border-all']],
            };
        @endphp

        @foreach ($subTabs as $tabKey => $tab)
            <button type="button" wire:click="setTab('{{ $tabKey }}')" class="flex items-center gap-2 border-b-2 px-5 py-3 text-sm font-semibold whitespace-nowrap transition-colors {{ $activeTab === $tabKey ? 'border-[color:var(--ascend-accent)] text-slate-900 dark:text-white' : 'border-transparent text-slate-500 hover:text-slate-700 dark:hover:text-slate-300' }}">
                <i class="{{ $tab['icon'] }}"></i>
                {{ __($tab['label']) }}
            </button>
        @endforeach
    </div>

    <!-- MODULE 1: ACCOUNTING & FINANCE -->
    @if ($moduleKey === 'finance')
        @if ($activeTab === 'overview')
            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <p class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-400">{{ __('Total Revenue') }}</p>
                    <p class="mt-2 text-2xl font-bold text-emerald-600">₦1,245,780.00</p>
                    <p class="mt-1 text-xs text-slate-400">{{ __('+18.6% vs last month') }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <p class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-400">{{ __('Operating Expenses') }}</p>
                    <p class="mt-2 text-2xl font-bold text-rose-500">₦324,500.00</p>
                    <p class="mt-1 text-xs text-slate-400">{{ __('Fixed & variable costs') }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <p class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-400">{{ __('Net Profit') }}</p>
                    <p class="mt-2 text-2xl font-bold text-blue-600">₦921,280.00</p>
                    <p class="mt-1 text-xs text-slate-400">{{ __('Net profit margin 73.9%') }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <p class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-400">{{ __('Outstanding Bills') }}</p>
                    <p class="mt-2 text-2xl font-bold text-amber-600">₦180,000.00</p>
                    <p class="mt-1 text-xs text-slate-400">{{ __('2 pending vendor bills') }}</p>
                </div>
            </div>

            <!-- Banking Accounts & Transfers -->
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                    <div>
                        <h2 class="text-lg font-bold text-slate-950 dark:text-white">{{ __('Banking & Liquid Accounts') }}</h2>
                        <p class="text-sm text-slate-500">{{ __('Bank accounts, cash reserves, and intra-company transfers.') }}</p>
                    </div>
                    <button type="button" wire:click="openCreateModal('transfer')" class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200">
                        <i class="fa-light fa-arrow-right-arrow-left mr-2"></i>{{ __('Initiate Transfer') }}
                    </button>
                </div>
                <div class="mt-5 grid gap-4 md:grid-cols-3">
                    <div class="rounded-xl border border-slate-200 p-4 dark:border-slate-800">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold uppercase text-slate-400">Access Bank HQ</span>
                            <span class="rounded-full bg-emerald-50 px-2 py-0.5 text-[10px] font-bold text-emerald-600">Active</span>
                        </div>
                        <p class="mt-3 text-xl font-bold text-slate-900 dark:text-white">₦4,850,000.00</p>
                        <p class="mt-1 text-xs text-slate-400">Acc: 0129481029 · Lagos HQ</p>
                    </div>
                    <div class="rounded-xl border border-slate-200 p-4 dark:border-slate-800">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold uppercase text-slate-400">GTBank Operations</span>
                            <span class="rounded-full bg-emerald-50 px-2 py-0.5 text-[10px] font-bold text-emerald-600">Active</span>
                        </div>
                        <p class="mt-3 text-xl font-bold text-slate-900 dark:text-white">₦2,140,500.00</p>
                        <p class="mt-1 text-xs text-slate-400">Acc: 0548102941 · Abuja Branch</p>
                    </div>
                    <div class="rounded-xl border border-slate-200 p-4 dark:border-slate-800">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold uppercase text-slate-400">Zenith Petty Cash</span>
                            <span class="rounded-full bg-blue-50 px-2 py-0.5 text-[10px] font-bold text-blue-600">Cash Reserve</span>
                        </div>
                        <p class="mt-3 text-xl font-bold text-slate-900 dark:text-white">₦350,000.00</p>
                        <p class="mt-1 text-xs text-slate-400">Acc: 1019482014 · Operations</p>
                    </div>
                </div>
            </section>
        @elseif ($activeTab === 'invoices')
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                    <h2 class="text-lg font-bold text-slate-950 dark:text-white">{{ __('Invoices, Estimates & Notes') }}</h2>
                    <button type="button" wire:click="openCreateModal('invoice')" class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
                        <i class="fa-light fa-plus mr-2"></i>{{ __('Create Invoice') }}
                    </button>
                </div>
                <div class="mt-5 overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50 text-xs uppercase text-slate-400 dark:bg-slate-800">
                            <tr>
                                <th class="px-4 py-3">Invoice #</th>
                                <th class="px-4 py-3">Customer / Supplier</th>
                                <th class="px-4 py-3">Issue Date</th>
                                <th class="px-4 py-3">Amount</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @foreach ($dbInvoices as $inv)
                                <tr>
                                    <td class="px-4 py-3 font-semibold text-slate-900 dark:text-white">{{ $inv->invoice_number }}</td>
                                    <td class="px-4 py-3">{{ $inv->client_name }}</td>
                                    <td class="px-4 py-3 text-slate-500">{{ $inv->issue_date?->format('Y-m-d') ?: now()->format('Y-m-d') }}</td>
                                    <td class="px-4 py-3 font-bold text-slate-900 dark:text-white">₦{{ number_format($inv->total, 2) }}</td>
                                    <td class="px-4 py-3">
                                        <span class="rounded-full px-2.5 py-0.5 text-xs font-bold {{ $inv->status === 'paid' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                            {{ ucfirst($inv->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <a href="{{ route('portal.invoice.pdf', $inv) }}" target="_blank" class="inline-flex items-center gap-1 font-semibold text-blue-600 hover:underline">
                                            <i class="fa-light fa-file-pdf"></i> Download PDF
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        @else
            <div class="rounded-2xl border border-slate-200 bg-white p-8 text-center dark:border-slate-800 dark:bg-slate-900">
                <i class="fa-light fa-chart-pie text-4xl text-emerald-500"></i>
                <h3 class="mt-3 text-lg font-bold text-slate-900 dark:text-white">{{ __(ucfirst($activeTab).' & Financial Ledger') }}</h3>
                <p class="mt-1 text-sm text-slate-500">{{ __('Double-entry ledger records, trial balance, and profit & loss analytics are active.') }}</p>
            </div>
        @endif
    @endif

    <!-- MODULE 2: CRM — LEADS, DEALS & CONTRACTS -->
    @if ($moduleKey === 'crm')
        @if ($activeTab === 'leads')
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                    <div>
                        <h2 class="text-lg font-bold text-slate-950 dark:text-white">{{ __('Leads Management & Customer Contacts') }}</h2>
                        <p class="text-sm text-slate-500">{{ __('Track new prospects, convert leads into deals, and log communication notes.') }}</p>
                    </div>
                    <button type="button" wire:click="openCreateModal('lead')" class="rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                        <i class="fa-light fa-user-plus mr-2"></i>{{ __('Add New Lead') }}
                    </button>
                </div>
                <div class="mt-5 overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50 text-xs uppercase text-slate-400 dark:bg-slate-800">
                            <tr>
                                <th class="px-4 py-3">Lead / Company Name</th>
                                <th class="px-4 py-3">Email & Phone</th>
                                <th class="px-4 py-3">Estimated Deal Value</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3 text-right">Convert to Deal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            <tr>
                                <td class="px-4 py-3 font-semibold text-slate-900 dark:text-white">Horizon Media Communications</td>
                                <td class="px-4 py-3 text-slate-500">contact@horizonmedia.ng · +234 802 987 6543</td>
                                <td class="px-4 py-3 font-bold text-slate-900 dark:text-white">₦7,800,000.00</td>
                                <td class="px-4 py-3"><span class="rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-bold text-blue-700">Qualified Lead</span></td>
                                <td class="px-4 py-3 text-right"><button wire:click="openCreateModal('convert_deal')" class="rounded-lg bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-600 hover:bg-blue-100">Convert to Deal</button></td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 font-semibold text-slate-900 dark:text-white">Apex Technology Solutions</td>
                                <td class="px-4 py-3 text-slate-500">info@apextech.com · +234 813 456 7890</td>
                                <td class="px-4 py-3 font-bold text-slate-900 dark:text-white">₦4,200,000.00</td>
                                <td class="px-4 py-3"><span class="rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-bold text-amber-700">New Contact</span></td>
                                <td class="px-4 py-3 text-right"><button wire:click="openCreateModal('convert_deal')" class="rounded-lg bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-600 hover:bg-blue-100">Convert to Deal</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        @else
            <div class="rounded-2xl border border-slate-200 bg-white p-8 text-center dark:border-slate-800 dark:bg-slate-900">
                <i class="fa-light fa-chart-kanban text-4xl text-blue-500"></i>
                <h3 class="mt-3 text-lg font-bold text-slate-900 dark:text-white">{{ __(ucfirst($activeTab).' & Client Management') }}</h3>
                <p class="mt-1 text-sm text-slate-500">{{ __('Deals pipeline, contracts, communication notes, and custom form fields are ready.') }}</p>
            </div>
        @endif
    @endif

    <!-- MODULE 3: PROJECT & TASK MANAGEMENT -->
    @if ($moduleKey === 'tasks')
        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                <div>
                    <h2 class="text-lg font-bold text-slate-950 dark:text-white">{{ __('Projects & Task Assignment') }}</h2>
                    <p class="text-sm text-slate-500">{{ __('Organize work, assign responsibilities, track progress logs, and view metrics.') }}</p>
                </div>
                <button type="button" wire:click="openCreateModal('project')" class="rounded-xl bg-sky-600 px-4 py-2 text-sm font-semibold text-white hover:bg-sky-700">
                    <i class="fa-light fa-plus mr-2"></i>{{ __('Create Project') }}
                </button>
            </div>
            <div class="mt-5 grid gap-4 md:grid-cols-3">
                <div class="rounded-xl border border-slate-200 p-5 dark:border-slate-800">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase text-sky-600">Active Project</span>
                        <span class="rounded-full bg-sky-50 px-2 py-0.5 text-[10px] font-bold text-sky-600">85% Complete</span>
                    </div>
                    <h3 class="mt-3 text-base font-bold text-slate-900 dark:text-white">Enterprise AI Onboarding</h3>
                    <p class="mt-1 text-xs text-slate-500">Due: 2026-08-20 · Assignee: Lagos HQ Team</p>
                    <div class="mt-4 h-2 w-full overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800">
                        <div class="h-full bg-sky-500" style="width: 85%;"></div>
                    </div>
                </div>
                <div class="rounded-xl border border-slate-200 p-5 dark:border-slate-800">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase text-emerald-600">Finance</span>
                        <span class="rounded-full bg-emerald-50 px-2 py-0.5 text-[10px] font-bold text-emerald-600">40% Complete</span>
                    </div>
                    <h3 class="mt-3 text-base font-bold text-slate-900 dark:text-white">Q3 Financial & Ledger Audit</h3>
                    <p class="mt-1 text-xs text-slate-500">Due: 2026-08-30 · Assignee: Finance Team</p>
                    <div class="mt-4 h-2 w-full overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800">
                        <div class="h-full bg-emerald-500" style="width: 40%;"></div>
                    </div>
                </div>
                <div class="rounded-xl border border-slate-200 p-5 dark:border-slate-800">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase text-purple-600">IT Ops</span>
                        <span class="rounded-full bg-purple-50 px-2 py-0.5 text-[10px] font-bold text-purple-600">60% Complete</span>
                    </div>
                    <h3 class="mt-3 text-base font-bold text-slate-900 dark:text-white">Omnichannel Inbox Migration</h3>
                    <p class="mt-1 text-xs text-slate-500">Due: 2026-08-15 · Assignee: IT & Support</p>
                    <div class="mt-4 h-2 w-full overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800">
                        <div class="h-full bg-purple-500" style="width: 60%;"></div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- MODULE 4: PRODUCTS & INVENTORY -->
    @if ($moduleKey === 'inventory')
        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                <div>
                    <h2 class="text-lg font-bold text-slate-950 dark:text-white">{{ __('Products, Stock & Warehouse Management') }}</h2>
                    <p class="text-sm text-slate-500">{{ __('Track product details, stock movements, supplier warehouses, and low stock alerts.') }}</p>
                </div>
                <button type="button" wire:click="openCreateModal('product')" class="rounded-xl bg-orange-600 px-4 py-2 text-sm font-semibold text-white hover:bg-orange-700">
                    <i class="fa-light fa-plus mr-2"></i>{{ __('Add New Product SKU') }}
                </button>
            </div>
            <div class="mt-5 overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase text-slate-400 dark:bg-slate-800">
                        <tr>
                            <th class="px-4 py-3">SKU</th>
                            <th class="px-4 py-3">Product Name & Category</th>
                            <th class="px-4 py-3">Price (NGN)</th>
                            <th class="px-4 py-3">Stock Level</th>
                            <th class="px-4 py-3">Reorder Status</th>
                            <th class="px-4 py-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        <tr>
                            <td class="px-4 py-3 font-mono font-semibold text-slate-900 dark:text-white">ENT-LIC-001</td>
                            <td class="px-4 py-3">Enterprise Server License · Software</td>
                            <td class="px-4 py-3 font-bold text-slate-900 dark:text-white">₦250,000.00</td>
                            <td class="px-4 py-3 font-semibold text-slate-800 dark:text-slate-200">2 units</td>
                            <td class="px-4 py-3"><span class="rounded-full bg-rose-100 px-2.5 py-0.5 text-xs font-bold text-rose-700">Low Stock Alert</span></td>
                            <td class="px-4 py-3 text-right"><button wire:click="openCreateModal('reorder')" class="text-orange-600 hover:underline">Reorder Stock</button></td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 font-mono font-semibold text-slate-900 dark:text-white">POS-HDW-004</td>
                            <td class="px-4 py-3">Thermal Barcode Scanner Unit · Hardware</td>
                            <td class="px-4 py-3 font-bold text-slate-900 dark:text-white">₦85,000.00</td>
                            <td class="px-4 py-3 font-semibold text-slate-800 dark:text-slate-200">18 units</td>
                            <td class="px-4 py-3"><span class="rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-bold text-emerald-700">In Stock</span></td>
                            <td class="px-4 py-3 text-right"><button class="text-blue-600 hover:underline">Edit SKU</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    @endif

    <!-- MODULE 5: POINT OF SALE (POS) -->
    @if ($moduleKey === 'pos')
        <div class="grid gap-6 xl:grid-cols-[1.2fr_0.8fr]">
            <!-- POS Terminal Checkout -->
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                    <div>
                        <h2 class="text-lg font-bold text-slate-950 dark:text-white">{{ __('POS Retail Checkout Terminal') }}</h2>
                        <p class="text-sm text-slate-500">{{ __('Click items below to add to customer cart and generate print receipt.') }}</p>
                    </div>
                    <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-600">Terminal #01 Active</span>
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
                        <button type="button" wire:click="addToPosCart('{{ $product['sku'] }}', '{{ $product['name'] }}', {{ $product['price'] }})" class="group rounded-xl border border-slate-200 p-4 text-left shadow-xs transition hover:border-orange-500 hover:shadow-md dark:border-slate-800 dark:hover:border-orange-500">
                            <p class="text-xs font-mono font-semibold text-slate-400">{{ $product['sku'] }}</p>
                            <p class="mt-2 text-sm font-bold text-slate-900 group-hover:text-orange-600 dark:text-white">{{ $product['name'] }}</p>
                            <p class="mt-2 text-base font-extrabold text-orange-600">₦{{ number_format($product['price'], 2) }}</p>
                        </button>
                    @endforeach
                </div>
            </section>

            <!-- Active Cart Drawer & Receipt Generation -->
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                    <h2 class="text-lg font-bold text-slate-950 dark:text-white">{{ __('Cart & Order Summary') }}</h2>
                    @if (!empty($posCart))
                        <button type="button" wire:click="clearPosCart" class="text-xs font-semibold text-rose-500 hover:underline">{{ __('Clear Cart') }}</button>
                    @endif
                </div>

                @if (empty($posCart))
                    <div class="my-12 text-center text-slate-400">
                        <i class="fa-light fa-basket-shopping text-4xl"></i>
                        <p class="mt-2 text-sm font-medium">{{ __('Cart is empty. Click products on left to add.') }}</p>
                    </div>
                @else
                    <div class="mt-4 divide-y divide-slate-100 dark:divide-slate-800 max-h-64 overflow-y-auto">
                        @foreach ($posCart as $index => $cartItem)
                            <div class="flex items-center justify-between py-3">
                                <div>
                                    <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $cartItem['name'] }}</p>
                                    <p class="text-xs text-slate-400">₦{{ number_format($cartItem['price'], 2) }} x {{ $cartItem['quantity'] }}</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button type="button" wire:click="updatePosCartQuantity({{ $index }}, {{ $cartItem['quantity'] - 1 }})" class="h-6 w-6 rounded-md bg-slate-100 font-bold text-slate-600 dark:bg-slate-800 dark:text-slate-300">-</button>
                                    <span class="text-sm font-bold">{{ $cartItem['quantity'] }}</span>
                                    <button type="button" wire:click="updatePosCartQuantity({{ $index }}, {{ $cartItem['quantity'] + 1 }})" class="h-6 w-6 rounded-md bg-slate-100 font-bold text-slate-600 dark:bg-slate-800 dark:text-slate-300">+</button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @php
                        $sub = array_reduce($posCart, fn ($acc, $item) => $acc + ($item['price'] * $item['quantity']), 0.0);
                        $tax = $sub * $posTaxRate;
                        $tot = $sub + $tax;
                    @endphp

                    <div class="mt-6 space-y-2 border-t pt-4 text-sm dark:border-slate-800">
                        <div class="flex justify-between text-slate-500">
                            <span>Subtotal</span>
                            <span>₦{{ number_format($sub, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-slate-500">
                            <span>VAT (7.5%)</span>
                            <span>₦{{ number_format($tax, 2) }}</span>
                        </div>
                        <div class="flex justify-between font-extrabold text-base text-slate-900 dark:text-white pt-2 border-t dark:border-slate-800">
                            <span>Total Payable</span>
                            <span class="text-orange-600">₦{{ number_format($tot, 2) }}</span>
                        </div>
                    </div>

                    <button type="button" wire:click="checkoutPos" class="mt-6 w-full rounded-xl bg-orange-600 py-3 text-center text-sm font-bold text-white shadow-lg hover:bg-orange-700">
                        <i class="fa-light fa-print mr-2"></i>{{ __('Complete Sale & Print Receipt') }}
                    </button>
                @endif
            </section>
        </div>
    @endif

    <!-- MODULE 6: USER MANAGEMENT & SECURITY -->
    @if ($moduleKey === 'administration')
        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                <div>
                    <h2 class="text-lg font-bold text-slate-950 dark:text-white">{{ __('User Management, Roles & Security') }}</h2>
                    <p class="text-sm text-slate-500">{{ __('Control team access, set role permissions, manage clients, and track security activity.') }}</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('admin-user-roles.index') }}" wire:navigate class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200">
                        <i class="fa-light fa-user-shield mr-2"></i>{{ __('Roles & Permissions') }}
                    </a>
                    <a href="{{ route('admin-users.create') }}" wire:navigate class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                        <i class="fa-light fa-user-plus mr-2"></i>{{ __('Add New User') }}
                    </a>
                </div>
            </div>

            <div class="mt-5 overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase text-slate-400 dark:bg-slate-800">
                        <tr>
                            <th class="px-4 py-3">User Name</th>
                            <th class="px-4 py-3">Email Address</th>
                            <th class="px-4 py-3">Timezone</th>
                            <th class="px-4 py-3">Role</th>
                            <th class="px-4 py-3 text-right">Edit</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @foreach ($users as $u)
                            <tr>
                                <td class="px-4 py-3 font-semibold text-slate-900 dark:text-white">{{ $u->name }}</td>
                                <td class="px-4 py-3 text-slate-500">{{ $u->email }}</td>
                                <td class="px-4 py-3 text-slate-500">{{ $u->timezone ?: 'Africa/Lagos' }}</td>
                                <td class="px-4 py-3">
                                    <span class="rounded-full px-2.5 py-0.5 text-xs font-bold {{ $u->is_super_admin ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                                        {{ $u->is_super_admin ? 'Super Admin' : ($u->role?->name ?: 'Member') }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('admin-users.edit', $u) }}" wire:navigate class="text-blue-600 hover:underline">Edit</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    @endif

    <!-- MODULE 7: AI AGENTS & CONTENT STUDIO -->
    @if ($moduleKey === 'ai-agents')
        <div class="grid gap-6 xl:grid-cols-[1.1fr_0.9fr]">
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                    <div>
                        <h2 class="text-lg font-bold text-slate-950 dark:text-white">{{ __('AI Content & Caption Studio') }}</h2>
                        <p class="text-sm text-slate-500">{{ __('Generate high-converting social posts, captions, and marketing copy.') }}</p>
                    </div>
                    <span class="rounded-full bg-purple-50 px-3 py-1 text-xs font-bold text-purple-600">AI Model Active</span>
                </div>

                <form wire:submit.prevent="generateAiContent" class="mt-5 space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200">Content Prompt / Topic</label>
                        <textarea wire:model="aiPrompt" rows="3" placeholder="e.g. Write a promotion for our new Abuja branch POS equipment release..." class="mt-1 block w-full rounded-xl border border-slate-200 p-3 text-sm outline-none focus:border-purple-500 dark:border-slate-800 dark:bg-slate-800 dark:text-white"></textarea>
                    </div>
                    <button type="submit" class="w-full rounded-xl bg-purple-600 py-3 text-center text-sm font-bold text-white shadow-md hover:bg-purple-700">
                        <i class="fa-light fa-sparkles mr-2"></i>{{ __('Generate AI Content') }}
                    </button>
                </form>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h2 class="text-lg font-bold text-slate-950 dark:text-white border-b pb-4 dark:border-slate-800">{{ __('AI Output & Distribution') }}</h2>

                @if ($generatedResult)
                    <div class="mt-4 rounded-xl border border-purple-200 bg-purple-50/50 p-4 dark:border-purple-900/60 dark:bg-purple-950/30">
                        <p class="text-sm font-medium text-slate-800 dark:text-purple-100">{{ $generatedResult }}</p>
                    </div>
                    <div class="mt-4 flex flex-col gap-2">
                        <button type="button" wire:click="sendGeneratedToPublishing" class="w-full rounded-xl bg-blue-600 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-blue-700">
                            <i class="fa-light fa-calendar-plus mr-2"></i>{{ __('Schedule in Publishing Calendar') }}
                        </button>
                        <a href="{{ route('portal.inbox') }}" wire:navigate class="w-full rounded-xl border border-slate-200 py-2.5 text-center text-sm font-semibold text-slate-700 hover:bg-slate-50 dark:border-slate-800 dark:text-slate-200">
                            <i class="fa-light fa-inbox mr-2"></i>{{ __('Send to Omnichannel Inbox') }}
                        </a>
                    </div>
                @else
                    <div class="my-12 text-center text-slate-400">
                        <i class="fa-light fa-sparkles text-4xl text-purple-300"></i>
                        <p class="mt-2 text-sm font-medium">{{ __('Enter a prompt on the left to generate AI marketing content.') }}</p>
                    </div>
                @endif
            </section>
        </div>
    @endif

    <!-- MODULE 8: AUTOMATION RULES & TRIGGERS -->
    @if ($moduleKey === 'automation')
        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                <div>
                    <h2 class="text-lg font-bold text-slate-950 dark:text-white">{{ __('Workflow Automation Rules & Webhooks') }}</h2>
                    <p class="text-sm text-slate-500">{{ __('Automate background tasks across CRM, Finance, POS, and Notifications.') }}</p>
                </div>
                <button type="button" wire:click="openCreateModal('automation')" class="rounded-xl bg-amber-600 px-4 py-2 text-sm font-semibold text-white hover:bg-amber-700">
                    <i class="fa-light fa-plus mr-2"></i>{{ __('Add New Automation Rule') }}
                </button>
            </div>
            <div class="mt-5 space-y-3">
                @foreach ($automationRules as $rule)
                    <div class="flex items-center justify-between rounded-xl border border-slate-200 p-4 dark:border-slate-800">
                        <div>
                            <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $rule['name'] }}</p>
                            <p class="text-xs text-slate-400">Trigger: <span class="font-semibold text-slate-600 dark:text-slate-300">{{ $rule['trigger'] }}</span> $\rightarrow$ Action: <span class="font-semibold text-blue-600">{{ $rule['action'] }}</span></p>
                        </div>
                        <button type="button" wire:click="toggleAutomationRule({{ $rule['id'] }})" class="rounded-full px-3 py-1 text-xs font-bold transition {{ $rule['active'] ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                            {{ $rule['active'] ? 'Active' : 'Paused' }}
                        </button>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <!-- MODULE 9: EXECUTIVE REPORTS & ANALYTICS -->
    @if ($moduleKey === 'reports')
        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                <div>
                    <h2 class="text-lg font-bold text-slate-950 dark:text-white">{{ __('Executive Management Reports & Performance') }}</h2>
                    <p class="text-sm text-slate-500">{{ __('Unified business intelligence across Abuja HQ and regional branches.') }}</p>
                </div>
                <a href="{{ route('portal.finance.export-csv') }}" class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
                    <i class="fa-light fa-file-excel mr-2"></i>{{ __('Export Master Executive Report (CSV)') }}
                </a>
            </div>

            <div class="mt-6 grid gap-4 md:grid-cols-3">
                <div class="rounded-xl border border-slate-200 p-4 dark:border-slate-800">
                    <p class="text-xs font-bold text-slate-400 uppercase">Monthly Revenue</p>
                    <p class="mt-2 text-2xl font-extrabold text-emerald-600">₦1,245,780.00</p>
                    <p class="mt-1 text-xs text-slate-500">From 182 settled transactions</p>
                </div>
                <div class="rounded-xl border border-slate-200 p-4 dark:border-slate-800">
                    <p class="text-xs font-bold text-slate-400 uppercase">Active CRM Pipeline</p>
                    <p class="mt-2 text-2xl font-extrabold text-blue-600">₦12,000,000.00</p>
                    <p class="mt-1 text-xs text-slate-500">56 open leads in pipeline</p>
                </div>
                <div class="rounded-xl border border-slate-200 p-4 dark:border-slate-800">
                    <p class="text-xs font-bold text-slate-400 uppercase">Total Inventory Valuation</p>
                    <p class="mt-2 text-2xl font-extrabold text-orange-600">₦18,450,000.00</p>
                    <p class="mt-1 text-xs text-slate-500">342 active product SKUs</p>
                </div>
            </div>
        </section>
    @endif

    <!-- Action Modal -->
    @if ($showModal)
        <div class="fixed inset-0 z-[160] flex items-center justify-center p-4" x-cloak>
            <div wire:click="closeModal" class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm"></div>
            <div class="relative w-full max-w-lg rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl dark:border-slate-800 dark:bg-slate-900">
                @if ($modalType === 'pos_receipt' && $modalData)
                    <div class="text-center border-b pb-4 dark:border-slate-800">
                        <i class="fa-light fa-circle-check text-4xl text-emerald-500"></i>
                        <h3 class="mt-2 text-xl font-bold text-slate-950 dark:text-white">Ascend Systems POS Receipt</h3>
                        <p class="text-xs text-slate-400">Receipt #: {{ $modalData['receipt_no'] }} · {{ $modalData['date'] }}</p>
                    </div>

                    <div class="mt-4 space-y-2 text-sm border-b pb-4 dark:border-slate-800">
                        @foreach ($modalData['items'] as $item)
                            <div class="flex justify-between">
                                <span class="font-medium">{{ $item['name'] }} (x{{ $item['quantity'] }})</span>
                                <span class="font-mono">₦{{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4 space-y-1 text-sm">
                        <div class="flex justify-between text-slate-500"><span>Subtotal</span><span>₦{{ number_format($modalData['subtotal'], 2) }}</span></div>
                        <div class="flex justify-between text-slate-500"><span>VAT (7.5%)</span><span>₦{{ number_format($modalData['tax'], 2) }}</span></div>
                        <div class="flex justify-between font-extrabold text-base text-slate-900 dark:text-white pt-2"><span>Total Paid</span><span class="text-orange-600">₦{{ number_format($modalData['total'], 2) }}</span></div>
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" wire:click="closeModal" class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 dark:border-slate-800 dark:text-slate-200">
                            {{ __('Close') }}
                        </button>
                        <button type="button" onclick="window.print()" class="rounded-xl bg-orange-600 px-4 py-2 text-sm font-semibold text-white hover:bg-orange-700">
                            <i class="fa-light fa-print mr-2"></i>{{ __('Print Receipt') }}
                        </button>
                    </div>
                @else
                    <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                        <h3 class="text-lg font-bold text-slate-950 dark:text-white">{{ __('Create New Record') }}</h3>
                        <button type="button" wire:click="closeModal" class="rounded-lg p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-slate-800">
                            <i class="fa-light fa-xmark text-base"></i>
                        </button>
                    </div>

                    <form wire:submit.prevent="submitModalForm" class="mt-5 space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200">Title / Name</label>
                            <input type="text" wire:model.defer="form.name" required class="mt-1 block w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none focus:border-blue-500 dark:border-slate-800 dark:bg-slate-800 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200">Amount / Price (NGN)</label>
                            <input type="text" wire:model.defer="form.amount" placeholder="e.g. 250000" class="mt-1 block w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none focus:border-blue-500 dark:border-slate-800 dark:bg-slate-800 dark:text-white">
                        </div>
                        <div class="flex justify-end gap-3 pt-4 border-t dark:border-slate-800">
                            <button type="button" wire:click="closeModal" class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 dark:border-slate-800 dark:text-slate-200">Cancel</button>
                            <button type="submit" class="rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Save</button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    @endif
</div>
