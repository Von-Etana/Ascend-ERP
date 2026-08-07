<?php

namespace Modules\AppInbox\Providers;

use Modules\AppInbox\Support\AbstractInboxProvider;

class InstagramProvider extends AbstractInboxProvider
{
    public function key(): string
    {
        return 'instagram';
    }

    public function label(): string
    {
        return 'Instagram';
    }
}
