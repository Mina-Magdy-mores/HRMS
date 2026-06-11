<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminPanelSetting;
use App\Models\Employee;
use App\Models\MainSalaryEmployee;
use App\Models\MainSalaryEmployeePLoan;
use App\Models\MainSalaryEmployeePLoanInstallment;
use App\Traits\GeneralTrait;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MainSalaryEmployeePLoanController extends Controller
{
    use GeneralTrait;
    public function index()
    {
        $company_id = Auth::user()->company_id;

        // Auto-archive parent loans if all their installments are paid and archived
        $activeLoans = MainSalaryEmployeePLoan::where('company_id', $company_id)->where('is_archived', 0)->get();
        foreach ($activeLoans as $loan) {
            $totalInstallments = MainSalaryEmployeePLoanInstallment::where('main_salary_employee_p_loan_id', $loan->id)->count();
            $paidAndArchived = MainSalaryEmployeePLoanInstallment::where('main_salary_employee_p_loan_id', $loan->id)
                ->whereIn('installment_status', ['1', '2'])
                ->where('is_archived', 1)
                ->count();

            if ($totalInstallments > 0 && $totalInstallments === $paidAndArchived) {
                $loan->update([
                    'is_archived' => 1,
                    'archived_by' => Auth::user()->id,
                    'archived_at' => date('Y-m-d H:i:s'),
                ]);
            }
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
        $mainSalaryEmployeePLoans = getColsWhereP(
            MainSalaryEmployeePLoan::class,
            ['employee', 'addedBy', 'updatedBy', 'disbursedBy', 'archivedBy'],
            ['*'],
            ['company_id' => $company_id],
            'id',
            'desc',
            PAGEINATION_COUNTER
        );

        foreach ($mainSalaryEmployeePLoans as $loan) {
            $totalPaid = MainSalaryEmployeePLoanInstallment::where('main_salary_employee_p_loan_id', $loan->id)
                ->whereIn('installment_status', ['1', '2'])
                ->sum('installment_amount_monthly');

            $loan->update([
                'paid_amount' => $totalPaid,
                'remaining_amount' => max(0, $loan->amount - $totalPaid)
            ]);
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
                return DB::transaction(function () use ($request, $company_id) {
                    $dataToInsert = [
                        'employee_id'             => $request->employee_id,
                        'employee_basic_salary'   => $request->employee_basic_salary,
                        'amount'                  => $request->amount,
                        'number_of_installment_months' => $request->number_of_installment_months,
                        'installment_amount_monthly' => $request->installment_amount_monthly,
                        'next_installment_date'   => $request->next_installment_date,
                        'next_installment_year_and_month'  => date('Y-m', strtotime($request->next_installment_date)),
                        'remaining_amount'        => $request->amount,
                        'company_id'              => $company_id,
                        'added_by'                => Auth::user()->id,
                        'notes'                   => $request->notes,
                    ];
                    $insertData = insert(MainSalaryEmployeePLoan::class, $dataToInsert);
                    if ($insertData) {
                        $next_installment_year_and_month = date('Y-m', strtotime($request->next_installment_date));
                        for ($i = 1; $i <= $request->number_of_installment_months; $i++) {
                            $dataToInsertInstallment = [
                                'employee_id' => $request->employee_id,
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
            $company_id = Auth::user()->company_id;

            // Auto-archive parent loans if all their installments are paid and archived
            $activeLoans = MainSalaryEmployeePLoan::where('company_id', $company_id)->where('is_archived', 0)->get();
            foreach ($activeLoans as $loan) {
                $totalInstallments = MainSalaryEmployeePLoanInstallment::where('main_salary_employee_p_loan_id', $loan->id)->count();
                $paidAndArchived = MainSalaryEmployeePLoanInstallment::where('main_salary_employee_p_loan_id', $loan->id)
                    ->whereIn('installment_status', ['1', '2'])
                    ->where('is_archived', 1)
                    ->count();

                if ($totalInstallments > 0 && $totalInstallments === $paidAndArchived) {
                    $loan->update([
                        'is_archived' => 1,
                        'archived_by' => Auth::user()->id,
                        'archived_at' => date('Y-m-d H:i:s'),
                    ]);
                }
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
                $totalPaid = MainSalaryEmployeePLoanInstallment::where('main_salary_employee_p_loan_id', $loan->id)
                    ->whereIn('installment_status', ['1', '2'])
                    ->sum('installment_amount_monthly');

                $loan->update([
                    'paid_amount' => $totalPaid,
                    'remaining_amount' => max(0, $loan->amount - $totalPaid)
                ]);
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
            $mainSalaryEmployeePLoan = getColsWhereRow(MainSalaryEmployeePLoan::class, ['*'], ['id' => $request->id, 'company_id' => $company_id]);

            if ($mainSalaryEmployeePLoan) {
                // Check if it should be archived now
                if ($mainSalaryEmployeePLoan->is_archived == 0) {
                    $totalInstallments = MainSalaryEmployeePLoanInstallment::where('main_salary_employee_p_loan_id', $mainSalaryEmployeePLoan->id)->count();
                    $paidAndArchived = MainSalaryEmployeePLoanInstallment::where('main_salary_employee_p_loan_id', $mainSalaryEmployeePLoan->id)
                        ->whereIn('installment_status', ['1', '2'])
                        ->where('is_archived', 1)
                        ->count();

                    if ($totalInstallments > 0 && $totalInstallments === $paidAndArchived) {
                        $mainSalaryEmployeePLoan->update([
                            'is_archived' => 1,
                            'archived_by' => Auth::user()->id,
                            'archived_at' => date('Y-m-d H:i:s'),
                        ]);
                    }
                }

                $totalPaid = MainSalaryEmployeePLoanInstallment::where('main_salary_employee_p_loan_id', $mainSalaryEmployeePLoan->id)
                    ->whereIn('installment_status', ['1', '2'])
                    ->sum('installment_amount_monthly');

                $mainSalaryEmployeePLoan->update([
                    'paid_amount' => $totalPaid,
                    'remaining_amount' => max(0, $mainSalaryEmployeePLoan->amount - $totalPaid)
                ]);

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

                 // Determine which installment is eligible for cash payment
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
            $company_id = Auth::user()->company_id;
            $mainSalaryEmployeePLoan = getColsWhereRow(MainSalaryEmployeePLoan::class, ['id', 'is_archived', 'is_disbursed'], ['company_id' => $company_id, 'id' => $request->id]);
            if (empty($mainSalaryEmployeePLoan)) {
                return response()->json(['status' => 'false', 'message' => 'عفوا غير قادر للوصول الى بيانات السلفة']);
            }
            if ($mainSalaryEmployeePLoan['is_archived'] == 1 || $mainSalaryEmployeePLoan['is_disbursed'] == 1) {
                return response()->json(['status' => 'false', 'message' => 'عفوا لا يمكن حذف السلفة']);
            }
            try {
                return DB::transaction(function () use ($mainSalaryEmployeePLoan) {
                    $destroyMainSalaryEmployeePLoanInstallments = $mainSalaryEmployeePLoan->mainSalaryEmployeePLoanInstallments()->delete();
                    $destroyMainSalaryEmployeePLoan = $mainSalaryEmployeePLoan->delete();
                    if ($destroyMainSalaryEmployeePLoan && $destroyMainSalaryEmployeePLoanInstallments) {
                        return response()->json(['status' => 'true', 'message' => 'تم حذف السلفة بنجاح']);
                    } else {
                        return response()->json(['status' => 'false', 'message' => 'عفوا لم يتم حذف السلفة']);
                    }
                });
            } catch (\Exception $e) {
                return response()->json(['status' => 'false', 'message' => 'عفوا لم يتم حذف السلفة']);
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
        $mainSalaryEmployeePLoan = MainSalaryEmployeePLoan::where('company_id', $company_id)->where('id', $request->id)->where('employee_id', $request->employee_id)->first();
        if (empty($mainSalaryEmployeePLoan)) {
            return response()->json(['status' => 'false', 'message' => 'عفوا غير قادر للوصول الى بيانات السلفة']);
        }
        if ($mainSalaryEmployeePLoan['is_archived'] == 1 || $mainSalaryEmployeePLoan['is_disbursed'] == 1) {
            return response()->json(['status' => 'false', 'message' => 'عفوا لا يمكن تعديل السلفة']);
        }

        if (date('Y-m-d', strtotime($request->year_and_month_started)) < date('Y-m-d')) {
            if (date('Y-m', strtotime($request->year_and_month_started)) < date('Y-m')) {
                return response()->json(['status' => 'false', 'message' => 'عفواً، لا يمكن أن يكون تاريخ بدء القسط في شهر سابق للشهر الحالي']);
            }
        }

        try {
            return DB::transaction(function () use ($request, $company_id, $mainSalaryEmployeePLoan) {
                $dataToUpdate = [
                    'amount'                  => $request->amount,
                    'number_of_installment_months' => $request->number_of_installment_months,
                    'installment_amount_monthly' => $request->installment_amount_monthly,
                    'next_installment_date'   => $request->year_and_month_started,
                    'next_installment_year_and_month'  => date('Y-m', strtotime($request->year_and_month_started)),
                    'remaining_amount'        => $request->amount,
                    'paid_amount'             => 0,
                    'updated_by'                => Auth::user()->id,
                    'notes'                   => $request->notes,
                ];
                $mainSalaryEmployeePLoan->mainSalaryEmployeePLoanInstallments()->delete();
                $flag = MainSalaryEmployeePLoan::where('id', $request->id)->update($dataToUpdate);
                $updateData = $mainSalaryEmployeePLoan->refresh();
                if ($flag) {
                    $next_installment_year_and_month = date('Y-m', strtotime($request->year_and_month_started));
                    for ($i = 1; $i <= $request->number_of_installment_months; $i++) {
                        $dataToInsertInstallment = [
                            'employee_id' => $request->employee_id,
                            'main_salary_employee_p_loan_id' => $updateData->id,
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
                        return response()->json(['status' => 'true', 'message' => 'تم تعديل السلفة بنجاح']);
                    } else {
                        return response()->json(['status' => 'false', 'message' => 'عفوا لم يتم تعديل السلفة']);
                    }
                } else {
                    return response()->json(['status' => 'false', 'message' => 'عفوا لم يتم تعديل السلفة']);
                }
            });
        } catch (\Exception $e) {
            return response()->json(['status' => 'false', 'message' => 'عفوا حدث خطأ ' . $e->getMessage()]);
        }
    }

    public function disbursed(Request $request)
    {
        if ($request->ajax()) {
            $company_id = Auth::user()->company_id;
            $mainSalaryEmployeePLoan = getColsWhereRow(MainSalaryEmployeePLoan::class, ['id', 'is_archived', 'is_disbursed', 'employee_id'], ['company_id' => $company_id, 'id' => $request->id]);
            if (empty($mainSalaryEmployeePLoan)) {
                return response()->json(['status' => 'false', 'message' => 'عفوا غير قادر للوصول الى بيانات السلفة']);
            }
            if ($mainSalaryEmployeePLoan['is_archived'] == 1 || $mainSalaryEmployeePLoan['is_disbursed'] == 1) {
                return response()->json(['status' => 'false', 'message' => 'عفوا لا يمكن صرف السلفة']);
            }
            try {
                return DB::transaction(function () use ($mainSalaryEmployeePLoan, $company_id) {
                    $updateData = $mainSalaryEmployeePLoan->update([
                        'is_disbursed' => 1,
                        'disbursed_by' => Auth::user()->id,
                        'disbursed_at' => date('Y-m-d H:i:s'),
                        'updated_by' => Auth::user()->id,
                    ]);
                    if ($updateData) {
                        $mainSalaryEmployee = MainSalaryEmployee::select('id')->where([
                            'employee_id' => $mainSalaryEmployeePLoan['employee_id'],
                            'company_id' => $company_id,
                            'is_archived' => 0
                        ])->first();

                        if (!empty($mainSalaryEmployee)) {
                            $this->recalculate_main_salary($mainSalaryEmployee->id);
                        }
                        return response()->json(['status' => 'true', 'message' => 'تم صرف السلفة بنجاح']);
                    } else {
                        return response()->json(['status' => 'false', 'message' => 'عفوا لم يتم صرف السلفة']);
                    }
                });
            } catch (\Exception $e) {
                return response()->json(['status' => 'false', 'message' => 'عفوا لم يتم صرف السلفة ' . $e->getMessage()]);
            }
        }
    }

    public function payInstallmentCash(Request $request)
    {
        if ($request->ajax()) {
            $company_id = Auth::user()->company_id;
            $installment = MainSalaryEmployeePLoanInstallment::where('company_id', $company_id)
                ->where('id', $request->id)
                ->where('is_archived', 0)
                ->where('installment_status', '0')
                ->first();

            if (empty($installment)) {
                return response()->json(['status' => 'false', 'message' => 'عفواً، القسط غير متاح للدفع كاش']);
            }

            $loan = $installment->mainSalaryEmployeePLoan;
            if (!$loan || $loan->is_archived == 1 || $loan->is_disbursed == 0) {
                return response()->json(['status' => 'false', 'message' => 'عفواً، السلفة الأساسية غير صالحة أو مغلقة']);
            }

            $firstEligible = $loan->mainSalaryEmployeePLoanInstallments()
                ->where('is_archived', 0)
                ->where('installment_status', '0')
                ->orderBy('id', 'asc')
                ->first();

            if (!$firstEligible || $firstEligible->id !== $installment->id) {
                return response()->json(['status' => 'false', 'message' => 'عفواً، يجب سداد الأقساط بالترتيب المستحق']);
            }

            try {
                return DB::transaction(function () use ($installment, $loan, $company_id) {
                    $installment->update([
                        'installment_status' => '2',
                        'is_archived' => 1,
                        'archived_by' => Auth::user()->id,
                        'archived_at' => date('Y-m-d H:i:s'),
                        'updated_by' => Auth::user()->id,
                    ]);

                    $totalPaid = MainSalaryEmployeePLoanInstallment::where('main_salary_employee_p_loan_id', $loan->id)
                        ->whereIn('installment_status', ['1', '2'])
                        ->sum('installment_amount_monthly');

                    $loan->update([
                        'paid_amount' => $totalPaid,
                        'remaining_amount' => max(0, $loan->amount - $totalPaid),
                        'updated_by' => Auth::user()->id,
                    ]);

                    // Check if parent loan should now be archived
                    $totalInstallments = MainSalaryEmployeePLoanInstallment::where('main_salary_employee_p_loan_id', $loan->id)->count();
                    $paidAndArchived = MainSalaryEmployeePLoanInstallment::where('main_salary_employee_p_loan_id', $loan->id)
                        ->whereIn('installment_status', ['1', '2'])
                        ->where('is_archived', 1)
                        ->count();

                    if ($totalInstallments > 0 && $totalInstallments === $paidAndArchived) {
                        $loan->update([
                            'is_archived' => 1,
                            'archived_by' => Auth::user()->id,
                            'archived_at' => date('Y-m-d H:i:s'),
                        ]);
                    }

                    $mainSalaryEmployee = MainSalaryEmployee::select('id')->where([
                        'employee_id' => $loan->employee_id,
                        'company_id' => $company_id,
                        'is_archived' => 0
                    ])->first();

                    if (!empty($mainSalaryEmployee)) {
                        $this->recalculate_main_salary($mainSalaryEmployee->id);
                    }

                    return response()->json([
                        'status' => 'true',
                        'message' => 'تم دفع القسط نقداً بنجاح وتحديث السلفة والراتب',
                        'parent_loan_id' => $loan->id
                    ]);
                });
            } catch (\Exception $e) {
                return response()->json(['status' => 'false', 'message' => 'عفواً، حدث خطأ: ' . $e->getMessage()]);
            }
        }
    }

    public function reschedule(Request $request)
    {
        if ($request->ajax()) {
            $company_id = Auth::user()->company_id;

            $loan = MainSalaryEmployeePLoan::where('id', $request->loan_id)
                ->where('company_id', $company_id)
                ->where('is_archived', 0)
                ->where('is_disbursed', 1)
                ->first();

            if (empty($loan)) {
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
                return DB::transaction(function () use ($loan, $cash_payment, $number_of_months, $start_date, $company_id, $firstAvailableInstallment) {

                    // 1. Delete all unarchived installments (is_archived = 0)
                    $loan->mainSalaryEmployeePLoanInstallments()->where('is_archived', 0)->delete();

                    // 2. Handle immediate cash payment if specified
                    if ($cash_payment > 0) {
                        $cash_payment_month = $firstAvailableInstallment ? $firstAvailableInstallment->next_installment_year_and_month : date('Y-m');
                        MainSalaryEmployeePLoanInstallment::create([
                            'employee_id' => $loan->employee_id,
                            'main_salary_employee_p_loan_id' => $loan->id,
                            'amount' => $loan->amount,
                            'installment_amount_monthly' => $cash_payment,
                            'next_installment_year_and_month' => $cash_payment_month,
                            'installment_status' => '2', // Paid cash
                            'is_archived' => 1, // Archived immediately
                            'archived_by' => Auth::user()->id,
                            'archived_at' => date('Y-m-d H:i:s'),
                            'company_id' => $company_id,
                            'added_by' => Auth::user()->id,
                            'notes' => 'دفعة نقدية فورية عند إعادة الجدولة',
                        ]);
                    }

                    // 3. Calculate new remaining balance and installment amount
                    $remainingBalance = floatval($loan->remaining_amount) - $cash_payment;

                    if ($remainingBalance > 0) {
                        $newInstallmentAmount = round($remainingBalance / $number_of_months, 2);

                        // 4. Create new installments starting from start_date
                        $next_installment_year_and_month = date('Y-m', strtotime($start_date));
                        for ($i = 1; $i <= $number_of_months; $i++) {
                            MainSalaryEmployeePLoanInstallment::create([
                                'employee_id' => $loan->employee_id,
                                'main_salary_employee_p_loan_id' => $loan->id,
                                'amount' => $loan->amount,
                                'installment_amount_monthly' => $newInstallmentAmount,
                                'next_installment_year_and_month' => $next_installment_year_and_month,
                                'installment_status' => '0', // Pending
                                'company_id' => $company_id,
                                'added_by' => Auth::user()->id,
                                'notes' => 'قسط مجدول جديد بعد إعادة الجدولة',
                            ]);
                            $next_installment_year_and_month = date('Y-m', strtotime($next_installment_year_and_month . ' + 1 month'));
                        }
                    }

                    // 5. Recalculate parent loan fields
                    $totalPaid = MainSalaryEmployeePLoanInstallment::where('main_salary_employee_p_loan_id', $loan->id)
                        ->whereIn('installment_status', ['1', '2'])
                        ->sum('installment_amount_monthly');

                    $loan->update([
                        'paid_amount' => $totalPaid,
                        'remaining_amount' => max(0, floatval($loan->amount) - $totalPaid),
                        'updated_by' => Auth::user()->id,
                    ]);

                    // 6. Check if parent loan should now be archived
                    $totalInstallments = MainSalaryEmployeePLoanInstallment::where('main_salary_employee_p_loan_id', $loan->id)->count();
                    $paidAndArchived = MainSalaryEmployeePLoanInstallment::where('main_salary_employee_p_loan_id', $loan->id)
                        ->whereIn('installment_status', ['1', '2'])
                        ->where('is_archived', 1)
                        ->count();

                    if ($totalInstallments > 0 && $totalInstallments === $paidAndArchived) {
                        $loan->update([
                            'is_archived' => 1,
                            'archived_by' => Auth::user()->id,
                            'archived_at' => date('Y-m-d H:i:s'),
                        ]);
                    }

                    // 7. Recalculate open salary record for this employee (if any exists)
                    $mainSalaryEmployee = MainSalaryEmployee::select('id')->where([
                        'employee_id' => $loan->employee_id,
                        'company_id' => $company_id,
                        'is_archived' => 0
                    ])->first();

                    if (!empty($mainSalaryEmployee)) {
                        $this->recalculate_main_salary($mainSalaryEmployee->id);
                    }

                    return response()->json([
                        'status' => 'true',
                        'message' => 'تمت إعادة جدولة الأقساط بنجاح وتحديث السلفة والراتب المفتوح إن وجد',
                        'parent_loan_id' => $loan->id
                    ]);
                });
            } catch (\Exception $e) {
                return response()->json(['status' => 'false', 'message' => 'عفواً، حدث خطأ: ' . $e->getMessage()]);
            }
        }
    }
}
