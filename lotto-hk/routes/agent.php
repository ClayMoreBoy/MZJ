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
Route::any('/auth/', 'Agent\AuthController@login');

Route::group(["namespace" => "Agent", 'middleware' => 'agent_auth'], function () {
    Route::get('/', 'HomeController@index');
    Route::any('/change-password/', 'AuthController@changePassword');
    Route::any('/logout/', 'AuthController@logout');
});

Route::group(["namespace" => "Agent", 'middleware' => 'agent_auth_api'], function () {
    Route::post('/user/update/', 'UserController@update');
    Route::post('/process/withdraw/', 'FinanceController@processWithdraw');
});

Route::group(["namespace" => "Agent", 'middleware' => 'agent_auth'], function () {
    Route::get('/user/search/', 'UserController@search');
    Route::get('/user/view/{id}/', 'UserController@view');
    Route::get('/user/edit/{id}/', 'UserController@edit');
    Route::any('/user/create/', 'UserController@create');

    Route::get('/deposit/accounts/', 'FinanceController@accounts');
    Route::any('/deposit/{id}/', 'FinanceController@deposit');
    Route::get('/bill/deposits/', 'FinanceController@deposits');
    Route::get('/bill/withdraws/', 'FinanceController@withdraws');

    Route::get('/order/search/', 'OrderController@search');
    Route::get('/order/issue/', 'OrderController@issue');

    Route::get('/report/issue/', 'ReportController@issue');
    Route::get('/report/account/', 'ReportController@account');
});
