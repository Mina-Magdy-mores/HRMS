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
 * @property int $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Admin $addedBy
 * @property-read \App\Models\Admin $updatedBy
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobsCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobsCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobsCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobsCategory whereAddedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobsCategory whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobsCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobsCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobsCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobsCategory whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobsCategory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobsCategory whereUpdatedBy($value)
 * @mixin \Eloquent
 */
#[Guarded([])]
class JobsCategory extends Model
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
        return $this->hasMany(Employee::class, 'job_id');
    }
}
