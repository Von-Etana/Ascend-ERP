<?php

$file = __DIR__ . '/../database/migrations/2026_04_18_110000_create_database.php';
$content = file_get_contents($file);

// Replace Schema::create('table_name', function ...
// with if (!Schema::hasTable('table_name')) { Schema::create('table_name', function ... }

$patched = preg_replace_callback(
    '/Schema::create\(\s*\'([^\']+)\'\s*,\s*function/i',
    function ($matches) {
        $tableName = $matches[1];
        return "if (! Schema::hasTable('$tableName')) {\n            Schema::create('$tableName', function";
    },
    $content
);

// Add closing braces for the if statements before each next table creation or end of up() method
// Let's inspect how tables end in create_database.php
file_put_contents(__DIR__ . '/patched_migration.php', $patched);
echo "Pattern matches replaced. Checking structure...\n";
