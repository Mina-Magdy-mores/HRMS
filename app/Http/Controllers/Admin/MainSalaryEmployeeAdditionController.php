<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MainSalaryEmployeeAddition;
use Illuminate\Http\Request;
use App\Models\AdminPanelSetting;
use App\Models\Employee;
use App\Models\FinanceMonthlyCalendar;
use App\Models\MainSalaryEmployee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MainSalaryEmployeeAdditionController extends Controller
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
        return view('admin.mainSalaryRecordAddition.index', ['financeMonthlyCalendars' => $financeMonthlyCalendars]);
    }

    public function ajaxCheck(Request $request)
    {
        if ($request->ajax()) {
            $company_id = Auth::user()->company_id;
            $checkIfExistsCounter = get_count_where(MainSalaryEmployeeAddition::class, ['company_id' => $company_id, 'finance_monthly_calendar_id' => $request->finance_monthly_calendar_id, 'employee_id' => $request->employee_id]);
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
                            'days_amount'             => $request->days_amount,
                            'total'                   => $request->total,
                            'company_id'              => $company_id,
                            'is_auto'                 => 0,
                            'status'                  => 1,
                            'added_by'                => Auth::user()->id,
                            'notes'                   => $request->notes,
                        ];
                        $insertData = insert(MainSalaryEmployeeAddition::class, $dataToInsert);
                        if ($insertData) {
                            return response()->json(['status' => 'true', 'message' => 'تم اضافة الايام بنجاح']);
                        } else {
                            return response()->json(['status' => 'false', 'message' => 'عفوا لم يتم اضافة الايام']);
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
            return redirect()->route('admin.main-salary-employee-additions.index')->with('error', 'عفوا غير قادر للوصول الى بيانات الشهر');
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
        $mainSalaryEmployeeAdditions = MainSalaryEmployeeAddition::with([
            'employee',
            'financeMonthlyCalendar',
            'addedBy',
            'updatedBy',
            'archivedBy',
        ])
            ->where('company_id', $company_id)
            ->where('finance_monthly_calendar_id', $id)
            ->orderBy('id', 'desc')
            ->paginate(PAGEINATION_COUNTER);
        $mainSalaryEmployeeAdditions2 = MainSalaryEmployeeAddition::where('company_id', $company_id)
            ->where('finance_monthly_calendar_id', $id)
            ->orderBy('id', 'desc')
            ->get();
        return view('admin.mainSalaryRecordAddition.show', [
            'financeMonthlyCalendar'          => $financeMonthlyCalendar,
            'mainSalaryEmployeeAdditions'     => $mainSalaryEmployeeAdditions,
            'mainSalaryEmployeeAdditions2'    => $mainSalaryEmployeeAdditions2,
            'employees'                       => $employees,
            'employees_has_opened_monthly_record' => $employees_has_opened_monthly_record,
        ]);
    }

    public function ajaxSearch(Request $request)
    {
        if ($request->ajax()) {
            $employee_id_search = $request->employee_id_search;
            $is_archived_search = $request->is_archived_search;
            if (empty($employee_id_search)) {
                $field1 = "id";
                $operator1 = ">=";
                $value1 = 0;
            } else {
                $field1 = "employee_id";
                $operator1 = "=";
                $value1 = $employee_id_search;
            }

            if (empty($is_archived_search)) {
                $field2 = "id";
                $operator2 = ">=";
                $value2 = 0;
            } else {
                $field2 = "is_archived";
                $operator2 = "=";
                $value2 = $is_archived_search;
            }

            $where = [
                [$field1, $operator1, $value1],
                [$field2, $operator2, $value2],
            ];
            $company_id = Auth::user()->company_id;
            $mainSalaryEmployeeAdditions = MainSalaryEmployeeAddition::with([
                'employee',
                'financeMonthlyCalendar',
                'addedBy',
                'updatedBy'
            ])
                ->where('company_id', $company_id)
                ->where('finance_monthly_calendar_id', $request->finance_monthly_calendar_id)
                ->where($where)
                ->orderBy('id', 'desc')
                ->paginate(PAGEINATION_COUNTER);
            return view('admin.mainSalaryRecordAddition.ajaxSearch', ['mainSalaryEmployeeAdditions' => $mainSalaryEmployeeAdditions]);
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
            $mainSalaryEmployeeAdditions = getColsWhereRow(MainSalaryEmployeeAddition::class, ['id'], ['company_id' => $company_id, 'id' => $request->id, 'is_archived' => 0]);
            if (empty($mainSalaryEmployeeAdditions)) {
                return response()->json(['status' => 'false', 'message' => 'عفوا غير قادر للوصول الى بيانات الاضافة']);
            }
            $destroy = destroy($mainSalaryEmployeeAdditions);
            if ($destroy) {
                return response()->json(['status' => 'true', 'message' => 'تم حذف الاضافة بنجاح']);
            } else {
                return response()->json(['status' => 'false', 'message' => 'عفوا لم يتم حذف الاضافة']);
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
        $mainSalaryEmployeeAdditions = MainSalaryEmployeeAddition::with([
            'employee:name,id,salary,payment_per_day',
        ])
            ->where('company_id', $company_id)
            ->where('id', $request->id)
            ->first();
        if (empty($mainSalaryEmployeeAdditions)) {
            return response()->json(['status' => 'false', 'message' => 'عفوا غير قادر للوصول الى بيانات الاضافة']);
        }
        return response()->json(['status' => 'true', 'mainSalaryEmployeeAdditions' => $mainSalaryEmployeeAdditions]);
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
            $mainSalaryEmployeeAddition = getColsWhereRow(MainSalaryEmployeeAddition::class, ['*'], ['company_id' => $company_id, 'id' => $request->id]);
            if (empty($mainSalaryEmployeeAddition)) {
                return response()->json(['status' => 'false', 'message' => 'عفوا غير قادر للوصول الى بيانات الاضافة']);
            }

            try {
                return DB::transaction(function () use ($request, $mainSalaryEmployeeAddition) {
                    $dataToUpdate = [
                        'days_amount' => $request->days_amount,
                        'total'       => $request->total,
                        'notes'       => $request->notes,
                        'updated_by'  => Auth::user()->id,
                    ];
                    $updateData = update($mainSalaryEmployeeAddition, $dataToUpdate);
                    if ($updateData) {
                        return response()->json(['status' => 'true', 'message' => 'تم تعديل الاضافة بنجاح']);
                    } else {
                        return response()->json(['status' => 'false', 'message' => 'عفوا لم يتم تعديل الاضافة']);
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
        $employee_id_search = $request->employee_id_search;
        $is_archived_search = $request->is_archived_search;
        if (empty($employee_id_search)) {
            $field1 = "id";
            $operator1 = ">=";
            $value1 = 0;
        } else {
            $field1 = "employee_id";
            $operator1 = "=";
            $value1 = $employee_id_search;
        }

        if (empty($is_archived_search)) {
            $field2 = "id";
            $operator2 = ">=";
            $value2 = 0;
        } else {
            $field2 = "is_archived";
            $operator2 = "=";
            $value2 = $is_archived_search;
        }

        $where = [
            [$field1, $operator1, $value1],
            [$field2, $operator2, $value2],
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

        $mainSalaryEmployeeAdditions = MainSalaryEmployeeAddition::with([
            'employee',
            'financeMonthlyCalendar',
            'addedBy',
            'updatedBy',
            'archivedBy',
        ])
            ->where('company_id', $company_id)
            ->where('finance_monthly_calendar_id', $finance_monthly_calendar_id)
            ->where($where)
            ->orderBy('employee_id', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $total_sum      = $mainSalaryEmployeeAdditions->sum('total');
        $total_days_sum = $mainSalaryEmployeeAdditions->sum('days_amount');

        return view('admin.mainSalaryRecordAddition.print_search', [
            'mainSalaryEmployeeAdditions' => $mainSalaryEmployeeAdditions,
            'systemData'                  => $systemData,
            'financeMonthlyCalendar'      => $financeMonthlyCalendar,
            'total_sum'                   => $total_sum,
            'total_days_sum'              => $total_days_sum,
        ]);
    }
}
