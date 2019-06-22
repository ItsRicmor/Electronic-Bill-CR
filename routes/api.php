<?php

use Illuminate\Support\Facades\Route;

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

Route::group(['prefix' => 'users'], function() {
    Route::post('/authenticate', 'AuthController@authenticate')->name('users.authenticate');
    Route::post('/signUp', 'AuthController@signUp')->name('users.signUp');
    Route::get('/me', 'AuthController@me')->name('users.me');
});