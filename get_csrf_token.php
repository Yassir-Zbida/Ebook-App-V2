<?php

// Get CSRF token from Laravel
$url = 'http://localhost:8000/admin/ebooks/create';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Extract CSRF token from the response
if (preg_match('/<meta name="csrf-token" content="([^"]+)"/', $response, $matches)) {
    echo $matches[1];
} else {
    echo "Could not find CSRF token";
} 