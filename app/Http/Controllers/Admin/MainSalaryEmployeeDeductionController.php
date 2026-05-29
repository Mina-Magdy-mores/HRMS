<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FinanceMonthlyCalendar;
use App\Models\MainSalaryEmployeeDeduction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MainSalaryEmployeeDeductionController extends Controller
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
        return view('admin.mainSalaryRecordDeduction.index', ['financeMonthlyCalendars' => $financeMonthlyCalendars]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
            return redirect()->route('admin.main-salary-employee-deductions.index')->with('error', 'عفوا غير قادر للوصول الى بيانات الشهر');
        }

        $mainSalaryEmployeeDeductions = MainSalaryEmployeeDeduction::with([
            'employee',
            'financeMonthlyCalendar.month',
            'addedBy',
            'updatedBy',
            'approvedBy'
        ])
        ->where('company_id', $company_id)
        ->where('finance_monthly_calendar_id', $id)
        ->orderBy('id', 'desc')
        ->get();

        return view('admin.mainSalaryRecordDeduction.show', [
            'financeMonthlyCalendar' => $financeMonthlyCalendar,
            'mainSalaryEmployeeDeductions' => $mainSalaryEmployeeDeductions
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MainSalaryEmployeeDeduction $mainSalaryEmployeeDeduction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MainSalaryEmployeeDeduction $mainSalaryEmployeeDeduction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MainSalaryEmployeeDeduction $mainSalaryEmployeeDeduction)
    {
        //
    }
}
