<?php

return [
    /*
    |--------------------------------------------------------------------------
    | MyFatoorah API Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration for MyFatoorah payment gateway.
    | Make sure to update the values in your .env file.
    |
    | Country Codes: KWT (Kuwait), SAU (Saudi Arabia), ARE (UAE),
    |                QAT (Qatar), BHR (Bahrain), OMN (Oman), JOD (Jordan), EGY (Egypt)
    |
    */

    // API Token (Live or Test)
    'api_key' => env('MYFATOORAH_API_KEY', ''),

    // Country Code (KWT, SAU, ARE, QAT, BHR, OMN, JOD, EGY)
    'country_iso' => env('MYFATOORAH_COUNTRY_ISO', 'ARE'),

    // Display Currency ISO (for invoice display: AED, SAR, KWD, etc.)
    'display_currency' => env('MYFATOORAH_DISPLAY_CURRENCY', 'AED'),

    // Test Mode: true for sandbox, false for production
    'test_mode' => env('MYFATOORAH_TEST_MODE', false),

    // Supplier Code for multi-vendor payment split
    'supplier_code' => env('MYFATOORAH_SUPPLIER_CODE', 1),

    // Webhook Secret (optional - for webhook verification)
    'webhook_secret' => env('MYFATOORAH_WEBHOOK_SECRET', ''),

    /*
    |--------------------------------------------------------------------------
    | Apple Pay Configuration (iOS)
    |--------------------------------------------------------------------------
    */
    'apple_pay' => [
        'enabled' => env('APPLE_PAY_ENABLED', false),
        'merchant_id' => env('APPLE_PAY_MERCHANT_ID', 'merchant.com.rakaz.store'),
        'display_name' => env('APPLE_PAY_DISPLAY_NAME', 'RAKAZ Store'),
        'country_code' => env('APPLE_PAY_COUNTRY_CODE', 'AE'),
        'currency_code' => env('APPLE_PAY_CURRENCY_CODE', 'AED'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Google Pay Configuration (Android)
    |--------------------------------------------------------------------------
    */
    'google_pay' => [
        'enabled' => env('GOOGLE_PAY_ENABLED', false),
        'merchant_id' => env('GOOGLE_PAY_MERCHANT_ID', ''),
        'merchant_name' => env('GOOGLE_PAY_MERCHANT_NAME', 'RAKAZ Store'),
        'country_code' => env('GOOGLE_PAY_COUNTRY_CODE', 'AE'),
        'currency_code' => env('GOOGLE_PAY_CURRENCY_CODE', 'AED'),
    ],
];
