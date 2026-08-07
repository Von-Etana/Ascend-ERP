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
        return [
            'provider' => $conversation->provider_key,
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
