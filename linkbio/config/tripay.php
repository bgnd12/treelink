<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Tripay Payment Gateway
    |--------------------------------------------------------------------------
    | Dokumentasi: https://tripay.co.id/developer
    | Sandbox     : https://tripay.co.id/api-sandbox
    | Production  : https://tripay.co.id/api
    */

    'merchant_code' => env('TRIPAY_MERCHANT_CODE', env('TRIPAY_MERCHANT')),

    'api_key' => env('TRIPAY_API_KEY'),

    'private_key' => env('TRIPAY_PRIVATE_KEY'),

    'mode' => env('TRIPAY_MODE', 'sandbox'),

    'base_url' => env('TRIPAY_BASE_URL', 'https://tripay.co.id/api-sandbox'),

    'success_url' => env('TRIPAY_SUCCESS_URL'),

    'failed_url' => env('TRIPAY_FAILED_URL'),

    'callback_url' => env('TRIPAY_CALLBACK_URL'),

    'timeout' => env('TRIPAY_TIMEOUT', 30),
];
