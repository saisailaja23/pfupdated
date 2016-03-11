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

Route::get('/profile/{username}', 'ProfileController@getProfileApi');

Route::get('/profiles', 'ProfileController@getProfileApi');
Route::get('/profiles/religion/{religion}', 'ProfileController@getProfileApi');
Route::get('/profiles/region/{region}', 'ProfileController@getProfileApi');
Route::get('/profiles/kids/{any}', 'ProfileController@getProfileApi');
Route::get('/profiles/state/{any}', 'ProfileController@getProfileApi');
Route::get('/profiles/name/{any}', 'ProfileController@getProfileApi');
Route::get('/profiles/child-preference/{any}', 'ProfileController@getProfileApi');
Route::get('/profiles/sort/{any}', 'ProfileController@getProfileApi');

Route::get('/flipbook/{any}', 'ProfileController@getProfileApi');
Route::get('/pdfprofile/{any}', 'ProfileController@getProfileApi');
Route::get('/pdfprofile/{any}/type/{type}', 'ProfileController@getProfileApi');

Route::get('/photos/albums/{any}', 'ProfileController@getAlbumApi');



Route::get('/journals/{username}', 'ProfileController@getJournalApi');
Route::get('/journal/{username}/{journalid}', 'ProfileController@getJournalApi');
Route::get('/journals/{username}/{all}', 'ProfileController@getJournalApi');//journals/{username}/{title}


Route::get('/letters/{username}', 'ProfileController@getLetterApi');
Route::get('/letter/{username}/{id}', 'ProfileController@getLetterApi');

