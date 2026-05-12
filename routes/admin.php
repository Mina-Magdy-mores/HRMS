<?php

use App\Http\Controllers\Admin\Admin_panel_settingController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\Finance_calendaController;
use App\Http\Controllers\Admin\LoginController;
use Illuminate\Support\Facades\Route;

// Admin
Route::prefix('/admin')->name('admin.')->group(function () {

// logged in routes
    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

        // general settings
        Route::get('/general-settings', [Admin_panel_settingController::class, 'index'])->name('general-settings');
        Route::put('/general-settings/{admin_panel_setting}', [Admin_panel_settingController::class, 'update'])->name('general-settings.update');

        // finance calendar
        Route::resource('finance_calendars', Finance_calendaController::class);
    });

    // guest routes
    Route::middleware('guest:admin')->group(function () {
        Route::controller(LoginController::class)->group(function () {
            Route::get('/login', 'index')->name('index');
            Route::post('/login', 'login')->name('login');
        });
    });
});

Route::fallback(function () {
    return view('admin.errors.404');
});
