<?php

namespace Modules\AppAscend\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Modules\AppAscend\Livewire\AscendModuleViewer;

class AppAscendServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'appascend');

        Livewire::component('ascend-module-viewer', AscendModuleViewer::class);

        register_user_sidebar_section('ascend', null, 1);

        register_user_sidebar_item('ascend', [
            'label' => __('Overview'),
            'route_name' => 'portal.dashboard',
            'active_when' => ['portal.dashboard'],
            'icon' => 'fa-light fa-grid-2',
            'order' => 1,
            'visible' => fn () => auth()->check(),
        ]);

        $modules = [
            ['label' => 'CRM', 'slug' => 'crm', 'icon' => 'fa-light fa-users', 'order' => 10],
            ['label' => 'Sales', 'slug' => 'sales', 'icon' => 'fa-light fa-chart-line-up', 'order' => 20],
            ['label' => 'Finance', 'slug' => 'finance', 'icon' => 'fa-light fa-circle-dollar', 'order' => 30],
            ['label' => 'Inventory', 'slug' => 'inventory', 'icon' => 'fa-light fa-boxes-stacked', 'order' => 40],
            ['label' => 'POS', 'slug' => 'pos', 'icon' => 'fa-light fa-cash-register', 'order' => 50],
            ['label' => 'Marketing', 'slug' => 'marketing', 'icon' => 'fa-light fa-bullhorn', 'order' => 60],
            ['label' => 'AI Agents', 'slug' => 'ai-agents', 'icon' => 'fa-light fa-sparkles', 'order' => 70],
            ['label' => 'Automation', 'slug' => 'automation', 'icon' => 'fa-light fa-bolt', 'order' => 80],
            ['label' => 'Tasks', 'slug' => 'tasks', 'icon' => 'fa-light fa-list-check', 'order' => 90],
            ['label' => 'Reports', 'slug' => 'reports', 'icon' => 'fa-light fa-chart-mixed', 'order' => 100],
            ['label' => 'Administration', 'slug' => 'administration', 'icon' => 'fa-light fa-shield-check', 'order' => 110],
        ];

        foreach ($modules as $module) {
            register_user_sidebar_item('ascend', [
                'label' => __($module['label']),
                'route' => url('/portal/ascend/'.$module['slug']),
                'active_when' => ['portal.ascend.module'],
                'icon' => $module['icon'],
                'order' => $module['order'],
                'visible' => fn () => auth()->check(),
            ]);
        }
    }
}
