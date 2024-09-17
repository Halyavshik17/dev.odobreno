<?php

use Illuminate\Support\Facades\Route;
use Modules\Companies\Http\Controllers\CompaniesController;

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

Route::prefix('admin/companies')->as('admin.companies.')->group(function () {
    Route::resource('/', CompaniesController::class)->except(['show'])->parameter('', 'company');
    //Route::post('{company}/status-update', [CompaniesController::class, 'statusUpdate'])->name('status-update');
});