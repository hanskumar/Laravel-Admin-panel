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

    Route::get('/category-list', 'CategoryController@index');
    Route::get('/category/getCategries/','CategoryController@getCategries')->name('category.getCategries');
    Route::any('/add-category', 'CategoryController@add');


    Route::any('/add-product', 'ProductController@add');
    Route::any('/product-list', 'ProductController@list');

    Route::POST('/product-data', 'ProductController@data')->name('product_data');;


    Route::any('/add-pincode', 'PincodeController@add');
    Route::any('/pincode-list', 'PincodeController@list');


    Route::any('/add-brand', 'BrandController@add');
    Route::any('/brand-list', 'BrandController@list');


    Route::any('/send-noti', 'NotificationController@add');


}); 