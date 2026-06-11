<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BloodGroupRequest;
use App\Models\BloodGroup;
use Illuminate\Support\Facades\Auth;

class BloodGroupController extends Controller
{
    public function index()
    {
        $company_id = Auth::user()->company_id;
        $bloodGroups = getColsWhereP(BloodGroup::class, ['addedBy', 'updatedBy'], ['*'], ['company_id' => $company_id]);
        $bloodGroups->getCollection()->loadCount('employees');
        return view('admin.blood_group.index', ['bloodGroups' => $bloodGroups]);
    }

    public function create()
    {
        return view('admin.blood_group.create');
    }

    public function store(BloodGroupRequest $request)
    {
        try {
            $company_id = Auth::user()->company_id;
            $checkIf = getColsWhereRow(BloodGroup::class, ['id'], ['company_id' => $company_id, 'name' => $request->name]);
            if ($checkIf) {
                return redirect()->back()->with('error', 'فصيلة الدم موجودة بالفعل')->withInput();
            }

            $validated = $request->validated();
            $validated['added_by'] = Auth::id();
            $validated['updated_by'] = Auth::id();
            $validated['company_id'] = $company_id;
            insert(BloodGroup::class, $validated);

            return redirect()->route('admin.blood-groups.index')->with('success', 'تم إنشاء فصيلة الدم بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إنشاء فصيلة الدم ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $company_id = Auth::user()->company_id;
        $bloodGroup = getColsWhereRow(BloodGroup::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
        if (!$bloodGroup) {
            return redirect()->route('admin.blood-groups.index')->with('error', 'فصيلة الدم غير موجودة');
        }
        return view('admin.blood_group.update', ['bloodGroup' => $bloodGroup]);
    }

    public function update(BloodGroupRequest $request, $id)
    {
        try {
            $company_id = Auth::user()->company_id;
            $bloodGroup = getColsWhereRow(BloodGroup::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
            if (!$bloodGroup) {
                return redirect()->route('admin.blood-groups.index')->with('error', 'فصيلة الدم غير موجودة');
            }

            $checkIf = BloodGroup::select('id')
                ->where(['company_id' => $company_id, 'name' => $request->name])
                ->where('id', '!=', $id)
                ->first();
            if ($checkIf) {
                return redirect()->back()->with('error', 'فصيلة الدم موجودة بالفعل')->withInput();
            }

            $validated = $request->validated();
            $validated['updated_by'] = Auth::id();
            $validated['company_id'] = $company_id;
            update($bloodGroup, $validated);

            return redirect()->route('admin.blood-groups.index')->with('success', 'تم تحديث فصيلة الدم بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث فصيلة الدم ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $company_id = Auth::user()->company_id;
            $bloodGroup = getColsWhereRow(BloodGroup::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
            if (!$bloodGroup) {
                return redirect()->route('admin.blood-groups.index')->with('error', 'فصيلة الدم غير موجودة');
            }
            if ($bloodGroup->employees()->exists()) {
                return redirect()->route('admin.blood-groups.index')->with('error', 'لا يمكن حذف فصيلة الدم لوجود موظفين مرتبطة بها');
            }
            destroy($bloodGroup);
            return redirect()->route('admin.blood-groups.index')->with('success', 'تم حذف فصيلة الدم بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف فصيلة الدم ' . $e->getMessage());
        }
    }
}
