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

Route::prefix('us')->group(function() {
    Route::get('/', 'UsController@index');
});

Route::group(['middleware' => ['role','auth']], function() {


	

	#------------------------------- end of ShowsController -----------------------------#

	# sections
	Route::get('contuct-us',[
		'uses' =>'UsController@Index',
		'as'   =>'contuctus',
		'title'=>'إدارة اتصل بنا',
		'subTitle'=>'  الرسائل',
		'icon' =>' <i class="fas fa-envelope-open-text"></i> ',
		'subIcon' =>' <i class="fas fa-envelope-open-text"></i>',
		'child' =>[
		
			'deletcontuctus',
			'offices',
			'addoffices',
			'storeoffices',
			'editoffices',
			'updateoffices',
			'Updatecontactoffice',
			'deleteoffices'

            
			
		]

	]);



	# delete show
	Route::post('delete-contuct-us',[
		'uses'=>'UsController@Delete',
		'as'  =>'deletcontuctus',
		'title'=>'حذف الرسالة'
	]);

	# offices
	Route::get('offices',[
		'uses'=>'OfficesController@index',
		'as'  =>'offices',
		'title'=>' قائمة المقرات',
		'icon' =>'<i class="fas fa-puzzle-piece"></i>',
		'hasFather'=>true
	]);

	# add offices
	Route::get('add-offices',[
		'uses'=>'OfficesController@Add',
		'as'  =>'addoffices',
		'title'=>'إضافة مقر',
		'icon' =>' <i class="fas fa-plus"></i> ',
		'hasFather' => true
	]);


	# store offices
	Route::post('store-offices',[
		'uses'=>'OfficesController@Store',
		'as'  =>'storeoffices',
		'title'=>'حفظ مقر'
	]);


	# edit offices
	Route::get('edit-offices/{id}',[
		'uses'=>'OfficesController@Edit',
		'as'  =>'editoffices',
		'title'=>'تعديل مقر'
	]);

	# update offices
	Route::post('update-offices',[
		'uses'=>'OfficesController@Update',
		'as'  =>'updateoffices',
		'title'=>'تحديث مقر'
	]);

	# update contact
	Route::post('update-offices-contact',[
		'uses'=>'OfficesController@Updatecontact',
		'as'  =>'Updatecontactoffice',
		'title'=>'تحديث ارقام مقر'
	]);


	# delete offices
	Route::post('delete-offices',[
		'uses'=>'OfficesController@Delete',
		'as'  =>'deleteoffices',
		'title'=>'حذف مقر'
	]);
	
   

});

//
//// contuct
//Route::get('get-detials-contuct-us', 'UsController@getcontuct')->name('front_get_contuct_uss');
//
//// contuct
//Route::post('add-contuct', 'UsController@contuct')->name('front_add_contuct');
//
//
//// about
//Route::get('get-about-us', 'UsController@about')->name('front_get_about');
//
//
//
//// terms
//Route::get('get-terms', 'UsController@terms')->name('front_get_terms');
//
//
//// privacy
//Route::get('get-privacy', 'UsController@privacy')->name('front_get_privacy');
//

