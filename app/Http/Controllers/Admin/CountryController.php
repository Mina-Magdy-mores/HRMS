<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CountryRequest;
use App\Models\Country;
use Illuminate\Support\Facades\Auth;

class CountryController extends Controller
{
    public function index()
    {
        $company_id = Auth::user()->company_id;
        $countries = getColsWhereP(Country::class, ['addedBy', 'updatedBy'], ['*'], ['company_id' => $company_id]);
        $countries->getCollection()->loadCount('employees');
        return view('admin.country.index', ['countries' => $countries]);
    }

    public function create()
    {
        return view('admin.country.create');
    }

    public function store(CountryRequest $request)
    {
        try {
            $company_id = Auth::user()->company_id;
            $checkIf = getColsWhereRow(Country::class, ['id'], ['company_id' => $company_id, 'name' => $request->name]);
            if ($checkIf) {
                return redirect()->back()->with('error', 'الدولة موجودة بالفعل')->withInput();
            }

            $validated = $request->validated();
            $validated['added_by'] = Auth::id();
            $validated['updated_by'] = Auth::id();
            $validated['company_id'] = $company_id;
            insert(Country::class, $validated);

            return redirect()->route('admin.countries.index')->with('success', 'تم إنشاء الدولة بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إنشاء الدولة ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $company_id = Auth::user()->company_id;
        $country = getColsWhereRow(Country::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
        if (!$country) {
            return redirect()->route('admin.countries.index')->with('error', 'الدولة غير موجودة');
        }
        return view('admin.country.update', ['country' => $country]);
    }

    public function update(CountryRequest $request, $id)
    {
        try {
            $company_id = Auth::user()->company_id;
            $country = getColsWhereRow(Country::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
            if (!$country) {
                return redirect()->route('admin.countries.index')->with('error', 'الدولة غير موجودة');
            }

            $checkIf = Country::select('id')
                ->where(['company_id' => $company_id, 'name' => $request->name])
                ->where('id', '!=', $id)
                ->first();
            if ($checkIf) {
                return redirect()->back()->with('error', 'الدولة موجودة بالفعل')->withInput();
            }

            $validated = $request->validated();
            $validated['updated_by'] = Auth::id();
            $validated['company_id'] = $company_id;
            update($country, $validated);

            return redirect()->route('admin.countries.index')->with('success', 'تم تحديث الدولة بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث الدولة ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $company_id = Auth::user()->company_id;
            $country = getColsWhereRow(Country::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
            if (!$country) {
                return redirect()->route('admin.countries.index')->with('error', 'الدولة غير موجودة');
            }
            if ($country->employees()->exists()) {
                return redirect()->route('admin.countries.index')->with('error', 'لا يمكن حذف هذه الدولة لوجود موظفين مرتبطة بها');
            }
            destroy($country);
            return redirect()->route('admin.countries.index')->with('success', 'تم حذف الدولة بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف الدولة ' . $e->getMessage());
        }
    }
}
