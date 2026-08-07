<?php

namespace Modules\AdminPlans\Facades;

use Illuminate\Support\Facades\Facade;
use Modules\AdminPlans\Support\PlanService;

class Plan extends Facade
{
    protected static function getFacadeAccessor()
    {
        return PlanService::class;
    }
}
