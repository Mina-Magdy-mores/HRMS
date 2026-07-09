<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminPanelSetting;
use App\Models\Employee;
use App\Models\MainSalaryEmployee;
use App\Models\MainSalaryEmployeePLoan;
use App\Models\MainSalaryEmployeePLoanInstallment;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\Finance\PLoanService;

class MainSalaryEmployeePLoanController extends Controller
{
    use GeneralTrait;

    protected $service;

    public function __construct(PLoanService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $company_id = Auth::user()->company_id;

        // Auto-archive parent loans if all their installments are paid and archived
        $activeLoans = MainSalaryEmployeePLoan::where('company_id', $company_id)->where('is_archived', 0)->get();
        foreach ($activeLoans as $loan) {
            $this->service->updateParentLoanStats($loan);
        }

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
        $mainSalaryEmployeePLoans = $this->service->getPaginated(
            ['employee', 'addedBy', 'updatedBy', 'disbursedBy', 'archivedBy'],
            ['*'],
            [],
            'id',
            'desc',
            PAGEINATION_COUNTER
        );

        foreach ($mainSalaryEmployeePLoans as $loan) {
            $this->service->updateParentLoanStats($loan);
        }

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

            if (date('Y-m-d', strtotime($request->next_installment_date)) < date('Y-m-d')) {
                if (date('Y-m', strtotime($request->next_installment_date)) < date('Y-m')) {
                    return response()->json(['status' => 'false', 'message' => 'عفواً، لا يمكن أن يكون تاريخ بدء القسط في شهر سابق للشهر الحالي']);
                }
            }

            try {
                $dataToInsert = [
                    'employee_id'             => $request->employee_id,
                    'employee_basic_salary'   => $request->employee_basic_salary,
                    'amount'                  => $request->amount,
                    'number_of_installment_months' => $request->number_of_installment_months,
                    'installment_amount_monthly' => $request->installment_amount_monthly,
                    'next_installment_date'   => $request->next_installment_date,
                    'next_installment_year_and_month'  => date('Y-m', strtotime($request->next_installment_date)),
                    'notes'                   => $request->notes ?: 'تم إنشاء السلفة وجدولتها تلقائياً',
                ];
                $this->service->createPLoan($dataToInsert);
                return response()->json(['status' => 'true', 'message' => 'تم اضافة السلفة بنجاح']);
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
            $company_id = Auth::user()->company_id;

            $activeLoans = MainSalaryEmployeePLoan::where('company_id', $company_id)->where('is_archived', 0)->get();
            foreach ($activeLoans as $loan) {
                $this->service->updateParentLoanStats($loan);
            }

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

            foreach ($mainSalaryEmployeePLoans as $loan) {
                $this->service->updateParentLoanStats($loan);
            }

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

    public function show(Request $request)
    {
        if ($request->ajax()) {
            $company_id = Auth::user()->company_id;
            $mainSalaryEmployeePLoan = $this->service->getById($request->id);

            if ($mainSalaryEmployeePLoan) {
                $this->service->updateParentLoanStats($mainSalaryEmployeePLoan);
                $mainSalaryEmployeePLoan->refresh();
                $mainSalaryEmployeePLoan->load([
                    'employee',
                    'archivedBy',
                    'mainSalaryEmployeePLoanInstallments' => function ($query) {
                        $query->orderBy('id', 'asc');
                    },
                    'mainSalaryEmployeePLoanInstallments.archivedBy',
                    'mainSalaryEmployeePLoanInstallments.mainSalaryEmployee',
                    'mainSalaryEmployeePLoanInstallments.addedBy',
                    'mainSalaryEmployeePLoanInstallments.updatedBy'
                ]);

                 $firstEligible = null;
                 if ($mainSalaryEmployeePLoan->is_disbursed == 1 && $mainSalaryEmployeePLoan->is_archived == 0) {
                      $firstEligible = $mainSalaryEmployeePLoan->mainSalaryEmployeePLoanInstallments
                          ->where('is_archived', 0)
                          ->where('installment_status', '0')
                          ->first();
                 }

                 foreach ($mainSalaryEmployeePLoan->mainSalaryEmployeePLoanInstallments as $installment) {
                     $installment->can_pay_cash = ($firstEligible && $installment->id === $firstEligible->id);
                 }
            }

            return view('admin.mainSalaryRecordPLoan.show', [
                'mainSalaryEmployeePLoans' => $mainSalaryEmployeePLoan,
            ]);
        }
    }

    public function destroy(Request $request)
    {
        if ($request->ajax()) {
            try {
                $this->service->deletePLoan($request->id);
                return response()->json(['status' => 'true', 'message' => 'تم حذف السلفة بنجاح']);
            } catch (\Exception $e) {
                return response()->json(['status' => 'false', 'message' => $e->getMessage()]);
            }
        }
    }

    public function edit(Request $request)
    {
        $company_id = Auth::user()->company_id;
        $mainSalaryEmployeePLoan = MainSalaryEmployeePLoan::with([
            'employee:name,id,salary,payment_per_day',
        ])
            ->where('company_id', $company_id)
            ->where('id', $request->id)
            ->first();
        if (empty($mainSalaryEmployeePLoan)) {
            return response()->json(['status' => 'false', 'message' => 'عفوا غير قادر للوصول الى بيانات السلفة']);
        }
        if ($mainSalaryEmployeePLoan['is_archived'] == 1 || $mainSalaryEmployeePLoan['is_disbursed'] == 1) {
            return response()->json(['status' => 'false', 'message' => 'عفوا لا يمكن تعديل السلفة']);
        }
        return response()->json(['status' => 'true', 'mainSalaryEmployeePLoan' => $mainSalaryEmployeePLoan]);
    }

    public function update(Request $request)
    {
        $company_id = Auth::user()->company_id;

        if (date('Y-m-d', strtotime($request->year_and_month_started)) < date('Y-m-d')) {
            if (date('Y-m', strtotime($request->year_and_month_started)) < date('Y-m')) {
                return response()->json(['status' => 'false', 'message' => 'عفواً، لا يمكن أن يكون تاريخ بدء القسط في شهر سابق للشهر الحالي']);
            }
        }

        try {
            $dataToUpdate = [
                'amount'                  => $request->amount,
                'number_of_installment_months' => $request->number_of_installment_months,
                'installment_amount_monthly' => $request->installment_amount_monthly,
                'next_installment_date'   => $request->year_and_month_started,
                'next_installment_year_and_month'  => date('Y-m', strtotime($request->year_and_month_started)),
                'notes'                   => $request->notes ?: 'تم تعديل السلفة وإعادة جولتها تلقائياً',
            ];
            $this->service->updatePLoan($request->id, $dataToUpdate);
            return response()->json(['status' => 'true', 'message' => 'تم تعديل السلفة بنجاح']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'false', 'message' => 'عفوا حدث خطأ ' . $e->getMessage()]);
        }
    }

    public function disbursed(Request $request)
    {
        if ($request->ajax()) {
            try {
                $this->service->disbursePLoan($request->id, Auth::id());
                return response()->json(['status' => 'true', 'message' => 'تم صرف السلفة بنجاح']);
            } catch (\Exception $e) {
                return response()->json(['status' => 'false', 'message' => 'عفوا لم يتم صرف السلفة ' . $e->getMessage()]);
            }
        }
    }

    public function payInstallmentCash(Request $request)
    {
        if ($request->ajax()) {
            try {
                $loan = $this->service->payInstallmentCash($request->id, Auth::id());
                return response()->json([
                    'status' => 'true',
                    'message' => 'تم دفع القسط نقداً بنجاح وتحديث السلفة والراتب',
                    'parent_loan_id' => $loan->id
                ]);
            } catch (\Exception $e) {
                return response()->json(['status' => 'false', 'message' => 'عفواً، حدث خطأ: ' . $e->getMessage()]);
            }
        }
    }

    public function reschedule(Request $request)
    {
        if ($request->ajax()) {
            $company_id = Auth::user()->company_id;

            $loan = $this->service->getById($request->loan_id);
            if (empty($loan) || $loan->is_archived == 1 || $loan->is_disbursed == 0) {
                return response()->json(['status' => 'false', 'message' => 'عفواً، السلفة غير صالحة أو مؤرشفة بالفعل']);
            }

            $firstAvailableInstallment = $loan->mainSalaryEmployeePLoanInstallments()
                ->where('is_archived', 0)
                ->where('installment_status', '0')
                ->orderBy('id', 'asc')
                ->first();

            $start_date = $request->start_date;
            if (empty($start_date)) {
                if ($firstAvailableInstallment) {
                    $start_date = $firstAvailableInstallment->next_installment_year_and_month . '-01';
                } else {
                    $start_date = date('Y-m-d');
                }
            }

            $remainingInstallmentsCount = $loan->mainSalaryEmployeePLoanInstallments()
                ->where('is_archived', 0)
                ->where('installment_status', '0')
                ->count();

            $number_of_months = $request->filled('number_of_months') ? intval($request->number_of_months) : $remainingInstallmentsCount;
            if ($number_of_months < 1) {
                $number_of_months = 1;
            }

            $cash_payment = floatval($request->cash_payment ?? 0);

            if ($cash_payment < 0 || $cash_payment > floatval($loan->remaining_amount)) {
                return response()->json(['status' => 'false', 'message' => 'عفواً، مبلغ السداد النقدي غير صحيح أو يتجاوز المبلغ المتبقي للسلفة']);
            }

            if (date('Y-m-d', strtotime($start_date)) < date('Y-m-d')) {
                if (date('Y-m', strtotime($start_date)) < date('Y-m')) {
                    return response()->json(['status' => 'false', 'message' => 'عفواً، لا يمكن أن يكون تاريخ بدء الأقساط في شهر سابق للشهر الحالي']);
                }
            }

            try {
                $loan = $this->service->reschedule($request->loan_id, $cash_payment, $number_of_months, $start_date, Auth::id());
                return response()->json([
                    'status' => 'true',
                    'message' => 'تمت إعادة جدولة الأقساط بنجاح وتحديث السلفة والراتب المفتوح إن وجد',
                    'parent_loan_id' => $loan->id
                ]);
            } catch (\Exception $e) {
                return response()->json(['status' => 'false', 'message' => 'عفواً، حدث خطأ: ' . $e->getMessage()]);
            }
        }
    }
}
