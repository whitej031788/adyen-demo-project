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
  
  // Preact Routes
  Route::get('/afp-onboarding', function () {return view('afp-onboarding');});
  Route::get('/afp-payment', function () {return view('afp-payment');});
  // End Preact Routes

  // Normal JS Routes
  Route::get('/custom-call-center', 'ShowController@customCallCenter');
  Route::get('/standard-ecom', 'ShowController@standardEcom');
  Route::post('/delete-demo', 'DemoController@delete');
  // End Normal JS Routes
});
// Create demo, GET for new  demo, POST for the actual creation
Route::get('/create-demo', 'DemoController@view');

Route::post('/create-demo', 'DemoController@create');

// File upload controllers
Route::get('file-upload', 'DemoController@fileUpload')->name('file.upload');

Route::post('file-upload', 'DemoController@fileUploadPost')->name('file.upload.post');
