<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('expenses')) {
            Schema::table('expenses', function (Blueprint $table) {
                if (! Schema::hasColumn('expenses', 'requested_by')) {
                    $table->unsignedBigInteger('requested_by')->nullable()->after('amount');
                }
                if (! Schema::hasColumn('expenses', 'requester_name')) {
                    $table->string('requester_name')->nullable()->after('requested_by');
                }
                if (! Schema::hasColumn('expenses', 'department')) {
                    $table->string('department')->nullable()->default('Operations')->after('requester_name');
                }
                if (! Schema::hasColumn('expenses', 'manager_approval_status')) {
                    $table->string('manager_approval_status')->default('pending')->after('approval_status'); // pending, approved, rejected
                }
                if (! Schema::hasColumn('expenses', 'manager_approved_by')) {
                    $table->unsignedBigInteger('manager_approved_by')->nullable()->after('manager_approval_status');
                }
                if (! Schema::hasColumn('expenses', 'manager_approved_at')) {
                    $table->timestamp('manager_approved_at')->nullable()->after('manager_approved_by');
                }
                if (! Schema::hasColumn('expenses', 'admin_approval_status')) {
                    $table->string('admin_approval_status')->default('pending')->after('manager_approved_at'); // pending, approved, rejected
                }
                if (! Schema::hasColumn('expenses', 'admin_approved_by')) {
                    $table->unsignedBigInteger('admin_approved_by')->nullable()->after('admin_approval_status');
                }
                if (! Schema::hasColumn('expenses', 'admin_approved_at')) {
                    $table->timestamp('admin_approved_at')->nullable()->after('admin_approved_by');
                }
                if (! Schema::hasColumn('expenses', 'rejection_reason')) {
                    $table->text('rejection_reason')->nullable()->after('admin_approved_at');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('expenses')) {
            Schema::table('expenses', function (Blueprint $table) {
                $columns = [
                    'requested_by',
                    'requester_name',
                    'department',
                    'manager_approval_status',
                    'manager_approved_by',
                    'manager_approved_at',
                    'admin_approval_status',
                    'admin_approved_by',
                    'admin_approved_at',
                    'rejection_reason',
                ];
                foreach ($columns as $column) {
                    if (Schema::hasColumn('expenses', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
};
