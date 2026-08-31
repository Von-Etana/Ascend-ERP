<?php

namespace Modules\AppInbox\Support;

use Modules\AppAgents\Services\AgentRunner;
use Modules\AppAutomation\Services\AutomationWebhookDispatcher;
use Modules\AppInbox\Models\InboxConversation;
use Modules\AppInbox\Models\InboxMessage;
use Throwable;

class InboxAiResponder
{
    public function __construct(
        protected AgentRunner $agents,
        protected InboxProviderRegistry $providers,
        protected InboxResponsePolicy $policy,
        protected AutomationWebhookDispatcher $automation,
    ) {}

    public function handleInbound(InboxConversation $conversation, InboxMessage $message): ?InboxMessage
    {
        if (! (bool) config('modules.appinbox.ai.enabled', true)) {
            return null;
        }

        if ($conversation->handling_mode !== 'ai') {
            return null;
        }

        if ($this->policy->requiresHuman((string) $message->body)) {
            $conversation->forceFill(['handling_mode' => 'human'])->save();
            $this->emit($conversation, 'inbox.conversation.handed_to_human', [
                'reason' => 'policy_keyword',
                'message_id' => $message->id,
            ]);

            return $this->draftFallback($conversation, $message, 'policy_keyword');
        }

        try {
            $result = $this->agents->run('inbox_reply', $this->agentInput($conversation, $message), [
                'source_type' => InboxMessage::class,
                'source_id' => $message->id,
                'provider_key' => $conversation->provider_key,
                'conversation_id' => $conversation->id,
            ]);

            return $this->applyAgentOutput($conversation, $message, $result);
        } catch (Throwable $exception) {
            $conversation->forceFill(['handling_mode' => 'human'])->save();
            $this->emit($conversation, 'inbox.conversation.handed_to_human', [
                'reason' => 'ai_failure',
                'error' => $exception->getMessage(),
                'message_id' => $message->id,
            ]);

            return $this->draftFallback($conversation, $message, 'ai_failure', $exception->getMessage());
        }
    }

    protected function applyAgentOutput(InboxConversation $conversation, InboxMessage $inboundMessage, array $result): ?InboxMessage
    {
        $output = (array) ($result['output'] ?? []);
        $confidence = (float) ($output['confidence'] ?? 0);
        $reply = trim((string) ($output['reply'] ?? ''));
        $handoffReason = $output['handoff_reason'] ?? null;
        $action = (string) ($output['action'] ?? 'draft');

        if ($reply === '') {
            $action = 'handoff';
            $handoffReason = $handoffReason ?: 'empty_reply';
        }

        if ($this->policy->requiresHuman($reply, $confidence) || $action === 'handoff') {
            $conversation->forceFill(['handling_mode' => 'human'])->save();
            $this->emit($conversation, 'inbox.conversation.handed_to_human', [
                'reason' => $handoffReason ?: 'low_confidence',
                'confidence' => $confidence,
                'agent_run_id' => $result['run_id'] ?? null,
                'message_id' => $inboundMessage->id,
            ]);

            return $this->createAiMessage($conversation, $reply, 'draft', $confidence, $result, $handoffReason ?: 'low_confidence');
        }

        if ($action !== 'auto_reply') {
            return $this->createAiMessage($conversation, $reply, 'draft', $confidence, $result, null);
        }

        $adapter = $this->providers->get((string) $conversation->provider_key);
        $delivery = $adapter->sendText(
            account: $conversation->account?->toArray() ?? [],
            recipientId: (string) ($conversation->contact_handle ?: $conversation->external_thread_id),
            body: $reply,
        );

        $message = $this->createAiMessage(
            conversation: $conversation,
            reply: $reply,
            deliveryStatus: ($delivery['accepted'] ?? false) ? 'sent' : 'failed',
            confidence: $confidence,
            result: $result,
            handoffReason: null,
            providerMessageId: $delivery['provider_message_id'] ?? null,
            delivery: $delivery,
        );

        $this->emit($conversation, ($delivery['accepted'] ?? false) ? 'inbox.message.sent' : 'inbox.message.delivery_failed', [
            'message_id' => $message->id,
            'agent_run_id' => $result['run_id'] ?? null,
            'confidence' => $confidence,
            'delivery' => $delivery,
        ]);

        if (! ($delivery['accepted'] ?? false)) {
            $conversation->forceFill(['handling_mode' => 'human'])->save();
        }

        return $message;
    }

    protected function createAiMessage(
        InboxConversation $conversation,
        string $reply,
        string $deliveryStatus,
        float $confidence,
        array $result,
        ?string $handoffReason,
        ?string $providerMessageId = null,
        array $delivery = [],
    ): InboxMessage {
        $message = $conversation->messages()->create([
            'provider_message_id' => $providerMessageId,
            'direction' => 'outbound',
            'sender_type' => 'ai',
            'body' => $reply,
            'attachments' => [],
            'delivery_status' => $deliveryStatus,
            'ai_confidence' => $confidence,
            'ai_source' => 'agent:inbox_reply',
            'sent_at' => $deliveryStatus === 'sent' ? now() : null,
            'metadata' => [
                'agent_run_id' => $result['run_id'] ?? null,
                'agent_output' => $result['output'] ?? [],
                'handoff_reason' => $handoffReason,
                'delivery' => $delivery,
            ],
        ]);

        $conversation->forceFill([
            'last_message_at' => now(),
            'last_message_preview' => $reply,
        ])->save();

        return $message;
    }

    protected function draftFallback(InboxConversation $conversation, InboxMessage $message, string $reason, ?string $error = null): InboxMessage
    {
        return $this->createAiMessage($conversation, 'AI paused this conversation for human review.', 'draft', 0, [
            'run_id' => null,
            'output' => [
                'action' => 'handoff',
                'reply' => 'AI paused this conversation for human review.',
                'confidence' => 0,
                'handoff_reason' => $reason,
                'reasoning' => $error,
            ],
        ], $reason);
    }

    protected function agentInput(InboxConversation $conversation, InboxMessage $message): array
    {
        $products = [];
        if (class_exists(\App\Models\InventoryProduct::class) && \Illuminate\Support\Facades\Schema::hasTable('inventory_products')) {
            $products = \App\Models\InventoryProduct::query()
                ->select(['sku', 'name', 'category', 'unit_price', 'wholesale_price', 'stock_quantity', 'location'])
                ->take(15)
                ->get()
                ->toArray();
        }

        return [
            'provider' => $conversation->provider_key,
            'company_knowledge' => [
                'company_name' => 'Ascend Systems',
                'website' => 'https://www.ascendsystems.ng',
                'tagline' => 'Clean Energy, Microgrid & Smart Power Hardware Technologies',
                'locations' => [
                    'Abuja HQ' => 'Plot 402 Maitama District, Abuja HQ Region',
                    'Lagos Hub' => 'Lekki Phase 1 Commercial Gateway, Lagos',
                ],
                'contact' => [
                    'email' => 'sales@ascendsystems.ng',
                    'phone' => '+234 803 000 1122',
                ],
                'warranty_policy' => '5-Year Direct Manufacturer Replacement Guarantee on all Hybrid Inverters and LiFePO4 Lithium Batteries.',
                'financing_options' => 'Flexible Net 30 credit terms for approved B2B wholesale partners and zero-down installment payment plans.',
                'featured_products_catalog' => $products,
                'standard_system_packages' => [
                    [
                        'name' => 'Ascend 5.5kVA Hybrid Solar System',
                        'components' => '5.5kVA Hybrid Inverter + 10.2kWh LiFePO4 Lithium Battery + 6x 550W Mono Panels',
                        'retail_price_ngn' => 2030000.00,
                        'wholesale_price_ngn' => 1745000.00,
                        'ideal_for' => '3-4 bedroom homes, powering ACs, refrigerators, water pumps, laptops, and lighting.',
                    ],
                    [
                        'name' => 'Ascend 10.2kVA Commercial Dual MPPT System',
                        'components' => '10.2kVA Dual MPPT Hybrid Inverter + 2x 10.2kWh LiFePO4 Batteries + 12x 550W Mono Panels',
                        'retail_price_ngn' => 4500000.00,
                        'wholesale_price_ngn' => 3825000.00,
                        'ideal_for' => 'Commercial offices, agro farms, petrol stations, and health clinics.',
                    ],
                ],
            ],
            'contact' => [
                'name' => $conversation->contact_name,
                'handle' => $conversation->contact_handle,
            ],
            'latest_message' => [
                'body' => $message->body,
                'attachments' => $message->attachments ?? [],
                'received_at' => $message->received_at?->toIso8601String(),
            ],
            'conversation' => $conversation->messages()
                ->latest('id')
                ->limit(8)
                ->get(['direction', 'sender_type', 'body', 'created_at'])
                ->reverse()
                ->values()
                ->all(),
        ];
    }

    protected function emit(InboxConversation $conversation, string $event, array $payload = []): void
    {
        $this->automation->dispatchGeneric(
            event: $event,
            userId: $conversation->account?->created_by_user_id,
            teamId: null,
            payload: $payload + [
                'conversation_id' => $conversation->id,
                'provider_key' => $conversation->provider_key,
                'occurred_at' => now()->toIso8601String(),
            ],
        );
    }
}
