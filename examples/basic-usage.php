<?php

/**
 * Basic Usage Example for RechargeMeter Service Package
 */

use RechargeMeter\RechargeMeterService\Facades\Recharge;

// Example: Basic meter recharge
try {
    // Set credentials
    Recharge::setCredentials('your-user-id', 'your-password');
    
    // Process a meter recharge
    $result = Recharge::process('22000169833', 1, 5000, 0);
    
    if ($result['success']) {
        echo "Recharge successful! Token: " . $result['data']['token'] . "\n";
    } else {
        echo "Recharge failed: " . $result['error'] . "\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
} 