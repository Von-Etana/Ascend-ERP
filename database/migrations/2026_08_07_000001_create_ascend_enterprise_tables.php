<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Bank Accounts
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('bank_name');
            $table->string('account_number');
            $table->string('currency', 10)->default('NGN');
            $table->decimal('balance', 15, 2)->default(0.00);
            $table->string('status')->default('active');
            $table->timestamps();
        });

        // 2. Invoices
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->string('client_name');
            $table->date('issue_date');
            $table->date('due_date')->nullable();
            $table->decimal('subtotal', 15, 2)->default(0.00);
            $table->decimal('tax', 15, 2)->default(0.00);
            $table->decimal('total', 15, 2)->default(0.00);
            $table->string('status')->default('pending'); // paid, pending, overdue
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 3. Expenses
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('category');
            $table->string('vendor');
            $table->decimal('amount', 15, 2)->default(0.00);
            $table->string('payment_method')->default('bank_transfer');
            $table->date('expense_date');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 4. CRM Leads
        Schema::create('crm_leads', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('contact_person');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->decimal('deal_value', 15, 2)->default(0.00);
            $table->string('status')->default('new'); // new, contacted, qualified, converted
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 5. CRM Deals
        Schema::create('crm_deals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('crm_lead_id')->nullable()->constrained('crm_leads')->nullOnDelete();
            $table->string('deal_name');
            $table->string('stage')->default('prospecting'); // prospecting, proposal, negotiation, closed_won, closed_lost
            $table->decimal('value', 15, 2)->default(0.00);
            $table->date('expected_close')->nullable();
            $table->timestamps();
        });

        // 6. Projects
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('assignee')->nullable();
            $table->date('due_date')->nullable();
            $table->integer('progress_percent')->default(0);
            $table->string('status')->default('active');
            $table->timestamps();
        });

        // 7. Project Tasks
        Schema::create('project_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->nullable()->constrained('projects')->cascadeOnDelete();
            $table->string('title');
            $table->string('assignee')->nullable();
            $table->date('due_date')->nullable();
            $table->string('priority')->default('normal'); // high, medium, normal
            $table->boolean('completed')->default(false);
            $table->timestamps();
        });

        // 8. Inventory Products
        Schema::create('inventory_products', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique();
            $table->string('name');
            $table->string('category')->default('General');
            $table->decimal('unit_price', 15, 2)->default(0.00);
            $table->decimal('cost_price', 15, 2)->default(0.00);
            $table->integer('stock_quantity')->default(0);
            $table->integer('reorder_level')->default(5);
            $table->string('location')->default('Lagos HQ');
            $table->timestamps();
        });

        // 9. POS Receipts
        Schema::create('pos_receipts', function (Blueprint $table) {
            $table->id();
            $table->string('receipt_number')->unique();
            $table->string('cashier_name');
            $table->decimal('subtotal', 15, 2)->default(0.00);
            $table->decimal('tax', 15, 2)->default(0.00);
            $table->decimal('total', 15, 2)->default(0.00);
            $table->string('payment_method')->default('cash');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pos_receipts');
        Schema::dropIfExists('inventory_products');
        Schema::dropIfExists('project_tasks');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('crm_deals');
        Schema::dropIfExists('crm_leads');
        Schema::dropIfExists('expenses');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('bank_accounts');
    }
};
