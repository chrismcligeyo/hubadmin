<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

    Route::get('requisitions', 'RequisitionsController@index');
    Route::get('requisitions/{id}', 'RequisitionsController@view');
    Route::put('requisitions/approve/{requisition}', 'RequisitionsController@markAsApproved');
	Route::put('requisitions/{requisition}', 'RequisitionsController@markAsDeclined');
	Route::get('requisitions/downloadpdf/{id}', 'RequisitionsController@downloadpdf')->name('pdf.document');

	Route::post('login', 'AuthController@login');

	


Route::post('register', 'AuthController@register');

Route::group(['middleware' => 'auth:api'], function(){
	Route::get('details', 'AuthController@details');
});
//Client
Route::group(['middleware' => 'auth:api', 'prefix' => 'client'], function(){
	Route::post('/create', 'ClientsController@create');
	Route::get('/view', 'ClientsController@index');
	Route::get('/view/{id}', 'ClientsController@view');
	Route::put('/edit/{id}', 'ClientsController@update');
	Route::delete('/delete/{id}', 'ClientsController@delete');
});

//Requisition Reconciliation Routes
Route::group(['middleware' => 'auth:api', 'prefix' => 'reconciliation'], function(){
	Route::post('/create', 'ReconciliationsController@create');
	Route::get('/view', 'ReconciliationsController@index');
	Route::get('/view/{id}', 'ReconciliationsController@view');
	Route::put('/edit/{id}', 'ReconciliationsController@update');
	Route::delete('/delete/{id}', 'ReconciliationsController@delete');
});

//Activation Reports
Route::group(['middleware' => 'auth:api', 'prefix' => 'activationReport'], function(){
	Route::post('/create', 'ActivationReportsController@create');
	Route::get('/view', 'ActivationReportsController@index');
	Route::get('/view/{id}', 'ActivationReportsController@view');
	Route::put('/edit/{id}', 'ActivationReportsController@update');
	Route::delete('/delete/{id}', 'ActivationReportsController@delete');
});

//Employee
Route::group(['middleware' => 'auth:api', 'prefix' => 'employee'], function(){
	Route::post('/create', 'EmployeesController@create');
	Route::get('/view', 'EmployeesController@index');
	Route::get('/view/{id}', 'EmployeesController@view');
	Route::put('/edit/{id}', 'EmployeesController@update');
	Route::delete('/delete/{id}', 'EmployeesController@delete');
});
//user
Route::group(['middleware' => 'auth:api', 'prefix' => 'user'], function(){
	Route::get('/view', 'UsersController@index');
});

//Requisition Category
Route::group(['middleware' => 'auth:api', 'prefix' => 'requisitioncategory'], function(){
	Route::post('/create', 'RequisitionCategoriesController@create');
	Route::get('/view', 'RequisitionCategoriesController@index');
	Route::get('/view/{id}', 'RequisitionCategoriesController@view');
	Route::put('/edit/{id}', 'RequisitionCategoriesController@update');
	Route::delete('/delete/{id}', 'RequisitionCategoriesController@delete');
});

//Requisitions
Route::group(['middleware' => 'auth:api', 'prefix' => 'requisition'], function(){
	Route::post('/create', 'RequisitionsController@create');
	Route::get('/view', 'RequisitionsController@index');
	Route::get('/view/{id}', 'RequisitionsController@view');
	Route::put('/edit/{id}', 'RequisitionsController@update');
	Route::put('/submit/{id}', 'RequisitionsController@submit');
	Route::put('/approve/{id}', 'RequisitionsController@approve');
	Route::put('/decline/{id}', 'RequisitionsController@decline');
	Route::put('/given/{id}', 'RequisitionsController@given');
	Route::delete('/delete/{id}', 'RequisitionsController@delete');
});

//Requisition Items
Route::group(['middleware' => 'auth:api', 'prefix' => 'requisitionitem'], function(){
	Route::post('/create', 'RequisitionItemsController@create');
	Route::get('/view', 'RequisitionItemsController@index');
	Route::get('/view/{id}', 'RequisitionItemsController@view');
	Route::get('/total', 'RequisitionItemsController@total');
	Route::put('/edit/{id}', 'RequisitionItemsController@update');
	Route::delete('/delete/{id}', 'RequisitionItemsController@delete');
});

//Route
Route::group(['middleware' => 'auth:api', 'prefix' => 'route'], function(){
	Route::post('/create', 'RoutesController@create');
	Route::get('/view', 'RoutesController@index');
	Route::get('/view/{id}', 'RoutesController@view');
	Route::put('/edit/{id}', 'RoutesController@update');
	Route::delete('/delete/{id}', 'RoutesController@delete');
});

//Activations Controller
Route::group(['middleware' => 'auth:api', 'prefix' => 'activation'], function(){
	Route::post('/create', 'ActivationsController@create');
	Route::get('/view', 'ActivationsController@index');
	Route::get('/view/{id}', 'ActivationsController@view');
	Route::put('/edit/{id}', 'ActivationsController@update');
	Route::delete('/delete/{id}', 'ActivationsController@delete');
});