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

Route::middleware('auth:api')->get('/fodderstock', function (Request $request) {
    return $request->user();
});


Route::prefix('v2')->namespace('Api\v2')->group(function() {

    Route::prefix('fodder')->group(function() {

        Route::get('tables','FodderStockController@fodder_tables');

    });
    Route::prefix('fodder-android')->group(function() {

        Route::get('tables','FodderStockController@new_fodder_tables');

    });
});

////my new api for FODDER statistics members
Route::prefix('fodderstock')->namespace('Api\v2')->group(function() {
    Route::get('statistics-Fodderstock-members', 'FodderStockController@statisticsFoddermembers')->middleware('premiumCustomer');
    Route::get('statistics-Fodderstock-list', 'FodderStockController@statisticsFodderlist')->middleware('premiumCustomer');
    Route::get('statistics-Fodderstock-members-android', 'FodderStockController@statisticsFoddermembers_android')->middleware('premiumCustomer');

});


