<?php

namespace Modules\AppInbox\Contracts;

interface InboxProvider
{
    public function key(): string;

    public function label(): string;

    public function capabilities(): array;

    public function verifyWebhook(array $headers, string $body): bool;

    public function normalizeInbound(array $payload): array;

    public function sendText(array $account, string $recipientId, string $body): array;

    public function sendMedia(array $account, string $recipientId, array $attachments, ?string $body = null): array;

    public function markRead(array $account, string $threadId): bool;
}
