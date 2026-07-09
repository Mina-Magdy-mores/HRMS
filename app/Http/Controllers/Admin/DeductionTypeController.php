<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeductionTypeRequest;
use App\Models\DeductionType;
use App\Services\HR\DeductionTypeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeductionTypeController extends Controller
{
    protected $service;

    public function __construct(DeductionTypeService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $items = $this->service->getPaginated([0=>'addedBy',1=>'updatedBy',]);
        $items->getCollection()->loadCount([0=>'mainSalaryEmployeeDeductionTypes',]);
        return view('admin.deductionType.index', ['deductionTypes' => $items]);
    }

    public function create()
    {
        $company_id = Auth::user()->company_id;
        return view('admin.deductionType.create');
    }

    public function store(DeductionTypeRequest $request)
    {
        try {
            if ($this->service->checkExists(['name' => $request->name])) {
                return redirect()->back()->with('error', 'النوع موجود بالفعل')->withInput();
            }

            $validated = $request->validated();
            $this->service->create($validated);

            return redirect()->route('admin.deductions-types.index')->with('success', 'تم إنشاء النوع بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إنشاء النوع ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $company_id = Auth::user()->company_id;
        $item = $this->service->getById($id);
        if (!$item) {
            return redirect()->route('admin.deductions-types.index')->with('error', 'النوع غير موجود');
        }
        return view('admin.deductionType.update', ['deductionType' => $item]);
    }

    public function update(DeductionTypeRequest $request, $id)
    {
        try {
            if (!$this->service->getById($id)) {
                return redirect()->route('admin.deductions-types.index')->with('error', 'النوع غير موجود');
            }

            if ($this->service->checkExists(['name' => $request->name], $id)) {
                return redirect()->back()->with('error', 'النوع موجود بالفعل')->withInput();
            }

            $validated = $request->validated();
            $this->service->update($id, $validated);

            return redirect()->route('admin.deductions-types.index')->with('success', 'تم تحديث النوع بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث النوع ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $item = $this->service->getById($id);
            if (!$item) {
                return redirect()->route('admin.deductions-types.index')->with('error', 'النوع غير موجود');
            }

            if ($item->mainSalaryEmployeeDeductionTypes()->exists()) {
                return redirect()->route('admin.deductions-types.index')->with('error', 'لا يمكن حذف هذا الخصم لارتباطه بسجلات رواتب');
            }
            $this->service->delete($id);
            return redirect()->route('admin.deductions-types.index')->with('success', 'تم حذف النوع بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف النوع ' . $e->getMessage());
        }
    }
}