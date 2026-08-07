<?php

namespace Modules\AppInbox\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\AppInbox\Support\InboxAiResponder;
use Modules\AppInbox\Support\InboxProviderRegistry;
use Modules\AppInbox\Support\InboxResponsePolicy;
use Modules\AppInbox\Support\InboxService;

class AppInboxServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'modules.appinbox');
        $this->app->singleton(InboxProviderRegistry::class);
        $this->app->singleton(InboxService::class);
        $this->app->singleton(InboxResponsePolicy::class);
        $this->app->singleton(InboxAiResponder::class);
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
        $this->loadRoutesFrom(__DIR__.'/../Routes/api.php');
        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'appinbox');

        register_user_sidebar_item('marketing', [
            'label' => __('Inbox'),
            'route_name' => 'portal.inbox',
            'active_when' => ['portal.inbox', 'api.inbox.*'],
            'icon' => 'fa-light fa-inbox',
            'order' => 5,
            'visible' => fn () => auth()->check(),
        ]);

        $registry = app(InboxProviderRegistry::class);
        $registry->registerDefaults();
    }
}
