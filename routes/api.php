<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Guest\TaskController as GuestTaskController;
use App\Http\Controllers\Guest\UserController as GuestUserController;
use App\Http\Controllers\ManualController;
use App\Http\Controllers\MeasurementController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskAbTestController;
use App\Http\Controllers\TaskAttachmentController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskManualController;
use App\Http\Controllers\TaskMeasurementController;
use App\Http\Controllers\TaskStatusController;
use App\Http\Controllers\TaskSubtaskController;
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

Route::middleware('guest')->prefix('guest')->group(function () {
    Route::controller(GuestTaskController::class)->group(function () {
        Route::post('tasks', 'store')->name('guest.tasks.store');
    });

    Route::controller(GuestUserController::class)->group(function () {
        Route::get('users', 'index')->name('guest.users.index');
    });
});

Route::middleware('auth')->group(function () {
    Route::controller(ProfileController::class)->group(function () {
        Route::post('profile', 'update')->name('profile.update');
    });

    Route::controller(UserController::class)->group(function () {
        Route::get('users', 'index')->name('users.index');
        Route::post('users', 'store')->name('users.store');
        Route::put('users/{user}', 'update')->name('users.update');
        Route::delete('users/{user}', 'destroy')->name('users.destroy');
    });

    Route::controller(TaskController::class)->group(function () {
        Route::get('tasks', 'index')->name('tasks.index');
        Route::post('tasks', 'store')->name('tasks.store');
        Route::get('tasks/{task}', 'show')->name('tasks.show');
        Route::put('tasks/{task}', 'update')->name('tasks.update');
        Route::delete('tasks/{task}', 'destroy')->name('tasks.destroy');
        Route::post('tasks/{task}/deploy', 'deploy')->name('tasks.deploy');
    });

    Route::controller(TaskMeasurementController::class)->group(function () {
        Route::post('tasks/{task}/measurements', 'store')->name('tasks.measurements.store');
        Route::delete('tasks/{task}/measurements/{task_measurement}', 'destroy')->name('tasks.measurement.destroy');
    });

    Route::controller(TaskManualController::class)->group(function () {
        Route::post('tasks/{task}/manuals', 'store')->name('tasks.manuals.store');
        Route::delete('tasks/{task}/manuals/{task_manual}', 'destroy')->name('tasks.manauls.destroy');
    });

    Route::controller(TaskSubtaskController::class)->group(function () {
        Route::post('tasks/{task}/subtasks', 'store')->name('tasks.subtasks.store');
        Route::post('tasks/{task}/subtasks/{subtask}/complete', 'complete')->name('tasks.subtasks.complete');
    });

    Route::controller(TaskAttachmentController::class)->group(function () {
        Route::post('tasks/{task}/attachments', 'store')->name('tasks.attachments.store');
        Route::delete('tasks/{task}/attachments/{media}', 'destroy')
            ->name('tasks.attachments.destroy');
    });

    Route::controller(TaskAbTestController::class)->group(function () {
        Route::post('tasks/{task}/abtests', 'store')->name('tasks.abtests.store');
        Route::put('tasks/{task}/abtests/{task_abtest_id}', 'update')->name('tasks.abtests.update');
        Route::delete('tasks/{task}/abtests/{task_abtest_id}', 'destroy')->name('tasks.abtests.destroy');
    });

    Route::controller(ManualController::class)->group(function () {
        Route::get('manuals', 'index')->name('manuals.index');
        Route::post('manuals', 'store')->name('manuals.store');
        Route::put('manuals/{manual}', 'update')->name('manuals.update');
        Route::delete('manuals/{manual}', 'destroy')->name('manuals.destroy');
    });

    Route::controller(MeasurementController::class)->group(function () {
        Route::get('measurements', 'index')->name('measurements.index');
    });

    Route::put('tasks/{task}/status', TaskStatusController::class)->name('task.status.update');
});
