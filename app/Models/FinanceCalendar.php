<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'finance_yr',
    'finance_yr_desc',
    'start_date',
    'end_date',
    'status',
    'company_id',
    'added_by',
    'updated_by'
])]
class FinanceCalendar extends Model
{
    public function addedBy()
    {
        return $this->belongsTo(Admin::class, 'added_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }
    public function financeMonthlyCalendars()
    {
        return $this->hasMany(FinanceMonthlyCalendar::class);
    }
}
