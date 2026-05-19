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
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Occasion> $addedOccasions
 * @property-read int|null $added_occasions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Qualification> $addedQualifications
 * @property-read int|null $added_qualifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Occasion> $updatedOccasions
 * @property-read int|null $updated_occasions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Qualification> $updatedQualifications
 * @property-read int|null $updated_qualifications_count
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
 */
	class Branche extends \Eloquent {}
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
 */
	class Department extends \Eloquent {}
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
 */
	class JobsCategory extends \Eloquent {}
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
 * @property-read \App\Models\Admin $addedBy
 * @property-read \App\Models\Admin $updatedBy
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
 */
	class Qualification extends \Eloquent {}
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

