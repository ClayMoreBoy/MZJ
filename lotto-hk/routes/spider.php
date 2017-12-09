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

Route::group(["namespace" => "Spider"], function () {
    Route::get('/history', 'SpiderController@history');
    Route::get('/init', 'SpiderController@init');
    Route::get('/live', 'SpiderController@live');
    Route::get('/newspaper', 'SpiderController@newspaper');
    Route::get('/newspaper-issue', 'SpiderController@newspaperIssue');
    Route::get('/newspaper-image', 'SpiderController@newspaperImage');

    Route::get('/column', 'SpiderController@column');
    Route::get('/article_list', 'SpiderController@article_list');
    Route::get('/article_get', 'SpiderController@article_get');

    Route::get('/getballbar', 'SpiderController@getballbar');
});

