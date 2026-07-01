<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

/**
 * @property int $id
 * @property int $finance_yr كود السنة المالية
 * @property string $finance_yr_desc
 * @property string $start_date
 * @property string $end_date
 * @property int $status واحد مفعل - صفر معطل - اتنين مغلق و مؤرشف
 * @property int $company_id
 * @property int $added_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Admin $addedBy
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FinanceMonthlyCalendar> $financeMonthlyCalendars
 * @property-read int|null $finance_monthly_calendars_count
 * @property-read \App\Models\Admin|null $updatedBy
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinanceCalendar newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinanceCalendar newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinanceCalendar query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinanceCalendar whereAddedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinanceCalendar whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinanceCalendar whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinanceCalendar whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinanceCalendar whereFinanceYr($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinanceCalendar whereFinanceYrDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinanceCalendar whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinanceCalendar whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinanceCalendar whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinanceCalendar whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinanceCalendar whereUpdatedBy($value)
 * @mixin \Eloquent
 */
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
    use LogsActivity;

    protected function getLogDisplayNameField()
    {
        return 'finance_yr_desc';
    }

    public function getLogName($actionName)
    {
        return "{$actionName}: {$this->finance_yr_desc}";
    }

    public function getLogActionName($defaultAction)
    {
        if ($defaultAction === 'تعديل') {
            if ($this->isDirty('status')) {
                if ($this->status == 1) {
                    return 'فتح السنة المالية';
                } elseif ($this->status == 2) {
                    return 'إغلاق السنة المالية';
                }
            }
        }
        return $defaultAction;
    }

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
        return $this->hasMany(FinanceMonthlyCalendar::class, 'financeCalendar_id');
    }
}