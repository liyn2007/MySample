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
delete('logout','SessionController@destory')->name('logout');

get('/users/{id}/edit','UsersController@edit')->name('users.edit');

get('/signup/confirm/{token}', 'UsersController@confirmEmail')->name('confirm_email');
