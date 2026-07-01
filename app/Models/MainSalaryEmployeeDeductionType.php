<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $main_salary_employee_id
 * @property int $employee_id
 * @property int $finance_monthly_calendar_id
 * @property int $deduction_type_id
 * @property numeric $amount
 * @property int|null $is_archived
 * @property int|null $archived_by
 * @property string|null $archived_at
 * @property int $is_auto
 * @property int $status
 * @property int $company_id
 * @property int $added_by
 * @property int|null $updated_by
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Admin $addedBy
 * @property-read \App\Models\Admin|null $archivedBy
 * @property-read \App\Models\DeductionType $deductionType
 * @property-read \App\Models\Employee $employee
 * @property-read \App\Models\FinanceMonthlyCalendar $financeMonthlyCalendar
 * @property-read \App\Models\MainSalaryEmployee $mainSalaryEmployee
 * @property-read \App\Models\Admin|null $updatedBy
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeDeductionType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeDeductionType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeDeductionType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeDeductionType whereAddedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeDeductionType whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeDeductionType whereArchivedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeDeductionType whereArchivedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeDeductionType whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeDeductionType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeDeductionType whereDeductionTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeDeductionType whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeDeductionType whereFinanceMonthlyCalendarId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeDeductionType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeDeductionType whereIsArchived($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeDeductionType whereIsAuto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeDeductionType whereMainSalaryEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeDeductionType whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeDeductionType whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeDeductionType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeDeductionType whereUpdatedBy($value)
 * @mixin \Eloquent
 */
use App\Traits\LogsActivity;

#[Guarded([])]
class MainSalaryEmployeeDeductionType extends Model
{
    use LogsActivity;

    public function getLogName($actionName)
    {
        $employeeName = $this->getEmployeeName();
        return "{$actionName} جزاء يدوي للموظف: {$employeeName}";
    }

    public function getLogEmployeeId()
    {
        return $this->employee_id;
    }
    public function mainSalaryEmployee()
    {
        return $this->belongsTo(MainSalaryEmployee::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function financeMonthlyCalendar()
    {
        return $this->belongsTo(FinanceMonthlyCalendar::class);
    }

    public function deductionType()
    {
        return $this->belongsTo(DeductionType::class);
    }

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
        return $this->belongsTo(Admin::class, 'archived_by');
    }
}
