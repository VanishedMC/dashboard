<?php

use Illuminate\Support\Facades\Route;

// Authentication routes
Route::get('/login', 'AuthController@redirect')->middleware('guest')->name('login');
Route::get('/login/discord', 'AuthController@login')->middleware('guest');
Route::get('/logout', 'AuthController@logout')->middleware('auth');

// Image routes
Route::get('/image{image}', 'Api\ImageController@getImage')->middleware('image-access');
Route::get('/i{image}', 'Api\ImageController@getImageView')->middleware('image-access');

// Dashboard routes
Route::get('/', 'DashboardController@view')->middleware('auth')->name('home');
Route::get('/{any}', 'DashboardController@view')->where('any', '.*')->middleware('auth');
