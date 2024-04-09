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

Route::group(['middleware' => ['role','auth']], function() {

 #------------------------------- start of tendersController -----------------------------#

	# tenders
	Route::get('tenders-sections',[
		'uses' =>'TendersSectionsController@Index',
		'as'   =>'tenderssections',
		'title'=>'إدارة  المناقصات',
		'subTitle'=>'  الاقسام',
		'icon' =>'<i class="fas fa-gavel"></i>',
		'subIcon' =>'<i class="fas fa-building"></i>',
		'child'=>[
			'storetenderSections',
			'updatetenderSections',
			'deletetenderssection',
			'tenders',
			'Addtenders',
			'Storetenders',
			'Deletetenders',
			'Edittenders',
            'Updatetenders',
            'selecttenderSections',
            'tenderajax'
		]
	]);


	# store section
	Route::post('store-tenders-sections',[
		'uses'=>'TendersSectionsController@Store',
		'as'  =>'storetenderSections',
		'title'=>'إضافة قسم'
	]);

	# update section
	Route::post('update-tenders-sections',[
		'uses'=>'TendersSectionsController@Update',
		'as'  =>'updatetenderSections',
		'title'=>'تحديث قسم'
	]);

    # update section
    Route::post('select-tenders-sections',[
        'uses'=>'TendersSectionsController@select',
        'as'  =>'selecttenderSections',
        'title'=>'تحديد قسم'
    ]);

    # delete section
	Route::post('delete-tenders-sections',[
		'uses'=>'TendersSectionsController@Delete',
		'as'  =>'deletetenderssection',
		'title'=>'حذف قسم'
	]);
	
    #tenders
	Route::get('tenders',[
		'uses'=>'TendersController@Index',
		'as'  =>'tenders',
		'title'=>' المناقصات',
		'icon' =>'<i class="fas fa-gavel"></i>',
		'hasFather'=>true
	]);

	# add tenders
	Route::get('add-tenders',[
		'uses'=>'TendersController@Addtenders',
		'as'  =>'Addtenders',
		'icon' =>'<i class="fas fa-plus"></i>',
		'title'=>'إضافة  مناقصة جديد',
		'hasFather'=>true
	]);

	# store tenders
	Route::post('store-tenders',[
		'uses'=>'TendersController@Storetenders',
		'as'  =>'Storetenders',
		'title'=>'حفظ المناقصة'
	]);

	# edit tenders
	Route::get('edit-tenders/{id}',[
		'uses'=>'TendersController@Edittenders',
		'as'  =>'Edittenders',
		'title'=>'تعديل المناقصة'
	]);

	# update tenders
	Route::post('update-tenders',[
		'uses'=>'TendersController@Updatetenders',
		'as'  =>'Updatetenders',
		'title'=>'تحديث المناقصة'
	]);

	# delete tenders
	Route::post('delete-tenders',[
		'uses'=>'TendersController@Deletetenders',
		'as'  =>'Deletetenders',
		'title'=>'حذف المناقصة'
    ]);

    # get showes (ajax)
    Route::post('get-tenders-ser',[
        'uses'=>'TendersController@tenderajax',
        'as'  =>'tenderajax',
        'title'=>'جلب  المعارض'
    ]);

	#------------------------------- end of tendersController -----------------------------#
});
//
//// tenders
Route::get('tenders-section/{name}',[
	'uses'=>'TendersFrontController@tenders',
	'as'  =>'front_section_tenders',
	'type'=>'main',
	'kind'=>'tenders',
	'title'=>'الرئيسية للاخبار ',
]);
//
//// tenders sort
//Route::get('tenders-section-view/{name}',[
//	'uses'=>'TendersFrontController@tenderssort',
//	'as'  =>'front_section_tenders_view',
//	'type'=>'main',
//	'kind'=>'tendersview',
//	'title'=>'الرئيسية للاخبار حسب التداول',
//]);
//
//// sections search
//Route::post('get-section-tenders-search', 'TendersFrontController@datas')->name('front_section_datas_tenders');
//
//
//// sections search
//Route::post('get-section-tenders-search-more', 'TendersFrontController@mores')->name('front_section_datas_tenders_more');
//
//
//// tenders sort
//Route::get('one-tenders/{id}',[
//	'uses'=>'TendersFrontController@onetenders',
//	'as'  =>'front_one_tenders',
//	'type'=>'sub',
//	'kind'=>'tendersone',
//	'title'=>'المناقصة',
//]);