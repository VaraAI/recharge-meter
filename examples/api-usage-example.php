<?php

/**
 * STS Vend System API Usage Examples
 * 
 * This file demonstrates how to use the RechargeMeter Service Package
 * with the STS Vend System API endpoints.
 * 
 * API Base URL: http://120.26.4.119:9094
 */

use RechargeMeter\RechargeMeterService\Facades\Recharge;
use RechargeMeter\RechargeMeterService\Facades\UseType;

// Example 1: Get Contract Information
// API Endpoint: GET /api/Power/GetContractInfo
// URL: http://120.26.4.119:9094/api/Power/GetContractInfo?UserId=A1Test&Password=123456&MeterType=1&MeterCode=22000169833

echo "=== Example 1: Get Contract Information ===\n";

try {
    // Set credentials
    Recharge::setCredentials('A1Test', '123456');
    
    // Get contract information
    $result = Recharge::getContractInfo('22000169833', 1);
    
    if ($result['success']) {
        echo "✅ Contract Info Retrieved Successfully!\n";
        echo "Meter Code: " . $result['data']['MeterCode'] . "\n";
        echo "Street Address: " . $result['data']['StreetAddress'] . "\n";
        echo "City/Town: " . $result['data']['CityTown'] . "\n";
        echo "Suburb/Location: " . $result['data']['SuburbLocation'] . "\n";
    } else {
        echo "❌ Failed to get contract info: " . $result['error'] . "\n";
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n";

// Example 2: Process Meter Recharge (Vending)
// API Endpoint: POST /api/Power/ProcessVending
// This would be the equivalent of a recharge operation

echo "=== Example 2: Process Meter Recharge ===\n";

try {
    // Set credentials
    Recharge::setCredentials('A1Test', '123456');
    
    // Process a recharge (amount-based vending)
    $result = Recharge::process('22000169833', 1, 5000, 0);
    
    if ($result['success']) {
        echo "✅ Recharge Processed Successfully!\n";
        echo "Token: " . $result['data']['token'] . "\n";
        echo "Amount: " . $result['data']['amount'] . "\n";
        echo "History ID: " . $result['history_id'] . "\n";
    } else {
        echo "❌ Recharge failed: " . $result['error'] . "\n";
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n";

// Example 3: Get Clear Credit Token
// API Endpoint: GET /api/Power/GetClearCreditToken

echo "=== Example 3: Get Clear Credit Token ===\n";

try {
    // Set credentials
    Recharge::setCredentials('A1Test', '123456');
    
    // Get clear credit token
    $result = Recharge::getClearCreditToken('22000169833', 1);
    
    if ($result['success']) {
        echo "✅ Clear Credit Token Retrieved!\n";
        echo "Token: " . $result['data']['token'] . "\n";
    } else {
        echo "❌ Failed to get clear credit token: " . $result['error'] . "\n";
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n";

// Example 4: Get Clear Tamper Sign Token
// API Endpoint: GET /api/Power/GetClearTamperSignToken

echo "=== Example 4: Get Clear Tamper Sign Token ===\n";

try {
    // Set credentials
    Recharge::setCredentials('A1Test', '123456');
    
    // Get clear tamper sign token
    $result = Recharge::getClearTamperSignToken('22000169833', 1);
    
    if ($result['success']) {
        echo "✅ Clear Tamper Sign Token Retrieved!\n";
        echo "Token: " . $result['data']['token'] . "\n";
    } else {
        echo "❌ Failed to get clear tamper sign token: " . $result['error'] . "\n";
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n";

// Example 5: Register New Meter
// API Endpoint: POST /api/Power/RegisterMeter

echo "=== Example 5: Register New Meter ===\n";

try {
    // Set credentials
    Recharge::setCredentials('A1Test', '123456');
    
    // Register a new meter
    $meterData = [
        'MeterCode' => '22000169834',
        'MeterType' => 1, // 1-Electric, 2-Water
        'CustomerName' => 'John Doe',
        'UseTypeId' => 'RES',
        'Address' => '123 Main Street, Dar es Salaam',
        'PhoneNumber' => '255123456789'
    ];
    
    $result = Recharge::registerMeter($meterData);
    
    if ($result['success']) {
        echo "✅ Meter Registered Successfully!\n";
        echo "Meter Code: " . $result['data']['MeterCode'] . "\n";
        echo "Customer: " . $result['data']['CustomerName'] . "\n";
    } else {
        echo "❌ Meter registration failed: " . $result['error'] . "\n";
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n";

// Example 6: Update Meter Information
// API Endpoint: POST /api/Power/UpdateMeter

echo "=== Example 6: Update Meter Information ===\n";

try {
    // Set credentials
    Recharge::setCredentials('A1Test', '123456');
    
    // Update meter information
    $updateData = [
        'MeterCode' => '22000169833',
        'MeterType' => 1,
        'CustomerName' => 'John Doe Updated',
        'Address' => '456 New Street, Dar es Salaam',
        'PhoneNumber' => '255987654321'
    ];
    
    $result = Recharge::updateMeter($updateData);
    
    if ($result['success']) {
        echo "✅ Meter Updated Successfully!\n";
        echo "Meter Code: " . $result['data']['MeterCode'] . "\n";
        echo "Updated Customer: " . $result['data']['CustomerName'] . "\n";
    } else {
        echo "❌ Meter update failed: " . $result['error'] . "\n";
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n";

// Example 7: Use Type Management
// API Endpoint: GET /api/Power/GetUseTypeList

echo "=== Example 7: Use Type Management ===\n";

try {
    // Set credentials for use type operations
    UseType::setCredentials('A1Test', '123456');
    
    // Get list of use types
    $result = UseType::getList();
    
    if ($result['success']) {
        echo "✅ Use Types Retrieved Successfully!\n";
        echo "Available Use Types:\n";
        foreach ($result['data'] as $useType) {
            echo "- " . $useType['use_type_name'] . " (ID: " . $useType['use_type_id'] . ")\n";
        }
    } else {
        echo "❌ Failed to get use types: " . $result['error'] . "\n";
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n";

// Example 8: Laravel Controller Usage
echo "=== Example 8: Laravel Controller Usage ===\n";

/*
class MeterController extends Controller
{
    public function getContractInfo(Request $request)
    {
        $request->validate([
            'meter_code' => 'required|string',
            'meter_type' => 'required|integer|in:1,2',
            'user_id' => 'required|string',
            'password' => 'required|string',
        ]);
        
        try {
            // Set credentials
            Recharge::setCredentials($request->user_id, $request->password);
            
            // Get contract info
            $result = Recharge::getContractInfo(
                $request->meter_code, 
                $request->meter_type
            );
            
            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'data' => $result['data'],
                    'message' => 'Contract information retrieved successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'error' => $result['error']
                ], 400);
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function processRecharge(Request $request)
    {
        $request->validate([
            'meter_code' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'vending_type' => 'required|integer|in:0,1',
            'user_id' => 'required|string',
            'password' => 'required|string',
        ]);
        
        try {
            // Set credentials
            Recharge::setCredentials($request->user_id, $request->password);
            
            // Process recharge
            $result = Recharge::process(
                $request->meter_code, 
                1, // meter type (1 for electric)
                $request->amount, 
                $request->vending_type
            );
            
            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'token' => $result['data']['token'],
                    'amount' => $result['data']['amount'],
                    'history_id' => $result['history_id'],
                    'message' => 'Recharge processed successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'error' => $result['error']
                ], 400);
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }
}
*/

echo "✅ All examples completed!\n";
echo "\n";
echo "API Base URL: http://120.26.4.119:9094\n";
echo "Test Credentials: UserId=A1Test, Password=123456\n";
echo "Test Meter Code: 22000169833\n"; 