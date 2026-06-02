<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Model;

#[Guarded([])]
class MainSalaryEmployeeAllowance extends Model
{
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

    public function allowanceType()
    {
        return $this->belongsTo(AllowanceType::class);
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
}
