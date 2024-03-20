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

// Route::middleware('auth:api')->get('/localstock', function (Request $request) {
//     return $request->user();
// });
//test sara hh
Route::prefix('localstock')->group(function() {

    # sections
    Route::get('local-stock-sections','api\ApiStockController@ShowSections');
    
    Route::get('all-local-stock-sections','api\ApiStockController@filterSections');

    Route::post('filter-stock-sub-sections','api\ApiStockController@SubFilter');

    Route::get('statistics-stock-sections','api\ApiStockController@statisticsSections');

    Route::get('local-stock-show-sub-section','api\ApiStockController@showmembers');
    Route::get('local-stock-show-sub-section-ios','api\ApiStockController@showmembersInIOS');

    Route::get('new-local-stock-show-sub-section','api\ApiStockController@newshowmembers');

    Route::get('feeds-items','api\ApiStockController@feeds_items');

    Route::get('companies-items','api\ApiStockController@companies_items');

    Route::get('fodder-stock-show-sub-section','api\ApiStockController@showMembersFodder');

    Route::get('filter-stock-show-sub-section','api\ApiStockController@GetSectionsInFilter');

    Route::get('statistics-stock-members','api\ApiStockController@statisticsmembers');
    ////my new api for local statistics members
    Route::get('statistics-Localstock-members','api\ApiStockController@statisticsLocalmembers')->middleware('premiumCustomer');
/////////////////////////
    
    Route::get('comprison-fodder','api\ApiStockController@comprisonfodder');

    Route::post('comprison-fodder-get','api\ApiStockController@Getcompaniesfodder');

    Route::get('statistics-detials','api\ApiStockController@detials');

    Route::get('statistics-detials-local-stock','api\ApiStockController@detialslocal');

    Route::prefix('v2')->namespace('api\v2')->group(function() {
        Route::get('local-stock-sub-section','StockController@refactory');
    });
  
});
Route::prefix('v2')->namespace('api\v2')->group(function() {

    Route::prefix('local')->group(function() {

        Route::get('tables','LocalStockController@local_tables');

    });
    Route::prefix('local-android')->group(function() {

        Route::get('tables','LocalStockController@new_local_tables');

    });
});
# start of ApiStockController

# end of ApiStockController