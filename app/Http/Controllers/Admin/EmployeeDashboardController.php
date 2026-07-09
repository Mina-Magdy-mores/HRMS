<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Auth;
use Illuminate\Http\Request;

class EmployeeDashboardController extends Controller
{
    /**
     * Display the employee dashboard/profile details.
     */
    public function index(Request $request)
    {
        $company_id = Auth::user()->company_id;
        $currentUser = Auth::user();
        
        // If employee is logged in, they can only view their own profile.
        // Otherwise, admins can select an employee to view.
        $employeeId = $currentUser->is_employee == 1 ? $currentUser->employee_id : $request->get('employee_id');
        
        $employees = [];
        if ($currentUser->is_employee == 0) {
            $employees = get_cols_where(Employee::class, ['id', 'name', 'employee_code'], ['company_id' => $company_id]);
        }

        $employee = null;
        $vacationBalances = null;
        $vacationBalancesList = collect();
        $attendanceLogs = collect();
        $salaryHistory = collect();
        $loans = collect();
        $ploans = collect();
        $tasks = collect();
        $financeMonthlyCalendars = collect();
        $financeMonthlyCalendar = null;

        if ($employeeId) {
            $employee = Employee::with(['department', 'qualification', 'job'])
                ->where('id', $employeeId)
                ->where('company_id', $company_id)
                ->first();

            if ($employee) {
                // أرصدة الإجازات
                $vacationBalancesList = \App\Models\MainEmployeesVacationsBalances::where('employee_id', $employeeId)
                    ->where('company_id', $company_id)
                    ->orderBy('id', 'desc')
                    ->get();
                $vacationBalances = $vacationBalancesList->first();

                // سجل البصمة (الحضور والانصراف)
                $attendanceLogs = \App\Models\AttendanceDeparture::where('employee_id', $employeeId)
                    ->where('company_id', $company_id)
                    ->orderBy('id', 'desc')
                    ->paginate(15, ['*'], 'attendance_page');

                // الشهور المالية لبصمة الموظف
                $financeMonthlyCalendars = \App\Models\FinanceMonthlyCalendar::with('month')
                    ->where('company_id', $company_id)
                    ->orderBy('finance_yr', 'desc')
                    ->orderBy('month_id', 'desc')
                    ->get();

                // الشهر المالي المختار
                $selected_calendar_id = $request->get('finance_monthly_calendar_id');
                if ($selected_calendar_id) {
                    $financeMonthlyCalendar = \App\Models\FinanceMonthlyCalendar::with('month')
                        ->where('company_id', $company_id)
                        ->where('id', $selected_calendar_id)
                        ->first();
                } else {
                    // افتراضيا، الشهر المفتوح أو الأحدث
                    $financeMonthlyCalendar = \App\Models\FinanceMonthlyCalendar::with('month')
                        ->where('company_id', $company_id)
                        ->where('status', 1)
                        ->first() ?: $financeMonthlyCalendars->first();
                }

                $salaryHistory = \App\Models\MainSalaryEmployee::with(['financeMonthlyCalendar.financeCalendar', 'financeMonthlyCalendar.month'])
                    ->where('employee_id', $employeeId)
                    ->where('company_id', $company_id)
                    ->orderBy('id', 'desc')
                    ->get();

                // السلف العادية
                $loans = \App\Models\MainSalaryEmployeeLoan::where('employee_id', $employeeId)
                    ->where('company_id', $company_id)
                    ->orderBy('id', 'desc')
                    ->get();

                // السلف المستديمة والأقساط
                $ploans = \App\Models\MainSalaryEmployeePLoan::with('mainSalaryEmployeePLoanInstallments')
                    ->where('employee_id', $employeeId)
                    ->where('company_id', $company_id)
                    ->orderBy('id', 'desc')
                    ->get();

                // مهام الموظف
                $tasks = \App\Models\EmployeeTask::where('employee_id', $employeeId)
                    ->where('company_id', $company_id)
                    ->orderBy('id', 'desc')
                    ->get();
            }
        }

        $activeTab = $request->get('tab', 'personal');

        return view('admin.employeeDashboard.index', compact(
            'employee', 'employees', 'vacationBalances', 'vacationBalancesList', 'attendanceLogs', 
            'salaryHistory', 'loans', 'ploans', 'employeeId', 'activeTab', 'tasks',
            'financeMonthlyCalendars', 'financeMonthlyCalendar'
        ));
    }
}
