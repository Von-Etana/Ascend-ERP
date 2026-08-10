<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tablesToEnsureUserId = [
            'notifications',
            'notification_manual',
            'notification_manual_states',
            'files',
            'account_groups',
            'social_accounts',
            'audit_logs',
            'ai_usage_logs',
            'credit_usage_logs',
            'credit_topup_ledgers',
            'options',
        ];

        foreach ($tablesToEnsureUserId as $tableName) {
            if (Schema::hasTable($tableName)) {
                if (! Schema::hasColumn($tableName, 'user_id')) {
                    Schema::table($tableName, function (Blueprint $table): void {
                        $table->unsignedBigInteger('user_id')->nullable()->index();
                    });
                }
                if (! Schema::hasColumn($tableName, 'owner_user_id')) {
                    Schema::table($tableName, function (Blueprint $table): void {
                        $table->unsignedBigInteger('owner_user_id')->nullable()->index();
                    });
                }
            }
        }
    }

    public function down(): void
    {
        //
    }
};
