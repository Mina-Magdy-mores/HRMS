<?php

use App\Http\Controllers\Admin\AdminPanelSettingController;
use App\Http\Controllers\Admin\AllowanceTypeController;
use App\Http\Controllers\Admin\BloodGroupController;
use App\Http\Controllers\Admin\BonusController;
use App\Http\Controllers\Admin\BrancheController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DeductionTypeController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\FinanceCalendarController;
use App\Http\Controllers\Admin\GovernorateController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\JobsCategoryController;
use App\Http\Controllers\Admin\MainSalaryEmployeeDeductionController;
use App\Http\Controllers\Admin\MainSalaryRecordController;
use App\Http\Controllers\Admin\NationalityController;
use App\Http\Controllers\Admin\OccasionController;
use App\Http\Controllers\Admin\QualificationController;
use App\Http\Controllers\Admin\ReligionController;
use App\Http\Controllers\Admin\ResignationController;
use App\Http\Controllers\Admin\ShiftsTypeController;
use Illuminate\Support\Facades\Route;


define('PAGEINATION_COUNTER', 3);
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

        // blood-groups routes
        Route::get('/blood-groups', [BloodGroupController::class, 'index'])->name('blood-groups.index');
        Route::get('/blood-groups/create', [BloodGroupController::class, 'create'])->name('blood-groups.create');
        Route::post('/blood-groups', [BloodGroupController::class, 'store'])->name('blood-groups.store');
        Route::get('/blood-groups/{id}/edit', [BloodGroupController::class, 'edit'])->name('blood-groups.edit');
        Route::put('/blood-groups/{id}', [BloodGroupController::class, 'update'])->name('blood-groups.update');
        Route::delete('/blood-groups/{id}', [BloodGroupController::class, 'destroy'])->name('blood-groups.destroy');

        // countries routes
        Route::get('/countries', [CountryController::class, 'index'])->name('countries.index');
        Route::get('/countries/create', [CountryController::class, 'create'])->name('countries.create');
        Route::post('/countries', [CountryController::class, 'store'])->name('countries.store');
        Route::get('/countries/{id}/edit', [CountryController::class, 'edit'])->name('countries.edit');
        Route::put('/countries/{id}', [CountryController::class, 'update'])->name('countries.update');
        Route::delete('/countries/{id}', [CountryController::class, 'destroy'])->name('countries.destroy');

        // governorates routes
        Route::get('/governorates', [GovernorateController::class, 'index'])->name('governorates.index');
        Route::get('/governorates/create', [GovernorateController::class, 'create'])->name('governorates.create');
        Route::post('/governorates', [GovernorateController::class, 'store'])->name('governorates.store');
        Route::get('/governorates/{id}/edit', [GovernorateController::class, 'edit'])->name('governorates.edit');
        Route::put('/governorates/{id}', [GovernorateController::class, 'update'])->name('governorates.update');
        Route::delete('/governorates/{id}', [GovernorateController::class, 'destroy'])->name('governorates.destroy');

        // cities routes
        Route::get('/cities', [CityController::class, 'index'])->name('cities.index');
        Route::get('/cities/create', [CityController::class, 'create'])->name('cities.create');
        Route::post('/cities', [CityController::class, 'store'])->name('cities.store');
        Route::get('/cities/{id}/edit', [CityController::class, 'edit'])->name('cities.edit');
        Route::put('/cities/{id}', [CityController::class, 'update'])->name('cities.update');
        Route::delete('/cities/{id}', [CityController::class, 'destroy'])->name('cities.destroy');

        //employees routs
        Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
        Route::get('/employees/{id}/details', [EmployeeController::class, 'getDetails'])->name('employees.details');
        Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employees.create');
        Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
        Route::get('/employees/{id}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
        Route::put('/employees/{id}', [EmployeeController::class, 'update'])->name('employees.update');
        Route::delete('/employees/{id}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
        Route::post('/employees/governorate-list', [EmployeeController::class, 'getGovernorateList'])->name('employees.governorate-list');
        Route::post('/employees/cities-list', [EmployeeController::class, 'getCitiesList'])->name('employees.cities-list');
        Route::post('/employees/search', [EmployeeController::class, 'search'])->name('employees.search');
        Route::get('/employees/{id}/download/{type}/{file?}', [EmployeeController::class, 'download'])->name('employees.download');
        Route::post('/employees/{id}/add-file', [EmployeeController::class, 'addFile'])->name('employees.add-file');
        Route::get('/employees/files/{id}/{employee_id}', [EmployeeController::class, 'deleteFile'])->name('employees.delete');

        // AllowanceType routes
        Route::get('/allowance-types', [AllowanceTypeController::class, 'index'])->name('allowance-types.index');
        Route::get('/allowance-types/create', [AllowanceTypeController::class, 'create'])->name('allowance-types.create');
        Route::post('/allowance-types', [AllowanceTypeController::class, 'store'])->name('allowance-types.store');
        Route::get('/allowance-types/{id}/edit', [AllowanceTypeController::class, 'edit'])->name('allowance-types.edit');
        Route::put('/allowance-types/{id}', [AllowanceTypeController::class, 'update'])->name('allowance-types.update');
        Route::delete('/allowance-types/{id}', [AllowanceTypeController::class, 'destroy'])->name('allowance-types.destroy');

        // DeductionType routes
        Route::get('/deduction-types', [DeductionTypeController::class, 'index'])->name('deduction-types.index');
        Route::get('/deduction-types/create', [DeductionTypeController::class, 'create'])->name('deduction-types.create');
        Route::post('/deduction-types', [DeductionTypeController::class, 'store'])->name('deduction-types.store');
        Route::get('/deduction-types/{id}/edit', [DeductionTypeController::class, 'edit'])->name('deduction-types.edit');
        Route::put('/deduction-types/{id}', [DeductionTypeController::class, 'update'])->name('deduction-types.update');
        Route::delete('/deduction-types/{id}', [DeductionTypeController::class, 'destroy'])->name('deduction-types.destroy');

        //Bonus routes
        Route::get('/bonuses', [BonusController::class, 'index'])->name('bonuses.index');
        Route::get('/bonuses/create', [BonusController::class, 'create'])->name('bonuses.create');
        Route::post('/bonuses', [BonusController::class, 'store'])->name('bonuses.store');
        Route::get('/bonuses/{id}/edit', [BonusController::class, 'edit'])->name('bonuses.edit');
        Route::put('/bonuses/{id}', [BonusController::class, 'update'])->name('bonuses.update');
        Route::delete('/bonuses/{id}', [BonusController::class, 'destroy'])->name('bonuses.destroy');

        //MainSalaryRecord routes
        Route::get('/main-salary-records', [MainSalaryRecordController::class, 'index'])->name('main-salary-records.index');
        Route::post('/main-salary-records/open-month/{id}', [MainSalaryRecordController::class, 'openMonth'])->name('main-salary-records.open-month');
        Route::get('/main-salary-records/create', [MainSalaryRecordController::class, 'create'])->name('main-salary-records.create');
        Route::post('/main-salary-records', [MainSalaryRecordController::class, 'store'])->name('main-salary-records.store');
        Route::get('/main-salary-records/{id}/edit', [MainSalaryRecordController::class, 'edit'])->name('main-salary-records.edit');
        Route::put('/main-salary-records/{id}', [MainSalaryRecordController::class, 'update'])->name('main-salary-records.update');
        Route::delete('/main-salary-records/{id}', [MainSalaryRecordController::class, 'destroy'])->name('main-salary-records.destroy');
        Route::post('/main-salary-records/load-open-month', [MainSalaryRecordController::class, 'loadOpenMonth'])->name('main-salary-records.load-open-month');

        //main_salary_employee_deductions
        Route::get('/main-salary-employee-deductions', [MainSalaryEmployeeDeductionController::class, 'index'])->name('main-salary-employee-deductions.index');
        Route::get('/main-salary-employee-deductions/create', [MainSalaryEmployeeDeductionController::class, 'create'])->name('main-salary-employee-deductions.create');
        Route::post('/main-salary-employee-deductions', [MainSalaryEmployeeDeductionController::class, 'store'])->name('main-salary-employee-deductions.store');
        Route::get('/main-salary-employee-deductions/{id}/edit', [MainSalaryEmployeeDeductionController::class, 'edit'])->name('main-salary-employee-deductions.edit');
        Route::put('/main-salary-employee-deductions/{id}', [MainSalaryEmployeeDeductionController::class, 'update'])->name('main-salary-employee-deductions.update');
        Route::delete('/main-salary-employee-deductions/{id}', [MainSalaryEmployeeDeductionController::class, 'destroy'])->name('main-salary-employee-deductions.destroy');
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
