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
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EmployeeFixedAllowance> $employeeFixedAllowances
 * @property-read int|null $employee_fixed_allowances_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MainSalaryEmployeeAllowance> $mainSalaryEmployeeAllowances
 * @property-read int|null $main_salary_employee_allowances_count
 * @property-read \App\Models\Admin|null $updatedBy
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AllowanceType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AllowanceType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AllowanceType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AllowanceType whereAddedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AllowanceType whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AllowanceType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AllowanceType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AllowanceType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AllowanceType whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AllowanceType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AllowanceType whereUpdatedBy($value)
 * @mixin \Eloquent
 */
use App\Traits\LogsActivity;

#[Guarded([])]
class AllowanceType extends Model
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
    public function mainSalaryEmployeeAllowances()
    {
        return $this->hasMany(MainSalaryEmployeeAllowance::class);
    }
    public function employeeFixedAllowances()
    {
        return $this->hasMany(EmployeeFixedAllowance::class);
    }
}
