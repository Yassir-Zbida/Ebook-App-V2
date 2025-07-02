<?php

// Simple test to simulate form submission with the new bracket notation
$url = 'http://localhost:8000/admin/ebooks';

// Create test data that mimics what the frontend should send with bracket notation
$postData = [
    'title' => 'Test Ebook',
    'description' => 'Test description',
    'price' => '29.99',
    'categories' => [
        0 => [
            'name' => 'Category 1',
            'description' => 'Description 1',
            'icon' => 'ri-folder-line',
            'parent_index' => '',
            'level' => '0'
        ],
        1 => [
            'name' => 'Category 2',
            'description' => 'Description 2',
            'icon' => 'ri-file-line',
            'parent_index' => '0',
            'level' => '1',
            'resource' => [
                'title' => 'Test Resource',
                'content_type' => 'pdf',
                'description' => 'Test resource description'
            ]
        ]
    ],
    '_token' => 'test-token'
];

echo "Sending POST data (bracket notation format):\n";
echo "title: " . $postData['title'] . "\n";
echo "description: " . $postData['description'] . "\n";
echo "price: " . $postData['price'] . "\n";
echo "categories: ARRAY with " . count($postData['categories']) . " items\n";
foreach ($postData['categories'] as $index => $category) {
    echo "  categories[$index]:\n";
    foreach ($category as $key => $value) {
        if (is_array($value)) {
            echo "    $key: ARRAY with " . count($value) . " items\n";
            foreach ($value as $subKey => $subValue) {
                echo "      $subKey: $subValue\n";
            }
        } else {
            echo "    $key: $value\n";
        }
    }
}
echo "\n";

// Initialize cURL
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, false);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

curl_close($ch);

echo "HTTP Response Code: $httpCode\n";
echo "Response: $response\n";

echo "\nThis shows how Laravel receives the bracket notation data.\n";

echo "\nCheck Laravel logs for detailed information.\n"; 