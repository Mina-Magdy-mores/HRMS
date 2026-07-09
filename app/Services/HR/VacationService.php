<?php

namespace App\Services\HR;

use App\Services\BaseService;
use App\Models\Employee;
use App\Models\AdminPanelSetting;
use App\Models\FinanceMonthlyCalendar;
use App\Models\MainEmployeesVacationsBalances;
use Illuminate\Support\Facades\Auth;

class VacationService extends BaseService
{
    public function __construct()
    {
        $this->setModel(MainEmployeesVacationsBalances::class);
    }

    /**
     * Calculate and sync vacations balance for an employee.
     */
    public function calculateEmployeesVacationsBalance($id)
    {
        $company_id = $this->getCompanyId();
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
                    // First time to calculate the balance
                    $hire_date = strtotime($employee->hire_date);
                    $current_date = strtotime(date('Y-m-d'));
                    $difference_in_days = round(($current_date - $hire_date) / (60 * 60 * 24));
                    $activeDays = number_format($admin_panel_settings->after_days_begin_vacation) * 1;
                    $dateofActiveFormula = date('Y-m-d', strtotime('+' . $activeDays . ' days', $hire_date));
                    $hire_year = date('Y', $hire_date);

                    if ($difference_in_days >= $admin_panel_settings->after_days_begin_vacation) {
                        if ($hire_year == $current_year) {
                            // Employee hired in the current year
                            $dataToInsert['current_month_balance'] = $admin_panel_settings->first_balance_begin_vacation;
                            $dataToInsert['total_available_balance'] = $admin_panel_settings->first_balance_begin_vacation;
                            $dataToInsert['remaining_net_balance'] = $admin_panel_settings->first_balance_begin_vacation;
                        } else {
                            // Employee hired in previous years
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
                        $dataToInsert['added_by'] = $this->getUserId();

                        $checkIfExsists = getColsWhereRow(
                            MainEmployeesVacationsBalances::class,
                            ['id'],
                            ['employee_id' => $employee->id, 'financial_year' => $current_year, 'year_and_month' => $dataToInsert['year_and_month']]
                        );

                        if (empty($checkIfExsists)) {
                            $flag = MainEmployeesVacationsBalances::create($dataToInsert);
                            if ($flag) {
                                $dataToUpdateInEmployee['vacation_formula'] = 1;
                                $dataToUpdateInEmployee['updated_by'] = $this->getUserId();
                                update($employee, $dataToUpdateInEmployee);
                                
                                // Auto recursively calculate remaining months
                                $this->calculateEmployeesVacationsBalance($employee->id);
                            }
                        }
                    }
                } else {
                    // Already has balance, fill gaps
                    $last_added = get_cols_where_row_orderby(
                        MainEmployeesVacationsBalances::class,
                        ['id', 'current_month_balance', 'total_available_balance', 'remaining_net_balance', 'year_and_month', 'financial_year'],
                        ['employee_id' => $employee->id, 'company_id' => $company_id],
                        'id',
                        'desc'
                    );

                    if (!empty($last_added)) {
                        if ($last_added->year_and_month != $current_opened_month->year_and_month) {
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
                                        'added_by' => $this->getUserId() ?? 1
                                    ];
                                    MainEmployeesVacationsBalances::create($dataToInsert);
                                }
                                $start->modify('+1 month');
                            }
                        }
                    } else {
                        // Fallback logic
                        $current_month = (int) date('m', strtotime($current_opened_month->year_and_month));
                        if ($current_opened_month->year_and_month) {
                            $firstMonthInOpenedYear = FinanceMonthlyCalendar::select(['id', 'year_and_month'])
                                ->where(['company_id' => $company_id, 'finance_yr' => $current_year])
                                ->where('status', '>', 0)
                                ->orderBy('id', 'asc')
                                ->first();

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
                                    $dataToInsert['added_by'] = $this->getUserId() ?? 1;

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
                $this->reupdateVacation($id);
            }
        }
    }

    /**
     * Reupdate/sync carrying over vacation balance values chronologically.
     */
    public function reupdateVacation($id)
    {
        $company_id = $this->getCompanyId();
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
}
