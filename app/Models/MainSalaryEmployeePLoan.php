<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Model;


/**
 * @property int $id
 * @property int $employee_id
 * @property numeric $employee_basic_salary employee basic salary for this loan
 * @property numeric $amount loan amount
 * @property int $number_of_installment_months number of installments for loan
 * @property numeric $installment_amount_monthly amount of each installment monthly
 * @property string $next_installment_year_and_month year and month of next installment
 * @property string $next_installment_date date of next installment
 * @property numeric $paid_amount paid amount for loan
 * @property numeric $remaining_amount remaining amount for loan
 * @property int|null $is_archived
 * @property int|null $is_disbursed is loan disbursed
 * @property int|null $disbursed_by
 * @property string|null $disbursed_at
 * @property int|null $archived_by
 * @property string|null $archived_at
 * @property int $company_id
 * @property int $added_by
 * @property int|null $updated_by
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Admin $addedBy
 * @property-read \App\Models\Admin|null $archivedBy
 * @property-read \App\Models\Admin|null $disbursedBy
 * @property-read \App\Models\Employee $employee
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MainSalaryEmployeePLoanInstallment> $mainSalaryEmployeePLoanInstallments
 * @property-read int|null $main_salary_employee_p_loan_installments_count
 * @property-read \App\Models\Admin|null $updatedBy
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeePLoan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeePLoan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeePLoan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeePLoan whereAddedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeePLoan whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeePLoan whereArchivedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeePLoan whereArchivedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeePLoan whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeePLoan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeePLoan whereDisbursedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeePLoan whereDisbursedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeePLoan whereEmployeeBasicSalary($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeePLoan whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeePLoan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeePLoan whereInstallmentAmountMonthly($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeePLoan whereIsArchived($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeePLoan whereIsDisbursed($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeePLoan whereNextInstallmentDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeePLoan whereNextInstallmentYearAndMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeePLoan whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeePLoan whereNumberOfInstallmentMonths($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeePLoan wherePaidAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeePLoan whereRemainingAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeePLoan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeePLoan whereUpdatedBy($value)
 * @mixin \Eloquent
 */
#[Guarded([])]
class MainSalaryEmployeePLoan extends Model
{
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function disbursedBy()
    {
        return $this->belongsTo(Admin::class, 'disbursed_by');
    }

    public function archivedBy()
    {
        return $this->belongsTo(Admin::class, 'archived_by');
    }

    public function addedBy()
    {
        return $this->belongsTo(Admin::class, 'added_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }
    public function mainSalaryEmployeePLoanInstallments()
    {
        return $this->hasMany(MainSalaryEmployeePLoanInstallment::class);
    }
}
