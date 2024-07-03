<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('auth')->group(function () {
    Route::post('register', 'App\Http\Controllers\AuthController@register');
    Route::post('login', 'App\Http\Controllers\AuthController@login');
    Route::get('refresh', 'AuthController@refresh');
    Route::post('forgot-password', 'App\Http\Controllers\AuthController@sendResetPasswordLink')->middleware('guest')->name('password.email');
    Route::post('reset-password', 'App\Http\Controllers\AuthController@resetPassword')->middleware('guest')->name('password.update');
    Route::post('update-password', 'App\Http\Controllers\AuthController@updatePassword');
    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('user', 'App\Http\Controllers\AuthController@user');
        Route::get('logout', 'App\Http\Controllers\AuthController@logout');
    });
});

Route::group(['middleware' => ['auth:api', 'staff.property.view']], function () {

    Route::get('dashboard-stats', 'App\Http\Controllers\DashboardController@stats')->middleware("checkPermission:can-view-appartment-types");
    Route::get('jobs-calendar', 'App\Http\Controllers\DashboardController@getScheduleJobs')->middleware("checkPermission:can-view-appartment-types");
    Route::get('dashboard-counts', 'App\Http\Controllers\DashboardController@jobCounts')->middleware("checkPermission:can-view-appartment-types");
    Route::any('check-token', 'App\Http\Controllers\AuthController@checkToken');
    Route::get('appartment-type', 'App\Http\Controllers\AppartmentTypeController@index')->middleware("checkPermission:can-view-appartment-types");
    Route::post('appartment-type', 'App\Http\Controllers\AppartmentTypeController@store')->middleware("checkPermission:can-view-appartment-types");
    Route::post('appartment-type/save', 'App\Http\Controllers\AppartmentTypeController@save')->middleware("checkPermission:can-view-appartment-types");
    Route::put('appartment-type/{id}', 'App\Http\Controllers\AppartmentTypeController@update')->middleware("checkPermission:can-view-appartment-types");
    Route::get('appartment-type/{id}/edit', 'App\Http\Controllers\AppartmentTypeController@edit')->middleware("checkPermission:can-view-appartment-types");
    Route::delete('appartment-type/{id}', 'App\Http\Controllers\AppartmentTypeController@destroy')->middleware("checkPermission:can-view-appartment-types");

    Route::get('service-type', 'App\Http\Controllers\ServiceController@index')->middleware("checkPermission:can-view-appartment-types");
    Route::post('service-type', 'App\Http\Controllers\ServiceController@store')->middleware("checkPermission:can-view-appartment-types");
    Route::put('service-type/{id}', 'App\Http\Controllers\ServiceController@update')->middleware("checkPermission:can-view-appartment-types");
    Route::get('service-type/{id}/edit', 'App\Http\Controllers\ServiceController@edit')->middleware("checkPermission:can-view-appartment-types");
    Route::delete('service-type/{id}', 'App\Http\Controllers\ServiceController@destroy')->middleware("checkPermission:can-view-appartment-types");

    Route::get('paint-color', 'App\Http\Controllers\PaintColorController@index')->middleware("checkPermission:can-view-appartment-types");
    Route::post('paint-color', 'App\Http\Controllers\PaintColorController@store')->middleware("checkPermission:can-view-appartment-types");
    Route::put('paint-color/{id}', 'App\Http\Controllers\PaintColorController@update')->middleware("checkPermission:can-view-appartment-types");
    Route::get('paint-color/{id}/edit', 'App\Http\Controllers\PaintColorController@edit')->middleware("checkPermission:can-view-appartment-types");
    Route::delete('paint-color/{id}', 'App\Http\Controllers\PaintColorController@destroy')->middleware("checkPermission:can-view-appartment-types");

    Route::get('paint-grade', 'App\Http\Controllers\PaintGradeController@index')->middleware("checkPermission:can-view-appartment-types");
    Route::post('paint-grade', 'App\Http\Controllers\PaintGradeController@store')->middleware("checkPermission:can-view-appartment-types");
    Route::put('paint-grade/{id}', 'App\Http\Controllers\PaintGradeController@update')->middleware("checkPermission:can-view-appartment-types");
    Route::get('paint-grade/{id}/edit', 'App\Http\Controllers\PaintGradeController@edit')->middleware("checkPermission:can-view-appartment-types");
    Route::delete('paint-grade/{id}', 'App\Http\Controllers\PaintGradeController@destroy')->middleware("checkPermission:can-view-appartment-types");

    Route::get('paint-finishing', 'App\Http\Controllers\PaintFinishingController@index')->middleware("checkPermission:can-view-appartment-types");
    Route::post('paint-finishing', 'App\Http\Controllers\PaintFinishingController@store')->middleware("checkPermission:can-view-appartment-types");
    Route::put('paint-finishing/{id}', 'App\Http\Controllers\PaintFinishingController@update')->middleware("checkPermission:can-view-appartment-types");
    Route::get('paint-finishing/{id}/edit', 'App\Http\Controllers\PaintFinishingController@edit')->middleware("checkPermission:can-view-appartment-types");
    Route::delete('paint-finishing/{id}', 'App\Http\Controllers\PaintFinishingController@destroy')->middleware("checkPermission:can-view-appartment-types");

    Route::get('paint-manufacturer', 'App\Http\Controllers\PaintManufacturerController@index')->middleware("checkPermission:can-view-appartment-types");
    Route::post('paint-manufacturer', 'App\Http\Controllers\PaintManufacturerController@store')->middleware("checkPermission:can-view-appartment-types");
    Route::put('paint-manufacturer/{id}', 'App\Http\Controllers\PaintManufacturerController@update')->middleware("checkPermission:can-view-appartment-types");
    Route::get('paint-manufacturer/{id}/edit', 'App\Http\Controllers\PaintManufacturerController@edit')->middleware("checkPermission:can-view-appartment-types");
    Route::delete('paint-manufacturer/{id}', 'App\Http\Controllers\PaintManufacturerController@destroy')->middleware("checkPermission:can-view-appartment-types");

    Route::get('paint-method', 'App\Http\Controllers\PaintMethodController@index')->middleware("checkPermission:can-view-appartment-types");
    Route::post('paint-method', 'App\Http\Controllers\PaintMethodController@store')->middleware("checkPermission:can-view-appartment-types");
    Route::put('paint-method/{id}', 'App\Http\Controllers\PaintMethodController@update')->middleware("checkPermission:can-view-appartment-types");
    Route::get('paint-method/{id}/edit', 'App\Http\Controllers\PaintMethodController@edit')->middleware("checkPermission:can-view-appartment-types");
    Route::delete('paint-method/{id}', 'App\Http\Controllers\PaintMethodController@destroy')->middleware("checkPermission:can-view-appartment-types");

    Route::get('paint-surface', 'App\Http\Controllers\PaintSurfaceController@index')->middleware("checkPermission:can-view-appartment-types");
    Route::post('paint-surface', 'App\Http\Controllers\PaintSurfaceController@store')->middleware("checkPermission:can-view-appartment-types");
    Route::put('paint-surface/{id}', 'App\Http\Controllers\PaintSurfaceController@update')->middleware("checkPermission:can-view-appartment-types");
    Route::get('paint-surface/{id}/edit', 'App\Http\Controllers\PaintSurfaceController@edit')->middleware("checkPermission:can-view-appartment-types");
    Route::delete('paint-surface/{id}', 'App\Http\Controllers\PaintSurfaceController@destroy')->middleware("checkPermission:can-view-appartment-types");

    Route::get('client', 'App\Http\Controllers\ClientController@index')->middleware("checkPermission:can-view-appartment-types");
    Route::get('client/check-qb/status', 'App\Http\Controllers\ClientController@checkQBStatus')->middleware("checkPermission:can-view-appartment-types");
    // Route::get('client/managers', 'App\Http\Controllers\ClientController@getclientManagers')->middleware("checkPermission:can-view-appartment-types");
    Route::post('client', 'App\Http\Controllers\ClientController@store')->middleware("checkPermission:can-view-appartment-types");
    Route::get('client/{id}/edit', 'App\Http\Controllers\ClientController@edit')->middleware("checkPermission:can-view-appartment-types");
    Route::post('client/{id}', 'App\Http\Controllers\ClientController@update')->middleware("checkPermission:can-view-appartment-types");
    Route::get('client/{id}/edit', 'App\Http\Controllers\ClientController@edit')->middleware("checkPermission:can-view-appartment-types");
    Route::delete('client/{id}', 'App\Http\Controllers\ClientController@destroy')->middleware("checkPermission:can-view-appartment-types");
    Route::get('client-history', 'App\Http\Controllers\ClientController@appHistoryData')->middleware("checkPermission:can-view-appartment-types");
    Route::post('search-client-history', 'App\Http\Controllers\ClientController@searchClientHistory')->middleware("checkPermission:can-view-appartment-types");
    Route::get('required-po-jobs', 'App\Http\Controllers\JobController@getRequiredPoJobs')->middleware("checkPermission:can-view-appartment-types");
    Route::put('client-billing/{id}', 'App\Http\Controllers\ClientController@updateClientBilling')->middleware("checkPermission:can-view-appartment-types");
    // client staff
    Route::get('client/{clientId}/staff', 'App\Http\Controllers\ClientStaffController@index')->middleware("checkPermission:can-view-appartment-types");
    Route::get('staff/roles_properties/{clientId}', 'App\Http\Controllers\ClientStaffController@rolesProperties')->middleware("checkPermission:can-view-appartment-types");
    // Route::get('client/{clientId}/staff/property/{propertyId}', 'App\Http\Controllers\ClientUserController@getStaff')->middleware("checkPermission:can-view-appartment-types");
    Route::get('client/staff/{id}', 'App\Http\Controllers\ClientStaffController@get')->middleware("checkPermission:can-view-appartment-types");
    // Route::get('client/{clientId}/user/create', 'App\Http\Controllers\ClientUserController@create')->middleware("checkPermission:can-view-appartment-types");
    Route::post('client/{clientId}/staff', 'App\Http\Controllers\ClientStaffController@store')->middleware("checkPermission:can-view-appartment-types");
    Route::put('client/{clientId}/staff/{id}', 'App\Http\Controllers\ClientStaffController@update')->middleware("checkPermission:can-view-appartment-types");
    Route::put('client/{clientId}/user/{id}/status', 'App\Http\Controllers\ClientUserController@updateStatus')->middleware("checkPermission:can-view-appartment-types");
    // Route::delete('client/{clientId}/user/{id}', 'App\Http\Controllers\ClientUserController@destroy')->middleware("checkPermission:can-view-appartment-types");
    Route::get('staff-roles', 'App\Http\Controllers\StaffRoleController@index')->middleware("checkPermission:can-view-appartment-types");
    Route::post('staff-roles', 'App\Http\Controllers\StaffRoleController@store')->middleware("checkPermission:can-view-appartment-types");
    Route::get('staff-roles/{id}/edit', 'App\Http\Controllers\StaffRoleController@edit')->middleware("checkPermission:can-view-appartment-types");
    Route::put('staff-roles/{id}', 'App\Http\Controllers\StaffRoleController@update')->middleware("checkPermission:can-view-appartment-types");
    Route::delete('staff-roles/{id}', 'App\Http\Controllers\StaffRoleController@destroy')->middleware("checkPermission:can-view-appartment-types");

    Route::get('property', 'App\Http\Controllers\PropertyController@index')->middleware("checkPermission:can-view-appartment-types");
    Route::get('property/create', 'App\Http\Controllers\PropertyController@create')->middleware("checkPermission:can-view-appartment-types");
    Route::post('property', 'App\Http\Controllers\PropertyController@store')->middleware("checkPermission:can-view-appartment-types");
    Route::post('property/staff', 'App\Http\Controllers\PropertyController@storeStaff')->middleware("checkPermission:can-view-appartment-types");
    Route::get('property/{id}/edit', 'App\Http\Controllers\PropertyController@edit')->middleware("checkPermission:can-view-appartment-types");
    Route::put('property/{id}', 'App\Http\Controllers\PropertyController@update')->middleware("checkPermission:can-view-appartment-types");
    Route::delete('property/{id}', 'App\Http\Controllers\PropertyController@destroy')->middleware("checkPermission:can-view-appartment-types");
    Route::put('property/{id}/billing', 'App\Http\Controllers\PropertyController@billing')->middleware("checkPermission:can-view-appartment-types");

    Route::get('client-property/{id}', 'App\Http\Controllers\ClientController@clientProperty')->middleware("checkPermission:can-view-appartment-types");
    Route::get('staff-property', 'App\Http\Controllers\ClientController@staffProperty')->middleware("checkPermission:can-view-appartment-types");
    // Route::get('client-supplier/{id}/edit', 'App\Http\Controllers\ClientController@getclientSupplier')->middleware("checkPermission:can-view-appartment-types");
    // Route::put('client-supplier/{id}', 'App\Http\Controllers\ClientController@updateclientSupplier')->middleware("checkPermission:can-view-appartment-types");
    Route::get('client-paint-spec', 'App\Http\Controllers\PaintSpecController@create')->middleware("checkPermission:can-view-appartment-types");
    Route::get('client-paint-spec/{id}/edit', 'App\Http\Controllers\PaintSpecController@edit')->middleware("checkPermission:can-view-appartment-types");
    Route::post('client-paint-spec', 'App\Http\Controllers\PaintSpecController@store')->middleware("checkPermission:can-view-appartment-types");
    Route::post('updatepropertystatus/{status}/{id}', 'App\Http\Controllers\ClientController@updatePropertyStatus')->middleware("checkPermission:can-view-appartment-types");

    Route::get('tax-type', 'App\Http\Controllers\TaxTypeController@index')->middleware("checkPermission:can-view-appartment-types");
    Route::post('tax-type', 'App\Http\Controllers\TaxTypeController@store')->middleware("checkPermission:can-view-appartment-types");
    Route::get('tax-type/{id}/edit', 'App\Http\Controllers\TaxTypeController@edit')->middleware("checkPermission:can-view-appartment-types");
    Route::put('tax-type/{id}', 'App\Http\Controllers\TaxTypeController@update')->middleware("checkPermission:can-view-appartment-types");
    Route::get('tax-type/{id}/edit', 'App\Http\Controllers\TaxTypeController@edit')->middleware("checkPermission:can-view-appartment-types");
    Route::delete('tax-type/{id}', 'App\Http\Controllers\TaxTypeController@destroy')->middleware("checkPermission:can-view-appartment-types");

    Route::get('department', 'App\Http\Controllers\DepartmentController@index')->middleware("checkPermission:can-view-appartment-types");
    Route::post('department', 'App\Http\Controllers\DepartmentController@store')->middleware("checkPermission:can-view-appartment-types");
    Route::get('department/{id}/edit', 'App\Http\Controllers\DepartmentController@edit')->middleware("checkPermission:can-view-appartment-types");
    Route::put('department/{id}', 'App\Http\Controllers\DepartmentController@update')->middleware("checkPermission:can-view-appartment-types");
    Route::get('department/{id}/edit', 'App\Http\Controllers\DepartmentController@edit')->middleware("checkPermission:can-view-appartment-types");
    Route::delete('department/{id}', 'App\Http\Controllers\DepartmentController@destroy')->middleware("checkPermission:can-view-appartment-types");

    Route::get('crew', 'App\Http\Controllers\CrewController@index')->middleware("checkPermission:can-view-appartment-types");
    Route::get('crew/create', 'App\Http\Controllers\CrewController@create')->middleware("checkPermission:can-view-appartment-types");
    Route::post('crew', 'App\Http\Controllers\CrewController@store')->middleware("checkPermission:can-view-appartment-types");
    Route::get('crew/{id}/edit', 'App\Http\Controllers\CrewController@edit')->middleware("checkPermission:can-view-appartment-types");
    Route::put('crew/{id}', 'App\Http\Controllers\CrewController@update')->middleware("checkPermission:can-view-appartment-types");
    Route::get('crew/{id}/edit', 'App\Http\Controllers\CrewController@edit')->middleware("checkPermission:can-view-appartment-types");
    Route::delete('crew/{id}', 'App\Http\Controllers\CrewController@destroy')->middleware("checkPermission:can-view-appartment-types");

    Route::get('employe', 'App\Http\Controllers\EmployeController@index')->middleware("checkPermission:can-view-appartment-types");
    Route::post('employe', 'App\Http\Controllers\EmployeController@store')->middleware("checkPermission:can-view-appartment-types");
    Route::delete('employe', 'App\Http\Controllers\EmployeController@destroy')->middleware("checkPermission:can-view-appartment-types");
    Route::get('employe/create', 'App\Http\Controllers\EmployeController@create')->middleware("checkPermission:can-view-appartment-types");
    Route::get('employe/{id}/edit', 'App\Http\Controllers\EmployeController@edit')->middleware("checkPermission:can-view-appartment-types");
    Route::post('employe/{id}', 'App\Http\Controllers\EmployeController@update')->middleware("checkPermission:can-view-appartment-types");
    Route::get('employe/{id}/edit', 'App\Http\Controllers\EmployeController@edit')->middleware("checkPermission:can-view-appartment-types");
    Route::delete('employe/{id}', 'App\Http\Controllers\EmployeController@destroy')->middleware("checkPermission:can-view-appartment-types");
    Route::post('employee-documents/{id}', 'App\Http\Controllers\EmployeController@uploadEmployeeDocuments')->middleware("checkPermission:can-view-appartment-types");
    Route::get('get-employee-documents/{id}', 'App\Http\Controllers\EmployeController@getEmployeeDocuments')->middleware("checkPermission:can-view-appartment-types");
    Route::delete('delete-employee-documents/{id}', 'App\Http\Controllers\EmployeController@deleteEmployeeDocuments')->middleware("checkPermission:can-view-appartment-types");
    Route::get('edit-employee-document/{id}', 'App\Http\Controllers\EmployeController@editEmployeeDocument')->middleware("checkPermission:can-view-appartment-types");
    Route::post('update-employee-document/{id}', 'App\Http\Controllers\EmployeController@updateEmployeeDocument')->middleware("checkPermission:can-view-appartment-types");

    Route::get('sub-category/{parentID}', 'App\Http\Controllers\SubCategoryController@index')->middleware("checkPermission:can-view-appartment-types");
    Route::post('sub-category', 'App\Http\Controllers\SubCategoryController@store')->middleware("checkPermission:can-view-appartment-types");
    Route::get('sub-category/{id}/edit', 'App\Http\Controllers\SubCategoryController@edit')->middleware("checkPermission:can-view-appartment-types");
    Route::put('sub-category/{id}', 'App\Http\Controllers\SubCategoryController@update')->middleware("checkPermission:can-view-appartment-types");
    Route::delete('sub-category/{id}', 'App\Http\Controllers\SubCategoryController@destroy')->middleware("checkPermission:can-view-appartment-types");

    Route::get('crew-employees', 'App\Http\Controllers\EmployeeCrewController@index')->middleware("checkPermission:can-view-appartment-types");
    Route::get('crew-employees/create', 'App\Http\Controllers\EmployeeCrewController@create')->middleware("checkPermission:can-view-appartment-types");
    Route::post('crew-employees', 'App\Http\Controllers\EmployeeCrewController@store')->middleware("checkPermission:can-view-appartment-types");
    Route::get('crew-employees/{id}/edit', 'App\Http\Controllers\EmployeeCrewController@edit')->middleware("checkPermission:can-view-appartment-types");
    Route::put('crew-employees/update', 'App\Http\Controllers\EmployeeCrewController@update')->middleware("checkPermission:can-view-appartment-types");
    Route::delete('crew-employees/{id}', 'App\Http\Controllers\EmployeeCrewController@destroy')->middleware("checkPermission:can-view-appartment-types");

    Route::get('client-property-pricing/{id}/edit', 'App\Http\Controllers\ClientPricingController@edit')->middleware("checkPermission:can-view-appartment-types");
    Route::post('client-property-pricing', 'App\Http\Controllers\ClientPricingController@store')->middleware("checkPermission:can-view-appartment-types");
    Route::post('udpate-pricing-check/{id}', 'App\Http\Controllers\ClientPricingController@updatePricingCheck')->middleware("checkPermission:can-view-appartment-types");

    Route::get('job', 'App\Http\Controllers\JobController@index')->middleware("checkPermission:can-view-appartment-types");
    Route::get('job/create/{clientId?}', 'App\Http\Controllers\JobController@create')->middleware("checkPermission:can-view-appartment-types");
    Route::get('job/{id}', 'App\Http\Controllers\JobController@show')->middleware("checkPermission:can-view-appartment-types");
    Route::get('job-invoice-generate/{id}', 'App\Http\Controllers\JobController@generateInvoice')->middleware("checkPermission:can-view-appartment-types");
    Route::post('job', 'App\Http\Controllers\JobController@store')->middleware("checkPermission:can-view-appartment-types");
    Route::post('copyquote/{quoteId}', 'App\Http\Controllers\JobController@copyQuote')->middleware("checkPermission:can-view-appartment-types");
    Route::get('job/{id}/edit', 'App\Http\Controllers\JobController@edit')->middleware("checkPermission:can-view-appartment-types");
    Route::put('job/{id}', 'App\Http\Controllers\JobController@update')->middleware("checkPermission:can-view-appartment-types");
    Route::delete('job/{id}', 'App\Http\Controllers\JobController@destroy')->middleware("checkPermission:can-view-appartment-types");
    Route::get('job-initial-data/{clientId?}', 'App\Http\Controllers\JobController@initialData')->middleware("checkPermission:can-view-appartment-types");
    Route::get('job-assign/{jobId}/{ParentServiceID}', 'App\Http\Controllers\JobController@assignInitialData')->middleware("checkPermission:can-view-appartment-types");
    Route::get('job-request', 'App\Http\Controllers\JobController@RequestedJobs')->middleware("checkPermission:can-view-appartment-types");
    Route::put('job-assign', 'App\Http\Controllers\JobController@assignJob')->middleware("checkPermission:can-view-appartment-types");
    Route::put('job-schedule-dates', 'App\Http\Controllers\JobController@ScheduleDates')->middleware("checkPermission:can-view-appartment-types");
    // Route::put('job-service-price-update', 'App\Http\Controllers\JobController@JobServicePriceUpdate')->middleware("checkPermission:can-view-appartment-types");
    Route::get('job-cancel', 'App\Http\Controllers\JobController@CancelledJobs')->middleware("checkPermission:can-view-appartment-types");
    Route::put('unschedule-job-service/{jobId}/{ParentServiceId?}', 'App\Http\Controllers\JobController@unscheduleService')->middleware("checkPermission:can-view-appartment-types");
    Route::put('cancel-job-notes/{jobId}', 'App\Http\Controllers\JobController@cancelJobNotes')->middleware("checkPermission:can-view-appartment-types");
    Route::get('all-jobs-count', 'App\Http\Controllers\JobController@allJobsCount')->middleware("checkPermission:can-view-appartment-types");
    Route::post('send-quote-email/{id}', 'App\Http\Controllers\JobController@sendQuoteEmail')->middleware("checkPermission:can-view-appartment-types");
    Route::post('updatequotestatus/{id}/{status}', 'App\Http\Controllers\JobController@updateQuoteStatus')->middleware("checkPermission:can-view-appartment-types");
    Route::get('quote/{id}/edit', 'App\Http\Controllers\JobController@getQuoteById')->middleware("checkPermission:can-view-appartment-types");
    Route::get('quote-pdf/{id}/{type}', 'App\Http\Controllers\JobController@qoutePdf')->middleware("checkPermission:can-view-appartment-types");
    Route::post('isreadytobill/{id}/{status}', 'App\Http\Controllers\JobController@isReadyToBill')->middleware("checkPermission:can-view-appartment-types");

    Route::get('property-services/{propertyId}/{apartmentTypeId?}', 'App\Http\Controllers\JobController@propertyServices')->middleware("checkPermission:can-view-appartment-types");
    Route::post('file-upload', 'App\Http\Controllers\FileUpload@store')->middleware("checkPermission:can-view-appartment-types");

    Route::get('invoice', 'App\Http\Controllers\InvoiceController@index')->middleware("checkPermission:can-view-appartment-types");
    Route::get('invoice/{id}', 'App\Http\Controllers\InvoiceController@invoice')->middleware("checkPermission:can-view-appartment-types");
    Route::post('invoice-payment', 'App\Http\Controllers\InvoiceController@invoicePayment')->middleware("checkPermission:can-view-appartment-types");
    Route::put('edit-invoice/{id}', 'App\Http\Controllers\InvoiceController@editInvoice')->middleware("checkPermission:can-view-appartment-types");
    Route::get('download-invoice/{id}', 'App\Http\Controllers\InvoiceController@downloadInvoice')->middleware("checkPermission:can-view-appartment-types");
    Route::post('send-invoice-email/{id}', 'App\Http\Controllers\InvoiceController@sendInvoiceEmail')->middleware("checkPermission:can-view-appartment-types");
    Route::put('cancel-invoice/{id}', 'App\Http\Controllers\InvoiceController@cancelInvoice')->middleware("checkPermission:can-view-appartment-types");
    Route::get('cancelled-invoice', 'App\Http\Controllers\InvoiceController@getCancelledInvoices')->middleware("checkPermission:can-view-appartment-types");
    Route::put('change-invoice-date/{id}', 'App\Http\Controllers\InvoiceController@changeInvoiceDate')->middleware("checkPermission:can-view-appartment-types");
    Route::put('update-invoice-pdf/{id}', 'App\Http\Controllers\InvoiceController@updateInvoicePdf')->middleware("checkPermission:can-view-appartment-types");
    Route::put('update-invoice-total/{id}', 'App\Http\Controllers\InvoiceController@updateInvoiceTotal')->middleware("checkPermission:can-view-appartment-types");
    Route::get('payment-info/{id}/client/{clientId}', 'App\Http\Controllers\InvoiceController@getPaymentInformation')->middleware("checkPermission:can-view-appartment-types");
    Route::get('staff-emails/{clientId}', 'App\Http\Controllers\InvoiceController@getStaffEmails')->middleware("checkPermission:can-view-appartment-types");
    Route::post('update-transaction', 'App\Http\Controllers\InvoiceController@updateTransaction')->middleware("checkPermission:can-view-appartment-types");
    Route::get('pastdueinvoices', 'App\Http\Controllers\InvoiceController@pastDueInvoices')->middleware("checkPermission:can-view-appartment-types");
    Route::get('paymentwaitinginvoices', 'App\Http\Controllers\InvoiceController@paymentAwaitingInvoices')->middleware("checkPermission:can-view-appartment-types");


    Route::prefix('employee')->group(function () {
        Route::get('jobs', 'App\Http\Controllers\EmployeeJobController@index')->middleware("checkPermission:can-view-appartment-types");
        Route::get('requested-service/{id}', 'App\Http\Controllers\EmployeeJobController@requestedService')->middleware("checkPermission:can-view-appartment-types");
        Route::put('progress_report/{id}', 'App\Http\Controllers\EmployeeJobController@jobReport')->middleware("checkPermission:can-view-appartment-types");
        Route::put('employee-progress_report/{id}', 'App\Http\Controllers\EmployeeJobController@savejobReport')->middleware("checkPermission:can-view-appartment-types");
        Route::post('requested-service-clock-out/{id}', 'App\Http\Controllers\EmployeeJobController@clockOut')->middleware("checkPermission:can-view-appartment-types");
        Route::post('requested-service-clock-in/{id}', 'App\Http\Controllers\EmployeeJobController@clockIn')->middleware("checkPermission:can-view-appartment-types");
        Route::put('job-completed/{id}', 'App\Http\Controllers\EmployeeJobController@jobCompleted')->middleware("checkPermission:can-view-appartment-types");
        Route::get('job-progress/{id}', 'App\Http\Controllers\EmployeeJobController@jobProgress')->middleware("checkPermission:can-view-appartment-types");
        Route::get('employee-job-progress/{serviceId}/{empId}', 'App\Http\Controllers\EmployeeJobController@employeejobProgress')->middleware("checkPermission:can-view-appartment-types");
        Route::get('time-sheet/{empId?}', 'App\Http\Controllers\EmployeeJobController@timesheet')->middleware("checkPermission:can-view-appartment-types");
        Route::get('edit-time-sheet/{id}', 'App\Http\Controllers\EmployeeJobController@editTimeSheet')->middleware("checkPermission:can-view-appartment-types");
        Route::put('update-time-sheet/{id}', 'App\Http\Controllers\EmployeeJobController@updateTimeSheet')->middleware("checkPermission:can-view-appartment-types");
        Route::get('get-notes-attachment/{id}', 'App\Http\Controllers\EmployeeJobController@getNotesAttachment')->middleware("checkPermission:can-view-appartment-types");
    });


    Route::get('role-permissions', 'App\Http\Controllers\RolePermissionController@index')->middleware("checkPermission:can-view-appartment-types");
    Route::get('role-permissions/{id}/edit', 'App\Http\Controllers\RolePermissionController@edit')->middleware("checkPermission:can-view-appartment-types");
    Route::post('role-permissions/{id}', 'App\Http\Controllers\RolePermissionController@store')->middleware("checkPermission:can-view-appartment-types");
    Route::delete('role-permissions/{id}/{permId}', 'App\Http\Controllers\RolePermissionController@delete')->middleware("checkPermission:can-view-appartment-types");
    Route::get('role-permissions/get/menu', 'App\Http\Controllers\RolePermissionController@getMenue');

    Route::get('permissions', 'App\Http\Controllers\PermissionController@index')->middleware("checkPermission:can-view-appartment-types");
    Route::post('permission', 'App\Http\Controllers\PermissionController@store')->middleware("checkPermission:can-view-appartment-types");
    Route::get('permission/{id}/edit', 'App\Http\Controllers\PermissionController@edit')->middleware("checkPermission:can-view-appartment-types");
    Route::put('permission/{id}', 'App\Http\Controllers\PermissionController@update')->middleware("checkPermission:can-view-appartment-types");
    Route::delete('permission/{id}', 'App\Http\Controllers\PermissionController@destroy')->middleware("checkPermission:can-view-appartment-types");

    //states and cities
    Route::get('city-state', 'App\Http\Controllers\CityStateController@index')->middleware("checkPermission:can-view-appartment-types");
    Route::get('state/{id}', 'App\Http\Controllers\CityStateController@show')->middleware("checkPermission:can-view-appartment-types");
    //client properties for add job
    Route::get('client-properties/{id}', 'App\Http\Controllers\JobController@properties')->middleware("checkPermission:can-view-appartment-types");
    //all users
    Route::get('users', 'App\Http\Controllers\UserController@index')->middleware("checkPermission:can-view-appartment-types");
    Route::get('user/{id}/edit', 'App\Http\Controllers\UserController@edit')->middleware("checkPermission:can-view-appartment-types");
    Route::get('user/create', 'App\Http\Controllers\UserController@create')->middleware("checkPermission:can-view-appartment-types");
    Route::post('user', 'App\Http\Controllers\UserController@store')->middleware("checkPermission:can-view-appartment-types");
    Route::put('user/{id}', 'App\Http\Controllers\UserController@update')->middleware("checkPermission:can-view-appartment-types");
    Route::put('user/{id}/status', 'App\Http\Controllers\UserController@updateStatus')->middleware("checkPermission:can-view-appartment-types");
    Route::delete('user/{id}', 'App\Http\Controllers\UserController@destroy')->middleware("checkPermission:can-view-appartment-types");
    Route::post('updateuserstatus/{status}/{id}', 'App\Http\Controllers\UserController@updateUserStatus')->middleware("checkPermission:can-view-appartment-types");
    //all roles
    Route::get('roles', 'App\Http\Controllers\RoleController@index')->middleware("checkPermission:can-view-appartment-types");
    Route::post('role', 'App\Http\Controllers\RoleController@store')->middleware("checkPermission:can-view-appartment-types");
    Route::get('role/{id}/edit', 'App\Http\Controllers\RoleController@edit')->middleware("checkPermission:can-view-appartment-types");
    Route::put('role/{id}', 'App\Http\Controllers\RoleController@update')->middleware("checkPermission:can-view-appartment-types");
    Route::delete('role/{id}', 'App\Http\Controllers\RoleController@destroy')->middleware("checkPermission:can-view-appartment-types");

    Route::get('subService-prices', 'App\Http\Controllers\SubCategoryPriceController@editPrice')->middleware("checkPermission:can-view-appartment-types");
    Route::put('subService-prices', 'App\Http\Controllers\SubCategoryPriceController@store')->middleware("checkPermission:can-view-appartment-types");
    Route::get('payroll-prices/{id}', 'App\Http\Controllers\PayrollController@editDepartmentPayrollPrice')->middleware("checkPermission:can-view-appartment-types");
    Route::put('payroll-prices', 'App\Http\Controllers\PayrollController@store')->middleware("checkPermission:can-view-appartment-types");

    Route::get('payroll/{id}', 'App\Http\Controllers\PayrollController@index')->middleware("checkPermission:can-view-appartment-types");
    Route::put('management_extra', 'App\Http\Controllers\PayrollController@addManagementExtra')->middleware("checkPermission:can-view-appartment-types");
    Route::get('management_extra/{job_id}/{employee_id}', 'App\Http\Controllers\PayrollController@getManagementExtra')->middleware("checkPermission:can-view-appartment-types");
    Route::put('paid_payroll', 'App\Http\Controllers\PayrollController@addPaidPayroll')->middleware("checkPermission:can-view-appartment-types");
    Route::get('service-subcategory/{id}', 'App\Http\Controllers\PayrollController@getServiceSubCategories')->middleware("checkPermission:can-view-appartment-types");

    Route::get('settings', "App\Http\Controllers\SettingController@index")->middleware("checkPermission:can-view-appartment-types");
    Route::post('settings/store', 'App\Http\Controllers\SettingController@store')->middleware("checkPermission:can-view-appartment-types");

    // client quotes
    Route::get('client-quote/{id?}', 'App\Http\Controllers\ClientController@clientQuote')->middleware("checkPermission:can-view-appartment-types");
    Route::get('client-quote-approve/{id}', 'App\Http\Controllers\PropertyController@clientQuoteApprove')->middleware("checkPermission:can-view-appartment-types");
    Route::get('quotelist', 'App\Http\Controllers\ClientController@getQuoteList')->middleware("checkPermission:can-view-appartment-types");

    Route::get('refinish-color', 'App\Http\Controllers\RefinishColorController@index')->middleware("checkPermission:can-view-appartment-types");
    Route::post('refinish-color', 'App\Http\Controllers\RefinishColorController@store')->middleware("checkPermission:can-view-appartment-types");
    Route::put('refinish-color/{id}', 'App\Http\Controllers\RefinishColorController@update')->middleware("checkPermission:can-view-appartment-types");
    Route::get('refinish-color/{id}/edit', 'App\Http\Controllers\RefinishColorController@edit')->middleware("checkPermission:can-view-appartment-types");
    Route::delete('refinish-color/{id}', 'App\Http\Controllers\RefinishColorController@destroy')->middleware("checkPermission:can-view-appartment-types");

    Route::get('multispect-color', 'App\Http\Controllers\MultiSpectColorController@index')->middleware("checkPermission:can-view-appartment-types");
    Route::post('multispect-color', 'App\Http\Controllers\MultiSpectColorController@store')->middleware("checkPermission:can-view-appartment-types");
    Route::put('multispect-color/{id}', 'App\Http\Controllers\MultiSpectColorController@update')->middleware("checkPermission:can-view-appartment-types");
    Route::get('multispect-color/{id}/edit', 'App\Http\Controllers\MultiSpectColorController@edit')->middleware("checkPermission:can-view-appartment-types");
    Route::delete('multispect-color/{id}', 'App\Http\Controllers\MultiSpectColorController@destroy')->middleware("checkPermission:can-view-appartment-types");

    Route::get('client-refinish-spec', 'App\Http\Controllers\RefinishSpecController@create')->middleware("checkPermission:can-view-appartment-types");
    Route::get('client-refinish-spec/{id}/edit', 'App\Http\Controllers\RefinishSpecController@edit')->middleware("checkPermission:can-view-appartment-types");
    Route::post('client-refinish-spec', 'App\Http\Controllers\RefinishSpecController@store')->middleware("checkPermission:can-view-appartment-types");

    Route::get('yearly-increment/{id}', 'App\Http\Controllers\PriceIncrementController@index')->middleware("checkPermission:can-view-appartment-types");
    Route::post('yearly-increment', 'App\Http\Controllers\PriceIncrementController@store')->middleware("checkPermission:can-view-appartment-types");
    Route::get('yearly-increment/{id}/edit', 'App\Http\Controllers\PriceIncrementController@edit')->middleware("checkPermission:can-view-appartment-types");
    Route::put('yearly-increment/{id}', 'App\Http\Controllers\PriceIncrementController@update')->middleware("checkPermission:can-view-appartment-types");
    Route::delete('yearly-increment/{id}', 'App\Http\Controllers\PriceIncrementController@destroy')->middleware("checkPermission:can-view-appartment-types");
    Route::get('services', 'App\Http\Controllers\PriceIncrementController@getServices')->middleware("checkPermission:can-view-appartment-types");
    Route::get('get-property-info/{id}', 'App\Http\Controllers\PriceIncrementController@getPropertyInfo')->middleware("checkPermission:can-view-appartment-types");

    // Route::get('service-prices', 'App\Http\Controllers\SubCategoryPriceController@getClientPrice')->middleware("checkPermission:can-view-appartment-types");
    // Route::put('service-prices', 'App\Http\Controllers\SubCategorclient-pricing-viewyPriceController@clientPricestore')->middleware("checkPermission:can-view-appartment-types");
    // Route::get('client-property-service-pricing/{id}/edit', 'App\Http\Controllers\ClientPricingController@editClientPrice')->middleware("checkPermission:can-view-appartment-types");
    Route::get('client-pricing-view/{id}', 'App\Http\Controllers\ClientPricingController@getClientPrice')->middleware("checkPermission:can-view-appartment-types");
    Route::get('copy-pricing/{id}', 'App\Http\Controllers\ClientPricingController@copyPrices')->middleware("checkPermission:can-view-appartment-types");
    Route::get('get-price-notes/{serviceId}', 'App\Http\Controllers\ClientPriceNotesController@getClientPrice')->middleware("checkPermission:can-view-appartment-types");
    Route::post('get-price-notes', 'App\Http\Controllers\ClientPriceNotesController@clientPricestore')->middleware("checkPermission:can-view-appartment-types");
    Route::delete('get-price-notes-delete/{id}', 'App\Http\Controllers\ClientPriceNotesController@clientPricesNoteDelete')->middleware("checkPermission:can-view-appartment-types");
    Route::get('get-property-price-notes/{id}/{serviceId}', 'App\Http\Controllers\ClientPriceNotesController@getClientPropertyPrice')->middleware("checkPermission:can-view-appartment-types");
    Route::post('get-property-price-notes/{id}', 'App\Http\Controllers\ClientPriceNotesController@clientPropertyPricestore')->middleware("checkPermission:can-view-appartment-types");
    Route::get('property-service-notes/{id}/{serviceId}', 'App\Http\Controllers\ClientPriceNotesController@getPropertyNotes')->middleware("checkPermission:can-view-appartment-types");

    Route::get('property/{propertyId}/staff', 'App\Http\Controllers\PropertyStaffController@index')->middleware("checkPermission:can-view-appartment-types");
    Route::get('staff/roles/{clientId}/{staffId}/{propertyId}', 'App\Http\Controllers\PropertyStaffController@roles')->middleware("checkPermission:can-view-appartment-types");
    Route::post('property/{propertyId}/staff', 'App\Http\Controllers\PropertyStaffController@store')->middleware("checkPermission:can-view-appartment-types");
    Route::get('property/staff/{id}', 'App\Http\Controllers\PropertyStaffController@get')->middleware("checkPermission:can-view-appartment-types");
    Route::put('property/{propertyId}/staff/{id}', 'App\Http\Controllers\PropertyStaffController@update')->middleware("checkPermission:can-view-appartment-types");
    Route::get('staff/{clientId}', 'App\Http\Controllers\PropertyStaffController@edit')->middleware("checkPermission:can-view-appartment-types");
    Route::put('staff/assign/{propertyId}', 'App\Http\Controllers\PropertyStaffController@assign')->middleware("checkPermission:can-view-appartment-types");
    Route::delete('staff/{staffId}', 'App\Http\Controllers\PropertyStaffController@destroy')->middleware("checkPermission:can-view-appartment-types");

    //staff roles global
    Route::get('staffrolesglobal', 'App\Http\Controllers\StaffRoleController@staffRolesList')->middleware("checkPermission:can-view-appartment-types");
    Route::post('createstaffrole', 'App\Http\Controllers\StaffRoleController@createRole')->middleware("checkPermission:can-view-appartment-types");
    Route::get('staffrole/{id}/edit', 'App\Http\Controllers\StaffRoleController@editStaffRole')->middleware("checkPermission:can-view-appartment-types");
    Route::put('updatestaffrole/{id}', 'App\Http\Controllers\StaffRoleController@updateStaffRole')->middleware("checkPermission:can-view-appartment-types");
    Route::put('convertroletoglobal/{id}', 'App\Http\Controllers\StaffRoleController@convertRoleAsGlobal')->middleware("checkPermission:can-view-appartment-types");
    Route::put('toggleStaffRoleLevel/{id}', 'App\Http\Controllers\StaffRoleController@toggleStaffRoleLevel')->middleware("checkPermission:can-view-appartment-types");
    Route::delete('deletestaffrole/{id}', 'App\Http\Controllers\StaffRoleController@deleteStaffRole')->middleware("checkPermission:can-view-appartment-types");
    Route::post('updaterolestatus/{status}/{id}', 'App\Http\Controllers\StaffRoleController@updateRoleStatus')->middleware("checkPermission:can-view-appartment-types");
});
