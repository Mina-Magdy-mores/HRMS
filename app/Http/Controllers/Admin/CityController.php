<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CityRequest;
use App\Models\City;
use App\Models\Governorate;
use Illuminate\Support\Facades\Auth;

class CityController extends Controller
{
    public function index()
    {
        $company_id = Auth::user()->company_id;
        $cities = getColsWhereP(City::class, ['addedBy', 'updatedBy', 'governorate'], ['*'], ['company_id' => $company_id]);
        $cities->getCollection()->loadCount('employees');
        return view('admin.city.index', ['cities' => $cities]);
    }

    public function create()
    {
        $company_id = Auth::user()->company_id;
        $governorates = get_cols_where(Governorate::class, ['id', 'name'], ['company_id' => $company_id, 'status' => 1]);
        return view('admin.city.create', compact('governorates'));
    }

    public function store(CityRequest $request)
    {
        try {
            $company_id = Auth::user()->company_id;
            $checkIf = getColsWhereRow(City::class, ['id'], ['company_id' => $company_id, 'name' => $request->name]);
            if ($checkIf) {
                return redirect()->back()->with('error', 'المدينة موجودة بالفعل')->withInput();
            }

            $validated = $request->validated();
            $validated['added_by'] = Auth::id();
            $validated['updated_by'] = Auth::id();
            $validated['company_id'] = $company_id;
            insert(City::class, $validated);

            return redirect()->route('admin.cities.index')->with('success', 'تم إنشاء المدينة بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إنشاء المدينة ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $company_id = Auth::user()->company_id;
        $city = getColsWhereRow(City::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
        if (!$city) {
            return redirect()->route('admin.cities.index')->with('error', 'المدينة غير موجودة');
        }
        $governorates = get_cols_where(Governorate::class, ['id', 'name'], ['company_id' => $company_id, 'status' => 1]);
        return view('admin.city.update', compact('city', 'governorates'));
    }

    public function update(CityRequest $request, $id)
    {
        try {
            $company_id = Auth::user()->company_id;
            $city = getColsWhereRow(City::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
            if (!$city) {
                return redirect()->route('admin.cities.index')->with('error', 'المدينة غير موجودة');
            }

            $checkIf = City::select('id')
                ->where(['company_id' => $company_id, 'name' => $request->name])
                ->where('id', '!=', $id)
                ->first();
            if ($checkIf) {
                return redirect()->back()->with('error', 'المدينة موجودة بالفعل')->withInput();
            }

            $validated = $request->validated();
            $validated['updated_by'] = Auth::id();
            $validated['company_id'] = $company_id;
            update($city, $validated);

            return redirect()->route('admin.cities.index')->with('success', 'تم تحديث المدينة بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث المدينة ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $company_id = Auth::user()->company_id;
            $city = getColsWhereRow(City::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
            if (!$city) {
                return redirect()->route('admin.cities.index')->with('error', 'المدينة غير موجودة');
            }
            if ($city->employees()->exists()) {
                return redirect()->route('admin.cities.index')->with('error', 'لا يمكن حذف هذه المدينة لوجود موظفين مرتبطة بها');
            }
            destroy($city);
            return redirect()->route('admin.cities.index')->with('success', 'تم حذف المدينة بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف المدينة ' . $e->getMessage());
        }
    }
}
