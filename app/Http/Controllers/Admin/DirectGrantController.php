<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DirectGrantRequest;
use App\Http\Requests\DirectGrantUpdateRequest;
use App\Models\DirectGrant;
use App\Models\Employee;
use App\Models\FinanceMonthlyCalendar;
use App\Models\SalaryGrantType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DirectGrantController extends Controller
{
    public function index()
    {
        $company_id = Auth::user()->company_id;
        $employees = Employee::where('company_id', $company_id)->orderBy('name', 'asc')->get();
        $financeMonthlyCalendars = FinanceMonthlyCalendar::with('month')
            ->where('company_id', $company_id)
            ->orderBy('id', 'desc')
            ->get();

        $directGrants = DirectGrant::with(['employee', 'financeMonthlyCalendar.month', 'grantType', 'addedBy', 'updatedBy'])
            ->where('company_id', $company_id)
            ->orderBy('id', 'desc')
            ->paginate(PAGEINATION_COUNTER);

        return view('admin.directGrant.index', [
            'directGrants' => $directGrants,
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
            return redirect()->route('admin.direct-grants.index')->with('error', 'عفواً، لا يوجد شهر مالي مفتوح حالياً لتسجيل المنح عليه.');
        }

        $employees = Employee::where('company_id', $company_id)->orderBy('name', 'asc')->get();
        $grantTypes = SalaryGrantType::where('company_id', $company_id)->where('status', 1)->orderBy('name', 'asc')->get();

        return view('admin.directGrant.create', [
            'employees' => $employees,
            'activeMonth' => $activeMonth,
            'grantTypes' => $grantTypes,
        ]);
    }

    public function store(DirectGrantRequest $request)
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

            insert(DirectGrant::class, $validated);

            return redirect()->route('admin.direct-grants.index')->with('success', 'تم تسجيل المنحة المباشرة بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تسجيل المنحة: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $company_id = Auth::user()->company_id;
        $directGrant = getColsWhereRow(DirectGrant::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
        if (!$directGrant) {
            return redirect()->route('admin.direct-grants.index')->with('error', 'المنحة المباشرة غير موجودة');
        }

        $activeMonth = FinanceMonthlyCalendar::with('month')
            ->where('company_id', $company_id)
            ->where('id', $directGrant->finance_monthly_calendar_id)
            ->first();

        $employees = Employee::where('company_id', $company_id)->orderBy('name', 'asc')->get();
        $grantTypes = SalaryGrantType::where('company_id', $company_id)->where('status', 1)->orderBy('name', 'asc')->get();

        return view('admin.directGrant.update', [
            'directGrant' => $directGrant,
            'employees' => $employees,
            'activeMonth' => $activeMonth,
            'grantTypes' => $grantTypes,
        ]);
    }

    public function update(DirectGrantUpdateRequest $request, $id)
    {
        try {
            $company_id = Auth::user()->company_id;
            $directGrant = getColsWhereRow(DirectGrant::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
            if (!$directGrant) {
                return redirect()->route('admin.direct-grants.index')->with('error', 'المنحة المباشرة غير موجودة');
            }

            $activeMonth = FinanceMonthlyCalendar::where('company_id', $company_id)
                ->where('id', $request->finance_monthly_calendar_id)
                ->where('status', 1)
                ->first();

            if (empty($activeMonth)) {
                return redirect()->back()->with('error', 'عفواً، لا يمكن تعديل المنحة لأن الشهر المالي مغلق أو غير موجود.')->withInput();
            }

            $validated = $request->validated();
            $validated['updated_by'] = Auth::id();
            update($directGrant, $validated);

            return redirect()->route('admin.direct-grants.index')->with('success', 'تم تحديث المنحة المباشرة بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء التحديث: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $company_id = Auth::user()->company_id;
            $directGrant = getColsWhereRow(DirectGrant::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
            if (!$directGrant) {
                return redirect()->route('admin.direct-grants.index')->with('error', 'المنحة المباشرة غير موجودة');
            }

            $activeMonth = FinanceMonthlyCalendar::where('company_id', $company_id)
                ->where('id', $directGrant->finance_monthly_calendar_id)
                ->where('status', 1)
                ->first();

            if (empty($activeMonth)) {
                return redirect()->route('admin.direct-grants.index')->with('error', 'عفواً، لا يمكن حذف المنحة لأن شهرها المالي مغلق بالفعل.');
            }

            destroy($directGrant);
            return redirect()->route('admin.direct-grants.index')->with('success', 'تم حذف المنحة المباشرة بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف المنحة: ' . $e->getMessage());
        }
    }

    public function ajaxSearch(Request $request)
    {
        if ($request->ajax()) {
            $company_id = Auth::user()->company_id;
            $employee_id = $request->employee_id_search;
            $calendar_id = $request->finance_monthly_calendar_id_search;

            $query = DirectGrant::with(['employee', 'financeMonthlyCalendar.month', 'grantType', 'addedBy', 'updatedBy'])
                ->where('company_id', $company_id);

            if ($employee_id !== null && $employee_id !== '') {
                $query->where('employee_id', $employee_id);
            }

            if ($calendar_id !== null && $calendar_id !== '') {
                $query->where('finance_monthly_calendar_id', $calendar_id);
            }

            $directGrants = $query->orderBy('id', 'desc')->paginate(PAGEINATION_COUNTER);

            return view('admin.directGrant.ajaxSearch', ['directGrants' => $directGrants]);
        }
    }
}
