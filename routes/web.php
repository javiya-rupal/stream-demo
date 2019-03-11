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
Route::get('/', 'HomeController@index');
Route::get('auth/{provider}', 'HomeController@redirectToProvider');
Route::get('auth/{provider}/callback', 'HomeController@handleProviderCallback');
//Route::group(['middleware' => 'auth'], function () {
    Route::get('/stream/{twitchId}', 'HomeController@readStream');
    Route::post('ajaxRequest', 'HomeController@ajaxRequestPost');
//});
Route::get('twitch/event/callback/{timeparam}', 'HomeController@webhookEventSubscribeCallback');