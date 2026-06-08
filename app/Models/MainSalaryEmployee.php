<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $finance_monthly_calendar_id
 * @property int $employee_id
 * @property string $employee_name Name of the employee
 * @property int|null $sensitive Indicates if the employee is sensitive
 * @property int $employee_status Status of the employee
 * @property int $employee_job_id
 * @property int $employee_branch_id
 * @property int $employee_department_id
 * @property numeric|null $employee_total_bonus Total bonus of the employee
 * @property numeric|null $employee_total_allowance Total additions of the employee
 * @property numeric|null $employee_total_deduction_type Total deductions of the employee
 * @property numeric|null $employee_total_overtime_days_counter Total overtime of the employee
 * @property numeric|null $employee_total_overtime_payment_per_day Overtime payment per day of the employee
 * @property numeric|null $employee_deductions_days_counter Number of deductions days of the employee
 * @property numeric|null $employee_deductions_payment_total Payment per deductions day of the employee
 * @property numeric|null $employee_additions_days_counter Number of allowance days of the employee
 * @property numeric|null $employee_additions_payment_total Payment per allowance day of the employee
 * @property numeric|null $employee_absences_days_counter Number of absence days of the employee
 * @property numeric|null $employee_absences_payment_total Payment per absence day of the employee
 * @property numeric|null $monthly_loan_amount Amount of the monthly loan
 * @property numeric|null $permanent_loan_amount Amount of the permanent loan
 * @property numeric|null $total_phone_payments Total phone payments
 * @property numeric|null $medical_insurance_amount Amount of the medical insurance
 * @property numeric|null $social_insurance_amount Amount of the social insurance
 * @property numeric|null $fixed_allowance Fixed allowance for the employee
 * @property numeric|null $variable_allowance Variable allowance for the employee
 * @property numeric|null $motivation_amount
 * @property numeric|null $total_benefits Total benefits
 * @property numeric|null $total_deductions Total deductions
 * @property numeric|null $employee_total_penalty_days Number of penalty days of the employee
 * @property numeric|null $employee_salary Salary of the employee
 * @property numeric|null $employee_rollover_amount Rollover amount for the employee
 * @property numeric|null $employee_last_month_salary Last month salary of the employee
 * @property numeric|null $employee_net_salary Net salary of the employee
 * @property numeric|null $employee_net_salary_after_close_for_roll_over
 * @property int|null $is_disbursed
 * @property numeric|null $employee_per_day_salary Per day salary of the employee
 * @property string|null $year_and_month Year and month for the salary record
 * @property int|null $financial_year Financial year for the salary record
 * @property int|null $payment_method Payment method for the salary record
 * @property int|null $payment_on_hold Status of the payment on hold
 * @property int|null $archived_by
 * @property int|null $is_archived Status of the archived employee
 * @property string|null $archived_at Date when the employee was archived
 * @property int $company_id
 * @property int $added_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Admin $addedBy
 * @property-read \App\Models\Admin|null $archivedBy
 * @property-read \App\Models\Branche $branch
 * @property-read \App\Models\Department $department
 * @property-read \App\Models\Employee $employee
 * @property-read \App\Models\FinanceMonthlyCalendar $financeMonthlyCalendar
 * @property-read \App\Models\JobsCategory $job
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
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MainSalaryEmployeePLoanInstallment> $mainSalaryEmployeePLoanInstallments
 * @property-read int|null $main_salary_employee_p_loan_installments_count
 * @property-read \App\Models\Admin|null $updatedBy
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployee newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployee newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployee query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployee whereAddedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployee whereArchivedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployee whereArchivedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployee whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployee whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployee whereEmployeeAbsencesDaysCounter($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployee whereEmployeeAbsencesPaymentTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployee whereEmployeeAdditionsDaysCounter($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployee whereEmployeeAdditionsPaymentTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployee whereEmployeeBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployee whereEmployeeDeductionsDaysCounter($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployee whereEmployeeDeductionsPaymentTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployee whereEmployeeDepartmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployee whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployee whereEmployeeJobId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployee whereEmployeeLastMonthSalary($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployee whereEmployeeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployee whereEmployeeNetSalary($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployee whereEmployeeNetSalaryAfterCloseForRollOver($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployee whereEmployeePerDaySalary($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployee whereEmployeeRolloverAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployee whereEmployeeSalary($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployee whereEmployeeStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployee whereEmployeeTotalAllowance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployee whereEmployeeTotalBonus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployee whereEmployeeTotalDeductionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployee whereEmployeeTotalOvertimeDaysCounter($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployee whereEmployeeTotalOvertimePaymentPerDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployee whereEmployeeTotalPenaltyDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployee whereFinanceMonthlyCalendarId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployee whereFinancialYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployee whereFixedAllowance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployee whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployee whereIsArchived($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployee whereIsDisbursed($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployee whereMedicalInsuranceAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployee whereMonthlyLoanAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployee whereMotivationAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployee wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployee wherePaymentOnHold($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployee wherePermanentLoanAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployee whereSensitive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployee whereSocialInsuranceAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployee whereTotalBenefits($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployee whereTotalDeductions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployee whereTotalPhonePayments($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployee whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployee whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployee whereVariableAllowance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployee whereYearAndMonth($value)
 * @mixin \Eloquent
 */
#[Guarded([])]
class MainSalaryEmployee extends Model
{
    public function addedBy()
    {
        return $this->belongsTo(Admin::class, 'added_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }
    public function archivedBy()
    {
        return $this->belongsTo(Admin::class, 'archived_by ');
    }
    public function financeMonthlyCalendar()
    {
        return $this->belongsTo(FinanceMonthlyCalendar::class, 'finance_monthly_calendar_id');
    }
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
    public function branch()
    {
        return $this->belongsTo(Branche::class, 'employee_branch_id');
    }
    public function department()
    {
        return $this->belongsTo(Department::class, 'employee_department_id');
    }
    public function job()
    {
        return $this->belongsTo(JobsCategory::class, 'employee_job_id');
    }
    public function mainSalaryEmployeeDeductions()
    {
        return $this->hasMany(MainSalaryEmployeeDeduction::class, 'main_salary_employee_id');
    }
    public function mainSalaryEmployeeAbsences()
    {
        return $this->hasMany(MainSalaryEmployeeAbsence::class, 'main_salary_employee_id');
    }

    public function mainSalaryEmployeeDeductionTypes()
    {
        return $this->hasMany(MainSalaryEmployeeDeductionType::class, 'main_salary_employee_id');
    }
    public function mainSalaryEmployeeAdditions()
    {
        return $this->hasMany(MainSalaryEmployeeAddition::class, 'main_salary_employee_id');
    }
    public function mainSalaryEmployeeLoans()
    {
        return $this->hasMany(MainSalaryEmployeeLoan::class, 'main_salary_employee_id');
    }
    public function mainSalaryEmployeeBonuses()
    {
        return $this->hasMany(MainSalaryEmployeeBonus::class, 'main_salary_employee_id');
    }
    public function mainSalaryEmployeeAllowances()
    {
        return $this->hasMany(MainSalaryEmployeeAllowance::class, 'main_salary_employee_id');
    }
    public function mainSalaryEmployeePLoanInstallments()
    {
        return $this->hasMany(MainSalaryEmployeePLoanInstallment::class, 'main_salary_employee_id');
    }
}
