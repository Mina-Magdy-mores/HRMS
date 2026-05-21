<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Model;

#[Guarded([])]
class DrivingLicenseType extends Model
{
    public function employees()
    {
        return $this->hasMany(Employee::class, 'driving_license_type_id');
    }
}
