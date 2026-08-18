<?php

namespace Modules\AppInbox\Providers;

use Modules\AppAscend\Services\WhatsAppNotificationService;
use Modules\AppInbox\Support\AbstractInboxProvider;

class WhatsAppProvider extends AbstractInboxProvider
{
    public function key(): string
    {
        return 'whatsapp';
    }

    public function label(): string
    {
        return 'WhatsApp';
    }

    public function verifyWebhook(array $headers, string $body): bool
    {
        $secret = trim((string) config('modules.appinbox.webhook_secrets.whatsapp', env('WHATSAPP_APP_SECRET', '')));

        if ($secret === '') {
            return app()->environment(['local', 'testing']) || (bool) config('app.demo_mode', true);
        }

        // Meta WhatsApp webhooks send X-Hub-Signature-256: sha256=<hash>
        $provided = $headers['x-hub-signature-256'][0] ?? $headers['x-inbox-signature'][0] ?? $headers['x-signature'][0] ?? null;
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
        // 1. Check for standard Meta WhatsApp Cloud API payload format
        $entry = $payload['entry'][0] ?? null;
        $change = $entry['changes'][0] ?? null;
        $value = $change['value'] ?? null;
        $message = $value['messages'][0] ?? null;

        if ($message) {
            $from = (string) ($message['from'] ?? '');
            $contact = $value['contacts'][0] ?? [];
            $name = (string) ($contact['profile']['name'] ?? 'WhatsApp User');
            $body = '';

            if (isset($message['text']['body'])) {
                $body = (string) $message['text']['body'];
            } elseif (isset($message['interactive']['button_reply']['title'])) {
                $body = (string) $message['interactive']['button_reply']['title'];
            } elseif (isset($message['interactive']['list_reply']['title'])) {
                $body = (string) $message['interactive']['list_reply']['title'];
            } elseif (isset($message['type']) && $message['type'] !== 'text') {
                $body = '[' . ucfirst((string) $message['type']) . ']';
            }

            return [
                'external_thread_id' => $from ?: 'whatsapp-thread',
                'external_message_id' => (string) ($message['id'] ?? uniqid('wa_msg_', true)),
                'contact_id' => $from ?: 'whatsapp-contact',
                'contact_name' => $name,
                'contact_handle' => $from ? "+{$from}" : null,
                'body' => $body,
                'attachments' => [],
                'received_at' => now(),
                'raw_payload' => $payload,
            ];
        }

        // 2. Fallback to generic normalized structure (from AbstractInboxProvider)
        return parent::normalizeInbound($payload);
    }

    public function sendText(array $account, string $recipientId, string $body): array
    {
        $cleanRecipient = preg_replace('/[^0-9]/', '', $recipientId);
        $waService = app(WhatsAppNotificationService::class);
        $result = $waService->sendMessage($cleanRecipient, $body);

        return [
            'accepted' => (bool) ($result['success'] ?? false),
            'provider_message_id' => $result['data']['messages'][0]['id'] ?? null,
            'mode' => $result['status'] ?? 'delivered',
            'body' => $body,
            'raw' => $result,
        ];
    }
}

