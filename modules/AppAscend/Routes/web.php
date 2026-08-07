<?php

use Illuminate\Support\Facades\Route;
use Modules\AppAscend\Http\Controllers\AscendModuleController;

Route::middleware(['web', 'auth', 'verified'])
    ->prefix('portal/ascend')
    ->group(function (): void {
        Route::get('/{module}', AscendModuleController::class)
            ->whereIn('module', ['crm', 'sales', 'finance', 'inventory', 'pos', 'marketing', 'ai-agents', 'automation', 'tasks', 'reports', 'administration'])
            ->name('portal.ascend.module');
    });
