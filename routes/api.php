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


/* ----------------- AUTHENTICATION AND AUTHORIZATION ROUTES ------------------------------------------*/
Route::group(['prefix' => 'auth'], function () {

    Route::middleware('auth:api')->get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('login', 'Api\Auth\AuthController@login');
    Route::post('register', 'Api\Auth\AuthController@register');
    Route::put('update', 'Api\Auth\AuthController@update')->middleware('auth:api');
    Route::get('activate/{token}', 'Api\Auth\AuthController@activateAccount');
});

Route::group(['middleware' => 'auth:api'], function(){
    Route::get('user-detail', 'Api\Auth\AuthController@userDetail');
});
/*-------------------------------------------------------------------------------------------------------*/

/*---------------------------- PASSWORD RESET ROUTES --------------------------------------------- */

Route::group([
    'prefix' => 'password'
], function () {
    Route::post('create', 'Api\PasswordReset\PasswordResetController@create');
    Route::get('find/{token}', 'Api\PasswordReset\PasswordResetController@find');
    Route::post('reset', 'Api\PasswordReset\PasswordResetController@reset');
});

/* ----------------------------------------------------------------------------------------------- */

Route::apiResource('articulos', 'Api\Articulo\ArticuloController');
Route::get('articulos/count/{id}', 'Api\Articulo\ArticuloController@countRating');

Route::apiResource('ordenes', 'Api\Orden\OrdenController');
Route::apiResource('feedback', 'Api\Feedback\FeedbackController');

Route::apiResource('wishlist', 'Api\Wishlist\WishlistController');
Route::post('wishlist/detach', 'Api\Wishlist\WishlistController@detach');

Route::apiResource('carrito', 'Api\Carrito\CarritoController');
Route::post('carrito/detach', 'Api\Carrito\CarritoController@detach');
