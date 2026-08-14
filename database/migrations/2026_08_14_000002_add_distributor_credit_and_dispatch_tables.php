<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add credit limit & distributor tier columns to users
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (! Schema::hasColumn('users', 'distributor_tier')) {
                    $table->string('distributor_tier')->default('standard')->after('role_id'); // standard | tier2_gold | tier1_platinum
                }
                if (! Schema::hasColumn('users', 'credit_limit')) {
                    $table->decimal('credit_limit', 15, 2)->default(0.00)->after('distributor_tier');
                }
                if (! Schema::hasColumn('users', 'credit_balance')) {
                    $table->decimal('credit_balance', 15, 2)->default(0.00)->after('credit_limit');
                }
            });
        }

        // 2. Add Paystack & warehouse dispatch tracking to retailer_orders
        if (Schema::hasTable('retailer_orders')) {
            Schema::table('retailer_orders', function (Blueprint $table) {
                if (! Schema::hasColumn('retailer_orders', 'paystack_reference')) {
                    $table->string('paystack_reference')->nullable()->after('invoice_id');
                }
                if (! Schema::hasColumn('retailer_orders', 'paystack_status')) {
                    $table->string('paystack_status')->default('unpaid')->after('paystack_reference'); // unpaid | paid | pending
                }
                if (! Schema::hasColumn('retailer_orders', 'scanned_items')) {
                    $table->json('scanned_items')->nullable()->after('paystack_status');
                }
                if (! Schema::hasColumn('retailer_orders', 'warehouse_location')) {
                    $table->string('warehouse_location')->default('Abuja Central Warehouse')->after('scanned_items');
                }
                if (! Schema::hasColumn('retailer_orders', 'dispatched_at')) {
                    $table->timestamp('dispatched_at')->nullable()->after('warehouse_location');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('retailer_orders')) {
            Schema::table('retailer_orders', function (Blueprint $table) {
                $table->dropColumn(['paystack_reference', 'paystack_status', 'scanned_items', 'warehouse_location', 'dispatched_at']);
            });
        }

        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn(['distributor_tier', 'credit_limit', 'credit_balance']);
            });
        }
    }
};
