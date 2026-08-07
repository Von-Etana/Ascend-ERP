<?php

namespace Modules\AppInbox\Support;

use Modules\AppInbox\Contracts\InboxProvider;

abstract class AbstractInboxProvider implements InboxProvider
{
    public function capabilities(): array
    {
        return ['inbound' => true, 'text' => true, 'media' => true, 'read_receipts' => true, 'webhooks' => true];
    }

    public function verifyWebhook(array $headers, string $body): bool
    {
        $secret = trim((string) config('modules.appinbox.webhook_secrets.'.$this->key(), ''));

        if ($secret === '') {
            return app()->environment(['local', 'testing']) || (bool) config('app.demo_mode');
        }

        $provided = $headers['x-inbox-signature'][0] ?? $headers['x-signature'][0] ?? null;
        $provided = is_string($provided) ? trim($provided) : '';

        if (str_starts_with($provided, 'sha256=')) {
            $provided = substr($provided, 7);
        }

        return $provided !== '' && hash_equals(hash_hmac('sha256', $body, $secret), $provided);
    }

    public function normalizeInbound(array $payload): array
    {
        return [
            'external_thread_id' => (string) data_get($payload, 'thread_id', data_get($payload, 'conversation.id', 'demo-thread')),
            'external_message_id' => (string) data_get($payload, 'message_id', data_get($payload, 'message.id', uniqid('inbox_', true))),
            'contact_id' => (string) data_get($payload, 'contact.id', data_get($payload, 'from.id', 'demo-contact')),
            'contact_name' => (string) data_get($payload, 'contact.name', data_get($payload, 'from.name', 'New contact')),
            'contact_handle' => data_get($payload, 'contact.handle', data_get($payload, 'from.username')),
            'body' => (string) data_get($payload, 'body', data_get($payload, 'message.text', '')),
            'attachments' => (array) data_get($payload, 'attachments', []),
            'received_at' => now(),
            'raw_payload' => $payload,
        ];
    }

    public function sendText(array $account, string $recipientId, string $body): array
    {
        return ['accepted' => true, 'provider_message_id' => null, 'mode' => 'adapter_pending', 'body' => $body];
    }

    public function sendMedia(array $account, string $recipientId, array $attachments, ?string $body = null): array
    {
        return ['accepted' => true, 'provider_message_id' => null, 'mode' => 'adapter_pending', 'attachments' => $attachments, 'body' => $body];
    }

    public function markRead(array $account, string $threadId): bool
    {
        return true;
    }
}
