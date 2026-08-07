<?php

namespace Modules\AppInbox\Providers;

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
}
