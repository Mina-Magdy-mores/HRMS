<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

#[Guarded([])]
class MainSalaryEmployeeSettlement extends Model
{
    use LogsActivity;

    public function getLogName($actionName)
    {
        $employeeName = $this->getEmployeeName();
        return "{$actionName} تسوية راتب مؤرشف للموظف: {$employeeName}";
    }

    public function getLogEmployeeId()
    {
        return $this->employee_id;
    }

    public function mainSalaryEmployee()
    {
        return $this->belongsTo(MainSalaryEmployee::class, 'employee_id', 'employee_id')
            ->where('finance_monthly_calendar_id', $this->finance_monthly_calendar_id);
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
