<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Model;

use App\Traits\LogsActivity;

#[Guarded([])]
class MainEmployeesVacationsBalances extends Model
{
    use LogsActivity;

    public function getLogName($actionName)
    {
        $employeeName = $this->getEmployeeName();
        return "{$actionName} رصيد إجازات للموظف: {$employeeName}";
    }

    public function getLogEmployeeId()
    {
        return $this->employee_id;
    }
    protected $table = 'main_employees_vacations_balances';

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
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

