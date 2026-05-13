<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'financeCalendar_id',
    'number_of_days',
    'year_and_month',
    'finance_yr',
    'month_id',
    'start_date',
    'end_date',
    'status',
    'start_date_for_calculation',
    'end_date_for_calculation',
    'company_id',
    'added_by',
    'updated_by'
])]
class FinanceMonthlyCalendar extends Model
{
    public function addedBy()
    {
        return $this->belongsTo(Admin::class, 'added_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }
    public function financeCalendar()
    {
        return $this->belongsTo(FinanceCalendar::class, 'financeCalendar_id');
    }
    public function month()
    {
        return $this->belongsTo(Month::class);
    }
}
