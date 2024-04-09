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



#------------------------------- start of MajorsController -----------------------------#

	# sections
	Route::get('majors',[
		'uses' =>'MajorsController@Index',
		'as'   =>'majors',
		'title'=>'إدارة الاستشاريين',
		'subTitle'=>' التخصصات',
		'icon' =>'<i class="fas fa-user-nurse"></i>',
		'subIcon' =>'<i class="fas fa-user-nurse"></i>',
		'child'=>[
			'Storemajor',
			'Updatemajor',
            'Deletemajor',
			'AdddoctorPage',
			'IndexSubSection',
            'doctors',
            'Storedoctor',
            'Editdoctor',
            'Updatedoctor',
            'Deletedoctor',
            'Storeservies',
            'updateservies',
			'Deleteservies',
			'showorders',
			'Deleteorders',
			'showorder',
			'ShowOrd',
			'Storsections',
			'Updatesection',
			'Deletesection',
			'GetSubSectionsm',
			'Deletelink',
			'updatelink',
			'Storelinks'
		]
	]);

	# store majors
	Route::post('store-majors',[
		'uses'=>'MajorsController@Storemajor',
		'as'  =>'Storemajor',
		'title'=>'إضافة تخصص'
	]);

	# update majors
	Route::post('update-majors',[
		'uses'=>'MajorsController@Updatemajor',
		'as'  =>'Updatemajor',
		'title'=>'تحديث تخصص'
	]);

	# delete majors
	Route::post('delete-majors',[
		'uses'=>'MajorsController@Deletemajor',
		'as'  =>'Deletemajor',
		'title'=>'حذف تخصص'
	]);

	# sub sections
	Route::get('majors-sections',[
		'uses' =>'MajorsController@IndexSubSection',
		'as'   =>'IndexSubSection',
		'title'=>' الاقسام الفرعية للتخصصات',
        'icon' =>'<i class="fas fa-puzzle-piece"></i>',
        'hasFather'=>true
		
	]);

	# store sub sections
	Route::post('store-majors-sections',[
		'uses'=>'MajorsController@Storsections',
		'as'  =>'Storsections',
		'title'=>'إضافة قسم للتخصص'
	]);

	# update sub sections
	Route::post('update-majors-sub-sections',[
		'uses'=>'MajorsController@Updatesection',
		'as'  =>'Updatesection',
		'title'=>'تحديث قسم للتخصص'
	]);

	# delete sub sections
	Route::post('delete-majors-sections',[
		'uses'=>'MajorsController@Deletesection',
		'as'  =>'Deletesection',
		'title'=>'حذف قسم للتخصص'
	]);

#------------------------------- start of DoctorsController -----------------------------#

	# doctors
	Route::get('doctors',[
		'uses' =>'DoctorsController@Index',
		'as'   =>'doctors',
		'title'=>' الاستشاريين',
        'icon' =>'<i class="fas fa-users"></i>',
        'hasFather'=>true
		
	]);

	# add doctors
	Route::get('add-doctors-page',[
		'uses'=>'DoctorsController@AdddoctorPage',
		'as'  =>'AdddoctorPage',
		'title'=>'إضافة استشاري',
		
	]);

	# get sub sections (ajax)
	Route::post('get-sub-sections-to-doctors',[
		'uses'=>'DoctorsController@GetSubSectionsm',
		'as'  =>'GetSubSectionsm',
		'title'=>'جلب الأقسام الفرعية'
	]);

	# store doctors
	Route::post('store-doctors',[
		'uses'=>'DoctorsController@Storedoctor',
		'as'  =>'Storedoctor',
		'title'=>'حفظ استشاري'
	]);

	# edit doctors
	Route::get('edit-doctors/{id}',[
		'uses'=>'DoctorsController@Editdoctor',
		'as'  =>'Editdoctor',
		'title'=>'تعديل استشاري'
	]);

	# update doctors
	Route::post('update-doctors',[
		'uses'=>'DoctorsController@Updatedoctor',
		'as'  =>'Updatedoctor',
		'title'=>'تحديث استشاري'
	]);

	# delete doctors
	Route::post('delete-doctors',[
		'uses'=>'DoctorsController@Deletedoctor',
		'as'  =>'Deletedoctor',
		'title'=>'حذف استشاري'
    ]);
    
    # store servies
	Route::post('store-servies',[
		'uses'=>'DoctorsController@Storeservies',
		'as'  =>'Storeservies',
		'title'=>'حفظ وقت'
    ]);
    
    # update servies
	Route::post('update-servies',[
		'uses'=>'DoctorsController@updateservies',
		'as'  =>'updateservies',
		'title'=>'تحديث وقت'
    ]);
    
    # delete servies
	Route::post('delete-servies',[
		'uses'=>'DoctorsController@Deleteservies',
		'as'  =>'Deleteservies',
		'title'=>'حذف وقت'
	]);


	# store links
	Route::post('store-links',[
		'uses'=>'DoctorsController@Storelinks',
		'as'  =>'Storelinks',
		'title'=>'حفظ لينك'
	]);
	
	# update links
	Route::post('update-links',[
		'uses'=>'DoctorsController@updatelink',
		'as'  =>'updatelink',
		'title'=>'تحديث لينك'
	]);
	
	# delete links
	Route::post('delete-links',[
		'uses'=>'DoctorsController@Deletelink',
		'as'  =>'Deletelink',
		'title'=>'حذف لينك'
	]);
	
	# orders
	Route::get('orders',[
		'uses' =>'DoctorsController@showorders',
		'as'   =>'showorders',
		'title'=>' الطلبات',
        'icon' =>'<i class="fas fa-money-check-alt"></i>',
        'hasFather'=>true
		
	]);

	# order for doctor 
	Route::get('order-doctors/{id}',[
		'uses'=>'DoctorsController@showorder',
		'as'  =>'showorder',
		'title'=>' طلبات الاستشاري'
	]);

	# show order 
	Route::get('order-show/{id}',[
		'uses'=>'DoctorsController@ShowOrd',
		'as'  =>'ShowOrd',
		'title'=>'  بيانات الطلب'
	]);

	# delete orders
	Route::post('delete-orders',[
		'uses'=>'DoctorsController@Deleteorders',
		'as'  =>'Deleteorders',
		'title'=>'حذف الطلب'
	]);
});


// frontend
//// majors
//Route::get('/all-majors', 'frontConsultantsController@index')->name('front_majors');
//// sections
Route::get('major/{name}', 'frontConsultantsController@subsections')->name('front_sections');
//
//// sub sections search
//Route::post('get-section-search-name-in-majors', 'frontConsultantsController@GetSubSectionsserchname')->name('front_section_name_search_major');
//
//// doctors
//Route::get('consultant/major/section/{id}', 'frontConsultantsController@doctors')->name('front_doctors');
//
//// my res
//Route::get('consultant/major/doctor/my-res{id}', 'frontConsultantsController@res')->name('front_my_res');
//
//// sections
// Route::get('consultant/major/{id}', 'frontConsultantsController@sectionss')->name('front_sectionss');
//// doctor
//Route::get('consultant/{id}', 'frontConsultantsController@doctor')->name('front_doctor');
//
//// order call
//Route::get('consultant/{id}/resav-call', 'frontConsultantsController@ordercall')->name('front_orders_call');
//
//// order online
//Route::get('consultant/{id}/resav-online', 'frontConsultantsController@orderonline')->name('front_orders_online');
//
//// order meeting
//Route::get('consultant/{id}/resav-meeting', 'frontConsultantsController@ordermeeting')->name('front_orders_meeting');
//
//
//// order
//Route::post('consultant/order', 'frontConsultantsController@storeorder')->name('front_order');
//
//
//// rating
//Route::post('consultant-rate', 'frontConsultantsController@rating')->name('front_consultant_rating');
//
//// rating
//Route::post('consultant-update-rating', 'frontConsultantsController@updaterating')->name('front_consultant_update_rating');
//
//// reserv date call
//Route::post('get-reserv-date-call', 'frontConsultantsController@Getcall')->name('front_Getcall');
//
//// reserv date online
//Route::post('get-reserv-date-online', 'frontConsultantsController@Getonline')->name('front_Getonline');
//
//// reserv date meeting
//Route::post('get-reserv-date-meeting', 'frontConsultantsController@Getmetting')->name('front_Getmetting');
//
//
//// sub sections search
//Route::post('get-sub-section-consultant', 'frontConsultantsController@GetSubSections')->name('front_sub_section_consultant');
//


