<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeRequestTypeRequest;
use App\Models\EmployeeRequestType;
use App\Services\HR\EmployeeRequestTypeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeRequestTypeController extends Controller
{
    protected $service;

    public function __construct(EmployeeRequestTypeService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $items = $this->service->getPaginated([0=>'addedBy',1=>'updatedBy',]);
        return view('admin.employeeRequestTypes.index', ['types' => $items]);
    }

    public function create()
    {
        $company_id = Auth::user()->company_id;
        return view('admin.employeeRequestTypes.create');
    }

    public function store(EmployeeRequestTypeRequest $request)
    {
        try {
            if ($this->service->checkExists(['name' => $request->name])) {
                return redirect()->back()->with('error', 'النوع موجود بالفعل')->withInput();
            }

            $validated = $request->validated();
            $this->service->create($validated);

            return redirect()->route('admin.employee-request-types.index')->with('success', 'تم إنشاء النوع بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إنشاء النوع ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $company_id = Auth::user()->company_id;
        $item = $this->service->getById($id);
        if (!$item) {
            return redirect()->route('admin.employee-request-types.index')->with('error', 'النوع غير موجود');
        }
        return view('admin.employeeRequestTypes.update', ['type' => $item]);
    }

    public function update(EmployeeRequestTypeRequest $request, $id)
    {
        try {
            if (!$this->service->getById($id)) {
                return redirect()->route('admin.employee-request-types.index')->with('error', 'النوع غير موجود');
            }

            if ($this->service->checkExists(['name' => $request->name], $id)) {
                return redirect()->back()->with('error', 'النوع موجود بالفعل')->withInput();
            }

            $validated = $request->validated();
            $this->service->update($id, $validated);

            return redirect()->route('admin.employee-request-types.index')->with('success', 'تم تحديث النوع بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث النوع ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $item = $this->service->getById($id);
            if (!$item) {
                return redirect()->route('admin.employee-request-types.index')->with('error', 'النوع غير موجود');
            }

            $hasRequests = \App\Models\EmployeeRequest::where('employee_request_type_id', $id)->exists();
            if ($hasRequests) {
                return redirect()->route('admin.employee-request-types.index')->with('error', 'لا يمكن حذف هذا النوع لوجود طلبات مرتبطة به');
            }
            $this->service->delete($id);
            return redirect()->route('admin.employee-request-types.index')->with('success', 'تم حذف النوع بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف النوع ' . $e->getMessage());
        }
    }
}