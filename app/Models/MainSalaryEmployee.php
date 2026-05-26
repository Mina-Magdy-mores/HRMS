<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Model;

#[Guarded([])]
class MainSalaryEmployee extends Model
{
        public function addedBy()
    {
        return $this->belongsTo(Admin::class, 'added_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }
             // $table->foreignId('archived_by')->nullable()->constrained('admins')->cascadeOnUpdate();
            public function financeMonthlyCalendar()
            {
                return $this->belongsTo(FinanceMonthlyCalendar::class, 'finance_monthly_calendars_id');
            }
            public function employee()
            {
                return $this->belongsTo(Employee::class, 'employees_id');
            }
            public function branch(){
                return $this->belongsTo(Branche::class, 'employe_branch_id');
            }
            public function department()
            {
                return $this->belongsTo(Department::class, 'employee_department_id');
            }
            public function job(){
                return $this->belongsTo(JobsCategory::class, 'employee_job_id');
            }

}
