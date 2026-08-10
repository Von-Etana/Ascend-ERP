<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    DB::table('options')->updateOrInsert(
        ['key' => 'website_logo_brand_dark'],
        ['value' => 'public/img/logo-brand-dark.png']
    );
    DB::table('options')->updateOrInsert(
        ['key' => 'website_logo_brand_light'],
        ['value' => 'public/img/logo-brand-light.png']
    );
    DB::table('options')->updateOrInsert(
        ['key' => 'website_logo_dark'],
        ['value' => 'public/img/logo-dark.png']
    );
    DB::table('options')->updateOrInsert(
        ['key' => 'website_logo_light'],
        ['value' => 'public/img/logo-light.png']
    );
    DB::table('options')->updateOrInsert(
        ['key' => 'website_title'],
        ['value' => 'Ascend AI ERP']
    );
    echo "Options table updated with Ascend AI ERP logo and title!\n";
} catch (\Throwable $e) {
    echo "Options table notice: " . $e->getMessage() . "\n";
}
