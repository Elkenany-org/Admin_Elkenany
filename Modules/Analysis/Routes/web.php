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

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => ['role','auth']], function() {

	#------------------------------- start of DataAnalysisKeywordsController -----------------------------#

	# data analysis keywords
	Route::get('data-analysis-keywords',[
		'uses' =>'DataAnalysisKeywordsController@Index',
		'as'   =>'dataanalysiskeywords',
		'title'=>'إدارة تحليل البيانات',
		'subTitle'=>'الكلمات الدلالية',
		'icon' =>'<i class="fas fa-database"></i>',
		'subIcon' =>'<i class="fas fa-key"></i>',
		'child'=>[
			'storekeyword',
			'keywordstatistics',
			'keywordsstatisticsbydate',
			'currentkeywordstatistics',
			'updatekeyword',
			'deletekeyword',
		]
	]);

	# keywords statistics
	Route::get('keyword-statistics',[
		'uses'=>'DataAnalysisKeywordsController@KeywordsStatistics',
		'as'  =>'keywordstatistics',
		'title'=>'إحصائيات الكلمات الدلالية',
		'icon' =>'<i class="fas fa-chart-bar"></i>',
		'hasFather'=>true
	]);

	# get keywords statistics by date ( ajax )
	Route::post('keywords-statistics-by-date',[
		'uses'=>'DataAnalysisKeywordsController@KeywordsStatisticsByDate',
		'as'  =>'keywordsstatisticsbydate',
		'title'=>' إحصائيات بالتاريخ',
	]);

	# current keyword statistics
	Route::get('current-keyword-statistics/{id}',[
		'uses'=>'DataAnalysisKeywordsController@KeywordStatistics',
		'as'  =>'currentkeywordstatistics',
		'title'=>'إحصائيات كلمة دلالية',
	]);

	# store keyword
	Route::post('store-keyword',[
		'uses'=>'DataAnalysisKeywordsController@Store',
		'as'  =>'storekeyword',
		'title'=>'إضافة كلمة دلالية'
	]);

	# update keyword
	Route::post('update-keyword',[
		'uses'=>'DataAnalysisKeywordsController@Update',
		'as'  =>'updatekeyword',
		'title'=>'تحديث كلمة دلالية'
	]);

	# delete keyword
	Route::post('delete-keyword',[
		'uses'=>'DataAnalysisKeywordsController@Delete',
		'as'  =>'deletekeyword',
		'title'=>'حذف كلمة دلالية'
	]);

	#------------------------------- end of DataAnalysisKeywordsController -----------------------------#

});
