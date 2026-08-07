<?php

namespace Modules\AppInbox\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Modules\AppAutomation\Services\AutomationWebhookDispatcher;
use Modules\AppInbox\Models\InboxConversation;
use Modules\AppInbox\Support\InboxAiResponder;
use Modules\AppInbox\Support\InboxProviderRegistry;
use Modules\AppInbox\Support\InboxResponsePolicy;

class InboxWebhookController
{
    public function __construct(
        protected InboxProviderRegistry $providers,
        protected InboxResponsePolicy $policy,
        protected InboxAiResponder $ai,
        protected AutomationWebhookDispatcher $automation,
    ) {}

    public function __invoke(Request $request, string $provider): JsonResponse
    {
        $adapter = $this->providers->get($provider);
        $raw = (string) $request->getContent();
        abort_unless($adapter->verifyWebhook($request->headers->all(), $raw), 401, 'Invalid inbox webhook signature.');
        $normalized = $adapter->normalizeInbound($request->all());
        $conversation = InboxConversation::query()->firstOrCreate(['provider_key' => $provider, 'external_thread_id' => $normalized['external_thread_id']], ['contact_name' => $normalized['contact_name'], 'contact_handle' => $normalized['contact_handle'], 'status' => 'open', 'handling_mode' => $this->policy->requiresHuman($normalized['body']) ? 'human' : 'ai']);
        $message = $conversation->messages()->firstOrCreate(['provider_message_id' => $normalized['external_message_id']], ['direction' => 'inbound', 'sender_type' => 'contact', 'body' => $normalized['body'], 'attachments' => $normalized['attachments'], 'delivery_status' => 'received', 'received_at' => $normalized['received_at'], 'metadata' => ['raw_payload' => $normalized['raw_payload']]]);
        $conversation->update(['unread_count' => (int) $conversation->unread_count + 1, 'last_message_at' => $message->received_at ?: now(), 'last_message_preview' => $message->body, 'handling_mode' => $this->policy->requiresHuman($message->body) ? 'human' : $conversation->handling_mode]);
        $conversation->loadMissing('account');

        if ($conversation->wasRecentlyCreated) {
            $this->automation->dispatchGeneric('inbox.conversation.created', $conversation->account?->created_by_user_id, null, [
                'conversation_id' => $conversation->id,
                'provider_key' => $conversation->provider_key,
                'contact_name' => $conversation->contact_name,
            ]);
        }

        $this->automation->dispatchGeneric('inbox.message.received', $conversation->account?->created_by_user_id, null, [
            'conversation_id' => $conversation->id,
            'message_id' => $message->id,
            'provider_key' => $conversation->provider_key,
            'body' => $message->body,
        ]);

        $aiMessage = $this->ai->handleInbound($conversation->fresh(['account', 'messages']), $message);

        return response()->json(['ok' => true, 'event_id' => (string) Str::uuid(), 'conversation_id' => $conversation->id, 'message_id' => $message->id, 'ai_message_id' => $aiMessage?->id]);
    }
}
