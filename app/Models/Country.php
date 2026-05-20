<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Model;
#[Guarded([])]

class Country extends Model
{
    public function addedBy()
    {
        return $this->belongsTo(Admin::class, 'added_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }

    public function governorates()
    {
        return $this->hasMany(Governorate::class, 'country_id');
    }

    public function employees()
    {
        return $this->hasMany(Employee::class, 'country_id');
    }
}
