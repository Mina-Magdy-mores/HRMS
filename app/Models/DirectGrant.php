<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

#[Guarded([])]
class DirectGrant extends Model
{
    use LogsActivity;

    public function getLogName($actionName)
    {
        $employeeName = $this->getEmployeeName();
        $grantTypeName = $this->grantType ? $this->grantType->name : 'منحة';
        return "{$actionName} منحة مالية مباشرة للموظف: {$employeeName} (نوع: {$grantTypeName}) بقيمة {$this->amount} ج.م";
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

    public function grantType()
    {
        return $this->belongsTo(SalaryGrantType::class, 'salary_grant_type_id');
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
