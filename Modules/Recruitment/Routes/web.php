<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('recruitment')->group(function () {
    Route::get('/', 'RecruitmentController@index');
});
Route::group(['middleware' => ['role', 'auth']], function () {


    #------------------------------- end of ShowsController -----------------------------#

    # sections
    Route::get('jobs-categories', [
        'uses' => 'RecruitmentController@Index',
        'as' => 'jobsCategories',
        'title' => 'إدارة الوظاثف',
        'subTitle' => ' الاقسام الرئيسية',
        'icon' => ' <i class="fas fa-building"></i> ',
        'subIcon' => ' <i class="fas fa-building"></i> ',
        'child' => [
            'storeCategories',
            'indexRecruiters',
            'editCategory',
            'deleteCategory',
            'storeEditCategory',
            'editRecruiter',
            'storeEditRecruiter',
            'indexJobs',
            'editJob',
            'storeEditJob',
            'deleteJob'
        ]

    ]);

    # store section
    Route::post('store-jobs-categories', [
        'uses' => 'RecruitmentController@store',
        'as' => 'storeCategories',
        'title' => 'إضافة قسم',
//        'icon' => '<i class="fas fa-boxes"></i>',
//        'hasFather' => true
    ]);

    # update section
    Route::Get('editCategory/{id}', [
        'uses' => 'RecruitmentController@UpdateCategory',
        'as' => 'editCategory',
        'title' => 'تحديث قسم'
    ]);
    # update section
    Route::post('store-Update-Category', [
        'uses' => 'RecruitmentController@storeUpdateCategory',
        'as' => 'storeEditCategory',
        'title' => 'حفظ قسم'
    ]);
    # update section
    Route::post('deleteCategory', [
        'uses' => 'RecruitmentController@deleteCategory',
        'as' => 'deleteCategory',
        'title' => 'مسح قسم'
    ]);


    # recruiters
    Route::get('jobs-recruiters', [
        'uses' => 'RecruitmentController@Recruiters',
        'as' => 'indexRecruiters',
        'title' => ' اصحاب الوظائف',
        'icon' => '<i class="fas fa-boxes"></i>',
        'hasFather' => true
    ]);

    # update section
    Route::Get('update-recruiter/{id}', [
        'uses' => 'RecruitmentController@UpdateRecruiter',
        'as' => 'editRecruiter',
        'title' => 'تحديث حالة عميل'
    ]);

    # update section
    Route::post('storeEditRecruiter', [
        'uses' => 'RecruitmentController@storeEditRecruiter',
        'as' => 'storeEditRecruiter',
        'title' => 'حفظ حالة العميل'
    ]);

    # recruiters
    Route::get('jobs', [
        'uses' => 'RecruitmentController@Jobs',
        'as' => 'indexJobs',
        'title' => 'الوظائف',
        'icon' => '<i class="fas fa-boxes"></i>',
        'hasFather' => true
    ]);

    # update section
    Route::Get('editJob/{id}', [
        'uses' => 'RecruitmentController@UpdateJob',
        'as' => 'editJob',
        'title' => 'تحديث قسم'
    ]);
    # update section
    Route::post('store-Update-Job', [
        'uses' => 'RecruitmentController@storeUpdateJob',
        'as' => 'storeEditJob',
        'title' => 'حفظ قسم'
    ]);
    # update section
    Route::post('deleteJob', [
        'uses' => 'RecruitmentController@deleteJob',
        'as' => 'deleteJob',
        'title' => 'مسح قسم'
    ]);

});
