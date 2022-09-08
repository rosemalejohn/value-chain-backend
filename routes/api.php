<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ManualController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskManualController;
use App\Http\Controllers\TaskMeasurementController;
use App\Http\Controllers\TaskStatusController;
use App\Http\Controllers\UserController;
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

Route::controller(AuthController::class)->group(function () {
    Route::post('auth/login', 'login')->name('auth.login');
    Route::get('auth/user', 'user')->name('auth.user');
    Route::post('auth/logout', 'logout')->name('auth.logout');
});

Route::middleware('auth')->group(function () {
    Route::controller(UserController::class)->group(function () {
        Route::get('users', 'index')->name('tasks.index');
        Route::post('users', 'store')->name('tasks.store');
        Route::put('users/{user}', 'update')->name('tasks.update');
    });

    Route::controller(TaskController::class)->group(function () {
        Route::get('tasks', 'index')->name('tasks.index');
        Route::post('tasks', 'store')->name('tasks.store');
        Route::get('tasks/{task}', 'show')->name('tasks.show');
        Route::put('tasks/{task}', 'update')->name('tasks.update');
    });

    Route::controller(TaskMeasurementController::class)->group(function () {
        Route::post('tasks/{task}/measurements', 'store')->name('tasks.measurements.store');
        Route::put('tasks/{task}/measurements/{task_measurement}', 'update')->name('tasks.measurements.update');
        Route::delete('tasks/{task}/measurements/{task_measurement}', 'destroy')->name('tasks.measurement.destroy');
    });

    Route::controller(TaskManualController::class)->group(function () {
        Route::post('tasks/{task}/manuals', 'store')->name('tasks.manuals.store');
        Route::delete('tasks/{task}/manuals/{task_manual}', 'destroy')->name('tasks.manauls.destroy');
    });

    Route::controller(ManualController::class)->group(function () {
        Route::get('manuals', 'index')->name('manuals.index');
        Route::post('manuals', 'store')->name('manuals.store');
        Route::put('manuals/{manual}', 'update')->name('manuals.update');
    });

    Route::put('tasks/{task}/status', TaskStatusController::class)->name('task.status.update');
});
