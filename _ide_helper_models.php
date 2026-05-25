<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string|null $email
 * @property string $username
 * @property string $password
 * @property string $added_by
 * @property string $updated_by
 * @property int $status
 * @property string $date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $company_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AdminPanelSetting> $addedAdminPanels
 * @property-read int|null $added_admin_panels_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Department> $addedDepartments
 * @property-read int|null $added_departments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FinanceCalendar> $addedFinanceCalendars
 * @property-read int|null $added_finance_calendars_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FinanceMonthlyCalendar> $addedFinanceMonthlyCalendars
 * @property-read int|null $added_finance_monthly_calendars_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\JobsCategory> $addedJobsCategoies
 * @property-read int|null $added_jobs_categoies_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Occasion> $addedOccasions
 * @property-read int|null $added_occasions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Qualification> $addedQualifications
 * @property-read int|null $added_qualifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ShiftsType> $addedShiftsTypes
 * @property-read int|null $added_shifts_types_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Branche> $branches
 * @property-read int|null $branches_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AdminPanelSetting> $updatedAdminPanels
 * @property-read int|null $updated_admin_panels_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Branche> $updatedBranches
 * @property-read int|null $updated_branches_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Department> $updatedDepartments
 * @property-read int|null $updated_departments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FinanceCalendar> $updatedFinanceCalendars
 * @property-read int|null $updated_finance_calendars_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FinanceMonthlyCalendar> $updatedFinanceMonthlyCalendars
 * @property-read int|null $updated_finance_monthly_calendars_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\JobsCategory> $updatedJobsCategoies
 * @property-read int|null $updated_jobs_categoies_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Occasion> $updatedOccasions
 * @property-read int|null $updated_occasions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Qualification> $updatedQualifications
 * @property-read int|null $updated_qualifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ShiftsType> $updatedShiftsTypes
 * @property-read int|null $updated_shifts_types_count
 * @method static \Database\Factories\AdminFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereAddedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereUsername($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AllowanceType> $addedAllowanceTypes
 * @property-read int|null $added_allowance_types_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BloodGroup> $addedBloodGroup
 * @property-read int|null $added_blood_group_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\City> $addedCities
 * @property-read int|null $added_cities_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Country> $addedCountries
 * @property-read int|null $added_countries_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DeductionType> $addedDeductionTypes
 * @property-read int|null $added_deduction_types_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Employee> $addedEmployees
 * @property-read int|null $added_employees_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\File> $addedFiles
 * @property-read int|null $added_files_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Governorate> $addedGovernorates
 * @property-read int|null $added_governorates_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Nationality> $addedNationality
 * @property-read int|null $added_nationality_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Religion> $addedReligion
 * @property-read int|null $added_religion_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Resignation> $addedResignation
 * @property-read int|null $added_resignation_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AllowanceType> $updatedAllowanceTypes
 * @property-read int|null $updated_allowance_types_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BloodGroup> $updatedBloodGroup
 * @property-read int|null $updated_blood_group_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\City> $updatedCities
 * @property-read int|null $updated_cities_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Country> $updatedCountries
 * @property-read int|null $updated_countries_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DeductionType> $updatedDeductionTypes
 * @property-read int|null $updated_deduction_types_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Employee> $updatedEmployees
 * @property-read int|null $updated_employees_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\File> $updatedFiles
 * @property-read int|null $updated_files_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Governorate> $updatedGovernorates
 * @property-read int|null $updated_governorates_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Nationality> $updatedNationality
 * @property-read int|null $updated_nationality_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Religion> $updatedReligion
 * @property-read int|null $updated_religion_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Resignation> $updatedResignation
 * @property-read int|null $updated_resignation_count
 */
	class Admin extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $company_name
 * @property int $status واحد مفعل - صفر معطل
 * @property string|null $image
 * @property string $phone
 * @property string $address
 * @property string $email
 * @property int $created_by
 * @property int $updated_by
 * @property int $company_id
 * @property numeric $after_minute_calculate_delay بعد كم عدد دقيقة نحسب تاخير حضور
 * @property numeric $after_minute_calculate_early_departure بعد كم عدد دقيقة نحسب انصراف مبكر
 * @property numeric $after_minute_quarter_day_cut بعد كم عدد دقيقة من مجموع الحضور او الانصراف مبكر نخصم ربع يوم
 * @property numeric $after_days_half_day_cut بعد كم مرة تاخير او انصراف مبكر نخصم نص يوم
 * @property numeric $after_days_allday_day_cut بعد كم مرة تاخير او انصراف مبكر نخصم يوم كامل
 * @property numeric $monthly_vacation_balance رصيد الاجازات الشهرية
 * @property numeric $after_days_begin_vacation بعد كام يوم ينزل للموظف رصيد الاجازات الشهرية
 * @property numeric $first_balance_begin_vacation رصيد الاجازات الأولي عند بدء العمل
 * @property numeric $sanctions_value_first_absence قيمه خصم الايام بعد اول مرة غياب بدون اذن
 * @property numeric $sanctions_value_second_absence قيمه خصم الايام بعد ثاني مرة غياب بدون اذن
 * @property numeric $sanctions_value_third_absence قيمه خصم الايام بعد ثالث مرة غياب بدون اذن
 * @property numeric $sanctions_value_fourth_absence قيمه خصم الايام بعد رابع مرة غياب بدون اذن
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Admin $addedBy
 * @property-read \App\Models\Admin $updatedBy
 * @method static \Database\Factories\AdminPanelSettingFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPanelSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPanelSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPanelSetting query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPanelSetting whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPanelSetting whereAfterDaysAlldayDayCut($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPanelSetting whereAfterDaysBeginVacation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPanelSetting whereAfterDaysHalfDayCut($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPanelSetting whereAfterMinuteCalculateDelay($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPanelSetting whereAfterMinuteCalculateEarlyDeparture($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPanelSetting whereAfterMinuteQuarterDayCut($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPanelSetting whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPanelSetting whereCompanyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPanelSetting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPanelSetting whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPanelSetting whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPanelSetting whereFirstBalanceBeginVacation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPanelSetting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPanelSetting whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPanelSetting whereMonthlyVacationBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPanelSetting wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPanelSetting whereSanctionsValueFirstAbsence($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPanelSetting whereSanctionsValueFourthAbsence($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPanelSetting whereSanctionsValueSecondAbsence($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPanelSetting whereSanctionsValueThirdAbsence($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPanelSetting whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPanelSetting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPanelSetting whereUpdatedBy($value)
 * @mixin \Eloquent
 */
	class AdminPanelSetting extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property int $status
 * @property int $company_id
 * @property int $added_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Admin $addedBy
 * @property-read \App\Models\Admin|null $updatedBy
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AllowanceType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AllowanceType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AllowanceType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AllowanceType whereAddedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AllowanceType whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AllowanceType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AllowanceType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AllowanceType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AllowanceType whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AllowanceType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AllowanceType whereUpdatedBy($value)
 */
	class AllowanceType extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property int $status
 * @property int $company_id
 * @property int $added_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Admin $addedBy
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Employee> $employees
 * @property-read int|null $employees_count
 * @property-read \App\Models\Admin|null $updatedBy
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodGroup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodGroup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodGroup query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodGroup whereAddedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodGroup whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodGroup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodGroup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodGroup whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodGroup whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodGroup whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodGroup whereUpdatedBy($value)
 */
	class BloodGroup extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property int $status
 * @property int $company_id
 * @property int $added_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bonus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bonus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bonus query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bonus whereAddedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bonus whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bonus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bonus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bonus whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bonus whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bonus whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bonus whereUpdatedBy($value)
 */
	class Bonus extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $address
 * @property string $phone
 * @property string|null $email
 * @property int $status
 * @property int $created_by
 * @property int $updated_by
 * @property int $company_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Admin $createdBy
 * @property-read \App\Models\Admin $updatedBy
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branche newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branche newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branche query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branche whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branche whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branche whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branche whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branche whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branche whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branche whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branche wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branche whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branche whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branche whereUpdatedBy($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Employee> $employees
 * @property-read int|null $employees_count
 */
	class Branche extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property int $status
 * @property int $company_id
 * @property int $governorate_id
 * @property int $added_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Admin $addedBy
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Employee> $employees
 * @property-read int|null $employees_count
 * @property-read \App\Models\Governorate $governorate
 * @property-read \App\Models\Admin|null $updatedBy
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City whereAddedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City whereGovernorateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City whereUpdatedBy($value)
 */
	class City extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property int $status
 * @property int $company_id
 * @property int $added_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Admin $addedBy
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Employee> $employees
 * @property-read int|null $employees_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Governorate> $governorates
 * @property-read int|null $governorates_count
 * @property-read \App\Models\Admin|null $updatedBy
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereAddedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereUpdatedBy($value)
 */
	class Country extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property int $status
 * @property int $company_id
 * @property int $added_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Admin $addedBy
 * @property-read \App\Models\Admin|null $updatedBy
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeductionType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeductionType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeductionType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeductionType whereAddedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeductionType whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeductionType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeductionType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeductionType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeductionType whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeductionType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeductionType whereUpdatedBy($value)
 */
	class DeductionType extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $number
 * @property string|null $description
 * @property int $company_id
 * @property int $status
 * @property int $created_by
 * @property int $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Admin $createdBy
 * @property-read \App\Models\Admin $updatedBy
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Department newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Department newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Department query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Department whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Department whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Department whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Department whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Department whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Department whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Department whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Department whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Department whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Department whereUpdatedBy($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Employee> $employees
 * @property-read int|null $employees_count
 */
	class Department extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property int $status
 * @property int $company_id
 * @property int $added_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Employee> $employees
 * @property-read int|null $employees_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DrivingLicenseType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DrivingLicenseType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DrivingLicenseType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DrivingLicenseType whereAddedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DrivingLicenseType whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DrivingLicenseType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DrivingLicenseType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DrivingLicenseType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DrivingLicenseType whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DrivingLicenseType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DrivingLicenseType whereUpdatedBy($value)
 */
	class DrivingLicenseType extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $employee_code Employee Code unique identifier
 * @property string|null $fingerprint_code Fingerprint Code unique identifier
 * @property string $name
 * @property string|null $birth_date
 * @property int $nationality_id
 * @property int $gender 1: Male, 2: Female, 3: Other
 * @property int|null $religion_id
 * @property string|null $nationality_number
 * @property string|null $nationality_expiry_date
 * @property string|null $nationality_place_of_issue
 * @property string|null $email
 * @property string|null $home_telephone
 * @property string|null $work_telephone
 * @property int|null $marital_status 1: Single, 2: Married, 3: engaged , 4: Widowed , 5: Divorced
 * @property int|null $children_count
 * @property string|null $stable_address
 * @property int|null $country_id
 * @property int|null $governorate_id
 * @property int|null $city_id
 * @property string|null $home_address
 * @property int|null $blood_group_id
 * @property int|null $driving_license 1: Yes, 0: No
 * @property int|null $driving_license_type_id
 * @property string|null $driving_license_number
 * @property int|null $military_status
 * @property string|null $military_start_date
 * @property string|null $military_end_date
 * @property string|null $military_weapon
 * @property string|null $military_exemption_date
 * @property string|null $military_exemption_reason
 * @property string|null $postponement_reason
 * @property int|null $qualifications_id
 * @property string|null $qualification_year
 * @property int|null $graduation_grade 1: Excellent, 2: Very Good, 3: Good, 4: Fair, 5: Poor
 * @property string|null $graduation_specialization
 * @property int $job_id
 * @property int $department_id
 * @property int $branch_id
 * @property string|null $hire_date
 * @property string|null $hire_date_day_month_year
 * @property int $employment_status 1: Active, 0: Inactive
 * @property int|null $fixed_shift
 * @property int|null $shift_type_id
 * @property numeric|null $daily_work_hours
 * @property int|null $resignation_id
 * @property string|null $resignation_date
 * @property string|null $resignation_reason
 * @property numeric|null $salary
 * @property int|null $motivation_type 0: None, 1: Fixed, 2: Variable
 * @property numeric|null $motivation_amount
 * @property int|null $payment_method 1: Cash, 2: Bank Transfer, 3: Check
 * @property string|null $bank_account_number
 * @property numeric|null $payment_per_day
 * @property int|null $has_social_insurance 1: Yes, 0: No
 * @property numeric|null $social_insurance_amount
 * @property string|null $social_insurance_number
 * @property int|null $has_medical_insurance 1: Yes, 0: No
 * @property string|null $medical_insurance_number
 * @property numeric|null $medical_insurance_amount
 * @property int|null $fixed_allowance
 * @property int|null $has_attendance 1: Yes, 0: No
 * @property int|null $vacation_formula
 * @property int|null $active_for_vacation
 * @property int|null $has_sensitive_data
 * @property string|null $sponsor_name
 * @property string|null $passport_number
 * @property string|null $passport_expiry_date
 * @property string|null $passport_place_of_issue
 * @property string|null $image
 * @property string|null $cv
 * @property int|null $language_id
 * @property int|null $has_disability 1: Yes, 0: No
 * @property string|null $disability_description
 * @property int|null $has_relative 1: Yes, 0: No
 * @property string|null $relative_description
 * @property string|null $urgent_contact_details
 * @property string|null $notes
 * @property int $company_id
 * @property int $added_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Admin $addedBy
 * @property-read \App\Models\BloodGroup|null $bloodGroup
 * @property-read \App\Models\Branche $branch
 * @property-read \App\Models\City|null $city
 * @property-read \App\Models\Country|null $country
 * @property-read \App\Models\Department $department
 * @property-read \App\Models\DrivingLicenseType|null $drivingLicenseType
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\File> $files
 * @property-read int|null $files_count
 * @property-read \App\Models\Governorate|null $governorate
 * @property-read \App\Models\JobsCategory $job
 * @property-read \App\Models\Language|null $language
 * @property-read \App\Models\MilitaryStatus|null $militaryStatus
 * @property-read \App\Models\Nationality $nationality
 * @property-read \App\Models\Qualification|null $qualification
 * @property-read \App\Models\Religion|null $religion
 * @property-read \App\Models\Resignation|null $resignation
 * @property-read \App\Models\ShiftsType|null $shiftType
 * @property-read \App\Models\Admin|null $updatedBy
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereActiveForVacation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereAddedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereBankAccountNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereBirthDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereBloodGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereChildrenCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereCv($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereDailyWorkHours($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereDepartmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereDisabilityDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereDrivingLicense($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereDrivingLicenseNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereDrivingLicenseTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereEmployeeCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereEmploymentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereFingerprintCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereFixedAllowance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereFixedShift($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereGovernorateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereGraduationGrade($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereGraduationSpecialization($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereHasAttendance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereHasDisability($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereHasMedicalInsurance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereHasRelative($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereHasSensitiveData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereHasSocialInsurance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereHireDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereHireDateDayMonthYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereHomeAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereHomeTelephone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereJobId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereLanguageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereMaritalStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereMedicalInsuranceAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereMedicalInsuranceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereMilitaryEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereMilitaryExemptionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereMilitaryExemptionReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereMilitaryStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereMilitaryStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereMilitaryWeapon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereMotivationAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereMotivationType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereNationalityExpiryDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereNationalityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereNationalityNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereNationalityPlaceOfIssue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee wherePassportExpiryDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee wherePassportNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee wherePassportPlaceOfIssue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee wherePaymentPerDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee wherePostponementReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereQualificationYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereQualificationsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereRelativeDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereReligionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereResignationDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereResignationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereResignationReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereSalary($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereShiftTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereSocialInsuranceAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereSocialInsuranceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereSponsorName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereStableAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereUrgentContactDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereVacationFormula($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereWorkTelephone($value)
 */
	class Employee extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property int $employee_id
 * @property string $path
 * @property int $company_id
 * @property int $added_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Admin $addedBy
 * @property-read \App\Models\Employee $employee
 * @property-read \App\Models\Admin|null $updatedBy
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereAddedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereUpdatedBy($value)
 */
	class File extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $finance_yr كود السنة المالية
 * @property string $finance_yr_desc
 * @property string $start_date
 * @property string $end_date
 * @property int $status واحد مفعل - صفر معطل
 * @property int $company_id
 * @property int $added_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Admin $addedBy
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FinanceMonthlyCalendar> $financeMonthlyCalendars
 * @property-read int|null $finance_monthly_calendars_count
 * @property-read \App\Models\Admin|null $updatedBy
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinanceCalendar newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinanceCalendar newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinanceCalendar query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinanceCalendar whereAddedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinanceCalendar whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinanceCalendar whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinanceCalendar whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinanceCalendar whereFinanceYr($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinanceCalendar whereFinanceYrDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinanceCalendar whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinanceCalendar whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinanceCalendar whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinanceCalendar whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinanceCalendar whereUpdatedBy($value)
 * @mixin \Eloquent
 */
	class FinanceCalendar extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $financeCalendar_id
 * @property int $number_of_days
 * @property string $year_and_month
 * @property int $finance_yr
 * @property int $month_id
 * @property string $start_date
 * @property string $end_date
 * @property int $status واحد مفعل - صفر معطل
 * @property string $start_date_for_calculation
 * @property string $end_date_for_calculation
 * @property int $company_id
 * @property int $added_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Admin $addedBy
 * @property-read \App\Models\FinanceCalendar $financeCalendar
 * @property-read \App\Models\Month $month
 * @property-read \App\Models\Admin|null $updatedBy
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinanceMonthlyCalendar newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinanceMonthlyCalendar newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinanceMonthlyCalendar query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinanceMonthlyCalendar whereAddedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinanceMonthlyCalendar whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinanceMonthlyCalendar whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinanceMonthlyCalendar whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinanceMonthlyCalendar whereEndDateForCalculation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinanceMonthlyCalendar whereFinanceCalendarId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinanceMonthlyCalendar whereFinanceYr($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinanceMonthlyCalendar whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinanceMonthlyCalendar whereMonthId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinanceMonthlyCalendar whereNumberOfDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinanceMonthlyCalendar whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinanceMonthlyCalendar whereStartDateForCalculation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinanceMonthlyCalendar whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinanceMonthlyCalendar whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinanceMonthlyCalendar whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinanceMonthlyCalendar whereYearAndMonth($value)
 * @mixin \Eloquent
 */
	class FinanceMonthlyCalendar extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property int $status
 * @property int $company_id
 * @property int $country_id
 * @property int $added_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Admin $addedBy
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\City> $cities
 * @property-read int|null $cities_count
 * @property-read \App\Models\Country $country
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Employee> $employees
 * @property-read int|null $employees_count
 * @property-read \App\Models\Admin|null $updatedBy
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Governorate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Governorate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Governorate query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Governorate whereAddedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Governorate whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Governorate whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Governorate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Governorate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Governorate whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Governorate whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Governorate whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Governorate whereUpdatedBy($value)
 */
	class Governorate extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property int $status
 * @property int $company_id
 * @property int $added_by
 * @property int $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Admin $addedBy
 * @property-read \App\Models\Admin $updatedBy
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobsCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobsCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobsCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobsCategory whereAddedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobsCategory whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobsCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobsCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobsCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobsCategory whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobsCategory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobsCategory whereUpdatedBy($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Employee> $employees
 * @property-read int|null $employees_count
 */
	class JobsCategory extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property int $status
 * @property int $company_id
 * @property int $added_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Admin $addedBy
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Employee> $employees
 * @property-read int|null $employees_count
 * @property-read \App\Models\Admin|null $updatedBy
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Language newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Language newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Language query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Language whereAddedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Language whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Language whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Language whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Language whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Language whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Language whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Language whereUpdatedBy($value)
 */
	class Language extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property int $status
 * @property int $company_id
 * @property int $added_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Employee> $employees
 * @property-read int|null $employees_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MilitaryStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MilitaryStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MilitaryStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MilitaryStatus whereAddedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MilitaryStatus whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MilitaryStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MilitaryStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MilitaryStatus whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MilitaryStatus whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MilitaryStatus whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MilitaryStatus whereUpdatedBy($value)
 */
	class MilitaryStatus extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $name_en
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FinanceMonthlyCalendar> $FinanceMonthlyCalendars
 * @property-read int|null $finance_monthly_calendars_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Month newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Month newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Month query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Month whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Month whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Month whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Month whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Month whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Month whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Month extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property int $status
 * @property int $company_id
 * @property int $added_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Admin $addedBy
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Employee> $employees
 * @property-read int|null $employees_count
 * @property-read \App\Models\Admin|null $updatedBy
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nationality newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nationality newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nationality query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nationality whereAddedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nationality whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nationality whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nationality whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nationality whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nationality whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nationality whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nationality whereUpdatedBy($value)
 */
	class Nationality extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $from_date
 * @property string $to_date
 * @property numeric $days_count
 * @property int $status
 * @property int $company_id
 * @property int $added_by
 * @property int $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Admin $addedBy
 * @property-read \App\Models\Admin $updatedBy
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Occasion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Occasion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Occasion query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Occasion whereAddedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Occasion whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Occasion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Occasion whereDaysCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Occasion whereFromDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Occasion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Occasion whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Occasion whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Occasion whereToDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Occasion whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Occasion whereUpdatedBy($value)
 * @mixin \Eloquent
 */
	class Occasion extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property int $status
 * @property int $company_id
 * @property int $added_by
 * @property int $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Admin $addedBy
 * @property-read Admin $updatedBy
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Qualification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Qualification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Qualification query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Qualification whereAddedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Qualification whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Qualification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Qualification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Qualification whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Qualification whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Qualification whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Qualification whereUpdatedBy($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Employee> $employees
 * @property-read int|null $employees_count
 */
	class Qualification extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property int $status
 * @property int $company_id
 * @property int $added_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Admin $addedBy
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Employee> $employees
 * @property-read int|null $employees_count
 * @property-read \App\Models\Admin|null $updatedBy
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Religion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Religion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Religion query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Religion whereAddedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Religion whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Religion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Religion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Religion whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Religion whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Religion whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Religion whereUpdatedBy($value)
 */
	class Religion extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property int $status
 * @property int $company_id
 * @property int $added_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Admin $addedBy
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Employee> $employees
 * @property-read int|null $employees_count
 * @property-read \App\Models\Admin|null $updatedBy
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resignation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resignation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resignation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resignation whereAddedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resignation whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resignation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resignation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resignation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resignation whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resignation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resignation whereUpdatedBy($value)
 */
	class Resignation extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $type 1: Day Shift, 2: Night Shift
 * @property string $start_time
 * @property string $end_time
 * @property numeric $total_hours
 * @property int $status
 * @property int $company_id
 * @property int $created_by
 * @property int $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Admin $createdBy
 * @property-read \App\Models\Admin $updatedBy
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShiftsType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShiftsType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShiftsType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShiftsType whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShiftsType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShiftsType whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShiftsType whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShiftsType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShiftsType whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShiftsType whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShiftsType whereTotalHours($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShiftsType whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShiftsType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShiftsType whereUpdatedBy($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Employee> $employees
 * @property-read int|null $employees_count
 */
	class ShiftsType extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class User extends \Eloquent {}
}

