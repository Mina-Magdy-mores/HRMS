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
 * @property-read \App\Models\Admin|null $updatedBy
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bonus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bonus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bonus query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bonus whereAddedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bonus whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bonus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bonus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bonus whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bonus whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bonus whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bonus whereUpdatedBy($value)
 * @mixin \Eloquent
 */
use App\Traits\LogsActivity;

class Bonus extends Model
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

    public function mainSalaryEmployeeBonuses()
    {
        return $this->hasMany(MainSalaryEmployeeBonus::class, 'bonus_id');
    }
}
