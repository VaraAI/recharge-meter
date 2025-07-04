<?php

/**
 * Test Script for Contract Info Functionality
 * 
 * This script tests the GetContractInfo API endpoint using the provided credentials:
 * - UserId: A1Test
 * - Password: 123456
 * - MeterType: 1
 * - MeterCode: 22000169833
 */

// Test credentials
$userId = 'A1Test';
$password = '123456';
$meterType = 1;
$meterCode = '22000169833';

echo "🔧 Testing RechargeMeter Service Package\n";
echo "========================================\n\n";

echo "📋 Test Parameters:\n";
echo "- UserId: $userId\n";
echo "- Password: $password\n";
echo "- MeterType: $meterType\n";
echo "- MeterCode: $meterCode\n\n";

// Test 1: Direct API Call (cURL)
echo "=== Test 1: Direct API Call ===\n";

$apiUrl = "http://120.26.4.119:9094/api/Power/GetContractInfo";
$params = [
    'UserId' => $userId,
    'Password' => $password,
    'MeterType' => $meterType,
    'MeterCode' => $meterCode
];

$url = $apiUrl . '?' . http_build_query($params);
echo "API URL: $url\n\n";

// Make the API call
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_USERAGENT, 'RechargeMeter-Test/1.0');

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

if ($error) {
    echo "❌ cURL Error: $error\n\n";
} else {
    echo "✅ HTTP Status Code: $httpCode\n";
    echo "📄 Raw Response:\n";
    echo $response . "\n\n";
    
    // Parse JSON response
    $data = json_decode($response, true);
    if ($data) {
        echo "📊 Parsed Response:\n";
        echo "- Code: " . ($data['Code'] ?? 'N/A') . "\n";
        echo "- Message: " . ($data['Message'] ?? 'N/A') . "\n";
        if (isset($data['Data'])) {
            echo "- Data:\n";
            foreach ($data['Data'] as $key => $value) {
                echo "  * $key: $value\n";
            }
        }
    } else {
        echo "❌ Failed to parse JSON response\n";
    }
}

echo "\n";

// Test 2: Simple HTTP Client Test (using file_get_contents)
echo "=== Test 2: Simple HTTP Client Test ===\n";

try {
    $context = stream_context_create([
        'http' => [
            'timeout' => 30,
            'user_agent' => 'RechargeMeter-Test/1.0'
        ]
    ]);
    
    $response = file_get_contents($url, false, $context);
    
    if ($response === false) {
        echo "❌ Failed to fetch data from API\n";
    } else {
        echo "✅ Data fetched successfully\n";
        echo "📄 Response:\n";
        echo $response . "\n\n";
        
        $data = json_decode($response, true);
        if ($data && isset($data['Data'])) {
            echo "📊 Contract Information:\n";
            echo "- Meter Code: " . ($data['Data']['MeterCode'] ?? 'N/A') . "\n";
            echo "- Street Address: " . ($data['Data']['StreetAddress'] ?? 'N/A') . "\n";
            echo "- City/Town: " . ($data['Data']['CityTown'] ?? 'N/A') . "\n";
            echo "- Suburb/Location: " . ($data['Data']['SuburbLocation'] ?? 'N/A') . "\n";
        }
    }
} catch (Exception $e) {
    echo "❌ Error with HTTP client: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 3: Test with different meter codes
echo "=== Test 3: Test with Different Meter Codes ===\n";

$testMeterCodes = ['22000169833', '22000169834', '22000169835'];

foreach ($testMeterCodes as $testCode) {
    echo "Testing Meter Code: $testCode\n";
    
    $testUrl = $apiUrl . '?' . http_build_query([
        'UserId' => $userId,
        'Password' => $password,
        'MeterType' => $meterType,
        'MeterCode' => $testCode
    ]);
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $testUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($response) {
        $data = json_decode($response, true);
        if ($data) {
            echo "  - Status: " . ($data['Code'] ?? 'Unknown') . "\n";
            echo "  - Message: " . ($data['Message'] ?? 'No message') . "\n";
        } else {
            echo "  - Status: Invalid JSON response\n";
        }
    } else {
        echo "  - Status: No response\n";
    }
    echo "\n";
}

// Summary
echo "=== Test Summary ===\n";
echo "✅ Direct API call completed\n";
echo "✅ HTTP client test completed\n";
echo "✅ Multiple meter codes tested\n";
echo "📋 Test credentials used:\n";
echo "   - UserId: $userId\n";
echo "   - Password: $password\n";
echo "   - MeterType: $meterType\n";
echo "   - MeterCode: $meterCode\n";
echo "🌐 API Base URL: http://120.26.4.119:9094\n";
echo "🔗 Endpoint: /api/Power/GetContractInfo\n\n";

echo "🎉 Testing completed!\n"; 