<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $from_date
 * @property string $to_date
 * @property numeric $days_count
 * @property int $status
 * @property int $company_id
 * @property int $added_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Admin $addedBy
 * @property-read \App\Models\Admin|null $updatedBy
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Occasion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Occasion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Occasion query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Occasion whereAddedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Occasion whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Occasion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Occasion whereDaysCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Occasion whereFromDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Occasion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Occasion whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Occasion whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Occasion whereToDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Occasion whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Occasion whereUpdatedBy($value)
 * @mixin \Eloquent
 */
#[Guarded([])]

class Occasion extends Model
{
       public function addedBy()
    {
        return $this->belongsTo(Admin::class, 'added_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }
}
