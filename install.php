<?php

/**
 * Installation script for RechargeMeter Service Package
 * 
 * This script helps set up the package after installation.
 * Run this script from your Laravel project root:
 * php vendor/recharge-meter/recharge-meter-service/install.php
 */

echo "RechargeMeter Service Package Installation\n";
echo "==========================================\n\n";

// Check if we're in a Laravel project
if (!file_exists('artisan')) {
    echo "❌ Error: This script must be run from a Laravel project root directory.\n";
    echo "Please navigate to your Laravel project root and run this script again.\n";
    exit(1);
}

// Check if config file exists
if (file_exists('config/recharge.php')) {
    echo "⚠️  Warning: Configuration file already exists at config/recharge.php\n";
    echo "   Skipping configuration file creation.\n\n";
} else {
    echo "📝 Creating configuration file...\n";
    // The config will be published via artisan command
    echo "   Run: php artisan vendor:publish --tag=recharge-config\n\n";
}

// Check if migrations exist
$migrationPath = 'database/migrations';
$migrationFiles = glob($migrationPath . '/*_create_recharge_histories_table.php');

if (empty($migrationFiles)) {
    echo "📊 Creating database migrations...\n";
    echo "   Run: php artisan migrate\n\n";
} else {
    echo "✅ Database migrations already exist.\n\n";
}

echo "🎉 Installation completed!\n\n";
echo "Next steps:\n";
echo "1. Publish configuration: php artisan vendor:publish --tag=recharge-config\n";
echo "2. Run migrations: php artisan migrate\n";
echo "3. Configure your .env file with the required settings\n";
echo "4. Check the README.md for usage examples\n\n";

echo "For more information, visit: https://github.com/recharge-meter/recharge-meter-service\n"; 