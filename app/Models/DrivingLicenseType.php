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
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Employee> $employees
 * @property-read int|null $employees_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DrivingLicenseType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DrivingLicenseType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DrivingLicenseType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DrivingLicenseType whereAddedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DrivingLicenseType whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DrivingLicenseType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DrivingLicenseType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DrivingLicenseType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DrivingLicenseType whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DrivingLicenseType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DrivingLicenseType whereUpdatedBy($value)
 * @mixin \Eloquent
 */
#[Guarded([])]
class DrivingLicenseType extends Model
{
    public function employees()
    {
        return $this->hasMany(Employee::class, 'driving_license_type_id');
    }
}
