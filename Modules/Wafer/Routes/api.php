<?php

use Illuminate\Http\Request;

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

// Route::middleware('auth:api')->get('/wafer', function (Request $request) {
//     return $request->user();
// });


# start of ApiWaferSectiosController
// sections
Route::get('wafer-sections','api\ApiWaferSectiosController@showsections');
// section
Route::get('wafer-section/{id}','api\ApiWaferSectiosController@showsection');
// posts
Route::get('wafer-section-posts/{id}','api\ApiWaferSectiosController@showposts');
# end of ApiWaferSectiosController

# start of ApiWaferFarmersController
// farmer
Route::get('wafer-farmer/{id}','api\ApiWaferFarmersController@showfarmer');
// farmer posts
Route::get('wafer-farmer-posts/{id}','api\ApiWaferFarmersController@showfposts');
// post
Route::get('wafer-post/{id}','api\ApiWaferFarmersController@showpost');
// orders
Route::get('wafer-post-orders/{id}','api\ApiWaferFarmersController@showorders');
// cars
Route::get('wafer-order-cars/{id}','api\ApiWaferFarmersController@showorder');
// orders management
Route::get('wafer-orders-management','api\ApiWaferFarmersController@showmanageorders');
// store wafer post
Route::post('store-wafer-post','api\ApiWaferFarmersController@addpost');
// store wafer order management
Route::post('store-wafer-order-management','api\ApiWaferFarmersController@addorderm');
// store wafer order
Route::post('store-wafer-order','api\ApiWaferFarmersController@addorderp');
# end of ApiWaferFarmersController