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
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\HR\VacationBalanceService;

class MainEmployeesVacationsBalancesController extends Controller
{
    use GeneralTrait;

    protected $service;

    public function __construct(VacationBalanceService $service)
    {
        $this->service = $service;
    }

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
        $adminPanelSetting = AdminPanelSetting::select('is_allowed_to_pull_annual_from_fingerprint')
            ->where('company_id', $company_id)->first();

        $is_allowed_to_pull_annual_from_fingerprint = $adminPanelSetting->is_allowed_to_pull_annual_from_fingerprint;

        //Calculate Total monthly and annual vacation for the employee
        $this->service->calculateBalance($id);
        $this->service->calculateBalance($id);

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

        return view('admin.mainEmployeesVacationsBalances.show', compact('employee', 'vacationBalances', 'current_opened_month', 'financialYears', 'is_allowed_to_pull_annual_from_fingerprint'));
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

            $adminPanelSetting = AdminPanelSetting::select('is_allowed_to_pull_annual_from_fingerprint')
                ->where('company_id', $company_id)->first();
            $is_allowed_to_pull_annual_from_fingerprint = $adminPanelSetting ? $adminPanelSetting->is_allowed_to_pull_annual_from_fingerprint : 0;

            return view('admin.mainEmployeesVacationsBalances.show-table', compact('vacationBalances', 'is_allowed_to_pull_annual_from_fingerprint'));
        }
    }

    public function edit($id)
    {
        $company_id = Auth::user()->company_id;

        $adminPanelSetting = AdminPanelSetting::select('is_allowed_to_pull_annual_from_fingerprint')
            ->where('company_id', $company_id)->first();
        if (empty($adminPanelSetting)) {
            return redirect()->back()->with('error', 'ضبط إعدادات البرنامج غير موجودة يرجى التواصل مع الإدارة');
        }
        if ($adminPanelSetting->is_allowed_to_pull_annual_from_fingerprint == 1) {
            return redirect()->back()->with('error', 'تعديل الأرصدة يدوياً غير متاح نظراً لضبط النظام');
        }

        $balance = $this->service->getById($id);
        if (empty($balance)) {
            return redirect()->back()->with('error', 'السجل غير موجود');
        }

        $employee = Employee::where('company_id', $company_id)->find($balance->employee_id);

        return view('admin.mainEmployeesVacationsBalances.edit', compact('balance', 'employee'));
    }

    public function update(Request $request, $id)
    {
        $company_id = Auth::user()->company_id;

        $adminPanelSetting = AdminPanelSetting::select('is_allowed_to_pull_annual_from_fingerprint')
            ->where('company_id', $company_id)->first();
        if (empty($adminPanelSetting)) {
            return redirect()->back()->with('error', 'ضبط إعدادات البرنامج غير موجودة يرجى التواصل مع الإدارة');
        }
        if ($adminPanelSetting->is_allowed_to_pull_annual_from_fingerprint == 1) {
            return redirect()->back()->with('error', 'تعديل الأرصدة يدوياً غير متاح نظراً لضبط النظام');
        }

        $balance = $this->service->getById($id);
        if (empty($balance)) {
            return redirect()->back()->with('error', 'السجل غير موجود');
        }

        $request->validate([
            'carryover_from_previous_month' => 'required|numeric|min:0',
            'current_month_balance' => 'required|numeric|min:0',
            'spent_balance' => 'required|numeric|min:0',
        ], [
            'carryover_from_previous_month.required' => 'الرصيد المرحل مطلوب',
            'carryover_from_previous_month.numeric' => 'الرصيد المرحل يجب أن يكون رقم',
            'current_month_balance.required' => 'رصيد الشهر مطلوب',
            'current_month_balance.numeric' => 'رصيد الشهر يجب أن يكون رقم',
            'spent_balance.required' => 'الرصيد المستهلك مطلوب',
            'spent_balance.numeric' => 'الرصيد المستهلك يجب أن يكون رقم',
        ]);

        $carryover = (float)$request->carryover_from_previous_month;
        $current = (float)$request->current_month_balance;
        $spent = (float)$request->spent_balance;

        $total_available = $carryover + $current;
        $remaining_net = $total_available - $spent;

        try {
            $dataToUpdate = [
                'carryover_from_previous_month' => $carryover,
                'current_month_balance' => $current,
                'spent_balance' => $spent,
                'total_available_balance' => $total_available,
                'remaining_net_balance' => $remaining_net,
            ];
            $this->service->updateBalance($id, $dataToUpdate);

            return redirect()->route('admin.main-employees-vacations-balances.show', $balance->employee_id)
                ->with('success', 'تم تعديل رصيد الإجازات وتحديث الشهور التالية بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ: ' . $e->getMessage());
        }
    }
}
