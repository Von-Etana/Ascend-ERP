<?php

use Illuminate\Support\Facades\Route;
use Modules\AppAscend\Livewire\AscendModuleViewer;

Route::middleware(['web', 'auth', 'verified'])
    ->group(function (): void {
        Route::prefix('portal/ascend')->group(function (): void {
            Route::get('/{moduleKey}', AscendModuleViewer::class)
                ->whereIn('moduleKey', ['crm', 'sales', 'finance', 'inventory', 'pos', 'marketing', 'ai-agents', 'automation', 'tasks', 'reports', 'administration', 'retailer'])
                ->name('portal.ascend.module');
        });

        // Direct navigation aliases for portal modules
        Route::get('/portal/crm', fn () => redirect()->route('portal.ascend.module', ['moduleKey' => 'crm']));
        Route::get('/portal/sales', fn () => redirect()->route('portal.ascend.module', ['moduleKey' => 'sales']));
        Route::get('/portal/finance', fn () => redirect()->route('portal.ascend.module', ['moduleKey' => 'finance']));
        Route::get('/portal/inventory', fn () => redirect()->route('portal.ascend.module', ['moduleKey' => 'inventory']));
        Route::get('/portal/pos', fn () => redirect()->route('portal.ascend.module', ['moduleKey' => 'pos']));
        Route::get('/portal/marketing', fn () => redirect()->route('portal.ascend.module', ['moduleKey' => 'marketing']));
        Route::get('/portal/ai-agents', fn () => redirect()->route('portal.ascend.module', ['moduleKey' => 'ai-agents']));
        Route::get('/portal/retailer', fn () => redirect()->route('portal.ascend.module', ['moduleKey' => 'retailer']));
    });
