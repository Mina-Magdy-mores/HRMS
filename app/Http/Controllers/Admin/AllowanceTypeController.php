<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AllowanceTypeRequest;
use App\Http\Requests\AllowanceTypeUpdateRequest;
use App\Models\AllowanceType;
use App\Services\HR\AllowanceTypeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AllowanceTypeController extends Controller
{
    protected $service;

    public function __construct(AllowanceTypeService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $items = $this->service->getPaginated([0=>'addedBy',1=>'updatedBy',]);
        $items->getCollection()->loadCount([0=>'employeeFixedAllowances',1=>'mainSalaryEmployeeAllowances',]);
        return view('admin.allowanceType.index', ['allowanceTypes' => $items]);
    }

    public function create()
    {
        $company_id = Auth::user()->company_id;
        return view('admin.allowanceType.create');
    }

    public function store(AllowanceTypeRequest $request)
    {
        try {
            if ($this->service->checkExists(['name' => $request->name])) {
                return redirect()->back()->with('error', 'النوع موجودة بالفعل')->withInput();
            }

            $validated = $request->validated();
            $this->service->create($validated);

            return redirect()->route('admin.allowance-types.index')->with('success', 'تم إنشاء النوع بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إنشاء النوع ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $company_id = Auth::user()->company_id;
        $item = $this->service->getById($id);
        if (!$item) {
            return redirect()->route('admin.allowance-types.index')->with('error', 'النوع غير موجودة');
        }
        return view('admin.allowanceType.update', ['allowanceType' => $item]);
    }

    public function update(AllowanceTypeUpdateRequest $request, $id)
    {
        try {
            if (!$this->service->getById($id)) {
                return redirect()->route('admin.allowance-types.index')->with('error', 'النوع غير موجودة');
            }

            if ($this->service->checkExists(['name' => $request->name], $id)) {
                return redirect()->back()->with('error', 'النوع موجودة بالفعل')->withInput();
            }

            $validated = $request->validated();
            $this->service->update($id, $validated);

            return redirect()->route('admin.allowance-types.index')->with('success', 'تم تحديث النوع بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث النوع ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $item = $this->service->getById($id);
            if (!$item) {
                return redirect()->route('admin.allowance-types.index')->with('error', 'النوع غير موجودة');
            }

            if ($item->employeeFixedAllowances()->exists() || $item->mainSalaryEmployeeAllowances()->exists()) {
                return redirect()->route('admin.allowance-types.index')->with('error', 'لا يمكن حذف هذا البدل لارتباطه بموظفين أو بسجلات رواتب');
            }
            $this->service->delete($id);
            return redirect()->route('admin.allowance-types.index')->with('success', 'تم حذف النوع بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف النوع ' . $e->getMessage());
        }
    }
}