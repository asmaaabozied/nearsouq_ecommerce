<?php

use Illuminate\Http\Request;
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

Route::group(['prefix' => '{locale}'], function () {

    Route::get('products', 'ProductController@getProducts');
    Route::post('createProducts', 'ProductController@createProducts');
    Route::post('updateProducts', 'ProductController@updateProducts');
    Route::delete('deleteProduct', 'ProductController@deleteProduct');
    Route::post('login', 'AuthController@login');

});





