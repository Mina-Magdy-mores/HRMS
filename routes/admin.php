<?php

use App\Http\Controllers\Admin\AdminPanelSettingController;
use App\Http\Controllers\Admin\BrancheController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\FinanceCalendarController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\JobsCategoryController;
use App\Http\Controllers\Admin\NationalityController;
use App\Http\Controllers\Admin\OccasionController;
use App\Http\Controllers\Admin\QualificationController;
use App\Http\Controllers\Admin\ReligionController;
use App\Http\Controllers\Admin\ResignationController;
use App\Http\Controllers\Admin\ShiftsTypeController;
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
        Route::post('/shifts-types/search', [ShiftsTypeController::class, 'search'])->name('shifts-types.search');

        // departments routes
        Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');
        Route::get('/departments/create', [DepartmentController::class, 'create'])->name('departments.create');
        Route::post('/departments', [DepartmentController::class, 'store'])->name('departments.store');
        Route::get('/departments/{shiftsType}/edit', [DepartmentController::class, 'edit'])->name('departments.edit');
        Route::put('/departments/{shiftsType}', [DepartmentController::class, 'update'])->name('departments.update');
        Route::delete('/departments/{shiftsType}', [DepartmentController::class, 'destroy'])->name('departments.destroy');

        // jobCategories routes
        Route::get('/jobCategories', [JobsCategoryController::class, 'index'])->name('jobCategories.index');
        Route::get('/jobCategories/create', [JobsCategoryController::class, 'create'])->name('jobCategories.create');
        Route::post('/jobCategories', [JobsCategoryController::class, 'store'])->name('jobCategories.store');
        Route::get('/jobCategories/{id}/edit', [JobsCategoryController::class, 'edit'])->name('jobCategories.edit');
        Route::put('/jobCategories/{id}', [JobsCategoryController::class, 'update'])->name('jobCategories.update');
        Route::delete('/jobCategories/{id}', [JobsCategoryController::class, 'destroy'])->name('jobCategories.destroy');

        // qualifications routes
        Route::get('/qualifications', [QualificationController::class, 'index'])->name('qualifications.index');
        Route::get('/qualifications/create', [QualificationController::class, 'create'])->name('qualifications.create');
        Route::post('/qualifications', [QualificationController::class, 'store'])->name('qualifications.store');
        Route::get('/qualifications/{id}/edit', [QualificationController::class, 'edit'])->name('qualifications.edit');
        Route::put('/qualifications/{id}', [QualificationController::class, 'update'])->name('qualifications.update');
        Route::delete('/qualifications/{id}', [QualificationController::class, 'destroy'])->name('qualifications.destroy');

        // occasions routes
        Route::get('/occasions', [OccasionController::class, 'index'])->name('occasions.index');
        Route::get('/occasions/create', [OccasionController::class, 'create'])->name('occasions.create');
        Route::post('/occasions', [OccasionController::class, 'store'])->name('occasions.store');
        Route::get('/occasions/{id}/edit', [OccasionController::class, 'edit'])->name('occasions.edit');
        Route::put('/occasions/{id}', [OccasionController::class, 'update'])->name('occasions.update');
        Route::delete('/occasions/{id}', [OccasionController::class, 'destroy'])->name('occasions.destroy');

        // Resignations routes
        Route::get('/resignations', [ResignationController::class, 'index'])->name('resignations.index');
        Route::get('/resignations/create', [ResignationController::class, 'create'])->name('resignations.create');
        Route::post('/resignations', [ResignationController::class, 'store'])->name('resignations.store');
        Route::get('/resignations/{id}/edit', [ResignationController::class, 'edit'])->name('resignations.edit');
        Route::put('/resignations/{id}', [ResignationController::class, 'update'])->name('resignations.update');
        Route::delete('/resignations/{id}', [ResignationController::class, 'destroy'])->name('resignations.destroy');

        // nationalities routes
        Route::get('/nationalities', [NationalityController::class, 'index'])->name('nationalities.index');
        Route::get('/nationalities/create', [NationalityController::class, 'create'])->name('nationalities.create');
        Route::post('/nationalities', [NationalityController::class, 'store'])->name('nationalities.store');
        Route::get('/nationalities/{id}/edit', [NationalityController::class, 'edit'])->name('nationalities.edit');
        Route::put('/nationalities/{id}', [NationalityController::class, 'update'])->name('nationalities.update');
        Route::delete('/nationalities/{id}', [NationalityController::class, 'destroy'])->name('nationalities.destroy');

        // religions routes
        Route::get('/religions', [ReligionController::class, 'index'])->name('religions.index');
        Route::get('/religions/create', [ReligionController::class, 'create'])->name('religions.create');
        Route::post('/religions', [ReligionController::class, 'store'])->name('religions.store');
        Route::get('/religions/{id}/edit', [ReligionController::class, 'edit'])->name('religions.edit');
        Route::put('/religions/{id}', [ReligionController::class, 'update'])->name('religions.update');
        Route::delete('/religions/{id}', [ReligionController::class, 'destroy'])->name('religions.destroy');
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
