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

  Route::post('/delete-demo', 'DemoController@delete');
});

// Create demo, GET for new  demo, POST for the actual creation
Route::get('/create-demo', 'DemoController@view');

Route::post('/create-demo', 'DemoController@create');
