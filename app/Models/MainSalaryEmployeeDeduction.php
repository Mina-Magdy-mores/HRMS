<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Model;

#[Guarded([])]
class MainSalaryEmployeeDeduction extends Model
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
        public function approvedBy()
        {
            return $this->belongsTo(Admin::class, 'approved_by');
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
