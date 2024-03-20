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

// Route::middleware('auth:api')->get('/consultants', function (Request $request) {
//     return $request->user();
// });

# start of ApiMajorsController


# end of ApiMajorsController

# start of ApiDoctorsController

# end of ApiDoctorsController

Route::prefix('consultants')->group(function() {
    Route::get('/','api\ApiDoctorsController@index');

    Route::get('major','api\ApiMajorsController@showmajors');
    Route::get('major-filter','api\ApiMajorsController@filterSections');

    Route::get('doctors','api\ApiDoctorsController@showdoctors');
    Route::get('doctors-filter','api\ApiDoctorsController@filterSection');

    Route::get('doctors-sub-filter','api\ApiDoctorsController@FilterSubs');

    Route::get('my-orders','api\ApiDoctorsController@res')->middleware('CustomerAuth');




    Route::get('doctor','api\ApiDoctorsController@showdoctor');

    Route::post('doctor-rating','api\ApiDoctorsController@updaterating')->middleware('CustomerAuth');

    Route::get('doctor-services','api\ApiDoctorsController@showservices');

    Route::get('order-service','api\ApiDoctorsController@storeorder')->middleware('CustomerAuth');



});