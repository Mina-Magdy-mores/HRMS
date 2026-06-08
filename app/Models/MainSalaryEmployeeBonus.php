<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $main_salary_employee_id
 * @property int $employee_id
 * @property int $finance_monthly_calendar_id
 * @property int $bonus_id
 * @property numeric $amount
 * @property int|null $is_archived
 * @property int|null $archived_by
 * @property string|null $archived_at
 * @property int $is_auto 0 => no, 1 => yes
 * @property int $status 1 => active, 0 => inactive
 * @property int $company_id
 * @property int $added_by
 * @property int|null $updated_by
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Admin $addedBy
 * @property-read \App\Models\Admin|null $archivedBy
 * @property-read \App\Models\Bonus $bonus
 * @property-read \App\Models\Employee $employee
 * @property-read \App\Models\FinanceMonthlyCalendar $financeMonthlyCalendar
 * @property-read \App\Models\MainSalaryEmployee $mainSalaryEmployee
 * @property-read \App\Models\Admin|null $updatedBy
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeBonus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeBonus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeBonus query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeBonus whereAddedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeBonus whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeBonus whereArchivedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeBonus whereArchivedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeBonus whereBonusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeBonus whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeBonus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeBonus whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeBonus whereFinanceMonthlyCalendarId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeBonus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeBonus whereIsArchived($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeBonus whereIsAuto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeBonus whereMainSalaryEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeBonus whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeBonus whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeBonus whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeBonus whereUpdatedBy($value)
 * @mixin \Eloquent
 */
#[Guarded([])]
class MainSalaryEmployeeBonus extends Model
{
    protected $table = 'main_salary_employee_bonuses';

    public function mainSalaryEmployee()
    {
        return $this->belongsTo(MainSalaryEmployee::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function financeMonthlyCalendar()
    {
        return $this->belongsTo(FinanceMonthlyCalendar::class);
    }

    public function bonus()
    {
        return $this->belongsTo(Bonus::class);
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
