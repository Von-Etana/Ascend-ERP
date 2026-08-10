<?php
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
?>

<div class="space-y-8" style="--ascend-accent: <?php echo e($accent); ?>;">
    <!-- Premium Module Glassmorphic Header Banner -->
    <div class="relative overflow-hidden rounded-3xl border border-slate-200/80 p-6 md:p-8 shadow-sm transition-all duration-300 dark:border-slate-800" style="background: <?php echo e($headerBg); ?>;">
        <div class="absolute -right-10 -top-10 h-64 w-64 rounded-full opacity-20 blur-3xl" style="background: <?php echo e($accent); ?>;"></div>

        <div class="relative z-10 flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex items-start sm:items-center gap-5">
                <span class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl text-2xl text-white shadow-xl shadow-blue-500/10 transition-transform duration-300 hover:scale-105" style="background: <?php echo e($accent); ?>;">
                    <i class="<?php echo e(match($moduleKey) {
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
                    }); ?>"></i>
                </span>
                <div>
                    <a href="<?php echo e(route('portal.dashboard')); ?>" wire:navigate class="mb-2 inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-slate-500 transition hover:text-slate-900 dark:hover:text-white">
                        <i class="fa-light fa-arrow-left"></i><?php echo e(__('Workspace')); ?> &rarr; <?php echo e(__(ucfirst($moduleKey))); ?>

                    </a>
                    <h1 class="text-3xl font-extrabold tracking-tight text-slate-950 dark:text-white">
                        <?php echo e(match($moduleKey) {
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
                        }); ?>

                    </h1>
                    <p class="mt-1 max-w-2xl text-sm font-medium text-slate-600 dark:text-slate-300">
                        <?php echo e(match($moduleKey) {
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
                        }); ?>

                    </p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(in_array($moduleKey, ['finance', 'crm', 'sales', 'inventory', 'reports'], true)): ?>
                    <a href="<?php echo e(route('portal.finance.export-csv')); ?>" class="inline-flex items-center gap-2 rounded-2xl border border-slate-200/90 bg-white/90 px-4 py-2.5 text-sm font-bold text-slate-700 shadow-sm backdrop-blur-md transition-all hover:bg-white hover:shadow dark:border-slate-800 dark:bg-slate-900/90 dark:text-slate-200">
                        <i class="fa-light fa-file-excel text-emerald-600"></i>
                        <?php echo e(__('Export CSV')); ?>

                    </a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <button type="button" wire:click="openCreateModal('<?php echo e($moduleKey === 'finance' ? 'invoice' : ($moduleKey === 'inventory' ? 'product' : $moduleKey)); ?>')" class="inline-flex items-center gap-2 rounded-2xl px-6 py-3 text-sm font-bold text-white shadow-lg shadow-blue-500/20 transition-all hover:scale-[1.02] hover:brightness-105 active:scale-95" style="background: <?php echo e($accent); ?>;">
                    <i class="fa-light fa-plus text-base"></i>
                    <?php echo e(match($moduleKey) {
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
                    }); ?>

                </button>
            </div>
        </div>
    </div>

    <!-- Status Toast Notifications -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('status')): ?>
        <div class="flex items-center justify-between rounded-2xl border border-emerald-500/30 bg-emerald-500/10 px-5 py-3.5 text-sm font-bold text-emerald-600 shadow-xs dark:text-emerald-400">
            <div class="flex items-center gap-2">
                <i class="fa-light fa-circle-check text-base"></i>
                <span><?php echo e(session('status')); ?></span>
            </div>
            <button type="button" class="text-emerald-500 hover:text-emerald-700" onclick="this.parentElement.remove()"><i class="fa-light fa-xmark"></i></button>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('warning')): ?>
        <div class="flex items-center justify-between rounded-2xl border border-amber-500/30 bg-amber-500/10 px-5 py-3.5 text-sm font-bold text-amber-600 shadow-xs dark:text-amber-400">
            <div class="flex items-center gap-2">
                <i class="fa-light fa-triangle-exclamation text-base"></i>
                <span><?php echo e(session('warning')); ?></span>
            </div>
            <button type="button" class="text-amber-500 hover:text-amber-700" onclick="this.parentElement.remove()"><i class="fa-light fa-xmark"></i></button>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Module Sub-Tabs Pill Navigation Bar -->
    <div class="rounded-2xl border border-slate-200/80 bg-slate-100/80 p-1.5 backdrop-blur-md dark:border-slate-800 dark:bg-slate-900/80">
        <div class="flex items-center gap-1.5 overflow-x-auto scrollbar-none">
            <?php
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
                        'analytics' => ['label' => 'Campaign Analytics', 'icon' => 'fa-light fa-chart-pie'],
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
            ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $subTabs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tabKey => $tab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <button
                    type="button"
                    wire:click="setTab('<?php echo e($tabKey); ?>')"
                    class="flex items-center gap-2.5 rounded-xl px-4 py-2.5 text-xs font-bold whitespace-nowrap transition-all duration-200 <?php echo e($activeTab === $tabKey ? 'bg-white text-slate-950 shadow-md shadow-slate-200/50 dark:bg-slate-800 dark:text-white dark:shadow-none' : 'text-slate-600 hover:bg-white/60 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800/50 dark:hover:text-white'); ?>"
                    <?php if($activeTab === $tabKey): ?> style="color: <?php echo e($accent); ?>;" <?php endif; ?>
                >
                    <i class="<?php echo e($tab['icon']); ?> text-sm"></i>
                    <?php echo e(__($tab['label'])); ?>

                </button>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>
    </div>

    <!-- MODULE 1: ACCOUNTING & FINANCE ENHANCED USERFLOW -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($moduleKey === 'finance'): ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activeTab === 'overview'): ?>
            <div class="space-y-6">
                <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400"><?php echo e(__('Total Revenue')); ?></p>
                        <p class="mt-2 text-2xl font-black text-emerald-600">₦1,245,780.00</p>
                        <p class="mt-1 text-xs font-medium text-emerald-500"><i class="fa-light fa-arrow-trend-up mr-1"></i>+18.6% vs last month</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400"><?php echo e(__('Operating Expenses')); ?></p>
                        <p class="mt-2 text-2xl font-black text-rose-500">₦324,500.00</p>
                        <p class="mt-1 text-xs font-medium text-slate-400">Fixed & variable operational costs</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400"><?php echo e(__('Net Profit Margin')); ?></p>
                        <p class="mt-2 text-2xl font-black text-blue-600">₦921,280.00</p>
                        <p class="mt-1 text-xs font-medium text-blue-500">73.9% net margin</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400"><?php echo e(__('Outstanding Bills')); ?></p>
                        <p class="mt-2 text-2xl font-black text-amber-600">₦180,000.00</p>
                        <p class="mt-1 text-xs font-medium text-slate-400">2 pending vendor bills</p>
                    </div>
                </div>

                <!-- Banking Accounts & Liquid Reserves -->
                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                        <div>
                            <h2 class="text-lg font-bold text-slate-950 dark:text-white"><?php echo e(__('Banking Accounts & Liquid Reserves (NGN)')); ?></h2>
                            <p class="text-sm text-slate-500"><?php echo e(__('Bank accounts, cash reserves, and inter-company liquidity transfers.')); ?></p>
                        </div>
                        <button type="button" wire:click="initiateBankTransfer('Access Bank HQ', 'GTBank Operations', 500000.00)" class="rounded-xl border border-emerald-300 bg-emerald-50 px-4 py-2 text-xs font-bold text-emerald-700 hover:bg-emerald-100 dark:bg-emerald-950/40 dark:border-emerald-800 dark:text-emerald-300">
                            <i class="fa-light fa-arrow-right-arrow-left mr-1.5"></i><?php echo e(__('Initiate Transfer')); ?>

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
        <?php elseif($activeTab === 'invoices'): ?>
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                    <div>
                        <h2 class="text-lg font-bold text-slate-950 dark:text-white"><?php echo e(__('Invoices, Estimates & Billing')); ?></h2>
                        <p class="text-sm text-slate-500"><?php echo e(__('Issue customer invoices, track billing statuses, and dispatch payment reminders.')); ?></p>
                    </div>
                    <button type="button" wire:click="openCreateModal('invoice')" class="rounded-xl bg-emerald-600 px-4 py-2.5 text-xs font-bold text-white hover:bg-emerald-700 transition">
                        <i class="fa-light fa-plus mr-1.5"></i><?php echo e(__('Create New Invoice')); ?>

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
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $dbInvoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <tr class="transition hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                                    <td class="px-4 py-3.5 font-bold font-mono text-slate-900 dark:text-white"><?php echo e($inv->invoice_number); ?></td>
                                    <td class="px-4 py-3.5 font-bold"><?php echo e($inv->client_name); ?></td>
                                    <td class="px-4 py-3.5 text-slate-500 text-xs"><?php echo e($inv->issue_date?->format('Y-m-d') ?: now()->format('Y-m-d')); ?></td>
                                    <td class="px-4 py-3.5 font-black text-slate-900 dark:text-white">₦<?php echo e(number_format($inv->total, 2)); ?></td>
                                    <td class="px-4 py-3.5">
                                        <span class="rounded-full px-3 py-1 text-xs font-bold <?php echo e($inv->status === 'paid' ? 'bg-emerald-500/10 text-emerald-600 border border-emerald-500/20' : 'bg-amber-500/10 text-amber-600 border border-amber-500/20'); ?>">
                                            <?php echo e(ucfirst($inv->status)); ?>

                                        </span>
                                    </td>
                                    <td class="px-4 py-3.5 text-right space-x-2">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($inv->status !== 'paid'): ?>
                                            <button type="button" wire:click="markInvoicePaid(<?php echo e($inv->id); ?>)" class="text-xs font-bold text-emerald-600 hover:underline">
                                                Mark Paid
                                            </button>
                                            <button type="button" wire:click="generatePaystackPaymentLink(<?php echo e($inv->id); ?>)" class="rounded-lg bg-blue-500/10 px-2.5 py-1 text-xs font-bold text-blue-600 hover:bg-blue-500/20 transition">
                                                <i class="fa-brands fa-paystack mr-1 text-blue-500"></i>Paystack NGN
                                            </button>
                                            <button type="button" wire:click="sendWhatsAppInvoiceNotice(<?php echo e($inv->id); ?>)" class="text-xs font-bold text-emerald-600 hover:underline" title="Send WhatsApp Payment Notice">
                                                <i class="fa-brands fa-whatsapp mr-1 text-emerald-500"></i>WhatsApp
                                            </button>
                                            <button type="button" wire:click="sendInvoiceReminder(<?php echo e($inv->id); ?>)" class="text-xs font-bold text-slate-500 hover:text-slate-900 dark:hover:text-white">
                                                <i class="fa-light fa-bell mr-1"></i>Reminder
                                            </button>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <a href="<?php echo e(route('portal.invoice.pdf', $inv)); ?>" target="_blank" class="inline-flex items-center gap-1 font-bold text-rose-600 hover:underline text-xs">
                                            <i class="fa-light fa-file-pdf"></i>PDF
                                        </a>
                                    </td>
                                </tr>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        <?php elseif($activeTab === 'expenses'): ?>
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                    <div>
                        <h2 class="text-lg font-bold text-slate-950 dark:text-white"><?php echo e(__('Expenses & Vendor Receipts Log')); ?></h2>
                        <p class="text-sm text-slate-500"><?php echo e(__('Record company expenses, vendor payments, and operational costs.')); ?></p>
                    </div>
                    <button type="button" wire:click="openCreateModal('expense')" class="rounded-xl bg-emerald-600 px-4 py-2 text-xs font-bold text-white hover:bg-emerald-700">
                        <i class="fa-light fa-plus mr-1.5"></i><?php echo e(__('Log Expense')); ?>

                    </button>
                </div>

                <div class="mt-5 overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50 text-xs uppercase text-slate-400 dark:bg-slate-800">
                            <tr>
                                <th class="px-4 py-3.5">Category</th>
                                <th class="px-4 py-3.5">Vendor / Payee</th>
                                <th class="px-4 py-3.5">Amount (NGN)</th>
                                <th class="px-4 py-3.5">Payment Method</th>
                                <th class="px-4 py-3.5">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $dbExpenses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <tr class="transition hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                                    <td class="px-4 py-3.5 font-bold text-slate-900 dark:text-white"><?php echo e($exp->category); ?></td>
                                    <td class="px-4 py-3.5 font-medium"><?php echo e($exp->vendor); ?></td>
                                    <td class="px-4 py-3.5 font-black text-rose-600">₦<?php echo e(number_format($exp->amount, 2)); ?></td>
                                    <td class="px-4 py-3.5 text-xs text-slate-500 uppercase"><?php echo e(str_replace('_', ' ', $exp->payment_method)); ?></td>
                                    <td class="px-4 py-3.5 text-slate-500 text-xs"><?php echo e($exp->expense_date?->format('Y-m-d') ?: now()->format('Y-m-d')); ?></td>
                                </tr>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        <?php elseif($activeTab === 'ledger'): ?>
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                    <div>
                        <h2 class="text-lg font-bold text-slate-950 dark:text-white"><?php echo e(__('General Ledger & Double-Entry Trial Balance Sheet')); ?></h2>
                        <p class="text-sm text-slate-500"><?php echo e(__('Complete trial balance audit of chart of accounts, debits, and credits in NGN.')); ?></p>
                    </div>
                    <a href="<?php echo e(route('portal.finance.export-csv')); ?>" class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-2 text-xs font-bold text-emerald-700 hover:bg-emerald-100 dark:bg-emerald-950/40 dark:border-emerald-800 dark:text-emerald-300">
                        <i class="fa-light fa-file-excel mr-1.5"></i><?php echo e(__('Export Ledger CSV')); ?>

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
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $generalLedger; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $acc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <tr class="transition hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                                    <td class="px-4 py-3.5 font-mono font-bold text-slate-900 dark:text-white"><?php echo e($acc['code']); ?></td>
                                    <td class="px-4 py-3.5 font-bold"><?php echo e($acc['account']); ?></td>
                                    <td class="px-4 py-3.5">
                                        <span class="rounded-full px-2.5 py-0.5 text-xs font-bold bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300">
                                            <?php echo e($acc['type']); ?>

                                        </span>
                                    </td>
                                    <td class="px-4 py-3.5 font-mono text-slate-700 dark:text-slate-300">₦<?php echo e(number_format($acc['debit'], 2)); ?></td>
                                    <td class="px-4 py-3.5 font-mono text-slate-700 dark:text-slate-300">₦<?php echo e(number_format($acc['credit'], 2)); ?></td>
                                    <td class="px-4 py-3.5 font-mono font-black text-right <?php echo e($acc['balance'] >= 0 ? 'text-emerald-600' : 'text-rose-600'); ?>">
                                        ₦<?php echo e(number_format($acc['balance'], 2)); ?>

                                    </td>
                                </tr>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        <?php else: ?>
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                    <div>
                        <h2 class="text-lg font-bold text-slate-950 dark:text-white"><?php echo e(__('Profit & Loss (P&L) Income Statement Report')); ?></h2>
                        <p class="text-sm text-slate-500"><?php echo e(__('Net profit performance breakdown, gross margins, and EBITDA.')); ?></p>
                    </div>
                    <a href="<?php echo e(route('portal.finance.export-csv')); ?>" class="rounded-xl bg-emerald-600 px-4 py-2 text-xs font-bold text-white hover:bg-emerald-700">
                        <i class="fa-light fa-download mr-1.5"></i><?php echo e(__('Download CSV Statement')); ?>

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
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- MODULE 2: CRM — LEADS, DEALS & PIPELINE WORKSPACE -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($moduleKey === 'crm'): ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activeTab === 'leads'): ?>
            <div class="space-y-6">
                <!-- CRM Pipeline KPI Summary Cards -->
                <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400"><?php echo e(__('Total Pipeline Value')); ?></p>
                        <p class="mt-2 text-2xl font-black text-blue-600">₦<?php echo e(number_format($dbDeals->sum('value'), 2)); ?></p>
                        <p class="mt-1 text-xs font-medium text-blue-500"><i class="fa-light fa-chart-line-up mr-1"></i><?php echo e($dbDeals->count()); ?> Active Deals</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400"><?php echo e(__('Qualified Leads')); ?></p>
                        <p class="mt-2 text-2xl font-black text-emerald-600"><?php echo e($dbLeads->where('status', 'qualified')->count() ?: $dbLeads->count()); ?> Leads</p>
                        <p class="mt-1 text-xs font-medium text-emerald-500"><i class="fa-light fa-user-check mr-1"></i>Ready for conversion</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400"><?php echo e(__('Win Rate')); ?></p>
                        <p class="mt-2 text-2xl font-black text-slate-900 dark:text-white">68.5%</p>
                        <p class="mt-1 text-xs font-medium text-emerald-500"><i class="fa-light fa-arrow-trend-up mr-1"></i>+4.2% vs last quarter</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400"><?php echo e(__('Avg Deal Size')); ?></p>
                        <p class="mt-2 text-2xl font-black text-purple-600">₦<?php echo e($dbDeals->count() > 0 ? number_format($dbDeals->avg('value'), 2) : '3,500,000.00'); ?></p>
                        <p class="mt-1 text-xs font-medium text-slate-400">Per closed deal</p>
                    </div>
                </div>

                <!-- Leads Management Table -->
                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between border-b pb-4 dark:border-slate-800">
                        <div>
                            <h2 class="text-lg font-bold text-slate-950 dark:text-white"><?php echo e(__('Lead Acquisition & Qualification Pipeline')); ?></h2>
                            <p class="text-sm text-slate-500"><?php echo e(__('Manage inbound leads, qualify prospects, and convert to active deals.')); ?></p>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="relative">
                                <i class="fa-light fa-search absolute left-3 top-2.5 text-slate-400"></i>
                                <input type="text" wire:model.live.debounce.300ms="searchQuery" placeholder="Search leads..." class="rounded-xl border border-slate-200 py-2 pl-9 pr-4 text-xs font-semibold outline-none focus:border-blue-500 dark:border-slate-700 dark:bg-slate-800 dark:text-white w-48">
                            </div>
                            <button type="button" wire:click="openCreateModal('lead')" class="rounded-xl bg-blue-600 px-4 py-2.5 text-xs font-bold text-white shadow-md hover:bg-blue-700 transition">
                                <i class="fa-light fa-plus mr-1.5"></i><?php echo e(__('Add New Lead')); ?>

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
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $dbLeads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lead): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                    <tr class="transition hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                                        <td class="px-4 py-3.5">
                                            <p class="font-bold text-slate-900 dark:text-white"><?php echo e($lead->company_name); ?></p>
                                            <p class="text-xs text-slate-400"><?php echo e($lead->contact_person); ?></p>
                                        </td>
                                        <td class="px-4 py-3.5 text-xs">
                                            <p class="font-semibold text-slate-600 dark:text-slate-300"><?php echo e($lead->email); ?></p>
                                            <p class="text-slate-400"><?php echo e($lead->phone); ?></p>
                                        </td>
                                        <td class="px-4 py-3.5 font-black text-slate-900 dark:text-white">₦<?php echo e(number_format($lead->deal_value, 2)); ?></td>
                                        <td class="px-4 py-3.5">
                                            <span class="rounded-full px-3 py-1 text-xs font-bold <?php echo e(match($lead->status) {
                                                'new' => 'bg-blue-500/10 text-blue-600 border border-blue-500/20',
                                                'contacted' => 'bg-amber-500/10 text-amber-600 border border-amber-500/20',
                                                'qualified' => 'bg-emerald-500/10 text-emerald-600 border border-emerald-500/20',
                                                'converted' => 'bg-purple-500/10 text-purple-600 border border-purple-500/20',
                                                default => 'bg-slate-100 text-slate-600',
                                            }); ?>">
                                                <?php echo e(ucfirst($lead->status)); ?>

                                            </span>
                                        </td>
                                        <td class="px-4 py-3.5 text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($lead->status !== 'converted'): ?>
                                                    <button type="button" wire:click="convertLeadToDeal(<?php echo e($lead->id); ?>)" class="rounded-lg bg-blue-500/10 px-2.5 py-1 text-xs font-bold text-blue-600 hover:bg-blue-500/20 transition">
                                                        <i class="fa-light fa-arrow-right mr-1"></i>Convert
                                                    </button>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                <button type="button" wire:click="archiveLead(<?php echo e($lead->id); ?>)" class="text-xs font-bold text-slate-400 hover:text-rose-500 transition">
                                                    <i class="fa-light fa-archive"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                    <tr>
                                        <td colspan="5" class="px-4 py-12 text-center">
                                            <i class="fa-light fa-user-plus text-4xl text-slate-300"></i>
                                            <p class="mt-3 text-sm font-medium text-slate-400"><?php echo e(__('No leads yet. Add your first lead to get started.')); ?></p>
                                            <button type="button" wire:click="openCreateModal('lead')" class="mt-4 rounded-xl bg-blue-600 px-5 py-2 text-xs font-bold text-white shadow-md hover:bg-blue-700"><?php echo e(__('Add First Lead')); ?></button>
                                        </td>
                                    </tr>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        <?php elseif($activeTab === 'deals'): ?>
            <div class="space-y-6">
                <!-- Pipeline Stage Visualization -->
                <div class="grid gap-4 sm:grid-cols-4">
                    <?php
                        $stages = [
                            'prospecting' => ['label' => 'Prospecting', 'icon' => 'fa-light fa-magnifying-glass', 'color' => 'slate'],
                            'proposal' => ['label' => 'Proposal', 'icon' => 'fa-light fa-file-signature', 'color' => 'blue'],
                            'negotiation' => ['label' => 'Negotiation', 'icon' => 'fa-light fa-handshake', 'color' => 'amber'],
                            'closed_won' => ['label' => 'Closed Won', 'icon' => 'fa-light fa-trophy', 'color' => 'emerald'],
                        ];
                    ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $stages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stageKey => $stageMeta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <?php $stageDeals = $dbDeals->where('stage', $stageKey); ?>
                        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                            <div class="flex items-center justify-between">
                                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-<?php echo e($stageMeta['color']); ?>-500/10 text-<?php echo e($stageMeta['color']); ?>-600">
                                    <i class="<?php echo e($stageMeta['icon']); ?> text-lg"></i>
                                </span>
                                <span class="text-xs font-bold text-slate-400 uppercase"><?php echo e($stageDeals->count()); ?> Deals</span>
                            </div>
                            <h3 class="mt-3 text-base font-bold text-slate-900 dark:text-white"><?php echo e($stageMeta['label']); ?></h3>
                            <p class="mt-1 text-lg font-black text-<?php echo e($stageMeta['color']); ?>-600">₦<?php echo e(number_format($stageDeals->sum('value'), 2)); ?></p>
                        </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>

                <!-- Deals Table -->
                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                        <div>
                            <h2 class="text-lg font-bold text-slate-950 dark:text-white"><?php echo e(__('Active Deals & Revenue Pipeline')); ?></h2>
                            <p class="text-sm text-slate-500"><?php echo e(__('Track deal stages, expected close dates, and projected revenue.')); ?></p>
                        </div>
                        <button type="button" wire:click="openCreateModal('deal')" class="rounded-xl bg-blue-600 px-4 py-2.5 text-xs font-bold text-white shadow-md hover:bg-blue-700 transition">
                            <i class="fa-light fa-plus mr-1.5"></i><?php echo e(__('New Deal')); ?>

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
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $dbDeals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $deal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                    <tr class="transition hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                                        <td class="px-4 py-3.5 font-bold text-slate-900 dark:text-white"><?php echo e($deal->deal_name); ?></td>
                                        <td class="px-4 py-3.5">
                                            <span class="rounded-full px-3 py-1 text-xs font-bold <?php echo e(match($deal->stage) {
                                                'prospecting' => 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300',
                                                'proposal' => 'bg-blue-500/10 text-blue-600 border border-blue-500/20',
                                                'negotiation' => 'bg-amber-500/10 text-amber-600 border border-amber-500/20',
                                                'closed_won' => 'bg-emerald-500/10 text-emerald-600 border border-emerald-500/20',
                                                default => 'bg-slate-100 text-slate-600',
                                            }); ?>">
                                                <?php echo e(ucfirst(str_replace('_', ' ', $deal->stage))); ?>

                                            </span>
                                        </td>
                                        <td class="px-4 py-3.5 font-black text-slate-900 dark:text-white">₦<?php echo e(number_format($deal->value, 2)); ?></td>
                                        <td class="px-4 py-3.5 text-xs text-slate-500"><?php echo e($deal->expected_close?->format('Y-m-d') ?: 'TBD'); ?></td>
                                        <td class="px-4 py-3.5">
                                            <?php $prob = match($deal->stage) { 'prospecting' => 20, 'proposal' => 50, 'negotiation' => 75, 'closed_won' => 100, default => 30 }; ?>
                                            <div class="flex items-center gap-2">
                                                <div class="h-1.5 w-16 overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800">
                                                    <div class="h-full rounded-full bg-blue-500 transition-all" style="width: <?php echo e($prob); ?>%;"></div>
                                                </div>
                                                <span class="text-xs font-bold text-slate-600 dark:text-slate-300"><?php echo e($prob); ?>%</span>
                                            </div>
                                        </td>
                                    </tr>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                    <tr>
                                        <td colspan="5" class="px-4 py-12 text-center">
                                            <i class="fa-light fa-chart-kanban text-4xl text-slate-300"></i>
                                            <p class="mt-3 text-sm font-medium text-slate-400"><?php echo e(__('No deals in pipeline yet.')); ?></p>
                                        </td>
                                    </tr>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        <?php elseif($activeTab === 'contacts'): ?>
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between border-b pb-4 dark:border-slate-800">
                    <div>
                        <h2 class="text-lg font-bold text-slate-950 dark:text-white"><?php echo e(__('Customer Contact Directory')); ?></h2>
                        <p class="text-sm text-slate-500"><?php echo e(__('Centralized client contact records, deal history, and communication log.')); ?></p>
                    </div>
                    <div class="relative">
                        <i class="fa-light fa-search absolute left-3 top-2.5 text-slate-400"></i>
                        <input type="text" wire:model.live.debounce.300ms="searchQuery" placeholder="Search contacts..." class="rounded-xl border border-slate-200 py-2 pl-9 pr-4 text-xs font-semibold outline-none focus:border-blue-500 dark:border-slate-700 dark:bg-slate-800 dark:text-white w-56">
                    </div>
                </div>

                <div class="mt-5 grid gap-4 md:grid-cols-2">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $crmContacts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contact): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <div class="rounded-2xl border border-slate-200 p-5 transition hover:border-blue-500/40 hover:shadow-md dark:border-slate-800 dark:hover:border-blue-500/40">
                            <div class="flex items-start justify-between">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-blue-500/10 text-sm font-black text-blue-600"><?php echo e(strtoupper(substr($contact['name'], 0, 2))); ?></span>
                                    <div>
                                        <h3 class="text-sm font-bold text-slate-900 dark:text-white"><?php echo e($contact['name']); ?></h3>
                                        <p class="text-xs text-slate-400"><?php echo e($contact['company']); ?></p>
                                    </div>
                                </div>
                                <span class="rounded-full bg-emerald-500/10 border border-emerald-500/20 px-2.5 py-0.5 text-[10px] font-bold text-emerald-600"><?php echo e($contact['deals']); ?> Deals</span>
                            </div>
                            <div class="mt-4 grid grid-cols-2 gap-3 text-xs">
                                <div>
                                    <p class="font-bold uppercase text-slate-400" style="font-size: 10px;">Email</p>
                                    <p class="font-semibold text-slate-600 dark:text-slate-300"><?php echo e($contact['email']); ?></p>
                                </div>
                                <div>
                                    <p class="font-bold uppercase text-slate-400" style="font-size: 10px;">Phone</p>
                                    <p class="font-semibold text-slate-600 dark:text-slate-300"><?php echo e($contact['phone']); ?></p>
                                </div>
                                <div>
                                    <p class="font-bold uppercase text-slate-400" style="font-size: 10px;">Total Value</p>
                                    <p class="font-black text-blue-600">₦<?php echo e(number_format($contact['value'], 2)); ?></p>
                                </div>
                                <div>
                                    <p class="font-bold uppercase text-slate-400" style="font-size: 10px;">Last Contact</p>
                                    <p class="font-semibold text-slate-600 dark:text-slate-300"><?php echo e($contact['last_contact']); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
            </section>
        <?php elseif($activeTab === 'contracts'): ?>
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                    <div>
                        <h2 class="text-lg font-bold text-slate-950 dark:text-white"><?php echo e(__('Client Contracts & Service Agreements')); ?></h2>
                        <p class="text-sm text-slate-500"><?php echo e(__('Track active contracts, renewal dates, and service agreement values.')); ?></p>
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
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $crmContracts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $contract): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <tr class="transition hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                                    <td class="px-4 py-3.5 font-mono font-bold text-slate-900 dark:text-white"><?php echo e($contract['id']); ?></td>
                                    <td class="px-4 py-3.5 font-bold"><?php echo e($contract['client']); ?></td>
                                    <td class="px-4 py-3.5 text-xs font-semibold text-slate-500"><?php echo e($contract['type']); ?></td>
                                    <td class="px-4 py-3.5 font-black text-slate-900 dark:text-white">₦<?php echo e(number_format($contract['value'], 2)); ?></td>
                                    <td class="px-4 py-3.5 text-xs text-slate-500"><?php echo e($contract['start']); ?> → <?php echo e($contract['end']); ?></td>
                                    <td class="px-4 py-3.5">
                                        <span class="rounded-full px-3 py-1 text-xs font-bold <?php echo e($contract['status'] === 'Active' ? 'bg-emerald-500/10 text-emerald-600 border border-emerald-500/20' : 'bg-amber-500/10 text-amber-600 border border-amber-500/20'); ?>">
                                            <?php echo e($contract['status']); ?>

                                        </span>
                                    </td>
                                    <td class="px-4 py-3.5 text-right">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($contract['status'] !== 'Active'): ?>
                                            <button type="button" wire:click="updateContractStatus(<?php echo e($index); ?>, 'Active')" class="text-xs font-bold text-emerald-600 hover:underline">Activate</button>
                                        <?php else: ?>
                                            <button type="button" wire:click="updateContractStatus(<?php echo e($index); ?>, 'Expired')" class="text-xs font-bold text-slate-400 hover:text-rose-500">Expire</button>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </td>
                                </tr>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        <?php else: ?>
            <section class="rounded-2xl border border-slate-200 bg-white p-8 text-center shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <i class="fa-light fa-sliders text-5xl text-blue-400"></i>
                <h3 class="mt-3 text-lg font-bold text-slate-950 dark:text-white"><?php echo e(__('CRM Form Builder & Settings')); ?></h3>
                <p class="mt-1 text-sm text-slate-500"><?php echo e(__('Customize lead capture fields, pipeline stages, and CRM workflow preferences.')); ?></p>
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
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- MODULE 5: INVENTORY & SUPPLY CHAIN HUB ENHANCED USERFLOW -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($moduleKey === 'inventory'): ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activeTab === 'products'): ?>
            <div class="space-y-6">
                <!-- Inventory KPI Summary Cards -->
                <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400"><?php echo e(__('Total Product SKUs')); ?></p>
                        <p class="mt-2 text-2xl font-black text-orange-600"><?php echo e($dbProducts->count()); ?> SKUs</p>
                        <p class="mt-1 text-xs font-medium text-slate-400"><i class="fa-light fa-boxes-stacked mr-1"></i>Active catalog items</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400"><?php echo e(__('Total Stock Valuation')); ?></p>
                        <p class="mt-2 text-2xl font-black text-slate-900 dark:text-white">₦<?php echo e(number_format($dbProducts->sum(fn($p) => $p->unit_price * $p->stock_quantity), 2)); ?></p>
                        <p class="mt-1 text-xs font-medium text-emerald-500"><i class="fa-light fa-chart-line-up mr-1"></i>Asset liquid valuation</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400"><?php echo e(__('Low Stock Alerts')); ?></p>
                        <p class="mt-2 text-2xl font-black text-rose-500"><?php echo e($dbProducts->filter(fn ($p) => $p->stock_quantity <= $p->reorder_level)->count()); ?> SKUs</p>
                        <p class="mt-1 text-xs font-medium text-rose-500"><i class="fa-light fa-triangle-exclamation mr-1"></i>Needs replenishment</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400"><?php echo e(__('Warehouse Utilization')); ?></p>
                        <p class="mt-2 text-2xl font-black text-blue-600">82.4%</p>
                        <p class="mt-1 text-xs font-medium text-slate-400"><i class="fa-light fa-warehouse mr-1"></i>3 Active Distribution Hubs</p>
                    </div>
                </div>

                <!-- Reorder Alerts Header Notification Banner -->
                <?php
                    $lowStockItems = $dbProducts->filter(fn ($p) => $p->stock_quantity <= $p->reorder_level);
                ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($lowStockItems->isNotEmpty()): ?>
                    <div class="flex items-center justify-between rounded-2xl border border-rose-500/30 bg-rose-500/10 p-5 text-sm font-bold text-rose-600 dark:text-rose-400 shadow-xs">
                        <div class="flex items-center gap-3">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-rose-500 text-white">
                                <i class="fa-light fa-triangle-exclamation text-lg"></i>
                            </span>
                            <div>
                                <p class="text-base font-extrabold"><?php echo e(__('Low Stock Reorder Alert!')); ?></p>
                                <p class="text-xs text-rose-600/80 dark:text-rose-300 font-medium">
                                    <?php echo e(__(':count product SKU(s) have dropped below their reorder threshold.', ['count' => $lowStockItems->count()])); ?>

                                </p>
                            </div>
                        </div>
                        <button type="button" wire:click="reorderStock('<?php echo e($lowStockItems->first()->sku); ?>')" class="rounded-xl bg-rose-600 px-4 py-2 text-xs font-bold text-white shadow-md hover:bg-rose-700 transition">
                            <i class="fa-light fa-cart-circle-plus mr-1.5"></i><?php echo e(__('Issue Reorder PO')); ?>

                        </button>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between border-b pb-4 dark:border-slate-800">
                        <div>
                            <h2 class="text-lg font-bold text-slate-950 dark:text-white"><?php echo e(__('Products & SKU Inventory Stock Control')); ?></h2>
                            <p class="text-sm text-slate-500"><?php echo e(__('Track product stock levels across warehouses, set reorder alerts, and add new SKUs.')); ?></p>
                        </div>
                        <button type="button" wire:click="openCreateModal('product')" class="inline-flex items-center gap-2 rounded-xl bg-orange-600 px-4 py-2.5 text-xs font-bold text-white shadow-md hover:bg-orange-700 transition">
                            <i class="fa-light fa-plus text-sm"></i><?php echo e(__('Add New Product SKU')); ?>

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
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $dbProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prod): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                    <tr class="transition hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                                        <td class="px-4 py-3.5 font-mono font-bold text-slate-900 dark:text-white"><?php echo e($prod->sku); ?></td>
                                        <td class="px-4 py-3.5 font-bold">
                                            <?php echo e($prod->name); ?>

                                            <span class="block text-xs font-normal text-slate-400"><?php echo e($prod->category); ?></span>
                                        </td>
                                        <td class="px-4 py-3.5 text-slate-500 text-xs font-semibold"><?php echo e($prod->location ?: 'Lagos HQ Central Warehouse'); ?></td>
                                        <td class="px-4 py-3.5 font-black text-slate-900 dark:text-white">₦<?php echo e(number_format($prod->unit_price, 2)); ?></td>
                                        <td class="px-4 py-3.5">
                                            <span class="font-bold text-slate-800 dark:text-slate-200"><?php echo e($prod->stock_quantity); ?> units</span>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($prod->stock_quantity <= $prod->reorder_level): ?>
                                                <span class="ml-2 inline-block rounded-full bg-rose-500/10 text-rose-600 border border-rose-500/20 px-2 py-0.5 text-[10px] font-bold">Low Stock Alert</span>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </td>
                                        <td class="px-4 py-3.5 flex items-center gap-1.5">
                                            <button type="button" wire:click="adjustStockQuantity(<?php echo e($prod->id); ?>, 10)" class="rounded-lg bg-emerald-50 px-2 py-1 text-xs font-bold text-emerald-600 hover:bg-emerald-100 dark:bg-emerald-950/40 dark:text-emerald-300">+10</button>
                                            <button type="button" wire:click="adjustStockQuantity(<?php echo e($prod->id); ?>, -1)" class="rounded-lg bg-rose-50 px-2 py-1 text-xs font-bold text-rose-600 hover:bg-rose-100 dark:bg-rose-950/40 dark:text-rose-300">-1</button>
                                        </td>
                                        <td class="px-4 py-3.5 text-right">
                                            <button type="button" wire:click="orderSupplierStock('Apex Hardware Supplies Ltd', '<?php echo e($prod->sku); ?>')" class="inline-flex items-center gap-1 text-xs font-bold text-orange-600 hover:underline">
                                                <i class="fa-light fa-cart-circle-plus"></i>Issue PO
                                            </button>
                                        </td>
                                    </tr>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        <?php elseif($activeTab === 'stock'): ?>
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                    <div>
                        <h2 class="text-lg font-bold text-slate-950 dark:text-white"><?php echo e(__('Stock Movement Audit Log & Branch Transfers')); ?></h2>
                        <p class="text-sm text-slate-500"><?php echo e(__('Real-time log of inbound PO receipts, warehouse transfers, and POS sales dispatches.')); ?></p>
                    </div>
                    <button type="button" wire:click="transferStock('POS-HDW-004', 'Lagos HQ Central Warehouse', 'Abuja Regional Distribution Hub', 10)" class="rounded-xl bg-orange-600 px-4 py-2 text-xs font-bold text-white hover:bg-orange-700">
                        <i class="fa-light fa-right-left mr-1.5"></i><?php echo e(__('Transfer Stock')); ?>

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
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $stockMovements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $move): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <tr class="transition hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                                    <td class="px-4 py-3.5 text-slate-500 text-xs"><?php echo e($move['date']); ?></td>
                                    <td class="px-4 py-3.5 font-mono font-bold text-slate-900 dark:text-white"><?php echo e($move['sku']); ?></td>
                                    <td class="px-4 py-3.5 font-bold"><?php echo e($move['product']); ?></td>
                                    <td class="px-4 py-3.5">
                                        <span class="rounded-full px-2.5 py-0.5 text-xs font-bold <?php echo e($move['qty'] > 0 ? 'bg-emerald-500/10 text-emerald-600 border border-emerald-500/20' : 'bg-rose-500/10 text-rose-600 border border-rose-500/20'); ?>">
                                            <?php echo e($move['type']); ?>

                                        </span>
                                    </td>
                                    <td class="px-4 py-3.5 font-black <?php echo e($move['qty'] > 0 ? 'text-emerald-600' : 'text-rose-600'); ?>">
                                        <?php echo e($move['qty'] > 0 ? '+'.$move['qty'] : $move['qty']); ?> units
                                    </td>
                                    <td class="px-4 py-3.5 text-slate-500 text-xs font-semibold"><?php echo e($move['origin']); ?> &rarr; <?php echo e($move['destination']); ?></td>
                                </tr>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        <?php elseif($activeTab === 'warehouses'): ?>
            <div class="space-y-6">
                <!-- Warehouse Availability Section -->
                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                        <div>
                            <h2 class="text-lg font-bold text-slate-950 dark:text-white"><?php echo e(__('Warehouse Availability & Regional Capacity')); ?></h2>
                            <p class="text-sm text-slate-500"><?php echo e(__('Monitor storage utilization %, manager contacts, and inter-branch transfers.')); ?></p>
                        </div>
                        <button type="button" wire:click="transferStock('POS-HDW-004', 'Lagos HQ Central Warehouse', 'Abuja Regional Distribution Hub', 15)" class="rounded-xl bg-orange-600 px-4 py-2 text-xs font-bold text-white hover:bg-orange-700">
                            <i class="fa-light fa-arrows-left-right mr-1.5"></i><?php echo e(__('Initiate Warehouse Transfer')); ?>

                        </button>
                    </div>

                    <div class="mt-5 grid gap-4 md:grid-cols-3">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $warehouses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $wh): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <div class="rounded-2xl border border-slate-200 p-5 dark:border-slate-800 shadow-2xs">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold uppercase text-orange-600"><?php echo e($wh['skus']); ?> Active SKUs</span>
                                    <span class="rounded-full bg-emerald-500/10 text-emerald-600 border border-emerald-500/20 px-2.5 py-0.5 text-[10px] font-bold"><?php echo e($wh['status']); ?></span>
                                </div>
                                <h3 class="mt-3 text-base font-bold text-slate-900 dark:text-white"><?php echo e($wh['name']); ?></h3>
                                <p class="mt-1 text-xs text-slate-500"><i class="fa-light fa-location-dot mr-1"></i><?php echo e($wh['location']); ?></p>
                                <p class="mt-1 text-xs text-slate-400"><i class="fa-light fa-user-gear mr-1"></i>Manager: <?php echo e($wh['manager']); ?> (<?php echo e($wh['contact']); ?>)</p>

                                <div class="mt-4 pt-3 border-t dark:border-slate-800">
                                    <div class="flex justify-between text-xs font-bold mb-1">
                                        <span class="text-slate-500">Storage Capacity</span>
                                        <span class="text-orange-600"><?php echo e($wh['capacity']); ?>%</span>
                                    </div>
                                    <div class="h-2 w-full overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800">
                                        <div class="h-full bg-orange-500" style="width: <?php echo e($wh['capacity']); ?>%;"></div>
                                    </div>
                                </div>
                            </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>
                </section>

                <!-- Supplier Management Directory -->
                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                        <div>
                            <h2 class="text-lg font-bold text-slate-950 dark:text-white"><?php echo e(__('Suppliers Directory & Lead Times')); ?></h2>
                            <p class="text-sm text-slate-500"><?php echo e(__('Approved hardware manufacturers, lead time days, and bulk PO ordering.')); ?></p>
                        </div>
                        <button type="button" wire:click="orderSupplierStock('Apex Hardware Supplies Ltd', 'POS-HDW-004')" class="rounded-xl border border-orange-200 bg-orange-50 px-4 py-2 text-xs font-bold text-orange-700 hover:bg-orange-100 dark:bg-orange-950/40 dark:text-orange-300 dark:border-orange-800">
                            <i class="fa-light fa-cart-shopping mr-1.5"></i><?php echo e(__('Order Bulk Supplier Stock')); ?>

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
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sup): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                    <tr class="transition hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                                        <td class="px-4 py-3.5 font-bold text-slate-900 dark:text-white"><?php echo e($sup['name']); ?></td>
                                        <td class="px-4 py-3.5 text-slate-500 text-xs font-semibold"><?php echo e($sup['category']); ?></td>
                                        <td class="px-4 py-3.5 text-slate-500 text-xs"><?php echo e($sup['contact']); ?> (<?php echo e($sup['email']); ?>)</td>
                                        <td class="px-4 py-3.5 font-bold text-emerald-600 text-xs"><?php echo e($sup['lead_time']); ?></td>
                                        <td class="px-4 py-3.5 font-bold text-amber-500 text-xs"><i class="fa-solid fa-star mr-1"></i><?php echo e($sup['rating']); ?></td>
                                        <td class="px-4 py-3.5 text-right">
                                            <button type="button" wire:click="orderSupplierStock('<?php echo e($sup['name']); ?>', 'POS-HDW-004')" class="text-xs font-bold text-orange-600 hover:underline">
                                                Issue Bulk PO
                                            </button>
                                        </td>
                                    </tr>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        <?php else: ?>
            <section class="rounded-2xl border border-slate-200 bg-white p-8 text-center shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <i class="fa-light fa-file-export text-5xl text-orange-500"></i>
                <h3 class="mt-3 text-lg font-bold text-slate-950 dark:text-white"><?php echo e(__('Bulk Product CSV Import / Export')); ?></h3>
                <p class="mt-1 text-sm text-slate-500"><?php echo e(__('Upload CSV file to update inventory stock quantities or download CSV backup.')); ?></p>
                <div class="mt-6 flex justify-center gap-4">
                    <a href="<?php echo e(route('portal.finance.export-csv')); ?>" class="rounded-2xl bg-orange-600 px-6 py-3 text-sm font-bold text-white shadow-md hover:bg-orange-700"><i class="fa-light fa-download mr-2"></i>Download Inventory CSV</a>
                </div>
            </section>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- MODULE 3: SALES ORDERS & REVENUE ENHANCED -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($moduleKey === 'sales'): ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activeTab === 'orders'): ?>
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                    <div>
                        <h2 class="text-lg font-bold text-slate-950 dark:text-white"><?php echo e(__('Confirmed Sales Orders & Fulfilment')); ?></h2>
                        <p class="text-sm text-slate-500"><?php echo e(__('Track confirmed customer sales orders, fulfilment status, and status updates.')); ?></p>
                    </div>
                    <button type="button" wire:click="openCreateModal('sales_order')" class="rounded-xl bg-blue-600 px-4 py-2 text-xs font-bold text-white hover:bg-blue-700">
                        <i class="fa-light fa-plus mr-2"></i><?php echo e(__('Create Sales Order')); ?>

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
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $salesOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $so): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <tr class="transition hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                                    <td class="px-4 py-3.5 font-mono font-bold text-slate-900 dark:text-white"><?php echo e($so['id']); ?></td>
                                    <td class="px-4 py-3.5 font-bold"><?php echo e($so['customer']); ?></td>
                                    <td class="px-4 py-3.5 text-slate-500"><?php echo e($so['date']); ?></td>
                                    <td class="px-4 py-3.5 font-black text-slate-900 dark:text-white">₦<?php echo e(number_format($so['amount'], 2)); ?></td>
                                    <td class="px-4 py-3.5">
                                        <span class="rounded-full px-3 py-1 text-xs font-bold <?php echo e($so['status'] === 'Confirmed' ? 'bg-emerald-500/10 text-emerald-600 border border-emerald-500/20' : ($so['status'] === 'Fulfilled' ? 'bg-blue-500/10 text-blue-600 border border-blue-500/20' : 'bg-slate-100 text-slate-600')); ?>">
                                            <?php echo e($so['status']); ?>

                                        </span>
                                    </td>
                                    <td class="px-4 py-3.5 text-right space-x-2">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($so['status'] !== 'Fulfilled'): ?>
                                            <button type="button" wire:click="updateOrderStatus(<?php echo e($index); ?>, 'Fulfilled')" class="text-xs font-bold text-emerald-600 hover:underline">
                                                Mark Fulfilled
                                            </button>
                                        <?php else: ?>
                                            <span class="text-xs font-bold text-slate-400">Complete</span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </td>
                                </tr>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        <?php else: ?>
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h2 class="text-lg font-bold text-slate-950 dark:text-white border-b pb-4 dark:border-slate-800"><?php echo e(__(ucfirst($activeTab).' Sales View')); ?></h2>
                <p class="mt-4 text-sm text-slate-500"><?php echo e(__('Pipeline forecasting and deal stage metrics active.')); ?></p>
            </section>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- MODULE 6: POINT OF SALE (POS) ENHANCED USERFLOW -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($moduleKey === 'pos'): ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activeTab === 'checkout'): ?>
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
                            <i class="fa-light fa-plus mr-1.5"></i><?php echo e(__('Scan Barcode')); ?>

                        </button>
                    </form>
                </section>

                <div class="grid gap-6 xl:grid-cols-[1.2fr_0.8fr]">
                    <!-- POS Terminal Checkout Products Grid -->
                    <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                            <div>
                                <h2 class="text-lg font-bold text-slate-950 dark:text-white"><?php echo e(__('POS Retail Checkout Terminal')); ?></h2>
                                <p class="text-sm text-slate-500"><?php echo e(__('Click items below to add to customer cart with instant stock decrement.')); ?></p>
                            </div>
                            <span class="rounded-full bg-emerald-500/10 border border-emerald-500/20 px-3 py-1 text-xs font-bold text-emerald-600">Terminal #01 Active</span>
                        </div>

                        <div class="mt-5 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = [
                                ['sku' => 'POS-HDW-004', 'name' => 'Barcode Scanner Unit', 'price' => 85000.00],
                                ['sku' => 'ENT-LIC-001', 'name' => 'Enterprise License', 'price' => 250000.00],
                                ['sku' => 'REC-PRN-002', 'name' => 'Thermal Receipt Printer', 'price' => 45000.00],
                                ['sku' => 'CSH-DRW-009', 'name' => 'Heavy Duty Cash Drawer', 'price' => 38000.00],
                                ['sku' => 'POS-DIS-012', 'name' => 'Customer Touch Display', 'price' => 120000.00],
                                ['sku' => 'SUP-PAP-100', 'name' => 'Receipt Roll Pack (10x)', 'price' => 12000.00],
                            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <button type="button" wire:click="addToPosCart('<?php echo e($product['sku']); ?>', '<?php echo e($product['name']); ?>', <?php echo e($product['price']); ?>)" class="group rounded-2xl border border-slate-200 p-4 text-left shadow-2xs transition-all duration-200 hover:scale-[1.02] hover:border-orange-500 hover:shadow-md dark:border-slate-800 dark:hover:border-orange-500">
                                    <p class="text-xs font-mono font-bold text-slate-400"><?php echo e($product['sku']); ?></p>
                                    <p class="mt-2 text-sm font-bold text-slate-900 group-hover:text-orange-600 dark:text-white"><?php echo e($product['name']); ?></p>
                                    <p class="mt-2 text-base font-black text-orange-600">₦<?php echo e(number_format($product['price'], 2)); ?></p>
                                </button>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </div>
                    </section>

                    <!-- Active Cart Drawer with Customer Info, Discounts & Payment Method Options -->
                    <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                            <h2 class="text-lg font-bold text-slate-950 dark:text-white"><?php echo e(__('Cart & Checkout Options')); ?></h2>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($posCart)): ?>
                                <button type="button" wire:click="clearPosCart" class="text-xs font-bold text-rose-500 hover:underline"><?php echo e(__('Clear Cart')); ?></button>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(empty($posCart)): ?>
                            <div class="my-12 text-center text-slate-400">
                                <i class="fa-light fa-basket-shopping text-5xl"></i>
                                <p class="mt-3 text-sm font-medium"><?php echo e(__('Cart is empty. Click products on left to add.')); ?></p>
                            </div>
                        <?php else: ?>
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
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $posCart; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $cartItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                    <div class="flex items-center justify-between py-3">
                                        <div>
                                            <p class="text-sm font-bold text-slate-900 dark:text-white"><?php echo e($cartItem['name']); ?></p>
                                            <p class="text-xs text-slate-400">₦<?php echo e(number_format($cartItem['price'], 2)); ?> x <?php echo e($cartItem['quantity']); ?></p>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <button type="button" wire:click="updatePosCartQuantity(<?php echo e($index); ?>, <?php echo e($cartItem['quantity'] - 1); ?>)" class="h-7 w-7 rounded-lg bg-slate-100 font-black text-slate-600 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300">-</button>
                                            <span class="text-sm font-black"><?php echo e($cartItem['quantity']); ?></span>
                                            <button type="button" wire:click="updatePosCartQuantity(<?php echo e($index); ?>, <?php echo e($cartItem['quantity'] + 1); ?>)" class="h-7 w-7 rounded-lg bg-slate-100 font-black text-slate-600 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300">+</button>
                                        </div>
                                    </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </div>

                            <!-- Loyalty Discount Selector -->
                            <div class="mt-4 border-t pt-3 dark:border-slate-800">
                                <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Apply Loyalty Discount</label>
                                <div class="flex items-center gap-2">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = [0, 5, 10, 15]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $disc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                        <button type="button" wire:click="setPosDiscount(<?php echo e($disc); ?>)" class="rounded-xl px-3 py-1 text-xs font-bold border transition <?php echo e($posDiscountPercent === (float)$disc ? 'bg-orange-600 text-white border-orange-600' : 'border-slate-200 text-slate-600 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300'); ?>">
                                            <?php echo e($disc === 0 ? 'None' : $disc.'%'); ?>

                                        </button>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
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

                            <?php
                                $sub = array_reduce($posCart, fn ($acc, $item) => $acc + ($item['price'] * $item['quantity']), 0.0);
                                $discAmt = $sub * ($posDiscountPercent / 100.0);
                                $taxable = $sub - $discAmt;
                                $tax = $taxable * $posTaxRate;
                                $tot = $taxable + $tax;
                            ?>

                            <div class="mt-4 space-y-1.5 border-t pt-3 text-sm dark:border-slate-800">
                                <div class="flex justify-between text-slate-500"><span>Subtotal</span><span>₦<?php echo e(number_format($sub, 2)); ?></span></div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($posDiscountPercent > 0): ?>
                                    <div class="flex justify-between text-emerald-600 font-bold"><span>Discount (<?php echo e($posDiscountPercent); ?>%)</span><span>-₦<?php echo e(number_format($discAmt, 2)); ?></span></div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <div class="flex justify-between text-slate-500"><span>VAT (7.5%)</span><span>₦<?php echo e(number_format($tax, 2)); ?></span></div>
                                <div class="flex justify-between font-black text-base text-slate-900 dark:text-white pt-2 border-t dark:border-slate-800">
                                    <span>Total Payable</span>
                                    <span class="text-orange-600">₦<?php echo e(number_format($tot, 2)); ?></span>
                                </div>
                            </div>

                            <button type="button" wire:click="checkoutPos" class="mt-5 w-full rounded-2xl bg-orange-600 py-3.5 text-center text-sm font-black text-white shadow-lg shadow-orange-500/20 hover:bg-orange-700 transition">
                                <i class="fa-light fa-print mr-2"></i><?php echo e(__('Complete Sale & Print Receipt')); ?>

                            </button>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </section>
                </div>
            </div>
        <?php elseif($activeTab === 'receipts'): ?>
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                    <div>
                        <h2 class="text-lg font-bold text-slate-950 dark:text-white"><?php echo e(__('Historical Sales Receipts')); ?></h2>
                        <p class="text-sm text-slate-500"><?php echo e(__('View past cashier POS transaction receipts and dispatch electronic e-receipts.')); ?></p>
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
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $dbPosReceipts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <tr class="transition hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                                    <td class="px-4 py-3.5 font-mono font-bold text-slate-900 dark:text-white"><?php echo e($rec->receipt_number); ?></td>
                                    <td class="px-4 py-3.5 font-bold"><?php echo e($rec->cashier_name ?: 'Ascend Cashier'); ?></td>
                                    <td class="px-4 py-3.5">
                                        <span class="rounded-full px-2.5 py-0.5 text-xs font-bold bg-orange-500/10 text-orange-600 border border-orange-500/20">
                                            <?php echo e(ucfirst(str_replace('_', ' ', $rec->payment_method ?: 'card'))); ?>

                                        </span>
                                    </td>
                                    <td class="px-4 py-3.5 font-black text-slate-900 dark:text-white">₦<?php echo e(number_format($rec->total, 2)); ?></td>
                                    <td class="px-4 py-3.5 text-slate-500 text-xs"><?php echo e($rec->created_at?->format('Y-m-d H:i') ?: now()->format('Y-m-d H:i')); ?></td>
                                    <td class="px-4 py-3.5 text-right space-x-3">
                                        <button type="button" wire:click="sendDigitalReceipt('<?php echo e($rec->receipt_number); ?>', 'customer@ascendsystems.ng')" class="text-xs font-bold text-blue-600 hover:underline">
                                            <i class="fa-light fa-paper-plane mr-1"></i>e-Receipt
                                        </button>
                                        <button type="button" wire:click="reprintPosReceipt('<?php echo e($rec->receipt_number); ?>')" class="text-xs font-bold text-orange-600 hover:underline">
                                            <i class="fa-light fa-print mr-1"></i>Reprint Receipt
                                        </button>
                                    </td>
                                </tr>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        <?php elseif($activeTab === 'barcodes'): ?>
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                    <div>
                        <h2 class="text-lg font-bold text-slate-950 dark:text-white"><?php echo e(__('Barcode & Thermal Label Printing Studio')); ?></h2>
                        <p class="text-sm text-slate-500"><?php echo e(__('Generate Code128 barcodes and print price labels for POS hardware & inventory items.')); ?></p>
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

                            <p class="text-xs font-mono font-extrabold text-slate-600">*<?php echo e($selectedBarcodeSku); ?>*</p>
                            <p class="mt-2 text-lg font-black text-orange-600">PRICE: ₦85,000.00</p>
                        </div>

                        <div class="flex justify-center gap-3">
                            <button type="button" wire:click="printBarcodeLabel('<?php echo e($selectedBarcodeSku); ?>', 1)" class="rounded-xl bg-orange-600 px-5 py-2.5 text-xs font-bold text-white shadow-md hover:bg-orange-700">
                                <i class="fa-light fa-print mr-1.5"></i>Print Thermal Label
                            </button>
                            <button type="button" wire:click="printBarcodeLabel('<?php echo e($selectedBarcodeSku); ?>', 50)" class="rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 dark:bg-slate-800 dark:border-slate-700 dark:text-white">
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
        <?php else: ?>
            <!-- POS Insights & Shift Logs -->
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                    <div>
                        <h2 class="text-lg font-bold text-slate-950 dark:text-white"><?php echo e(__('POS Insights & Cash Register Shift Logs')); ?></h2>
                        <p class="text-sm text-slate-500"><?php echo e(__('Reconcile daily cash float, card POS collections, and close register shifts.')); ?></p>
                    </div>
                    <button type="button" wire:click="closeShiftRegister" class="rounded-xl bg-orange-600 px-4 py-2 text-xs font-bold text-white hover:bg-orange-700">
                        <i class="fa-light fa-lock mr-1.5"></i><?php echo e(__('End Shift & Reconcile Register')); ?>

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
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- MODULE 7: MARKETING & SOCIAL CAMPAIGN HUB ENHANCED USERFLOW -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($moduleKey === 'marketing'): ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activeTab === 'campaigns'): ?>
            <div class="space-y-6">
                <!-- ROAS & Performance Overview Cards -->
                <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400"><?php echo e(__('Total Ad Spend')); ?></p>
                        <p class="mt-2 text-2xl font-black text-purple-600">₦3,700,000.00</p>
                        <p class="mt-1 text-xs font-medium text-purple-500"><i class="fa-light fa-bullhorn mr-1"></i>3 Active Campaigns</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400"><?php echo e(__('Leads Generated')); ?></p>
                        <p class="mt-2 text-2xl font-black text-blue-600">440 Leads</p>
                        <p class="mt-1 text-xs font-medium text-emerald-500"><i class="fa-light fa-arrow-trend-up mr-1"></i>+24.5% conversion rate</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400"><?php echo e(__('Average CAC')); ?></p>
                        <p class="mt-2 text-2xl font-black text-slate-900 dark:text-white">₦8,409.00</p>
                        <p class="mt-1 text-xs font-medium text-slate-400">Cost per acquired lead</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400"><?php echo e(__('ROAS Performance')); ?></p>
                        <p class="mt-2 text-2xl font-black text-emerald-600">4.8x ROAS</p>
                        <p class="mt-1 text-xs font-medium text-emerald-500">₦17,760,000 Revenue</p>
                    </div>
                </div>

                <!-- Campaigns Table -->
                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                        <div>
                            <h2 class="text-lg font-bold text-slate-950 dark:text-white"><?php echo e(__('Multi-Channel Marketing Campaigns')); ?></h2>
                            <p class="text-sm text-slate-500"><?php echo e(__('Track paid ad budgets, lead acquisition, and ROAS across social & search channels.')); ?></p>
                        </div>
                        <button type="button" wire:click="openCreateModal('marketing')" class="rounded-xl bg-purple-600 px-4 py-2.5 text-xs font-bold text-white shadow-md hover:bg-purple-700 transition">
                            <i class="fa-light fa-plus mr-1.5"></i><?php echo e(__('New Campaign')); ?>

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
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $marketingCampaigns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $camp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                    <tr class="transition hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                                        <td class="px-4 py-3.5 font-bold text-slate-900 dark:text-white"><?php echo e($camp['name']); ?></td>
                                        <td class="px-4 py-3.5 text-xs font-semibold text-slate-500"><?php echo e($camp['channel']); ?></td>
                                        <td class="px-4 py-3.5 font-black text-slate-900 dark:text-white">₦<?php echo e(number_format($camp['budget'], 2)); ?></td>
                                        <td class="px-4 py-3.5 font-bold text-blue-600"><?php echo e($camp['leads']); ?> Leads</td>
                                        <td class="px-4 py-3.5">
                                            <span class="rounded-full px-3 py-1 text-xs font-bold <?php echo e($camp['status'] === 'Active' ? 'bg-emerald-500/10 text-emerald-600 border border-emerald-500/20' : 'bg-amber-500/10 text-amber-600 border border-amber-500/20'); ?>">
                                                <?php echo e($camp['status']); ?>

                                            </span>
                                        </td>
                                        <td class="px-4 py-3.5 text-right space-x-2">
                                            <button type="button" wire:click="toggleCampaignStatus(<?php echo e($index); ?>)" class="text-xs font-bold text-purple-600 hover:underline">
                                                <?php echo e($camp['status'] === 'Active' ? 'Pause' : 'Activate'); ?>

                                            </button>
                                            <button type="button" wire:click="adjustCampaignBudget(<?php echo e($index); ?>, 500000.00)" class="text-xs font-bold text-emerald-600 hover:underline">
                                                +₦500k
                                            </button>
                                            <button type="button" wire:click="duplicateCampaign(<?php echo e($index); ?>)" class="text-xs font-bold text-slate-500 hover:underline">
                                                Clone
                                            </button>
                                        </td>
                                    </tr>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        <?php elseif($activeTab === 'social'): ?>
            <div class="space-y-6">
                <!-- Connected Channels Grid -->
                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                        <div>
                            <h2 class="text-lg font-bold text-slate-950 dark:text-white"><?php echo e(__('Social Media Channel Management')); ?></h2>
                            <p class="text-sm text-slate-500"><?php echo e(__('Connected social profiles, audience reach, and channel API integration.')); ?></p>
                        </div>
                    </div>

                    <div class="mt-5 grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $socialChannels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <div class="rounded-2xl border border-slate-200 p-5 dark:border-slate-800 shadow-2xs">
                                <div class="flex items-center justify-between">
                                    <i class="<?php echo e($chan['icon']); ?> text-2xl"></i>
                                    <span class="rounded-full bg-emerald-500/10 text-emerald-600 border border-emerald-500/20 px-2.5 py-0.5 text-[10px] font-bold"><?php echo e($chan['status']); ?></span>
                                </div>
                                <h3 class="mt-3 text-base font-bold text-slate-900 dark:text-white"><?php echo e($chan['name']); ?></h3>
                                <p class="mt-0.5 text-xs font-semibold text-slate-400"><?php echo e($chan['handle']); ?></p>
                                <p class="mt-3 text-xl font-black text-slate-900 dark:text-white"><?php echo e($chan['followers']); ?> <span class="text-xs font-normal text-slate-400">Followers</span></p>
                                <button type="button" wire:click="syncChannelStats('<?php echo e($chan['platform']); ?>')" class="mt-4 w-full rounded-xl border border-slate-200 py-2 text-center text-xs font-bold text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200">
                                    <i class="fa-light fa-arrows-rotate mr-1"></i>Sync Analytics
                                </button>
                            </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
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
        <?php elseif($activeTab === 'blasts'): ?>
            <div class="space-y-6">
                <!-- Create & Dispatch Audience Blast -->
                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                        <div>
                            <h2 class="text-lg font-bold text-slate-950 dark:text-white"><?php echo e(__('Create & Dispatch Audience Broadcast')); ?></h2>
                            <p class="text-sm text-slate-500"><?php echo e(__('Dispatch targeted email & SMS marketing blasts to subscribers & CRM leads.')); ?></p>
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
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $audienceBlasts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                    <tr class="transition hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                                        <td class="px-4 py-3.5 font-bold text-slate-900 dark:text-white"><?php echo e($b['subject']); ?></td>
                                        <td class="px-4 py-3.5 text-xs text-slate-500"><?php echo e($b['segment']); ?></td>
                                        <td class="px-4 py-3.5 font-bold text-emerald-600 text-xs"><?php echo e($b['delivered']); ?></td>
                                        <td class="px-4 py-3.5 font-bold text-blue-600 text-xs"><?php echo e($b['opened']); ?></td>
                                        <td class="px-4 py-3.5 text-slate-400 text-xs"><?php echo e($b['date']); ?></td>
                                        <td class="px-4 py-3.5 text-right">
                                            <button type="button" wire:click="resendUnopenedBlast(<?php echo e($index); ?>)" class="text-xs font-bold text-purple-600 hover:underline">
                                                Resend Unopened
                                            </button>
                                        </td>
                                    </tr>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        <?php elseif($activeTab === 'email'): ?>
            <div class="space-y-6">
                <!-- Email Campaign & Newsletter Builder -->
                <div class="grid gap-6 xl:grid-cols-[1.2fr_0.8fr]">
                    <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                            <div>
                                <h2 class="text-lg font-bold text-slate-950 dark:text-white"><?php echo e(__('Email Campaign & Newsletter Builder')); ?></h2>
                                <p class="text-sm text-slate-500"><?php echo e(__('Design newsletters, nurture series, and measure delivery performance.')); ?></p>
                            </div>
                            <button type="button" wire:click="openCreateModal('email_campaign')" class="rounded-xl bg-purple-600 px-4 py-2 text-xs font-bold text-white hover:bg-purple-700">
                                <i class="fa-light fa-plus mr-1.5"></i><?php echo e(__('New Template')); ?>

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
                                <p class="font-extrabold text-sm text-purple-600"><?php echo e($emailForm['subject'] ?: 'Subject: Your Email Subject Line Will Appear Here'); ?></p>
                                <p class="text-[11px] text-slate-400 mt-1"><?php echo e($emailForm['preheader'] ?: 'Preview text snippet visible in email inbox app...'); ?></p>
                            </div>
                            <div class="my-4 text-xs space-y-2 leading-relaxed text-slate-600 dark:text-slate-300">
                                <p><?php echo e($emailForm['body'] ?: 'Your email body text content will be rendered here with live real-time formatting preview.'); ?></p>
                            </div>
                            <div class="my-4 text-center">
                                <a href="#" onclick="return false;" class="inline-block rounded-xl bg-purple-600 px-5 py-2 text-xs font-bold text-white shadow-sm"><?php echo e($emailForm['cta_text'] ?: 'Explore Features'); ?></a>
                            </div>
                            <div class="border-t pt-3 text-[10px] text-center text-slate-400 dark:border-slate-800">
                                <p><?php echo e($emailForm['footer']); ?></p>
                                <p class="mt-0.5">Unsubscribe · Manage Email Preferences</p>
                            </div>
                        </div>
                    </section>
                </div>

                <!-- Template Gallery Cards -->
                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <h3 class="text-base font-bold text-slate-950 dark:text-white border-b pb-3 dark:border-slate-800"><?php echo e(__('Email Template Library & Automated Sequences')); ?></h3>
                    <div class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $emailTemplates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $tmpl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <div class="rounded-2xl border border-slate-200 p-4 transition hover:border-purple-500/40 dark:border-slate-800 dark:hover:border-purple-500/40">
                                <div class="flex items-center justify-between">
                                    <span class="rounded-full bg-purple-500/10 px-2 py-0.5 text-[9px] font-bold text-purple-600"><?php echo e($tmpl['category']); ?></span>
                                    <span class="rounded-full bg-emerald-500/10 px-2 py-0.5 text-[9px] font-bold text-emerald-600"><?php echo e($tmpl['status']); ?></span>
                                </div>
                                <h4 class="mt-3 text-xs font-bold text-slate-900 dark:text-white"><?php echo e($tmpl['name']); ?></h4>
                                <div class="mt-3 grid grid-cols-2 gap-2 text-[10px]">
                                    <div><span class="text-slate-400">Open Rate:</span> <span class="font-bold text-emerald-600"><?php echo e($tmpl['opens']); ?></span></div>
                                    <div><span class="text-slate-400">Click Rate:</span> <span class="font-bold text-purple-600"><?php echo e($tmpl['clicks']); ?></span></div>
                                </div>
                                <div class="mt-3 pt-2 border-t flex justify-end gap-2 dark:border-slate-800">
                                    <button type="button" wire:click="duplicateEmailTemplate(<?php echo e($idx); ?>)" class="text-[10px] font-bold text-purple-600 hover:underline"><i class="fa-light fa-copy mr-1"></i>Duplicate</button>
                                </div>
                            </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>
                </section>
            </div>
        <?php else: ?>
            <!-- Campaign Analytics & ROAS Dashboard -->
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                    <div>
                        <h2 class="text-lg font-bold text-slate-950 dark:text-white"><?php echo e(__('Campaign Analytics & ROAS Attribution')); ?></h2>
                        <p class="text-sm text-slate-500"><?php echo e(__('Return on Ad Spend (ROAS) and channel conversion attribution metrics.')); ?></p>
                    </div>
                    <a href="<?php echo e(route('portal.finance.export-csv')); ?>" class="rounded-xl bg-purple-600 px-4 py-2 text-xs font-bold text-white hover:bg-purple-700">
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
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- MODULE 8: AI AGENTS ENHANCED -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($moduleKey === 'ai-agents'): ?>
        <div class="grid gap-6 xl:grid-cols-[1.1fr_0.9fr]">
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                    <div>
                        <h2 class="text-lg font-bold text-slate-950 dark:text-white"><?php echo e(__('AI Content & Caption Studio')); ?></h2>
                        <p class="text-sm text-slate-500"><?php echo e(__('Generate high-converting social posts, captions, and marketing copy.')); ?></p>
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
                        <i class="fa-light fa-sparkles mr-2"></i><?php echo e(__('Generate AI Content')); ?>

                    </button>
                </form>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h2 class="text-lg font-bold text-slate-950 dark:text-white border-b pb-4 dark:border-slate-800"><?php echo e(__('AI Output & Publishing')); ?></h2>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($generatedResult || $repurposedResult): ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($generatedResult): ?>
                        <div class="mt-4 rounded-2xl border border-purple-200 bg-purple-50/50 p-5 dark:border-purple-900/60 dark:bg-purple-950/30">
                            <p class="text-xs font-bold uppercase tracking-wider text-purple-600">Generated Post (<?php echo e(ucfirst($aiTone)); ?> Tone)</p>
                            <p class="mt-2 text-sm font-medium text-slate-800 dark:text-purple-100"><?php echo e($generatedResult); ?></p>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <div class="mt-4 flex flex-col gap-2">
                        <button type="button" wire:click="sendGeneratedToPublishing" class="w-full rounded-2xl bg-blue-600 py-3 text-sm font-bold text-white shadow-md hover:bg-blue-700">
                            <i class="fa-light fa-calendar-plus mr-2"></i><?php echo e(__('Schedule in Social Calendar')); ?>

                        </button>
                    </div>
                <?php else: ?>
                    <div class="my-12 text-center text-slate-400">
                        <i class="fa-light fa-sparkles text-5xl text-purple-300"></i>
                        <p class="mt-3 text-sm font-medium"><?php echo e(__('Enter a prompt on the left to generate AI content.')); ?></p>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </section>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- MODULE 9: WORKFLOW AUTOMATION ENHANCED -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($moduleKey === 'automation'): ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activeTab === 'rules'): ?>
            <div class="space-y-6">
                <!-- Automation Overview Cards -->
                <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400"><?php echo e(__('Active Rules')); ?></p>
                        <p class="mt-2 text-2xl font-black text-amber-600"><?php echo e(count($automationRules)); ?> Rules Enabled</p>
                        <p class="mt-1 text-xs font-medium text-amber-500"><i class="fa-light fa-bolt mr-1"></i>Cross-Module Active</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400"><?php echo e(__('Executions (24h)')); ?></p>
                        <p class="mt-2 text-2xl font-black text-blue-600">1,840 Tasks</p>
                        <p class="mt-1 text-xs font-medium text-emerald-500"><i class="fa-light fa-check-double mr-1"></i>100% Delivery</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400"><?php echo e(__('Execution Success')); ?></p>
                        <p class="mt-2 text-2xl font-black text-emerald-600">99.8% Success</p>
                        <p class="mt-1 text-xs font-medium text-slate-400">0 Failed Retries</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400"><?php echo e(__('Average Latency')); ?></p>
                        <p class="mt-2 text-2xl font-black text-slate-900 dark:text-white">42ms</p>
                        <p class="mt-1 text-xs font-medium text-emerald-500"><i class="fa-light fa-gauge-high mr-1"></i>Ultra Fast</p>
                    </div>
                </div>

                <!-- Rules List -->
                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                        <div>
                            <h2 class="text-lg font-bold text-slate-950 dark:text-white"><?php echo e(__('Workflow Automation Rules & Webhooks')); ?></h2>
                            <p class="text-sm text-slate-500"><?php echo e(__('Automate background tasks across CRM, Finance, POS, and Notifications.')); ?></p>
                        </div>
                        <button type="button" wire:click="openCreateModal('automation')" class="rounded-xl bg-amber-600 px-4 py-2.5 text-xs font-bold text-white shadow-md hover:bg-amber-700 transition">
                            <i class="fa-light fa-plus mr-1.5"></i><?php echo e(__('Add New Automation Rule')); ?>

                        </button>
                    </div>

                    <div class="mt-5 space-y-3">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $automationRules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 rounded-2xl border border-slate-200 p-4 transition hover:border-amber-500/40 dark:border-slate-800 dark:hover:border-amber-500/40">
                                <div>
                                    <p class="text-sm font-bold text-slate-900 dark:text-white"><?php echo e($rule['name']); ?></p>
                                    <p class="mt-0.5 text-xs text-slate-400">Trigger: <span class="font-bold text-slate-700 dark:text-slate-200"><?php echo e($rule['trigger']); ?></span> &rarr; Action: <span class="font-bold text-blue-600"><?php echo e($rule['action']); ?></span></p>
                                </div>
                                <div class="flex items-center gap-3 shrink-0">
                                    <button type="button" wire:click="testAutomationRule(<?php echo e($rule['id']); ?>)" class="rounded-xl border border-amber-500/20 bg-amber-500/10 px-3 py-1.5 text-xs font-bold text-amber-600 hover:bg-amber-500/20">
                                        <i class="fa-light fa-vial mr-1"></i>Test Trigger
                                    </button>
                                    <button type="button" wire:click="toggleAutomationRule(<?php echo e($rule['id']); ?>)" class="rounded-full px-3.5 py-1 text-xs font-bold transition <?php echo e($rule['active'] ? 'bg-emerald-500/10 text-emerald-600 border border-emerald-500/20' : 'bg-slate-100 text-slate-500'); ?>">
                                        <?php echo e($rule['active'] ? 'Active' : 'Paused'); ?>

                                    </button>
                                    <button type="button" wire:click="deleteAutomationRule(<?php echo e($rule['id']); ?>)" class="text-xs font-bold text-slate-400 hover:text-rose-500">
                                        <i class="fa-light fa-trash-can"></i>
                                    </button>
                                </div>
                            </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>
                </section>
            </div>
        <?php elseif($activeTab === 'triggers'): ?>
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                    <div>
                        <h2 class="text-lg font-bold text-slate-950 dark:text-white"><?php echo e(__('Event Registry & Webhook Triggers')); ?></h2>
                        <p class="text-sm text-slate-500"><?php echo e(__('Real-time system events that trigger background workflows across modules.')); ?></p>
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
        <?php else: ?>
            <!-- Execution Logs -->
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                    <div>
                        <h2 class="text-lg font-bold text-slate-950 dark:text-white"><?php echo e(__('Automation Execution Audit Logs')); ?></h2>
                        <p class="text-sm text-slate-500"><?php echo e(__('Real-time log of background rule triggers, payload events, and execution status.')); ?></p>
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
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- MODULE 4: PROJECT & TASK MANAGEMENT ENHANCED WORKSPACE -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($moduleKey === 'tasks'): ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activeTab === 'projects'): ?>
            <div class="space-y-6">
                <!-- Productivity KPI Cards -->
                <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400"><?php echo e(__('Active Projects')); ?></p>
                        <p class="mt-2 text-2xl font-black text-sky-600"><?php echo e($dbProjects->where('status', 'active')->count() ?: $dbProjects->count()); ?></p>
                        <p class="mt-1 text-xs font-medium text-sky-500"><i class="fa-light fa-folder-open mr-1"></i>Currently running</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400"><?php echo e(__('Tasks in Progress')); ?></p>
                        <p class="mt-2 text-2xl font-black text-amber-600"><?php echo e(count(array_filter($tasks, fn($t) => $t['status'] === 'in_progress'))); ?></p>
                        <p class="mt-1 text-xs font-medium text-amber-500"><i class="fa-light fa-spinner mr-1"></i>Actively being worked on</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400"><?php echo e(__('Completed Tasks')); ?></p>
                        <p class="mt-2 text-2xl font-black text-emerald-600"><?php echo e(count(array_filter($tasks, fn($t) => $t['status'] === 'done'))); ?></p>
                        <p class="mt-1 text-xs font-medium text-emerald-500"><i class="fa-light fa-check-double mr-1"></i>Done this sprint</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400"><?php echo e(__('Team Velocity')); ?></p>
                        <p class="mt-2 text-2xl font-black text-purple-600">94%</p>
                        <p class="mt-1 text-xs font-medium text-emerald-500"><i class="fa-light fa-arrow-trend-up mr-1"></i>+8% vs last sprint</p>
                    </div>
                </div>

                <!-- Projects Grid -->
                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                        <div>
                            <h2 class="text-lg font-bold text-slate-950 dark:text-white"><?php echo e(__('Projects & Milestone Tracking')); ?></h2>
                            <p class="text-sm text-slate-500"><?php echo e(__('Organize work, track progress completion, and manage team assignments.')); ?></p>
                        </div>
                        <button type="button" wire:click="openCreateModal('project')" class="rounded-xl bg-sky-600 px-4 py-2.5 text-xs font-bold text-white shadow-md hover:bg-sky-700 transition">
                            <i class="fa-light fa-plus mr-1.5"></i><?php echo e(__('Create Project')); ?>

                        </button>
                    </div>
                    <div class="mt-5 grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $dbProjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $proj): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <div class="rounded-2xl border border-slate-200 p-5 transition hover:border-sky-500/40 hover:shadow-md dark:border-slate-800 dark:hover:border-sky-500/40">
                                <div class="flex items-center justify-between">
                                    <span class="rounded-full <?php echo e($proj->progress_percent >= 100 ? 'bg-emerald-500/10 text-emerald-600 border border-emerald-500/20' : 'bg-sky-500/10 text-sky-600 border border-sky-500/20'); ?> px-2.5 py-0.5 text-[10px] font-bold"><?php echo e($proj->progress_percent >= 100 ? 'Completed' : 'Active'); ?></span>
                                    <button type="button" wire:click="updateProjectProgress(<?php echo e($proj->id); ?>, <?php echo e(min(100, $proj->progress_percent + 25)); ?>)" class="rounded-lg bg-sky-500/10 px-2 py-1 text-[10px] font-bold text-sky-600 hover:bg-sky-500/20 transition" wire:loading.attr="disabled">
                                        <i class="fa-light fa-plus mr-0.5"></i>25%
                                    </button>
                                </div>
                                <h3 class="mt-3 text-base font-bold text-slate-900 dark:text-white"><?php echo e($proj->name); ?></h3>
                                <p class="mt-1 text-xs text-slate-500"><i class="fa-light fa-calendar mr-1"></i>Due: <?php echo e($proj->due_date?->format('M d, Y') ?: 'Aug 30, 2026'); ?></p>
                                <p class="mt-0.5 text-xs text-slate-400"><i class="fa-light fa-user mr-1"></i><?php echo e($proj->assignee ?: 'Lagos HQ Team'); ?></p>
                                <div class="mt-4 pt-3 border-t dark:border-slate-800">
                                    <div class="flex justify-between text-xs font-bold mb-1.5">
                                        <span class="text-slate-400">Progress</span>
                                        <span class="text-sky-600"><?php echo e($proj->progress_percent); ?>%</span>
                                    </div>
                                    <div class="h-2 w-full overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800">
                                        <div class="h-full rounded-full transition-all duration-500 <?php echo e($proj->progress_percent >= 100 ? 'bg-emerald-500' : 'bg-sky-500'); ?>" style="width: <?php echo e($proj->progress_percent); ?>%;"></div>
                                    </div>
                                </div>
                            </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            <div class="col-span-full py-12 text-center">
                                <i class="fa-light fa-folder-tree text-4xl text-slate-300"></i>
                                <p class="mt-3 text-sm font-medium text-slate-400"><?php echo e(__('No projects yet. Create your first project.')); ?></p>
                                <button type="button" wire:click="openCreateModal('project')" class="mt-4 rounded-xl bg-sky-600 px-5 py-2 text-xs font-bold text-white shadow-md hover:bg-sky-700"><?php echo e(__('Create First Project')); ?></button>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </section>
            </div>
        <?php elseif($activeTab === 'assignments'): ?>
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                    <div>
                        <h2 class="text-lg font-bold text-slate-950 dark:text-white"><?php echo e(__('Task Assignments & Kanban Board')); ?></h2>
                        <p class="text-sm text-slate-500"><?php echo e(__('Track task statuses, assignees, and move tasks through workflow stages.')); ?></p>
                    </div>
                </div>
                <div class="mt-5 grid gap-4 md:grid-cols-4">
                    <?php
                        $taskStatuses = [
                            'todo' => ['label' => 'To Do', 'color' => 'slate', 'icon' => 'fa-light fa-circle-dashed'],
                            'in_progress' => ['label' => 'In Progress', 'color' => 'amber', 'icon' => 'fa-light fa-spinner'],
                            'in_review' => ['label' => 'In Review', 'color' => 'blue', 'icon' => 'fa-light fa-eye'],
                            'done' => ['label' => 'Done', 'color' => 'emerald', 'icon' => 'fa-light fa-circle-check'],
                        ];
                    ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $taskStatuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $statusKey => $statusMeta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <div class="rounded-2xl border border-slate-200 bg-slate-50/50 p-4 dark:border-slate-800 dark:bg-slate-900/50">
                            <div class="flex items-center gap-2 border-b pb-3 dark:border-slate-800">
                                <i class="<?php echo e($statusMeta['icon']); ?> text-<?php echo e($statusMeta['color']); ?>-500"></i>
                                <h3 class="text-sm font-bold text-slate-900 dark:text-white"><?php echo e($statusMeta['label']); ?></h3>
                                <span class="ml-auto rounded-full bg-<?php echo e($statusMeta['color']); ?>-500/10 px-2 py-0.5 text-[10px] font-bold text-<?php echo e($statusMeta['color']); ?>-600"><?php echo e(count(array_filter($tasks, fn($t) => $t['status'] === $statusKey))); ?></span>
                            </div>

                            <div class="mt-3 space-y-2.5">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tIdx => $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($task['status'] === $statusKey): ?>
                                        <div class="rounded-xl border border-slate-200 bg-white p-3.5 shadow-2xs transition hover:shadow-sm dark:border-slate-700 dark:bg-slate-800">
                                            <div class="flex items-start justify-between">
                                                <span class="rounded-full px-2 py-0.5 text-[9px] font-bold <?php echo e(match($task['priority']) {
                                                    'Critical' => 'bg-rose-500/10 text-rose-600 border border-rose-500/20',
                                                    'High' => 'bg-amber-500/10 text-amber-600 border border-amber-500/20',
                                                    'Normal' => 'bg-blue-500/10 text-blue-600 border border-blue-500/20',
                                                    default => 'bg-slate-100 text-slate-500',
                                                }); ?>"><?php echo e($task['priority']); ?></span>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($statusKey !== 'done'): ?>
                                                    <?php $nextStatus = match($statusKey) { 'todo' => 'in_progress', 'in_progress' => 'in_review', 'in_review' => 'done', default => 'done' }; ?>
                                                    <button type="button" wire:click="updateTaskStatus(<?php echo e($tIdx); ?>, '<?php echo e($nextStatus); ?>')" class="text-[10px] font-bold text-sky-600 hover:underline"><i class="fa-light fa-arrow-right"></i></button>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </div>
                                            <p class="mt-2 text-xs font-bold text-slate-900 dark:text-white leading-snug"><?php echo e($task['title']); ?></p>
                                            <div class="mt-2.5 flex items-center justify-between text-[10px] text-slate-400">
                                                <span><i class="fa-light fa-user mr-0.5"></i><?php echo e(explode(' ', $task['assignee'])[0]); ?></span>
                                                <span><i class="fa-light fa-calendar mr-0.5"></i><?php echo e($task['due']); ?></span>
                                            </div>
                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </div>
                        </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
            </section>
        <?php elseif($activeTab === 'progress'): ?>
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                    <div>
                        <h2 class="text-lg font-bold text-slate-950 dark:text-white"><?php echo e(__('Activity Timeline & Progress Log')); ?></h2>
                        <p class="text-sm text-slate-500"><?php echo e(__('Chronological feed of project milestones, task completions, and team updates.')); ?></p>
                    </div>
                </div>

                <div class="mt-6 relative">
                    <div class="absolute left-5 top-0 bottom-0 w-0.5 bg-slate-200 dark:bg-slate-800"></div>

                    <div class="space-y-6">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = [
                            ['time' => '2 hours ago', 'user' => 'Babatunde Adeleke', 'action' => 'Completed task', 'detail' => 'Implement POS receipt thermal printing', 'icon' => 'fa-light fa-check-circle', 'color' => 'emerald'],
                            ['time' => '5 hours ago', 'user' => 'Fatima Bello', 'action' => 'Started working on', 'detail' => 'Design CRM pipeline Kanban board UI', 'icon' => 'fa-light fa-play-circle', 'color' => 'amber'],
                            ['time' => 'Yesterday', 'user' => 'Emeka Nwosu', 'action' => 'Updated progress to 75%', 'detail' => 'Inventory Automation project', 'icon' => 'fa-light fa-arrow-up-right', 'color' => 'blue'],
                            ['time' => '2 days ago', 'user' => 'Sola Adeyemi', 'action' => 'Created new project', 'detail' => 'Marketing Channels integration Q3', 'icon' => 'fa-light fa-folder-plus', 'color' => 'purple'],
                            ['time' => '3 days ago', 'user' => 'System', 'action' => 'Auto-assigned overdue alert', 'detail' => 'Q3 Executive Financial Report deadline approaching', 'icon' => 'fa-light fa-bell', 'color' => 'rose'],
                        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <div class="relative flex gap-4 pl-12">
                                <span class="absolute left-3 flex h-5 w-5 items-center justify-center rounded-full bg-<?php echo e($event['color']); ?>-500/10 ring-4 ring-white dark:ring-slate-900">
                                    <i class="<?php echo e($event['icon']); ?> text-xs text-<?php echo e($event['color']); ?>-500"></i>
                                </span>
                                <div class="rounded-2xl border border-slate-200 bg-slate-50/50 p-4 flex-1 dark:border-slate-800 dark:bg-slate-800/50">
                                    <div class="flex items-center justify-between">
                                        <p class="text-xs font-bold text-slate-900 dark:text-white"><?php echo e($event['user']); ?> <span class="font-normal text-slate-400"><?php echo e($event['action']); ?></span></p>
                                        <span class="text-[10px] font-semibold text-slate-400"><?php echo e($event['time']); ?></span>
                                    </div>
                                    <p class="mt-1 text-xs font-semibold text-slate-600 dark:text-slate-300"><?php echo e($event['detail']); ?></p>
                                </div>
                            </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>
                </div>
            </section>
        <?php else: ?>
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
                    <h2 class="text-lg font-bold text-slate-950 dark:text-white border-b pb-4 dark:border-slate-800"><?php echo e(__('Team Contributor Performance')); ?></h2>
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
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = [
                                    ['name' => 'Babatunde Adeleke', 'completed' => 8, 'in_progress' => 2, 'ontime' => '95%', 'avg' => '2.8 days'],
                                    ['name' => 'Fatima Bello', 'completed' => 6, 'in_progress' => 1, 'ontime' => '100%', 'avg' => '3.1 days'],
                                    ['name' => 'Emeka Nwosu', 'completed' => 5, 'in_progress' => 2, 'ontime' => '80%', 'avg' => '3.5 days'],
                                    ['name' => 'Sola Adeyemi', 'completed' => 4, 'in_progress' => 1, 'ontime' => '100%', 'avg' => '2.4 days'],
                                ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                    <tr class="transition hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                                        <td class="px-4 py-3.5">
                                            <div class="flex items-center gap-2.5">
                                                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-sky-500/10 text-xs font-black text-sky-600"><?php echo e(strtoupper(substr($member['name'], 0, 2))); ?></span>
                                                <span class="font-bold text-slate-900 dark:text-white"><?php echo e($member['name']); ?></span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3.5 font-black text-emerald-600"><?php echo e($member['completed']); ?></td>
                                        <td class="px-4 py-3.5 font-bold text-amber-600"><?php echo e($member['in_progress']); ?></td>
                                        <td class="px-4 py-3.5 font-bold text-sky-600"><?php echo e($member['ontime']); ?></td>
                                        <td class="px-4 py-3.5 text-xs font-semibold text-slate-500"><?php echo e($member['avg']); ?></td>
                                    </tr>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- MODULE 10: REPORTS ENHANCED -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($moduleKey === 'reports'): ?>
        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                <div>
                    <h2 class="text-lg font-bold text-slate-950 dark:text-white"><?php echo e(__('Executive Management Reports & Business Intelligence')); ?></h2>
                    <p class="text-sm text-slate-500"><?php echo e(__('Unified business intelligence across Lagos HQ and regional branches.')); ?></p>
                </div>
                <a href="<?php echo e(route('portal.finance.export-csv')); ?>" class="rounded-xl bg-teal-600 px-4 py-2 text-xs font-bold text-white hover:bg-teal-700">
                    <i class="fa-light fa-file-excel mr-2"></i><?php echo e(__('Export Master Executive Report (CSV)')); ?>

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
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Interactive Full-Page Dedicated Workspace Creation Interface -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showModal): ?>
        <div class="fixed inset-0 z-[160] overflow-y-auto bg-slate-100/95 p-4 md:p-8 backdrop-blur-md dark:bg-slate-950/95">
            <div class="mx-auto max-w-5xl space-y-6">
                <!-- Workspace Page Header Navigation -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="flex items-center gap-3">
                        <button type="button" wire:click="closeModal" class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-100 dark:border-slate-800 dark:bg-slate-800 dark:text-slate-200 transition">
                            <i class="fa-light fa-arrow-left mr-2"></i><?php echo e(__('Back to :module Workspace', ['module' => ucfirst($moduleKey)])); ?>

                        </button>
                        <div>
                            <h1 class="text-xl font-extrabold text-slate-950 dark:text-white">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($modalType === 'invoice'): ?> <?php echo e(__('New Enterprise Invoice Studio')); ?>

                                <?php elseif($modalType === 'expense'): ?> <?php echo e(__('Record Expense Transaction Studio')); ?>

                                <?php elseif($modalType === 'pos_sale'): ?> <?php echo e(__('New POS Quick Sale & Terminal Checkout')); ?>

                                <?php elseif($modalType === 'product'): ?> <?php echo e(__('Add New Product SKU Inventory Studio')); ?>

                                <?php elseif($modalType === 'campaign'): ?> <?php echo e(__('Create New Marketing Campaign Studio')); ?>

                                <?php elseif($modalType === 'rule'): ?> <?php echo e(__('Add New Automation Rule Studio')); ?>

                                <?php elseif($modalType === 'thermal_label'): ?> <?php echo e(__('Direct Thermal Barcode Printer Station')); ?>

                                <?php elseif($modalType === 'pos_receipt'): ?> <?php echo e(__('Ascend Systems Thermal POS Sales Receipt Station')); ?>

                                <?php else: ?> <?php echo e(__('Create New Enterprise Record Studio')); ?>

                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </h1>
                            <p class="text-xs font-semibold text-slate-400"><?php echo e(__('Full-page creation workspace & data entry interface')); ?></p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="button" wire:click="closeModal" class="rounded-2xl border border-slate-200 px-5 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 dark:border-slate-800 dark:text-slate-200">
                            <?php echo e(__('Cancel & Discard')); ?>

                        </button>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(in_array($modalType, ['invoice', 'expense', 'product', 'campaign', 'rule', 'pos_sale', 'deal', 'lead', 'sales_order', 'project'])): ?>
                            <button type="button" wire:click="submitModalForm" class="rounded-2xl bg-blue-600 px-6 py-2.5 text-xs font-bold text-white shadow-lg shadow-blue-500/20 hover:bg-blue-700 transition">
                                <i class="fa-light fa-cloud-arrow-up mr-2"></i><?php echo e(__('Save & Persist Record')); ?>

                            </button>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>

                <!-- Main Workspace Creation Body -->
                <div class="rounded-3xl border border-slate-200 bg-white p-6 md:p-10 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($modalType === 'thermal_label' && $modalData): ?>
                    <div class="text-center border-b pb-4 dark:border-slate-800">
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-orange-500/10 text-orange-600">
                            <i class="fa-light fa-barcode text-3xl"></i>
                        </div>
                        <h3 class="mt-2 text-lg font-bold text-slate-950 dark:text-white">Direct Thermal Barcode Label Printer</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Hardware: <?php echo e($modalData['printer']); ?></p>
                    </div>

                    <!-- Printable 58mm Thermal Sticker Visual Card -->
                    <div class="my-5 mx-auto w-64 rounded-2xl bg-white p-5 border-2 border-dashed border-slate-300 shadow-md text-slate-950 text-center">
                        <p class="text-[10px] font-black uppercase tracking-tight text-slate-600"><?php echo e($modalData['store']); ?></p>
                        <p class="text-sm font-extrabold mt-1 text-slate-900"><?php echo e($modalData['name']); ?></p>

                        <!-- Code128 Barcode Bars Graphic -->
                        <div class="my-3 flex items-center justify-center gap-1 font-mono text-2xl font-black tracking-widest text-slate-950 select-none">
                            |||| | ||| |||| | || ||||
                        </div>

                        <p class="text-xs font-mono font-extrabold text-slate-800">*<?php echo e($modalData['sku']); ?>*</p>
                        <p class="mt-2 text-xl font-black text-orange-600">PRICE: ₦<?php echo e(number_format($modalData['price'], 2)); ?></p>
                        <p class="mt-1 text-[9px] text-slate-400">Timestamp: <?php echo e($modalData['timestamp']); ?></p>
                    </div>

                    <div class="space-y-3 border-t pt-4 dark:border-slate-800">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-bold text-slate-500">Print Copies</span>
                            <span class="font-extrabold text-slate-900 dark:text-white"><?php echo e($modalData['copies']); ?> Copy / Roll Batch</span>
                        </div>

                        <div class="flex justify-end gap-3 pt-2">
                            <button type="button" wire:click="closeModal" class="rounded-2xl border border-slate-200 px-5 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 dark:border-slate-800 dark:text-slate-200">
                                <?php echo e(__('Cancel')); ?>

                            </button>
                            <button type="button" onclick="window.print()" class="rounded-2xl bg-orange-600 px-6 py-2.5 text-xs font-bold text-white shadow-lg shadow-orange-500/20 hover:bg-orange-700">
                                <i class="fa-light fa-print mr-2"></i><?php echo e(__('Send to Thermal Printer')); ?>

                            </button>
                        </div>
                    </div>
                <?php elseif($modalType === 'pos_receipt' && $modalData): ?>
                    <div class="text-center border-b pb-4 dark:border-slate-800">
                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-emerald-500/10 text-emerald-500">
                            <i class="fa-light fa-circle-check text-4xl"></i>
                        </div>
                        <h3 class="mt-3 text-xl font-bold text-slate-950 dark:text-white">Ascend Systems POS Receipt</h3>
                        <p class="text-xs text-slate-400 mt-1">Receipt #: <?php echo e($modalData['receipt_no']); ?> · <?php echo e($modalData['date']); ?></p>
                        <p class="text-xs font-bold text-emerald-600 mt-0.5">Method: <?php echo e($modalData['payment_method']); ?> · Customer: <?php echo e($modalData['customer'] ?? 'Walk-in Retail Client'); ?></p>
                    </div>

                    <div class="mt-4 space-y-2.5 text-sm border-b pb-4 dark:border-slate-800">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $modalData['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <div class="flex justify-between">
                                <span class="font-semibold"><?php echo e($item['name']); ?> (x<?php echo e($item['quantity']); ?>)</span>
                                <span class="font-mono font-bold">₦<?php echo e(number_format($item['price'] * $item['quantity'], 2)); ?></span>
                            </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>

                    <div class="mt-4 space-y-1.5 text-sm">
                        <div class="flex justify-between text-slate-500"><span>Subtotal</span><span>₦<?php echo e(number_format($modalData['subtotal'], 2)); ?></span></div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(($modalData['discount'] ?? 0) > 0): ?>
                            <div class="flex justify-between text-emerald-600 font-bold"><span>Loyalty Discount</span><span>-₦<?php echo e(number_format($modalData['discount'], 2)); ?></span></div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <div class="flex justify-between text-slate-500"><span>VAT (7.5%)</span><span>₦<?php echo e(number_format($modalData['tax'], 2)); ?></span></div>
                        <div class="flex justify-between font-black text-base text-slate-900 dark:text-white pt-2 border-t dark:border-slate-800"><span>Total Paid</span><span class="text-orange-600">₦<?php echo e(number_format($modalData['total'], 2)); ?></span></div>
                    </div>

                    <div class="mt-6 flex flex-wrap items-center justify-end gap-2.5">
                        <button type="button" wire:click="closeModal" class="rounded-2xl border border-slate-200 px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 dark:border-slate-800 dark:text-slate-200">
                            <?php echo e(__('Close')); ?>

                        </button>
                        <button type="button" wire:click="sendWhatsAppReceipt('<?php echo e($modalData['receipt_no']); ?>', '<?php echo e($customerContact); ?>')" class="rounded-2xl bg-emerald-600 px-4 py-2.5 text-xs font-bold text-white shadow-md hover:bg-emerald-700 transition">
                            <i class="fa-brands fa-whatsapp mr-1.5"></i><?php echo e(__('WhatsApp e-Receipt')); ?>

                        </button>
                        <a href="rawbt:base64,<?php echo e(base64_encode('ASCEND POS RECEIPT #' . $modalData['receipt_no'] . "\nTotal: NGN " . number_format($modalData['total'], 2))); ?>" class="rounded-2xl bg-blue-600 px-4 py-2.5 text-xs font-bold text-white shadow-md hover:bg-blue-700 transition">
                            <i class="fa-light fa-mobile mr-1.5"></i><?php echo e(__('RawBT Mobile Driver')); ?>

                        </a>
                        <button type="button" onclick="window.print()" class="rounded-2xl bg-orange-600 px-5 py-2.5 text-xs font-bold text-white hover:bg-orange-700">
                            <i class="fa-light fa-print mr-2"></i><?php echo e(__('Print Thermal Receipt')); ?>

                        </button>
                    </div>
                <?php elseif($modalType === 'pos_sale'): ?>
                    <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                        <div>
                            <h3 class="text-lg font-bold text-slate-950 dark:text-white"><?php echo e(__('New POS Quick Sale & Terminal Checkout')); ?></h3>
                            <p class="text-xs text-slate-400">Fast retail checkout with automatic inventory stock updates</p>
                        </div>
                        <button type="button" wire:click="closeModal" class="rounded-xl p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-slate-800">
                            <i class="fa-light fa-xmark text-lg"></i>
                        </button>
                    </div>

                    <div class="mt-4 space-y-4">
                        <!-- Barcode Scanner Quick Input -->
                        <form wire:submit.prevent="scanBarcode" class="flex items-center gap-2">
                            <input type="text" wire:model="barcodeScannerInput" placeholder="Scan SKU barcode (e.g. POS-HDW-004)..." class="w-full rounded-2xl border border-slate-200 px-3.5 py-2 text-xs font-semibold outline-none focus:border-orange-500 dark:border-slate-800 dark:bg-slate-800 dark:text-white">
                            <button type="submit" class="shrink-0 rounded-2xl bg-orange-600 px-4 py-2 text-xs font-bold text-white shadow-md hover:bg-orange-700">Scan</button>
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
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $posCart; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $cartItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <div class="flex items-center justify-between py-2 text-xs">
                                    <div>
                                        <p class="font-bold text-slate-900 dark:text-white"><?php echo e($cartItem['name']); ?></p>
                                        <p class="text-slate-400">₦<?php echo e(number_format($cartItem['price'], 2)); ?> x <?php echo e($cartItem['quantity']); ?></p>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <button type="button" wire:click="updatePosCartQuantity(<?php echo e($index); ?>, <?php echo e($cartItem['quantity'] - 1); ?>)" class="h-6 w-6 rounded bg-slate-100 font-bold text-slate-600 dark:bg-slate-800 dark:text-slate-300">-</button>
                                        <span class="font-black"><?php echo e($cartItem['quantity']); ?></span>
                                        <button type="button" wire:click="updatePosCartQuantity(<?php echo e($index); ?>, <?php echo e($cartItem['quantity'] + 1); ?>)" class="h-6 w-6 rounded bg-slate-100 font-bold text-slate-600 dark:bg-slate-800 dark:text-slate-300">+</button>
                                    </div>
                                </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </div>

                        <!-- Payment & Discount -->
                        <div class="grid gap-2 sm:grid-cols-2">
                            <div>
                                <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Discount</label>
                                <div class="flex gap-1">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = [0, 5, 10, 15]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $disc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                        <button type="button" wire:click="setPosDiscount(<?php echo e($disc); ?>)" class="rounded-lg px-2 py-1 text-[10px] font-bold border <?php echo e($posDiscountPercent === (float)$disc ? 'bg-orange-600 text-white' : 'border-slate-200 dark:border-slate-700'); ?>"><?php echo e($disc); ?>%</button>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
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
                            <i class="fa-light fa-print mr-2"></i><?php echo e(__('Complete Sale & Print Receipt')); ?>

                        </button>
                    </div>
                <?php elseif($modalType === 'invoice'): ?>
                    <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                        <div>
                            <h3 class="text-lg font-bold text-slate-950 dark:text-white"><?php echo e(__('Create New Customer Invoice')); ?></h3>
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
                <?php elseif($modalType === 'product'): ?>
                    <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                        <div>
                            <h3 class="text-lg font-bold text-slate-950 dark:text-white"><?php echo e(__('Add New Product SKU')); ?></h3>
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
                <?php elseif($modalType === 'campaign'): ?>
                    <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                        <div>
                            <h3 class="text-lg font-bold text-slate-950 dark:text-white"><?php echo e(__('Create New Marketing Campaign')); ?></h3>
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
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = ['Awareness', 'Lead Gen', 'Conversion', 'Retargeting']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                                <span class="rounded-full bg-purple-500/10 border border-purple-500/20 px-3 py-1 text-[10px] font-bold text-purple-600 cursor-pointer hover:bg-purple-500/20 transition"><?php echo e($type); ?></span>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
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
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = ['Lagos', 'Abuja (FCT)', 'Port Harcourt', 'Ibadan', 'Kano', 'All Nigeria']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $geo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                        <span class="rounded-full bg-slate-100 border border-slate-200 px-3 py-1 text-[10px] font-bold text-slate-600 cursor-pointer hover:bg-purple-500/10 hover:border-purple-500/20 hover:text-purple-600 transition dark:bg-slate-800 dark:border-slate-700 dark:text-slate-300"><?php echo e($geo); ?></span>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
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

                <?php elseif($modalType === 'rule'): ?>
                    <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                        <div>
                            <h3 class="text-lg font-bold text-slate-950 dark:text-white"><?php echo e(__('Add New Automation Rule Studio')); ?></h3>
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
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = ['Email Dispatch', 'SMS Alert', 'In-App Bell', 'Slack / Teams Webhook']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $channel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                        <label class="flex items-center gap-1.5 rounded-full bg-white border border-slate-200 px-3 py-1 text-[10px] font-bold text-slate-700 cursor-pointer dark:bg-slate-800 dark:border-slate-700 dark:text-slate-300">
                                            <input type="checkbox" checked class="rounded text-amber-600 focus:ring-amber-500">
                                            <span><?php echo e($channel); ?></span>
                                        </label>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
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
                <?php else: ?>
                    <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                        <h3 class="text-lg font-bold text-slate-950 dark:text-white"><?php echo e(__('Create New Record')); ?></h3>
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
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH C:\Users\DELL\Downloads\Ascend AI\modules\AppAscend\Providers/../Resources/views/livewire/ascend-module-viewer.blade.php ENDPATH**/ ?>