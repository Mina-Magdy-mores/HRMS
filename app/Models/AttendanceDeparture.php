<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;
    
use App\Traits\LogsActivity;

#[Guarded([])]
#[Table('attendances_departures')]
class AttendanceDeparture extends Model
{
    use LogsActivity;

    public function getLogName($actionName)
    {
        $employeeName = $this->getEmployeeName();
        return "{$actionName} سجل بصمة للموظف: {$employeeName}";
    }

    public function getLogEmployeeId()
    {
        return $this->employee_id;
    }
    public function financeMonthlyCalendar()
    {
        return $this->belongsTo(FinanceMonthlyCalendar::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function archivedBy()
    {
        return $this->belongsTo(Admin::class, 'archived_by');
    }

    public function occasion()
    {
        return $this->belongsTo(Occasion::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branche::class, 'employee_branch_id');
    }

    public function mainSalaryEmployee()
    {
        return $this->belongsTo(MainSalaryEmployee::class);
    }

    public function addedBy()
    {
        return $this->belongsTo(Admin::class, 'added_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }

    public function actions()
    {
        return $this->hasMany(AttendanceDepartureAction::class, 'attendances_departure_id');
    }

    public static function recalculateEmployeeMonth($employeeId, $calendarId, $companyId)
    {
        $employee = Employee::find($employeeId);
        if (empty($employee)) {
            return;
        }

        $settings = AdminPanelSetting::where('company_id', $companyId)->first();

        // Fetch all records chronologically
        $records = self::where([
            'employee_id' => $employeeId,
            'company_id' => $companyId,
            'finance_monthly_calendar_id' => $calendarId
        ])->orderBy('day_of_finger_print', 'asc')->get();

        $delayOrEarlyDaysSoFar = 0;
        $absenceDaysSoFar = 0;

        foreach ($records as $record) {
            $record->recalculate($employee, $companyId, $settings, $delayOrEarlyDaysSoFar, $absenceDaysSoFar);
        }
    }

    public function recalculate($employee, $companyId, $settings, &$delayOrEarlyDaysSoFar, &$absenceDaysSoFar)
    {
        if ($this->is_action_made_on_employee == '1') {
            if ($this->absence_hours > 0 && $this->cutting_days > 0) {
                $absenceDaysSoFar++;
            }
            if ($this->attendance_delay > 0 || $this->early_departure > 0) {
                $delayOrEarlyDaysSoFar++;
            }
            return;
        }

        if (empty($settings)) {
            $settings = AdminPanelSetting::where('company_id', $companyId)->first();
        }
        if (empty($settings)) {
            $settings = (object)[
                'after_minute_calculate_delay' => 15,
                'after_minute_calculate_early_departure' => 15,
                'after_minute_quarter_day_cut' => 3,
                'after_days_half_day_cut' => 3,
                'after_days_allday_day_cut' => 3,
                'after_mins_neglect' => 0,
                'sanctions_value_first_absence' => 1.00,
                'sanctions_value_second_absence' => 2.00,
                'sanctions_value_third_absence' => 3.00,
                'sanctions_value_fourth_absence' => 5.00,
                'first_balance_begin_vacation' => 0,
                'after_days_begin_vacation' => 0,
                'monthly_vacation_balance' => 0
            ];
        }

        // Auto-classify full absence days if no manual action was taken
        if ($this->is_action_made_on_employee == '0' && ($this->vacation_id == 0 || $this->vacation_id == null)) {
            if (!$this->checkInDateTime && !$this->checkOutDateTime) {
                // Check if there is a matched occasion first
                $date = $this->day_of_finger_print;
                $occasions = Occasion::where('company_id', $companyId)->where('status', 1)->get();
                $matchedOccasion = null;
                foreach ($occasions as $occ) {
                    if ($date >= $occ->from_date && $date <= $occ->to_date) {
                        $matchedOccasion = $occ;
                        break;
                    }
                }
                
                if (!$matchedOccasion) {
                    $dayName = date('l', strtotime($date));
                    if ($dayName === 'Friday' || $dayName === 'Saturday') {
                        $this->vacation_id = 5; // راحة أسبوعية
                    } else {
                        $this->vacation_id = 13; // إجازة بدون إذن
                    }
                    $this->update(['vacation_id' => $this->vacation_id]);
                }
            }
        }

        $shiftHours = null;
        $shiftData = null;
        if ($employee->fixed_shift == 1) {
            $shiftData = ShiftsType::where('company_id', $companyId)->find($employee->shift_type_id);
            if ($shiftData) {
                $shiftHours = $shiftData->total_hours;
            }
        } else {
            if ($employee->daily_work_hours > 0) {
                $shiftHours = $employee->daily_work_hours;
            }
        }

        $date = $this->day_of_finger_print;
        $checkInDateTime = $this->checkInDateTime;
        $checkOutDateTime = $this->checkOutDateTime;

        $total_hours = 0;
        $overtime_hours = 0;
        $absence_hours = 0;
        $attendance_delay = 0;
        $early_departure = 0;
        $cutting_days = 0;
        $notes = $this->notes;

        $is_vacation_or_holiday = ($this->vacation_id > 0 || $this->occasion_id > 0);

        if ($checkInDateTime && $checkOutDateTime) {
            $diffInSeconds = strtotime($checkOutDateTime) - strtotime($checkInDateTime);
            $diffInHours = max(0, $diffInSeconds / 3600);
            $total_hours = number_format($diffInHours, 2, '.', '');

            if ($shiftHours !== null) {
                if ($diffInHours < $shiftHours) {
                    $overtime_hours = 0;
                    $absence_hours = number_format($shiftHours - $diffInHours, 2, '.', '');
                } else {
                    $overtime_hours = number_format($diffInHours - $shiftHours, 2, '.', '');
                    $absence_hours = 0;
                }
            }
            if ($notes && str_contains($notes, 'غياب تلقائي')) {
                $notes = 'تم التحديث يدوياً';
            }
        } else {
            // One or both are missing
            if ($is_vacation_or_holiday) {
                $absence_hours = 0;
                $cutting_days = 0;
                $total_hours = 0;
                $overtime_hours = 0;
            } else {
                if (!$checkInDateTime && !$checkOutDateTime) {
                    // Full absence
                    $absence_hours = $shiftHours !== null ? $shiftHours : 0;
                    $total_hours = 0;
                    $overtime_hours = 0;

                    // Match occasion if any exists for this date
                    $occasions = Occasion::where('company_id', $companyId)->where('status', 1)->get();
                    $matchedOccasion = null;
                    foreach ($occasions as $occ) {
                        if ($date >= $occ->from_date && $date <= $occ->to_date) {
                            $matchedOccasion = $occ;
                            break;
                        }
                    }

                    if ($matchedOccasion) {
                        $this->occasion_id = $matchedOccasion->id;
                        $absence_hours = 0;
                        $cutting_days = 0;
                        $notes = 'إجازة رسمية: ' . $matchedOccasion->name;
                    } else {
                        // Check if employee is active for vacation and has vacation balance
                        $has_vacation_balance = false;
                        if ($employee->active_for_vacation == 1 && $employee->hire_date) {
                            $hireDate = Carbon::parse($employee->hire_date);
                            $targetDate = Carbon::parse($date);
                            $daysSinceHire = $hireDate->diffInDays($targetDate, false);

                            if ($daysSinceHire >= 0) {
                                $first_balance = (float)($settings->first_balance_begin_vacation ?? 0);
                                $after_days = (float)($settings->after_days_begin_vacation ?? 0);
                                $monthly_bal = (float)($settings->monthly_vacation_balance ?? 0);

                                $accumulated = $first_balance;
                                if ($daysSinceHire >= $after_days) {
                                    $monthsWorked = $hireDate->diffInMonths($targetDate);
                                    $accumulated += ($monthsWorked * $monthly_bal);
                                }

                                $taken = self::where('employee_id', $employee->id)
                                    ->where('company_id', $companyId)
                                    ->where('day_of_finger_print', '!=', $date)
                                    ->where(function ($query) {
                                        $query->where('vacation_id', '>', 0)
                                            ->orWhere(function ($q) {
                                                $q->where('absence_hours', '>', 0)
                                                    ->where('cutting_days', 0);
                                            });
                                    })
                                    ->count();

                                $available_balance = $accumulated - $taken;
                                if ($available_balance >= 1) {
                                    $has_vacation_balance = true;
                                }
                            }
                        }

                        if ($has_vacation_balance) {
                            $cutting_days = 0;
                            $notes = 'غياب تلقائي (خصماً من رصيد الإجازات المتاح)';
                        } else {
                            $absenceDaysSoFar++;
                            
                            if ($absenceDaysSoFar == 1) {
                                $cutting_days = (float)($settings->sanctions_value_first_absence ?? 1.00);
                            } elseif ($absenceDaysSoFar == 2) {
                                $cutting_days = (float)($settings->sanctions_value_second_absence ?? 2.00);
                            } elseif ($absenceDaysSoFar == 3) {
                                $cutting_days = (float)($settings->sanctions_value_third_absence ?? 3.00);
                            } else {
                                $cutting_days = (float)($settings->sanctions_value_fourth_absence ?? 5.00);
                            }

                            $notes = 'غياب تلقائي (غياب رقم ' . $absenceDaysSoFar . ' - خصم ' . $cutting_days . ' يوم)';
                        }
                    }
                } else {
                    // Only check-in or only check-out
                    $absence_hours = $shiftHours !== null ? $shiftHours : 0;
                    $total_hours = 0;
                    $overtime_hours = 0;
                    $cutting_days = 0;
                    if ($notes && str_contains($notes, 'غياب تلقائي')) {
                        $notes = 'حضور/انصراف ناقص';
                    }
                }
            }
        }

        // Delay calculation (if checkInDateTime exists)
        if ($checkInDateTime && $employee->fixed_shift == 1 && !empty($shiftData)) {
            $shiftStartDateTime = $date . ' ' . $shiftData->start_time;
            if (strtotime($checkInDateTime) > strtotime($shiftStartDateTime)) {
                $diffInSeconds = strtotime($checkInDateTime) - strtotime($shiftStartDateTime);
                $diffInMinutes = $diffInSeconds / 60;
                $fromMinutesIntoDecimalNumber = number_format($diffInMinutes, 2, '.', '');
                if ($fromMinutesIntoDecimalNumber >= $settings->after_minute_calculate_delay) {
                    $attendance_delay = $fromMinutesIntoDecimalNumber;
                }
            }
        }

        // Early departure calculation (if checkOutDateTime exists)
        if ($checkOutDateTime && $employee->fixed_shift == 1 && !empty($shiftData)) {
            $shiftEndDate = $date;
            if ($shiftData->end_time < $shiftData->start_time) {
                $shiftEndDate = date('Y-m-d', strtotime($date . ' +1 day'));
            }
            $shiftEndDateTime = $shiftEndDate . ' ' . $shiftData->end_time;
            if (strtotime($shiftEndDateTime) > strtotime($checkOutDateTime)) {
                $diffInSeconds = strtotime($shiftEndDateTime) - strtotime($checkOutDateTime);
                $diffInMinutes = $diffInSeconds / 60;
                $fromMinutesIntoDecimalNumber = number_format($diffInMinutes, 2, '.', '');
                if ($fromMinutesIntoDecimalNumber >= $settings->after_minute_calculate_early_departure) {
                    $early_departure = $fromMinutesIntoDecimalNumber;
                }
            }
        }

        // Calculate Delay & Early Departure deductions
        if ($attendance_delay > 0 || $early_departure > 0) {
            $delayOrEarlyDaysSoFar++;

            $quarter_times = (float)($settings->after_minute_quarter_day_cut ?? 1.00);
            $half_times = (float)($settings->after_days_half_day_cut ?? 2.00);
            $full_times = (float)($settings->after_days_allday_day_cut ?? 3.00);

            $delay_cut = 0.00;
            if ($delayOrEarlyDaysSoFar == $quarter_times) {
                $delay_cut = 0.25;
            } elseif ($delayOrEarlyDaysSoFar == $half_times) {
                $delay_cut = 0.50;
            } elseif ($delayOrEarlyDaysSoFar >= $full_times) {
                $delay_cut = 1.00;
            }

            $cutting_days += $delay_cut;
        }

        $this->update([
            'total_hours' => $total_hours,
            'overtime_hours' => $overtime_hours,
            'absence_hours' => $absence_hours,
            'attendance_delay' => $attendance_delay,
            'early_departure' => $early_departure,
            'cutting_days' => $cutting_days,
            'notes' => $notes,
        ]);
    }
}
