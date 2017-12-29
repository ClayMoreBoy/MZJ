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
    return view('mobiles.home');
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

    Route::get('/mobiles/games/all/', 'GamesController@all')->name('all-solo');
    Route::get('/mobiles/games/all-two/', 'GamesController@all')->name('all-two');
    Route::get('/mobiles/games/all-three/', 'GamesController@all')->name('all-three');
    Route::get('/mobiles/games/all-four/', 'GamesController@all')->name('all-four');
    Route::get('/mobiles/games/all-five/', 'GamesController@all')->name('all-five');
    Route::get('/mobiles/games/all-six/', 'GamesController@all')->name('all-six');

    Route::get('/mobiles/games/all-zodiac/', 'GamesController@allZodiac')->name('zodiac-solo');
    Route::get('/mobiles/games/all-zodiac-two/', 'GamesController@allZodiac')->name('zodiac-two');
    Route::get('/mobiles/games/all-zodiac-three/', 'GamesController@allZodiac')->name('zodiac-three');
    Route::get('/mobiles/games/all-zodiac-four/', 'GamesController@allZodiac')->name('zodiac-four');
    Route::get('/mobiles/games/all-zodiac-five/', 'GamesController@allZodiac')->name('zodiac-five');
    Route::get('/mobiles/games/all-zodiac-six/', 'GamesController@allZodiac')->name('zodiac-six');
});

Route::group(["namespace" => "Mobiles", 'middleware' => 'auth_api'], function () {
    Route::post('/mobiles/games/te/post/', 'GamesController@tePost');
    Route::post('/mobiles/games/all/post/', 'GamesController@allPost');
    Route::post('/mobiles/games/all-zodiac/post/', 'GamesController@allZodiacPost');

//    Route::post('/mobiles/games/all-zodiac-two/post/', 'GamesController@allZodiacTwoPost');
//    Route::post('/mobiles/games/all-zodiac-three/post/', 'GamesController@allZodiacThreePost');
//    Route::post('/mobiles/games/all-zodiac-four/post/', 'GamesController@allZodiacFourPost');
//    Route::post('/mobiles/games/all-zodiac-five/post/', 'GamesController@allZodiacFivePost');
//    Route::post('/mobiles/games/all-zodiac-six/post/', 'GamesController@allZodiacSixPost');
//
//    Route::post('/mobiles/games/all-two/post/', 'GamesController@allTwoPost');
//    Route::post('/mobiles/games/all-three/post/', 'GamesController@allThreePost');
//    Route::post('/mobiles/games/all-four/post/', 'GamesController@allFourPost');
//    Route::post('/mobiles/games/all-five/post/', 'GamesController@allFivePost');
//    Route::post('/mobiles/games/all-six/post/', 'GamesController@allSixPost');
});