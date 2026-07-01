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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeductionType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeductionType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeductionType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeductionType whereAddedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeductionType whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeductionType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeductionType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeductionType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeductionType whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeductionType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeductionType whereUpdatedBy($value)
 * @mixin \Eloquent
 */
use App\Traits\LogsActivity;

class DeductionType extends Model
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

    public function mainSalaryEmployeeDeductionTypes()
    {
        return $this->hasMany(MainSalaryEmployeeDeductionType::class, 'deduction_type_id');
    }
}
