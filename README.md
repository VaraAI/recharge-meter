# Laravel Recharge Meter Service

A Laravel package for managing electrical and water meter recharges using the STS Vend System API.

## Features

- ✅ **Meter recharge token generation** (GetVendingToken)
- ✅ **Clear credit/tamper token management** (GetClearCreditToken, GetClearTamperSignToken)
- ✅ **Contract information retrieval** (GetContractInfo)
- ✅ **Meter registration and updates** (MeterRegister, MeterUpdate)
- ✅ **Use type management** (UseTypeList, AddUseType, UpdateUseType, DeleteUseType)
- ✅ **Comprehensive logging**
- ✅ **Simulation mode for testing**
- ✅ **Database tracking of all operations**

## Author

**Dr Constantine Msigwa**  
Email: varaai@info.com

## Installation

### Via Composer

1. Install the package via Composer:

```bash
composer require recharge-meter/recharge-meter-service
```

2. Publish the configuration:

```bash
php artisan vendor:publish --tag=recharge-config
```

3. Run the migrations:

```bash
php artisan migrate
```

4. Configure your `.env` file:

```env
RECHARGE_API_URL=http://120.26.4.119:9094
RECHARGE_SIMULATE=false
RECHARGE_LOGGING_ENABLED=true
RECHARGE_LOG_CHANNEL=daily
RECHARGE_CONNECT_TIMEOUT=10
RECHARGE_REQUEST_TIMEOUT=30
```

### Manual Installation

If you prefer to install manually, add the package to your `composer.json`:

```json
{
    "require": {
        "recharge-meter/recharge-meter-service": "^1.0"
    }
}
```

Then run:

```bash
composer install
```

## Quick Start

After installation, you can start using the package immediately:

```php
use RechargeMeter\RechargeMeterService\Facades\Recharge;

// Set credentials
Recharge::setCredentials('your-user-id', 'your-password');

// Get contract information
$result = Recharge::getContractInfo('22000169833', 1);

if ($result['success']) {
    echo "Contract info: " . json_encode($result['data']);
}
```

## API Endpoints

This package supports all STS Vend System API endpoints:

### Power Management Endpoints

| Method | Endpoint | Description | Package Method |
|--------|----------|-------------|----------------|
| GET | `/api/Power/GetVendingToken` | Get recharge token | `getVendingToken()` |
| GET | `/api/Power/GetClearCreditToken` | Get clear credit token | `getClearCreditToken()` |
| GET | `/api/Power/GetClearTamperSignToken` | Get clear tamper sign token | `getClearTamperSignToken()` |
| GET | `/api/Power/GetContractInfo` | Get contract information | `getContractInfo()` |
| POST | `/api/Power/MeterRegister` | Register meter | `registerMeter()` |
| POST | `/api/Power/MeterUpdate` | Update meter | `updateMeter()` |

### Use Type Management Endpoints

| Method | Endpoint | Description | Package Method |
|--------|----------|-------------|----------------|
| GET | `/api/UseType/UseTypeList` | Get use type list | `getList()` |
| POST | `/api/UseType/AddUseType` | Add use type | `add()` |
| POST | `/api/UseType/UpdateUseType` | Update use type | `update()` |
| POST | `/api/UseType/DeleteUseType` | Delete use type | `delete()` |

## Usage

### Authentication

All operations require authentication:

```php
use RechargeMeter\RechargeMeterService\Facades\Recharge;
use RechargeMeter\RechargeMeterService\Facades\UseType;

// Set credentials for meter operations
Recharge::setCredentials('your-user-id', 'your-password');

// Set credentials for use type operations
UseType::setCredentials('your-user-id', 'your-password');
```

### Power Management Operations

#### 1. Get Vending Token (Recharge)
```php
// Amount-based vending (type 0)
$result = Recharge::getVendingToken('22000169833', 1, 5000, 0);

// Quantity-based vending (type 1)
$result = Recharge::getVendingToken('22000169833', 1, 100, 1);

// Backward compatibility (alias for getVendingToken)
$result = Recharge::process('22000169833', 1, 5000, 0);
```

#### 2. Get Clear Credit Token
```php
$result = Recharge::getClearCreditToken('22000169833', 1);
```

#### 3. Get Clear Tamper Sign Token
```php
$result = Recharge::getClearTamperSignToken('22000169833', 1);
```

#### 4. Get Contract Info
```php
$result = Recharge::getContractInfo('22000169833', 1);
```

#### 5. Register Meter
```php
$result = Recharge::registerMeter([
    'MeterCode' => '22000169833',
    'MeterType' => 1, // 1-Electric, 2-Water
    'CustomerName' => 'John Doe',
    'UseTypeId' => 'RES',
    'Address' => '123 Main St',
    'PhoneNumber' => '1234567890',
    'Fax' => '1234567891' // Optional
]);
```

#### 6. Update Meter
```php
$result = Recharge::updateMeter([
    'MeterCode' => '22000169833',
    'MeterType' => 1,
    'CustomerName' => 'John Doe Updated',
    'Address' => '456 New St',
    'PhoneNumber' => '0987654321',
    'UseTypeId' => 'RES' // Optional
]);
```

### Use Type Management Operations

#### 1. Get Use Type List
```php
$result = UseType::getList();
```

#### 2. Add Use Type
```php
$result = UseType::add(
    useTypeId: 'RES',
    useTypeName: 'Residential',
    meterType: 1,
    price: 100.00,
    vat: 18.00
);
```

#### 3. Update Use Type
```php
$result = UseType::update(
    useTypeId: 'RES',
    price: 120.00,
    vat: 20.00
);
```

#### 4. Delete Use Type
```php
$result = UseType::delete('RES');
```

## Response Format

All operations return a standardized response:

```php
[
    'success' => true|false,
    'data' => [...] | null,
    'error' => 'error message' | null,
    'history_id' => 123 // For recharge operations
]
```

## API Parameters

### GetVendingToken Parameters
- `MeterCode` (string, required): Meter code (max 13 characters)
- `MeterType` (integer, required): 1 for Electric, 2 for Water
- `AmountOrQuantity` (float, required): Recharge amount or quantity
- `VendingType` (integer, required): 0 for amount-based, 1 for quantity-based

### MeterRegister Parameters
- `MeterCode` (string, required): Meter code(s) - supports up to 20 codes separated by commas
- `MeterType` (integer, required): 1 for Electric, 2 for Water
- `CustomerName` (string, required, max 20 chars): Customer name
- `UseTypeId` (string, required): Use type ID
- `Address` (string, optional, max 50 chars): Customer address
- `PhoneNumber` (string, optional, max 20 chars): Phone number
- `Fax` (string, optional, max 20 chars): Fax number

### AddUseType Parameters
- `UseTypeId` (string, required, max 10 chars): Use type ID
- `UseTypeName` (string, required, max 20 chars): Use type name
- `MeterType` (integer, required): 1 for Electric, 2 for Water
- `Price` (float, required): Unit price (0 to 1,000,000,000,000,000)
- `Vat` (float, required): Tariff (0 to 1,000,000,000,000,000)

## Configuration

The package configuration file (`config/recharge.php`) contains the following settings:

```php
return [
    'api_url' => env('RECHARGE_API_URL', 'http://120.26.4.119:9094'),
    'simulate' => env('RECHARGE_SIMULATE', false),
    'logging_enabled' => env('RECHARGE_LOGGING_ENABLED', true),
    'log_channel' => env('RECHARGE_LOG_CHANNEL', 'daily'),
    'connect_timeout' => env('RECHARGE_CONNECT_TIMEOUT', 10),
    'request_timeout' => env('RECHARGE_REQUEST_TIMEOUT', 30),
];
```

## Testing

The package includes a simulation mode for testing. Enable it in your `.env`:

```env
RECHARGE_SIMULATE=true
```

Run the tests:

```bash
vendor/bin/phpunit
```

## Error Handling

All operations include comprehensive error handling and logging. Errors are:
- Logged to the configured channel
- Stored in the database for recharge operations
- Returned in a standardized format

## Examples

Check the `examples/` directory for complete usage examples:
- `basic-usage.php` - Simple recharge example
- `api-usage-example.php` - Comprehensive API examples
- `test-contract-info.php` - Testing script

## Requirements

- PHP >= 8.2
- Laravel >= 10.0
- GuzzleHTTP >= 7.8

## Support

For support, please contact:
- **Email**: varaai@info.com
- **Author**: Dr Constantine Msigwa

## License

This package is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Changelog

### Version 1.0.1
- Added all STS Vend System API endpoints
- Updated method names to match OpenAPI specification
- Added comprehensive API documentation
- Improved error handling and logging
- Added backward compatibility for existing methods

### Version 1.0.0
- Initial release
- Meter recharge functionality
- Use type management
- Comprehensive logging
- Database tracking
- Simulation mode for testing 