<?php

use App\Http\Controllers\Admin\AttendanceDepartureController;
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
use App\Http\Controllers\Admin\MainSalaryEmployeeAbsenceController;
use App\Http\Controllers\Admin\MainSalaryEmployeeAdditionController;
use App\Http\Controllers\Admin\MainSalaryEmployeeAllowanceController;
use App\Http\Controllers\Admin\MainSalaryEmployeeDeductionController;
use App\Http\Controllers\Admin\MainSalaryEmployeeDeductionTypeController;
use App\Http\Controllers\Admin\MainSalaryEmployeeBonusController;
use App\Http\Controllers\Admin\MainSalaryEmployeeController;
use App\Http\Controllers\Admin\MainSalaryEmployeeLoanController;
use App\Http\Controllers\Admin\MainSalaryEmployeePLoanController;
use App\Http\Controllers\Admin\MainSalaryRecordController;
use App\Http\Controllers\Admin\NationalityController;
use App\Http\Controllers\Admin\OccasionController;
use App\Http\Controllers\Admin\QualificationController;
use App\Http\Controllers\Admin\ReligionController;
use App\Http\Controllers\Admin\ResignationController;
use App\Http\Controllers\Admin\ShiftsTypeController;
use Illuminate\Support\Facades\Route;


if (!defined('PAGEINATION_COUNTER')) {
    define('PAGEINATION_COUNTER', 3);
}
// Admin
Route::prefix('/admin')->name('admin.')->group(function () {

    // logged in routes
    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/logout', [LoginController::class, 'logout'])->name('logout');


        // general settings
        Route::get('/general-settings', [AdminPanelSettingController::class, 'index'])->name('general-settings');
        Route::put('/general-settings/{adminPanelSetting}', [AdminPanelSettingController::class, 'update'])->name('general-settings.update');
        Route::get('/general-settings/downloadImage/{id}', [AdminPanelSettingController::class, 'downloadImage'])->name('general-settings.downloadImage');

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
        Route::post('/employees/fixed-allowances', [EmployeeController::class, 'addFixedAllowances'])->name('employees.add-allowance');
        Route::post('/employees/fixed-allowances/delete', [EmployeeController::class, 'deleteFixedAllowance'])->name('employees.delete-allowance');
        Route::post('/employees/fixed-allowances/update', [EmployeeController::class, 'updateFixedAllowance'])->name('employees.update-allowance');

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
        Route::get('/main-salary-employee-deductions/{id}/show', [MainSalaryEmployeeDeductionController::class, 'show'])->name('main-salary-employee-deductions.show');
        Route::post('/main-salary-employee-deductions/ajax-check', [MainSalaryEmployeeDeductionController::class, 'ajaxCheck'])->name('main-salary-employee-deductions.ajax-check');
        Route::post('/main-salary-employee-deductions/store', [MainSalaryEmployeeDeductionController::class, 'store'])->name('main-salary-employee-deductions.store');
        Route::post('/main-salary-employee-deductions/ajax-search', [MainSalaryEmployeeDeductionController::class, 'ajaxSearch'])->name('main-salary-employee-deductions.ajax-search');
        Route::post('/main-salary-employee-deductions/destroy', [MainSalaryEmployeeDeductionController::class, 'destroy'])->name('main-salary-employee-deductions.destroy');
        Route::post('/main-salary-employee-deductions/edit', [MainSalaryEmployeeDeductionController::class, 'edit'])->name('main-salary-employee-deductions.edit');
        Route::put('/main-salary-employee-deductions', [MainSalaryEmployeeDeductionController::class, 'update'])->name('main-salary-employee-deductions.update');
        Route::post('/main-salary-employee-deductions/print-search', [MainSalaryEmployeeDeductionController::class, 'printSearch'])->name('main-salary-employee-deductions.print-search');


        //main_salary_employee_absences
        Route::get('/main-salary-employee-absences', [MainSalaryEmployeeAbsenceController::class, 'index'])->name('main-salary-employee-absences.index');
        Route::get('/main-salary-employee-absences/{id}/show', [MainSalaryEmployeeAbsenceController::class, 'show'])->name('main-salary-employee-absences.show');
        Route::post('/main-salary-employee-absences/ajax-check', [MainSalaryEmployeeAbsenceController::class, 'ajaxCheck'])->name('main-salary-employee-absences.ajax-check');
        Route::post('/main-salary-employee-absences/store', [MainSalaryEmployeeAbsenceController::class, 'store'])->name('main-salary-employee-absences.store');
        Route::post('/main-salary-employee-absences/ajax-search', [MainSalaryEmployeeAbsenceController::class, 'ajaxSearch'])->name('main-salary-employee-absences.ajax-search');
        Route::post('/main-salary-employee-absences/destroy', [MainSalaryEmployeeAbsenceController::class, 'destroy'])->name('main-salary-employee-absences.destroy');
        Route::post('/main-salary-employee-absences/edit', [MainSalaryEmployeeAbsenceController::class, 'edit'])->name('main-salary-employee-absences.edit');
        Route::put('/main-salary-employee-absences', [MainSalaryEmployeeAbsenceController::class, 'update'])->name('main-salary-employee-absences.update');
        Route::post('/main-salary-employee-absences/print-search', [MainSalaryEmployeeAbsenceController::class, 'printSearch'])->name('main-salary-employee-absences.print-search');

        //main_salary_employee_additions
        Route::get('/main-salary-employee-additions', [MainSalaryEmployeeAdditionController::class, 'index'])->name('main-salary-employee-additions.index');
        Route::get('/main-salary-employee-additions/{id}/show', [MainSalaryEmployeeAdditionController::class, 'show'])->name('main-salary-employee-additions.show');
        Route::post('/main-salary-employee-additions/ajax-check', [MainSalaryEmployeeAdditionController::class, 'ajaxCheck'])->name('main-salary-employee-additions.ajax-check');
        Route::post('/main-salary-employee-additions/store', [MainSalaryEmployeeAdditionController::class, 'store'])->name('main-salary-employee-additions.store');
        Route::post('/main-salary-employee-additions/ajax-search', [MainSalaryEmployeeAdditionController::class, 'ajaxSearch'])->name('main-salary-employee-additions.ajax-search');
        Route::post('/main-salary-employee-additions/destroy', [MainSalaryEmployeeAdditionController::class, 'destroy'])->name('main-salary-employee-additions.destroy');
        Route::post('/main-salary-employee-additions/edit', [MainSalaryEmployeeAdditionController::class, 'edit'])->name('main-salary-employee-additions.edit');
        Route::put('/main-salary-employee-additions', [MainSalaryEmployeeAdditionController::class, 'update'])->name('main-salary-employee-additions.update');
        Route::post('/main-salary-employee-additions/print-search', [MainSalaryEmployeeAdditionController::class, 'printSearch'])->name('main-salary-employee-additions.print-search');

        //main_salary_employee_allowances
        Route::get('/main-salary-employee-allowances', [MainSalaryEmployeeAllowanceController::class, 'index'])->name('main-salary-employee-allowances.index');
        Route::get('/main-salary-employee-allowances/{id}/show', [MainSalaryEmployeeAllowanceController::class, 'show'])->name('main-salary-employee-allowances.show');
        Route::post('/main-salary-employee-allowances/ajax-check', [MainSalaryEmployeeAllowanceController::class, 'ajaxCheck'])->name('main-salary-employee-allowances.ajax-check');
        Route::post('/main-salary-employee-allowances/store', [MainSalaryEmployeeAllowanceController::class, 'store'])->name('main-salary-employee-allowances.store');
        Route::post('/main-salary-employee-allowances/ajax-search', [MainSalaryEmployeeAllowanceController::class, 'ajaxSearch'])->name('main-salary-employee-allowances.ajax-search');
        Route::post('/main-salary-employee-allowances/destroy', [MainSalaryEmployeeAllowanceController::class, 'destroy'])->name('main-salary-employee-allowances.destroy');
        Route::post('/main-salary-employee-allowances/edit', [MainSalaryEmployeeAllowanceController::class, 'edit'])->name('main-salary-employee-allowances.edit');
        Route::put('/main-salary-employee-allowances', [MainSalaryEmployeeAllowanceController::class, 'update'])->name('main-salary-employee-allowances.update');
        Route::post('/main-salary-employee-allowances/print-search', [MainSalaryEmployeeAllowanceController::class, 'printSearch'])->name('main-salary-employee-allowances.print-search');

        // MainSalaryEmployeeDeductionType routes
        Route::get('/main-salary-employee-deduction-types', [MainSalaryEmployeeDeductionTypeController::class, 'index'])->name('main-salary-employee-deduction-types.index');
        Route::get('/main-salary-employee-deduction-types/{id}/show', [MainSalaryEmployeeDeductionTypeController::class, 'show'])->name('main-salary-employee-deduction-types.show');
        Route::post('/main-salary-employee-deduction-types/ajax-check', [MainSalaryEmployeeDeductionTypeController::class, 'ajaxCheck'])->name('main-salary-employee-deduction-types.ajax-check');
        Route::post('/main-salary-employee-deduction-types/store', [MainSalaryEmployeeDeductionTypeController::class, 'store'])->name('main-salary-employee-deduction-types.store');
        Route::post('/main-salary-employee-deduction-types/ajax-search', [MainSalaryEmployeeDeductionTypeController::class, 'ajaxSearch'])->name('main-salary-employee-deduction-types.ajax-search');
        Route::post('/main-salary-employee-deduction-types/destroy', [MainSalaryEmployeeDeductionTypeController::class, 'destroy'])->name('main-salary-employee-deduction-types.destroy');
        Route::post('/main-salary-employee-deduction-types/edit', [MainSalaryEmployeeDeductionTypeController::class, 'edit'])->name('main-salary-employee-deduction-types.edit');
        Route::put('/main-salary-employee-deduction-types', [MainSalaryEmployeeDeductionTypeController::class, 'update'])->name('main-salary-employee-deduction-types.update');
        Route::post('/main-salary-employee-deduction-types/print-search', [MainSalaryEmployeeDeductionTypeController::class, 'printSearch'])->name('main-salary-employee-deduction-types.print-search');

        // MainSalaryEmployeeBonus routes
        Route::get('/main-salary-employee-bonuses', [MainSalaryEmployeeBonusController::class, 'index'])->name('main-salary-employee-bonuses.index');
        Route::get('/main-salary-employee-bonuses/{id}/show', [MainSalaryEmployeeBonusController::class, 'show'])->name('main-salary-employee-bonuses.show');
        Route::post('/main-salary-employee-bonuses/ajax-check', [MainSalaryEmployeeBonusController::class, 'ajaxCheck'])->name('main-salary-employee-bonuses.ajax-check');
        Route::post('/main-salary-employee-bonuses/store', [MainSalaryEmployeeBonusController::class, 'store'])->name('main-salary-employee-bonuses.store');
        Route::post('/main-salary-employee-bonuses/ajax-search', [MainSalaryEmployeeBonusController::class, 'ajaxSearch'])->name('main-salary-employee-bonuses.ajax-search');
        Route::post('/main-salary-employee-bonuses/destroy', [MainSalaryEmployeeBonusController::class, 'destroy'])->name('main-salary-employee-bonuses.destroy');
        Route::post('/main-salary-employee-bonuses/edit', [MainSalaryEmployeeBonusController::class, 'edit'])->name('main-salary-employee-bonuses.edit');
        Route::put('/main-salary-employee-bonuses', [MainSalaryEmployeeBonusController::class, 'update'])->name('main-salary-employee-bonuses.update');
        Route::post('/main-salary-employee-bonuses/print-search', [MainSalaryEmployeeBonusController::class, 'printSearch'])->name('main-salary-employee-bonuses.print-search');

        //loans routes
        Route::get('/main-salary-employee-loans', [MainSalaryEmployeeLoanController::class, 'index'])->name('main-salary-employee-loans.index');
        Route::get('/main-salary-employee-loans/{id}/show', [MainSalaryEmployeeLoanController::class, 'show'])->name('main-salary-employee-loans.show');
        Route::post('/main-salary-employee-loans/ajax-check', [MainSalaryEmployeeLoanController::class, 'ajaxCheck'])->name('main-salary-employee-loans.ajax-check');
        Route::post('/main-salary-employee-loans/store', [MainSalaryEmployeeLoanController::class, 'store'])->name('main-salary-employee-loans.store');
        Route::post('/main-salary-employee-loans/ajax-search', [MainSalaryEmployeeLoanController::class, 'ajaxSearch'])->name('main-salary-employee-loans.ajax-search');
        Route::post('/main-salary-employee-loans/destroy', [MainSalaryEmployeeLoanController::class, 'destroy'])->name('main-salary-employee-loans.destroy');
        Route::post('/main-salary-employee-loans/edit', [MainSalaryEmployeeLoanController::class, 'edit'])->name('main-salary-employee-loans.edit');
        Route::put('/main-salary-employee-loans', [MainSalaryEmployeeLoanController::class, 'update'])->name('main-salary-employee-loans.update');
        Route::post('/main-salary-employee-loans/print-search', [MainSalaryEmployeeLoanController::class, 'printSearch'])->name('main-salary-employee-loans.print-search');

        //MainSalaryEmployeePLoans Route
        Route::get('/main-salary-employee-ploans', [MainSalaryEmployeePLoanController::class, 'index'])->name('main-salary-employee-ploans.index');
        Route::post('/main-salary-employee-ploans/ajax-check', [MainSalaryEmployeePLoanController::class, 'ajaxCheck'])->name('main-salary-employee-ploans.ajax-check');
        Route::post('/main-salary-employee-ploans/store', [MainSalaryEmployeePLoanController::class, 'store'])->name('main-salary-employee-ploans.store');
        Route::post('/main-salary-employee-ploans/ajax-search', [MainSalaryEmployeePLoanController::class, 'ajaxSearch'])->name('main-salary-employee-ploans.ajax-search');
        Route::post('/main-salary-employee-ploans/print-search', [MainSalaryEmployeePLoanController::class, 'printSearch'])->name('main-salary-employee-ploans.print-search');
        Route::post('/main-salary-employee-ploans/show', [MainSalaryEmployeePLoanController::class, 'show'])->name('main-salary-employee-ploans.show');
        Route::post('/main-salary-employee-ploans/destroy', [MainSalaryEmployeePLoanController::class, 'destroy'])->name('main-salary-employee-ploans.destroy');
        Route::post('/main-salary-employee-ploans/edit', [MainSalaryEmployeePLoanController::class, 'edit'])->name('main-salary-employee-ploans.edit');
        Route::put('/main-salary-employee-ploans', [MainSalaryEmployeePLoanController::class, 'update'])->name('main-salary-employee-ploans.update');
        Route::post('/main-salary-employee-ploans/disbursed', [MainSalaryEmployeePLoanController::class, 'disbursed'])->name('main-salary-employee-ploans.disbursed');
        Route::post('/main-salary-employee-ploans/pay-installment-cash', [MainSalaryEmployeePLoanController::class, 'payInstallmentCash'])->name('main-salary-employee-ploans.pay-installment-cash');
        Route::post('/main-salary-employee-ploans/reschedule', [MainSalaryEmployeePLoanController::class, 'reschedule'])->name('main-salary-employee-ploans.reschedule');

        //main_salary_employee
        Route::get('/main-salary-employee', [MainSalaryEmployeeController::class, 'index'])->name('main-salary-employee.index');
        Route::get('/main-salary-employee/{id}/show', [MainSalaryEmployeeController::class, 'show'])->name('main-salary-employee.show');
        Route::post('/main-salary-employee/store', [MainSalaryEmployeeController::class, 'store'])->name('main-salary-employee.store');
        Route::post('/main-salary-employee/ajax-search', [MainSalaryEmployeeController::class, 'ajaxSearch'])->name('main-salary-employee.ajax-search');
        Route::post('/main-salary-employee/destroy', [MainSalaryEmployeeController::class, 'destroy'])->name('main-salary-employee.destroy');
        Route::post('/main-salary-employee/print-search', [MainSalaryEmployeeController::class, 'printSearch'])->name('main-salary-employee.print-search');
        Route::post('/main-salary-employee/print-search-detailed', [MainSalaryEmployeeController::class, 'printSearchDetailed'])->name('main-salary-employee.print-search-detailed');
        Route::get('/main-salary-employee/{calendar_id}/print-all-detailed', [MainSalaryEmployeeController::class, 'printAllDetailed'])->name('main-salary-employee.print-all-detailed');
        Route::get('/main-salary-employee/{id}/print-details', [MainSalaryEmployeeController::class, 'printDetails'])->name('main-salary-employee.print-details');
        Route::post('/main-salary-employee/toggle-payment-status', [MainSalaryEmployeeController::class, 'togglePaymentStatus'])->name('main-salary-employee.toggle-payment-status');
        Route::post('/main-salary-employee/openArchiveModal', [MainSalaryEmployeeController::class, 'openArchiveModal'])->name('main-salary-employee.openArchiveModal');
        Route::post('/main-salary-employee/archive', [MainSalaryEmployeeController::class, 'archive'])->name('main-salary-employee.archive');
        Route::post('/main-salary-employee/archive-month', [MainSalaryEmployeeController::class, 'archiveMonth'])->name('main-salary-employee.archive-month');
        Route::post('/main-salary-employee/recalculate_main_salary', [MainSalaryEmployeeController::class, 'recalculateMainSalary'])->name('main-salary-employee.recalculate_main_salary');
        
        //Finger print routes
        Route::get('/attendanceDepartures', [AttendanceDepartureController::class, 'index'])->name('attendanceDepartures.index');
        Route::get('/attendanceDepartures/{id}/show', [AttendanceDepartureController::class, 'show'])->name('attendanceDepartures.show');
        Route::post('/attendanceDepartures/ajax-check', [AttendanceDepartureController::class, 'ajaxCheck'])->name('attendanceDepartures.ajax-check');
        Route::post('/attendanceDepartures/store', [AttendanceDepartureController::class, 'store'])->name('attendanceDepartures.store');
        Route::post('/attendanceDepartures/ajax-search', [AttendanceDepartureController::class, 'ajaxSearch'])->name('attendanceDepartures.ajax-search');
        Route::post('/attendanceDepartures/destroy', [AttendanceDepartureController::class, 'destroy'])->name('attendanceDepartures.destroy');
        Route::post('/attendanceDepartures/edit', [AttendanceDepartureController::class, 'edit'])->name('attendanceDepartures.edit');
        Route::put('/attendanceDepartures', [AttendanceDepartureController::class, 'update'])->name('attendanceDepartures.update');
        Route::post('/attendanceDepartures/print-search', [AttendanceDepartureController::class, 'printSearch'])->name('attendanceDepartures.print-search');
        Route::get('/attendanceDepartures/upload-excel/{id}', [AttendanceDepartureController::class, 'uploadExcel'])->name('attendanceDepartures.upload-excel');

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
