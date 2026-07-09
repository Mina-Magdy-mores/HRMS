<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

#[Fillable(['employee_task_id', 'admin_id', 'comment'])]
class EmployeeTaskComment extends Model
{
    use HasFactory, LogsActivity;

    public function getLogName($actionName)
    {
        return "{$actionName} في تعليقات مهام الموظفين: " . mb_substr($this->comment, 0, 50) . '...';
    }

    public function task()
    {
        return $this->belongsTo(EmployeeTask::class, 'employee_task_id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }
}
