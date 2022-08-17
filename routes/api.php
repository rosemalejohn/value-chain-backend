<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
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
});
