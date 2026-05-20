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
                'finance_yr' => 2025,
                'finance_yr_desc' => 'السنة المالية 2025',
                'start_date' => '2025-01-01',
                'end_date' => '2025-12-31',
            ],
            [
                'finance_yr' => 2026,
                'finance_yr_desc' => 'السنة المالية 2026',
                'start_date' => '2026-01-01',
                'end_date' => '2026-12-31',
            ],
            [
                'finance_yr' => 2027,
                'finance_yr_desc' => 'السنة المالية 2027',
                'start_date' => '2027-01-01',
                'end_date' => '2027-12-31',
            ],
            [
                'finance_yr' => 2028,
                'finance_yr_desc' => 'السنة المالية 2028',
                'start_date' => '2028-01-01',
                'end_date' => '2028-12-31',
            ],
            [
                'finance_yr' => 2029,
                'finance_yr_desc' => 'السنة المالية 2029',
                'start_date' => '2029-01-01',
                'end_date' => '2029-12-31',
            ],
            [
                'finance_yr' => 2030,
                'finance_yr_desc' => 'السنة المالية 2030',
                'start_date' => '2030-01-01',
                'end_date' => '2030-12-31',
            ],
            [
                'finance_yr' => 2031,
                'finance_yr_desc' => 'السنة المالية 2031',
                'start_date' => '2031-01-01',
                'end_date' => '2031-12-31',
            ],
            [
                'finance_yr' => 2032,
                'finance_yr_desc' => 'السنة المالية 2032',
                'start_date' => '2032-01-01',
                'end_date' => '2032-12-31',
            ],
            [
                'finance_yr' => 2033,
                'finance_yr_desc' => 'السنة المالية 2033',
                'start_date' => '2033-01-01',
                'end_date' => '2033-12-31',
            ],
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
                    'status' => $calendar['finance_yr'] == 2026 ? 1 : 0,
                    'company_id' => 1,
                    'added_by' => 1,
                    'updated_by' => 1,
                ]
            );
        }

        $this->command->info('✅ Finance Calendars seeded: ' . count($financeCalendars) . ' records');
    }
}
