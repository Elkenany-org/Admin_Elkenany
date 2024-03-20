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


Route::prefix('showes')->group(function() {

    # sections
    Route::get('all-showes','api\ApiShowsController@showshows');

    Route::get('filter-showes','api\ApiShowsController@filterSections');

    Route::get('one-show','api\ApiShowsController@Show');

    Route::get('one-show-review','api\ApiShowsController@oneShowrev');

    Route::get('one-show-showers','api\ApiShowsController@Showers');

    Route::get('one-show-speakers','api\ApiShowsController@speakers');

    Route::post('one-show-place','api\ApiShowsController@place');

    Route::post('one-show-reat','api\ApiShowsController@rating');

    Route::post('one-show-going','api\ApiShowsController@going')->middleware('CustomerAuth');

    Route::post('one-show-notgoing','api\ApiShowsController@notgoing')->middleware('CustomerAuth');

    Route::post('one-show-interested','api\ApiShowsController@inter')->middleware('CustomerAuth');

    Route::post('one-show-notinterested','api\ApiShowsController@notinter')->middleware('CustomerAuth');
   
  
});


Route::post('contuct-us','api\ApiShowsController@contuct');

Route::get('about-us','api\ApiShowsController@about');

Route::get('contuct-us-office','api\ApiShowsController@offices');