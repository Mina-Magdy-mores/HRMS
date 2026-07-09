<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\FinanceMonthlyCalendar;
use App\Models\MainEmployeeInvestigation;
use Illuminate\Database\Seeder;

class MainEmployeeInvestigationSeeder extends Seeder
{
    public function run(): void
    {
        $employees = Employee::where('company_id', 1)->get();
        $calendar = FinanceMonthlyCalendar::where('year_and_month', '2026-07')->where('company_id', 1)->first();

        if ($employees->isEmpty() || !$calendar) {
            return;
        }

        // Seed investigations for 2 random employees
        $selectedEmployees = $employees->random(min(2, $employees->count()));

        $descriptions = [
            'التحقيق في واقعة التأخير المتكرر عن مواعيد العمل الرسمية بدون إذن مسبق خلال الأسبوع الماضي.',
            'التحقيق في شكوى سوء معاملة أحد العملاء/المرضى بمقر العمل يوم 4 يوليو.'
        ];

        foreach ($selectedEmployees as $index => $employee) {
            MainEmployeeInvestigation::create([
                'employee_id' => $employee->id,
                'finance_monthly_calendar_id' => $calendar->id,
                'is_auto' => 0,
                'description' => $descriptions[$index] ?? 'التحقيق في مخالفة تعليمات العمل وتكرار التقصير.',
                'is_archived' => 0, // Pending decision
                'company_id' => 1,
                'added_by' => 1,
                'updated_by' => 1,
                'notes' => 'تحقيق تجريبي',
            ]);
        }

        $this->command->info('✅ Main Employee Investigations seeded successfully!');
    }
}
