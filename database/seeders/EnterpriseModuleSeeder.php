<?php

namespace Database\Seeders;

use App\Models\BankAccount;
use App\Models\CrmDeal;
use App\Models\CrmLead;
use App\Models\Expense;
use App\Models\InventoryProduct;
use App\Models\Invoice;
use App\Models\PosReceipt;
use App\Models\Project;
use App\Models\ProjectTask;
use Illuminate\Database\Seeder;

class EnterpriseModuleSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Bank Accounts
        BankAccount::query()->firstOrCreate(['account_number' => '0129481029'], [
            'name' => 'Lagos Operations Account',
            'bank_name' => 'Access Bank HQ',
            'currency' => 'NGN',
            'balance' => 4850000.00,
            'status' => 'active',
        ]);

        BankAccount::query()->firstOrCreate(['account_number' => '0548102941'], [
            'name' => 'Abuja Branch Account',
            'bank_name' => 'GTBank Operations',
            'currency' => 'NGN',
            'balance' => 2140500.00,
            'status' => 'active',
        ]);

        // 2. Invoices
        Invoice::query()->firstOrCreate(['invoice_number' => 'INV-20431'], [
            'client_name' => 'Northbridge Media Nigeria',
            'issue_date' => now()->subDays(5),
            'due_date' => now()->addDays(20),
            'subtotal' => 1158865.12,
            'tax' => 86914.88,
            'total' => 1245780.00,
            'status' => 'paid',
            'notes' => 'Q3 Enterprise software setup & maintenance fee',
        ]);

        Invoice::query()->firstOrCreate(['invoice_number' => 'INV-20432'], [
            'client_name' => 'Brighton Analytics Ltd',
            'issue_date' => now()->subDays(2),
            'due_date' => now()->addDays(12),
            'subtotal' => 725581.40,
            'tax' => 54418.60,
            'total' => 780000.00,
            'status' => 'pending',
            'notes' => 'CRM custom integration services',
        ]);

        // 3. Expenses
        Expense::query()->firstOrCreate(['vendor' => 'Cloud Host Solutions'], [
            'category' => 'Infrastructure & Hosting',
            'amount' => 185000.00,
            'payment_method' => 'bank_transfer',
            'expense_date' => now()->subDays(3),
            'description' => 'Dedicated cloud server renewal',
        ]);

        // 4. CRM Leads & Deals
        $lead = CrmLead::query()->firstOrCreate(['company_name' => 'Horizon Media Communications'], [
            'contact_person' => 'Segun Adebayo',
            'email' => 'contact@horizonmedia.ng',
            'phone' => '+234 802 987 6543',
            'deal_value' => 7800000.00,
            'status' => 'qualified',
            'notes' => 'Interested in enterprise AI content & CRM package',
        ]);

        CrmDeal::query()->firstOrCreate(['deal_name' => 'Horizon Media Enterprise Package'], [
            'crm_lead_id' => $lead->id,
            'stage' => 'proposal',
            'value' => 7800000.00,
            'expected_close' => now()->addDays(15),
        ]);

        // 5. Projects & Tasks
        $project = Project::query()->firstOrCreate(['name' => 'Enterprise AI Onboarding'], [
            'description' => 'Initial deployment for corporate client',
            'assignee' => 'Lagos HQ Team',
            'due_date' => now()->addDays(14),
            'progress_percent' => 85,
            'status' => 'active',
        ]);

        ProjectTask::query()->firstOrCreate(['title' => 'Configure custom CRM fields for Horizon Media'], [
            'project_id' => $project->id,
            'assignee' => 'Super Admin',
            'due_date' => now()->addDays(3),
            'priority' => 'high',
            'completed' => false,
        ]);

        // 6. Inventory Products
        InventoryProduct::query()->firstOrCreate(['sku' => 'ENT-LIC-001'], [
            'name' => 'Enterprise Server License',
            'category' => 'Software',
            'unit_price' => 250000.00,
            'cost_price' => 180000.00,
            'stock_quantity' => 2,
            'reorder_level' => 10,
            'location' => 'Lagos HQ',
        ]);

        InventoryProduct::query()->firstOrCreate(['sku' => 'POS-HDW-004'], [
            'name' => 'Thermal Barcode Scanner Unit',
            'category' => 'Hardware',
            'unit_price' => 85000.00,
            'cost_price' => 60000.00,
            'stock_quantity' => 18,
            'reorder_level' => 5,
            'location' => 'Lagos HQ',
        ]);

        // 7. POS Receipts
        PosReceipt::query()->firstOrCreate(['receipt_number' => 'REC-10492'], [
            'cashier_name' => 'Ascend Administrator',
            'subtotal' => 85000.00,
            'tax' => 6375.00,
            'total' => 91375.00,
            'payment_method' => 'card',
        ]);
    }
}
