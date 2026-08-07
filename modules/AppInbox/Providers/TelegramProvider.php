<?php

namespace Modules\AppInbox\Providers;

use Modules\AppInbox\Support\AbstractInboxProvider;

class TelegramProvider extends AbstractInboxProvider
{
    public function key(): string
    {
        return 'telegram';
    }

    public function label(): string
    {
        return 'Telegram';
    }
}
