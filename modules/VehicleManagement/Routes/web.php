<?php

use Illuminate\Support\Facades\Route;
use Modules\VehicleManagement\Http\Controllers\PickupAndDropController;
use Modules\VehicleManagement\Http\Controllers\VehicleRequisitionController;
use Modules\VehicleManagement\Http\Controllers\VehicleRouteDetailController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::prefix('admin/vehicle')->as('admin.vehicle.')->group(function () {

    // type
    Route::resource('/type', 'VehicleTypeController')->except(['show']);
    //document type
    Route::resource('/document-type', 'DocumentTypeController')->except(['show'])->parameter('document-type', 'document_type');
    //division
    Route::resource('/division', 'VehicleDivisionController')->except(['show']);
    //vehicle routes
    Route::prefix('route-detail')->as('route-detail.')->group(function () {
        Route::resource('/', 'VehicleRouteDetailController')->except(['show'])->parameter('', 'route_detail');
        Route::get('/{route_detail}', [VehicleRouteDetailController::class, 'getRouteByID'])->name('detail');
    });
    //pickup and drop requisition
    Route::prefix('pick-drop')->as('pick-drop.')->group(function () {
        Route::resource('/', 'PickupAndDropController')->except(['show'])->parameter('', 'pick_drop');
        Route::post('{pick_drop}/status-update', [PickupAndDropController::class, 'statusUpdate'])->name('status-update');
    });
    // rta-office
    Route::resource('/rta-office', 'RTAOfficeController')->except(['show'])->parameters(['rta-office' => 'office']);
    // vehicle
    Route::resource('/', 'VehicleController')->except(['show'])->parameter('', 'vehicle');

    // legal-document
    Route::prefix('legal-document')->as('legal-document.')->group(function () {
        Route::resource('/', 'LegalDocumentationController')->except(['show'])->parameter('', 'legal_document');
    });

    // ownership
    Route::prefix('ownership')->as('ownership.')->group(function () {
        // type
        Route::resource('/type', 'VehicleOwnershipTypeController')->except(['show']);
    });

    // requisition
    Route::prefix('requisition')->as('requisition.')->group(function () {
        // type
        Route::resource('/type', 'VehicleRequisitionTypeController')->except(['show']);
        // purpose
        Route::resource('/purpose', 'VehicleRequisitionPurposeController')->except(['show']);
        //vehicle requisition
        Route::resource('/', 'VehicleRequisitionController')->except(['show'])->parameter('', 'requisition');
        Route::post('{requisition}/status-update', [VehicleRequisitionController::class, 'statusUpdate'])->name('status-update');
    });

    // insurance
    Route::prefix('insurance')->as('insurance.')->group(function () {
        // company
        Route::resource('/company', 'VehicleInsuranceCompanyController')->except(['show']);
        // recurring-period
        Route::resource('/recurring-period', 'VehicleInsuranceRecurringPeriodController')->except(['show']);
        // insurance
        Route::resource('/', 'InsuranceController')->except(['show'])->parameter('', 'insurance');
    });
});
