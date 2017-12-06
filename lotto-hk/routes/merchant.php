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
Route::any('/auth/', 'Merchant\AuthController@login');

Route::group(["namespace" => "Merchant", 'middleware' => 'merchant_auth'], function () {
    Route::get('/', 'HomeController@index');
    Route::any('/change-password/', 'AuthController@changePassword');
    Route::any('/logout/', 'AuthController@logout');
});

Route::group(["namespace" => "Merchant", 'middleware' => 'merchant_auth'], function () {
    Route::get('/order/search/', 'OrderController@search');
    Route::get('/user/search/', 'UserController@search');
});

