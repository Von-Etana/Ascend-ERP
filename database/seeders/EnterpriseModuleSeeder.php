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

        // 3. Initial Expenses
        $initialExpenses = [
            [
                'vendor' => 'Cloud Host Solutions Ltd',
                'category' => 'Infrastructure & Hosting',
                'amount' => 185000.00,
                'payment_method' => 'bank_transfer',
                'expense_date' => now()->subDays(3)->toDateString(),
                'description' => 'Dedicated cloud server renewal and high-availability backup cluster',
                'reference' => 'EXP-2026-001',
                'approval_status' => 'approved',
            ],
            [
                'vendor' => 'Starlink Internet Nigeria',
                'category' => 'Utilities & Communications',
                'amount' => 45000.00,
                'payment_method' => 'card',
                'expense_date' => now()->subDays(6)->toDateString(),
                'description' => 'Monthly high-speed satellite internet subscription for Lagos Operations Hub',
                'reference' => 'EXP-2026-002',
                'approval_status' => 'approved',
            ],
            [
                'vendor' => 'TotalEnergies Retail Station VI',
                'category' => 'Facility Operations',
                'amount' => 120000.00,
                'payment_method' => 'bank_transfer',
                'expense_date' => now()->subDays(8)->toDateString(),
                'description' => 'Diesel fuel supply (100L) for HQ backup generators',
                'reference' => 'EXP-2026-003',
                'approval_status' => 'approved',
            ],
            [
                'vendor' => 'Meta Platforms Ireland Ltd',
                'category' => 'Marketing & Advertising',
                'amount' => 350000.00,
                'payment_method' => 'card',
                'expense_date' => now()->subDays(10)->toDateString(),
                'description' => 'Paid acquisition ad spend on Facebook & Instagram Lead Ads',
                'reference' => 'EXP-2026-004',
                'approval_status' => 'approved',
            ],
            [
                'vendor' => 'FedEx / Red Star Express',
                'category' => 'Logistics & Dispatch',
                'amount' => 68500.00,
                'payment_method' => 'bank_transfer',
                'expense_date' => now()->subDays(12)->toDateString(),
                'description' => 'Courier and express delivery of POS hardware scanners to Abuja clients',
                'reference' => 'EXP-2026-005',
                'approval_status' => 'approved',
            ],
            [
                'vendor' => 'Prime Stationery & Printing Mart',
                'category' => 'Office Supplies',
                'amount' => 32000.00,
                'payment_method' => 'cash',
                'expense_date' => now()->subDays(15)->toDateString(),
                'description' => 'Thermal receipt roll boxes (50 rolls) and office stationery',
                'reference' => 'EXP-2026-006',
                'approval_status' => 'approved',
            ],
        ];

        foreach ($initialExpenses as $expData) {
            Expense::query()->firstOrCreate(['reference' => $expData['reference']], $expData);
        }

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

        // 8. Super Admin User & System Options
        \Illuminate\Support\Facades\DB::table('users')->updateOrInsert(
            ['email' => 'admin@ascendsystems.ng'],
            [
                'name' => 'Ascend Administrator',
                'username' => 'admin',
                'password' => \Illuminate\Support\Facades\Hash::make('Password123!'),
                'email_verified_at' => now(),
                'timezone' => 'Africa/Lagos',
                'locale' => 'en',
                'is_super_admin' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        \Illuminate\Support\Facades\DB::table('options')->updateOrInsert(
            ['name' => 'installer_completed_at'],
            [
                'value' => now()->toIso8601String(),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
