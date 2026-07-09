<?php

namespace Database\Seeders;

use App\Models\FinanceCalendar;
use App\Models\FinanceMonthlyCalendar;
use App\Models\Month;
use Illuminate\Database\Seeder;

class FinanceMonthlyCalendarSeeder extends Seeder
{
    public function run(): void
    {
        // جلب السنوات المالية
        $financeYears = FinanceCalendar::where('company_id', 1)->get();

        $monthlyCalendars = [];

        foreach ($financeYears as $financeYear) {
            $year = $financeYear->finance_yr;

            // جلب الشهور
            $months = Month::all();

            foreach ($months as $index => $month) {
                $monthNumber = $index + 1;

                // حساب بداية ونهاية الشهر
                $startDate = date("$year-$monthNumber-01");
                $endDate = date("Y-m-t", strtotime($startDate));
                $numberOfDays = date("t", strtotime($startDate));

                // تاريخ بداية ونهاية الحساب (نفس الشهر)
                $startDateForCalculation = $startDate;
                $endDateForCalculation = $endDate;

                // الشهور السابقة تؤرشف والشهور الحالية والمستقبلية تكون معلقة للفتح
                $currentMonthNumber = (int)date('m');
                $status = 0;
                if ($year == date('Y') && $monthNumber < $currentMonthNumber) {
                    $status = 2; // مؤرشف
                }

                $monthlyCalendars[] = [
                    'financeCalendar_id' => $financeYear->id,
                    'number_of_days' => $numberOfDays,
                    'year_and_month' => $year . '-' . str_pad($monthNumber, 2, '0', STR_PAD_LEFT),
                    'finance_yr' => $year,
                    'month_id' => $month->id,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'status' => $status,
                    'start_date_for_calculation' => $startDateForCalculation,
                    'end_date_for_calculation' => $endDateForCalculation,
                    'company_id' => 1,
                    'added_by' => 1,
                    'updated_by' => 1,
                ];
            }
        }

        foreach ($monthlyCalendars as $calendar) {
            FinanceMonthlyCalendar::updateOrCreate(
                [
                    'financeCalendar_id' => $calendar['financeCalendar_id'],
                    'month_id' => $calendar['month_id'],
                    'company_id' => 1
                ],
                $calendar
            );
        }

        $this->command->info('✅ Finance Monthly Calendars seeded: ' . count($monthlyCalendars) . ' records');
    }
}
