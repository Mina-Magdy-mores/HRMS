<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $company_name
 * @property int $status واحد مفعل - صفر معطل
 * @property int $is_active_system_monitoring هل المراقب للنظام مفعل ام لا
 * @property string|null $image
 * @property string $phone
 * @property string $address
 * @property string $email
 * @property int $created_by
 * @property int|null $updated_by
 * @property int $company_id
 * @property numeric $after_minute_calculate_delay بعد كم عدد دقيقة نحسب تاخير حضور
 * @property numeric $after_minute_calculate_early_departure بعد كم عدد دقيقة نحسب انصراف مبكر
 * @property numeric $after_minute_quarter_day_cut بعد كم عدد دقيقة من مجموع الحضور او الانصراف مبكر نخصم ربع يوم
 * @property numeric $after_days_half_day_cut بعد كم مرة تاخير او انصراف مبكر نخصم نص يوم
 * @property numeric $after_days_allday_day_cut بعد كم مرة تاخير او انصراف مبكر نخصم يوم كامل
 * @property numeric $monthly_vacation_balance رصيد الاجازات الشهرية
 * @property numeric $after_days_begin_vacation بعد كام يوم ينزل للموظف رصيد الاجازات الشهرية
 * @property numeric $first_balance_begin_vacation رصيد الاجازات الأولي عند بدء العمل
 * @property numeric $sanctions_value_first_absence قيمه خصم الايام بعد اول مرة غياب بدون اذن
 * @property numeric $sanctions_value_second_absence قيمه خصم الايام بعد ثاني مرة غياب بدون اذن
 * @property numeric $sanctions_value_third_absence قيمه خصم الايام بعد ثالث مرة غياب بدون اذن
 * @property numeric $sanctions_value_fourth_absence قيمه خصم الايام بعد رابع مرة غياب بدون اذن
 * @property int $after_shift_max_extra_hours أقصى عدد ساعات عمل إضافية بعد انتهاء الشيفت لتقفيل البصمة كـانصراف
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Admin $addedBy
 * @property-read \App\Models\Admin|null $updatedBy
 * @method static \Database\Factories\AdminPanelSettingFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPanelSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPanelSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPanelSetting query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPanelSetting whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPanelSetting whereAfterDaysAlldayDayCut($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPanelSetting whereAfterDaysBeginVacation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPanelSetting whereAfterDaysHalfDayCut($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPanelSetting whereAfterMinuteCalculateDelay($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPanelSetting whereAfterMinuteCalculateEarlyDeparture($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPanelSetting whereAfterMinuteQuarterDayCut($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPanelSetting whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPanelSetting whereCompanyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPanelSetting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPanelSetting whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPanelSetting whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPanelSetting whereFirstBalanceBeginVacation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPanelSetting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPanelSetting whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPanelSetting whereMonthlyVacationBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPanelSetting wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPanelSetting whereSanctionsValueFirstAbsence($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPanelSetting whereSanctionsValueFourthAbsence($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPanelSetting whereSanctionsValueSecondAbsence($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPanelSetting whereSanctionsValueThirdAbsence($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPanelSetting whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPanelSetting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPanelSetting whereUpdatedBy($value)
 * @mixin \Eloquent
 */
use App\Traits\LogsActivity;

#[Guarded([])]
class AdminPanelSetting extends Model
{
    use HasFactory, LogsActivity;

    protected function getLogDisplayNameField()
    {
        return 'company_name';
    }

    public function addedBy()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }
}
