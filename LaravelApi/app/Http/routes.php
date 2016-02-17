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

Route::get('names/{id}', function($id)
{
    $names = array(
      1 => "John",
      2 => "Mary",
      3 => "Steven"
    );    
    return array($id => $names[$id]);
});

// Route::get('profile', [
//     'as' => 'profile', 'uses' => 'Controller@showProfile'
// ]);


Route::get('familylist', 'FamilyController@showProfile');

/* profile List Api */

Route::get('/profiles', 'ParentController@showProfile');
Route::get('/profiles/{id}', 'ParentController@showProfile');
Route::get('/profiles/filter', 'ParentController@showProfile');
//Route::get('profiles/{id}', ['as' => 'search', 'uses' => 'ParentController@showProfile']);
