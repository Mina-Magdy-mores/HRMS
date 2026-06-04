<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Model;

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
