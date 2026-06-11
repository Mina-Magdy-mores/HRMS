<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\GovernorateRequest;
use App\Models\Country;
use App\Models\Governorate;
use Illuminate\Support\Facades\Auth;

class GovernorateController extends Controller
{
    public function index()
    {
        $company_id = Auth::user()->company_id;
        $governorates = getColsWhereP(Governorate::class, ['addedBy', 'updatedBy', 'country'], ['*'], ['company_id' => $company_id]);
        $governorates->getCollection()->loadCount('employees');
        return view('admin.governorate.index', ['governorates' => $governorates]);
    }

    public function create()
    {
        $company_id = Auth::user()->company_id;
        $countries = get_cols_where(Country::class, ['id', 'name'], ['company_id' => $company_id, 'status' => 1]);
        return view('admin.governorate.create', compact('countries'));
    }

    public function store(GovernorateRequest $request)
    {
        try {
            $company_id = Auth::user()->company_id;
            $checkIf = getColsWhereRow(Governorate::class, ['id'], ['company_id' => $company_id, 'name' => $request->name]);
            if ($checkIf) {
                return redirect()->back()->with('error', 'المحافظة موجودة بالفعل')->withInput();
            }

            $validated = $request->validated();
            $validated['added_by'] = Auth::id();
            $validated['updated_by'] = Auth::id();
            $validated['company_id'] = $company_id;
            insert(Governorate::class, $validated);

            return redirect()->route('admin.governorates.index')->with('success', 'تم إنشاء المحافظة بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إنشاء المحافظة ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $company_id = Auth::user()->company_id;
        $governorate = getColsWhereRow(Governorate::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
        if (!$governorate) {
            return redirect()->route('admin.governorates.index')->with('error', 'المحافظة غير موجودة');
        }
        $countries = get_cols_where(Country::class, ['id', 'name'], ['company_id' => $company_id, 'status' => 1]);
        return view('admin.governorate.update', compact('governorate', 'countries'));
    }

    public function update(GovernorateRequest $request, $id)
    {
        try {
            $company_id = Auth::user()->company_id;
            $governorate = getColsWhereRow(Governorate::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
            if (!$governorate) {
                return redirect()->route('admin.governorates.index')->with('error', 'المحافظة غير موجودة');
            }

            $checkIf = Governorate::select('id')
                ->where(['company_id' => $company_id, 'name' => $request->name])
                ->where('id', '!=', $id)
                ->first();
            if ($checkIf) {
                return redirect()->back()->with('error', 'المحافظة موجودة بالفعل')->withInput();
            }

            $validated = $request->validated();
            $validated['updated_by'] = Auth::id();
            $validated['company_id'] = $company_id;
            update($governorate, $validated);

            return redirect()->route('admin.governorates.index')->with('success', 'تم تحديث المحافظة بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث المحافظة ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $company_id = Auth::user()->company_id;
            $governorate = getColsWhereRow(Governorate::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
            if (!$governorate) {
                return redirect()->route('admin.governorates.index')->with('error', 'المحافظة غير موجودة');
            }
            if ($governorate->employees()->exists()) {
                return redirect()->route('admin.governorates.index')->with('error', 'لا يمكن حذف هذه المحافظة لوجود موظفين مرتبطة بها');
            }
            destroy($governorate);
            return redirect()->route('admin.governorates.index')->with('success', 'تم حذف المحافظة بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف المحافظة ' . $e->getMessage());
        }
    }
}
