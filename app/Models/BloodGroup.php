<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property int $status
 * @property int $company_id
 * @property int $added_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Admin $addedBy
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Employee> $employees
 * @property-read int|null $employees_count
 * @property-read \App\Models\Admin|null $updatedBy
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodGroup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodGroup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodGroup query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodGroup whereAddedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodGroup whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodGroup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodGroup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodGroup whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodGroup whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodGroup whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodGroup whereUpdatedBy($value)
 * @mixin \Eloquent
 */
#[Guarded([])]
class BloodGroup extends Model
{
    public function addedBy()
    {
        return $this->belongsTo(Admin::class, 'added_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }
    public function employees()
    {
        return $this->hasMany(Employee::class, 'blood_group_id');
    }

}
