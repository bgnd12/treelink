<?php

return [
    'merchant_id' => env('TREEPAY_MERCHANT_ID'),
    'api_key' => env('TREEPAY_API_KEY'),
    'private_key' => env('TREEPAY_PRIVATE_KEY'),
    'secret_key' => env('TREEPAY_SECRET_KEY'),
    'mode' => env('TREEPAY_MODE', 'sandbox'),
    'base_url' => env('TREEPAY_BASE_URL', 'https://sandbox.treepay.co.id/api/v1'),
    'success_url' => env('TREEPAY_SUCCESS_URL'),
    'failed_url' => env('TREEPAY_FAILED_URL'),
    'callback_url' => env('TREEPAY_CALLBACK_URL'),
    'timeout' => env('TREEPAY_TIMEOUT', 30),
];

