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

Route::prefix('store')->group(function() {
    Route::get('/', 'StoreController@index');
});


// paymob
Route::post('/credit', 'CustomersController@credit')->name('credit');
Route::get('/callback', 'CustomersController@callback')->name('callback');




Route::group(['middleware' => ['role','auth']], function() {

	#------------------------------- start of CustomersController -----------------------------#

	# customers
	Route::get('customers',[
		'uses' =>'CustomersController@Index',
		'as'   =>'customers',
		'title'=>'ادارة الاعضاء',
		'subTitle'=>'الاعضاء',
		'icon' =>'<i class="fas fa-users"></i>',
		'subIcon' =>'<i class="fas fa-users"></i>',
		'child'=>[
			'addcustomerpage',
			'storecustomer',
			'deletecustomer',
			'eidtcustomer',
			'updatecustomer',
			'dataCustomer',
			'dataajaxCustomer'
		]
	]);

	# add customer
	Route::get('add-customer-page',[
		'uses'=>'CustomersController@AddCustomerPage',
		'as'  =>'addcustomerpage',
		'icon' =>'<i class="fas fa-plus"></i>',
		'title'=>'إضافة عضو',
		'hasFather'=>true
	]);

	# store customer
	Route::post('store-customer',[
		'uses'=>'CustomersController@StoreCustomer',
		'as'  =>'storecustomer',
		'title'=>'حفظ عضو'
	]);

	# edit user
	Route::get('edit-customer/{id}',[
		'uses'=>'CustomersController@EditCustomer',
		'as'  =>'eidtcustomer',
		'title'=>'تعديل عضو'
	]);
	
	# data user
	Route::get('show-data/{id}',[
		'uses'=>'CustomersController@dataCustomer',
		'as'  =>'dataCustomer',
		'title'=>'تحليل البيانات للعضو '
	]);

	# get keywords user by date ( ajax )
	Route::post('user-data-by-date',[
		'uses'=>'CustomersController@dataajaxCustomer',
		'as'  =>'dataajaxCustomer',
		'title'=>' إحصائيات العضو بالتاريخ',
	]);


	# update customer
	Route::post('update-customer',[
		'uses'=>'CustomersController@UpdateCustomer',
		'as'  =>'updatecustomer',
		'title'=>'تحديث عضو'
	]);

	# delete customer
	Route::post('delete-customer',[
		'uses'=>'CustomersController@DeleteCustomer',
		'as'  =>'deletecustomer',
		'title'=>'حذف عضو'
	]);

    #------------------------------- end of CustomersController -----------------------------#

    #------------------------------- start of StoreAdsController -----------------------------#
	# sections
	Route::get('store-sections',[
		'uses' =>'StoreSectionsController@Index',
		'as'   =>'storesections',
		'title'=>'إدارة السوق',
		'subTitle'=>' الاقسام الرئيسية',
		'icon' =>' <i class="fas fa-building"></i> ',
		'subIcon' =>' <i class="fas fa-building"></i> ',
		'child' =>[
			'storestoresections',
			'updatestoreSection',
			'deletestoresection',
			'stores',
			'Addstoreads',
			'Storestoreads',
			'Deletestoreads',
			'Editstoreads',
            'Updatestoreads',
            'storeImagesstore',
			'deleteimagestore',
			'Deletecomments',
            'selectstoreSection'

			
			
			
		]

	]);


	# store section
	Route::post('store-store-sections',[
		'uses'=>'StoreSectionsController@Store',
		'as'  =>'storestoresections',
		'title'=>'إضافة قسم لسوق'
	]);

	# update section
	Route::post('update-store-sections',[
		'uses'=>'StoreSectionsController@Update',
		'as'  =>'updatestoreSection',
		'title'=>'تحديث قسم لسوق'
	]);

    # update section
    Route::post('select-store-sections',[
        'uses'=>'StoreSectionsController@select',
        'as'  =>'selectstoreSection',
        'title'=>'تحديد قسم لسوق'
    ]);

	# delete section
	Route::post('delete-store-sections',[
		'uses'=>'StoreSectionsController@Delete',
		'as'  =>'deletestoresection',
		'title'=>'حذف قسم سوق'
	]);

	# ads
	Route::get('store-ads-admin',[
		'uses' =>'StoreAdsController@Index',
		'as'   =>'stores',
		'title'=>'إدارة إعلانات المتجر',
		'icon' =>'<i class="fas fa-store"></i>',
		'hasFather'=>true
		
	]);

	# add ads
	Route::get('add-store-ads-admin',[
		'uses'=>'StoreAdsController@Addstoreads',
		'as'  =>'Addstoreads',
		'icon' =>'<i class="fas fa-plus"></i>',
		'title'=>'إضافة إعلان للمتجر',
		'hasFather'=>true
	]);

	# store ads
	Route::post('store-store-ads-admin',[
		'uses'=>'StoreAdsController@Storestoreads',
		'as'  =>'Storestoreads',
		'title'=>'حفظ إعلان'
	]);

	# edit ads
	Route::get('edit-store-ads-admin/{id}',[
		'uses'=>'StoreAdsController@Editstoreads',
		'as'  =>'Editstoreads',
		'title'=>'تعديل إعلان'
	]);

	# update ads
	Route::post('update-store-ads-admin',[
		'uses'=>'StoreAdsController@Updatestoreads',
		'as'  =>'Updatestoreads',
		'title'=>'تحديث إعلان'
	]);

	# delete ads
	Route::post('delete-store-ads-admin',[
		'uses'=>'StoreAdsController@Deletestoreads',
		'as'  =>'Deletestoreads',
		'title'=>'حذف إعلان'
    ]);

    # images
	Route::post('images-Store-admin',[
		'uses'=>'StoreAdsController@storeImages',
		'as'  =>'storeImagesstore',
		'title'=>'اضافة صور'
	]);

	# delete image
	Route::post('delete-image-Store',[
		'uses'=>'StoreAdsController@DeleteImage',
		'as'  =>'deleteimagestore',
		'title'=>'حذف صور'
	]);

	# delete comment
	Route::post('delete-comment-Store',[
		'uses'=>'StoreAdsController@Deletecomment',
		'as'  =>'Deletecomments',
		'title'=>'حذف تعليق للإعلان'
	]);




	#------------------------------- end of StoreAdsController -----------------------------#


});

//
Route::get('customer/login', 'FrontLoginController@showLoginForm')->name('customer_login');
//Route::post('customer/login', 'FrontLoginController@login')->name('customer_login_start');
Route::get('customer/register', 'FrontLoginController@showcustomerRegisterForm')->name('customer_register');
//Route::post('customer/register', 'FrontLoginController@register')->name('customer_register_start');
//
//Route::get('auth/google', 'FrontLoginController@redirectToGoogle')->name('customer_google');
//Route::get('auth/google/callback', 'FrontLoginController@handleGoogleCallback');
//
//Route::get('auth/facebook', 'FrontLoginController@redirectToFacebook')->name('customer_facebook');
//Route::get('auth/facebook/callback', 'FrontLoginController@handleFacebookCallback');
//
//Route::get('customer/setting', 'FrontLoginController@EditCustomer')->name('customer_edit');
//Route::post('customer/setting', 'FrontLoginController@UpdateCustomer')->name('updateprofile');



//
//// sections
Route::get('store-section/{name}',[
	'uses'=>'StoreAdsfrontController@sections',
	'as'  =>'front_section_store',
	'type'=>'main',
	'kind'=>'storesection',
	'title'=>'الرئيسية للسوق',
]);
//
//
//// sections search
//Route::post('get-section-store-search', 'StoreAdsfrontController@datas')->name('front_section_datas_store');
//
//
//// sections search
//Route::post('get-store-search-by-name', 'StoreAdsfrontController@searchByName')->name('front_store_by_name');
//
//
//// sections search
//Route::post('get-section-store-search-more', 'StoreAdsfrontController@mores')->name('front_section_datas_store_more');
//
//
//// ads
//Route::get('store-ads-detials/{id}',[
//	'uses'=>'StoreAdsfrontController@ads',
//	'as'  =>'front_ads_detials',
//]);
//
//// my ads
//Route::get('my-store-ads/{id}',[
//	'uses'=>'StoreAdsfrontController@myads',
//	'as'  =>'front_my_ads',
//	'type'=>'sub',
//	'kind'=>'mystoreads',
//	'title'=>'اعلاناتي',
//]);
//
//// add ads
//Route::get('add-store-ads/{id}', 'StoreAdsfrontController@addstore')->name('front_add_ads');
//
//// store ads
//Route::post('store-store-ads', 'StoreAdsfrontController@Storestoreads')->name('front_store_store_ads');
//
//// edit ads
//Route::get('edit-my-store-ads/{id}', 'StoreAdsfrontController@editads')->name('front_edit_ads');
//
//// store ads
//Route::post('update-my-store-ads', 'StoreAdsfrontController@updatestoreads')->name('front_update_store_ads');
//
//// delete ads
//Route::post('delete-my-store-ads', 'StoreAdsfrontController@Deleteads')->name('front_delete_store_ads');
//
//// my chats
//Route::get('my-chats/{id}', 'StoreAdsfrontController@chats')->name('front_chats');
//
//// start chat
//Route::post('start-my-chat/{id}', 'StoreAdsfrontController@startchat')->name('front_start_chat');
//
//// my chats
//Route::post('my-chats-massages', 'StoreAdsfrontController@chatsmassages')->name('front_chats_massages');
//
//
//// my chats
//Route::post('chats/send-massages', 'StoreAdsfrontController@writemassage')->name('front_chats_write');

