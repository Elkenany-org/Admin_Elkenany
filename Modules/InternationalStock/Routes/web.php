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

Route::prefix('internationalstock')->group(function() {
    Route::get('/', 'InternationalStockController@index');
});

Route::group(['middleware' => ['role','auth']], function() {
	

	#------------------------------- start of InternationalStockController -----------------------------#

	# sections
	Route::get('ports-ships',[
		'uses' =>'InternationalStockController@Index',
		'as'   =>'ports',
		'title'=>'  البورصة العالمية',
		'subTitle'=>'المواني',
		'icon' =>' <i class="fas fa-ship"></i> ',
		'subIcon' =>' <i class="fas fa-ship"></i> ',
		'child' =>[
			'storeports',
			'updateports',
			'deleteports',
			'Deleteproductships',
			'Updateproductships',
			'Storeproductships',
			'productsships',
			'ships',
			'addships',
			'Storeships',
			'Editships',
			'Updateships',
			'Deleteships',
			'Searchcompanyships',
			'latestnews',
			'Addlatestnews',
			'Storelatestnews',
			'Editlatestnews',
			'Updatelatestnews',
			'Deletelatestnews',
			'analysistec',
			'Addanalysistec',
			'Storeanalysistec',
			'Editanalysistec',
			'Updateanalysistec',
			'Deleteanalysistec',
			'interreports',
			'Addinterreports',
			'Storeinterreports',
			'Editinterreports',
			'Updateinterreports',
			'Deleteinterreports',
			'bodcast',
			'Addbodcast',
			'Storebodcast',
			'Editbodcast',
			'Updatebodcast',
			'Deletebodcast',
			'tv',
			'Addtv',
			'Storetv',
			'Edittv',
			'Updatetv',
			'Deletetv'



			
		]

	]);


	# store ports
	Route::post('store-ports-ships',[
		'uses'=>'InternationalStockController@Store',
		'as'  =>'storeports',
		'title'=>'إضافة ميناء'
	]);

	# update ports
	Route::post('update-ports-ships',[
		'uses'=>'InternationalStockController@Update',
		'as'  =>'updateports',
		'title'=>'تحديث ميناء'
	]);

	# delete ports
	Route::post('delete-ports-ships',[
		'uses'=>'InternationalStockController@Delete',
		'as'  =>'deleteports',
		'title'=>'حذف ميناء'
	]);

	# sub products
	Route::get('products-ships',[
		'uses'=>'InternationalStockController@products',
		'as'  =>'productsships',
		'title'=>' منتجات السفن',
		'icon' =>'<i class="fas fa-seedling"></i>',
		'hasFather'=>true
	]);
	
	# store products
	Route::post('store-products-ships',[
		'uses'=>'InternationalStockController@Storeproduct',
		'as'  =>'Storeproductships',
		'title'=>'إضافة منتج'
	]);

	# update products
	Route::post('update-products-ships',[
		'uses'=>'InternationalStockController@Updateproduct',
		'as'  =>'Updateproductships',
		'title'=>'تحديث منتج'
	]);

	# delete products
	Route::post('delete-products-ships',[
		'uses'=>'InternationalStockController@Deleteproduct',
		'as'  =>'Deleteproductships',
		'title'=>'حذف منتج'
	]);
	
	# ships
	Route::get('ships',[
		'uses'=>'ShipsController@index',
		'as'  =>'ships',
		'title'=>' حركة السفن',
		'icon' =>'<i class="fas fa-ship"></i>',
		'hasFather'=>true
	]);

	# add ships
	Route::get('add-ships',[
		'uses'=>'ShipsController@addships',
		'as'  =>'addships',
		'title'=>' اضافة حركة السفن',
	]);

	# get companies search (ajax)
	Route::post('get-companies-search-ship',[
		'uses'=>'ShipsController@Searchcompany',
		'as'  =>'Searchcompanyships',
		'title'=>'جلب  بحث الشركات'
	]);


	# store ships
	Route::post('store-ships',[
		'uses'=>'ShipsController@Storeships',
		'as'  =>'Storeships',
		'title'=>' حفظ حركة السفن',
	]);

	# edit ships
	Route::get('edit-ships/{id}',[
		'uses'=>'ShipsController@Editships',
		'as'  =>'Editships',
		'title'=>' تعديل حركة السفن',
	]);

	# update ships
	Route::post('update-ships',[
		'uses'=>'ShipsController@Updateships',
		'as'  =>'Updateships',
		'title'=>' تحديث حركة السفن',
	]);

	# delete ships
	Route::post('delete-ships',[
		'uses'=>'ShipsController@Deleteships',
		'as'  =>'Deleteships',
		'title'=>' حذف حركة السفن',
	]);

	
    #news
	Route::get('latest-news',[
		'uses'=>'LatestNewsController@Index',
		'as'  =>'latestnews',
		'title'=>' الاخبار',
		'icon' =>'<i class="fas fa-newspaper"></i>',
		'hasFather'=>true
	]);

	# add news
	Route::get('add-latest-news',[
		'uses'=>'LatestNewsController@Addnews',
		'as'  =>'Addlatestnews',
		'title'=>'إضافة  خبر جديد',

	]);

	# store news
	Route::post('store-latest-news',[
		'uses'=>'LatestNewsController@Storenews',
		'as'  =>'Storelatestnews',
		'title'=>'حفظ الخبر'
	]);

	# edit news
	Route::get('edit-latest-news/{id}',[
		'uses'=>'LatestNewsController@Editnews',
		'as'  =>'Editlatestnews',
		'title'=>'تعديل الخبر'
	]);

	# update news
	Route::post('update-latest-news',[
		'uses'=>'LatestNewsController@Updatenews',
		'as'  =>'Updatelatestnews',
		'title'=>'تحديث الخبر'
	]);

	# delete news
	Route::post('delete-latest-news',[
		'uses'=>'LatestNewsController@Deletenews',
		'as'  =>'Deletelatestnews',
		'title'=>'حذف الخبر'
	]);
	

	#analysis
	Route::get('technical-analysis',[
		'uses'=>'TecAnalysisController@Index',
		'as'  =>'analysistec',
		'title'=>' التحليل الفني',
		'icon' =>'<i class="fas fa-database"></i>',
		'hasFather'=>true
	]);

	# add analysis
	Route::get('add-technical-analysis',[
		'uses'=>'TecAnalysisController@Addnews',
		'as'  =>'Addanalysistec',
		'title'=>'إضافة  تحليل جديد',

	]);

	# store analysis
	Route::post('store-technical-analysis',[
		'uses'=>'TecAnalysisController@Storenews',
		'as'  =>'Storeanalysistec',
		'title'=>'حفظ التحليل الفني'
	]);

	# edit analysis
	Route::get('edit-technical-analysis/{id}',[
		'uses'=>'TecAnalysisController@Editnews',
		'as'  =>'Editanalysistec',
		'title'=>'تعديل التحليل الفني'
	]);

	# update analysis
	Route::post('update-technical-analysis',[
		'uses'=>'TecAnalysisController@Updatenews',
		'as'  =>'Updateanalysistec',
		'title'=>'تحديث التحليل الفني'
	]);

	# delete analysis
	Route::post('delete-technical-analysis',[
		'uses'=>'TecAnalysisController@Deletenews',
		'as'  =>'Deleteanalysistec',
		'title'=>'حذف التحلل الفني'
	]);
	

	#reports
	Route::get('international-reports',[
		'uses'=>'InterRepController@Index',
		'as'  =>'interreports',
		'title'=>'  التقرير الاسبوعي',
		'icon' =>'<i class="fas fa-clipboard"></i>',
		'hasFather'=>true
	]);

	# add reports
	Route::get('add-international-reports',[
		'uses'=>'InterRepController@Addnews',
		'as'  =>'Addinterreports',
		'title'=>'إضافة  تقرير جديد',

	]);

	# store reports
	Route::post('store-international-reports',[
		'uses'=>'InterRepController@Storenews',
		'as'  =>'Storeinterreports',
		'title'=>'حفظ تقرير '
	]);

	# edit reports
	Route::get('edit-international-reports/{id}',[
		'uses'=>'InterRepController@Editnews',
		'as'  =>'Editinterreports',
		'title'=>'تعديل تقرير '
	]);

	# update reports
	Route::post('update-international-reports',[
		'uses'=>'InterRepController@Updatenews',
		'as'  =>'Updateinterreports',
		'title'=>'تحديث تقرير'
	]);

	# delete reports
	Route::post('delete-international-reports',[
		'uses'=>'InterRepController@Deletenews',
		'as'  =>'Deleteinterreports',
		'title'=>'حذف تقرير '
	]);

	#bodcast
	Route::get('bodcast',[
		'uses'=>'bodcastController@Index',
		'as'  =>'bodcast',
		'title'=>'  بودكاست الكناني',
		'icon' =>'<i class="fas fa-volume-up"></i>',
		'hasFather'=>true
	]);

	# add bodcast
	Route::get('add-bodcast',[
		'uses'=>'bodcastController@Addnews',
		'as'  =>'Addbodcast',
		'title'=>'إضافة  بودكاست جديد',

	]);

	# store bodcast
	Route::post('store-bodcast',[
		'uses'=>'bodcastController@Storenews',
		'as'  =>'Storebodcast',
		'title'=>'حفظ بودكاست '
	]);

	# edit bodcast
	Route::get('edit-bodcast/{id}',[
		'uses'=>'bodcastController@Editnews',
		'as'  =>'Editbodcast',
		'title'=>'تعديل بودكاست '
	]);

	# update bodcast
	Route::post('update-bodcast',[
		'uses'=>'bodcastController@Updatenews',
		'as'  =>'Updatebodcast',
		'title'=>'تحديث بودكاست'
	]);

	# delete bodcast
	Route::post('delete-bodcast',[
		'uses'=>'bodcastController@Deletenews',
		'as'  =>'Deletebodcast',
		'title'=>'حذف بودكاست '
	]);

	#tv
	Route::get('tv',[
		'uses'=>'TvController@Index',
		'as'  =>'tv',
		'title'=>'  الكنانيtv ',
		'icon' =>'<i class="fas fa-tv"></i>',
		'hasFather'=>true
	]);

	# add tv
	Route::get('add-tv',[
		'uses'=>'TvController@Addnews',
		'as'  =>'Addtv',
		'title'=>'إضافة  tv جديد',

	]);

	# store tv
	Route::post('store-tv',[
		'uses'=>'TvController@Storenews',
		'as'  =>'Storetv',
		'title'=>'حفظ tv '
	]);

	# edit tv
	Route::get('edit-tv/{id}',[
		'uses'=>'TvController@Editnews',
		'as'  =>'Edittv',
		'title'=>'تعديل tv '
	]);

	# update tv
	Route::post('update-tv',[
		'uses'=>'TvController@Updatenews',
		'as'  =>'Updatetv',
		'title'=>'تحديث tv'
	]);

	# delete tv
	Route::post('delete-tv',[
		'uses'=>'TvController@Deletenews',
		'as'  =>'Deletetv',
		'title'=>'حذف tv '
	]);
	
	
	


});
#---------------------------------end of dashboard-------------------------------------------

// ships
//
//Route::get('all-ships-traffic',[
//	'uses'=>'ShipsfrontController@index',
//	'as'  =>'front_ships',
//	'type'=>'main',
//	'kind'=>'ships',
//	'title'=>'السفن',
//]);
//
//// ships
//Route::get('all-ships-traffic-statistc',[
//	'uses'=>'ShipsfrontController@statistic',
//	'as'  =>'front_ships_statistc',
//	'type'=>'sub',
//	'kind'=>'shipsstatistc',
//	'title'=>'احصائيات السفن',
//]);
//
//// ships
//Route::get('all-ships-traffic-statistc-kind/{id}',[
//	'uses'=>'ShipsfrontController@detials',
//	'as'  =>'front_ships_statistc_kind',
//	'type'=>'sub',
//	'kind'=>'shipsstatistcdetials',
//	'title'=>'تفاصيل احصائيات السفن',
//]);
//
//
//// news
//Route::get('all-latest-news',[
//	'uses'=>'LatestNewsFrontController@news',
//	'as'  =>'front_section_latest_news',
//	'type'=>'main',
//	'kind'=>'internationalstocknews',
//	'title'=>'الرئيسية للاخبار ',
//]);
//
//// news sort
//Route::get('latest-news-view',[
//	'uses'=>'LatestNewsFrontController@newssort',
//	'as'  =>'front_section_news_latest_view',
//	'type'=>'main',
//	'kind'=>'internationalstocknewsview',
//	'title'=>'الرئيسية للاخبار حسب التداول',
//]);
//
//// news sort
//Route::get('latest-one-news/{id}',[
//	'uses'=>'LatestNewsFrontController@onenews',
//	'as'  =>'front_latest_one_news',
//	'type'=>'sub',
//	'kind'=>'internationalstocknewsone',
//	'title'=>'الخبر',
//]);
//
//
//// news
//Route::get('all-technical-analysis',[
//	'uses'=>'TecAnalysisFrontController@news',
//	'as'  =>'front_analysistec',
//	'type'=>'main',
//	'kind'=>'internationalstockanalysis',
//	'title'=>'الرئيسية للتحليل الفني ',
//]);
//
//// news sort
//Route::get('technical-analysis-view',[
//	'uses'=>'TecAnalysisFrontController@newssort',
//	'as'  =>'front_analysistec_view',
//	'type'=>'main',
//	'kind'=>'internationalstockanalysisview',
//	'title'=>'الرئيسية للتحليل الفني حسب التداول',
//]);
//
//// news sort
//Route::get('technical-analysis-det/{id}',[
//	'uses'=>'TecAnalysisFrontController@onenews',
//	'as'  =>'front_one_analysistec',
//	'type'=>'sub',
//	'kind'=>'internationalstockanalysisone',
//	'title'=>'التحليل الفني',
//]);
//
//
//
//
//// bodcast
//Route::get('all-bodcast',[
//	'uses'=>'bodcastFrontController@news',
//	'as'  =>'front_bodcast',
//	'type'=>'main',
//	'kind'=>'internationalstockbodcast',
//	'title'=>'الرئيسية  للبودكاست ',
//]);
//
//
//Route::get('bodcast-view',[
//	'uses'=>'bodcastFrontController@newssort',
//	'as'  =>'front_bodcast_view',
//	'type'=>'main',
//	'kind'=>'internationalstockbodcastview',
//	'title'=>'الرئيسية  للبودكاست حسب التداول',
//]);
//
//// news sort
//Route::get('bodcast-det/{id}',[
//	'uses'=>'bodcastFrontController@onenews',
//	'as'  =>'front_one_bodcast',
//	'type'=>'sub',
//	'kind'=>'internationalstockanbodcastone',
//	'title'=>' البودكاست',
//]);
//
//
//// reports
//Route::get('all-reports-inter',[
//	'uses'=>'InterRepFrontController@news',
//	'as'  =>'front_reports',
//	'type'=>'main',
//	'kind'=>'internationalstockreports',
//	'title'=>'الرئيسية  للتقارير ',
//]);
//
//
//Route::get('reports-view-inter',[
//	'uses'=>'InterRepFrontController@newssort',
//	'as'  =>'front_reports_view',
//	'type'=>'main',
//	'kind'=>'internationalstockreportsview',
//	'title'=>'الرئيسية  للتقارير حسب التداول',
//]);
//
//// reports sort
//Route::get('reports-det-inter/{id}',[
//	'uses'=>'InterRepFrontController@onenews',
//	'as'  =>'front_one_reports',
//	'type'=>'sub',
//	'kind'=>'internationalstockanreportsone',
//	'title'=>' التقارير',
//]);
//
//// tv
//Route::get('all-tv-elkennany',[
//	'uses'=>'tvFrontController@tvs',
//	'as'  =>'front_tvs',
//	'type'=>'main',
//	'kind'=>'internationalstocktvs',
//	'title'=>'الرئيسية  لتلفزيون الكناني ',
//]);
//


