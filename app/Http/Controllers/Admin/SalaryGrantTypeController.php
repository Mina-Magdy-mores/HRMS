<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SalaryGrantTypeRequest;
use App\Http\Requests\SalaryGrantTypeUpdateRequest;
use App\Models\SalaryGrantType;
use Illuminate\Support\Facades\Auth;

class SalaryGrantTypeController extends Controller
{
    public function index()
    {
        $company_id = Auth::user()->company_id;
        $salaryGrantTypes = getColsWhereP(SalaryGrantType::class, ['addedBy', 'updatedBy'], ['*'], ['company_id' => $company_id]);
        $salaryGrantTypes->getCollection()->loadCount('directGrants');
        return view('admin.salaryGrantType.index', ['salaryGrantTypes' => $salaryGrantTypes]);
    }

    public function create()
    {
        return view('admin.salaryGrantType.create');
    }

    public function store(SalaryGrantTypeRequest $request)
    {
        try {
            $company_id = Auth::user()->company_id;
            $checkIf = getColsWhereRow(SalaryGrantType::class, ['id'], ['company_id' => $company_id, 'name' => $request->name]);
            if ($checkIf) {
                return redirect()->back()->with('error', 'المنحة موجودة بالفعل')->withInput();
            }

            $validated = $request->validated();
            $validated['added_by'] = Auth::id();
            $validated['company_id'] = $company_id;
            insert(SalaryGrantType::class, $validated);

            return redirect()->route('admin.salary-grant-types.index')->with('success', 'تم إنشاء نوع المنحة بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إنشاء نوع المنحة ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $company_id = Auth::user()->company_id;
        $salaryGrantType = getColsWhereRow(SalaryGrantType::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
        if (!$salaryGrantType) {
            return redirect()->route('admin.salary-grant-types.index')->with('error', 'نوع المنحة غير موجود');
        }
        return view('admin.salaryGrantType.update', ['salaryGrantType' => $salaryGrantType]);
    }

    public function update(SalaryGrantTypeUpdateRequest $request, $id)
    {
        try {
            $company_id = Auth::user()->company_id;
            $salaryGrantType = getColsWhereRow(SalaryGrantType::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
            if (!$salaryGrantType) {
                return redirect()->route('admin.salary-grant-types.index')->with('error', 'نوع المنحة غير موجود');
            }

            $validated = $request->validated();
            $validated['updated_by'] = Auth::id();
            $validated['company_id'] = $company_id;
            update($salaryGrantType, $validated);

            return redirect()->route('admin.salary-grant-types.index')->with('success', 'تم تحديث نوع المنحة بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث نوع المنحة ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $company_id = Auth::user()->company_id;
            $salaryGrantType = getColsWhereRow(SalaryGrantType::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
            if (!$salaryGrantType) {
                return redirect()->route('admin.salary-grant-types.index')->with('error', 'نوع المنحة غير موجود');
            }
            if ($salaryGrantType->directGrants()->exists()) {
                return redirect()->route('admin.salary-grant-types.index')->with('error', 'لا يمكن حذف هذا النوع لارتباطه بمنح مسجلة لموظفين');
            }
            destroy($salaryGrantType);
            return redirect()->route('admin.salary-grant-types.index')->with('success', 'تم حذف نوع المنحة بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف نوع المنحة ' . $e->getMessage());
        }
    }
}
