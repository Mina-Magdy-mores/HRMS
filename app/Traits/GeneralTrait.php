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
use App\Models\MainSalaryEmployeePLoan;
use App\Models\AdminPanelSetting;
use App\Models\MainEmployeesVacationsBalances;
use App\Models\MainSalaryEmployeePLoanInstallment;
use Illuminate\Support\Facades\Auth;

trait GeneralTrait
{
    function recalculate_main_salary($main_salary_employee_id)
    {
        $authUser   = Auth::guard('admin')->user() ?? Auth::user();
        $company_id = $authUser?->company_id;
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
                $installmentsToUpdate = MainSalaryEmployeePLoanInstallment::where('next_installment_year_and_month', $finance_monthly_calender['year_and_month'])
                    ->where('company_id', $company_id)
                    ->where('is_archived', 0)
                    ->where('installment_status', '!=', '2')
                    ->where('employee_id', $main_salary_employee['employee_id'])
                    ->whereHas('mainSalaryEmployeePLoan', function ($query) use ($main_salary_employee) {
                        $query->where('is_disbursed', 1);
                        $query->where('employee_id', $main_salary_employee['employee_id']);
                    })
                    ->get();

                foreach ($installmentsToUpdate as $installment) {
                    $installment->update([
                        'installment_status' => '1',
                        'main_salary_employee_id' => $main_salary_employee_id,
                        'notes' => $installment->notes ? $installment->notes . ' (تم الخصم من راتب شهر: ' . $finance_monthly_calender['year_and_month'] . ')' : 'تم خصم القسط تلقائياً من راتب شهر: ' . $finance_monthly_calender['year_and_month']
                    ]);
                }

                // Update all disbursed, active parent loans of this employee to keep them fully in sync
                $allPLoans = MainSalaryEmployeePLoan::where('employee_id', $main_salary_employee['employee_id'])
                    ->where('company_id', $company_id)
                    ->where('is_disbursed', 1)
                    ->where('is_archived', 0)
                    ->get();

                foreach ($allPLoans as $pLoan) {
                    $totalPaid = MainSalaryEmployeePLoanInstallment::where('main_salary_employee_p_loan_id', $pLoan->id)
                        ->whereIn('installment_status', ['1', '2'])
                        ->sum('installment_amount_monthly');

                    $pLoan->update([
                        'paid_amount' => $totalPaid,
                        'remaining_amount' => max(0, $pLoan->amount - $totalPaid)
                    ]);
                }
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

    public function calculate_employees_vacations_balance($id)
    {
        $company_id = Auth::user()->company_id;
        $employee = Employee::query()->where(['company_id' => $company_id, 'active_for_vacation' => 1, 'employment_status' => 1])->find($id);
        $admin_panel_settings = getColsWhereRow(AdminPanelSetting::class, ['*'], ['company_id' => $company_id]);
        if (!empty($employee) && !empty($admin_panel_settings)) {
            $current_opened_month = getColsWhereRow(
                FinanceMonthlyCalendar::class,
                ['id', 'finance_yr', 'year_and_month'],
                ['company_id' => $company_id, 'status' => 1]
            );
            if (!empty($current_opened_month)) {
                $current_year = $current_opened_month->finance_yr;
                if ($employee->vacation_formula == 0) {
                    //first time to calculate the balance
                    $hire_date = strtotime($employee->hire_date);
                    $current_date = strtotime(date('Y-m-d'));
                    $difference_in_days = round(($current_date - $hire_date) / (60 * 60 * 24));
                    $activeDays = number_format($admin_panel_settings->after_days_begin_vacation) * 1;
                    $dateofActiveFormula = date('Y-m-d', strtotime('+' . $activeDays . ' days', $hire_date));
                    $hire_year = date('Y', $hire_date);
                    if ($difference_in_days >= $admin_panel_settings->after_days_begin_vacation) {
                        if ($hire_year == $current_year) {
                            //employee hired in the current year
                            $dataToInsert['current_month_balance'] = $admin_panel_settings->first_balance_begin_vacation;
                            $dataToInsert['total_available_balance'] = $admin_panel_settings->first_balance_begin_vacation;
                            $dataToInsert['remaining_net_balance'] = $admin_panel_settings->first_balance_begin_vacation;
                        } else {
                            //employee hired in the previous years
                            $dataToInsert['current_month_balance'] = $admin_panel_settings->monthly_vacation_balance;
                            $dataToInsert['total_available_balance'] = $admin_panel_settings->monthly_vacation_balance;
                            $dataToInsert['remaining_net_balance'] = $admin_panel_settings->monthly_vacation_balance;
                        }
                        if ($difference_in_days <= 365) {
                            $dataToInsert['year_and_month'] = date('Y-m', strtotime($dateofActiveFormula));
                        } else {
                            $dataToInsert['year_and_month'] = $current_year . '-01';
                        }
                        $dataToInsert['financial_year'] = $current_year;
                        $dataToInsert['employee_id'] = $employee->id;
                        $dataToInsert['company_id'] = $company_id;
                        $dataToInsert['added_by'] = Auth::user()->id;
                        $checkIfExsists = getColsWhereRow(
                            MainEmployeesVacationsBalances::class,
                            ['id'],
                            ['employee_id' => $employee->id, 'financial_year' => $current_year, 'year_and_month' => $dataToInsert['year_and_month']]
                        );
                        if (empty($checkIfExsists)) {
                            $flag = MainEmployeesVacationsBalances::create($dataToInsert);
                            if ($flag) {
                                $dataToUpdateInEmployee['vacation_formula'] = 1;
                                $dataToUpdateInEmployee['updated_by'] = Auth::user()->id;
                                update($employee, $dataToUpdateInEmployee);
                                // استدعاء الدالة مجدداً بشكل تلقائي لتعبئة الشهور المتبقية والفجوات حتى تاريخ الشهر المالي المفتوح الحالي
                                $this->calculate_employees_vacations_balance($employee->id);
                            }
                        }
                    }
                } else {
                    //already has balance
                    // جلب آخر سجل تمت إضافته للموظف عبر كافة السنوات المالية السابقة دون التقييد بالسنة الحالية فقط لسد الفجوات
                    $last_added = get_cols_where_row_orderby(
                        MainEmployeesVacationsBalances::class,
                        ['id', 'current_month_balance', 'total_available_balance', 'remaining_net_balance', 'year_and_month', 'financial_year'],
                        ['employee_id' => $employee->id, 'company_id' => $company_id],
                        'id',
                        'desc'
                    );
                    if (!empty($last_added)) {
                        if ($last_added->year_and_month != $current_opened_month->year_and_month) {
                            // استخدام كائن تاريخ مرن للمرور على كافة الشهور المفقودة وتغذيتها تلقائياً بالترتيب الزمني الصحيح
                            $start = new \DateTime($last_added->year_and_month . '-01');
                            $start->modify('+1 month');
                            $end = new \DateTime($current_opened_month->year_and_month . '-01');
                            while ($start <= $end) {
                                $yearMonthStr = $start->format('Y-m');
                                $financialYear = $start->format('Y');
                                $checkIfExsists = getColsWhereRow(
                                    MainEmployeesVacationsBalances::class,
                                    ['id'],
                                    ['employee_id' => $employee->id, 'financial_year' => $financialYear, 'year_and_month' => $yearMonthStr]
                                );
                                if (empty($checkIfExsists)) {
                                    $dataToInsert = [
                                        'year_and_month' => $yearMonthStr,
                                        'current_month_balance' => $admin_panel_settings->monthly_vacation_balance,
                                        'total_available_balance' => $admin_panel_settings->monthly_vacation_balance,
                                        'remaining_net_balance' => $admin_panel_settings->monthly_vacation_balance,
                                        'financial_year' => $financialYear,
                                        'employee_id' => $employee->id,
                                        'company_id' => $company_id,
                                        'added_by' => Auth::user()->id ?? 1
                                    ];
                                    MainEmployeesVacationsBalances::create($dataToInsert);
                                }
                                $start->modify('+1 month');
                            }
                        }
                    } else {
                        // حالة احتياطية إذا لم يتوفر أي سجل رصيد مسبق
                        $current_month = (int) date('m', strtotime($current_opened_month->year_and_month));
                        if ($current_opened_month->year_and_month) {
                            $firstMonthInOpenedYear = FinanceMonthlyCalendar::select(
                                [
                                    'id',
                                    'year_and_month'
                                ]
                            )->where(
                                ['company_id' => $company_id, 'finance_yr' => $current_year]
                            )->where('status', '>', 0)
                                ->orderBy('id', 'asc')->first();
                            if (!empty($firstMonthInOpenedYear)) {
                                $i = (int) date('m', strtotime($firstMonthInOpenedYear->year_and_month));
                                while ($i <= $current_month) {
                                    if ($i < 10) {
                                        $dataToInsert['year_and_month'] = $current_year . '-0' . $i;
                                    } else {
                                        $dataToInsert['year_and_month'] = $current_year . '-' . $i;
                                    }
                                    $dataToInsert['current_month_balance'] = $admin_panel_settings->monthly_vacation_balance;
                                    $dataToInsert['total_available_balance'] = $admin_panel_settings->monthly_vacation_balance;
                                    $dataToInsert['remaining_net_balance'] = $admin_panel_settings->monthly_vacation_balance;
                                    $dataToInsert['financial_year'] = $current_year;
                                    $dataToInsert['employee_id'] = $employee->id;
                                    $dataToInsert['company_id'] = $company_id;
                                    $dataToInsert['added_by'] = Auth::user()->id ?? 1;
                                    $checkIfExsists = getColsWhereRow(
                                        MainEmployeesVacationsBalances::class,
                                        ['id'],
                                        ['employee_id' => $employee->id, 'financial_year' => $current_year, 'year_and_month' => $dataToInsert['year_and_month']]
                                    );
                                    if (empty($checkIfExsists)) {
                                        MainEmployeesVacationsBalances::create($dataToInsert);
                                    }
                                    $i++;
                                }
                            }
                        }
                    }
                }
                $this->reupdate_vacation($id);
            }
        }
    }
    public function reupdate_vacation($id)
    {
        $company_id = Auth::user()->company_id;
        $employee = Employee::query()->where(['company_id' => $company_id, 'active_for_vacation' => 1, 'employment_status' => 1])->find($id);
        $admin_panel_settings = getColsWhereRow(AdminPanelSetting::class, ['*'], ['company_id' => $company_id]);
        if (!empty($employee) && !empty($admin_panel_settings)) {
            $current_opened_month = getColsWhereRow(
                FinanceMonthlyCalendar::class,
                ['id', 'finance_yr', 'year_and_month'],
                ['company_id' => $company_id, 'status' => 1]
            );
            if (!empty($current_opened_month)) {
                if ($employee->vacation_formula == 1) {
                    if ($admin_panel_settings->is_allowed_to_transfer_vacation == 1) {
                        $vacationBalance = get_cols_where(
                            MainEmployeesVacationsBalances::class,
                            ['id', 'employee_id', 'spent_balance', 'remaining_net_balance', 'current_month_balance', 'carryover_from_previous_month', 'total_available_balance'],
                            ['employee_id' => $employee->id, 'company_id' => $company_id],
                            'id',
                            'asc'
                        );
                    } else {
                        $vacationBalance = get_cols_where(
                            MainEmployeesVacationsBalances::class,
                            ['id', 'employee_id', 'spent_balance', 'remaining_net_balance', 'current_month_balance', 'carryover_from_previous_month', 'total_available_balance'],
                            ['employee_id' => $employee->id, 'company_id' => $company_id, 'financial_year' => $current_opened_month->finance_yr],
                            'id',
                            'asc'
                        );
                    }

                    if (!empty($vacationBalance)) {
                        $previous_remaining_net_balance = null;
                        foreach ($vacationBalance as $index => $balance) {
                            if ($index === 0) {
                                $previous_remaining_net_balance = $balance->remaining_net_balance;
                                continue;
                            }

                            $carryover = $previous_remaining_net_balance;
                            $total_available = $carryover + $balance->current_month_balance;
                            $remaining_net = $total_available - $balance->spent_balance;

                            if (
                                $balance->carryover_from_previous_month != $carryover ||
                                $balance->total_available_balance != $total_available ||
                                $balance->remaining_net_balance != $remaining_net
                            ) {

                                $dataToUpdate = [
                                    'carryover_from_previous_month' => $carryover,
                                    'total_available_balance' => $total_available,
                                    'remaining_net_balance' => $remaining_net
                                ];

                                update($balance, $dataToUpdate);
                            }

                            $previous_remaining_net_balance = $remaining_net;
                        }
                    }
                }
            }
        }
    }

    public function pullFingerprintVariablesToSalary($employee_id, $finance_monthly_calendar_id, $company_id)
    {
        // 1. Get MainSalaryEmployee record
        $mainSalaryEmployee = MainSalaryEmployee::where([
            'employee_id' => $employee_id,
            'finance_monthly_calendar_id' => $finance_monthly_calendar_id,
            'company_id' => $company_id,
            'is_archived' => 0
        ])->first();

        if (empty($mainSalaryEmployee)) {
            return;
        }

        // 2. Get Employee daily rate
        $employee = Employee::where('company_id', $company_id)->find($employee_id);
        if (empty($employee)) {
            return;
        }
        $payment_per_day = $employee->payment_per_day ?? 0;

        // 3. Delete existing automatically pulled records to avoid duplicate inserts
        // For absences (is_auto = 1)
        MainSalaryEmployeeAbsence::where([
            'employee_id' => $employee_id,
            'finance_monthly_calendar_id' => $finance_monthly_calendar_id,
            'company_id' => $company_id,
            'is_auto' => 1
        ])->delete();

        // For deductions (deduction_type = 2 (fingerprint), is_auto = 1)
        MainSalaryEmployeeDeduction::where([
            'employee_id' => $employee_id,
            'finance_monthly_calendar_id' => $finance_monthly_calendar_id,
            'company_id' => $company_id,
            'deduction_type' => 2,
            'is_auto' => 1
        ])->delete();

        $excludedVacationIds = [4, 5, 11, 12];

        // 4. Column 1: Calculate Absences (absence_hours) for non-vacation days
        $absenceRecords = \App\Models\AttendanceDeparture::where([
            'employee_id' => $employee_id,
            'finance_monthly_calendar_id' => $finance_monthly_calendar_id,
            'company_id' => $company_id,
            'occasion_id' => null
        ])
        ->where(function($q) {
            $q->whereNull('vacation_id')
              ->orWhere('vacation_id', 0);
        })
        ->whereNull('checkInDateTime')
        ->whereNull('checkOutDateTime')
        ->get();

        $absenceDaysAmount = 0;
        foreach ($absenceRecords as $rec) {
            if ($rec->cutting_days > 0) {
                $absenceDaysAmount += (float)$rec->cutting_days;
            } else {
                $shift = $rec->shift_hours > 0 ? (float)$rec->shift_hours : 8.0;
                $absenceDaysAmount += (float)$rec->absence_hours / $shift;
            }
        }

        // Column 3: Deductible vacations (vacation_id > 0 and not in excluded ones)
        $deductibleVacations = \App\Models\AttendanceDeparture::where([
            'employee_id' => $employee_id,
            'finance_monthly_calendar_id' => $finance_monthly_calendar_id,
            'company_id' => $company_id,
        ])
        ->where('vacation_id', '>', 0)
        ->whereNotIn('vacation_id', $excludedVacationIds)
        ->get();

        // إجازات تُخصم من المرتب مباشرةً (عقوبات حقيقية): بدون إذن، بدون راتب
        $salaryDeductibleVacationIds = [6, 13];

        // إجازات تُخصم من رصيد الإجازات (لا تُنزّل كغياب في المرتب)
        $balanceDeductibleGroups = [];
        $salaryDeductibleGroups  = [];

        foreach ($deductibleVacations as $dv) {
            $days = $dv->cutting_days > 0 ? (float)$dv->cutting_days : 1.00;
            if (in_array($dv->vacation_id, $salaryDeductibleVacationIds)) {
                // تُخصم من المرتب
                if (!isset($salaryDeductibleGroups[$dv->vacation_id])) {
                    $salaryDeductibleGroups[$dv->vacation_id] = 0;
                }
                $salaryDeductibleGroups[$dv->vacation_id] += $days;
            } else {
                // تُخصم من رصيد الإجازات فقط — لا خصم مالي
                if (!isset($balanceDeductibleGroups[$dv->vacation_id])) {
                    $balanceDeductibleGroups[$dv->vacation_id] = 0;
                }
                $balanceDeductibleGroups[$dv->vacation_id] += $days;
            }
        }

        // Column 2: Calculate delay / manual deduction days (cutting_days) on non-vacation days
        $deductionDaysAmount = \App\Models\AttendanceDeparture::where([
            'employee_id' => $employee_id,
            'finance_monthly_calendar_id' => $finance_monthly_calendar_id,
            'company_id' => $company_id
        ])
        ->where(function($q) {
            $q->whereNull('vacation_id')
              ->orWhere('vacation_id', 0);
        })
        ->where(function ($query) {
            $query->whereNotNull('checkInDateTime')
                  ->orWhereNotNull('checkOutDateTime');
        })
        ->sum('cutting_days');

        // 6. Insert new variables if days_amount > 0
        // Insert Column 1 (Standard Absences)
        if ($absenceDaysAmount > 0) {
            MainSalaryEmployeeAbsence::create([
                'main_salary_employee_id' => $mainSalaryEmployee->id,
                'employee_id' => $employee_id,
                'finance_monthly_calendar_id' => $finance_monthly_calendar_id,
                'days_amount' => $absenceDaysAmount,
                'total' => $absenceDaysAmount * $payment_per_day,
                'company_id' => $company_id,
                'is_auto' => 1,
                'status' => 1,
                'added_by' => Auth::id() ?? 1,
                'notes' => 'خصم غياب تلقائي من البصمة',
            ]);
        }

        // Insert Column 3a (إجازات تُخصم من المرتب: بدون إذن، بدون راتب)
        if (!empty($salaryDeductibleGroups)) {
            $vacationTypes = \App\Models\VacationType::whereIn('id', array_keys($salaryDeductibleGroups))->get();

            foreach ($salaryDeductibleGroups as $vacId => $daysAmount) {
                if ($daysAmount > 0) {
                    $vt = $vacationTypes->firstWhere('id', $vacId);
                    $vacName = $vt ? $vt->name : 'إجازة';
                    MainSalaryEmployeeAbsence::create([
                        'main_salary_employee_id' => $mainSalaryEmployee->id,
                        'employee_id' => $employee_id,
                        'finance_monthly_calendar_id' => $finance_monthly_calendar_id,
                        'days_amount' => $daysAmount,
                        'total' => $daysAmount * $payment_per_day,
                        'company_id' => $company_id,
                        'is_auto' => 1,
                        'status' => 1,
                        'added_by' => Auth::id() ?? 1,
                        'notes' => 'خصم ' . $vacName . ' تلقائي من البصمة',
                    ]);
                }
            }
        }

        // إجازات الرصيد: تُخصم تلقائياً من سجل رصيد الإجازات السنوية
        // (اعتيادية، سنوية، مرضية، زواج، وضع/أمومة، حج، عزاء، عيد ميلاد، ميلاد، وغيرها)
        $calendar = \App\Models\FinanceMonthlyCalendar::find($finance_monthly_calendar_id);
        if ($calendar) {
            $yearMonth = $calendar->year_and_month; // e.g. "2028-03"

            $balanceRecord = \App\Models\MainEmployeesVacationsBalances::where([
                'employee_id'    => $employee_id,
                'year_and_month' => $yearMonth,
                'company_id'     => $company_id,
            ])->first();

            if ($balanceRecord) {
                // حساب إجمالي أيام الرصيد المستهلك من البصمة لهذا الشهر
                // (نحسب القيمة من الصفر في كل سحب لتجنب التراكم)
                $totalBalanceDays = 0;
                foreach ($balanceDeductibleGroups as $vacId => $daysAmount) {
                    $totalBalanceDays += $daysAmount;
                }

                // نضبط القيمة مباشرة بدلاً من الجمع على الموجود
                $newRemaining = (float)$balanceRecord->total_available_balance - $totalBalanceDays;

                $balanceRecord->update([
                    'spent_balance'        => $totalBalanceDays,
                    'remaining_net_balance' => max(0, $newRemaining),
                ]);
            }
        }


        // Insert Column 2 (Deductions / Delay)
        if ($deductionDaysAmount > 0) {
            MainSalaryEmployeeDeduction::create([
                'main_salary_employee_id' => $mainSalaryEmployee->id,
                'employee_id' => $employee_id,
                'finance_monthly_calendar_id' => $finance_monthly_calendar_id,
                'deduction_type' => 2, // 2 = finger print deduction
                'days_amount' => $deductionDaysAmount,
                'total' => $deductionDaysAmount * $payment_per_day,
                'company_id' => $company_id,
                'is_auto' => 1,
                'status' => 1,
                'added_by' => Auth::id() ?? 1,
                'notes' => 'خصم تأخير/انصراف مبكر تلقائي من البصمة',
            ]);
        }

        // 7. Recalculate main salary after variables are updated
        $this->recalculate_main_salary($mainSalaryEmployee->id);
    }

    public function pullFingerprintVariablesToSalaryForCalendar($finance_monthly_calendar_id, $company_id)
    {
        $activeSalaries = MainSalaryEmployee::where([
            'finance_monthly_calendar_id' => $finance_monthly_calendar_id,
            'company_id' => $company_id,
            'is_archived' => 0
        ])->get();

        foreach ($activeSalaries as $salary) {
            $this->pullFingerprintVariablesToSalary($salary->employee_id, $finance_monthly_calendar_id, $company_id);
        }
    }
}

