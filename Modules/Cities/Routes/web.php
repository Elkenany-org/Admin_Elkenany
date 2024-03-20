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

Route::prefix('cities')->group(function() {
    Route::get('/', 'CitiesController@index');
});

Route::group(['middleware' => ['role','auth']], function() {

	#------------------------------- start of CitiesController -----------------------------#

	# sections
	Route::get('cities',[
		'uses' =>'CitiesController@Index',
		'as'   =>'cities',
		'title'=>'إدارة المحافظات',
		'subTitle'=>'المحافظات',
		'icon' =>' <i class="fas fa-map-marked-alt"></i> ',
		'subIcon' =>' <i class="fas fa-map-marked-alt"></i> ',
		'child' =>[
			'storecities',
			'updatecities',
            'deletecities',

			
		]

	]);


	# store cities
	Route::post('store-cities',[
		'uses'=>'CitiesController@Store',
		'as'  =>'storecities',
		'title'=>'إضافة محافظة'
	]);

	# update cities
	Route::post('update-cities',[
		'uses'=>'CitiesController@Update',
		'as'  =>'updatecities',
		'title'=>'تحديث محافظة'
	]);

	# delete cities
	Route::post('delete-cities',[
		'uses'=>'CitiesController@Delete',
		'as'  =>'deletecities',
		'title'=>'حذف محافظة'
    ]);

});
