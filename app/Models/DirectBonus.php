<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

#[Guarded([])]
class DirectBonus extends Model
{
    use LogsActivity;

    public function getLogName($actionName)
    {
        $employeeName = $this->getEmployeeName();
        $bonusTypeName = $this->bonusType ? $this->bonusType->name : 'مكافأة';
        return "{$actionName} مكافأة مباشرة للموظف: {$employeeName} (نوع: {$bonusTypeName}) بقيمة {$this->amount} ج.م";
    }

    public function getLogEmployeeId()
    {
        return $this->employee_id;
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function financeMonthlyCalendar()
    {
        return $this->belongsTo(FinanceMonthlyCalendar::class, 'finance_monthly_calendar_id');
    }

    public function bonusType()
    {
        return $this->belongsTo(Bonus::class, 'bonus_id');
    }

    public function addedBy()
    {
        return $this->belongsTo(Admin::class, 'added_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }
}
