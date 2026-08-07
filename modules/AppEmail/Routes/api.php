<?php

use Illuminate\Support\Facades\Route;
use Modules\AppEmail\Http\Controllers\ResendWebhookController;

Route::middleware('api')->post('/api/email/v1/webhooks/resend', ResendWebhookController::class)->name('api.email.webhooks.resend');
