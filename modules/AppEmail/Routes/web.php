<?php

use Illuminate\Support\Facades\Route;
use Modules\AppEmail\Livewire\EmailMarketing;
use Modules\AppEmail\Livewire\Leads;

Route::middleware(['web', 'auth', 'verified'])->group(function (): void {
    Route::livewire('/portal/email-marketing', EmailMarketing::class)->name('portal.email-marketing');
    Route::livewire('/portal/leads', Leads::class)->name('portal.leads');
});
