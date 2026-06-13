<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

#[Guarded([])]
#[Table('attendance_departure_actions_excels')]
class AttendanceDepartureActionsExcel extends Model
{

    public function financeMonthlyCalendar()
    {
        return $this->belongsTo(FinanceMonthlyCalendar::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function mainSalaryEmployee()
    {
        return $this->belongsTo(MainSalaryEmployee::class);
    }

    public function addedBy()
    {
        return $this->belongsTo(Admin::class, 'added_by');
    }
}
