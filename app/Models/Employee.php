<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Model;

#[Guarded([])]
class Employee extends Model
{


    public function addedBy()
    {
        return $this->belongsTo(Admin::class, 'added_by');
    }
    public function updatedBy()
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }

    public function resignation()
    {
        return $this->belongsTo(Resignation::class, 'resignation_id');
    }

    public function religion()
    {
        return $this->belongsTo(Religion::class, 'religion_id');
    }

    public function qualification()
    {
        return $this->belongsTo(Qualification::class, 'qualifications_id');
    }

    public function job()
    {
        return $this->belongsTo(JobsCategory::class, 'job_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function nationality()
    {
        return $this->belongsTo(Nationality::class, 'nationality_id');
    }

    public function shiftType()
    {
        return $this->belongsTo(ShiftsType::class, 'shift_type_id');
    }
    public function bloodGroup()
    {
        return $this->belongsTo(BloodGroup::class, 'blood_group_id');
    }
    public function branch()
    {
        return $this->belongsTo(Branche::class, 'branch_id');
    }
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
    public function governorate()
    {
        return $this->belongsTo(Governorate::class, 'governorate_id');
    }
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }


}
