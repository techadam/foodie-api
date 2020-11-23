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

Route::namespace('Api')->group(function() {

    Route::prefix('auth')->group(function() {
        Route::post('/register', 'AuthController@register')->name('create-account');
        Route::post('/login', 'AuthController@login')->name('login');
    });

    Route::get('categories/all', 'CategoriesController@index')->name('get-categories');
    Route::get('menu/all', 'MenuController@index')->name('get-menu');
    Route::post('create-order', 'OrderController@create')->name('create-order');

    Route::prefix('orders')->group(function() {
        Route::get('/all', 'OrderController@index')->name('get-orders');
    });

    Route::group(['middleware' => ['jwt.auth', 'admin']], function() {
        Route::prefix('users')->group(function() {
            Route::get('/all', 'AuthController@getUsers')->name('get-users');
        });

        
        
        Route::prefix('categories')->group(function() {
            Route::post('/add', 'CategoriesController@create')->name('add-category');
            Route::post('/{id}/update', 'CategoriesController@update')->name('update-category');
            Route::delete('/{id}/delete', 'CategoriesController@destroy')->name('drop-category');
        });

        Route::prefix('menu')->group(function() {
            Route::post('/add', 'MenuController@create')->name('add-menu');
            Route::post('/{id}/update', 'MenuController@update')->name('update-menu');
            Route::delete('/{id}/delete', 'MenuController@destroy')->name('drop-menu');
        });
    });

});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
