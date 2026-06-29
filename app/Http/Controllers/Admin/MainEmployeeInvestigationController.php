<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminPanelSetting;
use App\Models\Employee;
use App\Models\FinanceMonthlyCalendar;
use App\Models\MainSalaryEmployee;
use App\Models\MainEmployeeInvestigation;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MainEmployeeInvestigationController extends Controller
{
    use GeneralTrait;

    /**
     * Display a listing of the resource (list of finance months).
     */
    public function index()
    {
        $company_id = Auth::user()->company_id;
        $financeMonthlyCalendars = get_cols_where_order2_with(
            FinanceMonthlyCalendar::class,
            ['financeCalendar'],
            ['*'],
            ['company_id' => $company_id],
            'finance_yr',
            'desc',
            'id',
            'asc',
            12
        );
        foreach ($financeMonthlyCalendars as $calendar) {
            $calendar->total_opened_months = get_count_where(
                FinanceMonthlyCalendar::class,
                ['company_id' => $company_id, 'status' => '1']
            );
            $calendar->total_prev_months_waiting_to_open = FinanceMonthlyCalendar::where([
                'company_id' => $company_id,
                'status'     => '0',
                'finance_yr' => $calendar->finance_yr,
            ])->where('month_id', '<', $calendar->month_id)->count();
        }
        return view('admin.mainSalaryRecordInvestigation.index', [
            'financeMonthlyCalendars' => $financeMonthlyCalendars,
        ]);
    }

    /**
     * Show the details page for a specific finance month.
     */
    public function show($id)
    {
        $company_id = Auth::user()->company_id;

        $financeMonthlyCalendar = FinanceMonthlyCalendar::with('month')
            ->where('company_id', $company_id)
            ->where('id', $id)
            ->first();

        if (empty($financeMonthlyCalendar)) {
            return redirect()
                ->route('admin.main-salary-employee-investigations.index')
                ->with('error', 'عفوا غير قادر للوصول الى بيانات الشهر');
        }

        $employees = Employee::select(['id', 'name', 'employee_code', 'salary', 'payment_per_day'])
            ->where('company_id', $company_id)
            ->orderBy('id', 'asc')
            ->get();

        $employees_has_opened_monthly_record = Employee::select([
            'id', 'name', 'employee_code', 'salary', 'payment_per_day',
        ])
            ->where('company_id', $company_id)
            ->whereHas('mainSalaryEmployee', function ($query) use ($id) {
                $query->where('employee_status', 1)
                    ->where('finance_monthly_calendar_id', $id)
                    ->where('is_archived', 0);
            })
            ->orderBy('id', 'asc')
            ->get();

        $investigations = MainEmployeeInvestigation::with([
            'employee',
            'financeMonthlyCalendar',
            'addedBy',
            'updatedBy',
        ])
            ->where('company_id', $company_id)
            ->where('finance_monthly_calendar_id', $id)
            ->orderBy('id', 'desc')
            ->paginate(PAGEINATION_COUNTER);

        $investigations2 = MainEmployeeInvestigation::where('company_id', $company_id)
            ->where('finance_monthly_calendar_id', $id)
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.mainSalaryRecordInvestigation.show', [
            'financeMonthlyCalendar'              => $financeMonthlyCalendar,
            'investigations'                      => $investigations,
            'investigations2'                     => $investigations2,
            'employees'                           => $employees,
            'employees_has_opened_monthly_record' => $employees_has_opened_monthly_record,
        ]);
    }

    /**
     * AJAX check: returns count of previous investigations for an employee.
     */
    public function ajaxCheck(Request $request)
    {
        if ($request->ajax()) {
            $company_id = Auth::user()->company_id;

            $total_count = MainEmployeeInvestigation::where('company_id', $company_id)
                ->where('employee_id', $request->employee_id)
                ->count();

            // Count for the current finance month only (to distinguish previous months vs current)
            $current_month_count = MainEmployeeInvestigation::where('company_id', $company_id)
                ->where('employee_id', $request->employee_id)
                ->where('finance_monthly_calendar_id', $request->finance_monthly_calendar_id)
                ->count();

            $previous_count = $total_count - $current_month_count;

            return response()->json([
                'status'             => 'true',
                'total_count'        => $total_count,
                'current_month_count'=> $current_month_count,
                'previous_count'     => $previous_count,
            ]);
        }
    }

    /**
     * Store a newly created investigation.
     */
    public function store(Request $request)
    {
        if ($request->ajax()) {
            $company_id = Auth::user()->company_id;

            $financeMonthlyCalendar = getColsWhereRow(
                FinanceMonthlyCalendar::class,
                ['id'],
                ['company_id' => $company_id, 'id' => $request->finance_monthly_calendar_id, 'status' => 1]
            );
            $mainSalaryEmployee = getColsWhereRow(
                MainSalaryEmployee::class,
                ['id'],
                [
                    'company_id'                  => $company_id,
                    'employee_id'                 => $request->employee_id,
                    'finance_monthly_calendar_id' => $request->finance_monthly_calendar_id,
                    'is_archived'                 => 0,
                ]
            );

            if (!empty($financeMonthlyCalendar) && !empty($mainSalaryEmployee)) {
                try {
                    return DB::transaction(function () use ($request, $company_id) {
                        $dataToInsert = [
                            'employee_id'                 => $request->employee_id,
                            'finance_monthly_calendar_id' => $request->finance_monthly_calendar_id,
                            'description'                 => $request->description,
                            'company_id'                  => $company_id,
                            'is_auto'                     => 0,
                            'added_by'                    => Auth::user()->id,
                            'notes'                       => $request->notes ?: null,
                        ];
                        $insertData = insert(MainEmployeeInvestigation::class, $dataToInsert);
                        if ($insertData) {
                            return response()->json(['status' => 'true', 'message' => 'تم إضافة التحقيق الإداري بنجاح']);
                        } else {
                            return response()->json(['status' => 'false', 'message' => 'عفوا لم يتم إضافة التحقيق الإداري']);
                        }
                    });
                } catch (\Exception $e) {
                    return response()->json(['status' => 'false', 'message' => 'عفوا حدث خطأ ' . $e->getMessage()]);
                }
            } else {
                return response()->json(['status' => 'false', 'message' => 'عفوا، لا توجد بيانات راتب مسجلة لهذا الموظف في هذا الشهر المالي أو الشهر مغلق.']);
            }
        }
    }

    /**
     * AJAX search for investigations within a finance month.
     */
    public function ajaxSearch(Request $request)
    {
        if ($request->ajax()) {
            $employee_id_search  = $request->employee_id_search;
            $is_archived_search  = $request->is_archived_search;
            $is_auto_search      = $request->is_auto_search;

            if (empty($employee_id_search)) {
                $field1    = 'id';
                $operator1 = '>=';
                $value1    = 0;
            } else {
                $field1    = 'employee_id';
                $operator1 = '=';
                $value1    = $employee_id_search;
            }

            if ($is_archived_search === null || $is_archived_search === '') {
                $field2    = 'id';
                $operator2 = '>=';
                $value2    = 0;
            } else {
                $field2    = 'is_archived';
                $operator2 = '=';
                $value2    = $is_archived_search;
            }

            if ($is_auto_search === null || $is_auto_search === '') {
                $field3    = 'id';
                $operator3 = '>=';
                $value3    = 0;
            } else {
                $field3    = 'is_auto';
                $operator3 = '=';
                $value3    = $is_auto_search;
            }

            $where = [
                [$field1, $operator1, $value1],
                [$field2, $operator2, $value2],
                [$field3, $operator3, $value3],
            ];

            $company_id = Auth::user()->company_id;

            $investigations = MainEmployeeInvestigation::with([
                'employee',
                'financeMonthlyCalendar',
                'addedBy',
                'updatedBy',
            ])
                ->where('company_id', $company_id)
                ->where('finance_monthly_calendar_id', $request->finance_monthly_calendar_id)
                ->where($where)
                ->orderBy('id', 'desc')
                ->paginate(PAGEINATION_COUNTER);

            return view('admin.mainSalaryRecordInvestigation.ajaxSearch', [
                'investigations' => $investigations,
            ]);
        }
    }

    /**
     * Delete (destroy) an investigation record.
     */
    public function destroy(Request $request)
    {
        if ($request->ajax()) {
            $company_id = Auth::user()->company_id;

            $financeMonthlyCalendar = getColsWhereRow(
                FinanceMonthlyCalendar::class,
                ['id'],
                ['company_id' => $company_id, 'id' => $request->finance_monthly_calendar_id, 'status' => 1]
            );
            if (empty($financeMonthlyCalendar)) {
                return response()->json(['status' => 'false', 'message' => 'عفوا غير قادر للوصول الى بيانات الشهر']);
            }

            $investigation = getColsWhereRow(
                MainEmployeeInvestigation::class,
                ['id'],
                ['company_id' => $company_id, 'id' => $request->id, 'is_archived' => 0]
            );
            if (empty($investigation)) {
                return response()->json(['status' => 'false', 'message' => 'عفوا غير قادر للوصول الى بيانات التحقيق أو أنه مؤرشف']);
            }

            $destroy = destroy($investigation);
            if ($destroy) {
                return response()->json(['status' => 'true', 'message' => 'تم حذف التحقيق الإداري بنجاح']);
            } else {
                return response()->json(['status' => 'false', 'message' => 'عفوا لم يتم حذف التحقيق الإداري']);
            }
        }
    }

    /**
     * Fetch a single investigation for editing.
     */
    public function edit(Request $request)
    {
        $company_id = Auth::user()->company_id;

        $financeMonthlyCalendar = getColsWhereRow(
            FinanceMonthlyCalendar::class,
            ['id'],
            ['company_id' => $company_id, 'id' => $request->finance_monthly_calendar_id, 'status' => 1]
        );
        if (empty($financeMonthlyCalendar)) {
            return response()->json(['status' => 'false', 'message' => 'عفوا غير قادر للوصول الى بيانات الشهر']);
        }

        $investigation = MainEmployeeInvestigation::with([
            'employee:name,id,salary,payment_per_day',
        ])
            ->where('company_id', $company_id)
            ->where('id', $request->id)
            ->first();

        if (empty($investigation)) {
            return response()->json(['status' => 'false', 'message' => 'عفوا غير قادر للوصول الى بيانات التحقيق']);
        }

        return response()->json(['status' => 'true', 'investigation' => $investigation]);
    }

    /**
     * Update an investigation record.
     */
    public function update(Request $request)
    {
        if ($request->ajax()) {
            $company_id = Auth::user()->company_id;

            $financeMonthlyCalendar = getColsWhereRow(
                FinanceMonthlyCalendar::class,
                ['id'],
                ['company_id' => $company_id, 'id' => $request->finance_monthly_calendar_id, 'status' => 1]
            );
            if (empty($financeMonthlyCalendar)) {
                return response()->json(['status' => 'false', 'message' => 'عفوا غير قادر للوصول الى بيانات الشهر']);
            }

            $investigation = getColsWhereRow(
                MainEmployeeInvestigation::class,
                ['*'],
                ['company_id' => $company_id, 'id' => $request->id]
            );
            if (empty($investigation)) {
                return response()->json(['status' => 'false', 'message' => 'عفوا غير قادر للوصول الى بيانات التحقيق']);
            }

            try {
                return DB::transaction(function () use ($request, $investigation) {
                    $dataToUpdate = [
                        'description' => $request->description,
                        'notes'       => $request->notes ?: null,
                        'updated_by'  => Auth::user()->id,
                    ];
                    $updateData = update($investigation, $dataToUpdate);
                    if ($updateData) {
                        return response()->json(['status' => 'true', 'message' => 'تم تعديل التحقيق الإداري بنجاح']);
                    } else {
                        return response()->json(['status' => 'false', 'message' => 'عفوا لم يتم تعديل التحقيق الإداري']);
                    }
                });
            } catch (\Exception $e) {
                return response()->json(['status' => 'false', 'message' => 'عفوا حدث خطأ ' . $e->getMessage()]);
            }
        }
    }

    /**
     * Print search results.
     */
    public function printSearch(Request $request)
    {
        $finance_monthly_calendar_id = $request->finance_monthly_calendar_id_search;
        $employee_id_search          = $request->employee_id_search;
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

        if (empty($is_archived_search)) {
            $field2    = 'id';
            $operator2 = '>=';
            $value2    = 0;
        } else {
            $field2    = 'is_archived';
            $operator2 = '=';
            $value2    = $is_archived_search;
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

        $investigations = MainEmployeeInvestigation::with([
            'employee',
            'financeMonthlyCalendar',
            'addedBy',
            'updatedBy',
        ])
            ->where('company_id', $company_id)
            ->where('finance_monthly_calendar_id', $finance_monthly_calendar_id)
            ->where($where)
            ->orderBy('employee_id', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        return view('admin.mainSalaryRecordInvestigation.print_search', [
            'investigations'        => $investigations,
            'systemData'            => $systemData,
            'financeMonthlyCalendar'=> $financeMonthlyCalendar,
        ]);
    }
}
