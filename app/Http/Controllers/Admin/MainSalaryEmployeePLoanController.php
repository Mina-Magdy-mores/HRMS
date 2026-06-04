<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminPanelSetting;
use App\Models\Employee;
use App\Models\MainSalaryEmployeePLoan;
use App\Models\MainSalaryEmployeePLoanInstallment;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MainSalaryEmployeePLoanController extends Controller
{
    public function index()
    {
        $company_id = Auth::user()->company_id;

        // Fetch statistics for info boxes
        $total_count = MainSalaryEmployeePLoan::where('company_id', $company_id)->count();
        $disbursed_count = MainSalaryEmployeePLoan::where('company_id', $company_id)->where('is_disbursed', 1)->count();
        $non_disbursed_count = MainSalaryEmployeePLoan::where('company_id', $company_id)->where('is_disbursed', 0)->count();
        $total_amount = MainSalaryEmployeePLoan::where('company_id', $company_id)->sum('amount');
        $employees_has_opened_monthly_record = Employee::select([
            'id',
            'name',
            'employee_code',
            'payment_per_day',
            'salary'
        ])
            ->where('company_id', $company_id)
            ->whereHas('mainSalaryEmployee', function ($query) {
                $query->where('employee_status', 1);
            })
            ->orderBy('id', 'asc')
            ->get();
        $employees = Employee::select([
            'id',
            'name',
        ])
            ->where('company_id', $company_id)
            ->orderBy('id', 'asc')
            ->get();
        $mainSalaryEmployeePLoans = getColsWhereP(
            MainSalaryEmployeePLoan::class,
            ['employee', 'addedBy', 'updatedBy', 'disbursedBy', 'archivedBy'],
            ['*'],
            ['company_id' => $company_id],
            'id',
            'desc',
            PAGEINATION_COUNTER
        );

        return view('admin.mainSalaryRecordPLoan.index', compact(
            'mainSalaryEmployeePLoans',
            'total_count',
            'disbursed_count',
            'non_disbursed_count',
            'total_amount',
            'employees_has_opened_monthly_record',
            'employees',
        ));
    }

    public function ajaxCheck(Request $request)
    {
        if ($request->ajax()) {
            $company_id = Auth::user()->company_id;
            $checkIfExistsCounter = get_count_where(MainSalaryEmployeePLoan::class, ['company_id' => $company_id, 'employee_id' => $request->employee_id, 'is_archived' => 0]);
            if ($checkIfExistsCounter > 0) {
                return response()->json(['status' => 'true', 'count' => $checkIfExistsCounter]);
            }
            return response()->json(['status' => 'false', 'count' => 0]);
        }
    }
    public function store(Request $request)
    {
        if ($request->ajax()) {
            $company_id = Auth::user()->company_id;
            $checkIfExists = getColsWhereRow(Employee::class, ['id'], ['id' => $request->employee_id, 'company_id' => $company_id, 'employment_status' => 1]);
            if ($checkIfExists == null) {
                return response()->json(['status' => 'false', 'message' => 'عفوا، لا توجد بيانات راتب مسجلة لهذا الموظف في هذا الشهر المالي.']);
            }
            try {
                return DB::transaction(function () use ($request, $company_id) {
                    $dataToInsert = [
                        'employee_id'             => $request->employee_id,
                        'employee_basic_salary'   => $request->employee_basic_salary,
                        'amount'                  => $request->amount,
                        'number_of_installment_months' => $request->number_of_installment_months,
                        'installment_amount_monthly' => $request->installment_amount_monthly,
                        'next_installment_date'   => $request->next_installment_date,
                        'next_installment_year_and_month'  => date('Y-m', strtotime($request->next_installment_date)),
                        'company_id'              => $company_id,
                        'added_by'                => Auth::user()->id,
                        'notes'                   => $request->notes,
                    ];
                    $insertData = insert(MainSalaryEmployeePLoan::class, $dataToInsert);
                    if ($insertData) {
                        $next_installment_year_and_month = date('Y-m', strtotime($request->next_installment_date));
                        for ($i = 1; $i <= $request->number_of_installment_months; $i++) {
                            $dataToInsertInstallment = [
                                'main_salary_employee_p_loan_id' => $insertData->id,
                                'amount' => $request->amount,
                                'installment_amount_monthly' => $request->installment_amount_monthly,
                                'next_installment_year_and_month'  => $next_installment_year_and_month,
                                'installment_status' => '0',
                                'company_id' => $company_id,
                                'added_by'                => Auth::user()->id,
                                'notes'                   => $request->notes,
                            ];
                            $next_installment_year_and_month = date('Y-m', strtotime($next_installment_year_and_month . ' + 1 month'));
                            $insertDataInstallment = insert(MainSalaryEmployeePLoanInstallment::class, $dataToInsertInstallment);
                        }



                        if ($insertDataInstallment) {
                            return response()->json(['status' => 'true', 'message' => 'تم اضافة السلفة بنجاح']);
                        } else {
                            return response()->json(['status' => 'false', 'message' => 'عفوا لم يتم اضافة السلفة']);
                        }
                    } else {
                        return response()->json(['status' => 'false', 'message' => 'عفوا لم يتم اضافة السلفة']);
                    }
                });
            } catch (\Exception $e) {
                return response()->json(['status' => 'false', 'message' => 'عفوا حدث خطأ ' . $e->getMessage()]);
            }
        } else {
            return response()->json(['status' => 'false', 'message' => 'عفوا، لا توجد بيانات راتب مسجلة لهذا الموظف في هذا الشهر المالي.']);
        }
    }





    public function ajaxSearch(Request $request)
    {
        if ($request->ajax()) {
            $employee_id_search = $request->employee_id_search;
            $is_archived_search = $request->is_archived_search;
            $is_disbursed_search = $request->is_disbursed_search;
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
            if (empty($is_disbursed_search)) {
                $field3 = "id";
                $operator3 = ">=";
                $value3 = 0;
            } else {
                $field3 = "is_disbursed";
                $operator3 = "=";
                $value3 = $is_disbursed_search;
            }

            $where = [
                [$field1, $operator1, $value1],
                [$field2, $operator2, $value2],
                [$field3, $operator3, $value3],
            ];
            $company_id = Auth::user()->company_id;
            $mainSalaryEmployeePLoans = MainSalaryEmployeePLoan::with([
                'employee',
                'addedBy',
                'updatedBy',
                'disbursedBy',
                'archivedBy'
            ])
                ->where('company_id', $company_id)
                ->where($where)
                ->orderBy('id', 'desc')
                ->paginate(PAGEINATION_COUNTER);
            return view('admin.mainSalaryRecordPLoan.ajaxSearch', ['mainSalaryEmployeePLoans' => $mainSalaryEmployeePLoans]);
        }
    }


    public function printSearch(Request $request)
    {
        $employee_id_search = $request->employee_id_search;
        $is_archived_search = $request->is_archived_search;
        $is_disbursed_search = $request->is_disbursed_search;
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
        if (empty($is_disbursed_search)) {
            $field3 = "id";
            $operator3 = ">=";
            $value3 = 0;
        } else {
            $field3 = "is_disbursed";
            $operator3 = "=";
            $value3 = $is_disbursed_search;
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

        $mainSalaryEmployeePLoans = MainSalaryEmployeePLoan::with([
            'employee',
            'addedBy',
            'updatedBy',
            'archivedBy',
            'disbursedBy',
            'mainSalaryEmployeePLoanInstallments'
        ])
            ->where('company_id', $company_id)
            ->where($where)
            ->orderBy('employee_id', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $total_sum = $mainSalaryEmployeePLoans->sum('amount');

        return view('admin.mainSalaryRecordPLoan.print_search', [
            'mainSalaryEmployeeLoans' => $mainSalaryEmployeePLoans,
            'systemData'              => $systemData,
            'total_sum'               => $total_sum,
        ]);
    }
}
