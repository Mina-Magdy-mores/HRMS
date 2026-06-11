<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResignationRequest;
use App\Models\Resignation;
use Illuminate\Support\Facades\Auth;

class ResignationController extends Controller
{
    public function index()
    {
        $company_id = Auth::user()->company_id;
        $resignations = getColsWhereP(Resignation::class, ['addedBy', 'updatedBy'], ['*'], ['company_id' => $company_id]);
        $resignations->getCollection()->loadCount('employees');
        return view('admin.resignations.index', ['resignations' => $resignations]);
    }

    public function create()
    {
        return view('admin.resignations.create');
    }

    public function store(ResignationRequest $request)
    {
        try {
            $company_id = Auth::user()->company_id;
            $checkIf = getColsWhereRow(Resignation::class, ['id'], ['company_id' => $company_id, 'name' => $request->name]);
            if ($checkIf) {
                return redirect()->back()->with('error', 'الاستقالة موجودة بالفعل')->withInput();
            }

            $validated = $request->validated();
            $validated['added_by'] = Auth::id();
            $validated['updated_by'] = Auth::id();
            $validated['company_id'] = $company_id;
            insert(Resignation::class, $validated);

            return redirect()->route('admin.resignations.index')->with('success', 'تم إنشاء الاستقالة بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إنشاء الاستقالة ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $company_id = Auth::user()->company_id;
        $resignation = getColsWhereRow(Resignation::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
        if (!$resignation) {
            return redirect()->route('admin.resignations.index')->with('error', 'الاستقالة غير موجودة');
        }
        return view('admin.resignations.update', ['resignation' => $resignation]);
    }

    public function update(ResignationRequest $request, $id)
    {
        try {
            $company_id = Auth::user()->company_id;
            $resignation = getColsWhereRow(Resignation::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
            if (!$resignation) {
                return redirect()->route('admin.resignations.index')->with('error', 'الاستقالة غير موجودة');
            }

            $checkIf = Resignation::select('id')
                ->where(['company_id' => $company_id, 'name' => $request->name])
                ->where('id', '!=', $id)
                ->first();
            if ($checkIf) {
                return redirect()->back()->with('error', 'الاستقالة موجودة بالفعل')->withInput();
            }

            $validated = $request->validated();
            $validated['updated_by'] = Auth::id();
            $validated['company_id'] = $company_id;
            update($resignation, $validated);

            return redirect()->route('admin.resignations.index')->with('success', 'تم تحديث الاستقالة بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث الاستقالة ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $company_id = Auth::user()->company_id;
            $resignation = getColsWhereRow(Resignation::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
            if (!$resignation) {
                return redirect()->route('admin.resignations.index')->with('error', 'الاستقالة غير موجودة');
            }
            if ($resignation->employees()->exists()) {
                return redirect()->route('admin.resignations.index')->with('error', 'لا يمكن حذف هذه الاستقالة لوجود موظفين مرتبطة بها');
            }
            destroy($resignation);
            return redirect()->route('admin.resignations.index')->with('success', 'تم حذف الاستقالة بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف الاستقالة ' . $e->getMessage());
        }
    }
}
