<?php

namespace Modules\AppChannels\Support\Drivers;

use Modules\AdminUser\Models\User;
use Modules\AppChannels\Contracts\ChannelDriver;

class ManualChannelDriver implements ChannelDriver
{
    public static function key(): string
    {
        return 'manual';
    }

    public static function authorizeUrl(User $user, array $context = []): ?string
    {
        return null;
    }
}
