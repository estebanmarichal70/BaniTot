<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Api Routes
|--------------------------------------------------------------------------
|
| Here is where you can register Api routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your Api!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('auth/login', 'Api\Auth\AuthController@login');
Route::post('auth/register', 'Api\Auth\AuthController@register');
Route::apiResource('articulos', 'Api\Articulo\ArticuloController');
Route::apiResource('ordenes', 'Api\Orden\OrdenController');
Route::apiResource('feedback', 'Api\Feedback\FeedbackController');

Route::group(['middleware' => 'auth:api'], function(){
    Route::get('user-detail', 'Api\Auth\AuthController@userDetail');
});
