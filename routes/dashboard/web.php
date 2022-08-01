<?php

Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']],
    function () {

        Route::prefix('dashboard')->name('dashboard.')->group(function () {


            // Start shops using auth or not auth when register


            Route::get('register', 'BrachesController@Register')->name('register');
            Route::post('save-shop', 'ShopController@SaveShop')->name('save-shop');
            Route::get('terms', 'BrachesController@termsPage')->name('terms');
            Route::post('checkCommerical', 'BrachesController@checkCommericalNumber')->name('checkCommerical');



            //End shops


        });
        Route::prefix('dashboard')->name('dashboard.')->middleware(['auth'])->group(function () {
            Route::get('/', 'WelcomeController@index')->name('welcome');
            //user routes
            Route::resource('users', 'UserController');
            Route::resource('deliverycost', 'DeliveryCostController');

            Route::post('users/block/{id}', 'UserController@block')->name('users.block');
            Route::get('showshop/{id}', 'UserController@showshop')->name('showshop');
            //roles
            Route::resource('roles', 'RoleController')->except(['show']);
            //logout
            Route::get('logout', 'UserController@logout')->name('logout');



            Route::get('changenotificationsstatus', 'NotificationController@changenotificationsstatus')->name('changenotificationsstatus');


            //shops

            Route::get('show-shop/{id}', 'ShopController@Getbrances')->name('showbrances');
            Route::post('SaveShopUser', 'UserController@SaveShopUser')->name('SaveShopUser');

            Route::get('updateshopsession/{id}', 'ShopController@updateshopsession')->name('updateshopsession');
            Route::get('shops/block/{id}', 'ShopController@block')->name('shops.block');

            Route::resource('shops', 'ShopController')->except(['show']);

            Route::get('brances', 'ShopController@brances')->name('brances');
            Route::get('brances/create', 'BrachesController@brances')->name('brances.create');
            Route::post('addbracnch', 'BrachesController@addbracnch')->name('addbracnch');

            Route::get('ShowShopsAuth', 'ShopController@ShowShopsAuth')->name('ShowShopsAuth');
            Route::get('CheckedUserShop', 'ShopController@CheckedUserShop')->name('CheckedUserShop');

            Route::post('saveshopuser', 'BrachesController@saveshopuser')->name('saveshopuser');

            Route::get('shop-register', 'BrachesController@ShopRegister')->name('shop-register');


            Route::post('edit-shop/{id}', 'BrachesController@editshop')->name('edit-shop');

            //end of shops
            //pages
            Route::resource('pages', 'PageController');
            Route::get('pages/show/{id}', 'PageController@show')->name('pages.show');

            //versions
            Route::resource('versions', 'VersionController');
             Route::get('versions/block/{id}', 'VersionController@block')->name('versions.block');


          //reasons
            Route::resource('reasons', 'ReasonController');

            //options
            Route::resource('options', 'OptionController');
            //products
            Route::resource('products', 'ProductController');
            Route::get('CheckedProduct', 'ProductController@CheckedProduct')->name('CheckedProduct');
            Route::get('getshopProduct/{id}', 'ProductController@getshopProduct')->name('getshopProduct');

            Route::get('files_product', 'ProductController@filesProduct')->name('files_product');
            Route::post('uploadecxel', 'ProductController@uploadecxel')->name('uploadecxel');
            //end products

            //wallets
            Route::resource('wallets', 'WalletController');
            Route::post('openmodal', 'WalletController@openmodal')->name('openmodal');
            //end wallets

            //banners
            Route::resource('banners', 'BannerController');
            //transacions
            Route::resource('Transaction', 'TransactionController');
            Route::post('TransactionFile', 'TransactionController@TransactionFile')->name('TransactionFile');
            //end transations

            //orders
            Route::resource('orderDetail', 'OrderDetailController')->except(['show']);
            Route::get('order/index/{type}', 'OrderController@index')->name('order.index');
            Route::resource('order', 'OrderController')->except(['show','index']);
            Route::get('order/details/{id}', 'OrderDetailController@index')->name('order.details');
            Route::get('order/history/{id}', 'OrderController@history')->name('order.history');
            Route::get('orderDetail/history/{id}', 'OrderDetailController@history')->name('orderDetail.history');
            //end orders
            //malls
            Route::resource('malls', 'MallController');
            //categories
            Route::resource('categories', 'CategoryController');
            //notifications
            Route::resource('notifications', 'NotificationController');
            Route::any('readNotification','NotificationController@readNotification');
            Route::any('saveOneSigalId','NotificationController@saveOneSigalId');

            //settings
            Route::resource('settings', 'SettingsController');
            Route::post('deleteProductImage', 'ProductController@deleteImage')->name('deleteImage');
            Route::resource('userReports', 'UserReportController');

            //transations
            Route::get('showTransactions/{id}', 'TransactionsPageController@index')->name('showTransactions');
            Route::resource('shop_settings', 'ShopSettingController');


            //reports
            Route::get('usersReports', 'UserReportController@getReportUsers')->name('usersReports');
            Route::get('ShopsReports', 'UserReportController@getReportShops')->name('ShopsReports');
            Route::get('ProductsReports', 'UserReportController@getReportProducts')->name('ProductsReports');
            Route::get('OrdersReports', 'UserReportController@getReportOrders')->name('OrdersReports');


            //deliveries
            Route::resource('deliveries', 'DeliveryController');
            Route::get('deliveries/address/{id}', 'DeliveryController@showAddress')->name('deliveries.address');
            Route::get('deliveries/showCaptainOrders/{captain_id}', 'DeliveryController@showCaptainOrders')->name('deliveries.showCaptainOrders');
            Route::get('deliveries/showMap', 'DeliveryController@show')->name('users.showMap');


        });//end of dashboard routes


    });



