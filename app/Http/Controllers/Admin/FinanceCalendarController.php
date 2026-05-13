<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\FinanceCalendarsRequest;
use App\Models\FinanceCalendar;
use App\Models\FinanceMonthlyCalendar;
use App\Models\Month;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FinanceCalendarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $financeCalendars = FinanceCalendar::with(['addedBy', 'updatedBy'])->orderBy('finance_yr', 'desc')->paginate(PAGEINATION_COUNTER);
        return view('admin.financeCalendar.index', compact('financeCalendars'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.financeCalendar.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FinanceCalendarsRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $validated = $request->validated();
                $validated['added_by'] = Auth::user()->id;
                $validated['updated_by'] = Auth::user()->id;
                $validated['company_id'] = Auth::user()->company_id;
                $new_finance_year =  FinanceCalendar::create($validated);
                if ($new_finance_year) {
                    $startDate = new DateTime($validated['start_date']);
                    $endDate = new DateTime($validated['end_date']);
                    $interval = new DateInterval('P1M');
                    $period = new DatePeriod($startDate, $interval, $endDate);
                    foreach ($period as $key => $date) {
                        $dataMonth['financeCalendar_id'] = $new_finance_year->id;
                        $monthName = $date->format('F');
                        $monthId = Month::where('name_en', $monthName)->first()?->id;
                        if (!$monthId) {
                            throw new \Exception('Month not found');
                        }
                        $dataMonth['month_id'] = $monthId;
                        $dataMonth['finance_yr'] = $new_finance_year->finance_yr;
                        if ($key == 0) {
                            $dataMonth['start_date'] =  $date->format('Y-m-d');
                            $dataMonth['start_date_for_calculation'] =  $date->format('Y-m-d');
                        } else {
                            $dataMonth['start_date'] =  $date->format('Y-m-01');
                            $dataMonth['start_date_for_calculation'] =  $date->format('Y-m-01');
                        }
                        $dataMonth['end_date'] =  $date->format('Y-m-t');
                        $dataMonth['end_date_for_calculation'] =  $date->format('Y-m-t');
                        $dataMonth['year_and_month'] = $date->format('Y-m');
                        $days = strtotime($dataMonth['end_date']) - strtotime($dataMonth['start_date']);
                        $dataMonth['number_of_days'] = ceil($days / (60 * 60 * 24) + 1);
                        $dataMonth['company_id'] =  Auth::user()->company_id;
                        $dataMonth['added_by'] = Auth::user()->id;
                        $dataMonth['updated_by'] = Auth::user()->id;
                        FinanceMonthlyCalendar::create($dataMonth);
                    }
                }
            });

            return redirect()
                ->route('admin.financeCalendars.index')
                ->with('success', 'تم الحفظ بنجاح');
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.financeCalendars.create')
                ->with('error', 'حدث خطا ما برجاء المحاوله لاحقا' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(FinanceCalendar $financeCalendar)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FinanceCalendar $financeCalendar)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FinanceCalendar $financeCalendar)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FinanceCalendar $financeCalendar)
    {
        if ($financeCalendar->state == 1) {
            return redirect()
                ->route('admin.financeCalendars.index')
                ->with('error', 'لا يمكن حذف السنة المالية المفعلة');
        }
        try {
            $financeCalendar->delete();
            return redirect()
                ->route('admin.financeCalendars.index')
                ->with('success', 'تم الحذف بنجاح');
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.FinanceCalendars.index')
                ->with('error', 'حدث خطا ما برجاء المحاوله لاحقا' . $e->getMessage());
        }
    }
    public function showMonths(Request $request, string $financeCalendar)
    {
        $financeCalendar = FinanceCalendar::find($financeCalendar);
        if (!$financeCalendar) {
            return redirect()
                ->route('admin.financeCalendars.index')
                ->with('error', 'حدث خطا ما برجاء المحاوله لاحقا');
        }
        $finanaceMonthlyCalendars = $financeCalendar->financeMonthlyCalendars;
      return view('admin.financeCalendar.months', compact('finanaceMonthlyCalendars'));

    }
}
