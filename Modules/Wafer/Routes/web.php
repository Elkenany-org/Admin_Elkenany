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


Route::group(['middleware' => ['role','auth']], function() {
#------------------------------- start of SectionsController -----------------------------#

	# sections
	Route::get('wafer-sections',[
		'uses' =>'SectionsController@Index',
		'as'   =>'wafer_sections',
		'title'=>' إدارة وافر',
		'subTitle'=>' الاقسام',
		'icon' =>'<i class="fas fa-dolly"></i>',
		'subIcon' =>'<i class="fas fa-dolly"></i>',
		'child'=>[
            'Storesectionwafer',
            'Deletesectionwafer',
            'Updatesectionwafer',
            'farmers',
            'Addfarmer',
            'Storefarmer',
            'Editfarmer',
            'Updatefarmer',
            'Deletefarmer',
            'posts',
            'Deletepost',
            'Showpost',
			'showfarmerpost',
			'Deleteorder',
			'Showorder',
			'ordersfarmer',
			'Updatefarmerorder',
			'Deletefarmerorder',
			'DeleteImagefarmer',
			'storeImagesfarmer'
		]
	]);

	# store sections
	Route::post('store-wafer-sections',[
		'uses'=>'SectionsController@Storesection',
		'as'  =>'Storesectionwafer',
		'title'=>'إضافة سكشن'
    ]);
    
    # update sections
	Route::post('update-wafer-sections',[
		'uses'=>'SectionsController@Updatesection',
		'as'  =>'Updatesectionwafer',
		'title'=>'تحديث سكشن'
	]);

	# delete sections
	Route::post('delete-wafer-sections',[
		'uses'=>'SectionsController@Deletesection',
		'as'  =>'Deletesectionwafer',
		'title'=>'حذف سكشن'
    ]);
    
#------------------------------- end of SectionsController -----------------------------#

#------------------------------- start of FarmersController -----------------------------#

	# farmers
	Route::get('wafer-farmers',[
		'uses' =>'FarmersController@Index',
		'as'   =>'farmers',
		'title'=>' المُزارعين',
        'icon' =>'<i class="fas fa-users"></i>',
        'hasFather'=>true
		
	]);

	# add farmers
	Route::get('add-wafer-farmers-page',[
		'uses'=>'FarmersController@Addfarmer',
		'as'  =>'Addfarmer',
		'title'=>'إضافة مُزارع',
		
	]);

	# store farmers
	Route::post('store-wafer-farmers',[
		'uses'=>'FarmersController@Storefarmer',
		'as'  =>'Storefarmer',
		'title'=>'حفظ مُزارع'
	]);

	# edit farmers
	Route::get('edit-wafer-farmers/{id}',[
		'uses'=>'FarmersController@Editfarmer',
		'as'  =>'Editfarmer',
		'title'=>'تعديل مُزارع'
    ]);
    
    # show farmers posts
	Route::get('edit-wafer-farmers-posts/{id}',[
		'uses'=>'FarmersController@showfarmerpost',
		'as'  =>'showfarmerpost',
		'title'=>' منشورات المُزارع'
	]);

	# update farmers
	Route::post('update-wafer-farmers',[
		'uses'=>'FarmersController@Updatefarmer',
		'as'  =>'Updatefarmer',
		'title'=>'تحديث مُزارع'
	]);

	# delete farmers
	Route::post('delete-wafer-farmers',[
		'uses'=>'FarmersController@Deletefarmer',
		'as'  =>'Deletefarmer',
		'title'=>'حذف مُزارع'
    ]);

    # posts
	Route::get('wafer-posts',[
		'uses' =>'FarmersController@posts',
		'as'   =>'posts',
		'title'=>' المنشورات',
        'icon' =>'<i class="fas fa-address-card"></i>',
        'hasFather'=>true
		
    ]);
    
    # delete posts
	Route::post('delete-wafer-posts',[
		'uses'=>'FarmersController@Deletepost',
		'as'  =>'Deletepost',
		'title'=>'حذف منشور وافر'
    ]);

    # show posts 
	Route::get('wafer-post-show/{id}',[
		'uses'=>'FarmersController@Showpost',
		'as'  =>'Showpost',
		'title'=>'  بيانات المنشور'
	]);


	# delete posts
	Route::post('delete-wafer-order',[
		'uses'=>'FarmersController@Deleteorder',
		'as'  =>'Deleteorder',
		'title'=>'حذف طلب وافر'
	]);
	

	# show order 
	Route::get('wafer-post-order/{id}',[
		'uses'=>'FarmersController@Showorder',
		'as'  =>'Showorder',
		'title'=>'  بيانات الطلب'
	]);

	# order farmer
	Route::get('wafer-orders-farmer',[
		'uses' =>'FarmersController@ordersfarmer',
		'as'   =>'ordersfarmer',
		'title'=>' طلبات المُزارعين',
		'icon' =>'<i class="fas fa-address-book"></i>',
		'hasFather'=>true
		
	]);

	# update farmers order
	Route::post('update-wafer-farmers-order',[
		'uses'=>'FarmersController@Updatefarmerorder',
		'as'  =>'Updatefarmerorder',
		'title'=>'تحديث طلب مُزارع'
	]);

	# delete farmers order
	Route::post('delete-wafer-farmers-order',[
		'uses'=>'FarmersController@Deletefarmerorder',
		'as'  =>'Deletefarmerorder',
		'title'=>'حذف طلب المُزارع'
	]);

	# images
	Route::post('images-Store-farmers',[
		'uses'=>'FarmersController@storeImagesfarmer',
		'as'  =>'storeImagesfarmer',
		'title'=>'إضافة صور مزرعة'
	]);

	# delete image
	Route::post('delete-image-farmers',[
		'uses'=>'FarmersController@DeleteImagefarmer',
		'as'  =>'DeleteImagefarmer',
		'title'=>'حذف  صور مزرعة'
	]);

});