<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Model;

#[Guarded([])]

class Governorate extends Model
{
    public function addedBy()
    {
        return $this->belongsTo(Admin::class, 'added_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function cities()
    {
        return $this->hasMany(City::class, 'governorate_id');
    }

    public function employees()
    {
        return $this->hasMany(Employee::class, 'governorate_id');
    }
}
