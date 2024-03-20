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

// Route::middleware('auth:api')->get('/guide', function (Request $request) {
//     return $request->user();
// });

# start of ApiSectionController
Route::prefix('guide')->group(function() {

    # sections
    Route::get('section','api\ApiSectionController@showSubSections');
    Route::get('all-filter-guide-sub-sections','api\ApiSectionController@filterSections');

    # companies
    Route::get('sub-section','api\ApicompanyController@showCompanies');

    Route::get('filter-guide-companies','api\ApicompanyController@filterSections');

    Route::get('filter-company-sub-sections','api\ApicompanyController@FilterSubs');

    Route::get('filter-company-country','api\ApicompanyController@Filtercount');
  
    Route::get('company','api\ApicompanyController@ShowCompany');

    Route::get('new-company','api\ApicompanyController@NewShowCompany');

    Route::get('transports-company','api\ApicompanyController@transportsCompany');
    Route::get('gallary-company','api\ApicompanyController@gallaryCompany');

    Route::post('rating-company','api\ApicompanyController@rating')->middleware('CustomerAuth');
    Route::post('update-rating-company','api\ApicompanyController@updaterating')->middleware('CustomerAuth');
});

Route::get('home-sectors','api\ApiSectionController@sectors');
Route::get('home-services','api\ApiSectionController@service');
Route::get('sponsers','api\ApiSectionController@sponsers');

Route::get('notfications','api\ApiSectionController@nots');

Route::get('search-all','api\ApiSectionController@ser');
Route::get('search-companies','api\ApiSectionController@serCompanies');

Route::get('search-all-ios','api\ApiSectionController@searchIos');

Route::get('profile','api\ApiSectionController@profile')->middleware('CustomerAuth');


Route::post('profile-update','api\ApiSectionController@UpdateCustomer')->middleware('CustomerAuth');

Route::get('noty-ads','api\ApiSectionController@adsnot');

Route::get('poup-up','api\ApiSectionController@poups');


Route::prefix('v2/guide')->namespace('api\v2')->group(function() {

    Route::get('index','CompanyController@index');
    Route::get('show','CompanyController@show');
    Route::get('filter-companies','CompanyController@filter_companies');

});

Route::get('test',function (){
    \Illuminate\Support\Facades\Log::info('message for logging',[
        'user_id' => 1
    ]);
});