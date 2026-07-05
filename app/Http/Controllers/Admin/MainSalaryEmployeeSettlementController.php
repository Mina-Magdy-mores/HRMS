<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MainSalaryEmployeeSettlement;
use App\Models\Employee;
use App\Models\FinanceMonthlyCalendar;
use App\Models\MainSalaryEmployee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MainSalaryEmployeeSettlementController extends Controller
{
    /**
     * Display a listing of the months.
     */
    public function index()
    {
        $company_id = Auth::user()->company_id;
        
        // Load the finance calendar months using the helper or raw query
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
            $calendar->total_opened_months = get_count_where(FinanceMonthlyCalendar::class, ['company_id' => $company_id, 'status' => '1']);
            $calendar->total_prev_months_waiting_to_open = FinanceMonthlyCalendar::where([
                'company_id' => $company_id,
                'status' => '0',
                'finance_yr' => $calendar->finance_yr
            ])->where('month_id', '<', $calendar->month_id)->count();
        }

        return view('admin.mainSalaryRecordSettlement.index', ['financeMonthlyCalendars' => $financeMonthlyCalendars]);
    }

    /**
     * Show settlements for a specific monthly calendar.
     */
    public function show($id)
    {
        $company_id = Auth::user()->company_id;
        $financeMonthlyCalendar = FinanceMonthlyCalendar::with('month')
            ->where('company_id', $company_id)
            ->where('id', $id)
            ->first();

        if (empty($financeMonthlyCalendar)) {
            return redirect()->route('admin.main-salary-employee-settlements.index')->with('error', 'عفواً، غير قادر على الوصول إلى بيانات الشهر المالي.');
        }

        // Fetch employees who have an archived salary in this month
        $employees = Employee::select(['id', 'name', 'employee_code', 'salary', 'payment_per_day'])
            ->where('company_id', $company_id)
            ->whereHas('mainSalaryEmployee', function ($query) use ($id) {
                $query->where('finance_monthly_calendar_id', $id)
                    ->where('is_archived', 1);
            })
            ->orderBy('id', 'asc')
            ->get();

        $mainSalaryEmployeeSettlements = MainSalaryEmployeeSettlement::with([
            'employee',
            'financeMonthlyCalendar',
            'addedBy',
            'updatedBy'
        ])
            ->where('company_id', $company_id)
            ->where('finance_monthly_calendar_id', $id)
            ->orderBy('id', 'desc')
            ->paginate(PAGEINATION_COUNTER);

        $mainSalaryEmployeeSettlements2 = MainSalaryEmployeeSettlement::where('company_id', $company_id)
            ->where('finance_monthly_calendar_id', $id)
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.mainSalaryRecordSettlement.show', [
            'financeMonthlyCalendar' => $financeMonthlyCalendar,
            'mainSalaryEmployeeSettlements' => $mainSalaryEmployeeSettlements,
            'mainSalaryEmployeeSettlements2' => $mainSalaryEmployeeSettlements2,
            'employees' => $employees,
        ]);
    }

    /**
     * Store a newly created settlement.
     */
    public function store(Request $request)
    {
        if ($request->ajax()) {
            $company_id = Auth::user()->company_id;

            $financeMonthlyCalendar = getColsWhereRow(FinanceMonthlyCalendar::class, ['id'], ['company_id' => $company_id, 'id' => $request->finance_monthly_calendar_id]);
            
            // Check if there is an archived salary record for the employee in this month
            $mainSalaryEmployee = getColsWhereRow(MainSalaryEmployee::class, ['id', 'is_archived'], [
                'company_id' => $company_id,
                'employee_id' => $request->employee_id,
                'finance_monthly_calendar_id' => $request->finance_monthly_calendar_id,
                'is_archived' => 1
            ]);

            if (empty($financeMonthlyCalendar)) {
                return response()->json(['status' => 'false', 'message' => 'الشهر المالي غير موجود.']);
            }

            if (empty($mainSalaryEmployee)) {
                return response()->json(['status' => 'false', 'message' => 'عفواً، لا توجد تفاصيل راتب مؤرشف لهذا الموظف في هذا الشهر المالي لعمل تسوية له.']);
            }

            // Check if settlement already exists for this employee in this month
            $exists = MainSalaryEmployeeSettlement::where('company_id', $company_id)
                ->where('finance_monthly_calendar_id', $request->finance_monthly_calendar_id)
                ->where('employee_id', $request->employee_id)
                ->exists();

            if ($exists) {
                return response()->json(['status' => 'false', 'message' => 'عفواً، توجد تسوية مسجلة بالفعل لهذا الموظف في هذا الشهر.']);
            }

            try {
                return DB::transaction(function () use ($request, $company_id) {
                    $employee_per_day_salary = (float)$request->employee_per_day_salary;
                    
                    // Additions
                    $working_days_number = (float)$request->input('working_days_number', 0);
                    $working_days_amount = $working_days_number * $employee_per_day_salary;

                    $extra_working_days_number = (float)$request->input('extra_working_days_number', 0);
                    $extra_working_days_amount = $extra_working_days_number * $employee_per_day_salary;

                    $absent_days_back_number = (float)$request->input('absent_days_back_number', 0);
                    $absent_days_back_amount = $absent_days_back_number * $employee_per_day_salary;

                    $deducted_days_restored_number = (float)$request->input('deducted_days_restored_number', 0);
                    $deducted_days_restored_amount = $deducted_days_restored_number * $employee_per_day_salary;

                    $different_in_salary_amount = (float)$request->input('different_in_salary_amount', 0);
                    $bonus_amount = (float)$request->input('bonus_amount', 0);
                    $allowance_amount = (float)$request->input('allowance_amount', 0);

                    $total_addition = $working_days_amount + $extra_working_days_amount + $absent_days_back_amount + $deducted_days_restored_amount + $different_in_salary_amount + $bonus_amount + $allowance_amount;

                    // Deductions
                    $absent_days_number = (float)$request->input('absent_days_number', 0);
                    $absent_days_amount = $absent_days_number * $employee_per_day_salary;

                    $deducted_days_number = (float)$request->input('deducted_days_number', 0);
                    $deducted_days_amount = $deducted_days_number * $employee_per_day_salary;

                    $salary_deduction_amount = (float)$request->input('salary_deduction_amount', 0);
                    $others_salary_deduction_amount = (float)$request->input('others_salary_deduction_amount', 0);
                    $medical_insurance_deduction_amount = (float)$request->input('medical_insurance_deduction_amount', 0);
                    $monthly_loan_deduction_amount = (float)$request->input('monthly_loan_deduction_amount', 0);
                    $permanent_loan_deduction_amount = (float)$request->input('permanent_loan_deduction_amount', 0);
                    $penalty_deduction_amount = (float)$request->input('penalty_deduction_amount', 0);

                    $total_deduction = $absent_days_amount + $deducted_days_amount + $salary_deduction_amount + $others_salary_deduction_amount + $medical_insurance_deduction_amount + $monthly_loan_deduction_amount + $permanent_loan_deduction_amount + $penalty_deduction_amount;

                    $final_total_amount = $total_addition - $total_deduction;

                    $dataToInsert = [
                        'finance_monthly_calendar_id' => $request->finance_monthly_calendar_id,
                        'employee_id' => $request->employee_id,
                        'employee_per_day_salary' => $employee_per_day_salary,
                        
                        'working_days_number' => $working_days_number,
                        'working_days_amount' => $working_days_amount,
                        'extra_working_days_number' => $extra_working_days_number,
                        'extra_working_days_amount' => $extra_working_days_amount,
                        'absent_days_back_number' => $absent_days_back_number,
                        'absent_days_back_amount' => $absent_days_back_amount,
                        'deducted_days_restored_number' => $deducted_days_restored_number,
                        'deducted_days_restored_amount' => $deducted_days_restored_amount,
                        'different_in_salary_amount' => $different_in_salary_amount,
                        'bonus_amount' => $bonus_amount,
                        'allowance_amount' => $allowance_amount,
                        'total_amount_for_addition' => $total_addition,

                        'absent_days_number' => $absent_days_number,
                        'absent_days_amount' => $absent_days_amount,
                        'deducted_days_number' => $deducted_days_number,
                        'deducted_days_amount' => $deducted_days_amount,
                        'salary_deduction_amount' => $salary_deduction_amount,
                        'others_salary_deduction_amount' => $others_salary_deduction_amount,
                        'medical_insurance_deduction_amount' => $medical_insurance_deduction_amount,
                        'monthly_loan_deduction_amount' => $monthly_loan_deduction_amount,
                        'permanent_loan_deduction_amount' => $permanent_loan_deduction_amount,
                        'penalty_deduction_amount' => $penalty_deduction_amount,
                        'total_amount_for_deduction' => $total_deduction,

                        'final_total_amount' => $final_total_amount,
                        
                        'company_id' => $company_id,
                        'added_by' => Auth::id(),
                        'notes' => $request->notes ?: 'تسوية رواتب مؤرشفة',
                    ];

                    $insertData = MainSalaryEmployeeSettlement::create($dataToInsert);

                    if ($insertData) {
                        // Reflect changes on the archived MainSalaryEmployee record
                        $this->updateArchivedSalary(
                            $request->employee_id,
                            $request->finance_monthly_calendar_id,
                            $company_id,
                            $total_addition,
                            $total_deduction,
                            $final_total_amount
                        );

                        return response()->json(['status' => 'true', 'message' => 'تم إضافة تسوية الراتب وتطبيقها بنجاح.']);
                    }

                    return response()->json(['status' => 'false', 'message' => 'عفواً، فشل إضافة التسوية.']);
                });
            } catch (\Exception $e) {
                return response()->json(['status' => 'false', 'message' => 'عفواً، حدث خطأ: ' . $e->getMessage()]);
            }
        }
    }

    /**
     * Retrieve a settlement for editing.
     */
    public function edit(Request $request)
    {
        if ($request->ajax()) {
            $company_id = Auth::user()->company_id;
            $settlement = MainSalaryEmployeeSettlement::with(['employee', 'addedBy', 'updatedBy'])
                ->where('company_id', $company_id)
                ->where('id', $request->id)
                ->first();

            if ($settlement) {
                return response()->json([
                    'status' => 'true',
                    'settlement' => $settlement
                ]);
            }

            return response()->json(['status' => 'false', 'message' => 'عفواً، السجل غير موجود.']);
        }
    }

    /**
     * Update an existing settlement.
     */
    public function update(Request $request)
    {
        if ($request->ajax()) {
            $company_id = Auth::user()->company_id;
            $settlement = MainSalaryEmployeeSettlement::where('company_id', $company_id)
                ->where('id', $request->id)
                ->first();

            if (empty($settlement)) {
                return response()->json(['status' => 'false', 'message' => 'عفواً، السجل غير موجود.']);
            }

            try {
                return DB::transaction(function () use ($request, $settlement, $company_id) {
                    $employee_per_day_salary = (float)$settlement->employee_per_day_salary;

                    // Calculate new addition & deduction totals
                    $working_days_number = (float)$request->input('working_days_number', 0);
                    $working_days_amount = $working_days_number * $employee_per_day_salary;

                    $extra_working_days_number = (float)$request->input('extra_working_days_number', 0);
                    $extra_working_days_amount = $extra_working_days_number * $employee_per_day_salary;

                    $absent_days_back_number = (float)$request->input('absent_days_back_number', 0);
                    $absent_days_back_amount = $absent_days_back_number * $employee_per_day_salary;

                    $deducted_days_restored_number = (float)$request->input('deducted_days_restored_number', 0);
                    $deducted_days_restored_amount = $deducted_days_restored_number * $employee_per_day_salary;

                    $different_in_salary_amount = (float)$request->input('different_in_salary_amount', 0);
                    $bonus_amount = (float)$request->input('bonus_amount', 0);
                    $allowance_amount = (float)$request->input('allowance_amount', 0);

                    $new_total_addition = $working_days_amount + $extra_working_days_amount + $absent_days_back_amount + $deducted_days_restored_amount + $different_in_salary_amount + $bonus_amount + $allowance_amount;

                    $absent_days_number = (float)$request->input('absent_days_number', 0);
                    $absent_days_amount = $absent_days_number * $employee_per_day_salary;

                    $deducted_days_number = (float)$request->input('deducted_days_number', 0);
                    $deducted_days_amount = $deducted_days_number * $employee_per_day_salary;

                    $salary_deduction_amount = (float)$request->input('salary_deduction_amount', 0);
                    $others_salary_deduction_amount = (float)$request->input('others_salary_deduction_amount', 0);
                    $medical_insurance_deduction_amount = (float)$request->input('medical_insurance_deduction_amount', 0);
                    $monthly_loan_deduction_amount = (float)$request->input('monthly_loan_deduction_amount', 0);
                    $permanent_loan_deduction_amount = (float)$request->input('permanent_loan_deduction_amount', 0);
                    $penalty_deduction_amount = (float)$request->input('penalty_deduction_amount', 0);

                    $new_total_deduction = $absent_days_amount + $deducted_days_amount + $salary_deduction_amount + $others_salary_deduction_amount + $medical_insurance_deduction_amount + $monthly_loan_deduction_amount + $permanent_loan_deduction_amount + $penalty_deduction_amount;

                    $new_final_total_amount = $new_total_addition - $new_total_deduction;

                    // Calculate Delta for archived salary updates
                    $deltaAddition = $new_total_addition - $settlement->total_amount_for_addition;
                    $deltaDeduction = $new_total_deduction - $settlement->total_amount_for_deduction;
                    $deltaFinal = $new_final_total_amount - $settlement->final_total_amount;

                    // Update settlement record
                    $settlement->update([
                        'working_days_number' => $working_days_number,
                        'working_days_amount' => $working_days_amount,
                        'extra_working_days_number' => $extra_working_days_number,
                        'extra_working_days_amount' => $extra_working_days_amount,
                        'absent_days_back_number' => $absent_days_back_number,
                        'absent_days_back_amount' => $absent_days_back_amount,
                        'deducted_days_restored_number' => $deducted_days_restored_number,
                        'deducted_days_restored_amount' => $deducted_days_restored_amount,
                        'different_in_salary_amount' => $different_in_salary_amount,
                        'bonus_amount' => $bonus_amount,
                        'allowance_amount' => $allowance_amount,
                        'total_amount_for_addition' => $new_total_addition,

                        'absent_days_number' => $absent_days_number,
                        'absent_days_amount' => $absent_days_amount,
                        'deducted_days_number' => $deducted_days_number,
                        'deducted_days_amount' => $deducted_days_amount,
                        'salary_deduction_amount' => $salary_deduction_amount,
                        'others_salary_deduction_amount' => $others_salary_deduction_amount,
                        'medical_insurance_deduction_amount' => $medical_insurance_deduction_amount,
                        'monthly_loan_deduction_amount' => $monthly_loan_deduction_amount,
                        'permanent_loan_deduction_amount' => $permanent_loan_deduction_amount,
                        'penalty_deduction_amount' => $penalty_deduction_amount,
                        'total_amount_for_deduction' => $new_total_deduction,

                        'final_total_amount' => $new_final_total_amount,
                        'notes' => $request->notes,
                        'updated_by' => Auth::id(),
                    ]);

                    // Apply delta values to archived salary
                    $this->updateArchivedSalary(
                        $settlement->employee_id,
                        $settlement->finance_monthly_calendar_id,
                        $company_id,
                        $deltaAddition,
                        $deltaDeduction,
                        $deltaFinal
                    );

                    return response()->json(['status' => 'true', 'message' => 'تم تحديث تسوية الراتب وتعديل الأثر المالي بنجاح.']);
                });
            } catch (\Exception $e) {
                return response()->json(['status' => 'false', 'message' => 'عفواً، حدث خطأ أثناء التحديث: ' . $e->getMessage()]);
            }
        }
    }

    /**
     * Delete a settlement.
     */
    public function destroy(Request $request)
    {
        if ($request->ajax()) {
            $company_id = Auth::user()->company_id;
            $settlement = MainSalaryEmployeeSettlement::where('company_id', $company_id)
                ->where('id', $request->id)
                ->first();

            if (empty($settlement)) {
                return response()->json(['status' => 'false', 'message' => 'عفواً، السجل غير موجود.']);
            }

            try {
                return DB::transaction(function () use ($settlement, $company_id) {
                    // Delas are negative of current totals
                    $deltaAddition = -$settlement->total_amount_for_addition;
                    $deltaDeduction = -$settlement->total_amount_for_deduction;
                    $deltaFinal = -$settlement->final_total_amount;

                    // Remove settlement
                    $settlement->delete();

                    // Apply reverse values to archived salary
                    $this->updateArchivedSalary(
                        $settlement->employee_id,
                        $settlement->finance_monthly_calendar_id,
                        $company_id,
                        $deltaAddition,
                        $deltaDeduction,
                        $deltaFinal
                    );

                    return response()->json(['status' => 'true', 'message' => 'تم حذف تسوية الراتب وعكس أثرها المالي بنجاح.']);
                });
            } catch (\Exception $e) {
                return response()->json(['status' => 'false', 'message' => 'عفواً، حدث خطأ أثناء الحذف: ' . $e->getMessage()]);
            }
        }
    }

    /**
     * Search settlements inside a month.
     */
    public function ajaxSearch(Request $request)
    {
        if ($request->ajax()) {
            $company_id = Auth::user()->company_id;
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

            if ($is_archived_search === null || $is_archived_search === '') {
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

            $mainSalaryEmployeeSettlements = MainSalaryEmployeeSettlement::with([
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

            return view('admin.mainSalaryRecordSettlement.ajaxSearch', ['mainSalaryEmployeeSettlements' => $mainSalaryEmployeeSettlements]);
        }
    }

    /**
     * Print settlement records for search.
     */
    public function printSearch(Request $request)
    {
        $company_id = Auth::user()->company_id;
        $employee_id_search = $request->employee_id_search;
        $is_archived_search = $request->is_archived_search;
        $finance_monthly_calendar_id = $request->finance_monthly_calendar_id_search;

        $financeMonthlyCalendar = FinanceMonthlyCalendar::with('month')
            ->where('company_id', $company_id)
            ->where('id', $finance_monthly_calendar_id)
            ->first();

        if (empty($financeMonthlyCalendar)) {
            return redirect()->route('admin.main-salary-employee-settlements.index')->with('error', 'عفواً، غير قادر على الوصول إلى بيانات الشهر المالي.');
        }

        if (empty($employee_id_search)) {
            $field1 = "id";
            $operator1 = ">=";
            $value1 = 0;
        } else {
            $field1 = "employee_id";
            $operator1 = "=";
            $value1 = $employee_id_search;
        }

        if ($is_archived_search === null || $is_archived_search === '') {
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

        $mainSalaryEmployeeSettlements = MainSalaryEmployeeSettlement::with([
            'employee',
            'financeMonthlyCalendar',
            'addedBy',
            'updatedBy'
        ])
            ->where('company_id', $company_id)
            ->where('finance_monthly_calendar_id', $finance_monthly_calendar_id)
            ->where($where)
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.mainSalaryRecordSettlement.print_search', [
            'financeMonthlyCalendar' => $financeMonthlyCalendar,
            'mainSalaryEmployeeSettlements' => $mainSalaryEmployeeSettlements
        ]);
    }

    /**
     * Apply delta to the archived salary.
     */
    private function updateArchivedSalary($employee_id, $finance_monthly_calendar_id, $company_id, $deltaAddition, $deltaDeduction, $deltaFinal)
    {
        $mainSalary = MainSalaryEmployee::where('employee_id', $employee_id)
            ->where('finance_monthly_calendar_id', $finance_monthly_calendar_id)
            ->where('company_id', $company_id)
            ->where('is_archived', 1)
            ->first();

        if ($mainSalary) {
            $mainSalary->total_benefits += $deltaAddition;
            $mainSalary->total_deductions += $deltaDeduction;
            $mainSalary->employee_net_salary += $deltaFinal;
            
            // Adjust the payout amount for the archived salary
            $mainSalary->archive_settlement_amount += $deltaFinal;

            // Update status type based on new net salary
            $net = (float)$mainSalary->employee_net_salary;
            if ($net > 0) {
                $mainSalary->archive_status_type = 1; // دائن
            } elseif ($net < 0) {
                $mainSalary->archive_status_type = 2; // مدين
            } else {
                $mainSalary->archive_status_type = 3; // صافي
            }

            // Adjust is_disbursed
            if ($net >= 0 && $mainSalary->archive_settlement_amount > 0) {
                $mainSalary->is_disbursed = 1;
            } else {
                $mainSalary->is_disbursed = 0;
            }

            $mainSalary->save();
        }
    }
}
