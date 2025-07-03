<?php

/**
 * Example usage of the RechargeMeter Service Package
 * 
 * This file demonstrates how to use the package in a Laravel application.
 * Copy and adapt these examples to your own application.
 */

use RechargeMeter\RechargeMeterService\Facades\Recharge;
use RechargeMeter\RechargeMeterService\Facades\UseType;

// Example 1: Basic meter recharge
try {
    // Set credentials for the operation
    Recharge::setCredentials('your-user-id', 'your-password');
    
    // Process a meter recharge (amount-based)
    $result = Recharge::process('22000169833', 1, 5000, 0);
    
    if ($result['success']) {
        echo "Recharge successful! Token: " . $result['data']['token'] . "\n";
    } else {
        echo "Recharge failed: " . $result['error'] . "\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

// Example 2: Get contract information
try {
    $contractInfo = Recharge::getContractInfo('22000169833', 1);
    
    if ($contractInfo['success']) {
        echo "Customer: " . $contractInfo['data']['customer_name'] . "\n";
        echo "Address: " . $contractInfo['data']['address'] . "\n";
        echo "Balance: " . $contractInfo['data']['balance'] . "\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

// Example 3: Register a new meter
try {
    $meterData = [
        'MeterCode' => '22000169834',
        'MeterType' => 1, // 1-Electric, 2-Water
        'CustomerName' => 'Jane Doe',
        'UseTypeId' => 'RES',
        'Address' => '456 Oak Street',
        'PhoneNumber' => '1234567890'
    ];
    
    $result = Recharge::registerMeter($meterData);
    
    if ($result['success']) {
        echo "Meter registered successfully!\n";
    } else {
        echo "Registration failed: " . $result['error'] . "\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

// Example 4: Use Type management
try {
    // Set credentials for use type operations
    UseType::setCredentials('your-user-id', 'your-password');
    
    // Get list of use types
    $useTypes = UseType::getList();
    
    if ($useTypes['success']) {
        echo "Available use types:\n";
        foreach ($useTypes['data'] as $useType) {
            echo "- " . $useType['use_type_name'] . " (ID: " . $useType['use_type_id'] . ")\n";
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

// Example 5: Clear credit token
try {
    $result = Recharge::getClearCreditToken('22000169833', 1);
    
    if ($result['success']) {
        echo "Clear credit token: " . $result['data']['token'] . "\n";
    } else {
        echo "Failed to get clear credit token: " . $result['error'] . "\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

// Example 6: Using in a Laravel Controller
/*
class MeterController extends Controller
{
    public function recharge(Request $request)
    {
        $request->validate([
            'meter_code' => 'required|string',
            'amount' => 'required|numeric|min:0',
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
                0  // vending type (0 for amount-based)
            );
            
            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'token' => $result['data']['token'],
                    'message' => 'Recharge successful'
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