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

Route::get('/admin', function () {
    return redirect()->route('dashboard.welcome');
    //return Redirect::to('public/ar/dashboard');
});


//Route::get('/home', 'HomeController@index')->name('home');

Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']],
    function () {
        Auth::routes(['register' => false]);
        Route::get('/', 'WebsiteController@index')->name('welcome');
        Route::get('/terms', 'WebsiteController@terms')->name('terms');
        Route::get('/sendMessage', 'WebsiteController@sendMessage')->name('sendMessage');
        Route::prefix('dashboard')->name('dashboard.')->middleware(['auth'])->group(function () {
            //user routes


        });
    });

;
