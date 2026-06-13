<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;


/**
 * @property int $id
 * @property string $name
 * @property string $address
 * @property string $phone
 * @property string|null $email
 * @property int $status
 * @property int $created_by
 * @property int|null $updated_by
 * @property int $company_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Admin $createdBy
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Employee> $employees
 * @property-read int|null $employees_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MainSalaryEmployee> $mainSalaryEmployee
 * @property-read int|null $main_salary_employee_count
 * @property-read \App\Models\Admin|null $updatedBy
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branche newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branche newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branche query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branche whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branche whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branche whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branche whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branche whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branche whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branche whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branche wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branche whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branche whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branche whereUpdatedBy($value)
 * @mixin \Eloquent
 */
#[Fillable(['name', 'address', 'phone', 'email', 'status', 'created_by', 'updated_by', 'company_id'])]
class Branche extends Model
{
    public function createdBy()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }
            public function employees()
    {
        return $this->hasMany(Employee::class,'branch_id');
    }
    public function mainSalaryEmployee(){
        return $this->hasMany(MainSalaryEmployee::class, 'employee_branch_id');
    }
    public function attendancesDepartures()
    {
        return $this->hasMany(AttendanceDeparture::class, 'employee_branch_id');
    }
}
