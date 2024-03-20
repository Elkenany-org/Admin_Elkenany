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

//Route::get('/', function () {
//    return view('welcome');
//})->name('welcome');
Route::group(['middleware' => ['role','auth']], function() {

	#------------------------------- start of HomeController -----------------------------#

	Route::get('/home',[
		'uses'  =>'HomeController@index',
		'as'    =>'home',
		'icon'  =>'<i class="nav-icon fas fa-home"></i>',
		'title' =>'الرئيسيه'
	]);

	#------------------------------- end of HomeController -----------------------------#

	#------------------------------- start of inboxController -----------------------------#

	# ads
	Route::get('inbox',[
		'uses'    =>'inboxController@Index',
		'as'      =>'inbox',
		'title'   =>'إدارة الرسائل',
		'subTitle'=>'الرسائل الواردة',
		'icon'    =>' <i class="fas fa-envelope"></i> ',
		'subIcon' =>' <i class="fas fa-envelope"></i> ',
		'child'   =>[
			'viewmessage',
			'deletemessage',
		]
	]);

	# view message
	Route::get('view-message/{id}',[
		'uses'=>'inboxController@View',
		'as'  =>'viewmessage',
		'title'=>'عرض رسالة'
	]);

	# delete message
	Route::post('delete-message',[
		'uses'=>'inboxController@Delete',
		'as'  =>'deletemessage',
		'title'=>'حذف رسالة'
	]);

	#------------------------------- end of inboxController -----------------------------#
	

	#------------------------------- start of UsersController -----------------------------#

	# users
	Route::get('users',[
		'uses' =>'UsersController@Index',
		'as'   =>'users',
		'title'=>'إدارة المشرفين',
		'subTitle'=>'المشرفين',
		'icon' =>'<i class="fas fa-address-book"></i>',
		'subIcon' =>'<i class="fas fa-address-book"></i>',
		'child'=>[
			'adduserpagem',
			'StoreUserm',
			'deleteuser',
			'eidtuser',
			'updateuser',
		]
	]);

	# add user
	Route::get('add-user-page',[
		'uses'=>'UsersController@AddUserPage',
		'as'  =>'adduserpagem',
		'icon' =>'<i class="fas fa-plus"></i>',
		'title'=>'إضافة مشرف',
		'hasFather'=>true
	]);

	# store user
	Route::post('store-user-admin',[
		'uses'=>'UsersController@StoreUser',
		'as'  =>'StoreUserm',
		'title'=>'حفظ المشرف'
	]);

	# edit user
	Route::get('edit-user/{id}',[
		'uses'=>'UsersController@EditUser',
		'as'  =>'eidtuser',
		'title'=>'تعديل مشرف'
	]);

	# update user
	Route::post('update-user',[
		'uses'=>'UsersController@UpdateUser',
		'as'  =>'updateuser',
		'title'=>'تحديث مشرف'
	]);

	# delete user
	Route::post('delete-user',[
		'uses'=>'UsersController@DeleteUser',
		'as'  =>'deleteuser',
		'title'=>'حذف مشرف'
	]);

	#------------------------------- end of UsersController -----------------------------#

	#------------------------------- start of PermissionsController -----------------------------#

	# permissions
	Route::get('permissions',[
		'uses' =>'PermissionsController@Index',
		'as'   =>'permissions',
		'title'=>'الصلاحيات',
		'subTitle'=>'الصلاحيات',
		'icon' =>'<i class="fas fa-biohazard"></i>',
		'subIcon' =>'<i class="fas fa-biohazard"></i>',
		'child'=>[
			'addrolepage',
			'addpermission',
			'editpermission',
			'editrolepage',
			'updatepermission',
			'deletepermission',
		]
	]);

	# add role page
	Route::get('add-role-page',[
		'uses'=>'PermissionsController@AddRolePage',
		'as'  =>'addrolepage',
		'icon' =>'<i class="fas fa-plus"></i>',
		'title'=>'إضافة صلاحيه',
		'hasFather'=>true
	]);

	# add role (ajax)
	Route::post('add-permission',[
		'uses'=>'PermissionsController@Add',
		'as'  =>'addpermission',
		'title'=>'حفظ صلاحيه'
	]);

	# edit permission
	Route::get('edit-permission/{id}',[
		'uses'  =>'PermissionsController@EditRole',
		'as'    =>'editrolepage',
		'title' =>'تعديل صلاحيه'
	]);

	# update role (ajax)
	Route::post('update-permission',[
		'uses'=>'PermissionsController@Update',
		'as'  =>'updatepermission',
		'title'=>'تحديث صلاحيه'
	]);

	# delete role 
	Route::post('delete-permission',[
		'uses'=>'PermissionsController@Delete',
		'as'  =>'deletepermission',
		'title'=>'حذف صلاحيه'
	]);

	#------------------------------- end of PermissionsController -----------------------------#

	#------------------------------- start of ReportsController -----------------------------#
	# supervisor reports
	Route::get('supervisors-reports',[
		'uses'  =>'ReportsController@Index',
		'as'    =>'supervisorsresports',
		'icon'  =>'<i class="fas fa-clipboard"></i>',
		'subIcon'  =>'<i class="fas fa-clipboard"></i>',
		'title' =>'إدارة التقارير',
		'subTitle' =>'تقارير المشرفين',
		'child' =>[
			'deletereport',
			'deleteallreports',
			'reports'
		]
	]);

	# reports
	Route::get('reports/{id?}',[
		'uses'=>'ReportsController@Reports',
		'as'  =>'reports',
		'title'=>'قائمة التقارير'
	]);


	# delete all reports
	Route::post('delete-all-reports',[
		'uses'=>'ReportsController@DeleteAllReports',
		'as'  =>'deleteallreports',
		'title'=>'حذف جميع التقارير'
	]);

	# delete report
	Route::post('delete-report',[
		'uses'=>'ReportsController@DeleteReport',
		'as'  =>'deletereport',
		'title'=>'حذف تقرير'
	]);

	#------------------------------- end of ReportsController -----------------------------#

	#------------------------------- start of SettingController -----------------------------#

	# setting
	Route::get('setting',[
		'uses' =>'SettingController@Index',
		'as'   =>'setting',
		'title'=>'الإعدادات',
		'icon' =>'<i class="fas fa-cog"></i>',
		'child'=>[
			'updatemainsetting',
			'updatecopyrigth',
			'updateaboutapp',
			'updatesmtp',
			'updatesms',
			'updateonesignal',
			'updatefcm',
			'storedynamicsetting',
			'updatedynamicsetting',
			'deletedynamicsetting',
			'Storesocial',
			'socialUpdate',
			'Deletesocial'


		]
	]);

	# update main setting
	Route::post('update-main-setting',[
		'uses'=>'SettingController@UpdateMainSetting',
		'as'  =>'updatemainsetting',
		'title'=>'تحديث الإعدادات العامه'
	]);

	# update copyrigth
	Route::post('update-copyrigth',[
		'uses'=>'SettingController@UpdateMainCopyrigth',
		'as'  =>'updatecopyrigth',
		'title'=>'تحديث الحقوق'
	]);

	# update copyrigth
	Route::post('update-about-app',[
		'uses'=>'SettingController@UpdateMainAboutApp',
		'as'  =>'updateaboutapp',
		'title'=>'تحديث عن التطبيق'
	]);

	# update smtp
	Route::post('update-smtp',[
		'uses'=>'SettingController@UpdateSMTP',
		'as'  =>'updatesmtp',
		'title'=>'تحديث ال SMTP'
	]);

	# update sms
	Route::post('update-sms',[
		'uses'=>'SettingController@UpdateSmS',
		'as'  =>'updatesms',
		'title'=>'تحديث ال sms'
	]);

	# update onesignal
	Route::post('update-onesignal',[
		'uses'=>'SettingController@UpdateOneSignal',
		'as'  =>'updateonesignal',
		'title'=>'تحديث ال onesignal'
	]);

	# update fcm
	Route::post('update-fcm',[
		'uses'=>'SettingController@UpdateFCM',
		'as'  =>'updatefcm',
		'title'=>'تحديث ال fcm'
	]);

	# store dynamic setting
	Route::post('store-dynamic-setting',[
		'uses'=>'SettingController@StoreDynamicSetting',
		'as'  =>'storedynamicsetting',
		'title'=>'إضافة إعدادات إضافية'
	]);

	# update dynamic setting
	Route::post('update-dynamic-setting',[
		'uses'=>'SettingController@UpdateDynamicSetting',
		'as'  =>'updatedynamicsetting',
		'title'=>'تحديث إعدادات إضافية'
	]);

	# delete dynamic setting
	Route::post('delete-dynamic-setting',[
		'uses'=>'SettingController@DeleteDynamicSetting',
		'as'  =>'deletedynamicsetting',
		'title'=>'حذف إعدادات إضافية'
	]);

	# store social
	Route::post('store-socials',[
		'uses'=>'SettingController@Storesocial',
		'as'  =>'Storesocial',
		'title'=>'إضافة موقع'
	]);

	# update social
	Route::post('update-socials-media',[
		'uses'=>'SettingController@socialUpdate',
		'as'  =>'socialUpdate',
		'title'=>'تحديث موقع'
	]);

	# delete social
	Route::post('delete-socials',[
		'uses'=>'SettingController@Deletesocial',
		'as'  =>'Deletesocial',
		'title'=>'حذف موقع'
	]);

	#------------------------------- end of SettingController -----------------------------#


	# news
	Route::get('main-sections',[
		'uses' =>'MainSectionController@Index',
		'as'   =>'mainSections',
		'title'=>'إدارة الاقسام الرئيسية',
		'subTitle'=>'  الاقسام',
		'icon' =>'<i class="fas fa-cog"></i>',
		'subIcon' =>'<i class="fas fa-building"></i>',
		'child'=>[
			'storemainSections',
			'updatemainSections'
		
		]
	]);



	# store section
	Route::post('store-main-sections',[
		'uses'=>'MainSectionController@Store',
		'as'  =>'storemainSections',
		'title'=>'إضافة قسم'
	]);

	# update section
	Route::post('update-main-sections',[
		'uses'=>'MainSectionController@Updatepsection',
		'as'  =>'updatemainSections',
		'title'=>'تحديث قسم'
	]);

	# images
	Route::get('main-images-home',[
		'uses' =>'ImagesController@Index',
		'as'   =>'imageshome',
		'title'=>'إدارة الصور الرئيسية',
		'subTitle'=>'  الصور',
		'icon' =>'<i class="fas fa-images"></i>',
		'subIcon' =>'<i class="fas fa-images"></i>',
		'child'=>[
			'Storehomeimage',
			'deleteehomeimage'
	
		
		]
	]);


	# store section
	Route::post('store-main-images-home',[
		'uses'=>'ImagesController@Storehomeimage',
		'as'  =>'Storehomeimage',
		'title'=>'إضافة صورة'
	]);

	# store section
	Route::post('delete-main-images-home',[
		'uses'=>'ImagesController@DeleteImage',
		'as'  =>'deleteehomeimage',
		'title'=>'حذف صورة'
	]);

	

});

#home page
Route::get('main-page',[
    'uses' =>'MainSectionController@MainImages',
    'as'   =>'mainImages',
    'title'=>'إدارة الصفحة الرئيسية',
    'subTitle'=>'  الصور الرئيسة',
    'icon' =>'<i class="fas fa-cog"></i>',
    'subIcon' =>'<i class="fas fa-building"></i>',
    'child'=>[
        'storemainImage',
        'updatemainImage',
        'deletemainImage'
    ]
]);
# store
Route::post('store-main-Image',[
    'uses'=>'MainSectionController@StoreImage',
    'as'  =>'storemainImage',
    'title'=>' إضافة صورة رئيسية'
]);

# update
Route::post('update-main-Image',[
    'uses'=>'MainSectionController@UpdateImage',
    'as'  =>'updatemainImage',
    'title'=>'تحديث صورة رئيسية'
]);
#delete
Route::post('delete-main-Image',[
    'uses'=>'MainSectionController@DeleteImage',
    'as'  =>'deletemainImage',
    'title'=>'حذف صورة رئيسية'
]);


#meta tags
Route::get('meta-tags',[
    'uses' =>'MetaTagController@Index',
    'as'   =>'MetaTags',
    'title'=>'إدارة ال Meta Tags',
    'subTitle'=>'meta tags',
    'icon' =>'<i class="fas fa-cog"></i>',
    'subIcon' =>'<i class="fas fa-building"></i>',
    'child'=>[
        'addMetaTag',
        'storeMetaTag',
        'updateMetaTag',
        'deleteMetaTag',
        'selectMetaTag'
    ]
]);

# add
Route::get('add-meta-tag',[
    'uses'=>'MetaTagController@addMetaTag',
    'as'  =>'addMetaTag',
    'icon' =>'<i class="fas fa-plus"></i>',
    'title'=>'إضافة meta tag',
    'hasFather'=>true
]);
#selection
Route::post('select-meta-tag',[
    'uses'=>'MetaTagController@selectMetaTag',
    'as'  =>'selectMetaTag',
    'title'=>'تحديد'
]);
# store
Route::post('store-meta-tag',[
    'uses'=>'MetaTagController@storeMetaTag',
    'as'  =>'storeMetaTag',
    'title'=>'حفظ ال meta tag'
]);

# update
Route::post('update-meta-tag',[
    'uses'=>'MetaTagController@updateMetaTag',
    'as'  =>'updateMetaTag',
    'title'=>'تحديث meta tag'
]);
#delete
Route::post('delete-meta-tag',[
    'uses'=>'MetaTagController@deleteMetaTag',
    'as'  =>'deleteMetaTag',
    'title'=>'حذف meta tag'
]);


// Auth::routes();
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');

Route::get('/swagger', function () {
    return view('swagger.index');
});