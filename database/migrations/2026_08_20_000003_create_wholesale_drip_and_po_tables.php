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
        if (!Schema::hasTable('supplier_purchase_orders')) {
            Schema::create('supplier_purchase_orders', function (Blueprint $table) {
                $table->id();
                $table->string('po_number')->unique();
                $table->string('supplier_name');
                $table->string('supplier_email')->nullable();
                $table->string('supplier_country')->default('China');
                $table->string('destination_warehouse')->default('Abuja Central Distribution Hub');
                $table->json('items')->nullable();
                $table->decimal('subtotal_usd', 15, 2)->default(0);
                $table->decimal('subtotal_ngn', 15, 2)->default(0);
                $table->decimal('exchange_rate', 10, 2)->default(1620.00);
                $table->string('status')->default('draft'); // draft, sent, in_transit, received, cancelled
                $table->string('payment_terms')->default('30% Advance, 70% Bill of Lading');
                $table->string('shipping_method')->default('Sea Freight (Apapa Port)');
                $table->date('expected_delivery_date')->nullable();
                $table->timestamp('sent_to_supplier_at')->nullable();
                $table->timestamp('received_at')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('crm_lead_drip_logs')) {
            Schema::create('crm_lead_drip_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('crm_lead_id')->nullable()->constrained('crm_leads')->nullOnDelete();
                $table->string('recipient_name');
                $table->string('recipient_phone')->nullable();
                $table->string('recipient_email')->nullable();
                $table->string('channel')->default('whatsapp'); // whatsapp, email
                $table->string('step')->default('hour_1'); // hour_1, day_2, day_5
                $table->string('subject_or_action');
                $table->text('message_body')->nullable();
                $table->string('status')->default('delivered'); // queued, delivered, read, replied
                $table->timestamp('sent_at')->nullable();
                $table->timestamp('read_at')->nullable();
                $table->timestamp('replied_at')->nullable();
                $table->text('reply_content')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('partner_warranty_claims')) {
            Schema::create('partner_warranty_claims', function (Blueprint $table) {
                $table->id();
                $table->string('claim_number')->unique();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('partner_company');
                $table->string('contact_person');
                $table->string('contact_phone');
                $table->string('serial_number');
                $table->string('product_sku');
                $table->string('product_name');
                $table->date('installation_date')->nullable();
                $table->text('fault_description');
                $table->string('status')->default('pending_review'); // pending_review, approved_replacement, in_repair, resolved, rejected
                $table->string('rma_tracking_code')->nullable();
                $table->text('resolution_notes')->nullable();
                $table->timestamp('resolved_at')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partner_warranty_claims');
        Schema::dropIfExists('crm_lead_drip_logs');
        Schema::dropIfExists('supplier_purchase_orders');
    }
};
