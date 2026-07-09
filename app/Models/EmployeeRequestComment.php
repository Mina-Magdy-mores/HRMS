<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

#[Fillable(['employee_request_id', 'admin_id', 'comment'])]
class EmployeeRequestComment extends Model
{
    use HasFactory, LogsActivity;

    public function getLogName($actionName)
    {
        return "{$actionName} في تعليقات طلبات الموظفين: " . mb_substr($this->comment, 0, 50) . '...';
    }

    public function request()
    {
        return $this->belongsTo(EmployeeRequest::class, 'employee_request_id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }
}
