<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DirectBonusRequest;
use App\Http\Requests\DirectBonusUpdateRequest;
use App\Models\DirectBonus;
use App\Models\Employee;
use App\Models\FinanceMonthlyCalendar;
use App\Models\Bonus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DirectBonusController extends Controller
{
    public function index()
    {
        $company_id = Auth::user()->company_id;
        $employees = Employee::where('company_id', $company_id)->orderBy('name', 'asc')->get();
        $financeMonthlyCalendars = FinanceMonthlyCalendar::with('month')
            ->where('company_id', $company_id)
            ->orderBy('id', 'desc')
            ->get();

        $directBonuses = DirectBonus::with(['employee', 'financeMonthlyCalendar.month', 'bonusType', 'addedBy', 'updatedBy'])
            ->where('company_id', $company_id)
            ->orderBy('id', 'desc')
            ->paginate(PAGEINATION_COUNTER);

        return view('admin.directBonus.index', [
            'directBonuses' => $directBonuses,
            'employees' => $employees,
            'financeMonthlyCalendars' => $financeMonthlyCalendars,
        ]);
    }

    public function create()
    {
        $company_id = Auth::user()->company_id;
        $activeMonth = FinanceMonthlyCalendar::with('month')
            ->where('company_id', $company_id)
            ->where('status', 1)
            ->first();

        if (empty($activeMonth)) {
            return redirect()->route('admin.direct-bonuses.index')->with('error', 'عفواً، لا يوجد شهر مالي مفتوح حالياً لتسجيل المكافآت عليه.');
        }

        $employees = Employee::where('company_id', $company_id)->orderBy('name', 'asc')->get();
        $bonusTypes = Bonus::where('company_id', $company_id)->where('status', 1)->orderBy('name', 'asc')->get();

        return view('admin.directBonus.create', [
            'employees' => $employees,
            'activeMonth' => $activeMonth,
            'bonusTypes' => $bonusTypes,
        ]);
    }

    public function store(DirectBonusRequest $request)
    {
        try {
            $company_id = Auth::user()->company_id;

            $activeMonth = FinanceMonthlyCalendar::where('company_id', $company_id)
                ->where('id', $request->finance_monthly_calendar_id)
                ->where('status', 1)
                ->first();

            if (empty($activeMonth)) {
                return redirect()->back()->with('error', 'عفواً، الشهر المالي المحدد مغلق أو غير موجود.')->withInput();
            }

            $validated = $request->validated();
            $validated['added_by'] = Auth::id();
            $validated['company_id'] = $company_id;

            insert(DirectBonus::class, $validated);

            return redirect()->route('admin.direct-bonuses.index')->with('success', 'تم تسجيل المكافأة المباشرة بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تسجيل المكافأة: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $company_id = Auth::user()->company_id;
        $directBonus = getColsWhereRow(DirectBonus::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
        if (!$directBonus) {
            return redirect()->route('admin.direct-bonuses.index')->with('error', 'المكافأة المباشرة غير موجودة');
        }

        $activeMonth = FinanceMonthlyCalendar::with('month')
            ->where('company_id', $company_id)
            ->where('id', $directBonus->finance_monthly_calendar_id)
            ->first();

        $employees = Employee::where('company_id', $company_id)->orderBy('name', 'asc')->get();
        $bonusTypes = Bonus::where('company_id', $company_id)->where('status', 1)->orderBy('name', 'asc')->get();

        return view('admin.directBonus.update', [
            'directBonus' => $directBonus,
            'employees' => $employees,
            'activeMonth' => $activeMonth,
            'bonusTypes' => $bonusTypes,
        ]);
    }

    public function update(DirectBonusUpdateRequest $request, $id)
    {
        try {
            $company_id = Auth::user()->company_id;
            $directBonus = getColsWhereRow(DirectBonus::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
            if (!$directBonus) {
                return redirect()->route('admin.direct-bonuses.index')->with('error', 'المكافأة المباشرة غير موجودة');
            }

            $activeMonth = FinanceMonthlyCalendar::where('company_id', $company_id)
                ->where('id', $request->finance_monthly_calendar_id)
                ->where('status', 1)
                ->first();

            if (empty($activeMonth)) {
                return redirect()->back()->with('error', 'عفواً، لا يمكن تعديل المكافأة لأن الشهر المالي مغلق أو غير موجود.')->withInput();
            }

            $validated = $request->validated();
            $validated['updated_by'] = Auth::id();
            update($directBonus, $validated);

            return redirect()->route('admin.direct-bonuses.index')->with('success', 'تم تحديث المكافأة المباشرة بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء التحديث: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $company_id = Auth::user()->company_id;
            $directBonus = getColsWhereRow(DirectBonus::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
            if (!$directBonus) {
                return redirect()->route('admin.direct-bonuses.index')->with('error', 'المكافأة المباشرة غير موجودة');
            }

            $activeMonth = FinanceMonthlyCalendar::where('company_id', $company_id)
                ->where('id', $directBonus->finance_monthly_calendar_id)
                ->where('status', 1)
                ->first();

            if (empty($activeMonth)) {
                return redirect()->route('admin.direct-bonuses.index')->with('error', 'عفواً، لا يمكن حذف المكافأة لأن شهرها المالي مغلق بالفعل.');
            }

            destroy($directBonus);
            return redirect()->route('admin.direct-bonuses.index')->with('success', 'تم حذف المكافأة المباشرة بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف المكافأة: ' . $e->getMessage());
        }
    }

    public function ajaxSearch(Request $request)
    {
        if ($request->ajax()) {
            $company_id = Auth::user()->company_id;
            $employee_id = $request->employee_id_search;
            $calendar_id = $request->finance_monthly_calendar_id_search;

            $query = DirectBonus::with(['employee', 'financeMonthlyCalendar.month', 'bonusType', 'addedBy', 'updatedBy'])
                ->where('company_id', $company_id);

            if ($employee_id !== null && $employee_id !== '') {
                $query->where('employee_id', $employee_id);
            }

            if ($calendar_id !== null && $calendar_id !== '') {
                $query->where('finance_monthly_calendar_id', $calendar_id);
            }

            $directBonuses = $query->orderBy('id', 'desc')->paginate(PAGEINATION_COUNTER);

            return view('admin.directBonus.ajaxSearch', ['directBonuses' => $directBonuses]);
        }
    }
}
