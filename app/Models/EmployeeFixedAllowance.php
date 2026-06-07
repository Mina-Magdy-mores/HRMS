<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Model;

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
