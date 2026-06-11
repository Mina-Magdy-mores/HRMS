<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeductionTypeRequest;
use App\Http\Requests\DeductionTypeUpdateRequest;
use App\Models\DeductionType;
use Illuminate\Support\Facades\Auth;

class DeductionTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $company_id = Auth::user()->company_id;
        $deductionTypes = getColsWhereP(DeductionType::class, ['addedBy', 'updatedBy'], ['*'], ['company_id' => $company_id]);
        $deductionTypes->getCollection()->loadCount('mainSalaryEmployeeDeductionTypes');
        return view('admin.deductionType.index', ['deductionTypes' => $deductionTypes]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.deductionType.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DeductionTypeRequest $request)
    {
        try {
            $company_id = Auth::user()->company_id;
            $checkIf = getColsWhereRow(DeductionType::class, ['id'], ['company_id' => $company_id, 'name' => $request->name]);
            if ($checkIf) {
                return redirect()->back()->with('error', 'نوع الخصم موجود بالفعل')->withInput();
            }

            $validated = $request->validated();
            $validated['added_by'] = Auth::id();
            $validated['company_id'] = $company_id;
            insert(DeductionType::class, $validated);

            return redirect()->route('admin.deduction-types.index')->with('success', 'تم إنشاء نوع الخصم بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إنشاء نوع الخصم ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $company_id = Auth::user()->company_id;
        $deductionType = getColsWhereRow(DeductionType::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
        if (!$deductionType) {
            return redirect()->route('admin.deduction-types.index')->with('error', 'نوع الخصم غير موجود');
        }
        return view('admin.deductionType.update', ['deductionType' => $deductionType]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DeductionTypeUpdateRequest $request, $id)
    {
        try {
            $company_id = Auth::user()->company_id;
            $deductionType = getColsWhereRow(DeductionType::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
            if (!$deductionType) {
                return redirect()->route('admin.deduction-types.index')->with('error', 'نوع الخصم غير موجود');
            }

            $validated = $request->validated();
            $validated['updated_by'] = Auth::id();
            $validated['company_id'] = $company_id;
            update($deductionType, $validated);

            return redirect()->route('admin.deduction-types.index')->with('success', 'تم تحديث نوع الخصم بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث نوع الخصم ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $company_id = Auth::user()->company_id;
            $deductionType = getColsWhereRow(DeductionType::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
            if (!$deductionType) {
                return redirect()->route('admin.deduction-types.index')->with('error', 'نوع الخصم غير موجود');
            }
            if ($deductionType->mainSalaryEmployeeDeductionTypes()->exists()) {
                return redirect()->route('admin.deduction-types.index')->with('error', 'لا يمكن حذف هذا النوع لارتباطه بخصومات موظفين');
            }
            destroy($deductionType);
            return redirect()->route('admin.deduction-types.index')->with('success', 'تم حذف نوع الخصم بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف نوع الخصم ' . $e->getMessage());
        }
    }
}
