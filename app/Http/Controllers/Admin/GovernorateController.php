<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\GovernorateRequest;
use App\Models\Governorate;
use App\Services\HR\GovernorateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GovernorateController extends Controller
{
    protected $service;

    public function __construct(GovernorateService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $items = $this->service->getPaginated([0=>'addedBy',1=>'updatedBy',2=>'addedBy',3=>'updatedBy',4=>'country',]);
        $items->getCollection()->loadCount([0=>'cities',]);
        return view('admin.governorate.index', ['governorates' => $items]);
    }

    public function create()
    {
        $company_id = Auth::user()->company_id;
        $countries = get_cols_where(\App\Models\Country::class, ['id', 'name'], ['company_id' => $company_id, 'status' => 1]);
        return view('admin.governorate.create', compact('countries'));
    }

    public function store(GovernorateRequest $request)
    {
        try {
            if ($this->service->checkExists(['name' => $request->name])) {
                return redirect()->back()->with('error', 'المحافظة موجودة بالفعل')->withInput();
            }

            $validated = $request->validated();
            $this->service->create($validated);

            return redirect()->route('admin.governorates.index')->with('success', 'تم إنشاء المحافظة بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إنشاء المحافظة ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $company_id = Auth::user()->company_id;
        $item = $this->service->getById($id);
        if (!$item) {
            return redirect()->route('admin.governorates.index')->with('error', 'المحافظة غير موجودة');
        }
        $countries = get_cols_where(\App\Models\Country::class, ['id', 'name'], ['company_id' => $company_id, 'status' => 1]);
        return view('admin.governorate.update', ['governorate' => $item], compact('countries'));
    }

    public function update(GovernorateRequest $request, $id)
    {
        try {
            if (!$this->service->getById($id)) {
                return redirect()->route('admin.governorates.index')->with('error', 'المحافظة غير موجودة');
            }

            if ($this->service->checkExists(['name' => $request->name], $id)) {
                return redirect()->back()->with('error', 'المحافظة موجودة بالفعل')->withInput();
            }

            $validated = $request->validated();
            $this->service->update($id, $validated);

            return redirect()->route('admin.governorates.index')->with('success', 'تم تحديث المحافظة بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث المحافظة ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $item = $this->service->getById($id);
            if (!$item) {
                return redirect()->route('admin.governorates.index')->with('error', 'المحافظة غير موجودة');
            }

            if ($item->cities()->exists()) {
                return redirect()->route('admin.governorates.index')->with('error', 'لا يمكن حذف هذه المحافظة لوجود مدن مرتبطة بها');
            }
            $this->service->delete($id);
            return redirect()->route('admin.governorates.index')->with('success', 'تم حذف المحافظة بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف المحافظة ' . $e->getMessage());
        }
    }
}