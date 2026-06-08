<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $main_salary_employee_id
 * @property int $employee_id
 * @property int $finance_monthly_calendar_id
 * @property int $deduction_type 1: days deducted, 2: finger print deduction, 3: Investigation
 * @property numeric $days_amount
 * @property numeric $total
 * @property int|null $is_archived
 * @property int|null $archived_by
 * @property string|null $archived_at
 * @property int $is_auto
 * @property int $status
 * @property int $company_id
 * @property int $added_by
 * @property int|null $updated_by
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Admin $addedBy
 * @property-read \App\Models\Admin|null $archivedBy
 * @property-read \App\Models\Employee $employee
 * @property-read \App\Models\FinanceMonthlyCalendar $financeMonthlyCalendar
 * @property-read \App\Models\MainSalaryEmployee $mainSalaryEmployee
 * @property-read \App\Models\Admin|null $updatedBy
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeDeduction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeDeduction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeDeduction query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeDeduction whereAddedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeDeduction whereArchivedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeDeduction whereArchivedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeDeduction whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeDeduction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeDeduction whereDaysAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeDeduction whereDeductionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeDeduction whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeDeduction whereFinanceMonthlyCalendarId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeDeduction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeDeduction whereIsArchived($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeDeduction whereIsAuto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeDeduction whereMainSalaryEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeDeduction whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeDeduction whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeDeduction whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeDeduction whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MainSalaryEmployeeDeduction whereUpdatedBy($value)
 * @mixin \Eloquent
 */
#[Guarded([])]
class MainSalaryEmployeeDeduction extends Model
{
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
        public function archivedBy()
        {
            return $this->belongsTo(Admin::class, 'archived_by');
        }
        public function addedBy()
        {
            return $this->belongsTo(Admin::class, 'added_by');
        }
        public function updatedBy()
        {
            return $this->belongsTo(Admin::class, 'updated_by');
        }
}
