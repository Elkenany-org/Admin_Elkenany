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

// Route::middleware('auth:api')->get('/academy', function (Request $request) {
//     return $request->user();
// });



# start of ApiCoursesController


Route::prefix('academy')->group(function() {
    
    Route::get('academy-cources','api\ApiCoursesController@showcources');

    Route::get('academy-cource-live','api\ApiCoursesController@showcourcelive')->middleware('CustomerAuth');

    Route::get('academy-cource-offline','api\ApiCoursesController@showcourceoffline')->middleware('CustomerAuth');

    Route::get('academy-cource-online','api\ApiCoursesController@showcourceonline')->middleware('CustomerAuth');

    Route::post('academy-cource-live-going','api\ApiCoursesController@goinglive')->middleware('CustomerAuth');

    Route::post('academy-cource-offline-going','api\ApiCoursesController@goingoffline')->middleware('CustomerAuth');

    Route::post('academy-cource-online-going','api\ApiCoursesController@goingonline')->middleware('CustomerAuth');

    Route::get('academy-cources-all-live','api\ApiCoursesController@lives');
 
    Route::get('academy-cources-all-offline','api\ApiCoursesController@offlines');

    Route::get('academy-cources-all-online','api\ApiCoursesController@onlines');


    Route::post('academy-cource-online-watch','api\ApiCoursesController@watch')->middleware('CustomerAuth');

    Route::get('my-cources','api\ApiCoursesController@mycourses')->middleware('CustomerAuth');

    Route::get('exams-cources','api\ApiCoursesController@exams')->middleware('CustomerAuth');

    Route::get('exams-cources-questions','api\ApiCoursesController@exam')->middleware('CustomerAuth');

    Route::post('academy-cource-quize-answer','api\ApiCoursesController@quize')->middleware('CustomerAuth');

    Route::get('exams-cources-archive','api\ApiCoursesController@archive')->middleware('CustomerAuth');

    Route::get('cources-certificates','api\ApiCoursesController@certificates')->middleware('CustomerAuth');

    Route::get('cources-certificate','api\ApiCoursesController@certificate')->middleware('CustomerAuth');

    Route::post('academy-cource-comment','api\ApiCoursesController@comment')->middleware('CustomerAuth');

    Route::post('academy-cource-replay','api\ApiCoursesController@replay')->middleware('CustomerAuth');



});
// cources



