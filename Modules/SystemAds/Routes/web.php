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

Route::prefix('systemads')->group(function() {
    Route::get('/', 'SystemAdsController@index');
    //ajax
    Route::get('getSection/{type}','AdsUserController@getSection');
    Route::get('getSubSection/{type_place}/{section_type}/{main}','AdsUserController@getSubSection');
});

Route::group(['middleware' => ['role','auth']], function() {

	#------------------------------- start of AdsUserController -----------------------------#

	# users
	Route::get('users-system-ads',[
		'uses' =>'AdsUserController@Index',
		'as'   =>'usersads',
		'title'=>'ادارة الاعلانات',
		'subTitle'=>'الاعضاء',
		'icon' =>'<i class="fas fa-ad"></i>',
		'subIcon' =>'<i class="fas fa-users"></i>',
		'child'=>[
			'adduserads',
			'storeuserads',
			'deleteuserads',
			'eidtuserads',
            'updateuserads',
            'Storemembershipads',
            'Updatemembership',
			'Deletmembershipads',
			'adssystem',
			'Addsystemads',
			'Storesystemads',
			'Searchcompanyads',
			'Editadss',
			'updateadsss'
		]
	]);

	# add user
	Route::get('add-user-system-ads',[
		'uses'=>'AdsUserController@AdduserPage',
		'as'  =>'adduserads',
		'icon' =>'<i class="fas fa-plus"></i>',
		'title'=>'إضافة عضو',
		'hasFather'=>true
	]);

	# store user
	Route::post('store-user',[
		'uses'=>'AdsUserController@Storeuser',
		'as'  =>'storeuserads',
		'title'=>'حفظ عضو'
	]);

	# edit user
	Route::get('edit-user-system-ads/{id}',[
		'uses'=>'AdsUserController@Edituser',
		'as'  =>'eidtuserads',
		'title'=>'تعديل عضو'
	]);
	
	# update user
	Route::post('update-user-system-ads',[
		'uses'=>'AdsUserController@Updateuser',
		'as'  =>'updateuserads',
		'title'=>'تحديث عضو'
	]);

	# delete user
	Route::post('delete-user-system-ads',[
		'uses'=>'AdsUserController@Deleteuser',
		'as'  =>'deleteuserads',
		'title'=>'حذف عضو'
	]);
	
	# get companies search (ajax)
	Route::post('get-companies-search-ads',[
		'uses'=>'AdsUserController@Searchcompany',
		'as'  =>'Searchcompanyads',
		'title'=>'جلب  بحث الشركات'
	]);
    
    # store membership
	Route::post('store-membership-ads',[
		'uses'=>'AdsUserController@Storemembership',
		'as'  =>'Storemembershipads',
		'title'=>'حفظ عضوية'
    ]);

    # edit membership
	Route::post('update-membership-ads/{id}',[
		'uses'=>'AdsUserController@Updatemembership',
		'as'  =>'Updatemembership',
		'title'=>'تعديل عضوية'
    ]);
    
    # delete user
	Route::post('delete-membership-ads',[
		'uses'=>'AdsUserController@Deletmembership',
		'as'  =>'Deletmembershipads',
		'title'=>'حذف عضوية'
	]);
	
	# ads
	Route::get('all-system-ads',[
		'uses'=>'AdsUserController@systemads',
		'as'  =>'adssystem',
		'icon' =>'<i class="fas fa-ad"></i>',
		'title'=>'الاعلانات',
		'hasFather'=>true
	]);

	# ads
	Route::get('add-system-ads',[
		'uses'=>'AdsUserController@Addsystemads',
		'as'  =>'Addsystemads',
		'title'=>'عمل اعلان',

	]);

	# store user
	Route::post('store-system-ads',[
		'uses'=>'AdsUserController@Storesystemads',
		'as'  =>'Storesystemads',
		'title'=>'حفظ اعلان'
	]);

	# ads
	Route::get('edit-system-ads/{id}',[
		'uses'=>'AdsUserController@Editads',
		'as'  =>'Editadss',
		'title'=>'تعديل اعلان',

	]);

	# store user
	Route::post('update-system-ads',[
		'uses'=>'AdsUserController@updateads',
		'as'  =>'updateadsss',
		'title'=>'تحديث اعلان'
	]);


    #------------------------------- end of AdsUserController -----------------------------#



});