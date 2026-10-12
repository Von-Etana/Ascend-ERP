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
        if (! Schema::hasTable('price_quotes')) {
            Schema::create('price_quotes', function (Blueprint $table) {
                $table->id();
                $table->string('quote_number')->unique();
                $table->string('client_name');
                $table->string('email')->nullable();
                $table->string('phone')->nullable();
                $table->text('address')->nullable();
                $table->date('valid_until')->nullable();
                $table->decimal('subtotal', 15, 2)->default(0);
                $table->decimal('discount_amount', 15, 2)->default(0);
                $table->decimal('tax', 15, 2)->default(0);
                $table->decimal('total', 15, 2)->default(0);
                $table->string('status')->default('Draft'); // Draft, Sent, Accepted, Converted, Invoiced, Expired
                $table->text('notes')->nullable();
                $table->json('items')->nullable();
                $table->unsignedBigInteger('crm_deal_id')->nullable();
                $table->unsignedBigInteger('crm_lead_id')->nullable();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price_quotes');
    }
};
