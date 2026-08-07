<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ascend_leads', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained('ascend_companies')->nullOnDelete();
            $table->foreignId('owner_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('source', 50)->default('manual');
            $table->string('status', 30)->default('new');
            $table->unsignedTinyInteger('score')->default(0);
            $table->string('company_name')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('last_contacted_at')->nullable();
            $table->timestamps();
            $table->index(['company_id', 'status', 'score']);
        });

        Schema::create('email_templates', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained('ascend_companies')->nullOnDelete();
            $table->string('name');
            $table->string('subject')->nullable();
            $table->longText('html')->nullable();
            $table->longText('text')->nullable();
            $table->string('category', 40)->default('newsletter');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('email_campaigns', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained('ascend_companies')->nullOnDelete();
            $table->foreignId('template_id')->nullable()->constrained('email_templates')->nullOnDelete();
            $table->foreignId('created_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name');
            $table->string('subject')->nullable();
            $table->string('from_email')->nullable();
            $table->string('from_name')->nullable();
            $table->string('status', 30)->default('draft');
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->unsignedInteger('recipients_count')->default(0);
            $table->unsignedInteger('delivered_count')->default(0);
            $table->unsignedInteger('opened_count')->default(0);
            $table->unsignedInteger('clicked_count')->default(0);
            $table->unsignedInteger('bounced_count')->default(0);
            $table->unsignedInteger('unsubscribed_count')->default(0);
            $table->json('audience')->nullable();
            $table->timestamps();
            $table->index(['company_id', 'status', 'scheduled_at']);
        });

        Schema::create('email_campaign_recipients', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('campaign_id')->constrained('email_campaigns')->cascadeOnDelete();
            $table->foreignId('lead_id')->nullable()->constrained('ascend_leads')->nullOnDelete();
            $table->string('email');
            $table->string('name')->nullable();
            $table->string('status', 30)->default('queued');
            $table->string('resend_email_id')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('opened_at')->nullable();
            $table->timestamp('clicked_at')->nullable();
            $table->timestamp('bounced_at')->nullable();
            $table->timestamp('unsubscribed_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->unique(['campaign_id', 'email']);
        });

        Schema::create('email_events', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('campaign_id')->nullable()->constrained('email_campaigns')->nullOnDelete();
            $table->foreignId('recipient_id')->nullable()->constrained('email_campaign_recipients')->nullOnDelete();
            $table->string('resend_email_id')->nullable();
            $table->string('event', 40);
            $table->json('payload')->nullable();
            $table->timestamp('occurred_at')->nullable();
            $table->timestamps();
            $table->index(['resend_email_id', 'event']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_events');
        Schema::dropIfExists('email_campaign_recipients');
        Schema::dropIfExists('email_campaigns');
        Schema::dropIfExists('email_templates');
        Schema::dropIfExists('ascend_leads');
    }
};
