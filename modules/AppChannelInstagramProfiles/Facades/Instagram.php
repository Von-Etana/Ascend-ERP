<?php

namespace Modules\AppChannelInstagramProfiles\Facades;

use Illuminate\Support\Facades\Facade;
use Modules\AppChannelInstagramProfiles\Services\Instagram\InstagramApiService;

class Instagram extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return InstagramApiService::class;
    }
}
