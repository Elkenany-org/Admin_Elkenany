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

Route::prefix('shows')->group(function() {
    Route::get('/', 'ShowsController@index');
});
Route::group(['middleware' => ['role','auth']], function() {


	

	#------------------------------- end of ShowsController -----------------------------#

	# sections
	Route::get('shows-sections',[
		'uses' =>'showSectionsController@Index',
		'as'   =>'showssections',
		'title'=>'إدارة المعارض',
		'subTitle'=>' الاقسام الرئيسية',
		'icon' =>' <i class="fas fa-building"></i> ',
		'subIcon' =>' <i class="fas fa-building"></i> ',
		'child' =>[
			'storeshowssections',
			'updateshowssection',
			'deleteshowssection',
			'indexorgan',
			'Storeorgan',
			'Updateorgan',
			'Deleteorgan',
			'showes',
			'showajax',
			'addshow',
			'storeshow',
			'editshow',
			'updateshow',
			'Updatetac',
			'storeImagesshow',
			'deleteimagesshow',
			'storeshower',
			'updateshower',
			'Deleteshower',
			'storespeaker',
			'updatespeaker',
			'Deletespeaker',
			'deleteshow',
			'places',
			'deletplaces',
            'selectshowssection'

            
			
		]

	]);


	# store section
	Route::post('store-shows-sections',[
		'uses'=>'showSectionsController@Store',
		'as'  =>'storeshowssections',
		'title'=>'إضافة قسم'
	]);

	# update section
	Route::post('update-shows-sections',[
		'uses'=>'showSectionsController@Update',
		'as'  =>'updateshowssection',
		'title'=>'تحديث قسم'
	]);

    # update section
    Route::post('select-shows-sections',[
        'uses'=>'showSectionsController@select',
        'as'  =>'selectshowssection',
        'title'=>'تحديد قسم'
    ]);
	# delete section
	Route::post('delete-shows-sections',[
		'uses'=>'showSectionsController@Delete',
		'as'  =>'deleteshowssection',
		'title'=>'حذف قسم'
	]);

	# organisers
	Route::get('organisers',[
		'uses'=>'showSectionsController@indexorgan',
		'as'  =>'indexorgan',
		'title'=>'  ادارة الجهات المنظمة',
		'icon' =>'<i class="fas fa-boxes"></i>',
		'hasFather'=>true
	]);
	
	# store organisers
	Route::post('store-organisers',[
		'uses'=>'showSectionsController@Storeorgan',
		'as'  =>'Storeorgan',
		'title'=>'إضافة جيهة'
	]);

	# update organisers
	Route::post('update-organisers',[
		'uses'=>'showSectionsController@Updateorgan',
		'as'  =>'Updateorgan',
		'title'=>'تحديث جيهة'
	]);

	# delete organisers
	Route::post('delete-organisers',[
		'uses'=>'showSectionsController@Deleteorgan',
		'as'  =>'Deleteorgan',
		'title'=>'حذف جيهة'
	]);

	# showes
	Route::get('showes',[
		'uses'=>'ShowsController@index',
		'as'  =>'showes',
		'title'=>' قائمة المعارض',
		'icon' =>'<i class="fas fa-puzzle-piece"></i>',
		'hasFather'=>true
	]);

	# get showes (ajax)
	Route::post('get-showes-ser',[
		'uses'=>'ShowsController@showajax',
		'as'  =>'showajax',
		'title'=>'جلب  المعارض'
	]);

	# add show
	Route::get('add-show',[
		'uses'=>'ShowsController@Add',
		'as'  =>'addshow',
		'title'=>'إضافة معرض',
		'icon' =>' <i class="fas fa-plus"></i> ',
		'hasFather' => true
	]);

	# store show
	Route::post('store-show',[
		'uses'=>'ShowsController@Store',
		'as'  =>'storeshow',
		'title'=>'حفظ معرض'
	]);


	# edit show
	Route::get('edit-show/{id}',[
		'uses'=>'ShowsController@Edit',
		'as'  =>'editshow',
		'title'=>'تعديل معرض'
	]);

	# update show
	Route::post('update-show',[
		'uses'=>'ShowsController@Update',
		'as'  =>'updateshow',
		'title'=>'تحديث معرض'
	]);

	# update cost
	Route::post('update-show-cost',[
		'uses'=>'ShowsController@Updatetac',
		'as'  =>'Updatetac',
		'title'=>'تحديث سعر الدخول'
	]);

	# images
	Route::post('images-show',[
		'uses'=>'ShowsController@storeImages',
		'as'  =>'storeImagesshow',
		'title'=>'الصوراضافة'
	]);

	# delete image
	Route::post('delete-image-show',[
		'uses'=>'ShowsController@DeleteImage',
		'as'  =>'deleteimagesshow',
		'title'=>'حذف صورة'
	]);

	
	# shower
	Route::post('shower',[
		'uses'=>'ShowsController@storeshower',
		'as'  =>'storeshower',
		'title'=>'اضافة عارض'
	]);

	# update shower
	Route::post('update-shower',[
		'uses'=>'ShowsController@updateshower',
		'as'  =>'updateshower',
		'title'=>'تحديث عارض'
	]);

	# delete shower
	Route::post('delete-shower',[
		'uses'=>'ShowsController@Deleteshower',
		'as'  =>'Deleteshower',
		'title'=>'حذف عارض'
	]);
   
	
	# speaker
	Route::post('speaker',[
		'uses'=>'ShowsController@storespeaker',
		'as'  =>'storespeaker',
		'title'=>'اضافة عارض'
	]);

	# update speaker
	Route::post('update-speaker',[
		'uses'=>'ShowsController@updatespeaker',
		'as'  =>'updatespeaker',
		'title'=>'تحديث عارض'
	]);

	# delete speaker
	Route::post('delete-speaker',[
		'uses'=>'ShowsController@Deletespeaker',
		'as'  =>'Deletespeaker',
		'title'=>'حذف عارض'
	]);

	# delete show
	Route::post('delete-show',[
		'uses'=>'ShowsController@Delete',
		'as'  =>'deleteshow',
		'title'=>'حذف معرض'
	]);

	# organisers
	Route::get('places',[
		'uses'=>'ShowsController@places',
		'as'  =>'places',
		'title'=>'  ادارة  طلبات العرض',
		'icon' =>'<i class="fas fa-address-card"></i>',
		'hasFather'=>true
	]);

	# delete show
	Route::post('delete-places',[
		'uses'=>'ShowsController@Deleteplaces',
		'as'  =>'deletplaces',
		'title'=>'حذف الطلب'
	]);
   

});
#--------------------------------------------end of dashboard controller---------------------------------------------------#
// all shows
//
//
Route::get('section-all-show/{id}',[
	'uses'=>'ShowsfrontController@shows',
	'as'  =>'front_shows',
	'type'=>'main',
	'kind'=>'showes',
	'title'=>'المعارض',
]);
//
//// show sort
//Route::get('show-section-view/{name}',[
//	'uses'=>'ShowsfrontController@showssort',
//	'as'  =>'front_shows_view',
//	'type'=>'main',
//	'kind'=>'showesview',
//	'title'=>'المعارض حسب التداول',
//]);
//
//
//// show sort
//Route::get('show-section-last/{name}',[
//	'uses'=>'ShowsfrontController@showslast',
//	'as'  =>'front_shows_last',
//	'type'=>'main',
//	'kind'=>'showesviewlast',
//	'title'=>'المعارض حسب الاكثر',
//]);
//
//// show sort by city
//Route::get('show-sort-city/{id}',[
//	'uses'=>'ShowsfrontController@showssortcity',
//	'as'  =>'front_show_city',
//	'type'=>'main',
//	'kind'=>'showescity',
//	'title'=>'المعارض حسب المحافظات',
//]);
//
//// show sort by country
//Route::get('show-sort-country/{id}',[
//	'uses'=>'ShowsfrontController@showssortcountries',
//	'as'  =>'front_show_country',
//	'type'=>'main',
//	'kind'=>'showescountry',
//	'title'=>'المعارض حسب للدول',
//]);
//
//// sections search
//Route::post('get-section-show-search', 'ShowsfrontController@datas')->name('front_show_datas');
//
//// sections search
//Route::post('get-show-search-by-name', 'ShowsfrontController@searchByName')->name('front_show_search_name');
//
//
//// sections search
//Route::post('get-section-show-search-more', 'ShowsfrontController@mores')->name('front_show_datas_more');
//
//
//// one
//Route::get('one-show/{id}',[
//	'uses'=>'ShowsfrontController@oneShow',
//	'as'  =>'front_one_show',
//	'type'=>'sub',
//	'kind'=>'show',
//	'title'=>'صفحة المعرض',
//]);
//
//// review
//Route::get('one-show-review/{id}',[
//	'uses'=>'ShowsfrontController@oneShowrev',
//	'as'  =>'front_one_show_review',
//	'type'=>'sub',
//	'kind'=>'showreview',
//	'title'=>'المرجعات',
//]);
//
//// Showers
//Route::get('one-show-Showers/{id}',[
//	'uses'=>'ShowsfrontController@Showers',
//	'as'  =>'front_one_show_Showers',
//	'type'=>'sub',
//	'kind'=>'Showers',
//	'title'=>'العارضون',
//]);
//
//// speakers
//Route::get('one-show-speakers/{id}',[
//	'uses'=>'ShowsfrontController@speakers',
//	'as'  =>'front_one_show_speakers',
//	'type'=>'sub',
//	'kind'=>'speakers',
//	'title'=>'المتحدثون',
//]);
//
//
//// places
//Route::post('one-show-places', 'ShowsfrontController@place')->name('front_add_place');
//
//// reating
//Route::post('one-show-reating', 'ShowsfrontController@rating')->name('front_add_reating');
//
//// going
//Route::post('one-show-going', 'ShowsfrontController@going')->name('front_add_going');
//
//
//// notgoing
//Route::post('one-show-not-going', 'ShowsfrontController@notgoing')->name('front_add_notgoing');
//
//
//
//// inter
//Route::post('one-show-inter', 'ShowsfrontController@inter')->name('front_add_inter');
//
//
//// notinter
//Route::post('one-show-not-inter', 'ShowsfrontController@notinter')->name('front_add_notinter');