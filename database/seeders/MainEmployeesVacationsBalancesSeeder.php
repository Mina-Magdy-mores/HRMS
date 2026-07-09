<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\MainEmployeesVacationsBalances;
use Illuminate\Database\Seeder;

class MainEmployeesVacationsBalancesSeeder extends Seeder
{
    public function run(): void
    {
        $employees = Employee::where('company_id', 1)->get();

        if ($employees->isEmpty()) {
            return;
        }

        // Clean existing vacation balances first
        MainEmployeesVacationsBalances::truncate();

        $months = [
            '2026-01', '2026-02', '2026-03', '2026-04', '2026-05', '2026-06', '2026-07'
        ];

        foreach ($employees as $employee) {
            $carryover = 0.00;

            foreach ($months as $index => $yearMonth) {
                $currentMonthIncrement = 1.75;
                $totalAvailable = $carryover + $currentMonthIncrement;
                
                // Let some random months have spent vacations
                $spent = 0.00;
                if ($index > 0 && rand(0, 10) > 7) {
                    $spent = rand(1, 2);
                }

                // If spent exceeds available, clamp it
                if ($spent > $totalAvailable) {
                    $spent = floor($totalAvailable);
                }

                $remaining = $totalAvailable - $spent;
                $isArchived = ($yearMonth == '2026-07') ? 0 : 1;

                MainEmployeesVacationsBalances::create([
                    'employee_id' => $employee->id,
                    'year_and_month' => $yearMonth,
                    'financial_year' => 2026,
                    'carryover_from_previous_month' => $carryover,
                    'current_month_balance' => $currentMonthIncrement,
                    'total_available_balance' => $totalAvailable,
                    'spent_balance' => $spent,
                    'remaining_net_balance' => $remaining,
                    'is_archived' => $isArchived,
                    'archived_by' => $isArchived ? 1 : null,
                    'archived_at' => $isArchived ? now()->subMonths(7 - ($index + 1)) : null,
                    'added_by' => 1,
                    'updated_by' => 1,
                    'company_id' => 1,
                ]);

                // Next month's carryover is this month's remaining balance
                $carryover = $remaining;
            }
        }

        $this->command->info('✅ Historical Vacations Balances seeded successfully!');
    }
}
