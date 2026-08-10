<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('notifications') && ! Schema::hasColumn('notifications', 'user_id')) {
            Schema::table('notifications', function (Blueprint $table): void {
                $table->unsignedBigInteger('user_id')->nullable()->index();
            });
        }

        if (Schema::hasTable('notification_manual_states') && ! Schema::hasColumn('notification_manual_states', 'user_id')) {
            Schema::table('notification_manual_states', function (Blueprint $table): void {
                $table->unsignedBigInteger('user_id')->nullable()->index();
            });
        }

        if (Schema::hasTable('audit_logs') && ! Schema::hasColumn('audit_logs', 'causer_user_id')) {
            Schema::table('audit_logs', function (Blueprint $table): void {
                $table->unsignedBigInteger('causer_user_id')->nullable()->index();
            });
        }
    }

    public function down(): void
    {
        //
    }
};
