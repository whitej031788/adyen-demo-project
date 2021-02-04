<?php

return [
    'apiKey' => env('ADYEN_API_KEY', null),
    'username' => env('ADYEN_USERNAME', null),
    'password' => env('ADYEN_PASSWORD', null),
    'clientKey' => env('ADYEN_CLIENT_KEY', null),
    'motoMerchantAccount' => env('ADYEN_MERCHANT_ACCOUNT_MOTO', null),
    'posMerchantAccount' => env('ADYEN_MERCHANT_ACCOUNT_POS', null),
    'ecomMerchantAccount' => env('ADYEN_MERCHANT_ACCOUNT_ECOM', null),
    'platformsMerchantAccount' => env('ADYEN_MERCHANT_ACCOUNT_PLATFORMS', null)
];
