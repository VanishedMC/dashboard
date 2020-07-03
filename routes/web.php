<?php

use Illuminate\Support\Facades\Route;

// Authentication routes
Route::get('/login', 'AuthController@redirect')->middleware('guest')->name('login');
Route::get('/login/discord', 'AuthController@login')->middleware('guest');
Route::get('/logout', 'AuthController@logout')->middleware('auth');

// Dashboard routes
Route::get('/', 'DashboardController@view')->middleware('auth')->name('home');
Route::get('/images', 'DashboardController@view')->middleware('auth');

// Image routes
Route::get('/image{image}', 'Api\ImageController@getByUid')->middleware('image-access');
Route::get('/i{image}', 'Api\ImageController@getImageView')->middleware('image-access');

// Youtube routes
Route::get('/youtube/information', 'YoutubeController@getVideoInformation')->middleware('permission:YOUTUBE');
Route::post('/youtube/information', 'YoutubeController@postVideoInformation')->middleware('permission:YOUTUBE');
Route::get('/youtube/download', 'YoutubeController@getDownload')->middleware('permission:YOUTUBE');
Route::post('/youtube/download', 'YoutubeController@postDownload')->middleware('permission:YOUTUBE');
Route::post('/youtube/reset', 'YoutubeController@reset')->middleware('permission:YOUTUBE');

// Catch-all route
Route::get('/{any}', 'DashboardController@view')->where('any', '.*')->middleware('auth');
