<?php

namespace Modules\AdminNotifications\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\AdminNotifications\Facades\Notifier;
use Modules\AdminNotifications\Models\Notification;
use Modules\AdminNotifications\Models\NotificationManual;
use Modules\AdminNotifications\Services\NotificationService;

class AdminNotificationsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'modules.adminnotifications');
        $this->app->singleton(NotificationService::class, NotificationService::class);

        if (! class_exists('Notifier')) {
            class_alias(Notifier::class, 'Notifier');
        }
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'adminnotifications');

        register_sidebar_item('main', [
            'label' => 'Notifications',
            'route_name' => 'admin-notifications.index',
            'active_when' => ['admin-notifications.*'],
            'icon' => 'fa-light fa-bell',
            'order' => 17,
        ]);

        register_header_item([
            'view' => 'adminnotifications::partials.header-item-admin',
            'position' => 'end',
            'order' => 25,
        ]);

        register_header_item([
            'view' => 'adminnotifications::partials.header-item',
            'position' => 'end',
            'order' => 25,
        ], 'user');

        register_admin_dashboard_item('admin-notifications.snapshot', [
            'title' => 'Notifications',
            'view' => 'adminnotifications::dashboard.snapshot',
            'width' => 'full',
            'order' => 70,
            'data' => fn () => [
                'metrics' => [
                    'campaigns' => NotificationManual::query()->count(),
                    'global' => NotificationManual::query()->where('is_global', true)->count(),
                    'deliveries' => Notification::query()->count(),
                    'unread' => Notification::query()->whereNull('read_at')->count(),
                ],
                'route' => route('admin-notifications.index'),
            ],
        ]);
    }
}
