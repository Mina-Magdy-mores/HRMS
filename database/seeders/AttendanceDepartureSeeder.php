<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\FinanceMonthlyCalendar;
use App\Models\AttendanceDeparture;
use Illuminate\Database\Seeder;

class AttendanceDepartureSeeder extends Seeder
{
    public function run(): void
    {
        $employees = Employee::where('company_id', 1)->get();
        // Fetch months from January to July 2026
        $calendars = FinanceMonthlyCalendar::where('finance_yr', 2026)
            ->where('company_id', 1)
            ->where('month_id', '<=', 7)
            ->get();

        if ($employees->isEmpty() || $calendars->isEmpty()) {
            return;
        }

        // Clean existing attendance for all these calendars
        $calendarIds = $calendars->pluck('id')->toArray();
        AttendanceDeparture::whereIn('finance_monthly_calendar_id', $calendarIds)->delete();

        foreach ($calendars as $calendar) {
            $yearMonth = $calendar->year_and_month;
            $daysInMonth = $calendar->number_of_days;

            foreach ($employees as $employee) {
                for ($day = 1; $day <= $daysInMonth; $day++) {
                    $date = sprintf('%s-%02d', $yearMonth, $day);

                    // Skip weekends (Friday/Saturday in Egypt)
                    $dayOfWeek = date('w', strtotime($date));
                    if ($dayOfWeek == 5 || $dayOfWeek == 6) {
                        continue;
                    }

                    // For the active month (July 2026), only seed up to July 9 (current date)
                    if ($yearMonth == '2026-07' && $day > 9) {
                        continue;
                    }

                    // Random check-in between 07:45 and 08:30
                    $inHour = rand(7, 8);
                    $inMin = ($inHour == 7) ? rand(45, 59) : rand(0, 30);
                    $checkInTime = sprintf('%02d:%02d:00', $inHour, $inMin);

                    // Delay check (normal check-in is 08:00)
                    $isDelay = ($inHour == 8 && $inMin > 0) ? '1' : '0';

                    // Random check-out between 15:45 and 17:00
                    $outHour = rand(15, 16);
                    $outMin = ($outHour == 15) ? rand(45, 59) : rand(0, 59);
                    $checkOutTime = sprintf('%02d:%02d:00', $outHour, $outMin);

                    // Early departure check (normal check-out is 16:00)
                    $isEarlyOut = ($outHour == 15) ? '1' : '0';

                    $checkInDateTime = $date . ' ' . $checkInTime;
                    $checkOutDateTime = $date . ' ' . $checkOutTime;

                    // Calculate total hours
                    $inTs = strtotime($checkInDateTime);
                    $outTs = strtotime($checkOutDateTime);
                    $totalHours = round(($outTs - $inTs) / 3600, 2);

                    AttendanceDeparture::create([
                        'finance_monthly_calendar_id' => $calendar->id,
                        'employee_id' => $employee->id,
                        'shift_hours' => 8.00,
                        'status_move' => '2',
                        'checkInDate' => $date,
                        'checkOutDate' => $date,
                        'checkInTime' => $checkInTime,
                        'checkOutTime' => $checkOutTime,
                        'checkInDateTime' => $checkInDateTime,
                        'checkOutDateTime' => $checkOutDateTime,
                        'variables' => null,
                        'attendance_delay' => $isDelay,
                        'early_departure' => $isEarlyOut,
                        'approved_attendance_delay_early_departure' => null,
                        'total_hours' => $totalHours,
                        'absence_hours' => 0,
                        'overtime_hours' => ($totalHours > 8) ? ($totalHours - 8) : 0,
                        'is_action_made_on_employee' => '0',
                        'is_archived' => ($calendar->status == 2) ? 1 : 0, // Mark archived if month is archived
                        'employee_branch_id' => $employee->branch_id,
                        'employee_status' => $employee->employment_status,
                        'added_by' => 1,
                        'updated_by' => 1,
                        'company_id' => 1,
                        'year_and_month' => $yearMonth,
                        'notes' => 'حضور تجريبي تاريخي مضاف تلقائياً',
                    ]);
                }
            }
        }

        $this->command->info('✅ Historical Attendance logs seeded successfully!');
    }
}
