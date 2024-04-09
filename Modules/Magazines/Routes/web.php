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

Route::prefix('magazines')->group(function() {
    Route::get('/', 'MagazinesController@index');
});

Route::group(['middleware' => ['role','auth']], function() {


	

	#------------------------------- end of MagazinesController -----------------------------#

	# sections
	Route::get('sections-magazines',[
		'uses' =>'MagazinesSectionsController@Index',
		'as'   =>'sectionsmagazins',
		'title'=>'إدارة الدلائل والمجلات',
		'subTitle'=>' الاقسام الرئيسية',
		'icon' =>' <i class="fas fa-newspaper"></i> ',
		'subIcon' =>' <i class="fas fa-building"></i> ',
		'child' =>[
			'storeMagazinessections',
			'updateMagazinessection',
            'deleteMagazinessection',
            'magazines',
            'addmagazine',
            'storemagazine',
            'storesocialm',
            'editmagazine',
            'updatemagazine',
            'Updatecontactm',
            'Updatelocalm',
            'deletemagazine',
            'storeImagesm',
            'updateimagesm',
            'deleteimagesm',
            'storeguide',
            'updateguide',
            'Deleteguide',
            'storegallarym',
            'updategallarym',
            'Deletegallarym',
            'gallarym',
            'selectmagazinesection'



        
			
			
		]

	]);


	# store section
	Route::post('store-magazines-sections',[
		'uses'=>'MagazinesSectionsController@Store',
		'as'  =>'storeMagazinessections',
		'title'=>'إضافة قسم'
	]);

	# update section
	Route::post('update-magazines-sections',[
		'uses'=>'MagazinesSectionsController@Update',
		'as'  =>'updateMagazinessection',
		'title'=>'تحديث قسم'
	]);

        # select section
    Route::post('select-magazines-sections',[
        'uses'=>'MagazinesSectionsController@select',
        'as'  =>'selectmagazinesection',
        'title'=>'تحديد قسم'
    ]);

	# delete section
	Route::post('delete-magazines-sections',[
		'uses'=>'MagazinesSectionsController@Delete',
		'as'  =>'deleteMagazinessection',
		'title'=>'حذف قسم'
	]);
	
	# magazines
	Route::get('magazines',[
		'uses'=>'MagazinesController@index',
		'as'  =>'magazines',
		'title'=>' قائمة المجلات',
		'icon' =>'<i class="fas fa-puzzle-piece"></i>',
		'hasFather'=>true
	]);

	# add magazine
	Route::get('add-magazine',[
		'uses'=>'MagazinesController@Add',
		'as'  =>'addmagazine',
		'title'=>'إضافة مجلة',
		'icon' =>' <i class="fas fa-plus"></i> ',
		'hasFather' => true
	]);

	# store magazine
	Route::post('store-magazine',[
		'uses'=>'MagazinesController@Store',
		'as'  =>'storemagazine',
		'title'=>'حفظ مجلة'
	]);

	# store social
	Route::post('store-magazine-social',[
		'uses'=>'MagazinesController@storesocial',
		'as'  =>'storesocialm',
		'title'=>'حفظ التواصل'
	]);

	# edit magazine
	Route::get('edit-magazine/{id}',[
		'uses'=>'MagazinesController@Edit',
		'as'  =>'editmagazine',
		'title'=>'تعديل مجلة'
	]);

	# update magazine
	Route::post('update-magazine',[
		'uses'=>'MagazinesController@Update',
		'as'  =>'updatemagazine',
		'title'=>'تحديث مجلة'
	]);

	# update contact
	Route::post('update-magazine-contact',[
		'uses'=>'MagazinesController@Updatecontact',
		'as'  =>'Updatecontactm',
		'title'=>'تحديث ارقام مجلة'
	]);

	# update location
	Route::post('update-magazine-location',[
		'uses'=>'MagazinesController@Updatelocal',
		'as'  =>'Updatelocalm',
		'title'=>'تحديث عناوين مجلة'
	]);

	# delete magazine
	Route::post('delete-magazine',[
		'uses'=>'MagazinesController@Delete',
		'as'  =>'deletemagazine',
		'title'=>'حذف مجلة'
	]);

	# images
	Route::post('images-magazines',[
		'uses'=>'MagazinesController@storeImages',
		'as'  =>'storeImagesm',
		'title'=>'الصوراضافة'
	]);


	# update image
	Route::post('update-image-magazines',[
		'uses'=>'MagazinesController@Updateimage',
		'as'  =>'updateimagesm',
		'title'=>'تحديث صورة'
	]);

	# delete image
	Route::post('delete-image-magazines',[
		'uses'=>'MagazinesController@DeleteImage',
		'as'  =>'deleteimagesm',
		'title'=>'حذف صورة'
	]);

	# guide
	Route::post('guides',[
		'uses'=>'MagazinesController@storeguide',
		'as'  =>'storeguide',
		'title'=>'اضافة دلائل'
	]);

	# update guide
	Route::post('update-guide',[
		'uses'=>'MagazinesController@updateguide',
		'as'  =>'updateguide',
		'title'=>'تحديث دلائل'
	]);

	# delete guide
	Route::post('delete-guide',[
		'uses'=>'MagazinesController@Deleteguide',
		'as'  =>'Deleteguide',
		'title'=>'حذف دلائل'
	]);

	# store gallary
	Route::post('store-gallary-magazines',[
		'uses'=>'MagazinesController@storegallary',
		'as'  =>'storegallarym',
		'title'=>'اضافة البوم'
	]);

	# update gallary
	Route::post('update-gallary-magazines',[
		'uses'=>'MagazinesController@updategallary',
		'as'  =>'updategallarym',
		'title'=>'تحديث البوم'
	]);

	# delete gallary
	Route::post('delete-gallary-magazines',[
		'uses'=>'MagazinesController@Deletegallary',
		'as'  =>'Deletegallarym',
		'title'=>'حذف البوم'
	]);

	# gallary 
	Route::get('gallary-magazine-images/{id}',[
		'uses'=>'MagazinesController@gallary',
		'as'  =>'gallarym',
		'title'=>' الالبوم'
	]);


	#------------------------------- end of MagazinesController -----------------------------#


});
//
//// magazines sort
// Route::get('magazines-section-sort-rate-count/{id}',[
// 	'uses'=>'MagazineFrontController@sortmagazinesrate',
// 	'as'  =>'front_magazines_sort_rate_magazines',
// 	'type'=>'main',
// 	'kind'=>'magazinesrate',
// 	'title'=>'المجلات والدلائل حسب التقيم',
// ]);
//
//// magazines sort by city
//Route::get('magazines-section-sort-city/{id}',[
//	'uses'=>'MagazineFrontController@sortmagazinescity',
//	'as'  =>'front_magazines_city',
//	'type'=>'main',
//	'kind'=>'magazinescity',
//	'title'=>'المجلات والدلائل حسب المحافظات',
//]);
//// magazines
Route::get('magazines-magazines/{id}',[
	'uses'=>'MagazineFrontController@magazines',
	'as'  =>'front_magazines',
	'type'=>'main',
	'kind'=>'magazines',
	'title'=>'المجلات والدلائل ',
]);
//// magazine
//Route::get('magazines-magazine/{id}',[
//	'uses'=>'MagazineFrontController@magazine',
//	'as'  =>'front_magazine',
//]);
//// magazines search
//Route::post('get-magazines-guide-search', 'MagazineFrontController@Getmagazines')->name('front_magazines_guide_searchs');
//
//// magazines search
//Route::post('get-magazines-search-name', 'MagazineFrontController@GetmagazinesByName')->name('front_magazines_name_searchs');
//
//// magazines search rate
//Route::post('get-magazines-guide-search-rate', 'MagazineFrontController@Getrating')->name('front_magazines_guide_search_rate');
//
//
//// rating
//Route::post('magazines-rating', 'MagazineFrontController@rating')->name('front_magazines_rating');
//Route::get('magazines-customer-rating/{magazin_id}', 'MagazineFrontController@custmerRate')->name('front_magazines_customerRate');
//Route::get('magazines-rate-magazin/{magazin_id}', 'MagazineFrontController@getRateOfMagazin');
//
//// rating
//Route::post('magazines-update-rating', 'MagazineFrontController@updaterating')->name('front_magazines_update_rating');