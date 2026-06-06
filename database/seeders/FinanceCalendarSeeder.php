<?php

namespace Database\Seeders;

use App\Models\FinanceCalendar;
use Illuminate\Database\Seeder;

class FinanceCalendarSeeder extends Seeder
{
    public function run(): void
    {
        $financeCalendars = [
            [
                'finance_yr' => 2026,
                'finance_yr_desc' => 'السنة المالية 2026',
                'start_date' => '2026-01-01',
                'end_date' => '2026-12-31',
            ]
        ];

        foreach ($financeCalendars as $calendar) {
            FinanceCalendar::updateOrCreate(
                [
                    'finance_yr' => $calendar['finance_yr'],
                    'company_id' => 1
                ],
                [
                    'finance_yr_desc' => $calendar['finance_yr_desc'],
                    'start_date' => $calendar['start_date'],
                    'end_date' => $calendar['end_date'],
                    'status' => 0,
                    'company_id' => 1,
                    'added_by' => 1,
                    'updated_by' => 1,
                ]
            );
        }

        $this->command->info('✅ Finance Calendars seeded: ' . count($financeCalendars) . ' records');
    }
}
