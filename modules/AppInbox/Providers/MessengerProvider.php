<?php

namespace Modules\AppInbox\Providers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Modules\AppInbox\Support\AbstractInboxProvider;
use Throwable;

class MessengerProvider extends AbstractInboxProvider
{
    public function key(): string
    {
        return 'messenger';
    }

    public function label(): string
    {
        return 'Messenger';
    }

    public function verifyWebhook(array $headers, string $body): bool
    {
        $secret = trim((string) config('modules.appinbox.webhook_secrets.messenger', env('META_APP_SECRET', '')));

        if ($secret === '') {
            return app()->environment(['local', 'testing']) || (bool) config('app.demo_mode', true);
        }

        $provided = $headers['x-hub-signature-256'][0] ?? $headers['x-signature'][0] ?? null;
        $provided = is_string($provided) ? trim($provided) : '';

        if (str_starts_with($provided, 'sha256=')) {
            $provided = substr($provided, 7);
        }

        if ($provided !== '') {
            return hash_equals(hash_hmac('sha256', $body, $secret), $provided);
        }

        return app()->environment(['local', 'testing']) || (bool) config('app.demo_mode', true);
    }

    public function normalizeInbound(array $payload): array
    {
        // Meta Messenger Graph API format
        $entry = $payload['entry'][0] ?? null;
        $messaging = $entry['messaging'][0] ?? null;

        if ($messaging && isset($messaging['message'])) {
            $senderId = (string) ($messaging['sender']['id'] ?? 'fb_user');
            $messageId = (string) ($messaging['message']['mid'] ?? uniqid('fb_msg_', true));
            $text = (string) ($messaging['message']['text'] ?? '');

            return [
                'external_thread_id' => $senderId,
                'external_message_id' => $messageId,
                'contact_id' => $senderId,
                'contact_name' => 'Facebook User',
                'contact_handle' => $senderId,
                'body' => $text,
                'attachments' => [],
                'received_at' => now(),
                'raw_payload' => $payload,
            ];
        }

        return parent::normalizeInbound($payload);
    }

    public function sendText(array $account, string $recipientId, string $body): array
    {
        $token = trim((string) (
            $account['token'] ??
            config('modules.appinbox.meta_page_access_token') ?:
            env('META_PAGE_ACCESS_TOKEN') ?:
            env('FACEBOOK_PAGE_ACCESS_TOKEN', '')
        ));

        if ($token === '') {
            return [
                'accepted' => true,
                'provider_message_id' => 'mock_fb_'.uniqid(),
                'mode' => 'simulated_or_pending_token',
                'body' => $body,
            ];
        }

        try {
            $response = Http::timeout(10)
                ->withToken($token)
                ->post('https://graph.facebook.com/v21.0/me/messages', [
                    'recipient' => ['id' => $recipientId],
                    'message' => ['text' => $body],
                ]);

            return [
                'accepted' => $response->successful(),
                'provider_message_id' => data_get($response->json(), 'message_id'),
                'mode' => 'meta_graph_api',
                'body' => $body,
                'raw' => $response->json(),
            ];
        } catch (Throwable $e) {
            Log::error('Messenger Send Error: '.$e->getMessage());
            return [
                'accepted' => false,
                'error' => $e->getMessage(),
                'body' => $body,
            ];
        }
    }
}
