<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminPanelSetting;
use App\Models\AllowanceType;
use App\Models\Employee;
use App\Models\FinanceMonthlyCalendar;
use App\Models\MainSalaryEmployee;
use App\Models\MainSalaryEmployeeAllowance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MainSalaryEmployeeAllowanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $company_id = Auth::user()->company_id;
        $financeMonthlyCalendars = get_cols_where_order2_with(FinanceMonthlyCalendar::class, ['financeCalendar'], ['*'], ['company_id' => $company_id], 'finance_yr', 'desc', 'id', 'asc', 12);
        foreach ($financeMonthlyCalendars as $calendar) {
            $calendar->total_opened_months = get_count_where(FinanceMonthlyCalendar::class, ['company_id' => $company_id, 'status' => '1']);
            $calendar->total_prev_months_waiting_to_open = FinanceMonthlyCalendar::where(['company_id' => $company_id, 'status' => '0', 'finance_yr' => $calendar->finance_yr])->where('month_id', '<', $calendar->month_id)->count();
        }
        return view('admin.mainSalaryRecordAllowance.index', ['financeMonthlyCalendars' => $financeMonthlyCalendars]);
    }

    public function ajaxCheck(Request $request)
    {
        if ($request->ajax()) {
            $company_id = Auth::user()->company_id;
            $checkIfExistsCounter = get_count_where(MainSalaryEmployeeAllowance::class, ['company_id' => $company_id, 'finance_monthly_calendar_id' => $request->finance_monthly_calendar_id, 'employee_id' => $request->employee_id, 'allowance_type_id' => $request->allowance_type_id]);
            if ($checkIfExistsCounter > 0) {
                return response()->json(['status' => 'true', 'count' => $checkIfExistsCounter]);
            }
            return response()->json(['status' => 'false', 'count' => 0]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->ajax()) {
            $company_id = Auth::user()->company_id;

            $financeMonthlyCalendars = getColsWhereRow(FinanceMonthlyCalendar::class, ['id'], ['company_id' => $company_id, 'id' => $request->finance_monthly_calendar_id, 'status' => 1]);
            $mainSalaryEmployee = getColsWhereRow(MainSalaryEmployee::class, ['id'], ['company_id' => $company_id, 'employee_id' => $request->employee_id, 'finance_monthly_calendar_id' => $request->finance_monthly_calendar_id, 'is_archived' => 0]);
            if (!empty($financeMonthlyCalendars) && !empty($mainSalaryEmployee)) {
                try {
                    return DB::transaction(function () use ($request, $company_id, $mainSalaryEmployee) {
                        $dataToInsert = [
                            'main_salary_employee_id' => $mainSalaryEmployee['id'],
                            'employee_id'             => $request->employee_id,
                            'finance_monthly_calendar_id' => $request->finance_monthly_calendar_id,
                            'allowance_type_id'       => $request->allowance_type_id,
                            'amount'                  => $request->amount,
                            'company_id'              => $company_id,
                            'is_auto'                 => 0,
                            'status'                  => 1,
                            'added_by'                => Auth::user()->id,
                            'notes'                   => $request->notes,
                        ];
                        $insertData = insert(MainSalaryEmployeeAllowance::class, $dataToInsert);
                        if ($insertData) {
                            return response()->json(['status' => 'true', 'message' => 'تم اضافة البدل بنجاح']);
                        } else {
                            return response()->json(['status' => 'false', 'message' => 'عفوا لم يتم اضافة البدل']);
                        }
                    });
                } catch (\Exception $e) {
                    return response()->json(['status' => 'false', 'message' => 'عفوا حدث خطأ ' . $e->getMessage()]);
                }
            } else {
                return response()->json(['status' => 'false', 'message' => 'عفوا، لا توجد بيانات راتب مسجلة لهذا الموظف في هذا الشهر المالي.']);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $company_id = Auth::user()->company_id;
        $financeMonthlyCalendar = FinanceMonthlyCalendar::with('month')
            ->where('company_id', $company_id)
            ->where('id', $id)
            ->first();

        if (empty($financeMonthlyCalendar)) {
            return redirect()->route('admin.main-salary-employee-allowances.index')->with('error', 'عفوا غير قادر للوصول الى بيانات الشهر');
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

        $allowanceTypes = AllowanceType::where('company_id', $company_id)->orderBy('id', 'asc')->get();

        $mainSalaryEmployeeAllowances = MainSalaryEmployeeAllowance::with([
            'employee',
            'financeMonthlyCalendar',
            'allowanceType',
            'addedBy',
            'updatedBy',
        ])
            ->where('company_id', $company_id)
            ->where('finance_monthly_calendar_id', $id)
            ->orderBy('id', 'desc')
            ->paginate(PAGEINATION_COUNTER);

        $mainSalaryEmployeeAllowances2 = MainSalaryEmployeeAllowance::where('company_id', $company_id)
            ->where('finance_monthly_calendar_id', $id)
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.mainSalaryRecordAllowance.show', [
            'financeMonthlyCalendar'          => $financeMonthlyCalendar,
            'mainSalaryEmployeeAllowances'    => $mainSalaryEmployeeAllowances,
            'mainSalaryEmployeeAllowances2'   => $mainSalaryEmployeeAllowances2,
            'employees'                       => $employees,
            'employees_has_opened_monthly_record' => $employees_has_opened_monthly_record,
            'allowanceTypes'                  => $allowanceTypes,
        ]);
    }

    public function ajaxSearch(Request $request)
    {
        if ($request->ajax()) {
            $employee_id_search    = $request->employee_id_search;
            $allowance_type_search = $request->allowance_type_search;
            $is_archived_search    = $request->is_archived_search;

            if (empty($employee_id_search)) {
                $field1    = 'id';
                $operator1 = '>=';
                $value1    = 0;
            } else {
                $field1    = 'employee_id';
                $operator1 = '=';
                $value1    = $employee_id_search;
            }

            if (empty($allowance_type_search)) {
                $field2    = 'id';
                $operator2 = '>=';
                $value2    = 0;
            } else {
                $field2    = 'allowance_type_id';
                $operator2 = '=';
                $value2    = $allowance_type_search;
            }

            if (empty($is_archived_search)) {
                $field3    = 'id';
                $operator3 = '>=';
                $value3    = 0;
            } else {
                $field3    = 'is_archived';
                $operator3 = '=';
                $value3    = $is_archived_search;
            }

            $where = [
                [$field1, $operator1, $value1],
                [$field2, $operator2, $value2],
                [$field3, $operator3, $value3],
            ];

            $company_id = Auth::user()->company_id;
            $mainSalaryEmployeeAllowances = MainSalaryEmployeeAllowance::with([
                'employee',
                'financeMonthlyCalendar',
                'allowanceType',
                'addedBy',
                'updatedBy',
            ])
                ->where('company_id', $company_id)
                ->where('finance_monthly_calendar_id', $request->finance_monthly_calendar_id)
                ->where($where)
                ->orderBy('id', 'desc')
                ->paginate(PAGEINATION_COUNTER);

            return view('admin.mainSalaryRecordAllowance.ajaxSearch', ['mainSalaryEmployeeAllowances' => $mainSalaryEmployeeAllowances]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        if ($request->ajax()) {
            $company_id = Auth::user()->company_id;
            $financeMonthlyCalendars = getColsWhereRow(FinanceMonthlyCalendar::class, ['id'], ['company_id' => $company_id, 'id' => $request->finance_monthly_calendar_id, 'status' => 1]);
            if (empty($financeMonthlyCalendars)) {
                return response()->json(['status' => 'false', 'message' => 'عفوا غير قادر للوصول الى بيانات الشهر']);
            }
            $mainSalaryEmployee = getColsWhereRow(MainSalaryEmployee::class, ['id'], ['company_id' => $company_id, 'id' => $request->main_salary_employee_id, 'is_archived' => 0]);
            if (empty($mainSalaryEmployee)) {
                return response()->json(['status' => 'false', 'message' => 'عفوا غير قادر للوصول الى بيانات الموظف']);
            }
            $mainSalaryEmployeeAllowance = getColsWhereRow(MainSalaryEmployeeAllowance::class, ['id'], ['company_id' => $company_id, 'id' => $request->id, 'is_archived' => 0]);
            if (empty($mainSalaryEmployeeAllowance)) {
                return response()->json(['status' => 'false', 'message' => 'عفوا غير قادر للوصول الى بيانات البدل']);
            }
            $destroy = destroy($mainSalaryEmployeeAllowance);
            if ($destroy) {
                return response()->json(['status' => 'true', 'message' => 'تم حذف البدل بنجاح']);
            } else {
                return response()->json(['status' => 'false', 'message' => 'عفوا لم يتم حذف البدل']);
            }
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        $company_id = Auth::user()->company_id;
        $financeMonthlyCalendars = getColsWhereRow(FinanceMonthlyCalendar::class, ['id'], ['company_id' => $company_id, 'id' => $request->finance_monthly_calendar_id, 'status' => 1]);
        if (empty($financeMonthlyCalendars)) {
            return response()->json(['status' => 'false', 'message' => 'عفوا غير قادر للوصول الى بيانات الشهر']);
        }
        $mainSalaryEmployee = getColsWhereRow(MainSalaryEmployee::class, ['id'], ['company_id' => $company_id, 'id' => $request->main_salary_employee_id, 'is_archived' => 0]);
        if (empty($mainSalaryEmployee)) {
            return response()->json(['status' => 'false', 'message' => 'عفوا غير قادر للوصول الى بيانات الموظف']);
        }
        $mainSalaryEmployeeAllowance = MainSalaryEmployeeAllowance::with([
            'employee:name,id,salary,payment_per_day',
            'allowanceType:id,name',
        ])
            ->where('company_id', $company_id)
            ->where('id', $request->id)
            ->first();
        if (empty($mainSalaryEmployeeAllowance)) {
            return response()->json(['status' => 'false', 'message' => 'عفوا غير قادر للوصول الى بيانات البدل']);
        }
        return response()->json(['status' => 'true', 'mainSalaryEmployeeAllowance' => $mainSalaryEmployeeAllowance]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        if ($request->ajax()) {
            $company_id = Auth::user()->company_id;
            $financeMonthlyCalendars = getColsWhereRow(FinanceMonthlyCalendar::class, ['id'], ['company_id' => $company_id, 'id' => $request->finance_monthly_calendar_id, 'status' => 1]);
            if (empty($financeMonthlyCalendars)) {
                return response()->json(['status' => 'false', 'message' => 'عفوا غير قادر للوصول الى بيانات الشهر']);
            }
            $mainSalaryEmployee = getColsWhereRow(MainSalaryEmployee::class, ['id'], ['company_id' => $company_id, 'id' => $request->main_salary_employee_id, 'is_archived' => 0]);
            if (empty($mainSalaryEmployee)) {
                return response()->json(['status' => 'false', 'message' => 'عفوا غير قادر للوصول الى بيانات الموظف']);
            }
            $mainSalaryEmployeeAllowance = getColsWhereRow(MainSalaryEmployeeAllowance::class, ['*'], ['company_id' => $company_id, 'id' => $request->id]);
            if (empty($mainSalaryEmployeeAllowance)) {
                return response()->json(['status' => 'false', 'message' => 'عفوا غير قادر للوصول الى بيانات البدل']);
            }

            try {
                return DB::transaction(function () use ($request, $mainSalaryEmployeeAllowance) {
                    $dataToUpdate = [
                        'allowance_type_id' => $request->allowance_type_id,
                        'amount'            => $request->amount,
                        'notes'             => $request->notes,
                        'updated_by'        => Auth::user()->id,
                    ];
                    $updateData = update($mainSalaryEmployeeAllowance, $dataToUpdate);
                    if ($updateData) {
                        return response()->json(['status' => 'true', 'message' => 'تم تعديل البدل بنجاح']);
                    } else {
                        return response()->json(['status' => 'false', 'message' => 'عفوا لم يتم تعديل البدل']);
                    }
                });
            } catch (\Exception $e) {
                return response()->json(['status' => 'false', 'message' => 'عفوا حدث خطأ ' . $e->getMessage()]);
            }
        }
    }

    public function printSearch(Request $request)
    {
        $finance_monthly_calendar_id = $request->finance_monthly_calendar_id_search;
        $employee_id_search          = $request->employee_id_search;
        $allowance_type_search       = $request->allowance_type_search;
        $is_archived_search          = $request->is_archived_search;

        if (empty($employee_id_search)) {
            $field1    = 'id';
            $operator1 = '>=';
            $value1    = 0;
        } else {
            $field1    = 'employee_id';
            $operator1 = '=';
            $value1    = $employee_id_search;
        }

        if (empty($allowance_type_search)) {
            $field2    = 'id';
            $operator2 = '>=';
            $value2    = 0;
        } else {
            $field2    = 'allowance_type_id';
            $operator2 = '=';
            $value2    = $allowance_type_search;
        }

        if (empty($is_archived_search)) {
            $field3    = 'id';
            $operator3 = '>=';
            $value3    = 0;
        } else {
            $field3    = 'is_archived';
            $operator3 = '=';
            $value3    = $is_archived_search;
        }

        $where = [
            [$field1, $operator1, $value1],
            [$field2, $operator2, $value2],
            [$field3, $operator3, $value3],
        ];

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

        $mainSalaryEmployeeAllowances = MainSalaryEmployeeAllowance::with([
            'employee',
            'financeMonthlyCalendar',
            'allowanceType',
            'addedBy',
            'updatedBy',
        ])
            ->where('company_id', $company_id)
            ->where('finance_monthly_calendar_id', $finance_monthly_calendar_id)
            ->where($where)
            ->orderBy('employee_id', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $total_amount_sum = $mainSalaryEmployeeAllowances->sum('amount');

        return view('admin.mainSalaryRecordAllowance.print_search', [
            'mainSalaryEmployeeAllowances' => $mainSalaryEmployeeAllowances,
            'systemData'                   => $systemData,
            'financeMonthlyCalendar'       => $financeMonthlyCalendar,
            'total_amount_sum'             => $total_amount_sum,
        ]);
    }
}
