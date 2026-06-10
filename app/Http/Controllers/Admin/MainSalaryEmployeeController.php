<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminPanelSetting;
use App\Models\Branche;
use App\Models\Department;
use App\Models\Employee;
use App\Models\FinanceMonthlyCalendar;
use App\Models\JobsCategory;
use App\Models\MainSalaryEmployee;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MainSalaryEmployeeController extends Controller
{
    use GeneralTrait;
    public function index()
    {
        $company_id = Auth::user()->company_id;
        $financeMonthlyCalendars = get_cols_where_order2_with(FinanceMonthlyCalendar::class, ['financeCalendar'], ['*'], ['company_id' => $company_id], 'finance_yr', 'desc', 'id', 'asc', 12);
        foreach ($financeMonthlyCalendars as $calendar) {
            $calendar->total_opened_months = get_count_where(FinanceMonthlyCalendar::class, ['company_id' => $company_id, 'status' => '1']);
            $calendar->total_prev_months_waiting_to_open = FinanceMonthlyCalendar::where(['company_id' => $company_id, 'status' => '0', 'finance_yr' => $calendar->finance_yr])->where('month_id', '<', $calendar->month_id)->count();
        }
        return view('admin.mainSalaryEmployee.index', ['financeMonthlyCalendars' => $financeMonthlyCalendars]);
    }

    public function show($id)
    {
        $company_id = Auth::user()->company_id;
        $financeMonthlyCalendar = FinanceMonthlyCalendar::with('month')
            ->where('company_id', $company_id)
            ->where('id', $id)
            ->first();

        if (empty($financeMonthlyCalendar)) {
            return redirect()->route('admin.main-salary-employee-deductions.index')->with('error', 'عفوا غير قادر للوصول الى بيانات الشهر');
        }

        $employees = Employee::select(['id', 'name', 'employee_code', 'salary', 'payment_per_day'])->where('company_id', $company_id)->orderBy('id', 'asc')->get();
        $employees_has_opened_monthly_record = Employee::select([
            'id',
            'name',
            'employee_code',
            'salary',
            'payment_per_day'
        ])
            ->where('company_id', $company_id)
            ->whereHas('mainSalaryEmployee', function ($query) use ($id) {
                $query->where('employee_status', 1)
                    ->where('finance_monthly_calendar_id', $id)
                    ->where('is_archived', 0);
            })
            ->orderBy('id', 'asc')
            ->get();

        $mainSalaryEmployees = MainSalaryEmployee::with([
            'employee',
            'financeMonthlyCalendar',
            'addedBy',
            'updatedBy',
            'branch',
            'department'
        ])
            ->select(
                '*'
            )
            ->where('company_id', $company_id)
            ->where('finance_monthly_calendar_id', $id)
            ->orderBy('id', 'asc')
            ->paginate(PAGEINATION_COUNTER);
        $mainSalaryEmployees2 = MainSalaryEmployee::select('*')->where('company_id', $company_id)
            ->where('finance_monthly_calendar_id', $id)
            ->orderBy('id', 'asc')
            ->get();


        $branches = get_cols_where(Branche::class, ['id', 'name'], ['company_id' => $company_id, 'status' => 1], 'id', 'asc');
        $departments = get_cols_where(Department::class, ['id', 'name'], ['company_id' => $company_id, 'status' => 1], 'id', 'asc');
        $jobs = get_cols_where(JobsCategory::class, ['id', 'name'], ['company_id' => $company_id, 'status' => 1], 'id', 'asc');

        $employees_does_not_have_opened_monthly_record = Employee::select([
            'id',
            'name',
            'employee_code',
            'salary',
            'payment_per_day'
        ])
            ->where('company_id', $company_id)
            ->where('employment_status', 1)
            ->whereDoesntHave('mainSalaryEmployee', function ($query) use ($id) {
                $query->where('finance_monthly_calendar_id', $id)
                    ->where('is_archived', 0);
            })
            ->orderBy('id', 'asc')
            ->get();

        return view('admin.mainSalaryEmployee.show', [
            'financeMonthlyCalendar' => $financeMonthlyCalendar,
            'mainSalaryEmployees' => $mainSalaryEmployees,
            'mainSalaryEmployees2' => $mainSalaryEmployees2,
            'employees' => $employees,
            'employees_has_opened_monthly_record' => $employees_has_opened_monthly_record,
            'employees_does_not_have_opened_monthly_record' => $employees_does_not_have_opened_monthly_record,
            'branches' => $branches,
            'departments' => $departments,
            'jobs' => $jobs,
        ]);
    }

    public function store(Request $request)
    {
        if ($request->ajax()) {
            $company_id = Auth::user()->company_id;
            $employee_id = $request->employee_id;
            $finance_monthly_calendar_id = $request->finance_monthly_calendar_id;

            $financeMonthlyCalendar = getColsWhereRow(FinanceMonthlyCalendar::class, ['*'], ['company_id' => $company_id, 'id' => $finance_monthly_calendar_id, 'status' => 1]);
            if (empty($financeMonthlyCalendar)) {
                return response()->json(['status' => 'false', 'message' => 'عفوا، الشهر المالي غير مفتوح أو غير موجود']);
            }

            $employee = getColsWhereRow(Employee::class, ['*'], ['company_id' => $company_id, 'id' => $employee_id, 'employment_status' => 1]);
            if (empty($employee)) {
                return response()->json(['status' => 'false', 'message' => 'عفوا، الموظف غير موجود أو غير نشط']);
            }

            $checkIfExists = get_count_where(MainSalaryEmployee::class, [
                'company_id' => $company_id,
                'employee_id' => $employee_id,
                'finance_monthly_calendar_id' => $finance_monthly_calendar_id,
                'is_archived' => 0
            ]);

            if ($checkIfExists > 0) {
                return response()->json(['status' => 'false', 'message' => 'عفوا، يوجد سجل مالي مفتوح بالفعل لهذا الموظف في هذا الشهر']);
            }

            try {
                return DB::transaction(function () use ($employee, $financeMonthlyCalendar, $company_id) {
                    $dataToInsert = [];
                    $dataToInsert['finance_monthly_calendar_id'] = $financeMonthlyCalendar->id;
                    $dataToInsert['employee_id'] = $employee->id;
                    $dataToInsert['company_id'] = $company_id;
                    $dataToInsert['employee_name'] = $employee->name;
                    $dataToInsert['employee_per_day_salary'] = $employee->payment_per_day;
                    $dataToInsert['sensitive'] = $employee->has_sensitive_data;
                    $dataToInsert['employee_status'] = $employee->employment_status;
                    $dataToInsert['employee_branch_id'] = $employee->branch_id;
                    $dataToInsert['employee_department_id'] = $employee->department_id;
                    $dataToInsert['employee_job_id'] = $employee->job_id;
                    $dataToInsert['employee_salary'] = $employee->salary;

                    $employee_rollover_amount = get_cols_where_row_orderby(
                        MainSalaryEmployee::class,
                        ['employee_net_salary_after_close_for_roll_over'],
                        ['employee_id' => $employee->id, 'company_id' => $company_id, 'is_archived' => 1],
                        'id',
                        'desc'
                    );

                    if (!empty($employee_rollover_amount)) {
                        $dataToInsert['employee_rollover_amount'] = $employee_rollover_amount->employee_net_salary_after_close_for_roll_over;
                    } else {
                        $dataToInsert['employee_rollover_amount'] = 0;
                    }

                    $dataToInsert['year_and_month'] = $financeMonthlyCalendar->year_and_month;
                    $dataToInsert['financial_year'] = $financeMonthlyCalendar->finance_yr;
                    $dataToInsert['payment_method'] = $employee->payment_method;
                    $dataToInsert['added_by'] = Auth::id();

                    $insert = insert(MainSalaryEmployee::class, $dataToInsert, true);
                    if (!empty($insert)) {
                        $this->recalculate_main_salary($insert->id);
                        return response()->json(['status' => 'true', 'message' => 'تم إضافة سجل الراتب للموظف بنجاح']);
                    } else {
                        return response()->json(['status' => 'false', 'message' => 'عفوا، حدث خطأ أثناء إضافة السجل المالي']);
                    }
                });
            } catch (\Exception $e) {
                return response()->json(['status' => 'false', 'message' => 'حدث خطأ: ' . $e->getMessage()]);
            }
        }
    }
    public function destroy(Request $request)
    {
        if ($request->ajax()) {
            $company_id = Auth::user()->company_id;
            $employee_id = $request->employee_id;
            $finance_monthly_calendar_id = $request->finance_monthly_calendar_id;

            $financeMonthlyCalendar = getColsWhereRow(FinanceMonthlyCalendar::class, ['*'], ['company_id' => $company_id, 'id' => $finance_monthly_calendar_id, 'status' => 1]);
            if (empty($financeMonthlyCalendar)) {
                return response()->json(['status' => 'false', 'message' => 'عفوا، الشهر المالي غير مفتوح أو غير موجود']);
            }

            $employee = getColsWhereRow(Employee::class, ['*'], ['company_id' => $company_id, 'id' => $employee_id, 'employment_status' => 1]);
            if (empty($employee)) {
                return response()->json(['status' => 'false', 'message' => 'عفوا، الموظف غير موجود أو غير نشط']);
            }

            $mainSalaryEmployee = getColsWhereRow(MainSalaryEmployee::class, ['id'], [
                'company_id' => $company_id,
                'employee_id' => $employee_id,
                'finance_monthly_calendar_id' => $finance_monthly_calendar_id,
                'is_archived' => 0,
                'is_disbursed' => 0,
                'payment_on_hold' => 0,
            ]);

            if (empty($mainSalaryEmployee)) {
                return response()->json(['status' => 'false', 'message' => ' عذرا، لا يمكن حذف الراتب لأنه محذوف بالفعل او موقوفمش او مؤرشف او تم صرف']);
            }

            try {
                return DB::transaction(function () use ($mainSalaryEmployee) {
                    $mainSalaryEmployee->mainSalaryEmployeeDeductions()->delete();
                    $mainSalaryEmployee->mainSalaryEmployeeAbsences()->delete();
                    $mainSalaryEmployee->mainSalaryEmployeeDeductionTypes()->delete();
                    $mainSalaryEmployee->mainSalaryEmployeeAdditions()->delete();
                    $mainSalaryEmployee->mainSalaryEmployeeLoans()->delete();
                    $mainSalaryEmployee->mainSalaryEmployeeBonuses()->delete();
                    $mainSalaryEmployee->mainSalaryEmployeeAllowances()->delete();
                    $mainSalaryEmployee->mainSalaryEmployeePLoanInstallments()->update([
                        'installment_status' => '0',
                        'main_salary_employee_id' => null,
                    ]);

                    $flag = destroy($mainSalaryEmployee);
                    if (!empty($flag)) {
                        return response()->json(['status' => 'true', 'message' => 'تم حذف سجل الراتب للموظف بنجاح']);
                    } else {
                        return response()->json(['status' => 'false', 'message' => 'عفوا، حدث خطأ أثناء حذف السجل المالي']);
                    }
                });
            } catch (\Exception $e) {
                return response()->json(['status' => 'false', 'message' => 'حدث خطأ: ' . $e->getMessage()]);
            }
        }
    }


    public function ajaxSearch(Request $request)
    {
        if ($request->ajax()) {
            $employee_id_search = $request->employee_id_search;
            $is_disbursed_search = $request->is_disbursed_search;
            $payment_on_hold_search = $request->payment_on_hold_search;
            $branch_id_search = $request->branch_id_search;
            $department_id_search = $request->department_id_search;
            $job_id_search = $request->job_id_search;
            $employment_status_search = $request->employment_status_search;
            $payment_method_search = $request->payment_method_search;
            $is_archived_search = $request->is_archived_search;

            if ($employee_id_search == '') {
                $field1 = "id";
                $operator1 = ">=";
                $value1 = 0;
            } else {
                $field1 = "employee_id";
                $operator1 = "=";
                $value1 = $employee_id_search;
            }
            if ($is_disbursed_search == '') {
                $field2 = "id";
                $operator2 = ">=";
                $value2 = 0;
            } else {
                $field2 = "is_disbursed";
                $operator2 = "=";
                $value2 = $is_disbursed_search;
            }

            if ($payment_on_hold_search == '') {
                $field3 = "id";
                $operator3 = ">=";
                $value3 = 0;
            } else {
                $field3 = "payment_on_hold";
                $operator3 = "=";
                $value3 = $payment_on_hold_search;
            }
            if ($is_archived_search == '') {
                $field4 = "id";
                $operator4 = ">=";
                $value4 = 0;
            } else {
                $field4 = "is_archived";
                $operator4 = "=";
                $value4 = $is_archived_search;
            }

            if ($branch_id_search == '') {
                $field5 = "id";
                $operator5 = ">=";
                $value5 = 0;
            } else {
                $field5 = "employee_branch_id";
                $operator5 = "=";
                $value5 = $branch_id_search;
            }
            if ($department_id_search == '') {
                $field6 = "id";
                $operator6 = ">=";
                $value6 = 0;
            } else {
                $field6 = "employee_department_id";
                $operator6 = "=";
                $value6 = $department_id_search;
            }
            if ($job_id_search == '') {
                $field7 = "id";
                $operator7 = ">=";
                $value7 = 0;
            } else {
                $field7 = "employee_job_id";
                $operator7 = "=";
                $value7 = $job_id_search;
            }
            if ($employment_status_search == '') {
                $field8 = "id";
                $operator8 = ">=";
                $value8 = 0;
            } else {
                $field8 = "employee_status";
                $operator8 = "=";
                $value8 = $employment_status_search;
            }
            if ($payment_method_search == '') {
                $field9 = "id";
                $operator9 = ">=";
                $value9 = 0;
            } else {
                $field9 = "payment_method";
                $operator9 = "=";
                $value9 = $payment_method_search;
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
                [$field9, $operator9, $value9],
            ];
            $where = collect($where)->unique()->values()->toArray();

            $company_id = Auth::user()->company_id;
            $mainSalaryEmployees = MainSalaryEmployee::with([
                'employee',
                'financeMonthlyCalendar',
                'addedBy',
                'updatedBy',
                'branch',
                'department'
            ])
                ->where('company_id', $company_id)
                ->where('finance_monthly_calendar_id', $request->finance_monthly_calendar_id)
                ->where($where)
                ->orderBy('id', 'asc')
                ->paginate(PAGEINATION_COUNTER);


            $financeMonthlyCalendar = FinanceMonthlyCalendar::select(['id', 'status'])->where([
                'company_id' => $company_id,
                'id' => $request->finance_monthly_calendar_id
            ])->first();

            return view('admin.mainSalaryEmployee.ajaxSearch', [
                'mainSalaryEmployees' => $mainSalaryEmployees,
                'financeMonthlyCalendar' => $financeMonthlyCalendar
            ]);
        }
    }

    public function printSearch(Request $request)
    {
        $finance_monthly_calendar_id = $request->finance_monthly_calendar_id_search;
        $employee_id_search = $request->employee_id_search;
        $is_disbursed_search = $request->is_disbursed_search;
        $payment_on_hold_search = $request->payment_on_hold_search;
        $branch_id_search = $request->branch_id_search;
        $department_id_search = $request->department_id_search;
        $job_id_search = $request->job_id_search;
        $employment_status_search = $request->employment_status_search;
        $payment_method_search = $request->payment_method_search;
        $is_archived_search = $request->is_archived_search;


        if ($employee_id_search == '') {
            $field1 = "id";
            $operator1 = ">=";
            $value1 = 0;
        } else {
            $field1 = "employee_id";
            $operator1 = "=";
            $value1 = $employee_id_search;
        }
        if ($is_disbursed_search == '') {
            $field2 = "id";
            $operator2 = ">=";
            $value2 = 0;
        } else {
            $field2 = "is_disbursed";
            $operator2 = "=";
            $value2 = $is_disbursed_search;
        }

        if ($payment_on_hold_search == '') {
            $field3 = "id";
            $operator3 = ">=";
            $value3 = 0;
        } else {
            $field3 = "payment_on_hold";
            $operator3 = "=";
            $value3 = $payment_on_hold_search;
        }

        if ($is_archived_search == '') {
            $field4 = "id";
            $operator4 = ">=";
            $value4 = 0;
        } else {
            $field4 = "is_archived";
            $operator4 = "=";
            $value4 = $is_archived_search;
        }

        if ($branch_id_search == '') {
            $field5 = "id";
            $operator5 = ">=";
            $value5 = 0;
        } else {
            $field5 = "employee_branch_id ";
            $operator5 = "=";
            $value5 = $branch_id_search;
        }
        if ($department_id_search == '') {
            $field6 = "id";
            $operator6 = ">=";
            $value6 = 0;
        } else {
            $field6 = "employee_department_id";
            $operator6 = "=";
            $value6 = $department_id_search;
        }
        if ($job_id_search == '') {
            $field7 = "id";
            $operator7 = ">=";
            $value7 = 0;
        } else {
            $field7 = "employee_job_id";
            $operator7 = "=";
            $value7 = $job_id_search;
        }
        if ($employment_status_search == '') {
            $field8 = "id";
            $operator8 = ">=";
            $value8 = 0;
        } else {
            $field8 = "employee_status";
            $operator8 = "=";
            $value8 = $employment_status_search;
        }
        if ($payment_method_search == '') {
            $field9 = "id";
            $operator9 = ">=";
            $value9 = 0;
        } else {
            $field9 = "payment_method";
            $operator9 = "=";
            $value9 = $payment_method_search;
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
            [$field9, $operator9, $value9],
        ];
        $where = collect($where)->unique()->values()->toArray();

        $company_id = Auth::user()->company_id;

        $adminPanelSetting = AdminPanelSetting::where('company_id', $company_id)->first();
        $systemData = [
            'system_name' => $adminPanelSetting->company_name ?? '',
            'photo'       => $adminPanelSetting->image ?? '',
            'address'     => $adminPanelSetting->address ?? '',
            'phone'       => $adminPanelSetting->phone ?? '',
            'email'       => $adminPanelSetting->email ?? '',
        ];

        $financeMonthlyCalendar = FinanceMonthlyCalendar::with('financeCalendar')
            ->where('company_id', $company_id)
            ->where('id', $finance_monthly_calendar_id)
            ->first();

        $mainSalaryEmployees = MainSalaryEmployee::with([
            'employee',
            'financeMonthlyCalendar',
            'addedBy',
            'updatedBy',
            'branch',
            'department',
            'job'
        ])
            ->where('company_id', $company_id)
            ->where('finance_monthly_calendar_id', $finance_monthly_calendar_id)
            ->where($where)
            ->orderBy('id', 'asc')
            ->get();

        $total_salary_sum = $mainSalaryEmployees->sum('employee_salary');
        $total_benefits_sum = $mainSalaryEmployees->sum('total_benefits');
        $total_deductions_sum = $mainSalaryEmployees->sum('total_deductions');
        $total_net_salary_sum = $mainSalaryEmployees->sum('employee_net_salary');

        $searchFilters = [];
        if (!empty($employee_id_search)) {
            $emp = Employee::find($employee_id_search);
            if ($emp) $searchFilters['اسم الموظف'] = $emp->name;
        }
        if (!empty($branch_id_search)) {
            $br = Branche::find($branch_id_search);
            if ($br) $searchFilters['الفرع'] = $br->name;
        }
        if (!empty($department_id_search)) {
            $dep = Department::find($department_id_search);
            if ($dep) $searchFilters['الإدارة'] = $dep->name;
        }
        if (!empty($job_id_search)) {
            $jb = JobsCategory::find($job_id_search);
            if ($jb) $searchFilters['الوظيفة'] = $jb->name;
        }
        if ($employment_status_search !== null && $employment_status_search !== '') {
            $searchFilters['حالة التوظيف'] = $employment_status_search == 1 ? 'نشط' : 'غير نشط';
        }
        if ($payment_method_search !== null && $payment_method_search !== '') {
            $searchFilters['طريقة الدفع'] = $payment_method_search == 1 ? 'نقداً' : ($payment_method_search == 2 ? 'تحويل بنكي' : 'شيك');
        }
        if ($is_disbursed_search !== null && $is_disbursed_search !== '') {
            $searchFilters['حالة الصرف'] = $is_disbursed_search == 1 ? 'تم الصرف' : 'لم يتم الصرف';
        }
        if ($payment_on_hold_search !== null && $payment_on_hold_search !== '') {
            $searchFilters['حالة الراتب'] = $payment_on_hold_search == 1 ? 'موقوف' : 'نشط';
        }
        if ($is_archived_search !== null && $is_archived_search !== '') {
            $searchFilters['حالة الأرشفة'] = $is_archived_search == 1 ? 'مؤرشف' : 'غير مؤرشف';
        }

        return view('admin.mainSalaryEmployee.print_search', [
            'mainSalaryEmployees' => $mainSalaryEmployees,
            'systemData'          => $systemData,
            'financeMonthlyCalendar' => $financeMonthlyCalendar,
            'total_salary_sum'    => $total_salary_sum,
            'total_benefits_sum'  => $total_benefits_sum,
            'total_deductions_sum' => $total_deductions_sum,
            'total_net_salary_sum' => $total_net_salary_sum,
            'searchFilters'       => $searchFilters,
        ]);
    }

    public function printSearchDetailed(Request $request)
    {
        $finance_monthly_calendar_id = $request->finance_monthly_calendar_id_search;
        $employee_id_search = $request->employee_id_search;
        $is_disbursed_search = $request->is_disbursed_search;
        $payment_on_hold_search = $request->payment_on_hold_search;
        $branch_id_search = $request->branch_id_search;
        $department_id_search = $request->department_id_search;
        $job_id_search = $request->job_id_search;
        $employment_status_search = $request->employment_status_search;
        $payment_method_search = $request->payment_method_search;
        $is_archived_search = $request->is_archived_search;

        $where = [];

        if ($employee_id_search == '') {
            $field1 = "id";
            $operator1 = ">=";
            $value1 = 0;
        } else {
            $field1 = "employee_id";
            $operator1 = "=";
            $value1 = $employee_id_search;
        }
        if ($is_disbursed_search == '') {
            $field2 = "id";
            $operator2 = ">=";
            $value2 = 0;
        } else {
            $field2 = "is_disbursed";
            $operator2 = "=";
            $value2 = $is_disbursed_search;
        }

        if ($payment_on_hold_search == '') {
            $field3 = "id";
            $operator3 = ">=";
            $value3 = 0;
        } else {
            $field3 = "payment_on_hold";
            $operator3 = "=";
            $value3 = $payment_on_hold_search;
        }

        if ($is_archived_search == '') {
            $field4 = "id";
            $operator4 = ">=";
            $value4 = 0;
        } else {
            $field4 = "is_archived";
            $operator4 = "=";
            $value4 = $is_archived_search;
        }

        if ($branch_id_search == '') {
            $field5 = "id";
            $operator5 = ">=";
            $value5 = 0;
        } else {
            $field5 = "employee_branch_id ";
            $operator5 = "=";
            $value5 = $branch_id_search;
        }
        if ($department_id_search == '') {
            $field6 = "id";
            $operator6 = ">=";
            $value6 = 0;
        } else {
            $field6 = "employee_department_id";
            $operator6 = "=";
            $value6 = $department_id_search;
        }
        if ($job_id_search == '') {
            $field7 = "id";
            $operator7 = ">=";
            $value7 = 0;
        } else {
            $field7 = "employee_job_id";
            $operator7 = "=";
            $value7 = $job_id_search;
        }
        if ($employment_status_search == '') {
            $field8 = "id";
            $operator8 = ">=";
            $value8 = 0;
        } else {
            $field8 = "employee_status";
            $operator8 = "=";
            $value8 = $employment_status_search;
        }
        if ($payment_method_search == '') {
            $field9 = "id";
            $operator9 = ">=";
            $value9 = 0;
        } else {
            $field9 = "payment_method";
            $operator9 = "=";
            $value9 = $payment_method_search;
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
            [$field9, $operator9, $value9],
        ];
        $where = collect($where)->unique()->values()->toArray();

        $company_id = Auth::user()->company_id;

        $adminPanelSetting = AdminPanelSetting::where('company_id', $company_id)->first();
        $systemData = [
            'system_name' => $adminPanelSetting->company_name ?? '',
            'photo'       => $adminPanelSetting->image ?? '',
            'address'     => $adminPanelSetting->address ?? '',
            'phone'       => $adminPanelSetting->phone ?? '',
            'email'       => $adminPanelSetting->email ?? '',
        ];

        $financeMonthlyCalendar = FinanceMonthlyCalendar::with('financeCalendar')
            ->where('company_id', $company_id)
            ->where('id', $finance_monthly_calendar_id)
            ->first();

        $mainSalaryEmployees = MainSalaryEmployee::with([
            'employee',
            'financeMonthlyCalendar',
            'addedBy',
            'updatedBy',
            'branch',
            'department',
            'job'
        ])
            ->where('company_id', $company_id)
            ->where('finance_monthly_calendar_id', $finance_monthly_calendar_id)
            ->where($where)
            ->orderBy('id', 'asc')
            ->get();

        $total_salary_sum = $mainSalaryEmployees->sum('employee_salary');
        $total_benefits_sum = $mainSalaryEmployees->sum('total_benefits');
        $total_deductions_sum = $mainSalaryEmployees->sum('total_deductions');
        $total_net_salary_sum = $mainSalaryEmployees->sum('employee_net_salary');

        $searchFilters = [];
        if (!empty($employee_id_search)) {
            $emp = Employee::find($employee_id_search);
            if ($emp) $searchFilters['اسم الموظف'] = $emp->name;
        }
        if (!empty($branch_id_search)) {
            $br = Branche::find($branch_id_search);
            if ($br) $searchFilters['الفرع'] = $br->name;
        }
        if (!empty($department_id_search)) {
            $dep = Department::find($department_id_search);
            if ($dep) $searchFilters['الإدارة'] = $dep->name;
        }
        if (!empty($job_id_search)) {
            $jb = JobsCategory::find($job_id_search);
            if ($jb) $searchFilters['الوظيفة'] = $jb->name;
        }
        if ($employment_status_search !== null && $employment_status_search !== '') {
            $searchFilters['حالة التوظيف'] = $employment_status_search == 1 ? 'نشط' : 'غير نشط';
        }
        if ($payment_method_search !== null && $payment_method_search !== '') {
            $searchFilters['طريقة الدفع'] = $payment_method_search == 1 ? 'نقداً' : ($payment_method_search == 2 ? 'تحويل بنكي' : 'شيك');
        }
        if ($is_disbursed_search !== null && $is_disbursed_search !== '') {
            $searchFilters['حالة الصرف'] = $is_disbursed_search == 1 ? 'تم الصرف' : 'لم يتم الصرف';
        }
        if ($payment_on_hold_search !== null && $payment_on_hold_search !== '') {
            $searchFilters['حالة الراتب'] = $payment_on_hold_search == 1 ? 'موقوف' : 'نشط';
        }
        if ($is_archived_search !== null && $is_archived_search !== '') {
            $searchFilters['حالة الأرشفة'] = $is_archived_search == 1 ? 'مؤرشف' : 'غير مؤرشف';
        }

        return view('admin.mainSalaryEmployee.print_search_detailed', [
            'mainSalaryEmployees' => $mainSalaryEmployees,
            'systemData'          => $systemData,
            'financeMonthlyCalendar' => $financeMonthlyCalendar,
            'total_salary_sum'    => $total_salary_sum,
            'total_benefits_sum'  => $total_benefits_sum,
            'total_deductions_sum' => $total_deductions_sum,
            'total_net_salary_sum' => $total_net_salary_sum,
            'searchFilters'       => $searchFilters,
        ]);
    }

    public function printDetails($id)
    {
        $company_id = Auth::user()->company_id;
        $adminPanelSetting = AdminPanelSetting::where('company_id', $company_id)->first();
        $systemData = [
            'system_name' => $adminPanelSetting->company_name ?? '',
            'photo'       => $adminPanelSetting->image ?? '',
            'address'     => $adminPanelSetting->address ?? '',
            'phone'       => $adminPanelSetting->phone ?? '',
            'email'       => $adminPanelSetting->email ?? '',
        ];

        $record = MainSalaryEmployee::with([
            'employee',
            'financeMonthlyCalendar.month',
            'branch',
            'department',
            'job'
        ])
            ->where('company_id', $company_id)
            ->where('id', $id)
            ->first();

        if (empty($record)) {
            return redirect()->back()->with('error', 'عفوا غير قادر للوصول الى البيانات');
        }

        return view('admin.mainSalaryEmployee.print_details', [
            'record' => $record,
            'systemData' => $systemData,
        ]);
    }

    public function togglePaymentStatus(Request $request)
    {
        if ($request->ajax()) {
            $company_id = Auth::user()->company_id;
            $id = $request->id;


            $record = MainSalaryEmployee::where('company_id', $company_id)->where('id', $id)->first();
            if (empty($record)) {
                return response()->json(['status' => 'false', 'message' => 'عفواً، السجل غير موجود']);
            }

            // Toggle payment_on_hold
            $record->payment_on_hold = $record->payment_on_hold == 1 ? 0 : 1;
            $record->updated_by = Auth::id();
            $record->save();

            $message = $record->payment_on_hold == 1 ? 'تم إيقاف صرف الراتب بنجاح' : 'تم تفعيل صرف الراتب بنجاح';
            return response()->json(['status' => 'true', 'message' => $message]);
        }
    }
    public function openArchiveModal(Request $request)
    {
        if ($request->ajax()) {
            $company_id = Auth::user()->company_id;
            $id = $request->id;
            $employee_id = $request->employee_id;
            $finance_monthly_calendar_id = $request->finance_monthly_calendar_id;

            $financeMonthlyCalendar = getColsWhereRow(FinanceMonthlyCalendar::class, ['*'], ['company_id' => $company_id, 'id' => $finance_monthly_calendar_id, 'status' => 1]);
            if (empty($financeMonthlyCalendar)) {
                return response()->json(['status' => 'false', 'message' => 'عفوا، الشهر المالي غير مفتوح أو غير موجود']);
            }

            $employee = getColsWhereRow(Employee::class, ['*'], ['company_id' => $company_id, 'id' => $employee_id, 'employment_status' => 1]);
            if (empty($employee)) {
                return response()->json(['status' => 'false', 'message' => 'عفوا، الموظف غير موجود أو غير نشط']);
            }

            $record = MainSalaryEmployee::where('company_id', $company_id)->where('id', $id)->first();
            if (empty($record)) {
                return response()->json(['status' => 'false', 'message' => 'عفواً، السجل غير موجود']);
            }
            $this->recalculate_main_salary($id);
            $record = $record->fresh();

            $html = view('admin.mainSalaryEmployee.openArchiveModal', [
                'record' => $record,
                'financeMonthlyCalendar' => $financeMonthlyCalendar,
                'employee' => $employee
            ])->render();
            return response()->json(['status' => 'true', 'html' => $html]);
        }
    }

    public function archive(Request $request)
    {
        if ($request->ajax()) {
            $company_id = Auth::user()->company_id;
            $id = $request->id;
            $disbursed_amount = (float)$request->input('disbursed_amount', 0.00);

            $record = MainSalaryEmployee::where('company_id', $company_id)->where('id', $id)->first();
            if (empty($record)) {
                return response()->json(['status' => 'false', 'message' => 'عفواً، السجل غير موجود']);
            }

            if ($record->is_archived == 1) {
                return response()->json(['status' => 'false', 'message' => 'عفواً، هذا السجل مؤرشف بالفعل']);
            }

            $financeMonthlyCalendar = getColsWhereRow(FinanceMonthlyCalendar::class, ['*'], ['company_id' => $company_id, 'id' => $record->finance_monthly_calendar_id, 'status' => 1]);
            if (empty($financeMonthlyCalendar)) {
                return response()->json(['status' => 'false', 'message' => 'عفوا، الشهر المالي مغلق أو غير موجود ولا يمكن أرشفة رواتب الموظفين فيه']);
            }

            try {
                return DB::transaction(function () use ($record, $disbursed_amount) {
                    $archiveData = [
                        'is_archived' => 1,
                        'archived_by' => Auth::id(),
                        'archived_at' => now(),
                    ];

                    $netSalary = (float)$record->employee_net_salary;
                    $absNetSalary = abs($netSalary);

                    if ($disbursed_amount > $absNetSalary) {
                        $disbursed_amount = $absNetSalary;
                    }
                    if ($disbursed_amount < 0) {
                        $disbursed_amount = 0.00;
                    }

                    $remaining = $absNetSalary - $disbursed_amount;
                    if ($netSalary >= 0) {
                        $rolloverForNextMonth = $remaining;
                    } else {
                        $rolloverForNextMonth = -$remaining;
                    }

                    $statusType = 3; // 3 = صافي
                    if ($netSalary > 0) {
                        $statusType = 1; // 1 = دائن
                    } elseif ($netSalary < 0) {
                        $statusType = 2; // 2 = مدين
                    }

                    $record->is_archived = 1;
                    $record->archived_by = Auth::id();
                    $record->archived_at = now();
                    $record->archive_status_type = $statusType;
                    $record->archive_settlement_amount = $disbursed_amount;
                    $record->employee_net_salary_after_close_for_roll_over = $rolloverForNextMonth;

                    if ($netSalary >= 0 && $disbursed_amount > 0) {
                        $record->is_disbursed = 1;
                    } else {
                        $record->is_disbursed = 0;
                    }

                    $record->save();

                    $record->mainSalaryEmployeeDeductions()->update($archiveData);
                    $record->mainSalaryEmployeeAbsences()->update($archiveData);
                    $record->mainSalaryEmployeeDeductionTypes()->update($archiveData);
                    $record->mainSalaryEmployeeAdditions()->update($archiveData);
                    $record->mainSalaryEmployeeLoans()->update($archiveData);
                    $record->mainSalaryEmployeeBonuses()->update($archiveData);
                    $record->mainSalaryEmployeeAllowances()->update($archiveData);
                    $record->mainSalaryEmployeePLoanInstallments()->update($archiveData);

                    return response()->json(['status' => 'true', 'message' => 'تم أرشفة وتثبيت الراتب للموظف بنجاح']);
                });
            } catch (\Exception $e) {
                return response()->json(['status' => 'false', 'message' => 'حدث خطأ أثناء الأرشفة: ' . $e->getMessage()]);
            }
        }
    }
    public function recalculateMainSalary(Request $request)
    {
        if ($request->ajax()) {
            $company_id = Auth::user()->company_id;
            $id = $request->id;

            $record = MainSalaryEmployee::with('employee')->where('company_id', $company_id)->where('id', $id)->first();
            if (empty($record)) {
                return response()->json(['status' => 'false', 'message' => 'عفواً، السجل غير موجود']);
            }

            try {
                return DB::transaction(function () use ($id, $record) {

                    if ($record->is_archived == 1) {
                        return response()->json([
                            'status' => 'true',
                            'message' => 'الراتب مؤرشف بالفعل',
                            'data' => $record
                        ]);
                    } else {
                        $this->recalculate_main_salary($id);
                        $record = MainSalaryEmployee::with('employee')->findOrFail($id);
                        return response()->json([
                            'status' => 'true',
                            'message' => 'تم إعادة حساب الراتب للموظف بنجاح',
                            'data' => $record
                        ]);
                    }
                });
            } catch (\Exception $e) {
                return response()->json(['status' => 'false', 'message' => 'حدث خطأ أثناء إعادة حساب الراتب للموظف']);
            }
        }
    }
}
