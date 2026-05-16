<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'number', 'description', 'company_id', 'status', 'created_by', 'updated_by'])]
class Department extends Model
{
public function createdBy()
{
    return $this->belongsTo(Admin::class, 'created_by');
}

public function updatedBy()
{
    return $this->belongsTo(Admin::class, 'updated_by');
}
}
