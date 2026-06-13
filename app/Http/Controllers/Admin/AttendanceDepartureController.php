<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FinanceMonthlyCalendar;
use App\Models\Employee;
use App\Models\Branche;
use App\Models\Department;
use App\Models\JobsCategory;
use App\Models\AdminPanelSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceDepartureController extends Controller
{
    public function index()
    {
        $company_id = Auth::user()->company_id;
        $financeMonthlyCalendars = get_cols_where_order2_with(FinanceMonthlyCalendar::class, ['financeCalendar'], ['*'], ['company_id' => $company_id], 'finance_yr', 'desc', 'id', 'asc', 12);
        foreach ($financeMonthlyCalendars as $calendar) {
            $calendar->total_opened_months = get_count_where(FinanceMonthlyCalendar::class, ['company_id' => $company_id, 'status' => '1']);
            $calendar->total_prev_months_waiting_to_open = FinanceMonthlyCalendar::where(['company_id' => $company_id, 'status' => '0', 'finance_yr' => $calendar->finance_yr])->where('month_id', '<', $calendar->month_id)->count();
        }
        return view('admin.attendanceDepartures.index', ['financeMonthlyCalendars' => $financeMonthlyCalendars]);
    }

    public function show($id)
    {
        $company_id = Auth::user()->company_id;
        $financeMonthlyCalendar = FinanceMonthlyCalendar::with('month')
            ->where('company_id', $company_id)
            ->where('id', $id)
            ->first();

        if (empty($financeMonthlyCalendar)) {
            return redirect()->route('admin.attendanceDepartures.index')->with('error', 'عفوا غير قادر للوصول الى بيانات الشهر');
        }

        $employees = Employee::with(['job', 'branch', 'department'])
            ->where('company_id', $company_id)
            ->orderBy('id', 'asc')
            ->paginate(PAGEINATION_COUNTER);

        $employees_search_list = Employee::select(['id', 'name'])
            ->where('company_id', $company_id)
            ->orderBy('id', 'asc')
            ->get();

        $branches = get_cols_where(Branche::class, ['id', 'name'], ['company_id' => $company_id, 'status' => 1], 'id', 'asc');
        $departments = get_cols_where(Department::class, ['id', 'name'], ['company_id' => $company_id, 'status' => 1], 'id', 'asc');
        $jobs = get_cols_where(JobsCategory::class, ['id', 'name'], ['company_id' => $company_id, 'status' => 1], 'id', 'asc');

        return view('admin.attendanceDepartures.show', [
            'financeMonthlyCalendar' => $financeMonthlyCalendar,
            'employees' => $employees,
            'employees_search_list' => $employees_search_list,
            'branches' => $branches,
            'departments' => $departments,
            'jobs' => $jobs,
        ]);
    }

    public function ajaxSearch(Request $request)
    {
        if ($request->ajax()) {
            $company_id = Auth::user()->company_id;
            $employee_id = $request->employee_id_search;
            $branch_id = $request->branch_id_search;
            $department_id = $request->department_id_search;
            $job_id = $request->job_id_search;

            $query = Employee::with(['job', 'branch', 'department'])
                ->where('company_id', $company_id);

            if (!empty($employee_id)) {
                $query->where('id', $employee_id);
            }
            if (!empty($branch_id)) {
                $query->where('branch_id', $branch_id);
            }
            if (!empty($department_id)) {
                $query->where('department_id', $department_id);
            }
            if (!empty($job_id)) {
                $query->where('job_id', $job_id);
            }

            $employees = $query->orderBy('id', 'asc')->paginate(PAGEINATION_COUNTER);
            $financeMonthlyCalendar = FinanceMonthlyCalendar::findOrFail($request->finance_monthly_calendar_id);

            return view('admin.attendanceDepartures.ajaxSearch', compact('employees', 'financeMonthlyCalendar'));
        }
    }

    public function printSearch(Request $request)
    {
        $company_id = Auth::user()->company_id;
        $employee_id = $request->employee_id_search;
        $branch_id = $request->branch_id_search;
        $department_id = $request->department_id_search;
        $job_id = $request->job_id_search;

        $query = Employee::with(['job', 'branch', 'department'])
            ->where('company_id', $company_id);

        if (!empty($employee_id)) {
            $query->where('id', $employee_id);
        }
        if (!empty($branch_id)) {
            $query->where('branch_id', $branch_id);
        }
        if (!empty($department_id)) {
            $query->where('department_id', $department_id);
        }
        if (!empty($job_id)) {
            $query->where('job_id', $job_id);
        }

        $employees = $query->orderBy('id', 'asc')->get();
        $financeMonthlyCalendar = FinanceMonthlyCalendar::with('month')
            ->where('company_id', $company_id)
            ->where('id', $request->finance_monthly_calendar_id_search)
            ->first();

        $adminPanelSetting = AdminPanelSetting::where('company_id', $company_id)->first();
        $systemData = [
            'system_name' => $adminPanelSetting->company_name ?? '',
            'photo'       => $adminPanelSetting->image ?? '',
            'address'     => $adminPanelSetting->address ?? '',
            'phone'       => $adminPanelSetting->phone ?? '',
            'email'       => $adminPanelSetting->email ?? '',
        ];

        return view('admin.attendanceDepartures.print_search', compact('employees', 'financeMonthlyCalendar', 'systemData'));
    }
        public function uploadExcel($id)
    {
        $company_id = Auth::user()->company_id;
        $financeMonthlyCalendar = FinanceMonthlyCalendar::with('month')
            ->where('company_id', $company_id)
            ->where('id', $id)
            ->first();

        if (empty($financeMonthlyCalendar)) {
            return redirect()->route('admin.attendanceDepartures.index')->with('error', 'عفوا غير قادر للوصول الى بيانات الشهر');
        }

        return view('admin.attendanceDepartures.upload-excel', [
            'financeMonthlyCalendar' => $financeMonthlyCalendar
        ]);
    }
}
