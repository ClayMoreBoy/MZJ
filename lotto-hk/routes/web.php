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
    Route::get('/mobiles/', 'LottoController@index');
    Route::get('/mobiles/tools', 'LottoController@tools');
    Route::get('/mobiles/tools/te/number/', 'LottoController@toolsTeNumber');
    Route::get('/mobiles/tools/te/zodiac/', 'LottoController@toolsTeZodiac');
    Route::get('/mobiles/tools/te/weishu/', 'LottoController@toolsTeWeishu');
    Route::get('/mobiles/tools/te/color/', 'LottoController@toolsTeColor');
    Route::get('/mobiles/tools/ping/number/', 'LottoController@toolsPingNumber');
    Route::get('/mobiles/tools/ping/zodiac/', 'LottoController@toolsPingZodiac');
    Route::get('/mobiles/tools/ping/weishu/', 'LottoController@toolsPingWeishu');
    Route::get('/mobiles/tools/ping/color/', 'LottoController@toolsPingColor');

    Route::get('/mobiles/columns/', 'LottoController@columns');
    Route::get('/mobiles/newspapers/', 'LottoController@newspapers');
    Route::get('/mobiles/tz_te/', 'LottoController@tz_te');
    Route::get('/mobiles/tz_ping/', 'LottoController@tz_ping');
});