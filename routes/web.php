<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\SummaryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WorkerCostController;
use App\Http\Controllers\Admin\WorkTypeController;
use App\Http\Controllers\Auth\GoogleSocialiteController;
use App\Http\Controllers\TimeboxController;
use App\Http\Controllers\TimeboxDraftController;
use App\Http\Controllers\DetailController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [TimeboxController::class, 'index'])->name('home')->middleware('auth');

Route::get('auth/google', [GoogleSocialiteController::class, 'redirectToGoogle'])->name('google.auth');
Route::get('callback/google', [GoogleSocialiteController::class, 'handleCallback'])->name('google.callback');

Route::get('/details', [DetailController::class, 'details'])->name('details')->middleware('auth');
Route::post('/save-timebox/{timestampDate}', [TimeboxController::class, 'save'])->name('save.timebox')->middleware('auth');



Route::prefix('admin')->middleware('is_admin')->group(function () {
    Route::get('/migrate', function() {
        Artisan::call('migrate');
    });
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');
    Route::prefix('projects')->group(function () {
        Route::get('/', [ProjectController::class, 'index'])->name('admin.project.index');
        Route::get('/create', [ProjectController::class, 'create'])->name('admin.project.create');
        Route::post('/store', [ProjectController::class, 'store'])->name('admin.project.store');
        Route::delete('/{project}', [ProjectController::class, 'delete'])->name('admin.project.delete');
        Route::get('/{project}', [ProjectController::class, 'excelCreate'])->name('admin.project.excelCreate');
        Route::get('/{project}/export', [ProjectController::class, 'excelExport'])->name('admin.project.excelExport');
    });

    Route::prefix('work-types')->group(function () {
        Route::get('/', [WorkTypeController::class, 'index'])->name('admin.work_type.index');
        Route::get('/create', [WorkTypeController::class, 'create'])->name('admin.work_type.create');
        Route::post('/store', [WorkTypeController::class, 'store'])->name('admin.work_type.store');
        Route::delete('/{work_type}', [WorkTypeController::class, 'delete'])->name('admin.work_type.delete');
    });

    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('admin.user.index');
        Route::delete('/{user}', [UserController::class, 'delete'])->name('admin.user.delete');
        Route::get('/{user}', [UserController::class, 'excelCreate'])->name('admin.user.excelCreate');
        Route::get('/{user}/export', [UserController::class, 'excelExport'])->name('admin.user.excelExport');
        Route::get('/{user}/holiday', [UserController::class, 'excelHoliday'])->name('admin.user.excelHoliday');
     
    });

    Route::prefix('summary')->group(function () {
        Route::get('/users', [SummaryController::class, 'user'])->name('admin.summary.user');
        Route::get('/projects', [SummaryController::class, 'project'])->name('admin.summary.project');
    });
});


require __DIR__.'/auth.php';