<?php

use Illuminate\Support\Facades\Route;

// Authentication routes
Route::get('/login', 'AuthController@redirect')->middleware('guest')->name('login');
Route::get('/login/discord', 'AuthController@login')->middleware('guest');
Route::get('/logout', 'AuthController@logout')->middleware('auth');

// Dashboard routes
Route::get('/', 'DashboardController@view')->middleware('auth')->name('home');
Route::get('/images', 'DashboardController@view')->middleware('auth');
Route::get('/urls', 'DashboardController@view')->middleware('auth');

// Image routes
Route::get('/image{image}', 'Api\ImageController@getByUid')->middleware('image-access');
Route::get('/i{image}', 'Api\ImageController@getImageView')->middleware('image-access');

// Short url croutes

Route::get('/u{url}', 'Api\UrlController@redirect');

// Youtube routes
Route::group(['prefix' => 'youtube'], function () {
  Route::get('/information', 'YoutubeController@getVideoInformation')->middleware('permission:YOUTUBE');
  Route::get('/download', 'YoutubeController@getDownload')->middleware('permission:YOUTUBE');
  Route::post('/download', 'YoutubeController@postDownload')->middleware('permission:YOUTUBE');
  Route::post('/reset', 'YoutubeController@reset')->middleware('permission:YOUTUBE');
  Route::post('/information', 'YoutubeController@postVideoInformation')->middleware('permission:YOUTUBE');
});

// Catch-all route
Route::get('/{any}', 'DashboardController@view')->where('any', '.*')->middleware('auth');
