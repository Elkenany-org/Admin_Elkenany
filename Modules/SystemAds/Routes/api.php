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

Route::post('ads-login','api\ApiAdsController@login')->middleware('cors');

Route::prefix('ads')->group(function() {


    Route::get('ads-companies','api\ApiAdsController@creatAdscompany')->middleware('AdsAuth');
    Route::post('ads-system-create','api\ApiAdsController@Storesystemads')->middleware('AdsAuth');

    Route::get('ads-for-one-company','api\ApiAdsController@Adscompany')->middleware('AdsAuth');
    Route::get('get-sections','api\ApiAdsController@getSections')->middleware('AdsAuth');

    Route::get('get-sub-sections','api\ApiAdsController@getsubSections')->middleware('AdsAuth');

    Route::post('ads-system-edit','api\ApiAdsController@updateads')->middleware('AdsAuth');

    Route::get('ads-profile','api\ApiAdsController@profile')->middleware('AdsAuth');

    Route::post('ads-system-edit-password','api\ApiAdsController@editprofile')->middleware('AdsAuth');

    Route::post('check-login','api\ApiAdsController@check')->middleware('cors');

    Route::get('edit-ads-detials','api\ApiAdsController@editads')->middleware('AdsAuth');

    Route::post('ads-system-update-detials','api\ApiAdsController@updads')->middleware('AdsAuth');

    Route::get('company-links','api\ApiAdsController@company_links')->middleware('AdsAuth');
    Route::post('notification-create','api\ApiAdsController@notificationAds')->middleware('AdsAuth');

});
