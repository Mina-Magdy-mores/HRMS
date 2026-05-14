<?php

use App\Http\Controllers\Admin\AdminPanelSettingController;
use App\Http\Controllers\Admin\BrancheController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FinanceCalendarController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\ShiftsTypeController;
use Illuminate\Support\Facades\Route;


define('PAGEINATION_COUNTER', 11);
// Admin
Route::prefix('/admin')->name('admin.')->group(function () {

    // logged in routes
    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

        // general settings
        Route::get('/general-settings', [AdminPanelSettingController::class, 'index'])->name('general-settings');
        Route::put('/general-settings/{adminPanelSetting}', [AdminPanelSettingController::class, 'update'])->name('general-settings.update');

        // finance calendar
        Route::resource('financeCalendars', FinanceCalendarController::class);
        Route::get('financeCalendars/{financeCalendar}/months', [FinanceCalendarController::class, 'showMonths'])->name('financeCalendars.months');

        // branches routes
        Route::get('/branches', [BrancheController::class, 'index'])->name('branches.index');
        Route::get('/branches/create', [BrancheController::class, 'create'])->name('branches.create');
        Route::post('/branches', [BrancheController::class, 'store'])->name('branches.store');
        Route::get('/branches/{branche}/edit', [BrancheController::class, 'edit'])->name('branches.edit');
        Route::put('/branches/{branche}', [BrancheController::class, 'update'])->name('branches.update');
        Route::delete('/branches/{branche}', [BrancheController::class, 'destroy'])->name('branches.destroy');


        
        // shifts-types routes
        Route::get('/shifts-types', [ShiftsTypeController::class, 'index'])->name('shifts-types.index');
        Route::get('/shifts-types/create', [ShiftsTypeController::class, 'create'])->name('shifts-types.create');
        Route::post('/shifts-types', [ShiftsTypeController::class, 'store'])->name('shifts-types.store');
        Route::get('/shifts-types/{shiftsType}/edit', [ShiftsTypeController::class, 'edit'])->name('shifts-types.edit');
        Route::put('/shifts-types/{shiftsType}', [ShiftsTypeController::class, 'update'])->name('shifts-types.update');
        Route::delete('/shifts-types/{shiftsType}', [ShiftsTypeController::class, 'destroy'])->name('shifts-types.destroy');

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
