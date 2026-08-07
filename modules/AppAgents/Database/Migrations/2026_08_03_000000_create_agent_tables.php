<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agent_definitions', function (Blueprint $table): void {
            $table->id();
            $table->string('key', 80)->unique();
            $table->string('name');
            $table->string('purpose', 255);
            $table->text('system_prompt');
            $table->json('tool_keys')->nullable();
            $table->json('output_schema')->nullable();
            $table->json('policy')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('agent_runs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('agent_definition_id')->nullable()->constrained('agent_definitions')->nullOnDelete();
            $table->string('agent_key', 80);
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('team_id')->nullable()->constrained('teams')->nullOnDelete();
            $table->string('status', 30)->default('pending');
            $table->string('source_type', 120)->nullable();
            $table->unsignedBigInteger('source_id')->nullable();
            $table->json('input')->nullable();
            $table->json('context')->nullable();
            $table->json('output')->nullable();
            $table->decimal('confidence', 5, 4)->nullable();
            $table->string('handoff_reason')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->timestamps();
            $table->index(['agent_key', 'status', 'created_at']);
            $table->index(['source_type', 'source_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agent_runs');
        Schema::dropIfExists('agent_definitions');
    }
};
