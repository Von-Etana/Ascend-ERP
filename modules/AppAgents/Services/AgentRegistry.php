<?php

namespace Modules\AppAgents\Services;

use Illuminate\Support\Facades\Schema;
use Modules\AppAgents\Models\AgentDefinition;
use Throwable;

class AgentRegistry
{
    public function registerDefaults(): void
    {
        if (! $this->definitionsTableReady()) {
            return;
        }

        $this->upsert([
            'key' => 'inbox_reply',
            'name' => 'Inbox Reply Agent',
            'purpose' => 'Evaluate inbound social or email messages, draft useful replies, and decide when a human should take over.',
            'system_prompt' => implode("\n", [
                'You are the official Ascend Systems AI Customer Assistant agent.',
                'Provide clear, warm, accurate, and professional responses to inquiries across WhatsApp, Facebook Messenger, Instagram DM, Telegram, and Email.',
                'Utilize the provided `company_knowledge` object for verified pricing, inverter & battery specifications, warranty terms (5-Year Replacement Guarantee), Abuja/Lagos locations, and B2B wholesale discount options.',
                'Do not invent unverified pricing or promises not supported by company_knowledge or context.',
                'Escalate to a human agent for refunds, active dispute complaints, legal issues, or explicit requests for human assistance.',
                'Return strict JSON output adhering to output_schema.',
            ]),
            'tool_keys' => ['conversation_context', 'handoff_policy'],
            'output_schema' => [
                'action' => 'auto_reply|draft|handoff',
                'reply' => 'string',
                'confidence' => 'number between 0 and 1',
                'reasoning' => 'short private audit summary',
                'handoff_reason' => 'nullable string',
                'tags' => 'array of strings',
            ],
            'policy' => [
                'auto_reply_min_confidence' => 0.86,
                'draft_min_confidence' => 0.55,
            ],
            'is_active' => true,
        ]);
    }

    public function definition(string $key): ?AgentDefinition
    {
        if (! $this->definitionsTableReady()) {
            return null;
        }

        return AgentDefinition::query()
            ->where('key', $key)
            ->where('is_active', true)
            ->first();
    }

    protected function upsert(array $definition): void
    {
        AgentDefinition::query()->firstOrCreate(
            ['key' => $definition['key']],
            $definition
        );
    }

    protected function definitionsTableReady(): bool
    {
        try {
            return Schema::hasTable('agent_definitions');
        } catch (Throwable) {
            return false;
        }
    }
}
