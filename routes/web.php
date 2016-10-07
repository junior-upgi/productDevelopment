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

//Route::get('/', 'ProductDevelopment\ProjectController@showProject');
Route::get('/', function () {
    return redirect('login');
});

Route::get('ldap', 'ProductDevelopment\projectController@ldap');

Route::get('test', 'Service\WebServiceController@test');
//Route::get('UserSettingCost/{processID}/{staffID}', 'ProductDevelopment\ProcessController@userSettingCost');

Route::get('phpinfo', function () {
    phpinfo();
});

/*
Route::get('csrfToken', function () {
    return csrf_token();
});
*/

Route::get('errorRoute', function () {
    return view('errors.roleError');
})->name('errorRoute');

Route::get('login', 'Auth\LoginController@show');
Route::post('login', 'Auth\LoginController@login');
Route::get('logout', 'Auth\LoginController@logout');
Route::get('hashPassword', 'Auth\LoginController@hashPassword');

Route::get('reset', 'Auth\ResetPasswordController@resetPassword');
Route::post('checkPersonal', 'Auth\ResetPasswordController@checkPersonal');
Route::post('setPassword', 'Auth\ResetPasswordController@setPassword');
Route::get('testad', 'Auth\ResetPasswordController@testAD');

Route::group(['prefix' => 'Service'], function() {
    Route::get('UserLogin/{Account}/{Password}/{DeviceOS}/{DeviceID}/{DeviceToken}', 'Service\WebServiceController@userLogin');
    Route::get('CheckDevice/{DeviceOS}/{DeviceID}/{DeviceToken}', 'Service\WebServiceController@checkDevice');
    Route::get('MessageTime/{time}', 'Service\WebServiceController@messageTime');
    Route::post('SendMessage', 'Service\WebServiceController@sendMessage');
    Route::get('TestMessage/{Account}/{Title}/{Content}/{Url}/{AudioFile}', 'Service\WebServiceController@testMessage');
});

Route::group(['middleware' => 'auth', 'prefix' => 'Project'], function() {
    Route::get('ProjectList', 'ProductDevelopment\ProjectController@projectList');

    Route::get('AddProject', 'ProductDevelopment\ProjectController@addProject');
    Route::post('InsertProject', 'ProductDevelopment\ProjectController@insertProject');

    Route::get('EditProject/{projectID}', 'ProductDevelopment\ProjectController@editProject');
    Route::post('UpdateProject', 'ProductDevelopment\ProjectController@updateProject');

    Route::get('GetStaffByNodeID/{nodeID}', 'ProductDevelopment\ProjectController@getStaffByNodeID');

    Route::get('Delete/{projectID}', 'ProductDevelopment\ProjectController@deleteProject');

    Route::get('ShowProject', 'ProductDevelopment\ProjectController@showProject');
    //Route::get('ShowProduct/{Project}', 'ProductDevelopment\ProjectController@showProduct');
    //Route::get('ShowProcess/{Product}', 'ProductDevelopment\ProjectController@showProcess');
});

Route::group(['middleware' => 'auth', 'prefix' => 'Product'], function() {
    
    Route::get('ProductList/{projectID}', 'ProductDevelopment\ProductController@productList');

    Route::get('AddProduct/{projectID}', 'ProductDevelopment\ProductController@addProduct');
    Route::post('InsertProduct', 'ProductDevelopment\ProductController@insertProduct');

    Route::get('EditProduct/{productID}', 'ProductDevelopment\ProductController@editProduct');
    Route::post('UpdateProduct', 'ProductDevelopment\ProductController@updateProduct');

    Route::get('ProductExecute/{processID}', 'ProductDevelopment\ProductController@productExecute');

    Route::get('Delete/{productID}', 'ProductDevelopment\ProductController@deleteProduct');
});

Route::group(['middleware' => 'auth', 'prefix' => 'Process'], function() {
    Route::get('ProcessList/{productID}', 'ProductDevelopment\ProcessController@processList');

    Route::post('InsertProcess', 'ProductDevelopment\ProcessController@insertProcess');
    
    Route::get('GetProcessData/{processID}', 'ProductDevelopment\ProcessController@getProcessData');
    Route::get('GetPreparationList/{productID}/{processID}', 'ProductDevelopment\ProcessController@getPreparationList');
    Route::any('SetPreparation/{productID}/{processID}/{select}', 'ProductDevelopment\ProcessController@setPreparation');
    Route::post('SaveProcessSort', 'ProductDevelopment\ProcessController@saveProcessSort');
    Route::post('UpdateProcess', 'ProductDevelopment\ProcessController@updateProcess');

    Route::get('ProcessComplete/{processID}', 'ProductDevelopment\ProcessController@processComplete');

    Route::get('Delete/{processID}', 'ProductDevelopment\ProcessController@deleteProcess');

    Route::get('MyProcess', 'ProductDevelopment\ProcessController@myProcess');
    
});

Route::group(['middleware' => 'auth', 'prefix' => 'SysOption'], function() {
    Route::get('StaffList', 'SystemManagement\StaffController@staffList');
    Route::post('UpdateStaff', 'SystemManagement\StaffController@updateStaff');
    Route::get('GetStaffData/{StaffID}', 'SystemManagement\StaffController@getStaffData');
    Route::get('GetStaffList/{NodeID}', 'SystemManagement\StaffController@getStaffList');
    Route::get('InsertUser/{account}/{password}', 'SystemManagement\StaffController@insertUser');

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

Route::group(['prefix' => 'Mobile'], function() {
    Route::get('UserSettingCost/{processID}/{staffID}', 'ProductDevelopment\MobileController@userSettingCost');
    Route::get('OverdueInfo/{processID}', 'ProductDevelopment\MobileController@overdueInfo');
    Route::post('SaveCost', 'ProductDevelopment\MobileController@userSaveCost');
    Route::get('testSend', function () {
        return view('Mobile.SendMessage');
    });
});