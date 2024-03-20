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

Route::prefix('localstock')->group(function() {
    Route::get('/', 'LocalStockController@index');
});



Route::group(['middleware' => ['role','auth']], function() {


	#------------------------------- start of SectionsController -----------------------------#

	# sections
	Route::get('local-stock-sections',[
		'uses' =>'LocalStockSectionController@Index',
		'as'   =>'local_sections',
		'title'=>' إدارة البورصة المحلية',
		'subTitle'=>'الأقسام الرئيسيه',
		'icon' =>'<i class="fas fa-poll"></i>',
		'subIcon' =>'<i class="fas fa-poll"></i>',
		'child'=>[
			'storelocalstocksections',
			'updatelocalsection',
			'deletelocalsection',
			'subsections',
            'AddsectionPage',
            'Editsection',
            'updatecolumn',
            'Deletecolumn',
            'columnStore',
			'storeMember',
			'showMember',
			'addMember',
			'addsection',
			'updateMember',
			'showmovements',
			'products',
			'Storeproduct',
			'Updateproduct',
			'Deleteproducts',
			'storepsections',
			'updatepsection',
			'deletepsection',
			'checkMember',
			'getcompanies',
			'Searchcompany',
			'Searchproduct',
			'addsections',
			'storelocalstocksectionss',
			'updatelocalsectionss',
			'Deletemember',
			'localsubnumsort',
            'selectpsection'
	
		]
	]);

	# store psection
	Route::post('store-local-stock-sections',[
		'uses'=>'LocalStockSectionController@Storepsection',
		'as'  =>'storepsections',
		'title'=>'إضافة قسم رئيسي'
	]);

	# update psection
	Route::post('update-local-stock-section',[
		'uses'=>'LocalStockSectionController@Updatepsection',
		'as'  =>'updatepsection',
		'title'=>'تحديث قسم رئيس '
	]);

    # update psection
    Route::post('select-local-stock-section',[
        'uses'=>'LocalStockSectionController@selectpsection',
        'as'  =>'selectpsection',
        'title'=>'تحديد قسم رئيس '
    ]);
	# delete psection
	Route::post('delete-local-stock-section',[
		'uses'=>'LocalStockSectionController@Deletepsection',
		'as'  =>'deletepsection',
		'title'=>'حذف قسم رئيسي'
	]);

	# sub sections
	Route::get('local-stock-sub-sections/{id?}',[
		'uses'=>'LocalStockSectionController@SubSections',
		'as'  =>'subsections',
		'title'=>'الأقسام الفرعية',
		'icon' =>'<i class="fas fa-puzzle-piece"></i>',
		'hasFather'=>true
	]);

	# sort companies (ajax)
	Route::post('sub-local-sort-num',[
		'uses'=>'LocalStockSectionController@sortsub',
		'as'  =>'localsubnumsort',
		'title'=>'ترتيب الاقسام الفرعية'
	]);

	# add section
	Route::get('add-localstock-sub-sections',[
		'uses'=>'LocalStockSectionController@addsection',
		'as'  =>'addsection',
		'title'=>' اضافة قسم فرعي  ',
	]);

	# store section
	Route::post('store-local-stock-sub-sections',[
		'uses'=>'LocalStockSectionController@Storesection',
		'as'  =>'storelocalstocksections',
		'title'=>'حفظ قسم فرعي '
	]);

	# add section
	Route::get('add-localstock-subs-sections',[
		'uses'=>'LocalStockSectionController@addsections',
		'as'  =>'addsections',
		'title'=>' اضافة قسم للكل  ',
	]);

	# store section
	Route::post('store-local-stock-subs-sections',[
		'uses'=>'LocalStockSectionController@Storesections',
		'as'  =>'storelocalstocksectionss',
		'title'=>'حفظ قسم للكل '
	]);

	# edit section
	 Route::get('edit-local-stock-sub-sections/{id}',[
		'uses'=>'LocalStockSectionController@Editsection',
		'as'  =>'Editsection',
		'title'=>'تحديث  قسم فرعي'
	]);

	# update section
	Route::post('update-local-stock-sub-sections',[
		'uses'=>'LocalStockSectionController@Updatesection',
		'as'  =>'updatelocalsection',
		'title'=>'تحديث قسم فرعي '
	]);


	# update section
	Route::post('update-local-stock-subs-sections',[
		'uses'=>'LocalStockSectionController@Updatesections',
		'as'  =>'updatelocalsectionss',
		'title'=>'تحديث قسم للكل '
	]);

	# delete section
	Route::post('delete-local-stock-sub-sections',[
		'uses'=>'LocalStockSectionController@Deletesection',
		'as'  =>'deletelocalsection',
		'title'=>'حذف قسم فرعي '
    ]);
  

	# columns
	Route::post('columns',[
		'uses'=>'LocalStockSectionController@columnStore',
		'as'  =>'columnStore',
		'title'=>'اضافة عمود'
	]);
	
	
	# update columns
	Route::post('update-column',[
		'uses'=>'LocalStockSectionController@updatecolumn',
		'as'  =>'updatecolumn',
		'title'=>'تحديث عمود'
	]);

	# delete columns
	Route::post('delete-column',[
		'uses'=>'LocalStockSectionController@Deletecolumn',
		'as'  =>'Deletecolumn',
		'title'=>'حذف عمود'
	]);
	

   	#------------------------------- end of SectionsController -----------------------------#

	# show section
	Route::get('show-members/{id}',[
		'uses'=>'LocalStockMemberController@showMember',
		'as'  =>'showMember',
		'title'=>'بيانات البورصة'
	]);

	# add members
	Route::get('add-members/{id}',[
		'uses'=>'LocalStockMemberController@addMember',
		'as'  =>'addMember',
		'title'=>'اضافة منتج او شركة'
	]);

    
	# check members
	Route::post('check-members',[
		'uses'=>'LocalStockMemberController@checkMember',
		'as'  =>'checkMember',
		'title'=>' فحص عضو'
	]);

    # store Members
	Route::post('Members',[
		'uses'=>'LocalStockMemberController@storeMember',
		'as'  =>'storeMember',
		'title'=>'اضافة عضو'
	]);

	# delete Members
	Route::post('delete-local-stock-Members',[
		'uses'=>'LocalStockMemberController@Deletemember',
		'as'  =>'Deletemember',
		'title'=>'حذف  عضو بورصة '
    ]);
	
	# get companies (ajax)
	Route::post('get-companies',[
		'uses'=>'LocalStockMemberController@Getcompanies',
		'as'  =>'getcompanies',
		'title'=>'جلب  الشركات'
	]);

	# get companies search (ajax)
	Route::post('get-companies-search',[
		'uses'=>'LocalStockMemberController@Searchcompany',
		'as'  =>'Searchcompany',
		'title'=>'جلب  بحث الشركات'
	]);

	# get product search (ajax)
	Route::post('get-product-search',[
		'uses'=>'LocalStockMemberController@Searchproduct',
		'as'  =>'Searchproduct',
		'title'=>'جلب  بحث المنتجات'
	]);

	# update Members
	Route::post('update-Members',[
		'uses'=>'LocalStockMemberController@updateMember',
		'as'  =>'updateMember',
		'title'=>'تحديث عضو'
	]);

	# update Members
	Route::post('update-all-Members',[
		'uses'=>'LocalStockMemberController@updateallMember',
		'as'  =>'updateallMember',
		'title'=>'تحديث كل الاعضاء'
	]);

	# show movements
	Route::get('show-movements/{id}',[
		'uses'=>'LocalStockMemberController@showmovements',
		'as'  =>'showmovements',
		'title'=>'تحديثات العضو'
	]);


	# product
	Route::get('local-stock-products',[
		'uses' =>'LocalStockproductController@Index',
		'as'   =>'products',
		'title'=>' ادارة المنتجات',
		'icon' =>'<i class="fas fa-poll-h"></i>',
		'hasFather' => true
	]);

	# store product
	Route::post('store-local-stock-product',[
		'uses'=>'LocalStockproductController@Storeproduct',
		'as'  =>'Storeproduct',
		'title'=>'إضافة منتج لبورصة'
	]);

	# update product
	Route::post('update-local-stock-product',[
		'uses'=>'LocalStockproductController@Updateproduct',
		'as'  =>'Updateproduct',
		'title'=>'تحديث منتج'
	]);

	# delete product
	Route::post('delete-local-stock-product',[
		'uses'=>'LocalStockproductController@Deleteproducts',
		'as'  =>'Deleteproducts',
		'title'=>'حذف  منتج'
	]);

	#------------------------------- end of LocalStockSectionController -----------------------------#


});
//
//// sub sections
//Route::get('local-stock-section/{name}',[
//	'uses'=>'LocalStockFrontController@index',
//	'as'  =>'front_local_sections',
//	'type'=>'main',
//	'kind'=>'localstocksection',
//	'title'=>'القسم الرئيسي للبورصة',
//]);
//
//
//// sub sections sort name
//Route::get('local-stock-section-sort-name/{name}',[
//	'uses'=>'LocalStockFrontController@indexname',
//	'as'  =>'front_local_sections_sort_name',
//	'type'=>'main',
//	'kind'=>'localstocksectionview',
//	'title'=>'القسم الرئيسي للبورصة حسب الاسم ',
//]);
//
//// sub sections sort
//Route::get('local-stock-section-sort-view-count/{name}',[
//	'uses'=>'LocalStockFrontController@sort',
//	'as'  =>'front_local_sections_sort',
//	'type'=>'main',
//	'kind'=>'localstocksectionview',
//	'title'=>'القسم الرئيسي للبورصة حسب الاكثر تداولا',
//]);
//
//// members
//Route::get('local-stock-members/{id}/{type?}',[
//	'uses'=>'LocalStockFrontController@Members',
//	'as'  =>'front_local_members',
//	'type'=>'sub',
//	'kind'=>'localstocksubsection',
//	'title'=>'بورصات محلية',
//]);
//
//// sub sections search
//Route::post('get-local-sub-sections-search', 'LocalStockFrontController@Getsections')->name('front_local_sub_section_search');
//
//Route::post('get-local-sub-sections-sorting', 'LocalStockFrontController@Getsectionssort')->name('front_local_sub_section_sorting');
//
//
//
//// sub sections search
//Route::post('get-local-sub-sections-search-name', 'LocalStockFrontController@Getsectionsname')->name('front_local_sub_section_search_name');
//
//// members
//Route::get('local-stock-members-comprison/{id}', 'LocalStockFrontController@comprison')->name('front_local_comprison');
//
//
//// companies
//Route::post('get-local-companies', 'LocalStockFrontController@Getcompanies')->name('front_local_companies');
//
//
//// members
//Route::get('fodder-stock-members-comprison/{id}',[
//	'uses'=>'LocalStockFrontController@comprisonfodder',
//	'as'  =>'front_fodder_comprison',
//	'type'=>'sub',
//	'kind'=>'localstocksubsectioncomprison',
//	'title'=>'المقارنة',
//]);
//
//// companies
//Route::post('get-fodder-company', 'LocalStockFrontController@Getcompaniesfodder')->name('front_fodder_companies');
//
//// statistics sections
//Route::get('local-stock-statistics/{id}',[
//	'uses'=>'LocalStockFrontController@statistic',
//	'as'  =>'front_local_statistic',
//	'type'=>'sub',
//	'kind'=>'localstocksubsectionstatistics',
//	'title'=>'حصائيات البورصات',
//]);
//
//// statistics members
//Route::get('local-stock-statistics-members/{id}',[
//	'uses'=>'LocalStockFrontController@statisticmembers',
//	'as'  =>'front_local_statistic_members',
//	'type'=>'sub',
//	'kind'=>'localstockmembersstatistics',
//	'title'=>'حصائيات الشركات والمنتجات',
//]);
//// detials sections
//Route::get('local-stock-detials/{id}',[
//	'uses'=>'LocalStockFrontController@detials',
//	'as'  =>'front_local_detials',
//	'type'=>'sub',
//	'kind'=>'localstocksectionsstatisticsdetials',
//	'title'=>'تفاصيل حصائيات البورصات ',
//]);
//
//// detials member
//Route::get('local-stock-detials-member/{id}',[
//	'uses'=>'LocalStockFrontController@detialsmember',
//	'as'  =>'front_local_detials_member',
//	'type'=>'sub',
//	'kind'=>'localstockmembersstatisticsdetials',
//	'title'=>'تفاصيل حصائيات الشركات والمنتجات',
//]);
//
//// statistics sections
//Route::post('get-local-sections', 'LocalStockFrontController@statisticdrop')->name('get_local_statistic');
//
//// statistics member
//Route::post('get-member-sections', 'LocalStockFrontController@statisticdropmember')->name('get_member_statistic');