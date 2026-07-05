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
use App\Http\Controllers\Admin\MainEmployeesVacationsBalancesController;
use App\Http\Controllers\Admin\MainSalaryEmployeeLoanController;
use App\Http\Controllers\Admin\MainSalaryEmployeePLoanController;
use App\Http\Controllers\Admin\MainSalaryEmployeeSettlementController;
use App\Http\Controllers\Admin\MainSalaryRecordController;
use App\Http\Controllers\Admin\MainEmployeeInvestigationController;
use App\Http\Controllers\Admin\NationalityController;
use App\Http\Controllers\Admin\OccasionController;
use App\Http\Controllers\Admin\QualificationController;
use App\Http\Controllers\Admin\ReligionController;
use App\Http\Controllers\Admin\ResignationController;
use App\Http\Controllers\Admin\ShiftsTypeController;
use App\Http\Controllers\Admin\VacationTypeController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\AlertSystemMonitoringController;
use App\Http\Controllers\Admin\PermissionRoleController;
use App\Http\Controllers\Admin\PermissionMainMenuController;
use App\Http\Controllers\Admin\PermissionSubMenuController;
use App\Http\Controllers\Admin\PermissionSubMenuActionController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SalaryGrantTypeController;
use App\Http\Controllers\Admin\DirectBonusController;
use App\Http\Controllers\Admin\DirectGrantController;
use Illuminate\Support\Facades\Route;


if (!defined('PAGEINATION_COUNTER')) {
    define('PAGEINATION_COUNTER', 10);
}
// Admin
Route::prefix('/admin')->name('admin.')->group(function () {

    // logged in routes
    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

        // profile routes
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');


        // general settings
        Route::get('/general-settings', [AdminPanelSettingController::class, 'index'])->name('general-settings')->middleware('permission:الضبط العام,عرض');
        Route::put('/general-settings/{adminPanelSetting}', [AdminPanelSettingController::class, 'update'])->name('general-settings.update')->middleware('permission:الضبط العام,تعديل');
        Route::get('/general-settings/downloadImage/{id}', [AdminPanelSettingController::class, 'downloadImage'])->name('general-settings.downloadImage')->middleware('permission:الضبط العام,عرض');

        // Permissions and Users routes (Guarded by dynamic permissions, automatically bypasses for master admin)
        Route::group([], function () {
            // admin profiles routes
            Route::get('/admin-profiles', [AdminProfileController::class, 'index'])->name('admin-profiles.index')->middleware('permission:المستخدمين,عرض');
            Route::get('/admin-profiles/create', [AdminProfileController::class, 'create'])->name('admin-profiles.create')->middleware('permission:المستخدمين,إضافة');
            Route::post('/admin-profiles', [AdminProfileController::class, 'store'])->name('admin-profiles.store')->middleware('permission:المستخدمين,إضافة');
            Route::get('/admin-profiles/{id}/edit', [AdminProfileController::class, 'edit'])->name('admin-profiles.edit')->middleware('permission:المستخدمين,تعديل');
            Route::put('/admin-profiles/{id}', [AdminProfileController::class, 'update'])->name('admin-profiles.update')->middleware('permission:المستخدمين,تعديل');
            Route::delete('/admin-profiles/{id}', [AdminProfileController::class, 'destroy'])->name('admin-profiles.destroy')->middleware('permission:المستخدمين,حذف');
            Route::get('/admin-profiles/{id}/archive', [AdminProfileController::class, 'archive'])->name('admin-profiles.archive')->middleware('permission:المستخدمين,عرض');

            // Permissions and Roles routes
            Route::resource('permission-roles', PermissionRoleController::class)->middleware([
                'index' => 'permission:ادوار المستخدمين,عرض',
                'show' => 'permission:ادوار المستخدمين,عرض',
                'create' => 'permission:ادوار المستخدمين,إضافة',
                'store' => 'permission:ادوار المستخدمين,إضافة',
                'edit' => 'permission:ادوار المستخدمين,تعديل',
                'update' => 'permission:ادوار المستخدمين,تعديل',
                'destroy' => 'permission:ادوار المستخدمين,حذف',
            ]);
            Route::resource('permission-main-menus', PermissionMainMenuController::class)->middleware([
                'index' => 'permission:القوائم الرئيسيه للصلاحيات,عرض',
                'show' => 'permission:القوائم الرئيسيه للصلاحيات,عرض',
                'create' => 'permission:القوائم الرئيسيه للصلاحيات,إضافة',
                'store' => 'permission:القوائم الرئيسيه للصلاحيات,إضافة',
                'edit' => 'permission:القوائم الرئيسيه للصلاحيات,تعديل',
                'update' => 'permission:القوائم الرئيسيه للصلاحيات,تعديل',
                'destroy' => 'permission:القوائم الرئيسيه للصلاحيات,حذف',
            ]);
            Route::resource('permission-sub-menus', PermissionSubMenuController::class)->middleware([
                'index' => 'permission:القوائم الفرعيه للصلاحيات,عرض',
                'show' => 'permission:القوائم الفرعيه للصلاحيات,عرض',
                'create' => 'permission:القوائم الفرعيه للصلاحيات,إضافة',
                'store' => 'permission:القوائم الفرعيه للصلاحيات,إضافة',
                'edit' => 'permission:القوائم الفرعيه للصلاحيات,تعديل',
                'update' => 'permission:القوائم الفرعيه للصلاحيات,تعديل',
                'destroy' => 'permission:القوائم الفرعيه للصلاحيات,حذف',
            ]);
            Route::resource('permission-sub-menu-actions', PermissionSubMenuActionController::class)->middleware([
                'index' => 'permission:القوائم الفرعيه للصلاحيات,عرض',
                'show' => 'permission:القوائم الفرعيه للصلاحيات,عرض',
                'create' => 'permission:القوائم الفرعيه للصلاحيات,إضافة',
                'store' => 'permission:القوائم الفرعيه للصلاحيات,إضافة',
                'edit' => 'permission:القوائم الفرعيه للصلاحيات,تعديل',
                'update' => 'permission:القوائم الفرعيه للصلاحيات,تعديل',
                'destroy' => 'permission:القوائم الفرعيه للصلاحيات,حذف',
            ]);
        });

        // finance calendar
        Route::resource('financeCalendars', FinanceCalendarController::class)->middleware([
            'index' => 'permission:السنوات المالية,عرض',
            'show' => 'permission:السنوات المالية,عرض',
            'create' => 'permission:السنوات المالية,إضافة',
            'store' => 'permission:السنوات المالية,إضافة',
            'edit' => 'permission:السنوات المالية,تعديل',
            'update' => 'permission:السنوات المالية,تعديل',
            'destroy' => 'permission:السنوات المالية,حذف',
        ]);
        Route::get('financeCalendars/{financeCalendar}/months', [FinanceCalendarController::class, 'showMonths'])
            ->name('financeCalendars.months')
            ->middleware('permission:السنوات المالية,عرض');

        // branches routes
        Route::get('/branches', [BrancheController::class, 'index'])->name('branches.index')->middleware('permission:الفروع,عرض');
        Route::get('/branches/create', [BrancheController::class, 'create'])->name('branches.create')->middleware('permission:الفروع,إضافة');
        Route::post('/branches', [BrancheController::class, 'store'])->name('branches.store')->middleware('permission:الفروع,إضافة');
        Route::get('/branches/{branche}/edit', [BrancheController::class, 'edit'])->name('branches.edit')->middleware('permission:الفروع,تعديل');
        Route::put('/branches/{branche}', [BrancheController::class, 'update'])->name('branches.update')->middleware('permission:الفروع,تعديل');
        Route::delete('/branches/{branche}', [BrancheController::class, 'destroy'])->name('branches.destroy')->middleware('permission:الفروع,حذف');

        // shifts-types routes
        Route::get('/shifts-types', [ShiftsTypeController::class, 'index'])->name('shifts-types.index')->middleware('permission:أنواع الشفتات,عرض');
        Route::get('/shifts-types/create', [ShiftsTypeController::class, 'create'])->name('shifts-types.create')->middleware('permission:أنواع الشفتات,إضافة');
        Route::post('/shifts-types', [ShiftsTypeController::class, 'store'])->name('shifts-types.store')->middleware('permission:أنواع الشفتات,إضافة');
        Route::get('/shifts-types/{shiftsType}/edit', [ShiftsTypeController::class, 'edit'])->name('shifts-types.edit')->middleware('permission:أنواع الشفتات,تعديل');
        Route::put('/shifts-types/{shiftsType}', [ShiftsTypeController::class, 'update'])->name('shifts-types.update')->middleware('permission:أنواع الشفتات,تعديل');
        Route::delete('/shifts-types/{shiftsType}', [ShiftsTypeController::class, 'destroy'])->name('shifts-types.destroy')->middleware('permission:أنواع الشفتات,حذف');
        Route::post('/shifts-types/search', [ShiftsTypeController::class, 'search'])->name('shifts-types.search')->middleware('permission:أنواع الشفتات,عرض');

        // departments routes
        Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index')->middleware('permission:إدارات الموظفين,عرض');
        Route::get('/departments/create', [DepartmentController::class, 'create'])->name('departments.create')->middleware('permission:إدارات الموظفين,إضافة');
        Route::post('/departments', [DepartmentController::class, 'store'])->name('departments.store')->middleware('permission:إدارات الموظفين,إضافة');
        Route::get('/departments/{shiftsType}/edit', [DepartmentController::class, 'edit'])->name('departments.edit')->middleware('permission:إدارات الموظفين,تعديل');
        Route::put('/departments/{shiftsType}', [DepartmentController::class, 'update'])->name('departments.update')->middleware('permission:إدارات الموظفين,تعديل');
        Route::delete('/departments/{shiftsType}', [DepartmentController::class, 'destroy'])->name('departments.destroy')->middleware('permission:إدارات الموظفين,حذف');

        // jobCategories routes
        Route::get('/jobCategories', [JobsCategoryController::class, 'index'])->name('jobCategories.index')->middleware('permission:تصنيفات الوظائف,عرض');
        Route::get('/jobCategories/create', [JobsCategoryController::class, 'create'])->name('jobCategories.create')->middleware('permission:تصنيفات الوظائف,إضافة');
        Route::post('/jobCategories', [JobsCategoryController::class, 'store'])->name('jobCategories.store')->middleware('permission:تصنيفات الوظائف,إضافة');
        Route::get('/jobCategories/{id}/edit', [JobsCategoryController::class, 'edit'])->name('jobCategories.edit')->middleware('permission:تصنيفات الوظائف,تعديل');
        Route::put('/jobCategories/{id}', [JobsCategoryController::class, 'update'])->name('jobCategories.update')->middleware('permission:تصنيفات الوظائف,تعديل');
        Route::delete('/jobCategories/{id}', [JobsCategoryController::class, 'destroy'])->name('jobCategories.destroy')->middleware('permission:تصنيفات الوظائف,حذف');

        // qualifications routes
        Route::get('/qualifications', [QualificationController::class, 'index'])->name('qualifications.index')->middleware('permission:مؤهلات الموظفين,عرض');
        Route::get('/qualifications/create', [QualificationController::class, 'create'])->name('qualifications.create')->middleware('permission:مؤهلات الموظفين,إضافة');
        Route::post('/qualifications', [QualificationController::class, 'store'])->name('qualifications.store')->middleware('permission:مؤهلات الموظفين,إضافة');
        Route::get('/qualifications/{id}/edit', [QualificationController::class, 'edit'])->name('qualifications.edit')->middleware('permission:مؤهلات الموظفين,تعديل');
        Route::put('/qualifications/{id}', [QualificationController::class, 'update'])->name('qualifications.update')->middleware('permission:مؤهلات الموظفين,تعديل');
        Route::delete('/qualifications/{id}', [QualificationController::class, 'destroy'])->name('qualifications.destroy')->middleware('permission:مؤهلات الموظفين,حذف');

        // occasions routes
        Route::get('/occasions', [OccasionController::class, 'index'])->name('occasions.index')->middleware('permission:المناسبات الرسمية,عرض');
        Route::get('/occasions/create', [OccasionController::class, 'create'])->name('occasions.create')->middleware('permission:المناسبات الرسمية,إضافة');
        Route::post('/occasions', [OccasionController::class, 'store'])->name('occasions.store')->middleware('permission:المناسبات الرسمية,إضافة');
        Route::get('/occasions/{id}/edit', [OccasionController::class, 'edit'])->name('occasions.edit')->middleware('permission:المناسبات الرسمية,تعديل');
        Route::put('/occasions/{id}', [OccasionController::class, 'update'])->name('occasions.update')->middleware('permission:المناسبات الرسمية,تعديل');
        Route::delete('/occasions/{id}', [OccasionController::class, 'destroy'])->name('occasions.destroy')->middleware('permission:المناسبات الرسمية,حذف');

        // Resignations routes
        Route::get('/resignations', [ResignationController::class, 'index'])->name('resignations.index')->middleware('permission:انواع استقالات الموظفين,عرض');
        Route::get('/resignations/create', [ResignationController::class, 'create'])->name('resignations.create')->middleware('permission:انواع استقالات الموظفين,إضافة');
        Route::post('/resignations', [ResignationController::class, 'store'])->name('resignations.store')->middleware('permission:انواع استقالات الموظفين,إضافة');
        Route::get('/resignations/{id}/edit', [ResignationController::class, 'edit'])->name('resignations.edit')->middleware('permission:انواع استقالات الموظفين,تعديل');
        Route::put('/resignations/{id}', [ResignationController::class, 'update'])->name('resignations.update')->middleware('permission:انواع استقالات الموظفين,تعديل');
        Route::delete('/resignations/{id}', [ResignationController::class, 'destroy'])->name('resignations.destroy')->middleware('permission:انواع استقالات الموظفين,حذف');

        // Vacation Types routes
        Route::get('/vacation-types', [VacationTypeController::class, 'index'])->name('vacation-types.index')->middleware('permission:أنواع الإجازات,عرض');
        Route::get('/vacation-types/create', [VacationTypeController::class, 'create'])->name('vacation-types.create')->middleware('permission:أنواع الإجازات,إضافة');
        Route::post('/vacation-types', [VacationTypeController::class, 'store'])->name('vacation-types.store')->middleware('permission:أنواع الإجازات,إضافة');
        Route::get('/vacation-types/{id}/edit', [VacationTypeController::class, 'edit'])->name('vacation-types.edit')->middleware('permission:أنواع الإجازات,تعديل');
        Route::put('/vacation-types/{id}', [VacationTypeController::class, 'update'])->name('vacation-types.update')->middleware('permission:أنواع الإجازات,تعديل');
        Route::delete('/vacation-types/{id}', [VacationTypeController::class, 'destroy'])->name('vacation-types.destroy')->middleware('permission:أنواع الإجازات,حذف');

        // nationalities routes
        Route::get('/nationalities', [NationalityController::class, 'index'])->name('nationalities.index')->middleware('permission:الجنسية,عرض');
        Route::get('/nationalities/create', [NationalityController::class, 'create'])->name('nationalities.create')->middleware('permission:الجنسية,إضافة');
        Route::post('/nationalities', [NationalityController::class, 'store'])->name('nationalities.store')->middleware('permission:الجنسية,إضافة');
        Route::get('/nationalities/{id}/edit', [NationalityController::class, 'edit'])->name('nationalities.edit')->middleware('permission:الجنسية,تعديل');
        Route::put('/nationalities/{id}', [NationalityController::class, 'update'])->name('nationalities.update')->middleware('permission:الجنسية,تعديل');
        Route::delete('/nationalities/{id}', [NationalityController::class, 'destroy'])->name('nationalities.destroy')->middleware('permission:الجنسية,حذف');

        // religions routes
        Route::get('/religions', [ReligionController::class, 'index'])->name('religions.index')->middleware('permission:الأديان,عرض');
        Route::get('/religions/create', [ReligionController::class, 'create'])->name('religions.create')->middleware('permission:الأديان,إضافة');
        Route::post('/religions', [ReligionController::class, 'store'])->name('religions.store')->middleware('permission:الأديان,إضافة');
        Route::get('/religions/{id}/edit', [ReligionController::class, 'edit'])->name('religions.edit')->middleware('permission:الأديان,تعديل');
        Route::put('/religions/{id}', [ReligionController::class, 'update'])->name('religions.update')->middleware('permission:الأديان,تعديل');
        Route::delete('/religions/{id}', [ReligionController::class, 'destroy'])->name('religions.destroy')->middleware('permission:الأديان,حذف');

        // blood-groups routes
        Route::get('/blood-groups', [BloodGroupController::class, 'index'])->name('blood-groups.index')->middleware('permission:فصائل الدم,عرض');
        Route::get('/blood-groups/create', [BloodGroupController::class, 'create'])->name('blood-groups.create')->middleware('permission:فصائل الدم,إضافة');
        Route::post('/blood-groups', [BloodGroupController::class, 'store'])->name('blood-groups.store')->middleware('permission:فصائل الدم,إضافة');
        Route::get('/blood-groups/{id}/edit', [BloodGroupController::class, 'edit'])->name('blood-groups.edit')->middleware('permission:فصائل الدم,تعديل');
        Route::put('/blood-groups/{id}', [BloodGroupController::class, 'update'])->name('blood-groups.update')->middleware('permission:فصائل الدم,تعديل');
        Route::delete('/blood-groups/{id}', [BloodGroupController::class, 'destroy'])->name('blood-groups.destroy')->middleware('permission:فصائل الدم,حذف');

        // countries routes
        Route::get('/countries', [CountryController::class, 'index'])->name('countries.index')->middleware('permission:الدول,عرض');
        Route::get('/countries/create', [CountryController::class, 'create'])->name('countries.create')->middleware('permission:الدول,إضافة');
        Route::post('/countries', [CountryController::class, 'store'])->name('countries.store')->middleware('permission:الدول,إضافة');
        Route::get('/countries/{id}/edit', [CountryController::class, 'edit'])->name('countries.edit')->middleware('permission:الدول,تعديل');
        Route::put('/countries/{id}', [CountryController::class, 'update'])->name('countries.update')->middleware('permission:الدول,تعديل');
        Route::delete('/countries/{id}', [CountryController::class, 'destroy'])->name('countries.destroy')->middleware('permission:الدول,حذف');

        // governorates routes
        Route::get('/governorates', [GovernorateController::class, 'index'])->name('governorates.index')->middleware('permission:المحافظات,عرض');
        Route::get('/governorates/create', [GovernorateController::class, 'create'])->name('governorates.create')->middleware('permission:المحافظات,إضافة');
        Route::post('/governorates', [GovernorateController::class, 'store'])->name('governorates.store')->middleware('permission:المحافظات,إضافة');
        Route::get('/governorates/{id}/edit', [GovernorateController::class, 'edit'])->name('governorates.edit')->middleware('permission:المحافظات,تعديل');
        Route::put('/governorates/{id}', [GovernorateController::class, 'update'])->name('governorates.update')->middleware('permission:المحافظات,تعديل');
        Route::delete('/governorates/{id}', [GovernorateController::class, 'destroy'])->name('governorates.destroy')->middleware('permission:المحافظات,حذف');

        // cities routes
        Route::get('/cities', [CityController::class, 'index'])->name('cities.index')->middleware('permission:المدن,عرض');
        Route::get('/cities/create', [CityController::class, 'create'])->name('cities.create')->middleware('permission:المدن,إضافة');
        Route::post('/cities', [CityController::class, 'store'])->name('cities.store')->middleware('permission:المدن,إضافة');
        Route::get('/cities/{id}/edit', [CityController::class, 'edit'])->name('cities.edit')->middleware('permission:المدن,تعديل');
        Route::put('/cities/{id}', [CityController::class, 'update'])->name('cities.update')->middleware('permission:المدن,تعديل');
        Route::delete('/cities/{id}', [CityController::class, 'destroy'])->name('cities.destroy')->middleware('permission:المدن,حذف');

        //employees routs
        Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index')->middleware('permission:بيانات الموظفين,عرض');
        Route::get('/employees/{id}/details', [EmployeeController::class, 'getDetails'])->name('employees.details')->middleware('permission:بيانات الموظفين,عرض');
        Route::get('/employees/{id}/show', [EmployeeController::class, 'show'])->name('employees.show')->middleware('permission:بيانات الموظفين,عرض');
        Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employees.create')->middleware('permission:بيانات الموظفين,إضافة');
        Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store')->middleware('permission:بيانات الموظفين,إضافة');
        Route::get('/employees/{id}/edit', [EmployeeController::class, 'edit'])->name('employees.edit')->middleware('permission:بيانات الموظفين,تعديل');
        Route::put('/employees/{id}', [EmployeeController::class, 'update'])->name('employees.update')->middleware('permission:بيانات الموظفين,تعديل');
        Route::delete('/employees/{id}', [EmployeeController::class, 'destroy'])->name('employees.destroy')->middleware('permission:بيانات الموظفين,حذف');
        Route::post('/employees/governorate-list', [EmployeeController::class, 'getGovernorateList'])->name('employees.governorate-list')->middleware('permission:بيانات الموظفين,عرض');
        Route::post('/employees/cities-list', [EmployeeController::class, 'getCitiesList'])->name('employees.cities-list')->middleware('permission:بيانات الموظفين,عرض');
        Route::post('/employees/search', [EmployeeController::class, 'search'])->name('employees.search')->middleware('permission:بيانات الموظفين,عرض');
        Route::get('/employees/{id}/download/{type}/{file?}', [EmployeeController::class, 'download'])->name('employees.download')->middleware('permission:بيانات الموظفين,عرض');
        Route::post('/employees/{id}/add-file', [EmployeeController::class, 'addFile'])->name('employees.add-file')->middleware('permission:بيانات الموظفين,إضافة');
        Route::get('/employees/files/{id}/{employee_id}', [EmployeeController::class, 'deleteFile'])->name('employees.delete')->middleware('permission:بيانات الموظفين,حذف');
        Route::post('/employees/fixed-allowances', [EmployeeController::class, 'addFixedAllowances'])->name('employees.add-allowance');
        Route::post('/employees/fixed-allowances/delete', [EmployeeController::class, 'deleteFixedAllowance'])->name('employees.delete-allowance');
        Route::post('/employees/fixed-allowances/update', [EmployeeController::class, 'updateFixedAllowance'])->name('employees.update-allowance');

        // AllowanceType routes
        Route::get('/allowance-types', [AllowanceTypeController::class, 'index'])->name('allowance-types.index')->middleware('permission:انواع البدل للراتب,عرض');
        Route::get('/allowance-types/create', [AllowanceTypeController::class, 'create'])->name('allowance-types.create')->middleware('permission:انواع البدل للراتب,إضافة');
        Route::post('/allowance-types', [AllowanceTypeController::class, 'store'])->name('allowance-types.store')->middleware('permission:انواع البدل للراتب,إضافة');
        Route::get('/allowance-types/{id}/edit', [AllowanceTypeController::class, 'edit'])->name('allowance-types.edit')->middleware('permission:انواع البدل للراتب,تعديل');
        Route::put('/allowance-types/{id}', [AllowanceTypeController::class, 'update'])->name('allowance-types.update')->middleware('permission:انواع البدل للراتب,تعديل');
        Route::delete('/allowance-types/{id}', [AllowanceTypeController::class, 'destroy'])->name('allowance-types.destroy')->middleware('permission:انواع البدل للراتب,حذف');

        // DeductionType routes
        Route::get('/deduction-types', [DeductionTypeController::class, 'index'])->name('deduction-types.index')->middleware('permission:انواع الخصم للراتب,عرض');
        Route::get('/deduction-types/create', [DeductionTypeController::class, 'create'])->name('deduction-types.create')->middleware('permission:انواع الخصم للراتب,إضافة');
        Route::post('/deduction-types', [DeductionTypeController::class, 'store'])->name('deduction-types.store')->middleware('permission:انواع الخصم للراتب,إضافة');
        Route::get('/deduction-types/{id}/edit', [DeductionTypeController::class, 'edit'])->name('deduction-types.edit')->middleware('permission:انواع الخصم للراتب,تعديل');
        Route::put('/deduction-types/{id}', [DeductionTypeController::class, 'update'])->name('deduction-types.update')->middleware('permission:انواع الخصم للراتب,تعديل');
        Route::delete('/deduction-types/{id}', [DeductionTypeController::class, 'destroy'])->name('deduction-types.destroy')->middleware('permission:انواع الخصم للراتب,حذف');

        //Bonus routes
        Route::get('/bonuses', [BonusController::class, 'index'])->name('bonuses.index')->middleware('permission:انواع المكافآت للراتب,عرض');
        Route::get('/bonuses/create', [BonusController::class, 'create'])->name('bonuses.create')->middleware('permission:انواع المكافآت للراتب,إضافة');
        Route::post('/bonuses', [BonusController::class, 'store'])->name('bonuses.store')->middleware('permission:انواع المكافآت للراتب,إضافة');
        Route::get('/bonuses/{id}/edit', [BonusController::class, 'edit'])->name('bonuses.edit')->middleware('permission:انواع المكافآت للراتب,تعديل');
        Route::put('/bonuses/{id}', [BonusController::class, 'update'])->name('bonuses.update')->middleware('permission:انواع المكافآت للراتب,تعديل');
        Route::delete('/bonuses/{id}', [BonusController::class, 'destroy'])->name('bonuses.destroy')->middleware('permission:انواع المكافآت للراتب,حذف');

        //SalaryGrantType routes
        Route::get('/salary-grant-types', [SalaryGrantTypeController::class, 'index'])->name('salary-grant-types.index')->middleware('permission:أنواع منح الرواتب,عرض');
        Route::get('/salary-grant-types/create', [SalaryGrantTypeController::class, 'create'])->name('salary-grant-types.create')->middleware('permission:أنواع منح الرواتب,إضافة');
        Route::post('/salary-grant-types', [SalaryGrantTypeController::class, 'store'])->name('salary-grant-types.store')->middleware('permission:أنواع منح الرواتب,إضافة');
        Route::get('/salary-grant-types/{id}/edit', [SalaryGrantTypeController::class, 'edit'])->name('salary-grant-types.edit')->middleware('permission:أنواع منح الرواتب,تعديل');
        Route::put('/salary-grant-types/{id}', [SalaryGrantTypeController::class, 'update'])->name('salary-grant-types.update')->middleware('permission:أنواع منح الرواتب,تعديل');
        Route::delete('/salary-grant-types/{id}', [SalaryGrantTypeController::class, 'destroy'])->name('salary-grant-types.destroy')->middleware('permission:أنواع منح الرواتب,حذف');

        //MainSalaryRecord routes
        Route::get('/main-salary-records', [MainSalaryRecordController::class, 'index'])->name('main-salary-records.index')->middleware('permission:بيانات رواتب الموظفين,عرض');
        Route::post('/main-salary-records/open-month/{id}', [MainSalaryRecordController::class, 'openMonth'])->name('main-salary-records.open-month')->middleware('permission:بيانات رواتب الموظفين,إضافة');
        Route::get('/main-salary-records/create', [MainSalaryRecordController::class, 'create'])->name('main-salary-records.create')->middleware('permission:بيانات رواتب الموظفين,إضافة');
        Route::post('/main-salary-records', [MainSalaryRecordController::class, 'store'])->name('main-salary-records.store')->middleware('permission:بيانات رواتب الموظفين,إضافة');
        Route::get('/main-salary-records/{id}/edit', [MainSalaryRecordController::class, 'edit'])->name('main-salary-records.edit')->middleware('permission:بيانات رواتب الموظفين,تعديل');
        Route::put('/main-salary-records/{id}', [MainSalaryRecordController::class, 'update'])->name('main-salary-records.update')->middleware('permission:بيانات رواتب الموظفين,تعديل');
        Route::delete('/main-salary-records/{id}', [MainSalaryRecordController::class, 'destroy'])->name('main-salary-records.destroy')->middleware('permission:بيانات رواتب الموظفين,حذف');
        Route::post('/main-salary-records/load-open-month', [MainSalaryRecordController::class, 'loadOpenMonth'])->name('main-salary-records.load-open-month')->middleware('permission:بيانات رواتب الموظفين,عرض');

        //main_salary_employee_deductions
        Route::get('/main-salary-employee-deductions', [MainSalaryEmployeeDeductionController::class, 'index'])->name('main-salary-employee-deductions.index')->middleware('permission:الجزاءات اليدويه,عرض');
        Route::get('/main-salary-employee-deductions/{id}/show', [MainSalaryEmployeeDeductionController::class, 'show'])->name('main-salary-employee-deductions.show')->middleware('permission:الجزاءات اليدويه,عرض');
        Route::post('/main-salary-employee-deductions/ajax-check', [MainSalaryEmployeeDeductionController::class, 'ajaxCheck'])->name('main-salary-employee-deductions.ajax-check')->middleware('permission:الجزاءات اليدويه,عرض');
        Route::post('/main-salary-employee-deductions/store', [MainSalaryEmployeeDeductionController::class, 'store'])->name('main-salary-employee-deductions.store')->middleware('permission:الجزاءات اليدويه,إضافة');
        Route::post('/main-salary-employee-deductions/ajax-search', [MainSalaryEmployeeDeductionController::class, 'ajaxSearch'])->name('main-salary-employee-deductions.ajax-search')->middleware('permission:الجزاءات اليدويه,عرض');
        Route::post('/main-salary-employee-deductions/destroy', [MainSalaryEmployeeDeductionController::class, 'destroy'])->name('main-salary-employee-deductions.destroy')->middleware('permission:الجزاءات اليدويه,حذف');
        Route::post('/main-salary-employee-deductions/edit', [MainSalaryEmployeeDeductionController::class, 'edit'])->name('main-salary-employee-deductions.edit')->middleware('permission:الجزاءات اليدويه,تعديل');
        Route::put('/main-salary-employee-deductions', [MainSalaryEmployeeDeductionController::class, 'update'])->name('main-salary-employee-deductions.update')->middleware('permission:الجزاءات اليدويه,تعديل');
        Route::post('/main-salary-employee-deductions/print-search', [MainSalaryEmployeeDeductionController::class, 'printSearch'])->name('main-salary-employee-deductions.print-search')->middleware('permission:الجزاءات اليدويه,عرض');


        //main_salary_employee_absences
        Route::get('/main-salary-employee-absences', [MainSalaryEmployeeAbsenceController::class, 'index'])->name('main-salary-employee-absences.index')->middleware('permission:خصم الغياب اليدوي,عرض');
        Route::get('/main-salary-employee-absences/{id}/show', [MainSalaryEmployeeAbsenceController::class, 'show'])->name('main-salary-employee-absences.show')->middleware('permission:خصم الغياب اليدوي,عرض');
        Route::post('/main-salary-employee-absences/ajax-check', [MainSalaryEmployeeAbsenceController::class, 'ajaxCheck'])->name('main-salary-employee-absences.ajax-check')->middleware('permission:خصم الغياب اليدوي,عرض');
        Route::post('/main-salary-employee-absences/store', [MainSalaryEmployeeAbsenceController::class, 'store'])->name('main-salary-employee-absences.store')->middleware('permission:خصم الغياب اليدوي,إضافة');
        Route::post('/main-salary-employee-absences/ajax-search', [MainSalaryEmployeeAbsenceController::class, 'ajaxSearch'])->name('main-salary-employee-absences.ajax-search')->middleware('permission:خصم الغياب اليدوي,عرض');
        Route::post('/main-salary-employee-absences/destroy', [MainSalaryEmployeeAbsenceController::class, 'destroy'])->name('main-salary-employee-absences.destroy')->middleware('permission:خصم الغياب اليدوي,حذف');
        Route::post('/main-salary-employee-absences/edit', [MainSalaryEmployeeAbsenceController::class, 'edit'])->name('main-salary-employee-absences.edit')->middleware('permission:خصم الغياب اليدوي,تعديل');
        Route::put('/main-salary-employee-absences', [MainSalaryEmployeeAbsenceController::class, 'update'])->name('main-salary-employee-absences.update')->middleware('permission:خصم الغياب اليدوي,تعديل');
        Route::post('/main-salary-employee-absences/print-search', [MainSalaryEmployeeAbsenceController::class, 'printSearch'])->name('main-salary-employee-absences.print-search')->middleware('permission:خصم الغياب اليدوي,عرض');

        //main_salary_employee_additions
        Route::get('/main-salary-employee-additions', [MainSalaryEmployeeAdditionController::class, 'index'])->name('main-salary-employee-additions.index')->middleware('permission:أضافه الأيام اليدوي,عرض');
        Route::get('/main-salary-employee-additions/{id}/show', [MainSalaryEmployeeAdditionController::class, 'show'])->name('main-salary-employee-additions.show')->middleware('permission:أضافه الأيام اليدوي,عرض');
        Route::post('/main-salary-employee-additions/ajax-check', [MainSalaryEmployeeAdditionController::class, 'ajaxCheck'])->name('main-salary-employee-additions.ajax-check')->middleware('permission:أضافه الأيام اليدوي,عرض');
        Route::post('/main-salary-employee-additions/store', [MainSalaryEmployeeAdditionController::class, 'store'])->name('main-salary-employee-additions.store')->middleware('permission:أضافه الأيام اليدوي,إضافة');
        Route::post('/main-salary-employee-additions/ajax-search', [MainSalaryEmployeeAdditionController::class, 'ajaxSearch'])->name('main-salary-employee-additions.ajax-search')->middleware('permission:أضافه الأيام اليدوي,عرض');
        Route::post('/main-salary-employee-additions/destroy', [MainSalaryEmployeeAdditionController::class, 'destroy'])->name('main-salary-employee-additions.destroy')->middleware('permission:أضافه الأيام اليدوي,حذف');
        Route::post('/main-salary-employee-additions/edit', [MainSalaryEmployeeAdditionController::class, 'edit'])->name('main-salary-employee-additions.edit')->middleware('permission:أضافه الأيام اليدوي,تعديل');
        Route::put('/main-salary-employee-additions', [MainSalaryEmployeeAdditionController::class, 'update'])->name('main-salary-employee-additions.update')->middleware('permission:أضافه الأيام اليدوي,تعديل');
        Route::post('/main-salary-employee-additions/print-search', [MainSalaryEmployeeAdditionController::class, 'printSearch'])->name('main-salary-employee-additions.print-search')->middleware('permission:أضافه الأيام اليدوي,عرض');

        //main_salary_employee_allowances
        Route::get('/main-salary-employee-allowances', [MainSalaryEmployeeAllowanceController::class, 'index'])->name('main-salary-employee-allowances.index')->middleware('permission:البدلات المالية المسجلة,عرض');
        Route::get('/main-salary-employee-allowances/{id}/show', [MainSalaryEmployeeAllowanceController::class, 'show'])->name('main-salary-employee-allowances.show')->middleware('permission:البدلات المالية المسجلة,عرض');
        Route::post('/main-salary-employee-allowances/ajax-check', [MainSalaryEmployeeAllowanceController::class, 'ajaxCheck'])->name('main-salary-employee-allowances.ajax-check')->middleware('permission:البدلات المالية المسجلة,عرض');
        Route::post('/main-salary-employee-allowances/store', [MainSalaryEmployeeAllowanceController::class, 'store'])->name('main-salary-employee-allowances.store')->middleware('permission:البدلات المالية المسجلة,إضافة');
        Route::post('/main-salary-employee-allowances/ajax-search', [MainSalaryEmployeeAllowanceController::class, 'ajaxSearch'])->name('main-salary-employee-allowances.ajax-search')->middleware('permission:البدلات المالية المسجلة,عرض');
        Route::post('/main-salary-employee-allowances/destroy', [MainSalaryEmployeeAllowanceController::class, 'destroy'])->name('main-salary-employee-allowances.destroy')->middleware('permission:البدلات المالية المسجلة,حذف');
        Route::post('/main-salary-employee-allowances/edit', [MainSalaryEmployeeAllowanceController::class, 'edit'])->name('main-salary-employee-allowances.edit')->middleware('permission:البدلات المالية المسجلة,تعديل');
        Route::put('/main-salary-employee-allowances', [MainSalaryEmployeeAllowanceController::class, 'update'])->name('main-salary-employee-allowances.update')->middleware('permission:البدلات المالية المسجلة,تعديل');
        Route::post('/main-salary-employee-allowances/print-search', [MainSalaryEmployeeAllowanceController::class, 'printSearch'])->name('main-salary-employee-allowances.print-search')->middleware('permission:البدلات المالية المسجلة,عرض');

        // MainSalaryEmployeeDeductionType routes
        Route::get('/main-salary-employee-deduction-types', [MainSalaryEmployeeDeductionTypeController::class, 'index'])->name('main-salary-employee-deduction-types.index')->middleware('permission:الخصومات المالية المسجلة,عرض');
        Route::get('/main-salary-employee-deduction-types/{id}/show', [MainSalaryEmployeeDeductionTypeController::class, 'show'])->name('main-salary-employee-deduction-types.show')->middleware('permission:الخصومات المالية المسجلة,عرض');
        Route::post('/main-salary-employee-deduction-types/ajax-check', [MainSalaryEmployeeDeductionTypeController::class, 'ajaxCheck'])->name('main-salary-employee-deduction-types.ajax-check')->middleware('permission:الخصومات المالية المسجلة,عرض');
        Route::post('/main-salary-employee-deduction-types/store', [MainSalaryEmployeeDeductionTypeController::class, 'store'])->name('main-salary-employee-deduction-types.store')->middleware('permission:الخصومات المالية المسجلة,إضافة');
        Route::post('/main-salary-employee-deduction-types/ajax-search', [MainSalaryEmployeeDeductionTypeController::class, 'ajaxSearch'])->name('main-salary-employee-deduction-types.ajax-search')->middleware('permission:الخصومات المالية المسجلة,عرض');
        Route::post('/main-salary-employee-deduction-types/destroy', [MainSalaryEmployeeDeductionTypeController::class, 'destroy'])->name('main-salary-employee-deduction-types.destroy')->middleware('permission:الخصومات المالية المسجلة,حذف');
        Route::post('/main-salary-employee-deduction-types/edit', [MainSalaryEmployeeDeductionTypeController::class, 'edit'])->name('main-salary-employee-deduction-types.edit')->middleware('permission:الخصومات المالية المسجلة,تعديل');
        Route::put('/main-salary-employee-deduction-types', [MainSalaryEmployeeDeductionTypeController::class, 'update'])->name('main-salary-employee-deduction-types.update')->middleware('permission:الخصومات المالية المسجلة,تعديل');
        Route::post('/main-salary-employee-deduction-types/print-search', [MainSalaryEmployeeDeductionTypeController::class, 'printSearch'])->name('main-salary-employee-deduction-types.print-search')->middleware('permission:الخصومات المالية المسجلة,عرض');

        // MainEmployeeInvestigation routes
        Route::get('/main-salary-employee-investigations', [MainEmployeeInvestigationController::class, 'index'])->name('main-salary-employee-investigations.index')->middleware('permission:التحقيقات الإدارية,عرض');
        Route::get('/main-salary-employee-investigations/{id}/show', [MainEmployeeInvestigationController::class, 'show'])->name('main-salary-employee-investigations.show')->middleware('permission:التحقيقات الإدارية,عرض');
        Route::post('/main-salary-employee-investigations/store', [MainEmployeeInvestigationController::class, 'store'])->name('main-salary-employee-investigations.store')->middleware('permission:التحقيقات الإدارية,إضافة');
        Route::post('/main-salary-employee-investigations/ajax-check', [MainEmployeeInvestigationController::class, 'ajaxCheck'])->name('main-salary-employee-investigations.ajax-check')->middleware('permission:التحقيقات الإدارية,عرض');
        Route::post('/main-salary-employee-investigations/ajax-search', [MainEmployeeInvestigationController::class, 'ajaxSearch'])->name('main-salary-employee-investigations.ajax-search')->middleware('permission:التحقيقات الإدارية,عرض');
        Route::post('/main-salary-employee-investigations/destroy', [MainEmployeeInvestigationController::class, 'destroy'])->name('main-salary-employee-investigations.destroy')->middleware('permission:التحقيقات الإدارية,حذف');
        Route::post('/main-salary-employee-investigations/edit', [MainEmployeeInvestigationController::class, 'edit'])->name('main-salary-employee-investigations.edit')->middleware('permission:التحقيقات الإدارية,تعديل');
        Route::put('/main-salary-employee-investigations', [MainEmployeeInvestigationController::class, 'update'])->name('main-salary-employee-investigations.update')->middleware('permission:التحقيقات الإدارية,تعديل');
        Route::post('/main-salary-employee-investigations/print-search', [MainEmployeeInvestigationController::class, 'printSearch'])->name('main-salary-employee-investigations.print-search')->middleware('permission:التحقيقات الإدارية,عرض');

        // MainSalaryEmployeeBonus routes
        Route::get('/main-salary-employee-bonuses', [MainSalaryEmployeeBonusController::class, 'index'])->name('main-salary-employee-bonuses.index')->middleware('permission:المكافئات المالية المسجلة,عرض');
        Route::get('/main-salary-employee-bonuses/{id}/show', [MainSalaryEmployeeBonusController::class, 'show'])->name('main-salary-employee-bonuses.show')->middleware('permission:المكافئات المالية المسجلة,عرض');
        Route::post('/main-salary-employee-bonuses/ajax-check', [MainSalaryEmployeeBonusController::class, 'ajaxCheck'])->name('main-salary-employee-bonuses.ajax-check')->middleware('permission:المكافئات المالية المسجلة,عرض');
        Route::post('/main-salary-employee-bonuses/store', [MainSalaryEmployeeBonusController::class, 'store'])->name('main-salary-employee-bonuses.store')->middleware('permission:المكافئات المالية المسجلة,إضافة');
        Route::post('/main-salary-employee-bonuses/ajax-search', [MainSalaryEmployeeBonusController::class, 'ajaxSearch'])->name('main-salary-employee-bonuses.ajax-search')->middleware('permission:المكافئات المالية المسجلة,عرض');
        Route::post('/main-salary-employee-bonuses/destroy', [MainSalaryEmployeeBonusController::class, 'destroy'])->name('main-salary-employee-bonuses.destroy')->middleware('permission:المكافئات المالية المسجلة,حذف');
        Route::post('/main-salary-employee-bonuses/edit', [MainSalaryEmployeeBonusController::class, 'edit'])->name('main-salary-employee-bonuses.edit')->middleware('permission:المكافئات المالية المسجلة,تعديل');
        Route::put('/main-salary-employee-bonuses', [MainSalaryEmployeeBonusController::class, 'update'])->name('main-salary-employee-bonuses.update')->middleware('permission:المكافئات المالية المسجلة,تعديل');
        Route::post('/main-salary-employee-bonuses/print-search', [MainSalaryEmployeeBonusController::class, 'printSearch'])->name('main-salary-employee-bonuses.print-search')->middleware('permission:المكافئات المالية المسجلة,عرض');

        //loans routes
        Route::get('/main-salary-employee-loans', [MainSalaryEmployeeLoanController::class, 'index'])->name('main-salary-employee-loans.index')->middleware('permission:السلف الشهرية,عرض');
        Route::get('/main-salary-employee-loans/{id}/show', [MainSalaryEmployeeLoanController::class, 'show'])->name('main-salary-employee-loans.show')->middleware('permission:السلف الشهرية,عرض');
        Route::post('/main-salary-employee-loans/ajax-check', [MainSalaryEmployeeLoanController::class, 'ajaxCheck'])->name('main-salary-employee-loans.ajax-check')->middleware('permission:السلف الشهرية,عرض');
        Route::post('/main-salary-employee-loans/store', [MainSalaryEmployeeLoanController::class, 'store'])->name('main-salary-employee-loans.store')->middleware('permission:السلف الشهرية,إضافة');
        Route::post('/main-salary-employee-loans/ajax-search', [MainSalaryEmployeeLoanController::class, 'ajaxSearch'])->name('main-salary-employee-loans.ajax-search')->middleware('permission:السلف الشهرية,عرض');
        Route::post('/main-salary-employee-loans/destroy', [MainSalaryEmployeeLoanController::class, 'destroy'])->name('main-salary-employee-loans.destroy')->middleware('permission:السلف الشهرية,حذف');
        Route::post('/main-salary-employee-loans/edit', [MainSalaryEmployeeLoanController::class, 'edit'])->name('main-salary-employee-loans.edit')->middleware('permission:السلف الشهرية,تعديل');
        Route::put('/main-salary-employee-loans', [MainSalaryEmployeeLoanController::class, 'update'])->name('main-salary-employee-loans.update')->middleware('permission:السلف الشهرية,تعديل');
        Route::post('/main-salary-employee-loans/print-search', [MainSalaryEmployeeLoanController::class, 'printSearch'])->name('main-salary-employee-loans.print-search')->middleware('permission:السلف الشهرية,عرض');

        //MainSalaryEmployeePLoans Route
        Route::get('/main-salary-employee-ploans', [MainSalaryEmployeePLoanController::class, 'index'])->name('main-salary-employee-ploans.index')->middleware('permission:السلف المستديمة,عرض');
        Route::post('/main-salary-employee-ploans/ajax-check', [MainSalaryEmployeePLoanController::class, 'ajaxCheck'])->name('main-salary-employee-ploans.ajax-check')->middleware('permission:السلف المستديمة,عرض');
        Route::post('/main-salary-employee-ploans/store', [MainSalaryEmployeePLoanController::class, 'store'])->name('main-salary-employee-ploans.store')->middleware('permission:السلف المستديمة,إضافة');
        Route::post('/main-salary-employee-ploans/ajax-search', [MainSalaryEmployeePLoanController::class, 'ajaxSearch'])->name('main-salary-employee-ploans.ajax-search')->middleware('permission:السلف المستديمة,عرض');
        Route::post('/main-salary-employee-ploans/print-search', [MainSalaryEmployeePLoanController::class, 'printSearch'])->name('main-salary-employee-ploans.print-search')->middleware('permission:السلف المستديمة,عرض');
        Route::post('/main-salary-employee-ploans/show', [MainSalaryEmployeePLoanController::class, 'show'])->name('main-salary-employee-ploans.show')->middleware('permission:السلف المستديمة,عرض');
        Route::post('/main-salary-employee-ploans/destroy', [MainSalaryEmployeePLoanController::class, 'destroy'])->name('main-salary-employee-ploans.destroy')->middleware('permission:السلف المستديمة,حذف');
        Route::post('/main-salary-employee-ploans/edit', [MainSalaryEmployeePLoanController::class, 'edit'])->name('main-salary-employee-ploans.edit')->middleware('permission:السلف المستديمة,تعديل');
        Route::put('/main-salary-employee-ploans', [MainSalaryEmployeePLoanController::class, 'update'])->name('main-salary-employee-ploans.update')->middleware('permission:السلف المستديمة,تعديل');
        Route::post('/main-salary-employee-ploans/disbursed', [MainSalaryEmployeePLoanController::class, 'disbursed'])->name('main-salary-employee-ploans.disbursed')->middleware('permission:السلف المستديمة,إضافة');
        Route::post('/main-salary-employee-ploans/pay-installment-cash', [MainSalaryEmployeePLoanController::class, 'payInstallmentCash'])->name('main-salary-employee-ploans.pay-installment-cash')->middleware('permission:السلف المستديمة,إضافة');
        Route::post('/main-salary-employee-ploans/reschedule', [MainSalaryEmployeePLoanController::class, 'reschedule'])->name('main-salary-employee-ploans.reschedule')->middleware('permission:السلف المستديمة,إضافة');

        //main_salary_employee
        Route::get('/main-salary-employee', [MainSalaryEmployeeController::class, 'index'])->name('main-salary-employee.index')->middleware('permission:رواتب الموظفين مفصله,عرض');
        Route::get('/main-salary-employee/{id}/show', [MainSalaryEmployeeController::class, 'show'])->name('main-salary-employee.show')->middleware('permission:رواتب الموظفين مفصله,عرض');
        Route::post('/main-salary-employee/store', [MainSalaryEmployeeController::class, 'store'])->name('main-salary-employee.store')->middleware('permission:رواتب الموظفين مفصله,إضافة');
        Route::post('/main-salary-employee/ajax-search', [MainSalaryEmployeeController::class, 'ajaxSearch'])->name('main-salary-employee.ajax-search')->middleware('permission:رواتب الموظفين مفصله,عرض');
        Route::post('/main-salary-employee/destroy', [MainSalaryEmployeeController::class, 'destroy'])->name('main-salary-employee.destroy')->middleware('permission:رواتب الموظفين مفصله,حذف');
        Route::post('/main-salary-employee/print-search', [MainSalaryEmployeeController::class, 'printSearch'])->name('main-salary-employee.print-search')->middleware('permission:رواتب الموظفين مفصله,عرض');
        Route::post('/main-salary-employee/print-search-detailed', [MainSalaryEmployeeController::class, 'printSearchDetailed'])->name('main-salary-employee.print-search-detailed')->middleware('permission:رواتب الموظفين مفصله,عرض');
        Route::get('/main-salary-employee/{calendar_id}/print-all-detailed', [MainSalaryEmployeeController::class, 'printAllDetailed'])->name('main-salary-employee.print-all-detailed')->middleware('permission:رواتب الموظفين مفصله,عرض');
        Route::get('/main-salary-employee/{id}/print-details', [MainSalaryEmployeeController::class, 'printDetails'])->name('main-salary-employee.print-details')->middleware('permission:رواتب الموظفين مفصله,عرض');
        Route::post('/main-salary-employee/toggle-payment-status', [MainSalaryEmployeeController::class, 'togglePaymentStatus'])->name('main-salary-employee.toggle-payment-status')->middleware('permission:رواتب الموظفين مفصله,تعديل');
        Route::post('/main-salary-employee/openArchiveModal', [MainSalaryEmployeeController::class, 'openArchiveModal'])->name('main-salary-employee.openArchiveModal')->middleware('permission:رواتب الموظفين مفصله,عرض');
        Route::post('/main-salary-employee/archive', [MainSalaryEmployeeController::class, 'archive'])->name('main-salary-employee.archive')->middleware('permission:رواتب الموظفين مفصله,تعديل');
        Route::post('/main-salary-employee/archive-month', [MainSalaryEmployeeController::class, 'archiveMonth'])->name('main-salary-employee.archive-month')->middleware('permission:رواتب الموظفين مفصله,تعديل');
        Route::post('/main-salary-employee/recalculate_main_salary', [MainSalaryEmployeeController::class, 'recalculateMainSalary'])->name('main-salary-employee.recalculate_main_salary')->middleware('permission:رواتب الموظفين مفصله,إضافة');

        // Main salary employee settlements routes
        Route::get('/main-salary-employee-settlements', [MainSalaryEmployeeSettlementController::class, 'index'])->name('main-salary-employee-settlements.index')->middleware('permission:تسويات رواتب الموظفين المؤرشفة,عرض');
        Route::get('/main-salary-employee-settlements/{id}/show', [MainSalaryEmployeeSettlementController::class, 'show'])->name('main-salary-employee-settlements.show')->middleware('permission:تسويات رواتب الموظفين المؤرشفة,عرض');
        Route::post('/main-salary-employee-settlements/store', [MainSalaryEmployeeSettlementController::class, 'store'])->name('main-salary-employee-settlements.store')->middleware('permission:تسويات رواتب الموظفين المؤرشفة,إضافة');
        Route::post('/main-salary-employee-settlements/edit', [MainSalaryEmployeeSettlementController::class, 'edit'])->name('main-salary-employee-settlements.edit')->middleware('permission:تسويات رواتب الموظفين المؤرشفة,تعديل');
        Route::put('/main-salary-employee-settlements/update', [MainSalaryEmployeeSettlementController::class, 'update'])->name('main-salary-employee-settlements.update')->middleware('permission:تسويات رواتب الموظفين المؤرشفة,تعديل');
        Route::post('/main-salary-employee-settlements/destroy', [MainSalaryEmployeeSettlementController::class, 'destroy'])->name('main-salary-employee-settlements.destroy')->middleware('permission:تسويات رواتب الموظفين المؤرشفة,حذف');
        Route::post('/main-salary-employee-settlements/ajax-search', [MainSalaryEmployeeSettlementController::class, 'ajaxSearch'])->name('main-salary-employee-settlements.ajax-search')->middleware('permission:تسويات رواتب الموظفين المؤرشفة,عرض');
        Route::post('/main-salary-employee-settlements/print-search', [MainSalaryEmployeeSettlementController::class, 'printSearch'])->name('main-salary-employee-settlements.print-search')->middleware('permission:تسويات رواتب الموظفين المؤرشفة,عرض');

        // DirectBonus routes
        Route::get('/direct-bonuses', [DirectBonusController::class, 'index'])->name('direct-bonuses.index')->middleware('permission:المكافئات المباشرة,عرض');
        Route::get('/direct-bonuses/create', [DirectBonusController::class, 'create'])->name('direct-bonuses.create')->middleware('permission:المكافئات المباشرة,إضافة');
        Route::post('/direct-bonuses', [DirectBonusController::class, 'store'])->name('direct-bonuses.store')->middleware('permission:المكافئات المباشرة,إضافة');
        Route::get('/direct-bonuses/{id}/edit', [DirectBonusController::class, 'edit'])->name('direct-bonuses.edit')->middleware('permission:المكافئات المباشرة,تعديل');
        Route::put('/direct-bonuses/{id}', [DirectBonusController::class, 'update'])->name('direct-bonuses.update')->middleware('permission:المكافئات المباشرة,تعديل');
        Route::delete('/direct-bonuses/{id}', [DirectBonusController::class, 'destroy'])->name('direct-bonuses.destroy')->middleware('permission:المكافئات المباشرة,حذف');
        Route::post('/direct-bonuses/ajax-search', [DirectBonusController::class, 'ajaxSearch'])->name('direct-bonuses.ajax-search')->middleware('permission:المكافئات المباشرة,عرض');

        // DirectGrant routes
        Route::get('/direct-grants', [DirectGrantController::class, 'index'])->name('direct-grants.index')->middleware('permission:المنح المباشرة,عرض');
        Route::get('/direct-grants/create', [DirectGrantController::class, 'create'])->name('direct-grants.create')->middleware('permission:المنح المباشرة,إضافة');
        Route::post('/direct-grants', [DirectGrantController::class, 'store'])->name('direct-grants.store')->middleware('permission:المنح المباشرة,إضافة');
        Route::get('/direct-grants/{id}/edit', [DirectGrantController::class, 'edit'])->name('direct-grants.edit')->middleware('permission:المنح المباشرة,تعديل');
        Route::put('/direct-grants/{id}', [DirectGrantController::class, 'update'])->name('direct-grants.update')->middleware('permission:المنح المباشرة,تعديل');
        Route::delete('/direct-grants/{id}', [DirectGrantController::class, 'destroy'])->name('direct-grants.destroy')->middleware('permission:المنح المباشرة,حذف');
        Route::post('/direct-grants/ajax-search', [DirectGrantController::class, 'ajaxSearch'])->name('direct-grants.ajax-search')->middleware('permission:المنح المباشرة,عرض');

        //Main employees vacations balances routes
        Route::get('/main-employees-vacations-balances', [MainEmployeesVacationsBalancesController::class, 'index'])->name('main-employees-vacations-balances.index')->middleware('permission:أرصدة إجازات الموظفين,عرض');
        Route::post('/main-employees-vacations-balances/search', [MainEmployeesVacationsBalancesController::class, 'search'])->name('main-employees-vacations-balances.search')->middleware('permission:أرصدة إجازات الموظفين,عرض');
        Route::get('/main-employees-vacations-balances/{id}/show', [MainEmployeesVacationsBalancesController::class, 'show'])->name('main-employees-vacations-balances.show')->middleware('permission:أرصدة إجازات الموظفين,عرض');
        Route::post('/main-employees-vacations-balances/{id}/ajax-search-show', [MainEmployeesVacationsBalancesController::class, 'ajaxSearchShow'])->name('main-employees-vacations-balances.ajax-search-show')->middleware('permission:أرصدة إجازات الموظفين,عرض');
        Route::get('/main-employees-vacations-balances/{id}/edit', [MainEmployeesVacationsBalancesController::class, 'edit'])->name('main-employees-vacations-balances.edit')->middleware('permission:أرصدة إجازات الموظفين,تعديل');
        Route::put('/main-employees-vacations-balances/{id}', [MainEmployeesVacationsBalancesController::class, 'update'])->name('main-employees-vacations-balances.update')->middleware('permission:أرصدة إجازات الموظفين,تعديل');

        //Finger print routes
        Route::get('/attendanceDepartures', [AttendanceDepartureController::class, 'index'])->name('attendanceDepartures.index')->middleware('permission:سجلات البصمات,عرض');
        Route::get('/attendanceDepartures/{id}/show', [AttendanceDepartureController::class, 'show'])->name('attendanceDepartures.show')->middleware('permission:سجلات البصمات,عرض');
        Route::post('/attendanceDepartures/ajax-check', [AttendanceDepartureController::class, 'ajaxCheck'])->name('attendanceDepartures.ajax-check')->middleware('permission:سجلات البصمات,عرض');
        Route::post('/attendanceDepartures/store', [AttendanceDepartureController::class, 'store'])->name('attendanceDepartures.store')->middleware('permission:سجلات البصمات,إضافة');
        Route::post('/attendanceDepartures/ajax-search', [AttendanceDepartureController::class, 'ajaxSearch'])->name('attendanceDepartures.ajax-search')->middleware('permission:سجلات البصمات,عرض');
        Route::post('/attendanceDepartures/destroy', [AttendanceDepartureController::class, 'destroy'])->name('attendanceDepartures.destroy')->middleware('permission:سجلات البصمات,حذف');
        Route::post('/attendanceDepartures/edit', [AttendanceDepartureController::class, 'edit'])->name('attendanceDepartures.edit')->middleware('permission:سجلات البصمات,تعديل');
        Route::put('/attendanceDepartures', [AttendanceDepartureController::class, 'update'])->name('attendanceDepartures.update')->middleware('permission:سجلات البصمات,تعديل');
        Route::post('/attendanceDepartures/print-search', [AttendanceDepartureController::class, 'printSearch'])->name('attendanceDepartures.print-search')->middleware('permission:سجلات البصمات,عرض');
        Route::get('/attendanceDepartures/finger-print-details/{id}/{finance_monthly_calendar_id}', [AttendanceDepartureController::class, 'fingerPrintDetails'])->name('attendanceDepartures.finger-print-details')->middleware('permission:سجلات البصمات,عرض');
        Route::get('/attendanceDepartures/finger-print-details/{id}/{finance_monthly_calendar_id}/print', [AttendanceDepartureController::class, 'printFingerPrintDetails'])->name('attendanceDepartures.finger-print-details.print')->middleware('permission:سجلات البصمات,عرض');
        Route::post('/attendanceDepartures/finger-print-details/load-grid', [AttendanceDepartureController::class, 'loadFingerPrintGrid'])->name('attendanceDepartures.finger-print-details.load-grid')->middleware('permission:سجلات البصمات,عرض');
        Route::post('/attendanceDepartures/finger-print-details/save-row', [AttendanceDepartureController::class, 'saveFingerPrintRow'])->name('attendanceDepartures.finger-print-details.save-row')->middleware('permission:سجلات البصمات,إضافة');
        Route::post('/attendanceDepartures/finger-print-details/save-all', [AttendanceDepartureController::class, 'saveAllFingerPrintRows'])->name('attendanceDepartures.finger-print-details.save-all')->middleware('permission:سجلات البصمات,إضافة');
        Route::post('/attendanceDepartures/finger-print-details/day-movements', [AttendanceDepartureController::class, 'getDayMovements'])->name('attendanceDepartures.finger-print-details.day-movements')->middleware('permission:سجلات البصمات,عرض');
        Route::post('/attendanceDepartures/finger-print-details/update-day-movements', [AttendanceDepartureController::class, 'updateDayMovements'])->name('attendanceDepartures.finger-print-details.update-day-movements')->middleware('permission:سجلات البصمات,تعديل');

        // System Monitoring routes
        Route::get('/system-monitoring/self-logs', [AlertSystemMonitoringController::class, 'selfLogs'])->name('system-monitoring.self-logs')->middleware('permission:سجلات المراقبة الذاتية,عرض');
        Route::delete('/system-monitoring/self-logs/{id}', [AlertSystemMonitoringController::class, 'destroySelfLog'])->name('system-monitoring.destroy-self-log')->middleware('permission:سجلات المراقبة الذاتية,حذف');
        Route::get('/system-monitoring', [AlertSystemMonitoringController::class, 'index'])->name('system-monitoring.index')->middleware('permission:سجلات النظام العامة,عرض');
        Route::post('/system-monitoring/{id}/toggle-important', [AlertSystemMonitoringController::class, 'toggleImportant'])->name('system-monitoring.toggle-important')->middleware('permission:سجلات النظام العامة,تعديل');
        Route::delete('/system-monitoring/{id}', [AlertSystemMonitoringController::class, 'destroy'])->name('system-monitoring.destroy')->middleware('permission:سجلات النظام العامة,حذف');
        Route::post('/system-monitoring/ajax-search', [AlertSystemMonitoringController::class, 'ajaxSearch'])->name('system-monitoring.ajax-search')->middleware('permission:سجلات النظام العامة,عرض');
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
