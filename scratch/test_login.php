<?php

$cookieFile = __DIR__ . '/cookie.txt';
if (file_exists($cookieFile)) unlink($cookieFile);

// Step 1: GET login page
$ch = curl_init('https://app.ascendsystems.ng/login');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$html = curl_exec($ch);
$url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
curl_close($ch);

echo "Final GET URL: $url\n";
echo "Page snippet:\n";

// Find form or input tags
preg_match_all('/<input[^>]+>/i', $html, $inputs);
print_r($inputs[0]);

preg_match_all('/<form[^>]+>/i', $html, $forms);
print_r($forms[0]);
