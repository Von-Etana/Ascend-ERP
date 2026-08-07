<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('ascend:shared-queue', function () {
    $this->comment('Processing the Ascend Systems queue batch...');

    $this->call('queue:work', [
        '--queue' => env('DB_QUEUE', 'default'),
        '--stop-when-empty' => true,
        '--max-jobs' => config('queue.shared_hosting.max_jobs', 25),
        '--max-time' => config('queue.shared_hosting.max_time', 50),
        '--timeout' => config('queue.shared_hosting.timeout', 45),
        '--sleep' => config('queue.shared_hosting.sleep', 1),
        '--tries' => config('queue.shared_hosting.tries', 3),
        '--no-interaction' => true,
    ]);
})->purpose('Process a bounded queue batch for cPanel shared hosting');
