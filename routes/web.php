<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataController;
use App\Http\Controllers\ExcelController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\DataLogController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
require __DIR__ . '/auth.php';
// admin routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/dashboard', 'index')->name('admin.dashboard');
    });
    Route::controller(UserController::class)->group(function () {
        Route::get('/users', 'index')->name('admin.users');
        Route::post('/create-user', 'store')->name('admin.user.create');
        Route::delete('/delete-user', 'destroy')->name('admin.delete.user');
        Route::get('/get-user', 'show')->name('admin.get.user');
    });

    Route::controller(DataController::class)->group(function () {
        Route::get('/create-data', 'create')->name('admin.create.data');
        Route::get('/validate-unique-id', 'validate_unique_id')->name('admin.validate_unique_id');
        Route::post('/create-data', 'store')->name('admin.store.data');
        Route::get('/edit-data/{data}', 'show')->name('admin.edit.data');
        Route::post('/update-data/{data}', 'update')->name('admin.update.data');
        Route::delete('/delete-data', 'destroy')->name('admin.delete.data');
        Route::get('/data',  'index')->name('admin.data');
        Route::get('/data/inprogress', 'inprogess_entry')->name('admin.inprogress.data');
        Route::get('/data/completed', 'complete_entry')->name('admin.complete.data');
        Route::get('/data/duplicate', 'duplicate_entry')->name('admin.duplicate.data');
        Route::get('/filter', 'filter_data')->name('admin.filter.data');
        Route::get('/view-data', 'view_data')->name('admin.view.data');
    });

    Route::controller(ExcelController::class)->group(function () {
        Route::post('/upload-excel', 'uploadExcel')->name('upload.excel');
        Route::get('/export-data', 'exportExcel')->name('admin.export.data');
        Route::get('/conditional-export-data', 'conditionalExportExcel')->name('admin.conditional.export.data');
    });
    Route::controller(DataLogController::class)->group(function () {
        Route::get('/data-log/{data}', 'getLog')->name('admin.get.data.log');
        Route::get('/view-log', 'show')->name('admin.view.log');
        Route::post('/update-log', 'update')->name('admin.update.log');
        Route::delete('/delete-log', 'destroy')->name('admin.delete.log');
    });
});
Route::middleware(['auth'])->group(function () {
    Route::controller(DataController::class)->group(function () {
        // Route::get('/data', 'inprogess_entry')->name('admin.data');
        Route::get('/search-data', 'search_data')->name('user.search.data');
        Route::post('/add-address', 'add_address')->name('user.add.address');
    });
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/user-dashboard', 'user_index')->name('user.dashboard');
    });
});
