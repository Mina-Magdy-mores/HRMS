<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $main_salary_employee_p_loan_id
 * @property int|null $main_salary_employee_id
 * @property numeric $amount loan amount
 * @property numeric $installment_amount_monthly amount of each installment monthly
 * @property string $next_installment_year_and_month year and month of next installment
 * @property string $installment_status 0 is pending for installment,1 is paid for installment on salary,2 is paid for installment cash
 * @property int|null $is_archived
 * @property int|null $archived_by
 * @property string|null $archived_at
 * @property int $company_id
 * @property int $added_by
 * @property int|null $updated_by
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Admin|null $addedBy
 * @property-read \App\Models\Admin|null $archivedBy
 * @property-read \App\Models\MainSalaryEmployee|null $mainSalaryEmployee
 * @property-read \App\Models\MainSalaryEmployeePLoan $mainSalaryEmployeePLoan
 * @property-read \App\Models\Admin|null $updatedBy
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeePLoanInstallment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeePLoanInstallment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeePLoanInstallment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeePLoanInstallment whereAddedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeePLoanInstallment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeePLoanInstallment whereArchivedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeePLoanInstallment whereArchivedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeePLoanInstallment whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeePLoanInstallment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeePLoanInstallment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeePLoanInstallment whereInstallmentAmountMonthly($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeePLoanInstallment whereInstallmentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeePLoanInstallment whereIsArchived($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeePLoanInstallment whereMainSalaryEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeePLoanInstallment whereMainSalaryEmployeePLoanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeePLoanInstallment whereNextInstallmentYearAndMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeePLoanInstallment whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeePLoanInstallment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeePLoanInstallment whereUpdatedBy($value)
 * @mixin \Eloquent
 */
#[Guarded([])]
class MainSalaryEmployeePLoanInstallment extends Model
{
    public function mainSalaryEmployeePLoan()
    {
        return $this->belongsTo(MainSalaryEmployeePLoan::class);
    }
    public function mainSalaryEmployee()
    {
        return $this->belongsTo(MainSalaryEmployee::class);
    }
    public function archivedBy()
    {
        return $this->belongsTo(Admin::class);
    }
    public function addedBy()
    {
        return $this->belongsTo(Admin::class);
    }
    public function updatedBy()
    {
        return $this->belongsTo(Admin::class);
    }
}
