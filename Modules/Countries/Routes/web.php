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

Route::prefix('countries')->group(function() {
    Route::get('/', 'CountriesController@index');
});
Route::group(['middleware' => ['role','auth']], function() {

	#------------------------------- start of countriesController -----------------------------#

	# sections
	Route::get('countries',[
		'uses' =>'CountriesController@Index',
		'as'   =>'countries',
		'title'=>'إدارة الدول',
		'subTitle'=>'الدول',
		'icon' =>' <i class="fas fa-map-marked-alt"></i> ',
		'subIcon' =>' <i class="fas fa-map-marked-alt"></i> ',
		'child' =>[
			'storecountries',
			'updatecountries',
            'deletecountries',

			
		]

	]);


	# store countries
	Route::post('store-countries',[
		'uses'=>'CountriesController@Store',
		'as'  =>'storecountries',
		'title'=>'إضافة دولة'
	]);

	# update countries
	Route::post('update-countries',[
		'uses'=>'CountriesController@Update',
		'as'  =>'updatecountries',
		'title'=>'تحديث دولة'
	]);

	# delete countries
	Route::post('delete-countries',[
		'uses'=>'CountriesController@Delete',
		'as'  =>'deletecountries',
		'title'=>'حذف دولة'
    ]);

});
