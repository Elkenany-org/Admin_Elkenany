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

Route::prefix('ships')->group(function() {

    # sections
    Route::get('all-ships','api\ApishipsController@ships');

    Route::get('Fillter-ships','api\ApishipsController@Filterships');


    Route::get('statistics-ships','api\ApishipsController@statisticsship');

    Route::get('statistics-ships-detials','api\ApishipsController@detials');





});


Route::prefix('international')->group(function() {

   
    Route::get('filter','api\ApiinterController@filter');
    Route::get('reports','api\ApiinterController@Showreports');
    Route::get('report-detials','api\ApiinterController@ShwOnereport');

    Route::get('last-news','api\ApiinterController@Showlast');
    Route::get('last-news-detials','api\ApiinterController@ShwOnelastt');

    Route::get('analysis-tec','api\ApiinterController@Showanalysis');
    Route::get('analysis-detials','api\ApiinterController@ShwOneanalysis');


    Route::get('bodcasts','api\ApiinterController@bodcasts');
    Route::get('bodcast-detials','api\ApiinterController@bodcast');





});