<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'finance_calendar_id',
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
    'updated_by',
])]
class Finance_calendar extends Model
{
    //
}
