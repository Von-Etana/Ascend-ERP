<?php

namespace Modules\AppChannelFacebookPages\Facades;

use Illuminate\Support\Facades\Facade;
use Modules\AppChannelFacebookPages\Services\Facebook\FacebookApiService;

class Facebook extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return FacebookApiService::class;
    }
}
