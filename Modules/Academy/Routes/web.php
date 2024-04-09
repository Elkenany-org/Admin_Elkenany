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


#------------------------------- start of CourcesController -----------------------------#

	# courses
	Route::get('courses',[
		'uses' =>'CourcesController@Index',
		'as'   =>'courses',
		'title'=>'إدارة  الاكاديمية',
		'subTitle'=>' جميع الكورسات',
		'icon' =>'<i class="fas fa-trophy"></i>',
		'subIcon' =>'<i class="fas fa-trophy"></i>',
		'child'=>[
			'Addcourses',
            'Storecourses',
            'Editcourses',
            'Updatecourses',
            'Deletecourses',
            'storelive',
            'updatelive',
            'Deletelive',
            'updatemeeting',
			'storemeeting',
			'storeoffline',
			'updateoffline',
			'Deletevideo',
			'storequizze',
			'Editquizze',
			'updatequizze',
			'Deletequizze',
			'storequestion',
			'Deletequestion',
			'Editquestion',
			'updatequestion',
			'showanswer',
			'Deletecomment',
			'Editlive',
			'Editmeeting',
			'Deletemeeting',
			'Storefolder',
			'Updatefolder',
			'Deletefolder',
			'showfolder',
			'storeovideos',
			'updatevideo',
			'storequestionarticl',
			'updateanswerc',
			'updateanswerf'
		]
	]);

	# add courses
	Route::get('add-courses',[
		'uses'=>'CourcesController@Addcourses',
		'as'  =>'Addcourses',
		'icon' =>'<i class="fas fa-plus"></i>',
		'title'=>'إضافة كورس جديد',
		'hasFather'=>true
	]);

	# store courses
	Route::post('store-courses',[
		'uses'=>'CourcesController@Storecourses',
		'as'  =>'Storecourses',
		'title'=>'حفظ الكورس'
    ]);

    # edit courses
	Route::get('edit-courses/{id}',[
		'uses'=>'CourcesController@Editcourses',
		'as'  =>'Editcourses',
		'title'=>'تعديل الكورس'
	]);
	
	# store folder
	Route::post('store-folder',[
		'uses'=>'CourcesController@Storefolder',
		'as'  =>'Storefolder',
		'title'=>'إضافة المجلد'
	]);

	# update folder
	Route::post('update-folders',[
		'uses'=>'CourcesController@Updatefolder',
		'as'  =>'Updatefolder',
		'title'=>'تحديث المجلد'
	]);

	# delete folder
	Route::post('delete-courses-folder',[
		'uses'=>'CourcesController@Deletefolder',
		'as'  =>'Deletefolder',
		'title'=>'حذف  المجلد'
	]);
	
	# show folder
	Route::get('show-folder/{id}',[
		'uses'=>'CourcesController@showfolder',
		'as'  =>'showfolder',
		'title'=>' بيانات المجلد'
	]);

	# store video
	Route::post('store-offline-video',[
		'uses'=>'CourcesController@storeovideos',
		'as'  =>'storeovideos',
		'title'=>'حفظ الفديو '
	]);
	
	# update video
	Route::post('update-video',[
		'uses'=>'CourcesController@updatevideo',
		'as'  =>'updatevideo',
		'title'=>'تحديث الفديو'
	]);
    
    # store meeting
	Route::post('store-courses-meeting',[
		'uses'=>'CourcesController@storemeeting',
		'as'  =>'storemeeting',
		'title'=>'حفظ مقابلة '
    ]);
    
    # update courses
	Route::post('update-courses',[
		'uses'=>'CourcesController@Updatecourses',
		'as'  =>'Updatecourses',
		'title'=>'تحديث الكورس'
	]);

	# delete courses
	Route::post('delete-courses',[
		'uses'=>'CourcesController@Deletecourses',
		'as'  =>'Deletecourses',
		'title'=>'حذف الكورس'
    ]);

    # store live
	Route::post('store-courses-live',[
		'uses'=>'CourcesController@storelive',
		'as'  =>'storelive',
		'title'=>'حفظ لايف للكورس'
	]);
	
	# edit live
	Route::get('edit-courses-live/{id}',[
		'uses'=>'CourcesController@Editlive',
		'as'  =>'Editlive',
		'title'=>'تعديل اللايف'
    ]);

    # update live
	Route::post('update-courses-live',[
		'uses'=>'CourcesController@updatelive',
		'as'  =>'updatelive',
		'title'=>'تحديث الكورس اللايف'
    ]);
    
    # delete live
	Route::post('delete-courses-live',[
		'uses'=>'CourcesController@Deletelive',
		'as'  =>'Deletelive',
		'title'=>'حذف اللايف للكورس'
    ]);

	# edit meeting
	Route::get('edit-courses-meeting/{id}',[
		'uses'=>'CourcesController@Editmeeting',
		'as'  =>'Editmeeting',
		'title'=>'تعديل اللايف'
	]);

	# delete Meeting
	Route::post('delete-courses-Meeting',[
		'uses'=>'CourcesController@Deletemeeting',
		'as'  =>'Deletemeeting',
		'title'=>'حذف مقابلة للكورس'
    ]);
	
    # update Meeting
	Route::post('update-courses-Meeting',[
		'uses'=>'CourcesController@updatemeeting',
		'as'  =>'updatemeeting',
		'title'=>'تحديث  مقابلة'
	]);
	
	# store offline
	Route::post('store-courses-offline',[
		'uses'=>'CourcesController@storeoffline',
		'as'  =>'storeoffline',
		'title'=>'حفظ فديوهات '
    ]);

    # update offline
	Route::post('update-courses-offline',[
		'uses'=>'CourcesController@updateoffline',
		'as'  =>'updateoffline',
		'title'=>'تحديث فديوهات  '
    ]);
    
	# delete video
	Route::post('delete-video-courses',[
		'uses'=>'CourcesController@Deletevideo',
		'as'  =>'Deletevideo',
		'title'=>'حذف فديو'
	]);

	# delete comment
	Route::post('delete-comment-courses',[
		'uses'=>'CourcesController@Deletecomment',
		'as'  =>'Deletecomment',
		'title'=>'حذف تعليق'
	]);

	# store quizze
	Route::post('store-courses-quizze',[
		'uses'=>'QuizzesController@storequizze',
		'as'  =>'storequizze',
		'title'=>'حفظ اختبار '
    ]);

	# edit quizze
	Route::get('edit-quizze-questions/{id}',[
		'uses'=>'QuizzesController@Editquizze',
		'as'  =>'Editquizze',
		'title'=>'اسئلة الاختبار'
	]);
	
	# update quizze
	Route::post('update-quizze',[
		'uses'=>'QuizzesController@updatequizze',
		'as'  =>'updatequizze',
		'title'=>'تحديث الاختبار'
	]);

	# delete quizze
	Route::post('delete-quizze',[
		'uses'=>'QuizzesController@Deletequizze',
		'as'  =>'Deletequizze',
		'title'=>'حذف الاختبار'
	]);
	
	# store question
	Route::post('store-courses-quizze-question',[
		'uses'=>'QuizzesController@storequestion',
		'as'  =>'storequestion',
		'title'=>'حفظ السؤال '
	]);

	# store question articl
	Route::post('store-courses-quizze-question-articl',[
		'uses'=>'QuizzesController@storequestionarticl',
		'as'  =>'storequestionarticl',
		'title'=>'حفظ السؤال النظري '
	]);
	
	# delete question
	Route::post('delete-question',[
		'uses'=>'QuizzesController@Deletequestion',
		'as'  =>'Deletequestion',
		'title'=>'حذف السؤال'
	]);

	# edit question
	Route::get('edit-question/{id}',[
		'uses'=>'QuizzesController@Editquestion',
		'as'  =>'Editquestion',
		'title'=>'تعديل السؤال'
	]);
	
	# update question
	Route::post('update-courses-question',[
		'uses'=>'QuizzesController@updatequestion',
		'as'  =>'updatequestion',
		'title'=>'تحديث  السؤال'
	]);
	
	# show answer
	Route::get('show-answer/{id}',[
		'uses'=>'QuizzesController@showanswer',
		'as'  =>'showanswer',
		'title'=>' اجابات الاختبار'
	]);
	
	# update curect answer
	Route::post('update-curect-answer',[
		'uses'=>'QuizzesController@updateanswerc',
		'as'  =>'updateanswerc',
		'title'=>'تحديث الاجابة الصحيحة'
	]);

	# update folse
	Route::post('update-folse-answer',[
		'uses'=>'QuizzesController@updateanswerf',
		'as'  =>'updateanswerf',
		'title'=>'تحديث الاجابةالخاطئة'
	]);

	#------------------------------- end of CourcesController -----------------------------#
});

//
//// courses
Route::get('academy-courses', 'AcademyfrontController@Index')->name('front_academy_courses');
//
//// courses live
//Route::get('academy-course-live/{id}', 'AcademyfrontController@live')->name('front_live_courses');
//
//
//
//// courses going
//Route::post('academy-courses-going-live', 'AcademyfrontController@goinglive')->name('going_courses_live');
//
//// courses live
//Route::get('all-courses-live', 'AcademyfrontController@lives')->name('front_all_live');
//
//
//// courses offline
//Route::get('academy-course-offline/{id}', 'AcademyfrontController@offline')->name('front_offline_courses');
//
//
//
//// courses going
//Route::post('academy-courses-going-offline', 'AcademyfrontController@goingoffline')->name('going_courses_offline');
//
//// courses offline
//Route::get('all-courses-offline', 'AcademyfrontController@offlines')->name('front_all_offline');
//
//
//
//// courses online
//Route::get('academy-course-online/{id}', 'AcademyfrontController@online')->name('front_online_courses');
//
//
//
//// courses going
//Route::post('academy-courses-going-online', 'AcademyfrontController@goingonline')->name('going_courses_online');
//
//// courses online
//Route::get('all-courses-online', 'AcademyfrontController@onlines')->name('front_all_online');
//
//// courses watch
//Route::post('academy-courses-watch-online', 'AcademyfrontController@watch')->name('watch_courses_online');
//
//
//// my courses
//Route::get('my-courses', 'AcademyfrontController@mycourses')->name('front_my_courses');
//
//// exams
//Route::get('exams-courses', 'AcademyfrontController@exams')->name('front_exams_courses');
//
//
//
//// exam
//Route::get('academy-course-exam/{id}', 'AcademyfrontController@exam')->name('front_exam_courses');
//
//// exam add
//Route::post('academy-courses-exam-answer', 'AcademyfrontController@quize')->name('exam_courses_answer');
//
//
//
//// archive
//Route::get('academy-course-exam-archive/{id}', 'AcademyfrontController@archive')->name('front_exam_archive_courses');
//
//// my certificates
//Route::get('my-certificates', 'AcademyfrontController@certificates')->name('front_my_certificates');
//
//// my certificate
//Route::get('my-certificate/{id}', 'AcademyfrontController@certificate')->name('front_my_certificate');
//
//
//
//// courses comment
//Route::post('academy-courses-add-comment', 'AcademyfrontController@comment')->name('going_courses_add_comment');
//
//// courses replay
//Route::post('academy-courses-add-replay', 'AcademyfrontController@replay')->name('going_courses_add_replay');