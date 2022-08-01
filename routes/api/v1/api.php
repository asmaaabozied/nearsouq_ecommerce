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
    Route::post('/testing', 'api\v1\AuthController@testing');
    Route::middleware(['auth:api'])->post('/notify', 'api\v1\NotificationController@sendNotify');

});


Route::group(['prefix' => '{locale}'], function () {
//
    //auth login user 

    Route::post('register', 'api\v1\AuthController@register');

    Route::post('login', 'api\v1\AuthController@login');

    Route::post('resetpassword', 'api\v1\AuthController@send_verification_code');

    Route::post('resetpassword2', 'api\v1\AuthController@resetpassword');

    Route::post('changepassword', 'api\v1\AuthController@changepassword')->middleware(['auth:api']);

    Route::post('updateprofile', 'api\v1\AuthController@updateprofile')->middleware(['auth:api']);

    Route::get('logout', 'api\v1\AuthController@logout')->middleware(['auth:api']);

    Route::get('showprofile', 'api\v1\AuthController@showprofile')->middleware(['auth:api']);

    Route::post('addAddress', 'api\v1\AuthController@addAddress')->middleware(['auth:api']);
    Route::post('updateAddress', 'api\v1\AuthController@updateAddress')->middleware(['auth:api']);

    Route::get('showAddress', 'api\v1\AuthController@showAddress')->middleware(['auth:api']);
     Route::post('deleteAddress', 'api\v1\AuthController@deleteAddress')->middleware(['auth:api']);
       Route::get('getWallet', 'api\v1\AuthController@getWallet')->middleware(['auth:api']);
    Route::post('deleteAccount', 'api\v1\AuthController@deleteAccount')->middleware(['auth:api']);
    Route::post('logoutDevices', 'api\v1\AuthController@deleteSessions')->middleware(['auth:api']);
    Route::put('onlineStatus', 'api\v1\HomeController@onlineStatus')->middleware(['auth:api']);

    // end auth user 
    
    
    
    // Payment
    Route::get('showPaymentmethod', 'api\v1\PaymentController@showPaymentmethod')->middleware(['auth:api']);
    
    Route::post('paymentRequest', 'api\v1\PaymentController@paymentRequest');
    Route::post('paymentStatus', 'api\v1\PaymentController@paymentStatus');
     // end payment


    //shops
    Route::get('showshop', 'api\v1\ShopController@showshop');
    Route::get('shops', 'api\v1\ShopController@index');
    // end shops
    

    //malls
    Route::get('showmall', 'api\v1\MallController@showmall');
    Route::get('malls', 'api\v1\MallController@index');
    Route::get('RatingMallShop', 'api\v1\MallController@RatingMallShop');
    Route::get('MakeRatingMallShop', 'api\v1\MallController@MakeRatingMallShop')->middleware(['auth:api']);

    //end malls
    

    //categories
    Route::get('categories', 'api\v1\ShopController@getCategories');
    // end categories


    //setting
    Route::get('setting', 'api\v1\SettingController@getSetting');
    
    Route::get('pages', 'api\v1\PageController@GetPages');
    Route::post('sendMessage','api\v1\PageController@sendMessage')->middleware(['auth:api']);

     
    // end setting


    //Products
    Route::get('offerProduct', 'api\v1\ProductController@offerProduct');
    Route::get('indexProduct', 'api\v1\ProductController@indexProduct');
    Route::get('showProduct', 'api\v1\ProductController@showProduct');
    Route::post('addQuantity', 'api\v1\ProductController@addQuantity');
    Route::post('addRating', 'api\v1\ProductController@addRating')->middleware(['auth:api']);
    Route::get('showRating', 'api\v1\ProductController@showRating');
    Route::get('showProductWithCategories', 'api\v1\ProductController@showProductWithCategories');
    Route::post('AddFavouriteProduct', 'api\v1\ProductController@AddFavouriteProduct')->middleware(['auth:api']);
    Route::post('AddVisitorProduct', 'api\v1\ProductController@AddVisitorProduct')->middleware(['auth:api']);
    Route::get('ShowFavouriteProduct', 'api\v1\ProductController@ShowFavouriteProduct')->middleware(['auth:api']);
    Route::get('relatedProduct', 'api\v1\ProductController@relatedProduct');

     // end of products


    //homepages in applications mobile app
    Route::get('home', 'api\v1\HomeController@home');
    Route::get('generalSearch', 'api\v1\HomeController@search');
    Route::get('ipInfo', 'api\v1\SettingController@ipInfo');
   //end of homepages in applications mobile app


    //notification
    Route::get('ShowNotification', 'api\v1\NotificationController@ShowNotification')->middleware(['auth:api']);
    //end notification
    

    //orders
    Route::get('showOrder', 'api\v1\OrderController@showOrder')->middleware(['auth:api']);
    Route::get('listOfOrder', 'api\v1\OrderController@listOfOrder')->middleware(['auth:api']);
    Route::get('listOfOrderWithType', 'api\v1\OrderController@listOfOrderWithType')->middleware(['auth:api']);
    Route::get('canceledOrder', 'api\v1\OrderController@canceledOrder')->middleware(['auth:api']);
    Route::get('confirmOrder', 'api\v1\OrderController@confirmOrder')->middleware(['auth:api']);
    Route::post('addOrder', 'api\v1\OrderController@addOrder')->middleware(['auth:api']);
    Route::post('addCart', 'api\v1\OrderController@addCart')->middleware(['auth:api']);
    Route::delete('deleteCart', 'api\v1\OrderController@deleteCart')->middleware(['auth:api']);
    Route::get('showCart', 'api\v1\OrderController@showCart')->middleware(['auth:api']);
    Route::get('TotalDeliveryCost', 'api\v1\OrderController@TotalDeliveryCost')->middleware(['auth:api']);
    Route::get('listOfReason', 'api\v1\OrderController@listOfReason');
    // end orders
    
    //deliveryApp
    Route::get('ordersForDelivery', 'api\v1\DeliveryApp\DeliveryController@index')->middleware(['auth:api']);
    Route::put('changeStatusDelivered', 'api\v1\DeliveryApp\DeliveryController@changeStatusDelivered')->middleware(['auth:api']);
    Route::get('deliveryReasons', 'api\v1\DeliveryApp\DeliveryController@deliveryReasons')->middleware(['auth:api']);
    Route::get('orderdetails', 'api\v1\DeliveryApp\DeliveryController@orderDetails')->middleware(['auth:api']);
    Route::get('nearestDelivery', 'api\v1\DeliveryApp\DeliveryController@nearestDelivery')->middleware(['auth:api']);
    Route::post('changeStatus', 'api\v1\DeliveryApp\DeliveryController@changeStatus')->middleware(['auth:api']);
    Route::get('captainOrders', 'api\v1\DeliveryApp\DeliveryController@captainOrders')->middleware(['auth:api']);
    Route::get('relatedOrders', 'api\v1\DeliveryApp\DeliveryController@relatedOrders')->middleware(['auth:api']);
    Route::get('deliveryReport', 'api\v1\DeliveryApp\DeliveryController@deliveryReport')->middleware(['auth:api']);

    //endDeliveryApp

});



