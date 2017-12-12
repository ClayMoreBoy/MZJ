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

Route::get('/', function () {
    return redirect('/mobiles/');
});

Route::group(["namespace" => "Mobiles"], function () {
    Route::any('/mobiles/auth/', 'AuthController@login');
    Route::any('/mobiles/change-password/', 'AuthController@changePassword');
    Route::any('/mobiles/logout/', 'AuthController@logout');

    Route::any('/mobiles/withdraw/', 'AuthController@withdraw');
    Route::get('/mobiles/bills/', 'AuthController@bills');

    Route::get('/mobiles/account/balance.json', 'AuthController@balance');

    Route::get('/mobiles/', 'LottoController@index');
    Route::get('/mobiles/home/', 'LottoController@index');
    Route::get('/mobiles/tools', 'LottoController@tools');
    Route::get('/mobiles/tools/analyses/{action}/', 'LottoController@toolsAnalyses');
    Route::get('/mobiles/columns/', 'LottoController@columns');
    Route::get('/mobiles/newspapers/', 'LottoController@newspapers');
});

Route::group(["namespace" => "Mobiles", 'middleware' => 'auth'], function () {
    Route::get('/mobiles/order/order_curr/', 'OrdersController@order_curr');
    Route::get('/mobiles/order/order_history/', 'OrdersController@order_history');
});

//玩法
Route::group(["namespace" => "Mobiles", 'middleware' => 'auth'], function () {
    Route::get('/mobiles/games/te/', 'GamesController@te');
    Route::get('/mobiles/games/all/', 'GamesController@all');
    Route::get('/mobiles/games/all-zodiac/', 'GamesController@allZodiac');
});

Route::group(["namespace" => "Mobiles", 'middleware' => 'auth_api'], function () {
    Route::post('/mobiles/games/te/post/', 'GamesController@tePost');
    Route::post('/mobiles/games/all/post/', 'GamesController@allPost');
    Route::post('/mobiles/games/all-zodiac/post/', 'GamesController@allZodiacPost');
});