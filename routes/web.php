<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', 'ProductDevelopment\ProjectController@showProject');

Route::get('/ldap', 'ProductDevelopment\projectController@ldap');

Route::get('/phpinfo', function () {
    phpinfo();
});

Route::get('/errorRoute', function () {
    return view('errors.roleError');
})->name('errorRoute');

Route::get('login', 'Auth\LoginController@show');
Route::post('login', 'Auth\LoginController@login');
Route::get('logout', 'Auth\LoginController@logout');
Route::get('hashPassword', 'Auth\LoginController@hashPassword');

Route::group(['prefix' => 'Service'], function() {
    Route::get('UserLogin/{Account}/{Password}/{DeviceOS}/{DeviceID}/{DeviceToken}', 'Service\WebServiceController@userLogin');
    Route::get('CheckDevice/{DeviceOS}/{DeviceID}/{DeviceToken}', 'Service\WebServiceController@checkDevice');
    Route::get('MessageTime/{BroadcastID}/{Action}', 'Service\WebServiceController@messageTime');
    Route::get('TestMessage/{Account}/{Title}/{Content}/{Url}/{AudioFile}', 'Service\WebServiceController@testMessage');
});

Route::group(['middleware' => 'auth', 'prefix' => 'Project'], function() {
    Route::get('ProjectList', 'ProductDevelopment\ProjectController@projectList');

    Route::get('AddProject', 'ProductDevelopment\ProjectController@addProject');
    Route::post('InsertProject', 'ProductDevelopment\ProjectController@insertProject');

    Route::get('EditProject/{ProjectID}', 'ProductDevelopment\ProjectController@editProject');
    Route::post('UpdateProject/', 'ProductDevelopment\ProjectController@updateProject');

    Route::get('GetStaffByNodeID/{NodeID}', 'ProductDevelopment\ProjectController@getStaffByNodeID');

    Route::get('Delete/{ProjectID}', 'ProductDevelopment\ProjectController@deleteProject');

    Route::get('ShowProject/', 'ProductDevelopment\ProjectController@showProject');
    Route::get('ShowProduct/{Project}', 'ProductDevelopment\ProjectController@showProduct');
    Route::get('ShowProcess/{Product}', 'ProductDevelopment\ProjectController@showProcess');
});

Route::group(['middleware' => 'auth', 'prefix' => 'Product'], function() {
    
    Route::get('ProductList/{ProjectID}', 'ProductDevelopment\ProductController@productList');

    Route::get('AddProduct/{ProjectID}', 'ProductDevelopment\ProductController@addProduct');
    Route::post('InsertProduct', 'ProductDevelopment\ProductController@insertProduct');

    Route::get('EditProduct/{ProductID}', 'ProductDevelopment\ProductController@editProduct');
    Route::post('UpdateProduct/', 'ProductDevelopment\ProductController@updateProduct');

    Route::get('ProductExecute/{ProcessID}', 'ProductDevelopment\ProductController@productExecute');

    Route::get('Delete/{ProductID}', 'ProductDevelopment\ProductController@deleteProduct');
});

Route::group(['middleware' => 'auth', 'prefix' => 'Process'], function() {
    Route::get('ProcessList/{ProductID}', 'ProductDevelopment\ProcessController@processList');

    Route::post('InsertProcess', 'ProductDevelopment\ProcessController@insertProcess');
    
    Route::get('GetProcessData/{ProcessID}', 'ProductDevelopment\ProcessController@getProcessData');
    Route::get('GetPreparationList/{ProductID}/{ProcessID}', 'ProductDevelopment\ProcessController@getPreparationList');
    Route::any('SetPreparation/{ProductID}/{ProcessID}/{ChSelect}', 'ProductDevelopment\ProcessController@setPreparation');
    Route::post('SaveProcessSort/', 'ProductDevelopment\ProcessController@saveProcessSort');
    Route::post('UpdateProcess/', 'ProductDevelopment\ProcessController@updateProcess');

    Route::get('ProcessComplete/{ProcessID}', 'ProductDevelopment\ProcessController@processComplete');

    Route::get('Delete/{ProcessID}', 'ProductDevelopment\ProcessController@deleteProcess');
});

Route::group(['middleware' => 'auth', 'prefix' => 'SysOption'], function() {
    Route::get('StaffList', 'SystemManagement\StaffController@staffList');
    Route::post('UpdateStaff', 'SystemManagement\StaffController@updateStaff');
    Route::get('GetStaffData/{StaffID}', 'SystemManagement\StaffController@getStaffData');
    Route::get('GetStaffList/{NodeID}', 'SystemManagement\StaffController@getStaffList');

    Route::get('MoveData', 'SystemManagement\StaffController@moveData');

    Route::get('SideList', 'SystemManagement\AuthorizationController@sideList');
    Route::get('GetParentList/{SystemID}/{SideID?}', 'SystemManagement\AuthorizationController@getParentList');
    Route::get('GetSideData/{SideID}', 'SystemManagement\AuthorizationController@getSideData');
    Route::post('InsertSide', 'SystemManagement\AuthorizationController@insertSide');
    Route::post('UpdateSide', 'SystemManagement\AuthorizationController@updateSide');
    Route::get('DeleteSide/{SideID}', 'SystemManagement\AuthorizationController@deleteSide');
});

Route::group(['middleware' => 'auth', 'prefix' => 'Report'], function() {
    Route::any('ProjectExecuteRate', 'ProductDevelopment\ReportController@projectExecuteRate');
    Route::any('ProductExecuteRate', 'ProductDevelopment\ReportController@productExecuteRate');
});

//'middleware' => 'auth',

//Route::auth();