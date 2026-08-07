<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inbox_conversations', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained('ascend_companies')->nullOnDelete();
            $table->foreignId('social_account_id')->nullable()->constrained('social_accounts')->nullOnDelete();
            $table->string('provider_key', 40);
            $table->string('external_thread_id', 255);
            $table->string('contact_name');
            $table->string('contact_handle')->nullable();
            $table->text('contact_avatar_url')->nullable();
            $table->string('status', 30)->default('open');
            $table->string('handling_mode', 20)->default('ai');
            $table->foreignId('assigned_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedInteger('unread_count')->default(0);
            $table->timestamp('last_message_at')->nullable();
            $table->text('last_message_preview')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->unique(['provider_key', 'external_thread_id']);
            $table->index(['provider_key', 'status', 'last_message_at']);
        });

        Schema::create('inbox_participants', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('conversation_id')->constrained('inbox_conversations')->cascadeOnDelete();
            $table->string('external_id', 255);
            $table->string('name');
            $table->string('handle')->nullable();
            $table->text('avatar_url')->nullable();
            $table->string('role', 30)->default('contact');
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->unique(['conversation_id', 'external_id']);
        });

        Schema::create('inbox_messages', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('conversation_id')->constrained('inbox_conversations')->cascadeOnDelete();
            $table->string('provider_message_id')->nullable();
            $table->string('direction', 20);
            $table->string('sender_type', 20);
            $table->foreignId('sender_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->longText('body')->nullable();
            $table->json('attachments')->nullable();
            $table->string('delivery_status', 30)->default('received');
            $table->decimal('ai_confidence', 5, 4)->nullable();
            $table->string('ai_source', 80)->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('received_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->unique(['conversation_id', 'provider_message_id']);
            $table->index(['conversation_id', 'created_at']);
        });

        Schema::create('inbox_assignments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('conversation_id')->constrained('inbox_conversations')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('assigned_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('assigned_at');
            $table->timestamp('ended_at')->nullable();
            $table->timestamps();
            $table->index(['conversation_id', 'ended_at']);
        });

        Schema::create('inbox_tags', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('color', 20)->default('#2563eb');
            $table->timestamps();
            $table->unique('name');
        });

        Schema::create('inbox_conversation_tag', function (Blueprint $table): void {
            $table->foreignId('conversation_id')->constrained('inbox_conversations')->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained('inbox_tags')->cascadeOnDelete();
            $table->primary(['conversation_id', 'tag_id']);
        });

        Schema::create('inbox_ai_settings', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained('ascend_companies')->nullOnDelete();
            $table->boolean('enabled')->default(true);
            $table->decimal('confidence_threshold', 5, 4)->default(0.8000);
            $table->json('handoff_keywords')->nullable();
            $table->json('sensitive_topics')->nullable();
            $table->text('system_instructions')->nullable();
            $table->timestamps();
        });

        Schema::create('inbox_events', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('conversation_id')->nullable()->constrained('inbox_conversations')->nullOnDelete();
            $table->string('provider_key', 40)->nullable();
            $table->string('event', 100);
            $table->string('request_id', 80)->nullable();
            $table->string('status', 30)->default('accepted');
            $table->json('payload')->nullable();
            $table->json('response')->nullable();
            $table->timestamps();
            $table->index(['provider_key', 'event', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inbox_events');
        Schema::dropIfExists('inbox_ai_settings');
        Schema::dropIfExists('inbox_conversation_tag');
        Schema::dropIfExists('inbox_tags');
        Schema::dropIfExists('inbox_assignments');
        Schema::dropIfExists('inbox_messages');
        Schema::dropIfExists('inbox_participants');
        Schema::dropIfExists('inbox_conversations');
    }
};
