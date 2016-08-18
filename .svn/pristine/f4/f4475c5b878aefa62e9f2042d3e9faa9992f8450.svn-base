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
    Route::get('UserLogin/{Account}/{Password}/{DeviceOS}/{DeviceID}/{DeviceToken}', 'Service\WebServiceController@pserLogin');
    Route::get('CheckDevice/{DeviceOS}/{DeviceID}/{DeviceToken}', 'Service\WebServiceController@checkDevice');
    Route::get('MessageTime/{BroadcastID}/{Action}', 'Service\WebServiceController@messageTIme');
});

Route::group(['prefix' => 'Project'], function() {
    Route::get('phpinfo', 'ProductDevelopment\ProjectController@phpinfo');

    Route::get('ProjectList', 'ProductDevelopment\ProjectController@projectList');

    Route::get('AddProject', 'ProductDevelopment\ProjectController@addProject');
    Route::post('InsertProject', 'ProductDevelopment\ProjectController@insertProject');

    Route::get('EditProject/{ProjectID}', 'ProductDevelopment\ProjectController@editProject');
    Route::post('UpdateProject/', 'ProductDevelopment\ProjectController@updateProject');

    Route::get('GetStaffByNodeID/{NodeID}', 'ProductDevelopment\ProjectController@getStaffByNodeID');
});

Route::group(['prefix' => 'Product'], function() {
    Route::get('ProductList/{ProjectID}', 'ProductDevelopment\ProductController@productList');

    Route::get('AddProduct/{ProjectID}', 'ProductDevelopment\ProductController@addProduct');
    Route::post('InsertProduct', 'ProductDevelopment\ProductController@insertProduct');

    Route::get('EditProduct/{ProductID}', 'ProductDevelopment\ProductController@editProduct');
    Route::post('UpdateProduct/', 'ProductDevelopment\ProductController@updateProduct');

    Route::get('ProductExecute/{ProcessID}', 'ProductDevelopment\ProductController@productExecute');
});

Route::group(['prefix' => 'Process'], function() {
    Route::get('ProcessList/{ProductID}', 'ProductDevelopment\ProcessController@processList');

    Route::post('InsertProcess', 'ProductDevelopment\ProcessController@insertProcess');
    
    Route::get('GetProcessData/{ProcessID}', 'ProductDevelopment\ProcessController@getProcessData');
    Route::post('SaveProcessSort/', 'ProductDevelopment\ProcessController@saveProcessSort');
    Route::post('UpdateProcess/', 'ProductDevelopment\ProcessController@updateProcess');

    Route::get('ProcessComplete/{ProcessID}', 'ProductDevelopment\ProcessController@processComplete');
});