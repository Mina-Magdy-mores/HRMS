<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Model;


#[Guarded([])]
class MainSalaryEmployeePLoan extends Model
{
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function disbursedBy()
    {
        return $this->belongsTo(Admin::class, 'disbursed_by');
    }

    public function archivedBy()
    {
        return $this->belongsTo(Admin::class, 'archived_by');
    }

    public function addedBy()
    {
        return $this->belongsTo(Admin::class, 'added_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }
    public function mainSalaryEmployeePLoanInstallments()
    {
        return $this->hasMany(MainSalaryEmployeePLoanInstallment::class);
    }
}
