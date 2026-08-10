<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Modules\AdminUser\Models\User;
use Modules\AppAscend\Livewire\AscendModuleViewer;
use Tests\TestCase;

class AiAgentExecutionTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_dispatch_custom_prompt_to_selected_ai_agent(): void
    {
        $user = User::factory()->create(['is_super_admin' => true]);

        Livewire::actingAs($user)
            ->test(AscendModuleViewer::class, ['moduleKey' => 'ai-agents'])
            ->set('selectedAgent', 'financial')
            ->set('agentTaskInput', 'Analyze monthly P&L variance for Q3 2026')
            ->call('runAiAgentTask')
            ->assertSee('Financial AI Analysis Output');
    }

    public function test_can_run_quick_agent_template_shortcut(): void
    {
        $user = User::factory()->create(['is_super_admin' => true]);

        Livewire::actingAs($user)
            ->test(AscendModuleViewer::class, ['moduleKey' => 'ai-agents'])
            ->call('runQuickAgentTemplate', 'content', 'content_social')
            ->assertSee('Content AI Generation Output');
    }
}
