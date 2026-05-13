<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;


#[Fillable(['name', 'name_en'])]
class Month extends Model
{
    protected $table = 'months';
    public function FinanceMonthlyCalendars()
    {
        return $this->hasMany(FinanceMonthlyCalendar::class);
    }
}
