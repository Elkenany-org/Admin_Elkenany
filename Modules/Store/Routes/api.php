<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// paymob
Route::post('/credit', 'api\ApiStoreController@credit')->name('credit');
Route::post('/wallet', 'api\ApiStoreController@wallet')->name('wallet');
Route::get('/callback', 'api\ApiStoreController@callback')->name('callback');
Route::get('customers', 'api\ApiStoreController@customers');

// Route::middleware('auth:api')->get('/store', function (Request $request) {
//     return $request->user();
// });
Route::get('interests','api\ApiStoreController@interests');
Route::post('customer-survey','api\ApiStoreController@customerSurvey')->middleware('CustomerAuth');
Route::post('send-message','api\ApiStoreController@sendMessage');

Route::post('customer/check-login','api\ApiStoreController@check')->middleware('cors');

Route::post('register','api\ApiStoreController@Register');
Route::post('register-email-or-phone','api\ApiStoreController@Register_email_phone');

Route::post('delete','api\ApiStoreController@DeleteCustomer')->middleware('CustomerAuth');

Route::post('register-social','api\ApiStoreController@Registersocial');
Route::post('reg-log-social','api\ApiStoreController@REG_log_social');

Route::post('reg-log-google','api\ApiStoreController@Reg_Log_Google');
Route::post('reg-log-facebook','api\ApiStoreController@Reg_Log_Facebook');

Route::post('login','api\ApiStoreController@login');
Route::post('store-fcm','api\ApiStoreController@storeFcm')->middleware('CustomerAuth');
Route::post('logout','api\ApiStoreController@logout')->middleware('CustomerAuth');


Route::post('login-social','api\ApiStoreController@loginsocial');

Route::post('forget-password','api\ApiStoreController@forget');

Route::post('forget-password-code','api\ApiStoreController@code');

Route::prefix('store')->group(function() {

    # sections
    Route::get('ads-store','api\ApiStoreController@ShowAds');
    Route::get('ads-store-sections','api\ApiStoreController@filterSections');
    Route::get('ads-store-detials','api\ApiStoreController@ShowOneAds');

    

    Route::get('my-ads-store','api\ApiStoreController@MyShowAds')->middleware('CustomerAuth');

    Route::get('ads-store-sections-to-add','api\ApiStoreController@addadssec');

    Route::post('add-ads-store','api\ApiStoreController@Storestoreads')->middleware('CustomerAuth');

    Route::get('get-ads-store-to-edit','api\ApiStoreController@editadssec')->middleware('CustomerAuth');

    
    Route::post('update-ads-store','api\ApiStoreController@updatestoreads')->middleware('CustomerAuth');

    Route::get('delete-ads-store','api\ApiStoreController@Deleteads')->middleware('CustomerAuth');


    
    Route::get('start-chat','api\ApiStoreController@startchat')->middleware('CustomerAuth');

     
    Route::get('chats','api\ApiStoreController@chats')->middleware('CustomerAuth');

    Route::get('chats-massages','api\ApiStoreController@chatsmassage')->middleware('CustomerAuth');


    Route::post('add-massages','api\ApiStoreController@writemassage')->middleware('CustomerAuth');

    Route::get('notifications-ads','api\ApiStoreController@notifications')->middleware('CustomerAuth');

});


# start of ApiStoreController

# end of ApiStoreController