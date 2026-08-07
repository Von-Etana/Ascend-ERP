<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ascend_companies', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('legal_name')->nullable();
            $table->string('currency', 3)->default('NGN');
            $table->string('timezone')->default('Africa/Lagos');
            $table->json('settings')->nullable();
            $table->timestamps();
        });

        Schema::create('ascend_branches', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('company_id')->constrained('ascend_companies')->cascadeOnDelete();
            $table->string('name');
            $table->string('code', 40);
            $table->string('address')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['company_id', 'code']);
        });

        Schema::create('ascend_contacts', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('company_id')->constrained('ascend_companies')->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained('ascend_branches')->nullOnDelete();
            $table->string('type', 30)->default('contact');
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('status', 30)->default('active');
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->index(['company_id', 'type', 'status']);
        });

        Schema::create('ascend_deals', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('company_id')->constrained('ascend_companies')->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained('ascend_branches')->nullOnDelete();
            $table->foreignId('contact_id')->nullable()->constrained('ascend_contacts')->nullOnDelete();
            $table->string('name');
            $table->string('stage', 40)->default('prospecting');
            $table->decimal('value', 18, 2)->default(0);
            $table->date('expected_close_at')->nullable();
            $table->foreignId('owner_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index(['company_id', 'stage']);
        });

        Schema::create('ascend_products', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('company_id')->constrained('ascend_companies')->cascadeOnDelete();
            $table->string('sku', 80);
            $table->string('name');
            $table->decimal('price', 18, 2)->default(0);
            $table->decimal('reorder_level', 18, 3)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['company_id', 'sku']);
        });

        Schema::create('ascend_inventory_levels', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('company_id')->constrained('ascend_companies')->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained('ascend_branches')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('ascend_products')->cascadeOnDelete();
            $table->decimal('quantity', 18, 3)->default(0);
            $table->decimal('reserved_quantity', 18, 3)->default(0);
            $table->timestamps();
            $table->unique(['branch_id', 'product_id']);
        });

        Schema::create('ascend_tasks', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('company_id')->constrained('ascend_companies')->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained('ascend_branches')->nullOnDelete();
            $table->foreignId('assigned_to_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->string('status', 30)->default('open');
            $table->string('priority', 20)->default('medium');
            $table->timestamp('due_at')->nullable();
            $table->json('context')->nullable();
            $table->timestamps();
            $table->index(['company_id', 'status', 'due_at']);
        });

        Schema::create('ascend_audit_events', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('company_id')->constrained('ascend_companies')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('event', 100);
            $table->string('subject_type')->nullable();
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->json('payload')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->timestamps();
            $table->index(['company_id', 'event']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ascend_audit_events');
        Schema::dropIfExists('ascend_tasks');
        Schema::dropIfExists('ascend_inventory_levels');
        Schema::dropIfExists('ascend_products');
        Schema::dropIfExists('ascend_deals');
        Schema::dropIfExists('ascend_contacts');
        Schema::dropIfExists('ascend_branches');
        Schema::dropIfExists('ascend_companies');
    }
};
