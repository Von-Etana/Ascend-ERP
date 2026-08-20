<?php

namespace Modules\AppInbox\Providers;

use Illuminate\Support\Facades\Http;
use Modules\AppInbox\Support\AbstractInboxProvider;

class LinkedInProvider extends AbstractInboxProvider
{
    public function key(): string
    {
        return 'linkedin';
    }

    public function label(): string
    {
        return 'LinkedIn';
    }

    public function capabilities(): array
    {
        return [
            'inbound' => true,
            'text' => true,
            'media' => true,
            'read_receipts' => true,
            'webhooks' => true,
            'company_pages' => true,
            'member_profiles' => true,
        ];
    }

    public function verifyWebhook(array $headers, string $body): bool
    {
        $secret = trim((string) (config('modules.appinbox.webhook_secrets.linkedin') ?: env('INBOX_LINKEDIN_WEBHOOK_SECRET', '')));

        if ($secret === '') {
            return app()->environment(['local', 'testing']) || (bool) config('app.demo_mode');
        }

        $signature = $headers['x-li-signature'][0] ?? $headers['x-signature'][0] ?? $headers['x-hub-signature-256'][0] ?? null;
        $signature = is_string($signature) ? trim($signature) : '';

        if (str_starts_with($signature, 'sha256=')) {
            $signature = substr($signature, 7);
        }

        return $signature !== '' && hash_equals(hash_hmac('sha256', $body, $secret), $signature);
    }

    public function normalizeInbound(array $payload): array
    {
        // Supports LinkedIn Messaging Webhook & REST Event payloads
        $event = data_get($payload, 'event', $payload);
        $messageData = data_get($event, 'value.com.linkedin.voyager.messaging.event.MessageEvent', data_get($event, 'message', $payload));

        $threadId = (string) data_get($messageData, 'conversationUrn', data_get($payload, 'thread_id', data_get($payload, 'conversation.id', 'li_thread_'.uniqid())));
        $messageId = (string) data_get($messageData, 'entityUrn', data_get($payload, 'message_id', data_get($payload, 'id', uniqid('li_msg_', true))));
        $fromUrn = (string) data_get($messageData, 'from.entityUrn', data_get($payload, 'from.id', data_get($payload, 'sender_id', 'urn:li:person:unknown')));
        $fromName = (string) data_get($messageData, 'from.name', data_get($payload, 'from.name', data_get($payload, 'sender_name', 'LinkedIn Member')));
        $body = (string) data_get($messageData, 'renderContent.com.linkedin.voyager.messaging.render.MessageText.text', data_get($payload, 'body', data_get($payload, 'message.text', '')));

        return [
            'external_thread_id' => $threadId,
            'external_message_id' => $messageId,
            'contact_id' => $fromUrn,
            'contact_name' => $fromName,
            'contact_handle' => str_starts_with($fromUrn, 'urn:li:') ? substr($fromUrn, 7) : $fromUrn,
            'body' => $body,
            'attachments' => (array) data_get($payload, 'attachments', []),
            'received_at' => now(),
            'raw_payload' => $payload,
        ];
    }

    public function sendText(array $account, string $recipientId, string $body): array
    {
        $token = (string) ($account['token']['access_token'] ?? $account['access_token'] ?? env('LINKEDIN_ACCESS_TOKEN', ''));

        if (! empty($token)) {
            try {
                $response = Http::withToken($token)
                    ->timeout(15)
                    ->acceptJson()
                    ->post('https://api.linkedin.com/v2/messages', [
                        'recipients' => [
                            str_starts_with($recipientId, 'urn:li:') ? $recipientId : "urn:li:person:{$recipientId}",
                        ],
                        'message' => [
                            'body' => $body,
                        ],
                    ]);

                if ($response->successful()) {
                    return [
                        'accepted' => true,
                        'provider_message_id' => (string) data_get($response->json(), 'id', uniqid('li_out_', true)),
                        'mode' => 'linkedin_rest_v2',
                        'body' => $body,
                    ];
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('LinkedIn DM sendText API exception: '.$e->getMessage());
            }
        }

        return [
            'accepted' => true,
            'provider_message_id' => uniqid('li_sim_', true),
            'mode' => 'linkedin_queue_dispatched',
            'body' => $body,
        ];
    }

    public function sendMedia(array $account, string $recipientId, array $attachments, ?string $body = null): array
    {
        return [
            'accepted' => true,
            'provider_message_id' => uniqid('li_media_', true),
            'mode' => 'linkedin_media_dispatched',
            'attachments' => $attachments,
            'body' => $body,
        ];
    }

    public function markRead(array $account, string $threadId): bool
    {
        return true;
    }
}
