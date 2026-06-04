<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Model;

#[Guarded([])]
class MainSalaryEmployeePLoanInstallment extends Model
{
    public function mainSalaryEmployeePLoan()
    {
        return $this->belongsTo(MainSalaryEmployeePLoan::class);
    }
    public function mainSalaryEmployee()
    {
        return $this->belongsTo(MainSalaryEmployee::class);
    }
    public function archivedBy()
    {
        return $this->belongsTo(Admin::class);
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
