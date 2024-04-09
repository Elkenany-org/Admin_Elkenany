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

 #------------------------------- start of NewsController -----------------------------#

	# news
	Route::get('news-sections',[
		'uses' =>'NewsSectionsController@Index',
		'as'   =>'newssections',
		'title'=>'إدارة  الاخبار',
		'subTitle'=>'  الاقسام',
		'icon' =>'<i class="fas fa-newspaper"></i>',
		'subIcon' =>'<i class="fas fa-building"></i>',
		'child'=>[
			'storenewssections',
			'updatenewssection',
			'deletenewssection',
			'news',
			'Addnews',
			'Storenews',
			'Deletenews',
			'Editnews',
            'Updatenews',
            'storenewsImages',
			'DeletenewsImage',
			'Addnewsm',
			'Storenewsm',
			'Updatenewsm',
            'selectnewssection'
		]
	]);


	# store section
	Route::post('store-news-sections',[
		'uses'=>'NewsSectionsController@Store',
		'as'  =>'storenewssections',
		'title'=>'إضافة قسم'
	]);

	# update section
	Route::post('update-news-sections',[
		'uses'=>'NewsSectionsController@Update',
		'as'  =>'updatenewssection',
		'title'=>'تحديث قسم'
	]);

    	# update section
	Route::post('select-news-sections',[
        'uses'=>'NewsSectionsController@select',
        'as'  =>'selectnewssection',
        'title'=>'تحديث قسم'
    ]);

	# delete section
	Route::post('delete-news-sections',[
		'uses'=>'NewsSectionsController@Delete',
		'as'  =>'deletenewssection',
		'title'=>'حذف قسم'
	]);
	
    #news
	Route::get('news',[
		'uses'=>'NewsController@Index',
		'as'  =>'news',
		'title'=>' الاخبار',
		'icon' =>'<i class="fas fa-newspaper"></i>',
		'hasFather'=>true
	]);

	# add news
//	Route::get('news/create',[
//		'uses'=>'NewsController@create',
//		'as'  =>'Addnews',
//		'icon' =>'<i class="fas fa-plus"></i>',
//		'title'=>'إضافة  خبر جديد',
//		'hasFather'=>true
//	]);

    # add news
    Route::get('add-news',[
        'uses'=>'NewsController@Addnews',
        'as'  =>'Addnews',
        'icon' =>'<i class="fas fa-plus"></i>',
        'title'=>'إضافة  خبر جديد',
        'hasFather'=>true
    ]);

	# add news multi
	Route::get('add-news-multi',[
		'uses'=>'NewsController@Addnewsm',
		'as'  =>'Addnewsm',
		'icon' =>'<i class="fas fa-plus"></i>',
		'title'=>'إضافة خبر  حكومي',
		'hasFather'=>true
	]);

	# store news
	Route::post('store-news',[
		'uses'=>'NewsController@Storenews',
		'as'  =>'Storenews',
		'title'=>'حفظ الخبر'
	]);

	# store news
	Route::post('store-news-multi',[
		'uses'=>'NewsController@Storenewsm',
		'as'  =>'Storenewsm',
		'title'=>'حفظ خبر  حكومي'
	]);

	# edit news
	Route::get('edit-news/{id}',[
		'uses'=>'NewsController@Editnews',
		'as'  =>'Editnews',
		'title'=>'تعديل الخبر'
	]);

	# update news
	Route::post('update-news',[
		'uses'=>'NewsController@Updatenews',
		'as'  =>'Updatenews',
		'title'=>'تحديث الخبر'
	]);

	# update news
	Route::post('update-news-multi',[
		'uses'=>'NewsController@Updatenewsm',
		'as'  =>'Updatenewsm',
		'title'=>'تحديث خبر حكومي'
	]);

	# delete news
	Route::post('delete-news',[
		'uses'=>'NewsController@Deletenews',
		'as'  =>'Deletenews',
		'title'=>'حذف الخبر'
    ]);

    # news images
	Route::post('images-news-Store',[
		'uses'=>'NewsController@storenewsImages',
		'as'  =>'storenewsImages',
		'title'=>'اضافة صور اخبار'
	]);

	# delete news image
	Route::post('delete-image-news',[
		'uses'=>'NewsController@DeletenewsImage',
		'as'  =>'DeletenewsImage',
		'title'=>'حذف صور اخبار'
	]);

	#------------------------------- end of NewsController -----------------------------#
});
//
//// news
Route::get('news-section/{name}',[
	'uses'=>'NewsFrontController@news',
	'as'  =>'front_section_news',
	'type'=>'main',
	'kind'=>'news',
	'title'=>'الرئيسية للاخبار ',
]);
//
//// news sort
//Route::get('news-section-view/{name}',[
//	'uses'=>'NewsFrontController@newssort',
//	'as'  =>'front_section_news_view',
//	'type'=>'main',
//	'kind'=>'newsview',
//	'title'=>'الرئيسية للاخبار حسب التداول',
//]);
//
//// news sort
Route::get('news-section-last/{name}',[
	'uses'=>'NewsFrontController@newslast',
	'as'  =>'front_section_news_last',
	'type'=>'main',
	'kind'=>'newslast',
	'title'=>'الرئيسية للاخبار حسب الاحدث',
]);
//
//// sections search
//Route::post('get-section-news-search', 'NewsFrontController@datas')->name('front_section_datas_news');
//
//
//// sections search
//Route::post('get-section-news-search-more', 'NewsFrontController@mores')->name('front_section_datas_news_more');
//
//
//// news sort
//Route::get('one-news/{id}',[
//	'uses'=>'NewsFrontController@onenews',
//	'as'  =>'front_one_news',
//	'type'=>'sub',
//	'kind'=>'newsone',
//	'title'=>'الخبر',
//]);