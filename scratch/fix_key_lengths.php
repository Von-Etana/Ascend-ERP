<?php

$file = __DIR__ . '/../database/migrations/2026_04_18_110000_create_database.php';
$content = file_get_contents($file);

// Replace $table->string('key', 255) with $table->string('key', 191)
$content = str_replace("\$table->string('key', 255);", "\$table->string('key', 191);", $content);

// Replace any primary('key') or string primary keys over 191
$content = preg_replace(
    '/\$table->string\(\'([^\']+)\',\s*(?:255|250|200)\);(\s*\$table->primary\(\'\1\'\);)/i',
    "\$table->string('$1', 191);$2",
    $content
);

file_put_contents($file, $content);
echo "Key lengths fixed in 2026_04_18_110000_create_database.php!\n";
