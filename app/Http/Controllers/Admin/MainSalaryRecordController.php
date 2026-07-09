<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FinanceMonthlyCalendar;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\Finance\SalaryRecordService;

class MainSalaryRecordController extends Controller
{
    use GeneralTrait;

    protected $service;

    public function __construct(SalaryRecordService $service)
    {
        $this->service = $service;
    }

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

    public function openMonth(Request $request, $id)
    {
        try {
            $this->service->openMonth($id, $request->start_date_for_calculation, $request->end_date_for_calculation, Auth::id());
            return redirect()->back()->with('success', 'تم فتح الشهر المالى بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'عذراً، حدث خطأ أثناء فتح الشهر المالى ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function loadOpenMonth(Request $request)
    {
        if ($request->ajax()) {
            $company_id = Auth::user()->company_id;
            $financeMonthlyCalendar = getColsWhere(FinanceMonthlyCalendar::class, ['financeCalendar'], ['*'], ['id' => $request->id, 'company_id' => $company_id]);
            if (!$financeMonthlyCalendar) {
                return redirect()->back()->with('error', 'عذراً، الشهر المالى غير موجود');
            }
            return view('admin.mainSalaryRecord.load_open_month', compact('financeMonthlyCalendar'));
        }
    }
}
