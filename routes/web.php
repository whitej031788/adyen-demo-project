<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// If they haven't created a demo session, then send them to the start page for demo creation
Route::group(['middleware' => 'demosession'], function () {
  Route::get('/', 'ShowController@index');
  Auth::routes();
  // Preact Routes
  Route::get('/afp-onboarding', function () {return view('afp-onboarding');});
  Route::get('/afp-payment', function () {return view('afp-payment');});
  // End Preact Routes

  // Normal JS Routes
  Route::get('/custom-call-center', 'ShowController@customCallCenter');
  Route::get('/unified-commerce', 'ShowController@unifiedCommerce');
  Route::get('/hotel-checkin', 'ShowController@hotelCheckin');
  Route::any('/return-url/{payRef}', 'ShowController@returnUrl');
  Route::post('/delete-demo', 'DemoController@delete');
  Route::get('/webhook-viewer', 'WebhooksController@webhookViewer');
  Route::get('/saas-subscriptions', 'ShowController@saasSubscriptions');
  Route::get('/payg-registration', 'ShowController@paygRegistration');
  Route::get('/payg-interface', 'ShowController@paygInterface');
  Route::get('/payment-links', 'ShowController@paymentLinks');

  // Authenticated Routes
  Route::group(['middleware' => 'auth'], function () {
    Route::get('/payment-methods', 'ShowController@managePaymentMethods');
  });
  // End Normal JS Routes
});
// Create demo, GET for new  demo, POST for the actual creation
Route::get('/create-demo', 'DemoController@viewManual');

Route::get('/create-demo-manual', 'DemoController@viewManual');

Route::post('/create-demo', 'DemoController@create');

Route::get('/edit-demo', 'DemoController@edit');

Route::get('/load-demo-share-url/{hash}', 'DemoController@loadFromShareUrl');

// File upload controllers
Route::get('file-upload', 'DemoController@fileUpload')->name('file.upload');

Route::post('file-upload', 'DemoController@fileUploadPost')->name('file.upload.post');
