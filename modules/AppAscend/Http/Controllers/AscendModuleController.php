<?php

namespace Modules\AppAscend\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class AscendModuleController
{
    public function __invoke(Request $request, string $module): View
    {
        $catalog = [
            'crm' => ['name' => 'CRM', 'description' => 'Leads, contacts, companies, customers, and follow-up activity.', 'icon' => 'fa-light fa-users'],
            'sales' => ['name' => 'Sales', 'description' => 'Deals, pipelines, quotes, invoices, payments, and sales orders.', 'icon' => 'fa-light fa-chart-line-up'],
            'finance' => ['name' => 'Finance', 'description' => 'Revenue, expenses, budgets, taxes, ledgers, and reconciliation.', 'icon' => 'fa-light fa-circle-dollar'],
            'inventory' => ['name' => 'Inventory', 'description' => 'Products, stock, vendors, purchasing, and branch availability.', 'icon' => 'fa-light fa-boxes-stacked'],
            'pos' => ['name' => 'POS', 'description' => 'Cashier sessions, checkout, receipts, payments, and retail reporting.', 'icon' => 'fa-light fa-cash-register'],
            'marketing' => ['name' => 'Marketing', 'description' => 'Campaigns, social accounts, publishing, media, and performance analytics.', 'icon' => 'fa-light fa-bullhorn'],
            'ai-agents' => ['name' => 'AI Agents', 'description' => 'Content, strategy, lead generation, research, and operational intelligence.', 'icon' => 'fa-light fa-sparkles'],
            'automation' => ['name' => 'Automation', 'description' => 'Rules, webhooks, scheduled actions, notifications, and execution logs.', 'icon' => 'fa-light fa-bolt'],
            'tasks' => ['name' => 'Tasks', 'description' => 'Assignments, reminders, approvals, appointments, and team collaboration.', 'icon' => 'fa-light fa-list-check'],
            'reports' => ['name' => 'Reports', 'description' => 'Connected management reporting across sales, finance, inventory, and marketing.', 'icon' => 'fa-light fa-chart-mixed'],
            'administration' => ['name' => 'Administration', 'description' => 'Users, roles, permissions, branches, integrations, email, and auditability.', 'icon' => 'fa-light fa-shield-check'],
        ];

        abort_unless(isset($catalog[$module]), 404);

        return view('appascend::module', [
            'module' => $catalog[$module],
            'moduleKey' => $module,
            'title' => $catalog[$module]['name'].' - Ascend Systems',
        ])->layout(theme_view('layouts.app', 'app'), [
            'title' => $catalog[$module]['name'].' - Ascend Systems',
        ]);
    }
}
