<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResignationRequest;
use App\Models\Resignation;
use App\Services\HR\ResignationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResignationController extends Controller
{
    protected $service;

    public function __construct(ResignationService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $items = $this->service->getPaginated([0=>'addedBy',1=>'updatedBy',]);
        $items->getCollection()->loadCount([0=>'employees',]);
        return view('admin.resignations.index', ['resignations' => $items]);
    }

    public function create()
    {
        $company_id = Auth::user()->company_id;
        return view('admin.resignations.create');
    }

    public function store(ResignationRequest $request)
    {
        try {
            if ($this->service->checkExists(['name' => $request->name])) {
                return redirect()->back()->with('error', 'نوع نهاية الخدمة موجودة بالفعل')->withInput();
            }

            $validated = $request->validated();
            $this->service->create($validated);

            return redirect()->route('admin.resignations.index')->with('success', 'تم إنشاء نوع نهاية الخدمة بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إنشاء نوع نهاية الخدمة ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $company_id = Auth::user()->company_id;
        $item = $this->service->getById($id);
        if (!$item) {
            return redirect()->route('admin.resignations.index')->with('error', 'نوع نهاية الخدمة غير موجودة');
        }
        return view('admin.resignations.update', ['resignation' => $item]);
    }

    public function update(ResignationRequest $request, $id)
    {
        try {
            if (!$this->service->getById($id)) {
                return redirect()->route('admin.resignations.index')->with('error', 'نوع نهاية الخدمة غير موجودة');
            }

            if ($this->service->checkExists(['name' => $request->name], $id)) {
                return redirect()->back()->with('error', 'نوع نهاية الخدمة موجودة بالفعل')->withInput();
            }

            $validated = $request->validated();
            $this->service->update($id, $validated);

            return redirect()->route('admin.resignations.index')->with('success', 'تم تحديث نوع نهاية الخدمة بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث نوع نهاية الخدمة ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $item = $this->service->getById($id);
            if (!$item) {
                return redirect()->route('admin.resignations.index')->with('error', 'نوع نهاية الخدمة غير موجودة');
            }

            if ($item->employees()->exists()) {
                return redirect()->route('admin.resignations.index')->with('error', 'لا يمكن حذف هذا البند لوجود موظفين مرتبطة به');
            }
            $this->service->delete($id);
            return redirect()->route('admin.resignations.index')->with('success', 'تم حذف نوع نهاية الخدمة بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف نوع نهاية الخدمة ' . $e->getMessage());
        }
    }
}