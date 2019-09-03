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


// {slider} = {id} && Slider::find{slider}
Route::get('/slider/{slider}', 'SliderController@show');

Route::get('/events', 'EventController@get');

Route::get('/events/{year}/{month}/{event}', 'EventController@getOneEvent');

// Protected (admin routes)
Route::middleware(['jwt.auth'])->group(function () {

    Route::post('/events', 'EventController@put');


});
Route::post('/create-user', 'UserController@createUser');

Route::post('/login', 'UserController@loginUser');
Route::post('/refresh-token', 'UserController@refreshToken');