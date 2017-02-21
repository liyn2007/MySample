<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

get('/','StaticPagesController@home')->name('home');
Route::get('/help','StaticPagesController@help')->name('help');
Route::get('/about','StaticPagesController@about')->name('about');

get('signup','UsersController@create')->name('signup');

resource('users','UsersController');

get('login','SessionController@create')->name('login');
post('login','SessionController@store')->name('login');
delete('logout','SessionController@destroy')->name('logout');

get('/users/{id}/edit','UsersController@edit')->name('users.edit');

get('/signup/confirm/{token}', 'UsersController@confirmEmail')->name('confirm_email');

get('password/email', 'Auth\PasswordController@getEmail')->name('password.reset');
post('password/email', 'Auth\PasswordController@postEmail')->name('password.reset');
get('password/reset/{token}', 'Auth\PasswordController@getReset')->name('password.edit');
post('password/reset', 'Auth\PasswordController@postReset')->name('password.update');

resource('statuses','StatusesController',['only'=>['destroy','store']]);

get('/users/{id}/followings', 'UsersController@followings')->name('users.followings');
get('/users/{id}/followers', 'UsersController@followers')->name('users.followers');

post('users/followers/{id}', 'FollowersController@store')->name('followers.store');
delete('users/followers/{id}', 'FollowersController@destroy')->name('followers.destroy');
