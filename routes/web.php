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
/* Route::post('/get_state', 'FilterController@get_state');
Route::post('/get_city', 'FilterController@get_city'); */

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


}); 