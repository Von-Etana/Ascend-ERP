<?php

namespace Modules\AppInbox\Providers;

use Modules\AppInbox\Support\AbstractInboxProvider;

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
}
