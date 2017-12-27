<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(["namespace" => "Inner"], function () {
    Route::any('/create-merchant-account', 'AuthController@createMerchantAccount');
    Route::any('/sync-games', 'AuthController@syncGames');

    Route::get('/hit', 'OrderController@hit');
    Route::get('/report', 'OrderController@report');
});
