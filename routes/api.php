<?php

use Illuminate\Support\Facades\Route;

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

Route::group(['prefix' => 'image'], function () {
  Route::post('/upload', 'ImageController@upload')->middleware('permission:UPLOAD_IMAGE');
  Route::post('/{id}/update', 'ImageController@update')->middleware('permission:UPLOAD_IMAGE');

  Route::get('/list', 'ImageController@list')->middleware('permission:UPLOAD_IMAGE');
  Route::get('/{id}', 'ImageController@get')->middleware('permission:UPLOAD_IMAGE');

  Route::delete('/{id}', 'ImageController@delete')->middleware('permission:UPLOAD_IMAGE');
});

Route::post('/preferences/dontaskinvite', 'PreferencesController@askToInvite');

Route::group(['prefix' => 'reminders'], function () {
  Route::get('/', 'RemindersController@list');
  Route::post('/set', 'RemindersController@set');
  Route::post('/cancel', 'RemindersController@cancel');
});

Route::group(['prefix' => 'url'], function () {
  Route::post('/create', 'UrlController@create')->middleware('permission:CREATE_SHORT_URL');
  Route::get('/list', 'UrlController@list')->middleware('permission:CREATE_SHORT_URL');
  Route::delete('/{id}', 'UrlController@delete')->middleware('permission:CREATE_SHORT_URL');
});

Route::group(['prefix' => 'admin'], function () {
  Route::group(['prefix' => 'users'], function () {
    Route::get('/', 'UserController@listUsers')->middleware('permission:USER_OVERVIEW');
    Route::get('/{user}', 'UserController@getUserDetails')->middleware('permission:MANAGE_USER');
  });

  Route::group(['prefix' => 'permissions'], function () {
    Route::get('/', 'PermissionController@list')->middleware('permission:MANAGE_USER');
    Route::put('/grant', 'PermissionController@grant')->middleware('permission:MANAGE_USER');
    Route::put('/revoke', 'PermissionController@revoke')->middleware('permission:MANAGE_USER');
  });
});
