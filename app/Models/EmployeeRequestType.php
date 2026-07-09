<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

#[Fillable(['name', 'is_active', 'company_id', 'added_by', 'updated_by'])]
class EmployeeRequestType extends Model
{
    use HasFactory, LogsActivity;

    public function getLogName($actionName)
    {
        return "{$actionName} في أنواع طلبات الموظفين: {$this->name}";
    }

    public function addedBy()
    {
        return $this->belongsTo(Admin::class, 'added_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }
}
