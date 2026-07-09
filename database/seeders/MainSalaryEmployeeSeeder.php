<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\FinanceMonthlyCalendar;
use App\Models\MainSalaryEmployee;
use App\Models\MainSalaryEmployeeBonus;
use App\Models\MainSalaryEmployeeDeductionType;
use App\Models\MainSalaryEmployeeLoan;
use App\Models\MainSalaryEmployeePLoan;
use App\Models\MainSalaryEmployeePLoanInstallment;
use App\Models\AttendanceDeparture;
use App\Models\MainEmployeesVacationsBalances;
use App\Models\DirectBonus;
use App\Models\DirectGrant;
use App\Models\Bonus;
use App\Models\DeductionType;
use App\Models\SalaryGrantType;
use App\Models\Admin;
use App\Services\Finance\SalaryRecordService;
use App\Services\Finance\SalaryService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class MainSalaryEmployeeSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Clean existing salary tables first to prevent duplicate errors
        Schema::disableForeignKeyConstraints();
        DB::table('main_salary_employees')->delete();
        DB::table('main_salary_employee_absences')->delete();
        DB::table('main_salary_employee_additions')->delete();
        DB::table('main_salary_employee_allowances')->delete();
        DB::table('main_salary_employee_bonuses')->delete();
        DB::table('main_salary_employee_deductions')->delete();
        DB::table('main_salary_employee_deduction_types')->delete();
        DB::table('main_salary_employee_loans')->delete();
        DB::table('main_salary_employee_p_loans')->delete();
        DB::table('main_salary_employee_p_loan_installments')->delete();
        DB::table('main_salary_employee_settlements')->delete();
        DB::table('direct_bonuses')->delete();
        DB::table('direct_grants')->delete();
        Schema::enableForeignKeyConstraints();

        // Login Admin 1 programmatically
        $admin = Admin::find(1);
        auth()->login($admin);
        auth()->guard('admin')->login($admin);

        $employees = Employee::where('company_id', 1)->get();
        if ($employees->isEmpty()) {
            $this->command->error('❌ No employees found to seed salaries!');
            return;
        }

        // 2. Seed permanent loans in January 2026 (starting point)
        foreach ($employees as $employee) {
            if ($employee->id % 2 == 0) { // For even employee IDs
                $pLoan = MainSalaryEmployeePLoan::create([
                    'employee_id' => $employee->id,
                    'employee_basic_salary' => $employee->salary,
                    'amount' => 6000.00,
                    'number_of_installment_months' => 12,
                    'installment_amount_monthly' => 500.00,
                    'next_installment_year_and_month' => '2026-01',
                    'next_installment_date' => '2026-01-01',
                    'paid_amount' => 0.00,
                    'remaining_amount' => 6000.00,
                    'is_archived' => 0,
                    'is_disbursed' => 1,
                    'disbursed_by' => 1,
                    'disbursed_at' => now()->subMonths(6),
                    'company_id' => 1,
                    'added_by' => 1,
                    'notes' => 'سلفة مستديمة تجريبية بدأت في يناير 2026',
                ]);

                // Generate 12 installments (Jan to Dec 2026)
                $currentDate = new \DateTime('2026-01-01');
                for ($i = 0; $i < 12; $i++) {
                    MainSalaryEmployeePLoanInstallment::create([
                        'main_salary_employee_p_loan_id' => $pLoan->id,
                        'employee_id' => $employee->id,
                        'amount' => 6000.00,
                        'installment_amount_monthly' => 500.00,
                        'next_installment_year_and_month' => $currentDate->format('Y-m'),
                        'installment_status' => '0', // Pending, will be paid via monthly recalculation
                        'is_archived' => 0,
                        'company_id' => 1,
                        'added_by' => 1,
                        'updated_by' => 1,
                        'notes' => 'قسط مجدول لشهر ' . $currentDate->format('F'),
                    ]);
                    $currentDate->modify('+1 month');
                }
            }
        }

        $recordService = new SalaryRecordService();
        $salaryService = app(SalaryService::class);

        $bonus = Bonus::where('company_id', 1)->first();
        $deductionType = DeductionType::where('company_id', 1)->first();
        $grantType = SalaryGrantType::where('company_id', 1)->first();

        // 3. Process months 1 to 6 (January to June) and archive them
        for ($monthNum = 1; $monthNum <= 6; $monthNum++) {
            $yearMonth = sprintf('2026-%02d', $monthNum);
            $calendar = FinanceMonthlyCalendar::where('year_and_month', $yearMonth)->where('company_id', 1)->first();

            if (!$calendar) {
                continue;
            }

            // Set status to 0 temporarily so openMonth can execute
            $calendar->status = 0;
            $calendar->save();

            // Open the month
            $recordService->openMonth($calendar->id, $calendar->start_date, $calendar->end_date, $admin->id);

            $salaryRecords = MainSalaryEmployee::where('finance_monthly_calendar_id', $calendar->id)->get();

            foreach ($salaryRecords as $record) {
                // Seed monthly bonus
                if ($bonus) {
                    MainSalaryEmployeeBonus::create([
                        'main_salary_employee_id' => $record->id,
                        'employee_id' => $record->employee_id,
                        'finance_monthly_calendar_id' => $calendar->id,
                        'bonus_id' => $bonus->id,
                        'amount' => rand(100, 300),
                        'is_archived' => 0,
                        'is_auto' => 0,
                        'status' => 1,
                        'company_id' => 1,
                        'added_by' => 1,
                        'notes' => 'مكافأة أداء تجريبية لشهر ' . $monthNum,
                    ]);
                }

                // Seed monthly deduction
                if ($deductionType) {
                    MainSalaryEmployeeDeductionType::create([
                        'main_salary_employee_id' => $record->id,
                        'employee_id' => $record->employee_id,
                        'finance_monthly_calendar_id' => $calendar->id,
                        'deduction_type_id' => $deductionType->id,
                        'amount' => rand(50, 100),
                        'is_archived' => 0,
                        'is_auto' => 0,
                        'status' => 1,
                        'company_id' => 1,
                        'added_by' => 1,
                        'notes' => 'استقطاع تأمين تجريبي لشهر ' . $monthNum,
                    ]);
                }

                // Seed regular loan for odd employee IDs
                if ($record->employee_id % 2 == 1) {
                    MainSalaryEmployeeLoan::create([
                        'main_salary_employee_id' => $record->id,
                        'employee_id' => $record->employee_id,
                        'finance_monthly_calendar_id' => $calendar->id,
                        'amount' => 300.00,
                        'is_archived' => 0,
                        'is_auto' => 0,
                        'status' => 1,
                        'company_id' => 1,
                        'added_by' => 1,
                        'notes' => 'سلفة مؤقتة مستردة',
                    ]);
                }
            }

            // Pull fingerprint variables
            $salaryService->pullFingerprintVariablesToSalaryForCalendar($calendar->id, 1);

            // Recalculate salaries
            foreach ($salaryRecords as $record) {
                $salaryService->recalculateMainSalary($record->id);
            }

            // Close and Archive the month
            DB::transaction(function () use ($salaryRecords, $calendar) {
                $archiveData = [
                    'is_archived' => 1,
                    'archived_by' => 1,
                    'archived_at' => now(),
                ];

                AttendanceDeparture::where('company_id', 1)
                    ->where('finance_monthly_calendar_id', $calendar->id)
                    ->update($archiveData);

                MainEmployeesVacationsBalances::where('company_id', 1)
                    ->where('year_and_month', $calendar->year_and_month)
                    ->update($archiveData);

                foreach ($salaryRecords as $record) {
                    $net = (float)$record->employee_net_salary;

                    if ($net >= 0) {
                        $record->archive_status_type = $net == 0 ? 3 : 1;
                        $record->archive_settlement_amount = $net;
                        $record->employee_net_salary_after_close_for_roll_over = 0.00;
                        $record->is_disbursed = $net > 0 ? 1 : 0;
                    } else {
                        $record->archive_status_type = 2;
                        $record->archive_settlement_amount = 0.00;
                        $record->employee_net_salary_after_close_for_roll_over = $net;
                        $record->is_disbursed = 0;
                    }

                    $record->is_archived = 1;
                    $record->archived_by = 1;
                    $record->archived_at = now();
                    $record->save();

                    $record->mainSalaryEmployeeDeductions()->update($archiveData);
                    $record->mainSalaryEmployeeAbsences()->update($archiveData);
                    $record->mainSalaryEmployeeDeductionTypes()->update($archiveData);
                    $record->mainSalaryEmployeeAdditions()->update($archiveData);
                    $record->mainSalaryEmployeeLoans()->update($archiveData);
                    $record->mainSalaryEmployeeBonuses()->update($archiveData);
                    $record->mainSalaryEmployeeAllowances()->update($archiveData);
                    $record->mainSalaryEmployeePLoanInstallments()->update($archiveData);
                }

                $calendar->status = 2; // Closed
                $calendar->updated_by = 1;
                $calendar->save();
            });

            $this->command->info("✅ Month {$yearMonth} processed, recalculated, and archived.");
        }

        // 4. Process and open the active month (July 2026)
        $julyCalendar = FinanceMonthlyCalendar::where('year_and_month', '2026-07')->where('company_id', 1)->first();
        if ($julyCalendar) {
            $julyCalendar->status = 0;
            $julyCalendar->save();

            $recordService->openMonth($julyCalendar->id, $julyCalendar->start_date, $julyCalendar->end_date, $admin->id);

            $julyRecords = MainSalaryEmployee::where('finance_monthly_calendar_id', $julyCalendar->id)->get();

            foreach ($julyRecords as $record) {
                // Seed monthly bonus
                if ($bonus) {
                    MainSalaryEmployeeBonus::create([
                        'main_salary_employee_id' => $record->id,
                        'employee_id' => $record->employee_id,
                        'finance_monthly_calendar_id' => $julyCalendar->id,
                        'bonus_id' => $bonus->id,
                        'amount' => rand(150, 450),
                        'is_archived' => 0,
                        'is_auto' => 0,
                        'status' => 1,
                        'company_id' => 1,
                        'added_by' => 1,
                        'notes' => 'حافز إنتاج إضافي نشط لشهر يوليو',
                    ]);
                }

                // Seed monthly deduction
                if ($deductionType) {
                    MainSalaryEmployeeDeductionType::create([
                        'main_salary_employee_id' => $record->id,
                        'employee_id' => $record->employee_id,
                        'finance_monthly_calendar_id' => $julyCalendar->id,
                        'deduction_type_id' => $deductionType->id,
                        'amount' => rand(50, 150),
                        'is_archived' => 0,
                        'is_auto' => 0,
                        'status' => 1,
                        'company_id' => 1,
                        'added_by' => 1,
                        'notes' => 'خصم نشط لشهر يوليو',
                    ]);
                }

                // Seed regular loan for odd IDs
                if ($record->employee_id % 2 == 1) {
                    MainSalaryEmployeeLoan::create([
                        'main_salary_employee_id' => $record->id,
                        'employee_id' => $record->employee_id,
                        'finance_monthly_calendar_id' => $julyCalendar->id,
                        'amount' => 500.00,
                        'is_archived' => 0,
                        'is_auto' => 0,
                        'status' => 1,
                        'company_id' => 1,
                        'added_by' => 1,
                        'notes' => 'سلفة نقدية مؤقتة لشهر يوليو',
                    ]);
                }

                // Seed a direct bonus
                if ($bonus) {
                    DirectBonus::create([
                        'employee_id' => $record->employee_id,
                        'finance_monthly_calendar_id' => $julyCalendar->id,
                        'bonus_id' => $bonus->id,
                        'amount' => rand(100, 300),
                        'payment_date' => date('Y-m-d'),
                        'notes' => 'مكافأة مباشرة استثنائية يوليو',
                        'status' => 1,
                        'company_id' => 1,
                        'added_by' => 1,
                    ]);
                }

                // Seed a direct grant
                if ($grantType) {
                    DirectGrant::create([
                        'employee_id' => $record->employee_id,
                        'finance_monthly_calendar_id' => $julyCalendar->id,
                        'salary_grant_type_id' => $grantType->id,
                        'amount' => rand(200, 500),
                        'payment_date' => date('Y-m-d'),
                        'notes' => 'منحة مباشرة استثنائية يوليو',
                        'status' => 1,
                        'company_id' => 1,
                        'added_by' => 1,
                    ]);
                }
            }

            // Pull fingerprint variables
            $salaryService->pullFingerprintVariablesToSalaryForCalendar($julyCalendar->id, 1);

            // Recalculate salaries
            foreach ($julyRecords as $record) {
                $salaryService->recalculateMainSalary($record->id);
            }

            $this->command->info('✅ July 2026 active salary sheets seeded and calculated.');
        }
    }
}
