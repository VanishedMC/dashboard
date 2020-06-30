<?php

use Illuminate\Http\Request;
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

Route::group(['prefix' => 'image'], function() {
  Route::post('/upload', 'ImageController@upload');
  Route::post('/{id}/update', 'ImageController@update');
  
  Route::get('/list', 'ImageController@list');
  Route::get('/{id}', 'ImageController@get');
  
  Route::delete('/{id}', 'ImageController@delete');
});

Route::group(['prefix' => 'admin'], function() {
  Route::group(['prefix' => 'users'], function() {
    Route::get('/', 'UserController@listUsers')->middleware('permission:USER_OVERVIEW');
    Route::get('/{user}', 'UserController@getUserDetails')->middleware('permission:MANAGE_USER');
  });

  Route::group(['prefix' => 'permissions'], function() {
    Route::get('/', 'PermissionController@list')->middleware('permission:MANAGE_USER');
    Route::put('/grant', 'PermissionController@grant')->middleware('permission:MANAGE_USER');
    Route::put('/revoke', 'PermissionController@revoke')->middleware('permission:MANAGE_USER');
  });
});

