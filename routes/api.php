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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('usessrs', function() {
    return 'dd';
});

Route::group(['domain' => 'api.acme.co'], function () {
  Route::get('/apps', 'AppController@listApps')
    ->name('apps.list');
  Route::get('/apps/{id}', 'AppController@getApp')
    ->name('apps.get');
  Route::post('/apps', 'AppController@createApp')
    ->name('apps.create');
  Route::get('/users', 'UserController@listUsers')
    ->name('users.list');
  Route::get('/users/{id}', 'UserController@getUser')
    ->name('users.get');
});