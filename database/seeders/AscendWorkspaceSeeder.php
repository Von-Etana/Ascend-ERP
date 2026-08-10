<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\AdminUser\Models\User;
use Modules\AdminUser\Support\PersonalTeamProvisioner;
use Modules\AppAgents\Services\AgentRegistry;

class AscendWorkspaceSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Company (Nigeria / NGN)
        $companyId = DB::table('ascend_companies')->insertGetId([
            'name' => 'Ascend Enterprise Operations Nigeria',
            'legal_name' => 'Ascend Systems Nigeria Limited',
            'currency' => 'NGN',
            'timezone' => 'Africa/Lagos',
            'settings' => json_encode(['ai_mode' => 'hybrid', 'auto_reply_confidence' => 0.85]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Branches
        $branchLagosId = DB::table('ascend_branches')->insertGetId([
            'company_id' => $companyId,
            'name' => 'Lagos HQ',
            'code' => 'LOS-001',
            'address' => 'Victoria Island, Lagos, Nigeria',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $branchAbujaId = DB::table('ascend_branches')->insertGetId([
            'company_id' => $companyId,
            'name' => 'Abuja Branch',
            'code' => 'ABJ-002',
            'address' => 'Maitama, Abuja, Nigeria',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. Contacts
        $contactId = DB::table('ascend_contacts')->insertGetId([
            'company_id' => $companyId,
            'branch_id' => $branchLagosId,
            'type' => 'customer',
            'name' => 'Northbridge Media Nigeria',
            'email' => 'info@northbridge.ng',
            'phone' => '+234 803 123 4567',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 4. Deals (in NGN - Naira)
        DB::table('ascend_deals')->insert([
            [
                'company_id' => $companyId,
                'branch_id' => $branchLagosId,
                'contact_id' => $contactId,
                'name' => 'Enterprise AI Content Automation Package',
                'stage' => 'negotiation',
                'value' => 4500000.00,
                'expected_close_at' => now()->addDays(14),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => $companyId,
                'branch_id' => $branchAbujaId,
                'contact_id' => $contactId,
                'name' => 'Omnichannel Inbox Integration',
                'stage' => 'qualified',
                'value' => 7840000.00,
                'expected_close_at' => now()->addDays(30),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // 5. Products & Inventory
        $productId = DB::table('ascend_products')->insertGetId([
            'company_id' => $companyId,
            'sku' => 'AI-SUB-ENT-NGN-001',
            'name' => 'Enterprise License (Monthly)',
            'price' => 250000.00,
            'reorder_level' => 10,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('ascend_inventory_levels')->insert([
            'company_id' => $companyId,
            'branch_id' => $branchLagosId,
            'product_id' => $productId,
            'quantity' => 100,
            'reserved_quantity' => 5,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 6. Register Default AI Agents
        if (class_exists(AgentRegistry::class)) {
            app(AgentRegistry::class)->registerDefaults();
        }

        // 7. Default Enterprise Roles & Module Permissions
        DB::table('admin_roles')->insertOrIgnore([
            [
                'name' => 'Super Admin',
                'slug' => 'super-admin',
                'description' => 'Full administrative control across all enterprise modules, security policies, and workspace settings.',
                'permissions' => json_encode(['*']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Operations Manager',
                'slug' => 'operations-manager',
                'description' => 'Manages cross-department operations, CRM, sales pipeline, inventory, POS, inbox, and publishing.',
                'permissions' => json_encode(['portal.publishing.*', 'portal.ai-studio.*', 'portal.inbox.*', 'portal.crm.*', 'portal.sales.*', 'portal.inventory.*', 'portal.pos.*', 'admin-users.view']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Content & Social Media Specialist',
                'slug' => 'content-manager',
                'description' => 'Creates, schedules, and automates social posts and AI Studio content workflows, handles customer inbox.',
                'permissions' => json_encode(['portal.publishing.*', 'portal.ai-studio.*', 'portal.inbox.*']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sales & Support Representative',
                'slug' => 'sales-support',
                'description' => 'Handles CRM contacts, deal pipelines, customer support messages, and POS transactions.',
                'permissions' => json_encode(['portal.crm.*', 'portal.sales.*', 'portal.inbox.*', 'portal.pos.*']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Finance & Inventory Officer',
                'slug' => 'finance-inventory',
                'description' => 'Oversees inventory stock levels, sales billing, point-of-sale transactions, and financial reports.',
                'permissions' => json_encode(['portal.sales.*', 'portal.inventory.*', 'portal.pos.*', 'portal.finance.*']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // 8. Default Super Admin User
        $superAdminRoleId = DB::table('admin_roles')->where('slug', 'super-admin')->value('id');

        DB::table('users')->insertOrIgnore([
            'name' => 'Ascend Administrator',
            'username' => 'admin',
            'email' => 'admin@ascendsystems.ng',
            'password' => Hash::make('Password123!'),
            'email_verified_at' => now(),
            'timezone' => 'Africa/Lagos',
            'locale' => 'en',
            'is_super_admin' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $adminId = DB::table('users')->where('username', 'admin')->value('id');

        if ($adminId) {
            $userModel = User::find($adminId);
            if ($userModel) {
                app(PersonalTeamProvisioner::class)->ensureForUser($userModel);
            }
        }

        // 9. Mark Installer Completed in options table
        DB::table('options')->insertOrIgnore([
            'name' => 'installer_completed_at',
            'value' => now()->toIso8601String(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
