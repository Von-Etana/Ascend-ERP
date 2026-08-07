<?php

namespace Modules\AppInbox\Providers;

use Modules\AppInbox\Support\AbstractInboxProvider;

class EmailProvider extends AbstractInboxProvider
{
    public function key(): string
    {
        return 'email';
    }

    public function label(): string
    {
        return 'Email';
    }

    public function capabilities(): array
    {
        return ['inbound' => true, 'text' => true, 'media' => true, 'read_receipts' => false, 'webhooks' => true, 'html' => true];
    }
}
