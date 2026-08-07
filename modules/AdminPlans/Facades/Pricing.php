<?php

namespace Modules\AdminPlans\Facades;

use Illuminate\Support\Facades\Facade;
use Modules\AdminPlans\Support\PricingService;

class Pricing extends Facade
{
    protected static function getFacadeAccessor()
    {
        return PricingService::class;
    }
}
