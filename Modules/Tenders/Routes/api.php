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

// Route::middleware('auth:api')->get('/tenders', function (Request $request) {
//     return $request->user();
// });


Route::prefix('tenders')->group(function() {

    # sections
    Route::get('filter-sections-tenders','api\ApiTenController@filterSections');
    Route::get('tenders','api\ApiTenController@ShowNews');
    Route::get('tenders-detials','api\ApiTenController@ShwOneNew');

    Route::get('tenders-sections','api\ApiTenController@TenderSections');
    Route::get('filter-home','api\ApiTenController@filterHomeSections');

});