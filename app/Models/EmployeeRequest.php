<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

#[Fillable([
    'employee_id', 'employee_request_type_id', 'title', 'content', 'status', 
    'is_archived', 'archived_by', 'archived_at', 'company_id', 'added_by', 'updated_by'
])]
class EmployeeRequest extends Model
{
    use HasFactory, LogsActivity;

    public function getLogName($actionName)
    {
        $action = $this->getLogActionName($actionName);
        return "{$action} في طلبات الموظفين: {$this->title}";
    }

    public function getLogActionName($defaultAction)
    {
        if ($this->isDirty('is_archived') && $this->is_archived == 1) {
            return 'أرشفة';
        }
        if ($this->isDirty('status')) {
            return 'تغيير الحالة';
        }
        return $defaultAction;
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function type()
    {
        return $this->belongsTo(EmployeeRequestType::class, 'employee_request_type_id');
    }

    public function addedBy()
    {
        return $this->belongsTo(Admin::class, 'added_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }

    public function archivedBy()
    {
        return $this->belongsTo(Admin::class, 'archived_by');
    }

    public function comments()
    {
        return $this->hasMany(EmployeeRequestComment::class);
    }
}
