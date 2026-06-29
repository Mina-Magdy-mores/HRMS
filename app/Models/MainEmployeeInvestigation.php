<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $employee_id
 * @property int $finance_monthly_calendar_id
 * @property int $is_auto
 * @property string|null $description
 * @property int|null $is_archived
 * @property int|null $archived_by
 * @property string|null $archived_at
 * @property int $company_id
 * @property int $added_by
 * @property int|null $updated_by
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Admin $addedBy
 * @property-read \App\Models\Admin|null $archivedBy
 * @property-read \App\Models\Admin|null $updatedBy
 * @property-read \App\Models\Employee $employee
 * @property-read \App\Models\FinanceMonthlyCalendar $financeMonthlyCalendar
 * @mixin \Eloquent
 */
#[Guarded([])]
class MainEmployeeInvestigation extends Model
{
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function financeMonthlyCalendar()
    {
        return $this->belongsTo(FinanceMonthlyCalendar::class);
    }

    public function addedBy()
    {
        return $this->belongsTo(Admin::class, 'added_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }

    public function archivedBy()
    {
        return $this->belongsTo(Admin::class, 'archived_by');
    }
}
