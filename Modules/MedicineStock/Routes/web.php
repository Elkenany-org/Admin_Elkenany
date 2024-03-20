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

Route::prefix('medicinestock')->group(function() {
    Route::get('/', 'MedicineStockController@index');
});

Route::group(['middleware' => ['role','auth']], function() {


	#------------------------------- start of SectionsController -----------------------------#

	# sections
	Route::get('stock-medicine-sections',[
		'uses' =>'MedicineStockSectionsController@Index',
		'as'   =>'stock_medicine_sections',
		'title'=>' إدارة بورصة الأدوية',
		'subTitle'=>'الأقسام الرئيسيه',
		'icon' =>'<i class="fas fa-mortar-pestle"></i>',
		'subIcon' =>'<i class="fas fa-mortar-pestle"></i>',
		'child'=>[
			'Storemedicinesection',
            'updatemedicinesection',
            'selectmedicinesection',
            'deletemedicinesection',
            'medicinesubsections',
            'submedicinenumsort',
            'storemedicinesubsection',
            'updatemedicinesubsection',
			'deletemedicinesubsection',
			'activesubs',
			'Storeactive',
			'Updateactive',
			'Deleteactive',
			'names',
			'addnames',
			'Storenames',
			'editnames',
			'storeImagesnames',
			'DeleteImagenames',
			'updatenames',
			'Deletenames',
			'medicinestockmembers',
			'addstocksmedicine',
			'Getsectionsmedicine',
			'Storestocksmedicine',
			'updateMembermedicine',
			'showmovementsmedicine',
			'Deletemembermedicine',
			'checkmedicine'
            
		
			
            
		]
	]);

	# store psection
	Route::post('store-stock-medicine-sections',[
		'uses'=>'MedicineStockSectionsController@Storepsection',
		'as'  =>'Storemedicinesection',
		'title'=>'إضافة قسم رئيسي'
	]);

	# update psection
	Route::post('update-stock-medicine-section',[
		'uses'=>'MedicineStockSectionsController@Updatepsection',
		'as'  =>'updatemedicinesection',
		'title'=>'تحديث قسم رئيس '
	]);

    # select psection
    Route::post('select-stock-medicine-section',[
        'uses'=>'MedicineStockSectionsController@selectpsection',
        'as'  =>'selectmedicinesection',
        'title'=>'تحديد قسم رئيس '
    ]);

	# delete psection
	Route::post('delete-stock-medicine-section',[
		'uses'=>'MedicineStockSectionsController@Deletepsection',
		'as'  =>'deletemedicinesection',
		'title'=>'حذف قسم رئيسي'
	]);
	
	# sub sections
	Route::get('sub-medicine-sections/{id?}',[
		'uses'=>'MedicineStockSectionsController@SubSections',
		'as'  =>'medicinesubsections',
		'title'=>'بورصات الأدوية ',
		'icon' =>'<i class="fas fa-puzzle-piece"></i>',
		'hasFather'=>true
	]);

	# sort companies (ajax)
	Route::post('sub-medicine-sort-num',[
		'uses'=>'MedicineStockSectionsController@sortsub',
		'as'  =>'submedicinenumsort',
		'title'=>'ترتيب الاقسام الفرعية'
	]);


	# store sub section
	Route::post('store-medicine-subsection',[
		'uses'=>'MedicineStockSectionsController@StoreSubSection',
		'as'  =>'storemedicinesubsection',
		'title'=>' إضافة بورصة ادوية'
	]);

	# update subsection
	Route::post('update-medicine-subsection',[
		'uses'=>'MedicineStockSectionsController@UpdateSubSection',
		'as'  =>'updatemedicinesubsection',
		'title'=>'تحديث  بورصة ادوية'
	]);

	# delete sub section
	Route::post('delete-medicine-subsection',[
		'uses'=>'MedicineStockSectionsController@DeleteSubSection',
		'as'  =>'deletemedicinesubsection',
		'title'=>' حذف  بورصة ادوية'
	]);


	# sub active
	Route::get('active-medicine/{id?}',[
		'uses'=>'MedicineStockSectionsController@activesubs',
		'as'  =>'activesubs',
		'title'=>' المواد الفعالة ',
		'icon' =>'<i class="fas fa-syringe"></i>',
		'hasFather'=>true
	]);


	# store sub active
	Route::post('store-medicine-active',[
		'uses'=>'MedicineStockSectionsController@Storeactive',
		'as'  =>'Storeactive',
		'title'=>' إضافة مادة فعالة'
	]);

	# update active
	Route::post('update-medicine-active',[
		'uses'=>'MedicineStockSectionsController@Updateactive',
		'as'  =>'Updateactive',
		'title'=>'تحديث مادة فعالة'
	]);

	# delete sub active
	Route::post('delete-medicine-active',[
		'uses'=>'MedicineStockSectionsController@Deleteactive',
		'as'  =>'Deleteactive',
		'title'=>' حذف مادة فعالة'
	]);

	# names
	Route::get('names-medicine/{id?}',[
		'uses'=>'MedicineStockSectionsController@names',
		'as'  =>'names',
		'title'=>'  إدارة الأسامي التجارية ',
		'icon' =>'<i class="fas fa-file-signature"></i>',
		'hasFather'=>true
	]);

	# names add
	Route::get('add-names-medicine/{id?}',[
		'uses'=>'MedicineStockSectionsController@addnames',
		'as'  =>'addnames',
		'title'=>'إضافة اسم تجاري ',

	]);


	# store names
	Route::post('store-medicine-names',[
		'uses'=>'MedicineStockSectionsController@Storenames',
		'as'  =>'Storenames',
		'title'=>' حفظ إسم تجاري'
	]);

	# edit user
	Route::get('edit-names/{id}',[
		'uses'=>'MedicineStockSectionsController@editnames',
		'as'  =>'editnames',
		'title'=>'تعديل إسم تجاري'
	]);

	
	# images
	Route::post('images-names',[
		'uses'=>'MedicineStockSectionsController@storeImagesnames',
		'as'  =>'storeImagesnames',
		'title'=>'اضافة صور'
	]);

	# delete image
	Route::post('delete-image-names',[
		'uses'=>'MedicineStockSectionsController@DeleteImagenames',
		'as'  =>'DeleteImagenames',
		'title'=>'حذف صور'
	]);

	# update names 
	Route::post('update-names',[
		'uses'=>'MedicineStockSectionsController@updatenames',
		'as'  =>'updatenames',
		'title'=>' تحديث اسم تجاري'
	]);


	# delete names
	Route::post('delete-names',[
		'uses'=>'MedicineStockSectionsController@Deletenames',
		'as'  =>'Deletenames',
		'title'=>'حذف إسم تجاري'
	]);


	#------------------------------- end of MedicineStockSectionsController -----------------------------#
	


	# stock members
	Route::get('stock-medicine-members/{id}',[
		'uses'=>'MedicineStockController@index',
		'as'  =>'medicinestockmembers',
		'title'=>'محتوي بورصة الأدوية ',
		
	]);

	# add stock members
	Route::get('add-medicine-member',[
		'uses'=>'MedicineStockController@addstocks',
		'as'  =>'addstocksmedicine',
		'title'=>'  إضافة عضو'
	]);

	# get sub sections (ajax)
	Route::post('get-stock-medicine',[
		'uses'=>'MedicineStockController@Getsections',
		'as'  =>'Getsectionsmedicine',
		'title'=>'جلب  الاقسام'
	]);

	# store stock members
	Route::post('store-stock-medicine-stocks',[
		'uses'=>'MedicineStockController@Storestocks',
		'as'  =>'Storestocksmedicine',
		'title'=>'حفظ صنف '
	]);
	

	# update Members
	Route::post('update-stock-medicine-stocks',[
		'uses'=>'MedicineStockController@updateMember',
		'as'  =>'updateMembermedicine',
		'title'=>'تحديث صنف'
	]);
	

	# show movements
	Route::get('show-movements-medicine-stocks/{id}',[
		'uses'=>'MedicineStockController@showmovements',
		'as'  =>'showmovementsmedicine',
		'title'=>'تحديثات الصنف'
	]);

	# check members
	Route::post('check-medicine-stocks',[
		'uses'=>'MedicineStockController@checkMember',
		'as'  =>'checkmedicine',
		'title'=>' فحص صنف'
	]);

	# delete Members
	Route::post('delete-medicine-stocks-Members',[
		'uses'=>'MedicineStockController@Deletemember',
		'as'  =>'Deletemembermedicine',
		'title'=>'حذف  عضو بورصة '
	]);


});