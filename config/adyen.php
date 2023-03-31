<?php

return [
    'apiKey' => env('ADYEN_API_KEY', null),
    'apiKeyTwo' => env('ADYEN_API_KEY_2', null),
    'username' => env('ADYEN_USERNAME', null),
    'password' => env('ADYEN_PASSWORD', null),
    'clientKey' => env('ADYEN_CLIENT_KEY', null),
    'terminalPoiid' => env('ADYEN_TERMINAL_POIID', null),
    'terminalPoiidTwo' => env('ADYEN_TERMINAL_POIID_2', null),
    'motoMerchantAccount' => env('ADYEN_MERCHANT_ACCOUNT_MOTO', null),
    'posMerchantAccount' => env('ADYEN_MERCHANT_ACCOUNT_POS', null),
    'ecomMerchantAccount' => env('ADYEN_MERCHANT_ACCOUNT_ECOM', null),
    'platformsMerchantAccount' => env('ADYEN_MERCHANT_ACCOUNT_PLATFORMS', null),
    'paypalID' => env('PAYPAL_MERCHANT_ID', null),
    'binLookup' => env('ADYEN_BIN_LOOKUP_VERSION', 'v50'),
    'checkoutApiVersion' => env('ADYEN_CHECKOUT_API_VERSION', 'v67')
];
