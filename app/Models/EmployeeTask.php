<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

#[Fillable([
    'title',
    'content',
    'employee_id',
    'is_completed',
    'is_archived',
    'archived_by',
    'archived_at',
    'company_id',
    'added_by',
    'updated_by',
    'notes',
    'employee_reply',
    'employee_replied_at'
])]
class EmployeeTask extends Model
{
    use LogsActivity;

    protected $table = 'employee_tasks';

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
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

    public function getLogName($actionName)
    {
        return "{$actionName} في مهام الموظفين: {$this->title}";
    }

    public function getLogActionName($defaultAction)
    {
        if ($defaultAction === 'تعديل') {
            if ($this->isDirty('is_archived') && $this->is_archived == 1) {
                return 'أرشفة';
            }
            if ($this->isDirty('is_completed')) {
                return 'تغيير حالة';
            }
            if ($this->isDirty('employee_reply')) {
                return 'رد الموظف';
            }
        }
        return $defaultAction;
    }

    public function comments()
    {
        return $this->hasMany(EmployeeTaskComment::class, 'employee_task_id')->orderBy('id', 'asc');
    }

    public function getLogEmployeeId()
    {
        return $this->employee_id;
    }
}
