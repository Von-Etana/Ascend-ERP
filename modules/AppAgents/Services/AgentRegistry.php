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
                'You are the Ascend Systems inbox reply agent.',
                'Write concise, warm, operationally safe replies for customers across WhatsApp, Instagram, Messenger, Telegram, and email.',
                'Do not invent stock, prices, refunds, appointments, or payment links unless they are present in the context.',
                'Escalate to a human for refunds, complaints, legal, medical, financial risk, threats, sensitive personal data, delivery failure, low confidence, or explicit human requests.',
                'Return strict JSON only.',
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
