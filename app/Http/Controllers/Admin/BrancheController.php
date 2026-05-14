<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BrancheRequest;
use App\Models\Branche;
use Auth;
use Illuminate\Http\Request;

class BrancheController extends Controller
{
    public function index()
    {
        $company_id = Auth::user()->company_id;

        $branches = getColsWhereP(Branche::class, ['createdBy'], ['*'], ['company_id' => $company_id], 'id', 'asc', PAGEINATION_COUNTER);
        return view('admin.branches.index', compact('branches'));
    }
    public function create()
    {
        return view('admin.branches.create');
    }
    public function store(BrancheRequest $request)
    {
        $company_id = Auth::user()->company_id;
        $checkIfExist = getColsWhereRow(Branche::class, ['id'], ['name' => $request->name, 'company_id' => $company_id]);
        if (!empty($checkIfExist)) {
            return redirect()->back()->with('error', 'اسم الفرع موجود مسبقا')->withInput();
        }
        try {
            $validated = $request->validated();
            $validated['created_by'] = Auth::user()->id;
            $validated['updated_by'] = Auth::user()->id;
            $validated['company_id'] = $company_id;
            insert(Branche::class, $validated);
            return redirect()->route('admin.branches.index')->with('success', 'تم اضافة الفرع بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطا ما برجاء المحاوله لاحقا ' . $e->getMessage())->withInput();
        }
    }
    public function edit($id)
    {
        $company_id = Auth::user()->company_id;
        $branche = getColsWhereRow(Branche::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
        if (empty($branche)) {
            return redirect()->route('admin.branches.index')->with('error', 'هذا الفرع غير موجود');
        }
        return view('admin.branches.update', compact('branche'));
    }
    public function update(BrancheRequest $request,  $branche)
    {
        $company_id = Auth::user()->company_id;
        $branche = getColsWhereRow(Branche::class, ['*'], ['id' => $branche, 'company_id' => $company_id]);
        if (empty($branche)) {
            return redirect()->route('admin.branches.index')->with('error', 'هذا الفرع غير موجود');
        }
        try {
            $validated = $request->validated();
            $validated['updated_by'] = Auth::user()->id;
            update($branche, $validated);
            return redirect()->route('admin.branches.index')->with('success', 'تم تعديل الفرع بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطا ما برجاء المحاوله لاحقا ' . $e->getMessage())->withInput();
        }
    }
    public function destroy($id)
    {
        $company_id = Auth::user()->company_id;
        $branche = getColsWhereRow(Branche::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
        if (empty($branche)) {
            return redirect()->route('admin.branches.index')->with('error', 'هذا الفرع غير موجود');
        }
        try {
            destroy($branche);
            return redirect()->route('admin.branches.index')->with('success', 'تم حذف الفرع بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطا ما برجاء المحاوله لاحقا ' . $e->getMessage());
        }
    }
}
