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

//Route::get('/', 'WelcomeController@Hello');

//Route::get('home', 'HomeController@home');

Route::group(['prefix' => 'ProductDevelopment'], function() {
    Route::get('ProductList', 'ProductDevelopment\ProductDevelopmentController@ProductList');
});
//Route::get('Login', 'Logincontroller@index');

//Route::get('auth/login', 'Auth/AuthController@login');
/*
Route::group(['prefix' => 'auth'], function() {
    Route::get('login', 'Auth\AuthController@Login');
    Route::post('login', 'Auth\AuthController@CreateAccount');
    Route::get('register', 'Auth\AuthController@Register');
});
*/