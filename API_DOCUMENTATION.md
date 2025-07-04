# STS Vend System API Documentation

This document outlines the STS Vend System API endpoints and how they are used by the RechargeMeter Service Package.

## API Base URL
```
http://120.26.4.119:9094
```

## Authentication
All API endpoints require authentication using `UserId` and `Password` parameters.

**Test Credentials:**
- UserId: `A1Test`
- Password: `123456`

## API Endpoints

### Power Management Endpoints

#### 1. Get Vending Token
**Endpoint:** `GET /api/Power/GetVendingToken`

**Description:** Get the token of recharge

**Parameters:**
- `UserId` (string, required): The user id
- `Password` (string, required): The password
- `MeterType` (integer, required): The meter type (1-Electric | 2-Water)
- `MeterCode` (string, required): The meter code (max 13 characters)
- `AmountOrQuantity` (float, required): The recharge amount or quantity is determined by the VendingType
- `VendingType` (integer, required): The type of vending (0-amount | 1-quantity)

**Laravel Package Usage:**
```php
use RechargeMeter\RechargeMeterService\Facades\Recharge;

Recharge::setCredentials('A1Test', '123456');
$result = Recharge::getVendingToken('22000169833', 1, 5000, 0);
```

#### 2. Get Clear Credit Token
**Endpoint:** `GET /api/Power/GetClearCreditToken`

**Description:** Get the token of clear credit

**Parameters:**
- `UserId` (string, required): The user id
- `Password` (string, required): The password
- `MeterCode` (string, required): The meter code
- `MeterType` (integer, required): The meter type (1-Electric | 2-Water)

**Laravel Package Usage:**
```php
use RechargeMeter\RechargeMeterService\Facades\Recharge;

Recharge::setCredentials('A1Test', '123456');
$result = Recharge::getClearCreditToken('22000169833', 1);
```

#### 3. Get Clear Tamper Sign Token
**Endpoint:** `GET /api/Power/GetClearTamperSignToken`

**Description:** Get the token of clear tamper sign

**Parameters:**
- `UserId` (string, required): The user id
- `Password` (string, required): The password
- `MeterCode` (string, required): The meter code
- `MeterType` (integer, required): The meter type (1-Electric | 2-Water)

**Laravel Package Usage:**
```php
use RechargeMeter\RechargeMeterService\Facades\Recharge;

Recharge::setCredentials('A1Test', '123456');
$result = Recharge::getClearTamperSignToken('22000169833', 1);
```

#### 4. Get Contract Info
**Endpoint:** `GET /api/Power/GetContractInfo`

**Description:** Get the contract information of the meter

**Parameters:**
- `UserId` (string, required): The user id
- `Password` (string, required): The password
- `MeterType` (integer, required): The meter type (1-electric 2-water)
- `MeterCode` (string, required): The meter code

**Response Example:**
```json
{
    "Code": 200,
    "Message": "search successful",
    "Data": {
        "MeterCode": "0022000169833",
        "StreetAddress": "Mwenge Mpakani (三)",
        "CityTown": "2556840000",
        "SuburbLocation": "Dar es Salaam SP"
    }
}
```

**Laravel Package Usage:**
```php
use RechargeMeter\RechargeMeterService\Facades\Recharge;

Recharge::setCredentials('A1Test', '123456');
$result = Recharge::getContractInfo('22000169833', 1);
```

#### 5. Register Meter
**Endpoint:** `POST /api/Power/MeterRegister`

**Description:** Register the meter

**Parameters (multipart/form-data):**
- `UserId` (string, required): The user id
- `Password` (string, required): The password
- `UseTypeId` (string, required): The use type id
- `MeterCode` (string, required): The meter codes (Supports registering up to 20 meter codes simultaneously. If there are multiple, separate them with commas in English)
- `MeterType` (integer, required): The meter type (1-electric 2-water)
- `CustomerName` (string, required, max 20 chars): The customer name
- `Address` (string, optional, max 50 chars): The address
- `PhoneNumber` (string, optional, max 20 chars): The phone number
- `Fax` (string, optional, max 20 chars): The fax

**Laravel Package Usage:**
```php
use RechargeMeter\RechargeMeterService\Facades\Recharge;

Recharge::setCredentials('A1Test', '123456');
$result = Recharge::registerMeter([
    'MeterCode' => '22000169834',
    'MeterType' => 1,
    'CustomerName' => 'John Doe',
    'UseTypeId' => 'RES',
    'Address' => '123 Main Street, Dar es Salaam',
    'PhoneNumber' => '255123456789',
    'Fax' => '255123456790'
]);
```

#### 6. Update Meter
**Endpoint:** `POST /api/Power/MeterUpdate`

**Description:** Update the meter

**Parameters (multipart/form-data):**
- `UserId` (string, required): The user id
- `Password` (string, required): The password
- `MeterCode` (string, required, max 13 chars): The meter code
- `MeterType` (integer, required): The meter type (1-electric 2-water)
- `CustomerName` (string, required, max 20 chars): The customer name
- `Address` (string, optional, max 50 chars): The address
- `PhoneNumber` (string, optional, max 20 chars): The phone number
- `UseTypeId` (string, optional, max 32 chars): The use type id

**Laravel Package Usage:**
```php
use RechargeMeter\RechargeMeterService\Facades\Recharge;

Recharge::setCredentials('A1Test', '123456');
$result = Recharge::updateMeter([
    'MeterCode' => '22000169833',
    'MeterType' => 1,
    'CustomerName' => 'John Doe Updated',
    'Address' => '456 New Street, Dar es Salaam',
    'PhoneNumber' => '255987654321',
    'UseTypeId' => 'RES'
]);
```

### Use Type Management Endpoints

#### 1. Get Use Type List
**Endpoint:** `GET /api/UseType/UseTypeList`

**Description:** Get the use type list

**Parameters:**
- `userId` (string, required, max 10 chars): The user id
- `password` (string, required, max 20 chars): The password

**Laravel Package Usage:**
```php
use RechargeMeter\RechargeMeterService\Facades\UseType;

UseType::setCredentials('A1Test', '123456');
$result = UseType::getList();
```

#### 2. Add Use Type
**Endpoint:** `POST /api/UseType/AddUseType`

**Description:** Add the use type

**Parameters (multipart/form-data):**
- `UserId` (string, required): The user id
- `Password` (string, required): The password
- `UseTypeId` (string, required, max 10 chars): The id of use type
- `UseTypeName` (string, required, max 20 chars): The name of use type
- `MeterType` (integer, required): The meter type (1-electric 2-water)
- `Price` (float, required, 0 to 1,000,000,000,000,000): The unit-price
- `Vat` (float, required, 0 to 1,000,000,000,000,000): The tariff

**Laravel Package Usage:**
```php
use RechargeMeter\RechargeMeterService\Facades\UseType;

UseType::setCredentials('A1Test', '123456');
$result = UseType::add(
    useTypeId: 'RES',
    useTypeName: 'Residential',
    meterType: 1,
    price: 100.00,
    vat: 18.00
);
```

#### 3. Update Use Type
**Endpoint:** `POST /api/UseType/UpdateUseType`

**Description:** Update the use type

**Parameters (multipart/form-data):**
- `UserId` (string, required): The user id
- `Password` (string, required): The password
- `UseTypeId` (string, required): The id of use type
- `Price` (float, required): The unit-price
- `Vat` (float, required): The tariff

**Laravel Package Usage:**
```php
use RechargeMeter\RechargeMeterService\Facades\UseType;

UseType::setCredentials('A1Test', '123456');
$result = UseType::update(
    useTypeId: 'RES',
    price: 120.00,
    vat: 20.00
);
```

#### 4. Delete Use Type
**Endpoint:** `POST /api/UseType/DeleteUseType`

**Description:** Delete the use type

**Parameters (multipart/form-data):**
- `UserId` (string, required): The user id
- `Password` (string, required): The password
- `UseTypeId` (string, required): The id of use type

**Laravel Package Usage:**
```php
use RechargeMeter\RechargeMeterService\Facades\UseType;

UseType::setCredentials('A1Test', '123456');
$result = UseType::delete('RES');
```

## Response Format

All API endpoints return responses in the following format:

```json
{
    "Code": 200,
    "Message": "success message",
    "Data": {
        // Response data specific to each endpoint
    }
}
```

## Error Responses

When an error occurs, the API returns:

```json
{
    "Code": 400,
    "Message": "error message",
    "Data": null
}
```

## Laravel Package Response Format

The Laravel package standardizes all responses to:

```php
[
    'success' => true|false,
    'data' => [...] | null,
    'error' => 'error message' | null,
    'history_id' => 123 // For recharge operations
]
```

## Parameter Validation

### Meter Type Validation
- Must be either `1` (Electric) or `2` (Water)
- Pattern: `^[12]$`

### Vending Type Validation
- Must be either `0` (amount-based) or `1` (quantity-based)
- Pattern: `^[01]$`

### Meter Code Validation
- For single meter: max 13 characters
- For multiple meters: supports up to 20 codes separated by commas
- Pattern for multiple: `^(\d{1,13},){0,19}\d{1,13}$`

### String Length Limits
- CustomerName: max 20 characters
- Address: max 50 characters
- PhoneNumber: max 20 characters
- Fax: max 20 characters
- UseTypeId: max 10 characters
- UseTypeName: max 20 characters

### Numeric Limits
- Price: 0 to 1,000,000,000,000,000
- Vat: 0 to 1,000,000,000,000,000

## Testing

You can test the API endpoints directly using tools like:
- cURL
- Postman
- Browser (for GET requests)

**Example cURL commands:**

```bash
# Get Contract Info
curl "http://120.26.4.119:9094/api/Power/GetContractInfo?UserId=A1Test&Password=123456&MeterType=1&MeterCode=22000169833"

# Get Vending Token
curl "http://120.26.4.119:9094/api/Power/GetVendingToken?UserId=A1Test&Password=123456&MeterType=1&MeterCode=22000169833&AmountOrQuantity=5000&VendingType=0"

# Get Use Type List
curl "http://120.26.4.119:9094/api/UseType/UseTypeList?userId=A1Test&password=123456"
```

## Notes

- All amounts are typically in the smallest currency unit (e.g., cents)
- Meter codes should be provided without leading zeros
- The API supports both electric (MeterType=1) and water (MeterType=2) meters
- All timestamps are typically in UTC
- The API uses HTTP status codes for success/error indication
- POST requests use `multipart/form-data` encoding
- GET requests use query parameters
- The package automatically handles authentication and parameter formatting 