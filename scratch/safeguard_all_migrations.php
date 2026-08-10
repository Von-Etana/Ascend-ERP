<?php

$filePath = __DIR__ . '/../database/migrations/2026_04_18_110000_create_database.php';
$code = file_get_contents($filePath);

// Regex pattern to capture: Schema::create('tablename', function (Blueprint $table) ... });
$pattern = '/Schema::create\(\s*\'([^\']+)\'\s*,\s*function\s*\([^\)]*\)\s*:\s*void\s*\{.*?\n\s*\}\);/s';

$count = 0;
$newCode = preg_replace_callback($pattern, function ($matches) use (&$count) {
    $count++;
    $tableName = $matches[1];
    $createBlock = $matches[0];
    return "if (! Schema::hasTable('$tableName')) {\n            " . str_replace("\n", "\n    ", $createBlock) . "\n        }";
}, $code);

echo "Replaced $count table creation blocks in 2026_04_18_110000_create_database.php!\n";

file_put_contents($filePath, $newCode);
