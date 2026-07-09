<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CityRequest;
use App\Models\City;
use App\Services\HR\CityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CityController extends Controller
{
    protected $service;

    public function __construct(CityService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $items = $this->service->getPaginated([0=>'addedBy',1=>'updatedBy',2=>'addedBy',3=>'updatedBy',4=>'governorate',]);
        $items->getCollection()->loadCount([0=>'employees',]);
        return view('admin.city.index', ['cities' => $items]);
    }

    public function create()
    {
        $company_id = Auth::user()->company_id;
        $governorates = get_cols_where(\App\Models\Governorate::class, ['id', 'name'], ['company_id' => $company_id, 'status' => 1]);
        return view('admin.city.create', compact('governorates'));
    }

    public function store(CityRequest $request)
    {
        try {
            if ($this->service->checkExists(['name' => $request->name])) {
                return redirect()->back()->with('error', 'المدينة موجودة بالفعل')->withInput();
            }

            $validated = $request->validated();
            $this->service->create($validated);

            return redirect()->route('admin.cities.index')->with('success', 'تم إنشاء المدينة بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إنشاء المدينة ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $company_id = Auth::user()->company_id;
        $item = $this->service->getById($id);
        if (!$item) {
            return redirect()->route('admin.cities.index')->with('error', 'المدينة غير موجودة');
        }
        $governorates = get_cols_where(\App\Models\Governorate::class, ['id', 'name'], ['company_id' => $company_id, 'status' => 1]);
        return view('admin.city.update', ['city' => $item], compact('governorates'));
    }

    public function update(CityRequest $request, $id)
    {
        try {
            if (!$this->service->getById($id)) {
                return redirect()->route('admin.cities.index')->with('error', 'المدينة غير موجودة');
            }

            if ($this->service->checkExists(['name' => $request->name], $id)) {
                return redirect()->back()->with('error', 'المدينة موجودة بالفعل')->withInput();
            }

            $validated = $request->validated();
            $this->service->update($id, $validated);

            return redirect()->route('admin.cities.index')->with('success', 'تم تحديث المدينة بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث المدينة ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $item = $this->service->getById($id);
            if (!$item) {
                return redirect()->route('admin.cities.index')->with('error', 'المدينة غير موجودة');
            }

            if ($item->employees()->exists()) {
                return redirect()->route('admin.cities.index')->with('error', 'لا يمكن حذف هذه المدينة لوجود موظفين مرتبطة بها');
            }
            $this->service->delete($id);
            return redirect()->route('admin.cities.index')->with('success', 'تم حذف المدينة بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف المدينة ' . $e->getMessage());
        }
    }
}