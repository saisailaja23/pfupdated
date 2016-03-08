<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    //
});



/* profile List Api */

Route::get('/profile/{any}', 'ProfileController@getProfileApi');
Route::get('/profiles', 'ProfileController@getProfileApi');
Route::get('/profiles/religion/{any}', 'ProfileController@getProfileApi');
Route::get('/profiles/region/{any}', 'ProfileController@getProfileApi');
Route::get('/profiles/kids/{any}', 'ProfileController@getProfileApi');
Route::get('/profiles/state/{any}', 'ProfileController@getProfileApi');
Route::get('/profiles/name/{any}', 'ProfileController@getProfileApi');
Route::get('/profiles/child-preference/{any}', 'ProfileController@getProfileApi');
Route::get('/profiles/sort/{any}', 'ProfileController@getProfileApi');
Route::get('/flipbook/{any}', 'ProfileController@getProfileApi');
Route::get('/pdfprofile/{any}', 'ProfileController@getProfileApi');
Route::get('/pdfprofile/{any}/type/{type}', 'ProfileController@getProfileApi');
Route::get('/journal/{any}/journal-id/{id}', 'ProfileController@getProfileApi');