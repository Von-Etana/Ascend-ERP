<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ensure the social_accounts table has the user_id column that
     * the application queries via: where `user_id` = ?
     *
     * The original table only has created_by_user_id. This migration
     * adds user_id (as an alias/copy of created_by_user_id) so existing
     * queries don't throw SQLSTATE[42S22] Column not found: 1054.
     */
    public function up(): void
    {
        // 1. Add user_id to social_accounts if missing
        if (Schema::hasTable('social_accounts')) {
            if (! Schema::hasColumn('social_accounts', 'user_id')) {
                Schema::table('social_accounts', function (Blueprint $table): void {
                    $table->unsignedBigInteger('user_id')->nullable()->index('social_accounts_user_id_index');
                });

                // Back-fill user_id from created_by_user_id for existing rows
                \DB::statement('UPDATE `social_accounts` SET `user_id` = `created_by_user_id` WHERE `user_id` IS NULL AND `created_by_user_id` IS NOT NULL');
            }
        }

        // 2. Broad safety net: ensure user_id on every table the app queries by user_id
        $tables = [
            'notifications',
            'notification_manual',
            'notification_manual_states',
            'files',
            'account_groups',
            'audit_logs',
            'ai_usage_logs',
            'credit_usage_logs',
            'credit_topup_ledgers',
            'options',
            'posts',
            'campaigns',
            'automations',
            'automation_logs',
            'support_tickets',
            'support_categories',
            'invoices',
            'estimates',
            'transactions',
            'inventory_items',
            'purchase_orders',
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName) && ! Schema::hasColumn($tableName, 'user_id')) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName): void {
                    $table->unsignedBigInteger('user_id')->nullable()->index("{$tableName}_user_id_idx");
                });
            }
        }
    }

    public function down(): void
    {
        // Intentionally empty – removing user_id could break other code
    }
};
