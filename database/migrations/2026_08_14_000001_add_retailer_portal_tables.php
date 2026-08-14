<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add B2B wholesale columns to inventory_products if missing
        if (Schema::hasTable('inventory_products')) {
            Schema::table('inventory_products', function (Blueprint $table) {
                if (! Schema::hasColumn('inventory_products', 'wholesale_price')) {
                    $table->decimal('wholesale_price', 15, 2)->nullable()->after('unit_price');
                }
                if (! Schema::hasColumn('inventory_products', 'image_path')) {
                    $table->string('image_path')->nullable()->after('location');
                }
                if (! Schema::hasColumn('inventory_products', 'is_b2b_visible')) {
                    $table->boolean('is_b2b_visible')->default(true)->after('image_path');
                }
                if (! Schema::hasColumn('inventory_products', 'specifications')) {
                    $table->text('specifications')->nullable()->after('is_b2b_visible');
                }
            });
        }

        // 2. Retailer Orders table
        if (! Schema::hasTable('retailer_orders')) {
            Schema::create('retailer_orders', function (Blueprint $table) {
                $table->id();
                $table->string('order_number')->unique();
                $table->foreignId('retailer_user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('retailer_company_name');
                $table->string('retailer_email');
                $table->string('retailer_phone')->nullable();
                $table->json('items');
                $table->decimal('subtotal', 15, 2)->default(0.00);
                $table->decimal('tax', 15, 2)->default(0.00);
                $table->decimal('total_amount', 15, 2)->default(0.00);
                $table->string('order_type')->default('pending_approval'); // pending_approval | instant_invoice
                $table->string('status')->default('pending_approval'); // pending_approval | approved | invoiced | dispatched | delivered | rejected
                $table->text('shipping_address')->nullable();
                $table->text('notes')->nullable();
                $table->foreignId('invoice_id')->nullable()->constrained('invoices')->nullOnDelete();
                $table->foreignId('approved_by_user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('retailer_orders');
        if (Schema::hasTable('inventory_products')) {
            Schema::table('inventory_products', function (Blueprint $table) {
                $table->dropColumn(['wholesale_price', 'image_path', 'is_b2b_visible', 'specifications']);
            });
        }
    }
};
