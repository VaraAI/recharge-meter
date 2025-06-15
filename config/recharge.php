<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Recharge API Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration settings for the Recharge Meter Service.
    |
    */

    'api_url' => env('RECHARGE_API_URL', 'http://120.26.4.119:9094'),
    
    'simulate' => env('RECHARGE_SIMULATE', false),

    /*
    |--------------------------------------------------------------------------
    | Logging Configuration
    |--------------------------------------------------------------------------
    |
    | Configure whether to log API responses and transactions
    |
    */
    'logging' => [
        'enabled' => env('RECHARGE_LOGGING_ENABLED', true),
        'channel' => env('RECHARGE_LOG_CHANNEL', 'daily'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Timeout Configuration
    |--------------------------------------------------------------------------
    |
    | Configure API request timeouts in seconds
    |
    */
    'timeout' => [
        'connect' => env('RECHARGE_CONNECT_TIMEOUT', 10),
        'request' => env('RECHARGE_REQUEST_TIMEOUT', 30),
    ],
]; 