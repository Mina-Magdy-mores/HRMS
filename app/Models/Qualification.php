<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admin;

/**
 * @property int $id
 * @property string $name
 * @property int $status
 * @property int $company_id
 * @property int $added_by
 * @property int $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Admin $addedBy
 * @property-read Admin $updatedBy
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Qualification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Qualification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Qualification query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Qualification whereAddedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Qualification whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Qualification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Qualification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Qualification whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Qualification whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Qualification whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Qualification whereUpdatedBy($value)
 * @mixin \Eloquent
 */
#[Guarded([])]
class Qualification extends Model
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
        return $this->hasMany(Employee::class, 'qualifications_id');
    }
}
