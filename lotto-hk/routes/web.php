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

Route::group(["namespace" => "Foreground"], function () {
    Route::get('/', 'LottoController@index');
    Route::get('/home/', 'LottoController@home');

    Route::get('/mobiles/', 'LottoController@mobiles_index');
    Route::get('/mobiles/tz_te/', 'LottoController@mobiles_index');
    Route::get('/mobiles/tz_ping/', 'LottoController@mobiles_index');
});

Route::group(["namespace" => "Mobiles"], function () {
    Route::any('/mobiles/auth/', 'AuthController@login');
    Route::any('/mobiles/change-password/', 'AuthController@changePassword');
    Route::any('/mobiles/logout/', 'AuthController@logout');

    Route::get('/mobiles/account/balance.json', 'AuthController@balance');

    Route::get('/mobiles/', 'LottoController@index');
    Route::get('/mobiles/home/', 'LottoController@index');
    Route::get('/mobiles/tools', 'LottoController@tools');
    Route::get('/mobiles/tools/analyses/{action}/', 'LottoController@toolsAnalyses');
    Route::get('/mobiles/columns/', 'LottoController@columns');
    Route::get('/mobiles/newspapers/', 'LottoController@newspapers');
});

Route::group(["namespace" => "Mobiles"], function () {
    Route::get('/mobiles/games/te/', 'LottoController@tz_te');
    Route::get('/mobiles/games/ping/', 'LottoController@tz_ping');
});