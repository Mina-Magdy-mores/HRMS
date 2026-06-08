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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nationality newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nationality newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nationality query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nationality whereAddedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nationality whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nationality whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nationality whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nationality whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nationality whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nationality whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nationality whereUpdatedBy($value)
 * @mixin \Eloquent
 */
#[Guarded([])]
class Nationality extends Model
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
        return $this->hasMany(Employee::class, 'nationality_id');
    }
}
