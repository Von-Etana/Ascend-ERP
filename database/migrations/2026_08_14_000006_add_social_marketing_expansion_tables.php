<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Influencers & Brand Ambassadors Table
        if (! Schema::hasTable('influencer_ambassadors')) {
            Schema::create('influencer_ambassadors', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('handle');
                $table->string('platform'); // Instagram | YouTube | TikTok | LinkedIn
                $table->string('referral_code')->unique();
                $table->integer('leads_count')->default(0);
                $table->decimal('sales_attributed_ngn', 12, 2)->default(0);
                $table->decimal('commission_earned_ngn', 12, 2)->default(0);
                $table->string('status')->default('active');
                $table->timestamps();
            });
        }

        // 2. Web Lead Captures Table (www.ascendsystems.ng)
        if (! Schema::hasTable('web_lead_captures')) {
            Schema::create('web_lead_captures', function (Blueprint $table) {
                $table->id();
                $table->string('client_name');
                $table->string('phone');
                $table->string('email');
                $table->string('city_location'); // Abuja | Lagos | Kano | Port Harcourt | Ibadan
                $table->string('system_interest'); // 5.5kVA Hybrid Inverter | 10.2kWh LiFePO4 Battery | Commercial Array
                $table->decimal('estimated_budget_ngn', 12, 2)->default(0);
                $table->string('source_url')->default('https://www.ascendsystems.ng');
                $table->string('status')->default('new'); // new | quoted | converted
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('web_lead_captures');
        Schema::dropIfExists('influencer_ambassadors');
    }
};
