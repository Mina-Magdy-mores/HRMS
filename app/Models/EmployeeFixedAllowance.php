<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $employee_id
 * @property int $allowance_type_id
 * @property numeric $amount
 * @property int $company_id
 * @property int $added_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Admin|null $addedBy
 * @property-read \App\Models\AllowanceType $allowanceType
 * @property-read \App\Models\Employee $employee
 * @property-read \App\Models\Admin|null $updatedBy
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmployeeFixedAllowance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmployeeFixedAllowance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmployeeFixedAllowance query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmployeeFixedAllowance whereAddedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmployeeFixedAllowance whereAllowanceTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmployeeFixedAllowance whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmployeeFixedAllowance whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmployeeFixedAllowance whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmployeeFixedAllowance whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmployeeFixedAllowance whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmployeeFixedAllowance whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmployeeFixedAllowance whereUpdatedBy($value)
 * @mixin \Eloquent
 */
#[Guarded([])]
class EmployeeFixedAllowance extends Model
{

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
    public function allowanceType()
    {
        return $this->belongsTo(AllowanceType::class);
    }
    public function addedBy()
    {
        return $this->belongsTo(Admin::class);
    }
    public function updatedBy()
    {
        return $this->belongsTo(Admin::class);
    }
}
