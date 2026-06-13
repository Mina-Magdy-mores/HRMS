<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $financeCalendar_id
 * @property int $number_of_days
 * @property string $year_and_month
 * @property int $finance_yr
 * @property int $month_id
 * @property string $start_date
 * @property string $end_date
 * @property int $status واحد مفعل - صفر معطل - اتنين مغلق و مؤرشف
 * @property string $start_date_for_calculation
 * @property string $end_date_for_calculation
 * @property int $company_id
 * @property int $added_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Admin $addedBy
 * @property-read \App\Models\FinanceCalendar $financeCalendar
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MainSalaryEmployee> $mainSalaryEmployee
 * @property-read int|null $main_salary_employee_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MainSalaryEmployeeAbsence> $mainSalaryEmployeeAbsences
 * @property-read int|null $main_salary_employee_absences_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MainSalaryEmployeeAddition> $mainSalaryEmployeeAdditions
 * @property-read int|null $main_salary_employee_additions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MainSalaryEmployeeAllowance> $mainSalaryEmployeeAllowances
 * @property-read int|null $main_salary_employee_allowances_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MainSalaryEmployeeBonus> $mainSalaryEmployeeBonuses
 * @property-read int|null $main_salary_employee_bonuses_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MainSalaryEmployeeDeductionType> $mainSalaryEmployeeDeductionTypes
 * @property-read int|null $main_salary_employee_deduction_types_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MainSalaryEmployeeDeduction> $mainSalaryEmployeeDeductions
 * @property-read int|null $main_salary_employee_deductions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MainSalaryEmployeeLoan> $mainSalaryEmployeeLoans
 * @property-read int|null $main_salary_employee_loans_count
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
#[Fillable([
    'financeCalendar_id',
    'number_of_days',
    'year_and_month',
    'finance_yr',
    'month_id',
    'start_date',
    'end_date',
    'status',
    'start_date_for_calculation',
    'end_date_for_calculation',
    'company_id',
    'added_by',
    'updated_by'
])]
class FinanceMonthlyCalendar extends Model
{
    public function addedBy()
    {
        return $this->belongsTo(Admin::class, 'added_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }
    public function financeCalendar()
    {
        return $this->belongsTo(FinanceCalendar::class, 'financeCalendar_id');
    }
    public function month()
    {
        return $this->belongsTo(Month::class);
    }
    public function mainSalaryEmployee(){
        return $this->hasMany(MainSalaryEmployee::class, 'finance_monthly_calendar_id');
    }
    public function mainSalaryEmployeeDeductionTypes()
    {
        return $this->hasMany(MainSalaryEmployeeDeductionType::class, 'finance_monthly_calendar_id');
    }

    public function mainSalaryEmployeeAbsences(){
        return $this->hasMany(MainSalaryEmployeeAbsence::class, 'finance_monthly_calendar_id');
    }
    public function mainSalaryEmployeeAllowances(){
        return $this->hasMany(MainSalaryEmployeeAllowance::class, 'finance_monthly_calendar_id');
    }
    public function mainSalaryEmployeeLoans(){
        return $this->hasMany(MainSalaryEmployeeLoan::class, 'finance_monthly_calendar_id');
    }
    public function mainSalaryEmployeeAdditions(){
        return $this->hasMany(MainSalaryEmployeeAddition::class, 'finance_monthly_calendar_id');
    }
    public function mainSalaryEmployeeBonuses(){
        return $this->hasMany(MainSalaryEmployeeBonus::class, 'finance_monthly_calendar_id');
    }
    public function mainSalaryEmployeeDeductions(){
        return $this->hasMany(MainSalaryEmployeeDeduction::class, 'finance_monthly_calendar_id');
    }
    public function attendanceDepartureActionsExcel()
    {
        return $this->hasMany(AttendanceDepartureActionsExcel::class);
    }
    public function attendancesDepartures()
    {
        return $this->hasMany(AttendanceDeparture::class, 'finance_monthly_calendar_id');
    }
    public function attendancesDeparturesActions()
    {
        return $this->hasMany(AttendanceDepartureAction::class, 'finance_monthly_calendar_id');
    }
}
