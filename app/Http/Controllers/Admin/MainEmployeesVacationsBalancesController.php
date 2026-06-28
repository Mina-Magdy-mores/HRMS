<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminPanelSetting;
use App\Models\Branche;
use App\Models\Department;
use App\Models\Employee;
use App\Models\FinanceMonthlyCalendar;
use App\Models\JobsCategory;
use App\Models\MainEmployeesVacationsBalances;
use App\Models\FinanceCalendar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MainEmployeesVacationsBalancesController extends Controller
{
    public function index()
    {
        $company_id = Auth::user()->company_id;
        $employees = getColsWhereP(
            Employee::class,
            [
                'addedBy',
                'updatedBy',
                'job',
                'department',
                'branch',
            ],
            ['*'],
            ['company_id' => $company_id],
            'id',
            'asc',
            PAGEINATION_COUNTER
        );
        $branches = get_cols_where(Branche::class, ['id', 'name'], ['company_id' => $company_id, 'status' => 1], 'id', 'asc');
        $departments = get_cols_where(Department::class, ['id', 'name'], ['company_id' => $company_id, 'status' => 1], 'id', 'asc');
        $jobs = get_cols_where(JobsCategory::class, ['id', 'name'], ['company_id' => $company_id, 'status' => 1], 'id', 'asc');
        return view('admin.mainEmployeesVacationsBalances.index', compact('employees', 'branches', 'departments', 'jobs'));
    }

    public function search(Request $request)
    {
        if ($request->ajax()) {
            $fingerprint_code = $request->fingerprint_code;
            $employee_code = $request->employee_code;
            $name = $request->name;
            $branch_id = $request->branch_id;
            $department_id = $request->department_id;
            $job_id = $request->job_id;
            $employment_status = $request->employment_status;
            $active_for_vacation = $request->active_for_vacation;
            $gender = $request->gender;
            $code_type = $request->code_type;

            if ($code_type == '') {
                $field1 = "id";
                $operator1 = ">=";
                $value1 = 0;
            } else {
                if ($code_type == 'fingerprint_code') {
                    if (empty($fingerprint_code)) {
                        $field1 = "id";
                        $operator1 = ">=";
                        $value1 = 0;
                    } else {
                        $field1 = "fingerprint_code";
                        $operator1 = "=";
                        $value1 = $fingerprint_code;
                    }
                } else if ($code_type == 'employee_code') {
                    if (empty($employee_code)) {
                        $field1 = "id";
                        $operator1 = ">=";
                        $value1 = 0;
                    } else {
                        $field1 = "employee_code";
                        $operator1 = "=";
                        $value1 = $employee_code;
                    }
                } else {
                    $field1 = "id";
                    $operator1 = ">=";
                    $value1 = 0;
                }
            }

            if (empty($name)) {
                $field2 = "id";
                $operator2 = ">=";
                $value2 = 0;
            } else {
                $field2 = "name";
                $operator2 = "like";
                $value2 = "%{$name}%";
            }

            if (empty($branch_id)) {
                $field3 = "id";
                $operator3 = ">=";
                $value3 = 0;
            } else {
                $field3 = "branch_id";
                $operator3 = "=";
                $value3 = $branch_id;
            }

            if (empty($department_id)) {
                $field4 = "id";
                $operator4 = ">=";
                $value4 = 0;
            } else {
                $field4 = "department_id";
                $operator4 = "=";
                $value4 = $department_id;
            }

            if (empty($job_id)) {
                $field5 = "id";
                $operator5 = ">=";
                $value5 = 0;
            } else {
                $field5 = "job_id";
                $operator5 = "=";
                $value5 = $job_id;
            }

            if ($employment_status == '') {
                $field6 = "id";
                $operator6 = ">=";
                $value6 = 0;
            } else if ($employment_status == 0 || $employment_status == 1) {
                $field6 = "employment_status";
                $operator6 = "=";
                $value6 = $employment_status;
            }

            if ($active_for_vacation == '') {
                $field7 = "id";
                $operator7 = ">=";
                $value7 = 0;
            } else {
                $field7 = "active_for_vacation";
                $operator7 = "=";
                $value7 = $active_for_vacation;
            }

            if ($gender == '') {
                $field8 = "id";
                $operator8 = ">=";
                $value8 = 0;
            } else {
                $field8 = "gender";
                $operator8 = "=";
                $value8 = $gender;
            }

            $where = [
                [$field1, $operator1, $value1],
                [$field2, $operator2, $value2],
                [$field3, $operator3, $value3],
                [$field4, $operator4, $value4],
                [$field5, $operator5, $value5],
                [$field6, $operator6, $value6],
                [$field7, $operator7, $value7],
                [$field8, $operator8, $value8],
            ];

            $employees = getColsWhereP(Employee::class, [
                'addedBy',
                'updatedBy',
                'job',
                'department',
                'branch',
            ], ['*'], $where, 'id', 'asc', PAGEINATION_COUNTER);

            return view('admin.mainEmployeesVacationsBalances.ajaxSearch', compact('employees'));
        }
    }

    public function show($id)
    {
        $company_id = Auth::user()->company_id;
        $employee = Employee::with([
            'job',
            'department',
            'branch',
        ])->where('company_id', $company_id)->find($id);
        if (empty($employee)) {
            return redirect()->route('admin.main-employees-vacations-balances.index')->with('error', 'الموظف غير موجود');
        }

        //Calculate Total monthly and annual vacation for the employee
        $this->calculate_employees_vacations_balance($id);
        $this->calculate_employees_vacations_balance($id);
        
        $vacationBalances = MainEmployeesVacationsBalances::with(['addedBy', 'updatedBy', 'archivedBy'])
            ->where('employee_id', $id)
            ->where('company_id', $company_id)
            ->orderBy('id', 'asc')
            ->get();

        $current_opened_month = FinanceMonthlyCalendar::with('month')
            ->where('company_id', $company_id)
            ->where('status', 1)
            ->first();

        $financialYears = FinanceCalendar::where('company_id', $company_id)->orderBy('finance_yr', 'desc')->get();

        return view('admin.mainEmployeesVacationsBalances.show', compact('employee', 'vacationBalances', 'current_opened_month', 'financialYears'));
    }

    public function ajaxSearchShow(Request $request, $id)
    {
        if ($request->ajax()) {
            $company_id = Auth::user()->company_id;
            $financial_year = $request->financial_year;

            $vacationBalancesQuery = MainEmployeesVacationsBalances::with(['addedBy', 'updatedBy', 'archivedBy'])
                ->where('employee_id', $id)
                ->where('company_id', $company_id)
                ->orderBy('id', 'asc');

            if (!empty($financial_year)) {
                $vacationBalancesQuery->where('financial_year', $financial_year);
            }

            $vacationBalances = $vacationBalancesQuery->get();

            return view('admin.mainEmployeesVacationsBalances.show-table', compact('vacationBalances'));
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
                    $current_date = strtotime(date('Y-m-d', strtotime('+2 month')));
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
                            }
                        }
                    }
                } else {
                    //already has balance
                    $last_added = get_cols_where_row_orderby(
                        MainEmployeesVacationsBalances::class,
                        ['id', 'current_month_balance', 'total_available_balance', 'remaining_net_balance', 'year_and_month', 'financial_year'],
                        ['employee_id' => $employee->id, 'financial_year' => $current_year, 'company_id' => $company_id],
                        'id',
                        'desc'
                    );
                    $current_month = (int) date('m', strtotime($current_opened_month->year_and_month));
                    if (!empty($last_added)) {
                        if ($last_added->year_and_month != $current_opened_month->year_and_month) {
                            $i = (int) date('m', strtotime($last_added->year_and_month));
                            $i += 1;
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
                                $dataToInsert['added_by'] = Auth::user()->id;
                                $checkIfExsists = getColsWhereRow(
                                    MainEmployeesVacationsBalances::class,
                                    ['id'],
                                    ['employee_id' => $employee->id, 'financial_year' => $current_year, 'year_and_month' => $dataToInsert['year_and_month']]
                                );
                                if (empty($checkIfExsists)) {
                                    $flag = MainEmployeesVacationsBalances::create($dataToInsert);
                                    if ($flag) {
                                        //later
                                    }
                                }
                                $i++;
                            }
                        }
                    } else {

                        $current_month = (int) date('m', strtotime($current_opened_month->year_and_month));
                        if ($current_opened_month->year_and_month) {
                            $firstMonthInOpenedYear = get_cols_where_row_orderby(
                                FinanceMonthlyCalendar::class,
                                ['id', 'year_and_month'],
                                ['company_id' => $company_id, 'finance_yr' => $current_year, 'status' => 2],
                                'id',
                                'asc'
                            );
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
                                    $dataToInsert['added_by'] = Auth::user()->id;
                                    $checkIfExsists = getColsWhereRow(
                                        MainEmployeesVacationsBalances::class,
                                        ['id'],
                                        ['employee_id' => $employee->id, 'financial_year' => $current_year, 'year_and_month' => $dataToInsert['year_and_month']]
                                    );
                                    if (empty($checkIfExsists)) {
                                        $flag = MainEmployeesVacationsBalances::create($dataToInsert);
                                        if ($flag) {
                                            //later
                                        }
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
                            ['id', 'spent_balance', 'remaining_net_balance', 'current_month_balance', 'carryover_from_previous_month', 'total_available_balance'],
                            ['employee_id' => $employee->id, 'company_id' => $company_id],
                            'id',
                            'asc'
                        );
                    } else {
                        $vacationBalance = get_cols_where(
                            MainEmployeesVacationsBalances::class,
                            ['id', 'spent_balance', 'remaining_net_balance', 'current_month_balance', 'carryover_from_previous_month', 'total_available_balance'],
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
