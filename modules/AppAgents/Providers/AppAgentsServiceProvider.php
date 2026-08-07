<?php

namespace Modules\AppAgents\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\AppAgents\Services\AgentRegistry;
use Modules\AppAgents\Services\AgentRunner;

class AppAgentsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(AgentRegistry::class);
        $this->app->singleton(AgentRunner::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');

        $this->app->booted(function (): void {
            app(AgentRegistry::class)->registerDefaults();
        });
    }
}
