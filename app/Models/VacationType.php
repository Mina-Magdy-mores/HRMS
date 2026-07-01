<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Model;

use App\Traits\LogsActivity;

#[Guarded([])]
class VacationType extends Model
{
    use LogsActivity;
    public function addedBy()
    {
        return $this->belongsTo(Admin::class, 'added_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }

    public function attendancesDepartures()
    {
        return $this->hasMany(AttendanceDeparture::class, 'vacation_id');
    }
}
