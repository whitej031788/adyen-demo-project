<?php

return [
    'apiKey' => env('ADYEN_API_KEY', null),
    'apiKeyTwo' => env('ADYEN_API_KEY_2', null),
    'username' => env('ADYEN_USERNAME', null),
    'password' => env('ADYEN_PASSWORD', null),
    'clientKey' => env('ADYEN_CLIENT_KEY', null),
    'terminalPooid' => env('ADYEN_TERMINAL_POOID', null),
    'terminalPooidTwo' => env('ADYEN_TERMINAL_POOID_2', null),
    'motoMerchantAccount' => env('ADYEN_MERCHANT_ACCOUNT_MOTO', null),
    'posMerchantAccount' => env('ADYEN_MERCHANT_ACCOUNT_POS', null),
    'ecomMerchantAccount' => env('ADYEN_MERCHANT_ACCOUNT_ECOM', null),
    'platformsMerchantAccount' => env('ADYEN_MERCHANT_ACCOUNT_PLATFORMS', null)
];
