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

//With controller
Route::get('/', 'PagesController@index')->name('start');

Route::get('/about', 'PagesController@about');
Route::get('/news', 'PagesController@news');
Route::get('/contact', 'PagesController@contact');
Route::get('/donate', 'PagesController@donate');
Route::get('/policy', 'PagesController@policy');
Route::get('/help', 'PagesController@help');

Route::get('/speeches', 'PagesController@speeches');

Route::get('/speaker/{name}', 'PagesController@speaker');

// Route::get('/speakers', 'SpeakersController@show');
// Route::get('/speakers/{id}', 'SpeakersController@show');
Route::resource('speakers','SpeakersController');
Route::get('/conferences', 'PagesController@conferences');
Route::get('conference/{date}/speeches', 'PagesController@conference');
//Route::get('/conference', 'PagesController@conference');
Auth::routes();

Route::get('/dashboard', 'DashboardController@index');


Route::get('parties','PagesController@parties');
Route::get('/party/{name}', 'PagesController@party');