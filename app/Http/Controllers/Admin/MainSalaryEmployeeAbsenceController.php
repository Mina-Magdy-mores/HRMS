<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MainSalaryEmployeeAbsence;
use Illuminate\Http\Request;
use App\Models\AdminPanelSetting;
use App\Models\Employee;
use App\Models\FinanceMonthlyCalendar;
use App\Models\MainSalaryEmployee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class MainSalaryEmployeeAbsenceController extends Controller
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
        return view('admin.mainSalaryRecordAbsence.index', ['financeMonthlyCalendars' => $financeMonthlyCalendars]);
    }
    public function ajaxCheck(Request $request)
    {
        if ($request->ajax()) {
            $company_id = Auth::user()->company_id;
            $checkIfExistsCounter = get_count_where(MainSalaryEmployeeAbsence::class, ['company_id' => $company_id, 'finance_monthly_calendar_id' => $request->finance_monthly_calendar_id, 'employee_id' => $request->employee_id]);
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
                            'employee_id' => $request->employee_id,
                            'finance_monthly_calendar_id' => $request->finance_monthly_calendar_id,
                            'days_amount' => $request->days_amount,
                            'total' => $request->total,
                            'company_id' => $company_id,
                            'is_auto' => 0,
                            'status' => 1,
                            'added_by' => Auth::user()->id,
                            'notes' => $request->notes,
                        ];
                        $insertData = insert(MainSalaryEmployeeAbsence::class, $dataToInsert);
                        if ($insertData) {
                            return response()->json(['status' => 'true', 'message' => 'تم اضافة الغياب بنجاح']);
                        } else {
                            return response()->json(['status' => 'false', 'message' => 'عفوا لم يتم اضافة الغياب']);
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
            return redirect()->route('admin.main-salary-employee-absences.index')->with('error', 'عفوا غير قادر للوصول الى بيانات الشهر');
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
        $mainSalaryEmployeeAbsences = MainSalaryEmployeeAbsence::with([
            'employee',
            'financeMonthlyCalendar',
            'addedBy',
            'updatedBy',
            'approvedBy',
        ])
            ->where('company_id', $company_id)
            ->where('finance_monthly_calendar_id', $id)
            ->orderBy('id', 'desc')
            ->paginate(PAGEINATION_COUNTER);
        $mainSalaryEmployeeAbsences2 = MainSalaryEmployeeAbsence::where('company_id', $company_id)
            ->where('finance_monthly_calendar_id', $id)
            ->orderBy('id', 'desc')
            ->get();
        return view('admin.mainSalaryRecordAbsence.show', [
            'financeMonthlyCalendar' => $financeMonthlyCalendar,
            'mainSalaryEmployeeAbsences' => $mainSalaryEmployeeAbsences,
            'mainSalaryEmployeeAbsences2' => $mainSalaryEmployeeAbsences2,
            'employees' => $employees,
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
            $mainSalaryEmployeeAbsences = MainSalaryEmployeeAbsence::with([
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
            return view('admin.mainSalaryRecordAbsence.ajaxSearch', ['mainSalaryEmployeeAbsences' => $mainSalaryEmployeeAbsences]);
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
            $mainSalaryEmployeeAbsences = getColsWhereRow(MainSalaryEmployeeAbsence::class, ['id'], ['company_id' => $company_id, 'id' => $request->id, 'is_archived' => 0]);
            if (empty($mainSalaryEmployeeAbsences)) {
                return response()->json(['status' => 'false', 'message' => 'عفوا غير قادر للوصول الى بيانات الجزاء']);
            }
            $destroy = destroy($mainSalaryEmployeeAbsences);
            if ($destroy) {
                return response()->json(['status' => 'true', 'message' => 'تم حذف الجزاء بنجاح']);
            } else {
                return response()->json(['status' => 'false', 'message' => 'عفوا لم يتم حذف الجزاء']);
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
        $mainSalaryEmployeeAbsences = MainSalaryEmployeeAbsence::with([
            'employee:name,id,salary,payment_per_day',
        ])
            ->where('company_id', $company_id)
            ->where('id', $request->id)
            ->first();
        if (empty($mainSalaryEmployeeAbsences)) {
            return response()->json(['status' => 'false', 'message' => 'عفوا غير قادر للوصول الى بيانات الجزاء']);
        }
        return response()->json(['status' => 'true', 'mainSalaryEmployeeAbsences' => $mainSalaryEmployeeAbsences]);
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
            $mainSalaryEmployeeAbsence = getColsWhereRow(MainSalaryEmployeeAbsence::class, ['*'], ['company_id' => $company_id, 'id' => $request->id]);
            if (empty($mainSalaryEmployeeAbsence)) {
                return response()->json(['status' => 'false', 'message' => 'عفوا غير قادر للوصول الى بيانات الجزاء']);
            }

            try {
                return DB::transaction(function () use ($request, $mainSalaryEmployeeAbsence) {
                    $dataToUpdate = [
                        'days_amount' => $request->days_amount,
                        'total' => $request->total,
                        'notes' => $request->notes,
                        'updated_by' => Auth::user()->id,
                    ];
                    $updateData = update($mainSalaryEmployeeAbsence, $dataToUpdate);
                    if ($updateData) {
                        return response()->json(['status' => 'true', 'message' => 'تم تعديل الجزاء بنجاح']);
                    } else {
                        return response()->json(['status' => 'false', 'message' => 'عفوا لم يتم تعديل الجزاء']);
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

        $mainSalaryEmployeeAbsences = MainSalaryEmployeeAbsence::with([
            'employee',
            'financeMonthlyCalendar',
            'addedBy',
            'updatedBy',
            'approvedBy',
        ])
            ->where('company_id', $company_id)
            ->where('finance_monthly_calendar_id', $finance_monthly_calendar_id)
            ->where($where)
            ->orderBy('employee_id', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $total_sum         = $mainSalaryEmployeeAbsences->sum('total');
        $total_days_sum    = $mainSalaryEmployeeAbsences->sum('days_amount');

        return view('admin.mainSalaryRecordAbsence.print_search', [
            'mainSalaryEmployeeAbsences' => $mainSalaryEmployeeAbsences,
            'systemData'                   => $systemData,
            'financeMonthlyCalendar'        => $financeMonthlyCalendar,
            'total_sum'                    => $total_sum,
            'total_days_sum'               => $total_days_sum,
        ]);
    }
}
