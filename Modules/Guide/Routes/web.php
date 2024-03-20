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

Route::prefix('guide')->group(function() {
    Route::get('/', 'GuideController@index');
});

Route::group(['middleware' => ['role','auth']], function() {


	

	#------------------------------- end of CompaniesController -----------------------------#

	# sections
	Route::get('sections',[
		'uses' =>'GuideSectionsController@Index',
		'as'   =>'sections',
		'title'=>'إدارة الدليل',
		'subTitle'=>' الاقسام الرئيسية',
		'icon' =>' <i class="fas fa-building"></i> ',
		'subIcon' =>' <i class="fas fa-building"></i> ',
		'child' =>[
			'storeguidesections',
			'updateguideSection',
            'deleteguidesection',
            'guidesubsections',
            'deleteguidesubsection',
            'storeguidesubsection',
			'updateguidesubsection',
			'companies',
			'addcompany',
			'storecompany',
			'editcompany',
			'updatecompany',
			'deletecompany',
			'getsubsections',
			'storeImages',
			'storeImages',
			'deleteimages',
			'updateimages',
			'Updatesocial',
			'Deleteproduct',
			'updateproduct',
			'storeproduct',
			'Updatecontact',
			'storesocial',
			'Updatelocal',
			'storetransport',
			'updatetransport',
			'Deletetransport',
			'storegallary',
			'updategallary',
			'Deletegallary',
			'gallary',
			'GetSubSectionsserch',
			'Getcities',
			'guidecomps',
			'companyajax',
			'companyajaxsec',
			'companynumsort',
			'guidesubnumsort',
			'selectguidesection'
			
		]

	]);


	# store section
	Route::post('store-guide-sections',[
		'uses'=>'GuideSectionsController@Store',
		'as'  =>'storeguidesections',
		'title'=>'إضافة قسم'
	]);

	# update section
	Route::post('update-guide-sections',[
		'uses'=>'GuideSectionsController@Update',
		'as'  =>'updateguideSection',
		'title'=>'تحديث قسم'
	]);

    # select section
    Route::post('select-guide-sections',[
        'uses'=>'GuideSectionsController@select',
        'as'  =>'selectguidesection',
        'title'=>'تحديد قسم'
    ]);

	# delete section
	Route::post('delete-guide-sections',[
		'uses'=>'GuideSectionsController@Delete',
		'as'  =>'deleteguidesection',
		'title'=>'حذف قسم'
	]);
	
    # sub sections
	Route::get('sub-guide-sections/{id?}',[
		'uses'=>'GuideSectionsController@SubSections',
		'as'  =>'guidesubsections',
		'title'=>'الأقسام الفرعية',
		'icon' =>'<i class="fas fa-puzzle-piece"></i>',
		'hasFather'=>true
	]);

	# sort companies (ajax)
	Route::post('sub-sort-num',[
		'uses'=>'GuideSectionsController@sortsub',
		'as'  =>'guidesubnumsort',
		'title'=>'ترتيب الاقسام الفرعية'
	]);

	# sub sections
	Route::get('sub-guide-sections-companies/{id}',[
		'uses'=>'GuideSectionsController@comps',
		'as'  =>'guidecomps',
		'title'=>'شركات القسم',
	]);

	# store sub section
	Route::post('store-guide-subsection',[
		'uses'=>'GuideSectionsController@StoreSubSection',
		'as'  =>'storeguidesubsection',
		'title'=>' إضافة قسم فرعي'
	]);

	# update subsection
	Route::post('update-guide-subsection',[
		'uses'=>'GuideSectionsController@UpdateSubSection',
		'as'  =>'updateguidesubsection',
		'title'=>'تحديث قسم فرعي'
	]);

	# delete sub section
	Route::post('delete-guide-subsection',[
		'uses'=>'GuideSectionsController@DeleteSubSection',
		'as'  =>'deleteguidesubsection',
		'title'=>' حذف قسم فرعي'
	]);

	# get sub sections (ajax)
	Route::post('get-sub-sections',[
		'uses'=>'CompaniesController@GetSubSections',
		'as'  =>'getsubsections',
		'title'=>'جلب الأقسام الفرعية'
	]);

	# get sections search (ajax)
	Route::post('get-sub-sections-guide-search',[
		'uses'=>'CompaniesController@GetSubSectionsserch',
		'as'  =>'GetSubSectionsserch',
		'title'=>'بحث الأقسام الفرعية'
	]);

	# companies
	Route::get('companies',[
		'uses'=>'CompaniesController@index',
		'as'  =>'companies',
		'title'=>' قائمة الشركات',
		'icon' =>'<i class="fas fa-puzzle-piece"></i>',
		'hasFather'=>true
	]);

	# get companies (ajax)
	Route::post('get-companies-ser',[
		'uses'=>'CompaniesController@companyajax',
		'as'  =>'companyajax',
		'title'=>'جلب  الشركات'
	]);

	# get companies (ajax)
	Route::post('get-companies-ser-sec',[
		'uses'=>'GuideSectionsController@companyajaxsec',
		'as'  =>'companyajaxsec',
		'title'=>'جلب  الشركات بقسم'
	]);

	# sort companies (ajax)
	Route::post('companies-sort-num',[
		'uses'=>'CompaniesController@sort',
		'as'  =>'companynumsort',
		'title'=>'ترتيب  الشركات بقسم'
	]);

	# add company
	Route::get('add-company',[
		'uses'=>'CompaniesController@Add',
		'as'  =>'addcompany',
		'title'=>'إضافة شركة',
		'icon' =>' <i class="fas fa-plus"></i> ',
		'hasFather' => true
	]);

	# get cities (ajax)
	Route::post('get-cities',[
		'uses'=>'CompaniesController@Getcities',
		'as'  =>'Getcities',
		'title'=>'جلب  المحافظات'
	]);

	# store company
	Route::post('store-company',[
		'uses'=>'CompaniesController@Store',
		'as'  =>'storecompany',
		'title'=>'حفظ شركة'
	]);

	# store social
	Route::post('store-company-social',[
		'uses'=>'CompaniesController@storesocial',
		'as'  =>'storesocial',
		'title'=>'حفظ التواصل'
	]);

	# edit company
	Route::get('edit-company/{id}',[
		'uses'=>'CompaniesController@Edit',
		'as'  =>'editcompany',
		'title'=>'تعديل شركة'
	]);

	# update company
	Route::post('update-company',[
		'uses'=>'CompaniesController@Update',
		'as'  =>'updatecompany',
		'title'=>'تحديث شركة'
	]);

	# update contact
	Route::post('update-company-contact',[
		'uses'=>'CompaniesController@Updatecontact',
		'as'  =>'Updatecontact',
		'title'=>'تحديث ارقام شركة'
	]);

	# update location
	Route::post('update-company-location',[
		'uses'=>'CompaniesController@Updatelocal',
		'as'  =>'Updatelocal',
		'title'=>'تحديث عناوين شركة'
	]);

	# delete company
	Route::post('delete-company',[
		'uses'=>'CompaniesController@Delete',
		'as'  =>'deletecompany',
		'title'=>'حذف شركة'
	]);

	# images
	Route::post('images-Companies',[
		'uses'=>'CompaniesController@storeImages',
		'as'  =>'storeImages',
		'title'=>'الصوراضافة'
	]);


	# update image
	Route::post('update-image-Companies',[
		'uses'=>'CompaniesController@Updateimage',
		'as'  =>'updateimages',
		'title'=>'تحديث صورة'
	]);

	# delete image
	Route::post('delete-image-Companies',[
		'uses'=>'CompaniesController@DeleteImage',
		'as'  =>'deleteimages',
		'title'=>'حذف صورة'
	]);

	# update social
	Route::post('update-social',[
		'uses'=>'CompaniesController@Updatesocial',
		'as'  =>'Updatesocial',
		'title'=>'تحديث مواقع التواصل'
	]);


	# product
	Route::post('product',[
		'uses'=>'CompaniesController@storeproduct',
		'as'  =>'storeproduct',
		'title'=>'اضافة منتج'
	]);

	# update product
	Route::post('update-product',[
		'uses'=>'CompaniesController@updateproduct',
		'as'  =>'updateproduct',
		'title'=>'تحديث منتج'
	]);

	# delete product
	Route::post('delete-product',[
		'uses'=>'CompaniesController@Deleteproduct',
		'as'  =>'Deleteproduct',
		'title'=>'حذف منتج'
	]);

	# store transport
	Route::post('store-transport',[
		'uses'=>'CompaniesController@storetransport',
		'as'  =>'storetransport',
		'title'=>'اضافة شحن'
	]);

	# update transport
	Route::post('update-transport',[
		'uses'=>'CompaniesController@updatetransport',
		'as'  =>'updatetransport',
		'title'=>'تحديث شحن'
	]);

	# delete transport
	Route::post('delete-transport',[
		'uses'=>'CompaniesController@Deletetransport',
		'as'  =>'Deletetransport',
		'title'=>'حذف تكلفة'
	]);

	# store gallary
	Route::post('store-gallary',[
		'uses'=>'CompaniesController@storegallary',
		'as'  =>'storegallary',
		'title'=>'اضافة البوم'
	]);

	# update gallary
	Route::post('update-gallary',[
		'uses'=>'CompaniesController@updategallary',
		'as'  =>'updategallary',
		'title'=>'تحديث البوم'
	]);

	# delete gallary
	Route::post('delete-gallary',[
		'uses'=>'CompaniesController@Deletegallary',
		'as'  =>'Deletegallary',
		'title'=>'حذف البوم'
	]);

	# gallary 
	Route::get('gallary-company-images/{id}',[
		'uses'=>'CompaniesController@gallary',
		'as'  =>'gallary',
		'title'=>' الالبوم'
	]);


	#------------------------------- end of CompaniesController -----------------------------#


});
//
//// frontend
//Route::get('/',[
//	'uses'=>'GuideFrontController@index',
//	'as'  =>'fronts',
//	'type'=>'main',
//	'kind'=>'home',
//	'title'=>'الرئيسية ',
//]);
//
//
//// sub sections
//Route::get('Guide-section/{name}',[
//	'uses'=>'GuideFrontController@SubSections',
//	'as'  =>'front_section',
//	'type'=>'main',
//	'kind'=>'Guidesubsections',
//	'title'=>'الاقسام الفرعية للدليل ',
//]);
//
//// sub sections sort
//Route::get('Guide-section-sort-view-count/{name}',[
//	'uses'=>'GuideFrontController@SubSectionssort',
//	'as'  =>'front_section_sort',
//	'type'=>'main',
//	'kind'=>'Guidesubsectionsview',
//	'title'=>'الاقسام الفرعية للدليل حسب الاكثر تداول ',
//]);
//
//
//// sub sections sort
//Route::get('Guide-section-sort-name/{name}',[
//	'uses'=>'GuideFrontController@SubSectionsname',
//	'as'  =>'front_section_sort_name',
//	'type'=>'main',
//	'kind'=>'Guidesubsectionsview',
//	'title'=>'الاقسام الفرعية للدليل حسب  الاسم ',
//]);
//
//// companies sort
//Route::get('Guide-section-sort-rate-count/{id}',[
//	'uses'=>'GuideFrontController@sortcompaniesrate',
//	'as'  =>'front_companies_sort_rate',
//	'type'=>'sub',
//	'kind'=>'Guidecompaniesrate',
//	'title'=>'الشركات حسب التقيم   ',
//]);
//
//// companies sort
//Route::get('Guide-section-sort-name-alph/{id}',[
//	'uses'=>'GuideFrontController@sortcompaniesname',
//	'as'  =>'front_companies_sort_name_alph',
//	'type'=>'sub',
//	'kind'=>'Guidecompaniesname',
//	'title'=>'الشركات حسب الاسم   ',
//]);
//
//// companies sort by city
//Route::get('Guide-section-sort-city/{id}',[
//	'uses'=>'GuideFrontController@sortcompaniescity',
//	'as'  =>'front_companies_city',
//	'type'=>'sub',
//	'kind'=>'Guidecompaniescity',
//	'title'=>'الشركات حسب المحافظات   ',
//]);
//
//// companies sort by countries
//Route::get('Guide-section-sort-countries/{id}',[
//	'uses'=>'GuideFrontController@sortcompaniescountry',
//	'as'  =>'front_companies_country',
//	'type'=>'sub',
//	'kind'=>'Guidecompaniescountries',
//	'title'=>'الشركات حسب الدول   ',
//]);
//
//// companies
//Route::get('Guide-companies/{id}',[
//	'uses'=>'GuideFrontController@companies',
//	'as'  =>'front_companies',
//	'type'=>'sub',
//	'kind'=>'Guidecompanies',
//	'title'=>'الشركات',
//]);
//
//// company
//Route::get('Guide-company/{id}',[
//	'uses'=>'GuideFrontController@company',
//	'as'  =>'front_company',
//	'kind'=>'Guidecompany',
//	'title'=>'الشركة',
//]);
//
//
//// nav_search
//Route::get('get-search-in-nav', 'GuideFrontController@ser')->name('front_nav_search');
//
//// transports
//Route::post('get-transports', 'GuideFrontController@Gettransports')->name('front_Gettransports');
//
//// transports fooder
//Route::post('get-transports-fooder', 'GuideFrontController@Gettransportsfooder')->name('front_Gettransports_fooder');
//
//// sub sections search
//Route::post('get-section-search-in-guide', 'GuideFrontController@Getsections')->name('front_section_search');
//
//
//
//// sub sections search
//Route::post('get-section-search-name-in-guide', 'GuideFrontController@GetSubSectionsserchname')->name('front_section_name_search');
//
//
//// sub sections search
//Route::post('get-section-sort-in-guide', 'GuideFrontController@Getsectionssort')->name('front_section_search_sort');
//
//
//// sub sections search
//Route::post('get-sub-section-search', 'GuideFrontController@GetSubSections')->name('front_sub_section_search');
//
//
//// companies search
//Route::post('get-companies-guide-search', 'GuideFrontController@Getcompanies')->name('front_companies_guide_searchs');
//
//// companies search
//Route::post('get-companies-guide-search-by-name', 'GuideFrontController@Getcompaniessearchname')->name('front_companies_guide_searchs_by_name');
//
//// companies search rate
//Route::post('get-companies-guide-search-rate', 'GuideFrontController@Getrating')->name('front_companies_guide_search_rate');
//
//
//
//
//// rating
//Route::post('company-rating', 'GuideFrontController@rating')->name('front_companies_rating');
//Route::get('guide-customer-rate/{company_id}','GuideFrontController@customerRate');
//Route::get('guide-rate-company/{company_id}','GuideFrontController@getrateOfCompany');
//
//// rating
//Route::post('company-update-rating', 'GuideFrontController@updaterating')->name('front_companies_update_rating');
//
//Route::post('/', 'GuideFrontController@tok')->name('save_token');