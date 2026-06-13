<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

#[Guarded([])]
#[Table('attendances_departures')]
class AttendanceDeparture extends Model
{
    public function financeMonthlyCalendar()
    {
        return $this->belongsTo(FinanceMonthlyCalendar::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function archivedBy()
    {
        return $this->belongsTo(Admin::class, 'archived_by');
    }

    public function occasion()
    {
        return $this->belongsTo(Occasion::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branche::class, 'employee_branch_id');
    }

    public function mainSalaryEmployee()
    {
        return $this->belongsTo(MainSalaryEmployee::class);
    }

    public function addedBy()
    {
        return $this->belongsTo(Admin::class, 'added_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }

    public function actions()
    {
        return $this->hasMany(AttendanceDepartureAction::class, 'attendances_departure_id');
    }
}
