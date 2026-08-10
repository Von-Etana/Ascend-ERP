<?php

$source = 'C:/Users/DELL/Downloads/ascend logo.png';

if (!file_exists($source)) {
    die("Source logo file not found at: $source\n");
}

$targets = [
    'public/img/logo-brand-dark.png',
    'public/img/logo-brand-light.png',
    'public/img/logo-dark.png',
    'public/img/logo-light.png',
    'public/img/favicon.png',
    'public/apple-touch-icon.png',
    'resources/themes/app/default/assets/img/logo-brand-dark.png',
    'resources/themes/app/default/assets/img/logo-brand-light.png',
    'resources/themes/app/default/assets/img/logo-dark.png',
    'resources/themes/app/default/assets/img/logo-light.png',
    'resources/themes/app/default/assets/img/favicon.png',
    'resources/themes/guest/default/assets/img/logo-brand-dark.png',
    'resources/themes/guest/default/assets/img/logo-brand-light.png',
    'resources/themes/guest/default/assets/img/logo-dark.png',
    'resources/themes/guest/default/assets/img/logo-light.png',
    'resources/themes/guest/default/assets/img/favicon.png',
];

$baseDir = dirname(__DIR__);

foreach ($targets as $relPath) {
    $fullTarget = $baseDir . '/' . $relPath;
    $dir = dirname($fullTarget);
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
    if (copy($source, $fullTarget)) {
        echo "Successfully copied logo to: $relPath\n";
    } else {
        echo "FAILED copying to: $relPath\n";
    }
}

echo "Logo replacement completed!\n";
