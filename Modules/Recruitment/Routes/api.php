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

Route::middleware('auth:api')->get('/recruitment', function (Request $request) {
    return $request->user();
});

Route::prefix('recruitment')->group(function() {

    # sections
    Route::get('jobs-store','Api\ApiRecruitmentController@ShowJobs');//done
    Route::get('my-jobs-store','Api\ApiRecruitmentController@MyJobs')->middleware('CustomerAuth');
    Route::post('register','Api\ApiRecruitmentController@register');
    Route::post('login','Api\ApiRecruitmentController@login');
    Route::get('jobs-store-categories','Api\ApiRecruitmentController@filterCategories');
    Route::get('job-detials','Api\ApiRecruitmentController@ShowOneJob');
    Route::post('add-job','Api\ApiRecruitmentController@AddJob')->middleware('CustomerAuth');
    Route::post('apply-job','Api\ApiRecruitmentController@ApplyJob')->middleware('CustomerAuth');
    Route::get('job-applicants','Api\ApiRecruitmentController@Applicants')->middleware('CustomerAuth');

    Route::post('delete-job','Api\ApiRecruitmentController@deletejob')->middleware('CustomerAuth');
    Route::post('update-job','Api\ApiRecruitmentController@updatestorejobs')->middleware('CustomerAuth');

    Route::get('job-favorites','Api\ApiRecruitmentController@JobFavorites')->middleware('CustomerAuth');
    Route::post('add-to-job-favorites','Api\ApiRecruitmentController@AddToJobFavorites')->middleware('CustomerAuth');

    Route::post('remove-from-job-favorites','Api\ApiRecruitmentController@deletefavorite')->middleware('CustomerAuth');

    Route::get('filter-applicants','Api\ApiRecruitmentController@confirmApplicant');

    Route::get('application-details','Api\ApiRecruitmentController@applicationDetails')->middleware('CustomerAuth');

    Route::post('add-to-qualified-applicants','Api\ApiRecruitmentController@AddqualifiedApplicant')->middleware('CustomerAuth');


    Route::get('Companies','Api\ApiRecruitmentController@Companies')->middleware('CustomerAuth');



});
