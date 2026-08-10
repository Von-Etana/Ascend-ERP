<?php

$file = __DIR__ . '/../database/migrations/2026_04_18_110000_create_database.php';
$content = file_get_contents($file);

// Replace all ->string('foo', 255) with ->string('foo', 191)
$count = 0;
$patched = preg_replace_callback(
    '/\$table->string\(\s*\'([^\']+)\'\s*,\s*255\s*\)/i',
    function ($matches) use (&$count) {
        $count++;
        return "\$table->string('{$matches[1]}', 191)";
    },
    $content
);

file_put_contents($file, $patched);
echo "Replaced $count occurrences of string(col, 255) with string(col, 191) in 2026_04_18_110000_create_database.php!\n";
