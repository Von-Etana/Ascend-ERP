<?php

namespace Modules\AppEmail\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\AppEmail\Services\NewsletterGenerationService;
use Modules\AppEmail\Services\ResendEmailService;

class AppEmailServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'modules.appemail');
        $this->app->singleton(ResendEmailService::class);
        $this->app->singleton(NewsletterGenerationService::class);
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
        $this->loadRoutesFrom(__DIR__.'/../Routes/api.php');
        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'appemail');

        register_user_sidebar_item('marketing', [
            'label' => __('Email Marketing'),
            'route_name' => 'portal.email-marketing',
            'active_when' => ['portal.email-marketing'],
            'icon' => 'fa-light fa-envelope-open-text',
            'order' => 20,
            'visible' => fn () => auth()->check(),
        ]);

        register_user_sidebar_item('marketing', [
            'label' => __('Leads'),
            'route_name' => 'portal.leads',
            'active_when' => ['portal.leads'],
            'icon' => 'fa-light fa-user-plus',
            'order' => 25,
            'visible' => fn () => auth()->check(),
        ]);
    }
}
