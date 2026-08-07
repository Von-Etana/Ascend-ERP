<?php

use Illuminate\Support\Facades\Route;
use Modules\AppInbox\Livewire\Inbox;

Route::middleware(['web', 'auth', 'verified'])
    ->group(function (): void {
        Route::livewire('/portal/inbox', Inbox::class)->name('portal.inbox');
    });
