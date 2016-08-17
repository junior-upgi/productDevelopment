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

Route::group(['prefix' => 'Service'], function() {
    Route::get('UserLogin/{Account}/{Password}/{DeviceOS}/{DeviceID}/{DeviceToken}', 'Service\WebServiceController@UserLogin');
    Route::get('CheckDevice/{DeviceOS}/{DeviceID}/{DeviceToken}', 'Service\WebServiceController@CheckDevice');
    Route::get('MessageTime/{BroadcastID}/{Action}', 'Service\WebServiceController@MessageTIme');
});

Route::group(['prefix' => 'Project'], function() {
    Route::get('phpinfo', 'ProductDevelopment\ProjectController@phpinfo');

    Route::get('ProjectList', 'ProductDevelopment\ProjectController@ProjectList');

    Route::get('AddProject', 'ProductDevelopment\ProjectController@AddProject');
    Route::post('InsertProject', 'ProductDevelopment\ProjectController@InsertProject');

    Route::get('EditProject/{ProjectID}', 'ProductDevelopment\ProjectController@EditProject');
    Route::post('UpdateProject/{ProjectID}', 'ProductDevelopment\ProjectController@UpdateProject');

    Route::get('GetStaffByNodeID/{NodeID}', 'ProductDevelopment\ProjectController@GetStaffByNodeID');
});

Route::group(['prefix' => 'Product'], function() {
    Route::get('ProductList/{ProjectID}', 'ProductDevelopment\ProductController@ProductList');

    Route::get('AddProduct/{ProjectID}', 'ProductDevelopment\ProductController@AddProduct');
    Route::post('InsertProduct', 'ProductDevelopment\ProductController@InsertProduct');

    Route::get('EditProduct/{ProductID}', 'ProductDevelopment\ProductController@EditProduct');
    Route::post('UpdateProduct/{ProductID}', 'ProductDevelopment\ProductController@UpdateProduct');
});

Route::group(['prefix' => 'Process'], function() {
    Route::get('ProcessList/{ProductID}', 'ProductDevelopment\ProcessController@ProcessList');

    Route::post('InsertProcess', 'ProductDevelopment\ProcessController@InsertProcess');
    
    Route::get('GetProcessData/{ProcessID}', 'ProductDevelopment\ProcessController@GetProcessData');
    Route::post('SaveProcessSort/', 'ProductDevelopment\ProcessController@SaveProcessSort');
    Route::post('UpdateProcess/{ProcessID}', 'ProductDevelopment\ProcessController@UpdateProcess');
});