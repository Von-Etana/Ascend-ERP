<?php

use Database\Seeders\AITemplateCategorySeeder;
use Database\Seeders\AITemplateSeeder;
use Database\Seeders\StackPostsPlanSeeder;

return [
    'required_php_version' => env('INSTALLER_REQUIRED_PHP_VERSION', '8.3.0'),
    'required_extensions' => [
        'ctype',
        'curl',
        'fileinfo',
        'filter',
        'hash',
        'json',
        'mbstring',
        'openssl',
        'pdo',
        'pdo_mysql',
        'tokenizer',
        'xml',
    ],
    'writable_paths' => [
        '.env',
        'bootstrap/cache',
        'storage',
    ],
    'purchase_code_required' => env('INSTALLER_PURCHASE_CODE_REQUIRED', false),
    'purchase_verify_url' => env('INSTALLER_PURCHASE_VERIFY_URL', ''),
    'final_session_driver' => env('INSTALLER_FINAL_SESSION_DRIVER', 'database'),
    'final_cache_store' => env('INSTALLER_FINAL_CACHE_STORE', 'database'),
    'final_queue_connection' => env('INSTALLER_FINAL_QUEUE_CONNECTION', 'database'),
    'admin_plan_slug' => env('INSTALLER_ADMIN_PLAN_SLUG', ''),
    'default_seeders' => [
        StackPostsPlanSeeder::class,
        AITemplateCategorySeeder::class,
        AITemplateSeeder::class,
    ],
];
