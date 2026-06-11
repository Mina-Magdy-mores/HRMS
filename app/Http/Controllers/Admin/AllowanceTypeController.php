<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AllowanceTypeRequest;
use App\Http\Requests\AllowanceTypeUpdateRequest;
use App\Models\AllowanceType;
use Illuminate\Support\Facades\Auth;

class AllowanceTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $company_id = Auth::user()->company_id;
        $allowanceTypes = getColsWhereP(AllowanceType::class, ['addedBy', 'updatedBy'], ['*'], ['company_id' => $company_id]);
        $allowanceTypes->getCollection()->loadCount(['employeeFixedAllowances', 'mainSalaryEmployeeAllowances']);
        return view('admin.allowanceType.index', ['allowanceTypes' => $allowanceTypes]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.allowanceType.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AllowanceTypeRequest $request)
    {
        try {
            $company_id = Auth::user()->company_id;
            $checkIf = getColsWhereRow(AllowanceType::class, ['id'], ['company_id' => $company_id, 'name' => $request->name]);
            if ($checkIf) {
                return redirect()->back()->with('error', 'النوع موجودة بالفعل')->withInput();
            }

            $validated = $request->validated();
            $validated['added_by'] = Auth::id();
            $validated['company_id'] = $company_id;
            insert(AllowanceType::class, $validated);

            return redirect()->route('admin.allowance-types.index')->with('success', 'تم إنشاء النوع بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إنشاء النوع ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $company_id = Auth::user()->company_id;
        $allowanceType = getColsWhereRow(AllowanceType::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
        if (!$allowanceType) {
            return redirect()->route('admin.allowance-types.index')->with('error', 'النوع غير موجود');
        }
        return view('admin.allowanceType.update', ['allowanceType' => $allowanceType]);
    }

    public function update(AllowanceTypeUpdateRequest $request, $id)
    {
        try {
            $company_id = Auth::user()->company_id;
            $allowanceType = getColsWhereRow(AllowanceType::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
            if (!$allowanceType) {
                return redirect()->route('admin.allowance-types.index')->with('error', 'النوع غير موجود');
            }

            $validated = $request->validated();
            $validated['updated_by'] = Auth::id();
            $validated['company_id'] = $company_id;
            update($allowanceType, $validated);

            return redirect()->route('admin.allowance-types.index')->with('success', 'تم تحديث النوع بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث النوع ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $company_id = Auth::user()->company_id;
            $allowanceType = getColsWhereRow(AllowanceType::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
            if (!$allowanceType) {
                return redirect()->route('admin.allowance-types.index')->with('error', 'النوع غير موجود');
            }
            if ($allowanceType->employeeFixedAllowances()->exists() || $allowanceType->mainSalaryEmployeeAllowances()->exists()) {
                return redirect()->route('admin.allowance-types.index')->with('error', 'لا يمكن حذف هذا البدل لارتباطه بموظفين أو بسجلات رواتب');
            }
            destroy($allowanceType);
            return redirect()->route('admin.allowance-types.index')->with('success', 'تم حذف النوع بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف النوع ' . $e->getMessage());
        }
    }
}
