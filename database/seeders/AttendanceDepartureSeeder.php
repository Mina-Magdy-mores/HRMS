<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\FinanceMonthlyCalendar;
use App\Models\AttendanceDeparture;
use App\Models\AttendanceDepartureActionsExcel;
use App\Models\ShiftsType;
use App\Models\AdminPanelSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttendanceDepartureSeeder extends Seeder
{
    public function run(): void
    {
        $employees = Employee::where('company_id', 1)->get();

        $calendars = FinanceMonthlyCalendar::where('finance_yr', 2026)
            ->where('company_id', 1)
            ->where('month_id', '<=', 7)
            ->orderBy('month_id')
            ->get();

        if ($employees->isEmpty() || $calendars->isEmpty()) {
            $this->command->warn('⚠️ No employees or calendars found.');
            return;
        }

        $settings  = AdminPanelSetting::where('company_id', 1)->first();
        $allShifts = ShiftsType::where('company_id', 1)->where('status', 1)->get();

        // ── الحد الأدنى للتأخير والانصراف المبكر لاحتساب الدقائق ──
        $delayThreshold = $settings ? (float)$settings->after_minute_calculate_delay           : 15;
        $earlyThreshold = $settings ? (float)$settings->after_minute_calculate_early_departure : 15;

        // ── تنظيف البيانات القديمة ────────────────────────────────────
        $calendarIds = $calendars->pluck('id')->toArray();
        DB::table('attendances_departures_actions')
            ->whereIn('finance_monthly_calendar_id', $calendarIds)->delete();
        DB::table('attendance_departure_actions_excels')
            ->whereIn('finance_monthly_calendar_id', $calendarIds)->delete();
        AttendanceDeparture::whereIn('finance_monthly_calendar_id', $calendarIds)->delete();

        // ── سيناريوهات الحضور (offset بالدقائق من بداية / نهاية الشيفت) ──
        // in_offset: + تأخير، - حضور مبكر
        // out_offset: + وقت إضافي، - انصراف مبكر
        $scenarios = [
            ['type' => 'on_time',   'in' =>  0,  'out' =>   0],  // منضبط تماماً
            ['type' => 'on_time',   'in' => -5,  'out' =>   5],  // مبكر قليلاً
            ['type' => 'on_time',   'in' =>  3,  'out' =>  -3],  // شبه منضبط
            ['type' => 'on_time',   'in' =>  0,  'out' =>  15],  // ربع ساعة إضافية
            ['type' => 'on_time',   'in' => -3,  'out' =>   0],
            ['type' => 'delay',     'in' => 20,  'out' =>   0],  // تأخير 20 د
            ['type' => 'delay',     'in' => 35,  'out' =>   5],  // تأخير 35 د
            ['type' => 'delay',     'in' => 45,  'out' =>  10],  // تأخير 45 د
            ['type' => 'delay',     'in' => 60,  'out' =>   0],  // تأخير ساعة
            ['type' => 'early_out', 'in' =>  0,  'out' => -20],  // انصراف مبكر 20 د
            ['type' => 'early_out', 'in' =>  5,  'out' => -30],  // انصراف مبكر 30 د
            ['type' => 'overtime',  'in' =>  0,  'out' =>  60],  // ساعة إضافية
            ['type' => 'overtime',  'in' => -5,  'out' =>  90],  // 1.5 ساعة إضافية
        ];

        foreach ($calendars as $calendar) {
            $yearMonth   = $calendar->year_and_month;
            $daysInMonth = $calendar->number_of_days;

            foreach ($employees as $employee) {
                // ── تحديد شيفت الموظف ──────────────────────────────────
                $shiftData = null;
                if ($employee->fixed_shift == 1 && $employee->shift_type_id) {
                    $shiftData = $allShifts->firstWhere('id', $employee->shift_type_id);
                }

                $shiftStart = $shiftData ? $shiftData->start_time : '09:00:00';
                $shiftEnd   = $shiftData ? $shiftData->end_time   : '17:00:00';
                $shiftHours = $shiftData ? (float)$shiftData->total_hours
                            : (float)($employee->daily_work_hours ?? 8);

                list($ssh, $ssm) = explode(':', $shiftStart);
                list($seh, $sem) = explode(':', $shiftEnd);
                $shiftStartMins = (int)$ssh * 60 + (int)$ssm;
                $shiftEndMins   = (int)$seh * 60 + (int)$sem;
                if ($shiftEndMins <= $shiftStartMins) {
                    $shiftEndMins += 1440; // شيفت ليلي
                }

                $absentCount = 0;

                for ($day = 1; $day <= $daysInMonth; $day++) {
                    // تحقق من وجود اليوم فعلياً
                    if (!checkdate(
                        (int)substr($yearMonth, 5, 2),
                        $day,
                        (int)substr($yearMonth, 0, 4)
                    )) {
                        continue;
                    }

                    $date      = sprintf('%s-%02d', $yearMonth, $day);
                    $dayOfWeek = date('w', strtotime($date)); // 0=أحد ... 6=سبت

                    // شهر يوليو: فقط لغاية اليوم 9
                    if ($yearMonth == '2026-07' && $day > 9) {
                        continue;
                    }

                    // ── عطلة أسبوعية (جمعة=5, سبت=6) ──────────────────
                    if ($dayOfWeek == 5 || $dayOfWeek == 6) {
                        AttendanceDeparture::create([
                            'finance_monthly_calendar_id'               => $calendar->id,
                            'employee_id'                               => $employee->id,
                            'day_of_finger_print'                       => $date,
                            'shift_hours'                               => $shiftHours,
                            'status_move'                               => '2',
                            'checkInDate'                               => null,
                            'checkOutDate'                              => null,
                            'checkInTime'                               => null,
                            'checkOutTime'                              => null,
                            'checkInDateTime'                           => null,
                            'checkOutDateTime'                          => null,
                            'variables'                                 => null,
                            'attendance_delay'                          => 0,
                            'early_departure'                           => 0,
                            'approved_attendance_delay_early_departure' => null,
                            'total_hours'                               => 0,
                            'absence_hours'                             => 0,
                            'overtime_hours'                            => 0,
                            'cutting_days'                              => 0,
                            'vacation_id'                               => 5,
                            'occasion_id'                               => null,
                            'is_action_made_on_employee'                => '1',
                            'is_archived'                               => ($calendar->status == 2) ? 1 : 0,
                            'employee_branch_id'                        => $employee->branch_id,
                            'employee_status'                           => $employee->employment_status,
                            'added_by'                                  => 1,
                            'updated_by'                                => 1,
                            'company_id'                                => 1,
                            'year_and_month'                            => $yearMonth,
                            'notes'                                     => 'راحة أسبوعية',
                        ]);
                        continue;
                    }

                    // ── اختيار السيناريو بشكل متنوع وحتمي ────────────────
                    $scenarioIdx = ($day + $employee->id * 7) % count($scenarios);
                    $scenario    = $scenarios[$scenarioIdx];

                    // غياب: بحد أقصى مرتين في الشهر
                    if ($scenario['type'] === 'absent' || ($day % 17 === 0 && $absentCount < 2)) {
                        $absentCount++;
                        $record = AttendanceDeparture::create([
                            'finance_monthly_calendar_id'               => $calendar->id,
                            'employee_id'                               => $employee->id,
                            'day_of_finger_print'                       => $date,
                            'shift_hours'                               => $shiftHours,
                            'status_move'                               => '2',
                            'checkInDate'                               => null,
                            'checkOutDate'                              => null,
                            'checkInTime'                               => null,
                            'checkOutTime'                              => null,
                            'checkInDateTime'                           => null,
                            'checkOutDateTime'                          => null,
                            'variables'                                 => null,
                            'attendance_delay'                          => 0,
                            'early_departure'                           => 0,
                            'approved_attendance_delay_early_departure' => null,
                            'total_hours'                               => 0,
                            'absence_hours'                             => $shiftHours,
                            'overtime_hours'                            => 0,
                            'cutting_days'                              => 0,
                            'vacation_id'                               => 0,
                            'occasion_id'                               => null,
                            'is_action_made_on_employee'                => '0',
                            'is_archived'                               => ($calendar->status == 2) ? 1 : 0,
                            'employee_branch_id'                        => $employee->branch_id,
                            'employee_status'                           => $employee->employment_status,
                            'added_by'                                  => 1,
                            'updated_by'                                => 1,
                            'company_id'                                => 1,
                            'year_and_month'                            => $yearMonth,
                            'notes'                                     => 'غياب',
                        ]);
                        continue;
                    }

                    // ── حضور وانصراف ──────────────────────────────────────
                    $inOffset  = $scenario['in'];
                    $outOffset = $scenario['out'];

                    $checkInMins  = (($shiftStartMins + $inOffset)  % 1440 + 1440) % 1440;
                    $checkOutMins = (($shiftEndMins   + $outOffset) % 1440 + 1440) % 1440;

                    // ضمان أن الانصراف بعد الحضور بساعة على الأقل
                    $rawOut = $shiftEndMins + $outOffset;
                    $rawIn  = $shiftStartMins + $inOffset;
                    if ($rawOut - $rawIn < 60) {
                        $rawOut = $rawIn + (int)($shiftHours * 60);
                        $checkOutMins = ($rawOut % 1440 + 1440) % 1440;
                    }

                    $checkInH  = intdiv($checkInMins,  60);
                    $checkInM  = $checkInMins  % 60;
                    $checkOutH = intdiv($checkOutMins, 60);
                    $checkOutM = $checkOutMins % 60;

                    $checkInTime      = sprintf('%02d:%02d:00', $checkInH, $checkInM);
                    $checkOutTime     = sprintf('%02d:%02d:00', $checkOutH, $checkOutM);
                    $checkInDateTime  = $date . ' ' . $checkInTime;
                    $checkOutDateTime = $date . ' ' . $checkOutTime;

                    // ── حساب المتغيرات الدقيقة ────────────────────────────
                    $inTs       = strtotime($checkInDateTime);
                    $outTs      = strtotime($checkOutDateTime);
                    if ($outTs <= $inTs) {
                        $outTs += 86400; // شيفت ليلي ينتهي في اليوم التالي
                    }
                    $totalHours  = round(($outTs - $inTs) / 3600, 2);
                    $overtimeHrs = max(0, round($totalHours - $shiftHours, 2));
                    $absenceHrs  = max(0, round($shiftHours - $totalHours, 2));

                    // تأخير بالدقائق (فقط لو تجاوز الحد الأدنى)
                    $delayMins = 0;
                    if ($inOffset > 0 && $inOffset >= $delayThreshold) {
                        $delayMins = (float)$inOffset;
                    }

                    // انصراف مبكر بالدقائق (فقط لو تجاوز الحد الأدنى)
                    $earlyMins = 0;
                    if ($outOffset < 0 && abs($outOffset) >= $earlyThreshold) {
                        $earlyMins = (float)abs($outOffset);
                    }

                    // ── إنشاء سجل الحضور الرئيسي ─────────────────────────
                    $attendance = AttendanceDeparture::create([
                        'finance_monthly_calendar_id'               => $calendar->id,
                        'employee_id'                               => $employee->id,
                        'day_of_finger_print'                       => $date,
                        'shift_hours'                               => $shiftHours,
                        'status_move'                               => '2',
                        'checkInDate'                               => $date,
                        'checkOutDate'                              => $date,
                        'checkInTime'                               => $checkInTime,
                        'checkOutTime'                              => $checkOutTime,
                        'checkInDateTime'                           => $checkInDateTime,
                        'checkOutDateTime'                          => $checkOutDateTime,
                        'variables'                                 => null,
                        'attendance_delay'                          => $delayMins,
                        'early_departure'                           => $earlyMins,
                        'approved_attendance_delay_early_departure' => null,
                        'total_hours'                               => $totalHours,
                        'absence_hours'                             => $absenceHrs,
                        'overtime_hours'                            => $overtimeHrs,
                        'cutting_days'                              => 0,
                        'vacation_id'                               => 0,
                        'occasion_id'                               => null,
                        'is_action_made_on_employee'                => '0',
                        'is_archived'                               => ($calendar->status == 2) ? 1 : 0,
                        'employee_branch_id'                        => $employee->branch_id,
                        'employee_status'                           => $employee->employment_status,
                        'added_by'                                  => 1,
                        'updated_by'                                => 1,
                        'company_id'                                => 1,
                        'year_and_month'                            => $yearMonth,
                        'notes'                                     => 'حضور تجريبي - ' . $scenario['type'],
                    ]);

                    // ── إنشاء batch record في جدول الإكسيل ───────────────
                    $excelRecord = AttendanceDepartureActionsExcel::create([
                        'finance_monthly_calendar_id' => $calendar->id,
                        'employee_id'                 => $employee->id,
                        'dateTimeAction'              => $checkInDateTime,
                        'type'                        => '2', // arrival batch
                        'main_salary_employee_id'     => null,
                        'company_id'                  => 1,
                        'added_by'                    => 1,
                        'notes'                       => 'بيانات تجريبية - ' . $date,
                    ]);

                    // ── إنشاء حركات البصمة الفردية ───────────────────────
                    // حركة الحضور (type=2: arrival)
                    DB::table('attendances_departures_actions')->insert([
                        'attendances_departure_id'             => $attendance->id,
                        'finance_monthly_calendar_id'          => $calendar->id,
                        'employee_id'                          => $employee->id,
                        'dateTimeAction'                       => $checkInDateTime,
                        'type'                                 => '2', // arrival
                        'added_method'                         => '1', // automatic
                        'is_active_with_parent'                => '1',
                        'is_action_made_on_employee'           => '0',
                        'attendance_departure_actions_excel_id'=> $excelRecord->id,
                        'added_by'                             => 1,
                        'updated_by'                           => 1,
                        'company_id'                           => 1,
                        'notes'                                => 'حضور - ' . $checkInTime,
                        'created_at'                           => now(),
                        'updated_at'                           => now(),
                    ]);

                    // حركة الانصراف (type=1: departure)
                    DB::table('attendances_departures_actions')->insert([
                        'attendances_departure_id'             => $attendance->id,
                        'finance_monthly_calendar_id'          => $calendar->id,
                        'employee_id'                          => $employee->id,
                        'dateTimeAction'                       => $checkOutDateTime,
                        'type'                                 => '1', // departure
                        'added_method'                         => '1', // automatic
                        'is_active_with_parent'                => '1',
                        'is_action_made_on_employee'           => '0',
                        'attendance_departure_actions_excel_id'=> $excelRecord->id,
                        'added_by'                             => 1,
                        'updated_by'                           => 1,
                        'company_id'                           => 1,
                        'notes'                                => 'انصراف - ' . $checkOutTime,
                        'created_at'                           => now(),
                        'updated_at'                           => now(),
                    ]);

                } // end days loop
            } // end employees loop

            // ── إعادة حساب كل القيم لهذا الشهر بخوارزمية النظام ────────
            $this->command->info("   📊 Recalculating {$yearMonth}...");
            foreach ($employees as $employee) {
                AttendanceDeparture::recalculateEmployeeMonth(
                    $employee->id,
                    $calendar->id,
                    1
                );
            }
        } // end calendars loop

        // حذف ملف السكريبت المؤقت
        @unlink(base_path('check_shifts.php'));

        $this->command->info('✅ Attendance + Fingerprint actions seeded & recalculated!');
    }
}
