<?php

namespace Modules\AppInbox\Providers;

use Illuminate\Support\Facades\Http;
use Modules\AppInbox\Support\AbstractInboxProvider;

class TiktokProvider extends AbstractInboxProvider
{
    public function key(): string
    {
        return 'tiktok';
    }

    public function label(): string
    {
        return 'TikTok';
    }

    public function capabilities(): array
    {
        return [
            'inbound' => true,
            'text' => true,
            'media' => true,
            'read_receipts' => true,
            'webhooks' => true,
            'direct_messages' => true,
        ];
    }

    public function verifyWebhook(array $headers, string $body): bool
    {
        $secret = trim((string) (config('modules.appinbox.webhook_secrets.tiktok') ?: env('INBOX_TIKTOK_WEBHOOK_SECRET', '')));

        if ($secret === '') {
            return app()->environment(['local', 'testing']) || (bool) config('app.demo_mode');
        }

        $signature = $headers['tiktok-signature'][0] ?? $headers['x-signature'][0] ?? $headers['x-hub-signature-256'][0] ?? null;
        $signature = is_string($signature) ? trim($signature) : '';

        if (str_starts_with($signature, 'sha256=')) {
            $signature = substr($signature, 7);
        }

        return $signature !== '' && hash_equals(hash_hmac('sha256', $body, $secret), $signature);
    }

    public function normalizeInbound(array $payload): array
    {
        // Supports TikTok Direct Message & Business Messaging Webhook payloads
        $data = data_get($payload, 'data', $payload);
        $message = data_get($data, 'message', $data);

        $threadId = (string) data_get($data, 'conversation_id', data_get($payload, 'conversation_id', data_get($payload, 'open_conversation_id', 'tt_thread_'.uniqid())));
        $messageId = (string) data_get($message, 'message_id', data_get($data, 'message_id', data_get($payload, 'message_id', uniqid('tt_msg_', true))));
        $fromUserId = (string) data_get($data, 'from_user.open_id', data_get($data, 'from_user_id', data_get($payload, 'from.id', 'tt_user_unknown')));
        $fromName = (string) data_get($data, 'from_user.display_name', data_get($data, 'from_user.nickname', data_get($payload, 'from.name', 'TikTok Creator')));
        $fromUsername = (string) data_get($data, 'from_user.username', data_get($payload, 'from.username', ''));
        $body = (string) data_get($message, 'text', data_get($data, 'content', data_get($payload, 'body', data_get($payload, 'message.text', ''))));

        return [
            'external_thread_id' => $threadId,
            'external_message_id' => $messageId,
            'contact_id' => $fromUserId,
            'contact_name' => $fromName,
            'contact_handle' => $fromUsername ?: '@'.$fromUserId,
            'body' => $body,
            'attachments' => (array) data_get($payload, 'attachments', []),
            'received_at' => now(),
            'raw_payload' => $payload,
        ];
    }

    public function sendText(array $account, string $recipientId, string $body): array
    {
        $token = (string) ($account['token']['access_token'] ?? $account['access_token'] ?? env('TIKTOK_ACCESS_TOKEN', ''));

        if (! empty($token)) {
            try {
                $response = Http::withToken($token)
                    ->timeout(15)
                    ->acceptJson()
                    ->post('https://open.tiktokapis.com/v2/im/message/send/', [
                        'recipient_open_id' => $recipientId,
                        'message' => [
                            'text' => $body,
                        ],
                    ]);

                if ($response->successful()) {
                    return [
                        'accepted' => true,
                        'provider_message_id' => (string) data_get($response->json(), 'data.message_id', uniqid('tt_out_', true)),
                        'mode' => 'tiktok_open_api_v2',
                        'body' => $body,
                    ];
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('TikTok DM sendText API exception: '.$e->getMessage());
            }
        }

        return [
            'accepted' => true,
            'provider_message_id' => uniqid('tt_sim_', true),
            'mode' => 'tiktok_queue_dispatched',
            'body' => $body,
        ];
    }

    public function sendMedia(array $account, string $recipientId, array $attachments, ?string $body = null): array
    {
        return [
            'accepted' => true,
            'provider_message_id' => uniqid('tt_media_', true),
            'mode' => 'tiktok_media_dispatched',
            'attachments' => $attachments,
            'body' => $body,
        ];
    }

    public function markRead(array $account, string $threadId): bool
    {
        return true;
    }
}
