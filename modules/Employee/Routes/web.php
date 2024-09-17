<?php

use Illuminate\Support\Facades\Route;
use Modules\Employee\Http\Controllers\DepartmentController;
use Modules\Employee\Http\Controllers\DriverController;
use Modules\Employee\Http\Controllers\DriverPerformanceController;
use Modules\Employee\Http\Controllers\EmployeeController;
use Modules\Employee\Http\Controllers\LicenseTypeController;
use Modules\Employee\Http\Controllers\PositionController;

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

Route::prefix('admin')->as('admin.')->group(function () {

    Route::prefix('/position')->as('position.')->group(function () {
        Route::resource('/', PositionController::class)->except(['show'])->parameter('', 'position');
    });

    Route::prefix('/department')->as('department.')->group(function () {
        Route::resource('/', DepartmentController::class)->except(['show'])->parameter('', 'department');
    });

    Route::prefix('/license-type')->as('license_type.')->group(function () {
        Route::resource('/', LicenseTypeController::class)->except(['show'])->parameter('', 'license_type');
    });

    Route::prefix('/employee')->as('employee.')->group(function () {
        Route::resource('/', EmployeeController::class)->except(['show'])->parameter('', 'employee');
    });

    Route::prefix('/driver')->as('driver.')->group(function () {
        Route::prefix('/performance')->as('performance.')->group(function () {
            Route::resource('/', DriverPerformanceController::class)->except(['show'])->parameter('', 'performance');
        });

        Route::resource('/', DriverController::class)->except(['show'])->parameter('', 'driver');
    });
});
