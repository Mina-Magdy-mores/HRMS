<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

#[Guarded([])]
#[Table('attendances_departures_actions')]
class AttendanceDepartureAction extends Model
{
    public function attendanceDeparture()
    {
        return $this->belongsTo(AttendanceDeparture::class, 'attendances_departure_id');
    }

    public function financeMonthlyCalendar()
    {
        return $this->belongsTo(FinanceMonthlyCalendar::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
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
