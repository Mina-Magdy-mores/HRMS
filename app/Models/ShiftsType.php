<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int|null $type 1: Day Shift, 2: Night Shift, 3: Full day Shift
 * @property string $start_time
 * @property string $end_time
 * @property numeric $total_hours
 * @property int $status
 * @property int $company_id
 * @property int $created_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Admin $createdBy
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Employee> $employees
 * @property-read int|null $employees_count
 * @property-read \App\Models\Admin|null $updatedBy
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShiftsType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShiftsType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShiftsType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShiftsType whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShiftsType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShiftsType whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShiftsType whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShiftsType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShiftsType whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShiftsType whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShiftsType whereTotalHours($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShiftsType whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShiftsType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShiftsType whereUpdatedBy($value)
 * @mixin \Eloquent
 */
#[Fillable(['type', 'start_time', 'end_time', 'total_hours', 'status', 'company_id', 'created_by', 'updated_by'])]
class ShiftsType extends Model
{
    public function createdBy()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }
    public function updatedBy()
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }

    public function employees()
    {
        return $this->hasMany(Employee::class, 'shift_type_id');
    }
}
