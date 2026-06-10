<?php

namespace App\Traits;

use App\Models\Employee;
use App\Models\EmployeeFixedAllowance;
use App\Models\FinanceMonthlyCalendar;
use App\Models\MainSalaryEmployee;
use App\Models\MainSalaryEmployeeAbsence;
use App\Models\MainSalaryEmployeeAddition;
use App\Models\MainSalaryEmployeeAllowance;
use App\Models\MainSalaryEmployeeBonus;
use App\Models\MainSalaryEmployeeDeduction;
use App\Models\MainSalaryEmployeeDeductionType;
use App\Models\MainSalaryEmployeeLoan;
use App\Models\MainSalaryEmployeePLoanInstallment;
use Illuminate\Support\Facades\Auth;

trait GeneralTrait
{
    function recalculate_main_salary($main_salary_employee_id)
    {
        $company_id = Auth::user()->company_id;
        $main_salary_employee = getColsWhereRow(MainSalaryEmployee::class, ['*'], ['id' => $main_salary_employee_id, 'company_id' => $company_id, 'is_archived' => 0]);

        if (!empty($main_salary_employee)) {
            $employee = getColsWhereRow(
                Employee::class,
                ['fixed_allowance', 'payment_per_day', 'motivation_amount', 'social_insurance_amount', 'medical_insurance_amount', 'salary'],
                ['id' => $main_salary_employee['employee_id'], 'company_id' => $company_id]
            );
            $finance_monthly_calender = getColsWhereRow(
                FinanceMonthlyCalendar::class,
                ['id', 'year_and_month'],
                ['id' => $main_salary_employee['finance_monthly_calendar_id'], 'company_id' => $company_id, 'status' => '1']
            );

            if (!empty($employee) && !empty($finance_monthly_calender)) {
                $main_salary_employee_deductions =  getColsWhereRow(
                    MainSalaryEmployeeDeduction::class,
                    ['id', 'days_amount', 'total'],
                    ['main_salary_employee_id' => $main_salary_employee_id, 'company_id' => $company_id]
                );

                $main_salary_employee_absence =  getColsWhereRow(
                    MainSalaryEmployeeAbsence::class,
                    ['id', 'days_amount', 'total'],
                    ['main_salary_employee_id' => $main_salary_employee_id, 'company_id' => $company_id]
                );

                $main_salary_employee_additions =  getColsWhereRow(
                    MainSalaryEmployeeAddition::class,
                    ['id', 'days_amount', 'total'],
                    ['main_salary_employee_id' => $main_salary_employee_id, 'company_id' => $company_id]
                );

                $main_salary_employee_deduction_type =  getColsWhereRow(
                    MainSalaryEmployeeDeductionType::class,
                    ['id', 'amount'],
                    ['main_salary_employee_id' => $main_salary_employee_id, 'company_id' => $company_id]
                );

                $main_salary_employee_bonus =  getColsWhereRow(
                    MainSalaryEmployeeBonus::class,
                    ['id', 'amount'],
                    ['main_salary_employee_id' => $main_salary_employee_id, 'company_id' => $company_id]
                );

                $main_salary_employee_allowance =  getColsWhereRow(
                    MainSalaryEmployeeAllowance::class,
                    ['id', 'amount'],
                    ['main_salary_employee_id' => $main_salary_employee_id, 'company_id' => $company_id]
                );

                $main_salary_employee_loans =  getColsWhereRow(
                    MainSalaryEmployeeLoan::class,
                    ['id', 'amount'],
                    ['main_salary_employee_id' => $main_salary_employee_id, 'company_id' => $company_id]
                );
                $main_salary_employee_p_loans = MainSalaryEmployeePLoanInstallment::select('amount')
                    ->where('next_installment_year_and_month', $finance_monthly_calender['year_and_month'])
                    ->where('company_id', $company_id)
                    ->where('is_archived', 0)
                    ->where('installment_status', '!=', '2')
                    ->where('employee_id', $main_salary_employee['employee_id'])
                    ->whereHas('mainSalaryEmployeePLoan', function ($query) use ($main_salary_employee) {
                        $query->where('is_disbursed', 1);
                        $query->where('employee_id', $main_salary_employee['employee_id']);
                    })
                    ->sum('installment_amount_monthly');
                $dataToUpdateIn_main_salary_employee_p_loans['installment_status'] = 1;
                $dataToUpdateIn_main_salary_employee_p_loans['main_salary_employee_id'] = $main_salary_employee_id;

                MainSalaryEmployeePLoanInstallment::where('next_installment_year_and_month', $finance_monthly_calender['year_and_month'])
                    ->where('company_id', $company_id)
                    ->where('is_archived', 0)
                    ->where('installment_status', '!=', '2')
                    ->where('employee_id', $main_salary_employee['employee_id'])
                    ->whereHas('mainSalaryEmployeePLoan', function ($query) use ($main_salary_employee) {
                        $query->where('is_disbursed', 1);
                        $query->where('employee_id', $main_salary_employee['employee_id']);
                    })
                    ->update($dataToUpdateIn_main_salary_employee_p_loans);
                $employee_fixed_allowances = EmployeeFixedAllowance::select(['id', 'amount'])
                    ->where('employee_id', $main_salary_employee['employee_id'])
                    ->where('company_id', $company_id)
                    ->sum('amount');
                $dataToUpdate['employee_per_day_salary'] = $employee['payment_per_day'] ?? 0;
                $dataToUpdate['employee_salary'] = $employee['salary'] ?? 0;
                $dataToUpdate['motivation_amount'] = $employee['motivation_amount'] ?? 0;
                $dataToUpdate['fixed_allowance'] = $employee_fixed_allowances ?? 0;
                $dataToUpdate['employee_total_allowance'] = $main_salary_employee_allowance['amount'] ?? 0;
                $dataToUpdate['employee_total_bonus'] = $main_salary_employee_bonus['amount'] ?? 0;
                $dataToUpdate['employee_additions_days_counter'] = $main_salary_employee_additions['days_amount'] ?? 0;
                $dataToUpdate['employee_additions_payment_total'] = $main_salary_employee_additions['total'] ?? 0;

                $dataToUpdate['social_insurance_amount'] = $employee['social_insurance_amount'] ?? 0;
                $dataToUpdate['medical_insurance_amount'] = $employee['medical_insurance_amount'] ?? 0;
                $dataToUpdate['employee_deductions_days_counter'] = $main_salary_employee_deductions['days_amount'] ?? 0;
                $dataToUpdate['employee_deductions_payment_total'] = $main_salary_employee_deductions['total'] ?? 0;
                $dataToUpdate['employee_absences_days_counter'] = $main_salary_employee_absence['days_amount'] ?? 0;
                $dataToUpdate['employee_absences_payment_total'] = $main_salary_employee_absence['total'] ?? 0;
                $dataToUpdate['employee_total_deduction_type'] = $main_salary_employee_deduction_type['amount'] ?? 0;
                $dataToUpdate['monthly_loan_amount'] = $main_salary_employee_loans['amount'] ?? 0;
                $dataToUpdate['permanent_loan_amount'] =   $main_salary_employee_p_loans ?? 0;

                $dataToUpdate['total_benefits'] =   $dataToUpdate['employee_salary'] + $dataToUpdate['motivation_amount']
                    + $dataToUpdate['fixed_allowance'] + $dataToUpdate['employee_total_allowance']
                    + $dataToUpdate['employee_total_bonus'] + $dataToUpdate['employee_additions_payment_total'];

                $dataToUpdate['total_deductions'] =   $dataToUpdate['social_insurance_amount']
                    + $dataToUpdate['medical_insurance_amount'] + $dataToUpdate['employee_deductions_payment_total']
                    + $dataToUpdate['employee_absences_payment_total'] + $dataToUpdate['employee_total_deduction_type']
                    + $dataToUpdate['monthly_loan_amount'] + $dataToUpdate['permanent_loan_amount'];

                $dataToUpdate['employee_net_salary'] = $main_salary_employee['employee_rollover_amount'] + ($dataToUpdate['total_benefits'] - $dataToUpdate['total_deductions']);
                update($main_salary_employee, $dataToUpdate);
            }
        }
    }
}
