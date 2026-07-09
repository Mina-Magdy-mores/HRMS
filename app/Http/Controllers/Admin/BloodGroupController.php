<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BloodGroupRequest;
use App\Models\BloodGroup;
use App\Services\HR\BloodGroupService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BloodGroupController extends Controller
{
    protected $service;

    public function __construct(BloodGroupService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $items = $this->service->getPaginated([0=>'addedBy',1=>'updatedBy',]);
        $items->getCollection()->loadCount([0=>'employees',]);
        return view('admin.blood_group.index', ['bloodGroups' => $items]);
    }

    public function create()
    {
        $company_id = Auth::user()->company_id;
        return view('admin.blood_group.create');
    }

    public function store(BloodGroupRequest $request)
    {
        try {
            if ($this->service->checkExists(['name' => $request->name])) {
                return redirect()->back()->with('error', 'فصيلة الدم موجودة بالفعل')->withInput();
            }

            $validated = $request->validated();
            $this->service->create($validated);

            return redirect()->route('admin.blood-groups.index')->with('success', 'تم إنشاء فصيلة الدم بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إنشاء فصيلة الدم ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $company_id = Auth::user()->company_id;
        $item = $this->service->getById($id);
        if (!$item) {
            return redirect()->route('admin.blood-groups.index')->with('error', 'فصيلة الدم غير موجودة');
        }
        return view('admin.blood_group.update', ['bloodGroup' => $item]);
    }

    public function update(BloodGroupRequest $request, $id)
    {
        try {
            if (!$this->service->getById($id)) {
                return redirect()->route('admin.blood-groups.index')->with('error', 'فصيلة الدم غير موجودة');
            }

            if ($this->service->checkExists(['name' => $request->name], $id)) {
                return redirect()->back()->with('error', 'فصيلة الدم موجودة بالفعل')->withInput();
            }

            $validated = $request->validated();
            $this->service->update($id, $validated);

            return redirect()->route('admin.blood-groups.index')->with('success', 'تم تحديث فصيلة الدم بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث فصيلة الدم ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $item = $this->service->getById($id);
            if (!$item) {
                return redirect()->route('admin.blood-groups.index')->with('error', 'فصيلة الدم غير موجودة');
            }

            if ($item->employees()->exists()) {
                return redirect()->route('admin.blood-groups.index')->with('error', 'لا يمكن حذف فصيلة الدم لوجود موظفين مرتبطة بها');
            }
            $this->service->delete($id);
            return redirect()->route('admin.blood-groups.index')->with('success', 'تم حذف فصيلة الدم بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف فصيلة الدم ' . $e->getMessage());
        }
    }
}