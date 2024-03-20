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

// Route::middleware('auth:api')->get('/news', function (Request $request) {
//     return $request->user();
// });

# start of ApiNewsController
Route::prefix('news')->group(function() {

    # sections
    Route::get('filter-sections-news','api\ApiNewsController@filterSections');
    Route::get('news','api\ApiNewsController@ShowNews');
    Route::get('news-detials','api\ApiNewsController@ShwOneNew');



});

# end of ApiNewsController

Route::prefix('v2/news')->namespace('api\v2')->group(function() {
    Route::get('/','NewsController@index');
    Route::get('show','NewsController@show');
    Route::get('/filter-list','NewsController@filter_list');
});

