<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/adyen/getPaymentMethods', 'AdyenController@getPaymentMethods');
Route::post('/adyen/makePayment', 'AdyenController@makePayment');
Route::post('/adyen/generateAndSendPaymentLink', 'AdyenController@generateAndSendPaymentLink');
Route::post('/adyen/getPaymentLinkQR', 'AdyenController@getPaymentLinkQR');
Route::post('/adyen/terminalCloudApiRequest', 'AdyenController@terminalCloudApiRequest');


Route::post('/adyen/platforms/createAccountHolder', 'AdyenAFPController@createAccountHolder');
Route::post('/adyen/platforms/updateAccountHolder', 'AdyenAFPController@updateAccountHolder');
Route::post('/adyen/platforms/getOnboardingUrl', 'AdyenAFPController@getOnboardingUrl');




