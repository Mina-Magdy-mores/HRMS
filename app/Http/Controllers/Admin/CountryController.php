<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CountryRequest;
use App\Models\Country;
use App\Services\HR\CountryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CountryController extends Controller
{
    protected $service;

    public function __construct(CountryService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $items = $this->service->getPaginated([0=>'addedBy',1=>'updatedBy',]);
        $items->getCollection()->loadCount([0=>'governorates',]);
        return view('admin.country.index', ['countries' => $items]);
    }

    public function create()
    {
        $company_id = Auth::user()->company_id;
        return view('admin.country.create');
    }

    public function store(CountryRequest $request)
    {
        try {
            if ($this->service->checkExists(['name' => $request->name])) {
                return redirect()->back()->with('error', 'الدولة موجودة بالفعل')->withInput();
            }

            $validated = $request->validated();
            $this->service->create($validated);

            return redirect()->route('admin.countries.index')->with('success', 'تم إنشاء الدولة بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إنشاء الدولة ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $company_id = Auth::user()->company_id;
        $item = $this->service->getById($id);
        if (!$item) {
            return redirect()->route('admin.countries.index')->with('error', 'الدولة غير موجودة');
        }
        return view('admin.country.update', ['country' => $item]);
    }

    public function update(CountryRequest $request, $id)
    {
        try {
            if (!$this->service->getById($id)) {
                return redirect()->route('admin.countries.index')->with('error', 'الدولة غير موجودة');
            }

            if ($this->service->checkExists(['name' => $request->name], $id)) {
                return redirect()->back()->with('error', 'الدولة موجودة بالفعل')->withInput();
            }

            $validated = $request->validated();
            $this->service->update($id, $validated);

            return redirect()->route('admin.countries.index')->with('success', 'تم تحديث الدولة بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث الدولة ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $item = $this->service->getById($id);
            if (!$item) {
                return redirect()->route('admin.countries.index')->with('error', 'الدولة غير موجودة');
            }

            if ($item->governorates()->exists()) {
                return redirect()->route('admin.countries.index')->with('error', 'لا يمكن حذف هذه الدولة لوجود محافظات مرتبطة بها');
            }
            $this->service->delete($id);
            return redirect()->route('admin.countries.index')->with('success', 'تم حذف الدولة بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف الدولة ' . $e->getMessage());
        }
    }
}