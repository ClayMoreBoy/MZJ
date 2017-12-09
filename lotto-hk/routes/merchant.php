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

Route::group(["namespace" => "Merchant", 'middleware' => 'merchant_auth_api'], function () {
    Route::post('/agent/edit/update/', 'AgentController@update');
    Route::post('/game/update/', 'GameController@update');
});

Route::group(["namespace" => "Merchant", 'middleware' => 'merchant_auth'], function () {
    Route::get('/order/search/', 'OrderController@search');
    Route::get('/order/issue/', 'OrderController@issue');

    Route::get('/report/issue/', 'ReportController@issue');
    Route::get('/report/agent/', 'ReportController@agent');
    Route::get('/report/account/', 'ReportController@account');

    Route::get('/user/search/', 'UserController@search');
    Route::get('/user/today/', 'UserController@today');

    Route::get('/agent/search/', 'AgentController@search');
    Route::get('/agent/view/{id}/', 'AgentController@view');
    Route::any('/agent/create/', 'AgentController@create');
    Route::get('/agent/edit/{id}/', 'AgentController@edit');
});

Route::group(["namespace" => "Merchant", 'middleware' => 'merchant_auth'], function () {
    Route::get('/game/setting/', 'GameController@setting');
});


