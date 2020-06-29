<?php

use Illuminate\Support\Facades\Route;

Route::get('/login', 'AuthController@redirect')->middleware('guest')->name('login');
Route::get('/login/discord', 'AuthController@login')->middleware('guest');
Route::get('/logout', 'AuthController@logout')->middleware('auth');

Route::get('/', 'DashboardController@view')->middleware('auth')->name('home');

Route::get('/i{image}', 'Api\ImageController@getImage');

Route::get('/{any}', 'DashboardController@view')->where('any', '.*')->middleware('auth');
