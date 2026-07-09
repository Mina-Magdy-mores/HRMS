<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DepartmentRequest;
use App\Models\Department;
use App\Services\HR\DepartmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepartmentController extends Controller
{
    protected $service;

    public function __construct(DepartmentService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $items = $this->service->getPaginated([0=>'createdBy',1=>'updatedBy',]);
        $items->getCollection()->loadCount([0=>'employees',]);
        return view('admin.departments.index', ['departments' => $items]);
    }

    public function create()
    {
        $company_id = Auth::user()->company_id;
        return view('admin.departments.create');
    }

    public function store(DepartmentRequest $request)
    {
        try {
            if ($this->service->checkExists(['name' => $request->name])) {
                return redirect()->back()->with('error', 'القسم موجودة بالفعل')->withInput();
            }

            $validated = $request->validated();
            $this->service->create($validated);

            return redirect()->route('admin.departments.index')->with('success', 'تم إنشاء القسم بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إنشاء القسم ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $company_id = Auth::user()->company_id;
        $item = $this->service->getById($id);
        if (!$item) {
            return redirect()->route('admin.departments.index')->with('error', 'القسم غير موجودة');
        }
        return view('admin.departments.update', ['department' => $item]);
    }

    public function update(DepartmentRequest $request, $id)
    {
        try {
            if (!$this->service->getById($id)) {
                return redirect()->route('admin.departments.index')->with('error', 'القسم غير موجودة');
            }

            if ($this->service->checkExists(['name' => $request->name], $id)) {
                return redirect()->back()->with('error', 'القسم موجودة بالفعل')->withInput();
            }

            $validated = $request->validated();
            $this->service->update($id, $validated);

            return redirect()->route('admin.departments.index')->with('success', 'تم تحديث القسم بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث القسم ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $item = $this->service->getById($id);
            if (!$item) {
                return redirect()->route('admin.departments.index')->with('error', 'القسم غير موجودة');
            }

            if ($item->employees()->exists()) {
                return redirect()->route('admin.departments.index')->with('error', 'لا يمكن حذف هذا القسم لوجود موظفين مرتبطة به');
            }
            $this->service->delete($id);
            return redirect()->route('admin.departments.index')->with('success', 'تم حذف القسم بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف القسم ' . $e->getMessage());
        }
    }
}