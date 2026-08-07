<?php

use Illuminate\Support\Facades\Route;
use Modules\AppInbox\Http\Controllers\InboxApiController;
use Modules\AppInbox\Http\Controllers\InboxWebhookController;

Route::middleware(['api'])
    ->prefix('api/inbox/v1')
    ->name('api.inbox.')
    ->group(function (): void {
        Route::get('/conversations', [InboxApiController::class, 'index'])->name('conversations.index');
        Route::get('/conversations/{conversation}', [InboxApiController::class, 'show'])->name('conversations.show');
        Route::post('/conversations/{conversation}/messages', [InboxApiController::class, 'sendMessage'])->name('conversations.messages.store');
        Route::post('/conversations/{conversation}/assign', [InboxApiController::class, 'assign'])->name('conversations.assign');
        Route::post('/conversations/{conversation}/takeover', [InboxApiController::class, 'takeover'])->name('conversations.takeover');
        Route::post('/conversations/{conversation}/return-to-ai', [InboxApiController::class, 'returnToAi'])->name('conversations.return-to-ai');
        Route::post('/conversations/{conversation}/status', [InboxApiController::class, 'status'])->name('conversations.status');
        Route::get('/conversations/{conversation}/events', [InboxApiController::class, 'events'])->name('conversations.events');
        Route::get('/settings', [InboxApiController::class, 'settings'])->name('settings.show');
        Route::put('/settings', [InboxApiController::class, 'updateSettings'])->name('settings.update');
        Route::post('/webhooks/{provider}', InboxWebhookController::class)->withoutMiddleware('api')->name('webhooks.receive');
    });
