<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'company_name',
    'status',
    'image',
    'phone',
    'address',
    'email',
    'created_by',
    'updated_by',
    'company_id',
    'after_minute_calculate_delay',
    'after_minute_calculate_early_departure',
    'after_minute_quarter_day_cut',
    'after_days_half_day_cut',
    'after_days_allday_day_cut',
    'monthly_vacation_balance',
    'after_days_begin_vacation',
    'first_balance_begin_vacation',
    'sanctions_value_first_absence',
    'sanctions_value_second_absence',
    'sanctions_value_third_absence',
    'sanctions_value_fourth_absence'
])]
class AdminPanelSetting extends Model
{
    use HasFactory;
}
