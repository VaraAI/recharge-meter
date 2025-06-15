# Laravel Recharge Meter Service

A Laravel package for managing electrical and water meter recharges using the STS Vend System API.

## Features

- Meter recharge token generation
- Clear credit/tamper token management
- Contract information retrieval
- Meter registration and updates
- Use type management (tariffs and pricing)
- Comprehensive logging
- Simulation mode for testing
- Database tracking of all operations

## Installation

1. Add the package to your `composer.json`:

```json
{
    "require": {
        "recharge-meter/service": "*"
    }
}
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

## Usage

### Authentication

All operations require authentication:

```php
use RechargeMeter\Facades\Recharge;
use RechargeMeter\Facades\UseType;

// Set credentials for meter operations
Recharge::setCredentials('your-user-id', 'your-password');

// Set credentials for use type operations
UseType::setCredentials('your-user-id', 'your-password');
```

### Meter Operations

1. **Get Vending Token**
```php
// Amount-based vending (type 0)
$result = Recharge::process('22000169833', 1, 5000, 0);

// Quantity-based vending (type 1)
$result = Recharge::process('22000169833', 1, 100, 1);
```

2. **Clear Credit Token**
```php
$result = Recharge::getClearCreditToken('22000169833', 1);
```

3. **Clear Tamper Sign Token**
```php
$result = Recharge::getClearTamperSignToken('22000169833', 1);
```

4. **Get Contract Info**
```php
$result = Recharge::getContractInfo('22000169833', 1);
```

5. **Register Meter**
```php
$result = Recharge::registerMeter([
    'MeterCode' => '22000169833',
    'MeterType' => 1, // 1-Electric, 2-Water
    'CustomerName' => 'John Doe',
    'UseTypeId' => 'RES',
    'Address' => '123 Main St',
    'PhoneNumber' => '1234567890'
]);
```

6. **Update Meter**
```php
$result = Recharge::updateMeter([
    'MeterCode' => '22000169833',
    'MeterType' => 1,
    'CustomerName' => 'John Doe Updated',
    'Address' => '456 New St',
    'PhoneNumber' => '0987654321'
]);
```

### Use Type Management

1. **List Use Types**
```php
$useTypes = UseType::getList();
```

2. **Add Use Type**
```php
$result = UseType::add(
    useTypeId: 'RES',
    useTypeName: 'Residential',
    meterType: 1,
    price: 100.00,
    vat: 18.00
);
```

3. **Update Use Type**
```php
$result = UseType::update(
    useTypeId: 'RES',
    price: 120.00,
    vat: 20.00
);
```

4. **Delete Use Type**
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

## License

MIT 