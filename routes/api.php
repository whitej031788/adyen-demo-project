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
Route::post('/adyen/terminalCloudCardAcquisitionRequest', 'AdyenController@terminalCloudCardAcquisitionRequest');
Route::post('/adyen/adjustPayment', 'AdyenController@adjustPayment');
Route::post('/adyen/capturePayment', 'AdyenController@capturePayment');
Route::post('/adyen/makeDonation', 'AdyenController@makeDonation');
Route::post('/adyen/submitAdditionalDetails', 'AdyenController@submitAdditionalDetails');
Route::post('/adyen/getCostEstimate', 'AdyenController@getCostEstimate');
Route::post('/adyen/checkBalance', 'AdyenController@checkBalance');
Route::post('/adyen/createOrder', 'AdyenController@createOrder');
Route::post('/webhooks/payment', 'WebhooksController@handlePaymentNotification');
Route::post('/webhooks/platform', 'WebhooksController@handlePlatformNotification');

// Hospitality routes
Route::prefix('hospitality')->group(function () {
    Route::post('/addRegistrant', 'HospitalityController@addRegistrant');
    Route::patch('/updateRegistrant/{id}', 'HospitalityController@updateRegistrant');
    Route::post('/addLineItem', 'HospitalityController@addLineItem');
    Route::post('/removeLineItem', 'HospitalityController@removeLineItem');
    Route::post('/payFinalBill', 'HospitalityController@payFinalBill');
});