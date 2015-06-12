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

Route::get('/', 'CategoryController@index');

Route::get('dashboard', 'HomeController@index');

Route::resource('card', 'CardController');

Route::resource('category', 'CategoryController');

Route::get('detachCard/{id}', ['as'=>'detachCard', 'uses'=>'HomeController@detachCard'])->where('id', '[0-9]+');
Route::get('attachCard/{id}', ['as'=>'attachCard', 'uses'=>'HomeController@attachCard'])->where('id', '[0-9]+');

Route::controllers([
    'auth' => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
]);
