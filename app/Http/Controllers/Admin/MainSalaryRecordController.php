<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\FinanceMonthlyCalendar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MainSalaryRecordController extends Controller
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
        return view('admin.mainSalaryRecord.index', ['financeMonthlyCalendars' => $financeMonthlyCalendars]);
    }
    public function openMonth($id)
    {
        $company_id = Auth::user()->company_id;
        $financeMonthlyCalendar = getColsWhere(FinanceMonthlyCalendar::class, ['financeCalendar'],['*'], ['id' => $id, 'company_id' => $company_id]);
        if (!$financeMonthlyCalendar) {
            return redirect()->back()->with('error', 'عذراً، الشهر المالى غير موجود');
        }
        if ($financeMonthlyCalendar->financeCalendar->status != 1) {
            return redirect()->back()->with('error', 'عذراً، السنه المالية مغلقه');
        }
        if ($financeMonthlyCalendar->status == 1) {
            return redirect()->back()->with('error', 'عذراً، الشهر المالى مفتوح بالفعل');
        }
        if ($financeMonthlyCalendar->status == 2) {
            return redirect()->back()->with('error', 'عذراً، الشهر المالى مغلق و مؤرشف من قبل');
        }

        $total_opened_months = get_count_where(FinanceMonthlyCalendar::class, ['company_id' => $company_id, 'status' => '1']);
        if ($total_opened_months > 0) {
            return redirect()->back()->with('error', 'عذراً، يوجد شهر المالى مفتوح بالفعل حاليا');
        }
        $total_prev_months_waiting_to_open = FinanceMonthlyCalendar::where(['company_id' => $company_id, 'status' => '0', 'finance_yr' => $financeMonthlyCalendar->finance_yr])->where('month_id', '<', $financeMonthlyCalendar->month_id)->count();
        if ($total_prev_months_waiting_to_open > 0) {
            return redirect()->back()->with('error', 'عذراً، يوجد أشهر مالية سابقة معلقة و فى انتظار للفتح');
        }

        $dataToUpdate = ['status' => 1];
        $dataToUpdate['updated_by'] = Auth::id();
        $flag = update($financeMonthlyCalendar, $dataToUpdate);
        if (!$flag) {
            return redirect()->back()->with('error', 'عذراً، حدث خطأ أثناء فتح الشهر المالى');
        }else{
            $all_employees = getColsWhereP(Employee::class,[''], ['*',], ['company_id' => $company_id ,'employment_status' => 1],'employee_code');
            if(!$all_employees) {
                foreach ($all_employees as $employee) {
                    $dataToUpdate['finance_monthly_calendars_id'] = $id;
                    $dataToUpdate['employees_id '] = $all_employees->id;
                    

                }
            }
        }
        return redirect()->back()->with('success', 'تم فتح الشهر المالى بنجاح');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.mainSalaryRecord.create');
    }

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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
