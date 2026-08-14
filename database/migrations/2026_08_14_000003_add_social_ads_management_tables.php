<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Paid Ad Campaigns Table (Meta, Google, LinkedIn)
        if (! Schema::hasTable('social_ad_campaigns')) {
            Schema::create('social_ad_campaigns', function (Blueprint $table) {
                $table->id();
                $table->string('campaign_name');
                $table->string('platform'); // Meta Ads | Google Search Ads | LinkedIn Ads | TikTok Ads
                $table->string('objective'); // Lead Generation | Sales Conversion | Brand Awareness
                $table->string('target_product')->nullable(); // e.g. 5.5kVA Inverter / 10.2kWh Battery
                $table->decimal('budget_ngn', 15, 2)->default(0.00);
                $table->decimal('spend_ngn', 15, 2)->default(0.00);
                $table->integer('impressions')->default(0);
                $table->integer('clicks')->default(0);
                $table->integer('leads_generated')->default(0);
                $table->decimal('revenue_generated', 15, 2)->default(0.00);
                $table->string('status')->default('active'); // active | paused | completed | draft
                $table->text('ad_creative_url')->nullable();
                $table->timestamps();
            });
        }

        // 2. Scheduled Social Posts Table
        if (! Schema::hasTable('scheduled_social_posts')) {
            Schema::create('scheduled_social_posts', function (Blueprint $table) {
                $table->id();
                $table->string('platform'); // Facebook | Instagram | LinkedIn | X | WhatsApp Business
                $table->text('caption');
                $table->text('hashtags')->nullable();
                $table->text('image_url')->nullable();
                $table->timestamp('scheduled_at')->nullable();
                $table->string('status')->default('scheduled'); // draft | scheduled | published | failed
                $table->integer('engagement_likes')->default(0);
                $table->integer('engagement_shares')->default(0);
                $table->timestamps();
            });
        }

        // 3. Unified Social Inbox Table
        if (! Schema::hasTable('social_inbox_messages')) {
            Schema::create('social_inbox_messages', function (Blueprint $table) {
                $table->id();
                $table->string('sender_name');
                $table->string('sender_handle')->nullable();
                $table->string('channel'); // Facebook DM | Instagram Comment | WhatsApp Inquiry | LinkedIn Message
                $table->text('message_body');
                $table->text('ai_suggested_reply')->nullable();
                $table->text('replied_text')->nullable();
                $table->boolean('is_read')->default(false);
                $table->boolean('is_replied')->default(false);
                $table->timestamp('received_at')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('social_inbox_messages');
        Schema::dropIfExists('scheduled_social_posts');
        Schema::dropIfExists('social_ad_campaigns');
    }
};
