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
Route::prefix('magazine')->group(function() {

    # sections
    Route::get('filter-sections-magazines','api\ApimagazineController@filterSections');
    Route::get('magazines','api\ApimagazineController@showMagazines');
    Route::post('filter-get-magazines','api\ApimagazineController@FilterMagazines');
    Route::get('magazine-detials','api\ApimagazineController@ShowMagazine');

    Route::get('show','api\ApimagazineController@show');

    Route::get('magazine-gallary','api\ApimagazineController@gallarymagazines');


    Route::post('rating-magazine','api\ApimagazineController@rating')->middleware('CustomerAuth');
    Route::post('update-rating-magazine','api\ApimagazineController@updaterating')->middleware('CustomerAuth');

});