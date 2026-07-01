<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $main_salary_employee_id
 * @property int $employee_id
 * @property int $finance_monthly_calendar_id
 * @property numeric $days_amount days amount of addition
 * @property numeric $total total salary for addition days
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
 * @property-read \App\Models\Employee $employee
 * @property-read \App\Models\FinanceMonthlyCalendar $financeMonthlyCalendar
 * @property-read \App\Models\MainSalaryEmployee $mainSalaryEmployee
 * @property-read \App\Models\Admin|null $updatedBy
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeAddition newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeAddition newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeAddition query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeAddition whereAddedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeAddition whereArchivedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeAddition whereArchivedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeAddition whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeAddition whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeAddition whereDaysAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeAddition whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeAddition whereFinanceMonthlyCalendarId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeAddition whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeAddition whereIsArchived($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeAddition whereIsAuto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeAddition whereMainSalaryEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeAddition whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeAddition whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeAddition whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeAddition whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeAddition whereUpdatedBy($value)
 * @mixin \Eloquent
 */
use App\Traits\LogsActivity;

#[Guarded([])]
class MainSalaryEmployeeAddition extends Model
{
    use LogsActivity;

    public function getLogName($actionName)
    {
        $employeeName = $this->getEmployeeName();
        return "{$actionName} إضافة أيام يدوية للموظف: {$employeeName}";
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
