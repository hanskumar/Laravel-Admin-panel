<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('pages/login');
});

Route::get('/no-access', function () {
    return view('pages/403');
});

//===============Filter function==================//
Route::post('/get_state', 'FiltersController@get_state');
Route::post('/get_city', 'FiltersController@get_city'); 

Route::get('/logout', 'LoginController@logout');
Route::post('/login','LoginController@index');


/*
|--------------------------------------------------------------------------
| =============All given routes can access only after login==============
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => 'user_logged_in'], function () {
    Route::get('/dashboard','DashboardController@index');

    Route::GET('/category-list', 'CategoryController@index');
    Route::POST('/category/getCategries/','CategoryController@getCategries')->name('category.getCategries');
    Route::any('/add-category', 'CategoryController@add');


    Route::any('/add-product', 'ProductController@add');
    Route::any('/product-list', 'ProductController@list');
    Route::POST('/product-data', 'ProductController@data')->name('product_data');
    Route::GET('/download-product', 'ProductController@download');


    Route::any('/add-pincode', 'PincodeController@add');
    Route::GET('/pincode-list', 'PincodeController@list');
    Route::POST('/pincode-data', 'PincodeController@data')->name('pincode_data');
    Route::GET('/download-pincodes', 'PincodeController@download');

    Route::any('/add-brand', 'BrandController@add');
    Route::GET('/brand-list', 'BrandController@list');
    Route::POST('/brand-data', 'BrandController@data')->name('brand_data');


    Route::GET('/user-list', 'UserController@list');
    Route::POST('/user-data', 'UserController@data')->name('user_data');
    Route::GET('/download-user', 'UserController@download');


    Route::any('/send-noti', 'NotificationController@add');
    Route::GET('/noti-list', 'NotificationController@list');
    Route::POST('/noti-data', 'NotificationController@data')->name('noti_data');


    Route::GET('/orders', 'OrderController@list');
    Route::POST('/order-data', 'OrderController@data')->name('order_data');


}); 