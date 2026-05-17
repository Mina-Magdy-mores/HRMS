<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;


/**
 * @property int $id
 * @property string $name
 * @property string $name_en
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FinanceMonthlyCalendar> $FinanceMonthlyCalendars
 * @property-read int|null $finance_monthly_calendars_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Month newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Month newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Month query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Month whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Month whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Month whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Month whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Month whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Month whereUpdatedAt($value)
 * @mixin \Eloquent
 */
#[Fillable(['name', 'name_en'])]
class Month extends Model
{
    protected $table = 'months';
    public function FinanceMonthlyCalendars()
    {
        return $this->hasMany(FinanceMonthlyCalendar::class);
    }
}
