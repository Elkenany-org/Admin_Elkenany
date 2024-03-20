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

Route::prefix('notification')->middleware(['role','auth'])->group(function() {
//    Route::get('/', 'NotificationController@index');


    Route::get('Notification',[
        'uses' =>'NotificationController@index',
        'as'   =>'notification',
        'title'=>'إدارة الإشعارات',
        'subTitle'=>'الإشعارات المجدوله',
        'icon' =>' <i class="fas fa-bell"></i> ',
        'subIcon' =>' <i class="fas fa-calendar"></i>',
        'child' =>[
            'index_not_scheduled',
            'storenotification',
            'create_notification',
            'editnotification',
            'delete_notification',
            'delete_notification_notScheduled'


        ]

    ]);

    Route::get('Notifications',[
        'uses'=>'NotificationController@index_not_scheduled',
        'as'  =>'index_not_scheduled',
        'title'=>'الإشعارات الغير مجدوله',
        'icon' =>'<i class="fas fa-calendar-times"></i>',
        'hasFather' => true
    ]);

    Route::post('store-notification',[
        'uses'=>'NotificationController@store',
        'as'  =>'storenotification',
        'title'=>'حفظ إشعار',
    ]);

    Route::get('create-notification',[
        'uses'=>'NotificationController@create',
        'as'  =>'create_notification',
        'icon' =>'<i class="fas fa-plus"></i>',
        'title'=>'إضافة إشعار',
        'hasFather' => true
    ]);

    Route::post('edit-notification/{id}',[
        'uses'=>'NotificationController@update',
        'as'  =>'editnotification',
        'title'=>'تعديل إشعار',
    ]);

    Route::get('delete-notification/{id}',[
        'uses'=>'NotificationController@destroy',
        'as'  =>'delete_notification',
        'title'=>'حذف إشعار',
    ]);
    Route::get('delete-notification-not-scheduled/{id}',[
        'uses'=>'NotificationController@delete_notification_notScheduled',
        'as'  =>'delete_notification_notScheduled',
        'title'=>'حذف إشعار غير مجدول',
    ]);
});
Route::get('search-company','NotificationController@searchCompany')->name('search-company');
