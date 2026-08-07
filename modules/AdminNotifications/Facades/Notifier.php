<?php

namespace Modules\AdminNotifications\Facades;

use Illuminate\Support\Facades\Facade;
use Modules\AdminNotifications\Services\NotificationService;

class Notifier extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return NotificationService::class;
    }
}
