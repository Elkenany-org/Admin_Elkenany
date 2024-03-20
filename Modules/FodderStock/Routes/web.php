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

Route::prefix('fodderstock')->group(function() {
    Route::get('/', 'FodderStockController@index');
});


Route::group(['middleware' => ['role','auth']], function() {


	#------------------------------- start of SectionsController -----------------------------#

	# sections
	Route::get('stock-fodder-sections',[
		'uses' =>'FodderStockSectionsController@Index',
		'as'   =>'stock_fodder_sections',
		'title'=>' إدارة بورصة السلع',
		'subTitle'=>'الأقسام الرئيسيه',
		'icon' =>'<i class="fas fa-poll"></i>',
		'subIcon' =>'<i class="fas fa-poll"></i>',
		'child'=>[
			'Storefoddersection',
			'updatefoddersection',
			'deletefoddersection',
			'foddersubsections',
			'stockssections',
            'feeds',
            'Storefeed',
            'Updatefeed',
            'UpdateAllFeedsItem',
            'Deletefeed',
			'addstocks',
			'subsstocks',
			'stocks',
			'stockss',
            'Storestocks',
			'Getfeeds',
			'updateMemberfodder',
			'checkfodder',
			'showmovementsfodder',
			'Deletememberf',
			'deletefoddersubsection',
			'updatefoddersubsection',
			'storefoddersubsection',
			'feedssections',
			'subsfeeds',
			'indexfeeds',
			'Getfeedsfooder',
			'stockscompanies',
			'stocksscompany',
			'subfodnumsort',
			'feedssectionsmini',
			'subsfeedsmini',
			'indexfeedsmini',
			'Storefeedmini',
			'Updatefeedmini',
			'Deletefeedmini',
            'selectfoddersection'

			
            
		]
	]);

	# store psection
	Route::post('store-stock-fodder-sections',[
		'uses'=>'FodderStockSectionsController@Storepsection',
		'as'  =>'Storefoddersection',
		'title'=>'إضافة قسم رئيسي'
	]);

	# update psection
	Route::post('update-stock-fodder-section',[
		'uses'=>'FodderStockSectionsController@Updatepsection',
		'as'  =>'updatefoddersection',
		'title'=>'تحديث قسم رئيس '
	]);

    # update psection
    Route::post('select-stock-fodder-section',[
        'uses'=>'FodderStockSectionsController@selectpsection',
        'as'  =>'selectfoddersection',
        'title'=>'تحديد قسم رئيس '
    ]);

    # delete psection
	Route::post('delete-stock-fodder-section',[
		'uses'=>'FodderStockSectionsController@Deletepsection',
		'as'  =>'deletefoddersection',
		'title'=>'حذف قسم رئيسي'
	]);
	
	# sub sections
	Route::get('sub-fodder-sections/{id?}',[
		'uses'=>'FodderStockSectionsController@SubSections',
		'as'  =>'foddersubsections',
		'title'=>'الأقسام الفرعية',
		'icon' =>'<i class="fas fa-puzzle-piece"></i>',
		'hasFather'=>true
	]);

	# sort companies (ajax)
	Route::post('sub-fod-sort-num',[
		'uses'=>'FodderStockSectionsController@sortsub',
		'as'  =>'subfodnumsort',
		'title'=>'ترتيب الاقسام الفرعية'
	]);


	# store sub section
	Route::post('store-fodder-subsection',[
		'uses'=>'FodderStockSectionsController@StoreSubSection',
		'as'  =>'storefoddersubsection',
		'title'=>' إضافة قسم فرعي'
	]);

	# update subsection
	Route::post('update-fodder-subsection',[
		'uses'=>'FodderStockSectionsController@UpdateSubSection',
		'as'  =>'updatefoddersubsection',
		'title'=>'تحديث قسم فرعي'
	]);

	# delete sub section
	Route::post('delete-fodder-subsection',[
		'uses'=>'FodderStockSectionsController@DeleteSubSection',
		'as'  =>'deletefoddersubsection',
		'title'=>' حذف قسم فرعي'
	]);


    #------------------------------- end of FodderStockSectionsController -----------------------------#
    
    # feeds
	Route::get('stock-fodder-feeds-sections',[
		'uses'=>'FodderStockFeedsController@sections',
		'as'  =>'feedssections',
		'title'=>' اصناف العلف',
		'icon' =>'<i class="fas fa-seedling"></i>',
		'hasFather'=>true
	]);
	
	# feed subs
	Route::get('feeds-fodder-subs-sections/{id}',[
		'uses'=>'FodderStockFeedsController@subsstocks',
		'as'  =>'subsfeeds',
		'title'=>'  منتجات سلع لقسم ',
	]);

	# feeds
	Route::get('feeds-fodder-subs/{id}',[
		'uses'=>'FodderStockFeedsController@index',
		'as'  =>'indexfeeds',
		'title'=>'  منتجات الاعلاف ',
	]);
    
    # store fodder
	Route::post('store-stock-fodder-feeds',[
		'uses'=>'FodderStockFeedsController@Storefeed',
		'as'  =>'Storefeed',
		'title'=>'إضافة صنف علف'
	]);

	# update fodder
	Route::post('update-stock-fodder-feeds',[
		'uses'=>'FodderStockFeedsController@Updatefeed',
		'as'  =>'Updatefeed',
		'title'=>'تحديث صنف علف '
	]);

	# delete fodder
	Route::post('delete-stock-fodder-feeds',[
		'uses'=>'FodderStockFeedsController@Deletefeed',
		'as'  =>'Deletefeed',
		'title'=>'حذف صنف علف'
	]);

    # edit all selected fodder
	Route::post('update-stock-fodder-feeds-items',[
		'uses'=>'FodderStockFeedsController@UpdateAllFeedsItem',
		'as'  =>'UpdateAllFeedsItem',
		'title'=>'تعديل كل الاصناف'
	]);
	



	
    # feeds mini
	Route::get('stock-fodder-feeds-sections-mini',[
		'uses'=>'FodderStockFeedsController@sectionsmini',
		'as'  =>'feedssectionsmini',
		'title'=>' اقسام العلف',
		'icon' =>'<i class="fas fa-seedling"></i>',
		'hasFather'=>true
	]);
	
	# feed subs mini
	Route::get('feeds-fodder-subs-sections-mini/{id}',[
		'uses'=>'FodderStockFeedsController@subsstocksmini',
		'as'  =>'subsfeedsmini',
		'title'=>'  اقسام سلع لقسم ',
	]);

	# feeds
	Route::get('feeds-fodder-subs-mini/{id}',[
		'uses'=>'FodderStockFeedsController@indexmini',
		'as'  =>'indexfeedsmini',
		'title'=>'  اقسام الاعلاف ',
	]);
    
    # store fodder
	Route::post('store-stock-feeds-mini',[
		'uses'=>'FodderStockFeedsController@Storefeedmini',
		'as'  =>'Storefeedmini',
		'title'=>'إضافة قسم علف'
	]);

	# update fodder
	Route::post('update-stock-feeds-mini',[
		'uses'=>'FodderStockFeedsController@Updatefeedmini',
		'as'  =>'Updatefeedmini',
		'title'=>'تحديث قسم علف '
	]);

	# delete fodder
	Route::post('delete-stock-feeds-mini',[
		'uses'=>'FodderStockFeedsController@Deletefeedmini',
		'as'  =>'Deletefeedmini',
		'title'=>'حذف قسم علف'
	]);
	





	#------------------------------- end of FodderStockFeedsController -----------------------------#
	#dashbord urls
	# stocks
	Route::get('stock-fodder-stocks-sections',[
		'uses'=>'FodderStocksController@index',
		'as'  =>'stockssections',
		'title'=>'   بورصات السلع',
		'icon' =>'<i class="fas fa-poll"></i>',
		'hasFather'=>true
	]);

	# companies
	Route::get('stock-fodder-stocks-companies',[
		'uses'=>'FodderStocksController@companies',
		'as'  =>'stockscompanies',
		'title'=>'   الشركات ',
		'icon' =>'<i class="fas fa-poll"></i>',
		'hasFather'=>true
	]);
	
	# stocks
	Route::get('stock-fodder-subs-sections/{id}',[
		'uses'=>'FodderStocksController@subsstocks',
		'as'  =>'subsstocks',
		'title'=>'  بورصة سلع لقسم ',
	]);

	# stocks
	Route::get('stock-fodder-stocks-for-company/{id}',[
		'uses'=>'FodderStocksController@stocksscompany',
		'as'  =>'stocksscompany',
		'title'=>'   سلع للشركة',
	]);
    
    # stocks
	Route::get('stock-fodder-stocks/{id}',[
		'uses'=>'FodderStocksController@stocks',
		'as'  =>'stocks',
		'title'=>'  بورصة سلع للشركة',
	]);
	
	# stockss
	Route::get('stock-fodder-stockss/{id}',[
		'uses'=>'FodderStocksController@stockss',
		'as'  =>'stockss',
		'title'=>' الاصناف بورصة اعلاف',
    ]);

    # add stocks
	Route::get('add-stocks',[
		'uses'=>'FodderStocksController@addstocks',
		'as'  =>'addstocks',
		'title'=>'اضافة  صنف'
	]);
    
    # store fodder stocks
	Route::post('store-stock-fodder-stocks',[
		'uses'=>'FodderStocksController@Storestocks',
		'as'  =>'Storestocks',
		'title'=>'حفظ صنف '
	]);
	
	
	# delete Members
	Route::post('delete-fodder-stock-Members',[
		'uses'=>'FodderStocksController@Deletemember',
		'as'  =>'Deletememberf',
		'title'=>'حذف  عضو بورصة '
    ]);

    # get sub sections (ajax)
	Route::post('get-fodder-sections',[
		'uses'=>'FodderStocksController@Getfeeds',
		'as'  =>'Getfeeds',
		'title'=>'جلب  الاقسام'
	]);

	# get sub feeds (ajax)
	Route::post('get-fodder-feeds',[
		'uses'=>'FodderStocksController@Getfeedsfooder',
		'as'  =>'Getfeedsfooder',
		'title'=>'جلب  الاصناف'
	]);

	# update Members
	Route::post('update-stocks-fodder',[
		'uses'=>'FodderStocksController@updateMember',
		'as'  =>'updateMemberfodder',
		'title'=>'تحديث صنف'
	]);

	# check members
	Route::post('check-stocks-fodder',[
		'uses'=>'FodderStocksController@checkMember',
		'as'  =>'checkfodder',
		'title'=>' فحص صنف'
	]);

	# show movements
	Route::get('show-movements-stocks-fodder/{id}',[
		'uses'=>'FodderStocksController@showmovements',
		'as'  =>'showmovementsfodder',
		'title'=>'تحديثات الصنف'
	]);

    

	#------------------------------- end of FodderStocksController -----------------------------#


});
//
//
//// stocks
//Route::get('stock-fodder/{id}',[
//	'uses'=>'FodderStockFrontController@stocks',
//	'as'  =>'front_fodder_stocks',
//	'type'=>'sub',
//	'kind'=>'fodder',
//	'title'=>'الاقسام الفرعية لبرصة الاعلاف',
//]);
//
//// stocks
//Route::post('stock/fodder/feeds-search-{id}',[
//	'uses'=>'FodderStockFrontController@feeds',
//	'as'  =>'front_fodder_feeds',
//
//]);
//
//Route::post('stock/fodder/company-search-{id}',[
//	'uses'=>'FodderStockFrontController@companies',
//	'as'  =>'front_fodder_companies_stock',
//]);
//
//// statistics members
//Route::get('fodder-stock-statistics-members/{id}',[
//	'uses'=>'FodderStockFrontController@feedstst',
//	'as'  =>'front_fodder_statistic_members',
//	'type'=>'sub',
//	'kind'=>'statistics',
//	'title'=>'احصائيات بورصة الاعلاف',
//]);
//
//// detials member
//Route::get('fodder-stock-detials-member/{id}',[
//	'uses'=>'FodderStockFrontController@detialsmember',
//	'as'  =>'front_fodder_detials_member',
//	'type'=>'sub',
//	'kind'=>'detials',
//	'title'=>'تفاصيل احصائيات بورصة الاعلاف',
//]);
//
//// statistics fodder
//Route::post('get-fodder-member',[
//	'uses'=>'FodderStockFrontController@statisticdropmember',
//	'as'  =>'get_fodder_statistic',
//]);