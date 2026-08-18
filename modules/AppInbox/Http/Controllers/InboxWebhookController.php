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
use Modules\AppInbox\Support\WhatsAppMenuFlowService;
use Symfony\Component\HttpFoundation\Response;

class InboxWebhookController
{
    public function __construct(
        protected InboxProviderRegistry $providers,
        protected InboxResponsePolicy $policy,
        protected InboxAiResponder $ai,
        protected AutomationWebhookDispatcher $automation,
        protected WhatsAppMenuFlowService $whatsappFlow,
    ) {}

    public function __invoke(Request $request, string $provider): Response
    {
        // 1. Handle Meta Webhook Verification (GET hub.mode=subscribe)
        if ($request->isMethod('GET')) {
            $mode = $request->query('hub_mode', $request->query('hub.mode'));
            $token = $request->query('hub_verify_token', $request->query('hub.verify_token'));
            $challenge = $request->query('hub_challenge', $request->query('hub.challenge'));

            $expectedToken = config("modules.appinbox.webhook_secrets.{$provider}")
                ?: config('modules.appinbox.whatsapp_bot.verify_token')
                ?: env('WHATSAPP_VERIFY_TOKEN', 'ascend_webhook_secret');

            if ($mode === 'subscribe' && $token === $expectedToken) {
                return response((string) $challenge, 200)->header('Content-Type', 'text/plain');
            }

            return response()->json(['error' => 'Forbidden'], 403);
        }

        // 2. Inbound Webhook Payload Processing (POST)
        $adapter = $this->providers->get($provider);
        $raw = (string) $request->getContent();
        abort_unless($adapter->verifyWebhook($request->headers->all(), $raw), 401, 'Invalid inbox webhook signature.');

        $normalized = $adapter->normalizeInbound($request->all());

        $conversation = InboxConversation::query()->firstOrCreate(
            ['provider_key' => $provider, 'external_thread_id' => $normalized['external_thread_id']],
            [
                'contact_name' => $normalized['contact_name'],
                'contact_handle' => $normalized['contact_handle'],
                'status' => 'open',
                'handling_mode' => $this->policy->requiresHuman($normalized['body']) ? 'human' : 'ai',
            ]
        );

        $message = $conversation->messages()->firstOrCreate(
            ['provider_message_id' => $normalized['external_message_id']],
            [
                'direction' => 'inbound',
                'sender_type' => 'contact',
                'body' => $normalized['body'],
                'attachments' => $normalized['attachments'],
                'delivery_status' => 'received',
                'received_at' => $normalized['received_at'],
                'metadata' => ['raw_payload' => $normalized['raw_payload']],
            ]
        );

        $conversation->update([
            'unread_count' => (int) $conversation->unread_count + 1,
            'last_message_at' => $message->received_at ?: now(),
            'last_message_preview' => $message->body,
            'handling_mode' => $this->policy->requiresHuman($message->body) ? 'human' : $conversation->handling_mode,
        ]);
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

        $aiMessage = null;

        // 3. Check WhatsApp Bot Menu / Qualification Flow (Native Ascend Bot)
        $isWhatsApp = $provider === 'whatsapp';
        $whatsappBotEnabled = (bool) config('modules.appinbox.whatsapp_bot.enabled', true);
        $phone = (string) ($normalized['contact_id'] ?: $normalized['external_thread_id']);

        if ($isWhatsApp && $whatsappBotEnabled && $conversation->handling_mode !== 'human' && $this->whatsappFlow->isMenuTrigger($phone, (string) $message->body)) {
            $flowResult = $this->whatsappFlow->handle($phone, (string) $message->body);
            $replyText = $flowResult['reply'] ?? '';

            if (! empty($replyText)) {
                $delivery = $adapter->sendText(
                    account: $conversation->account?->toArray() ?? [],
                    recipientId: (string) ($conversation->contact_handle ?: $conversation->external_thread_id),
                    body: $replyText,
                );

                $aiMessage = $conversation->messages()->create([
                    'provider_message_id' => $delivery['provider_message_id'] ?? null,
                    'direction' => 'outbound',
                    'sender_type' => 'ai',
                    'body' => $replyText,
                    'attachments' => [],
                    'delivery_status' => ($delivery['accepted'] ?? false) ? 'sent' : 'delivered',
                    'ai_confidence' => 1.0,
                    'ai_source' => 'bot:whatsapp_menu_flow',
                    'sent_at' => now(),
                    'metadata' => [
                        'menu_state' => $flowResult['session']['state'] ?? null,
                        'lead_complete' => $flowResult['lead_complete'] ?? false,
                        'lead_id' => $flowResult['lead']->id ?? null,
                        'delivery' => $delivery,
                    ],
                ]);

                $conversation->forceFill([
                    'last_message_at' => now(),
                    'last_message_preview' => $replyText,
                ])->save();
            }

            if (! empty($flowResult['handoff'])) {
                $conversation->forceFill(['handling_mode' => 'human'])->save();
            }
        } else {
            // 4. Fallback to Generative AI / Agent Runner
            $aiMessage = $this->ai->handleInbound($conversation->fresh(['account', 'messages']), $message);
        }

        return response()->json([
            'ok' => true,
            'event_id' => (string) Str::uuid(),
            'conversation_id' => $conversation->id,
            'message_id' => $message->id,
            'ai_message_id' => $aiMessage?->id,
        ]);
    }
}

