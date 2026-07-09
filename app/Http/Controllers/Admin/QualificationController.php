<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\QualificationRequest;
use App\Models\Qualification;
use App\Services\HR\QualificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QualificationController extends Controller
{
    protected $service;

    public function __construct(QualificationService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $items = $this->service->getPaginated([0=>'addedBy',1=>'updatedBy',]);
        $items->getCollection()->loadCount([0=>'employees',]);
        return view('admin.qualification.index', ['qualifications' => $items]);
    }

    public function create()
    {
        $company_id = Auth::user()->company_id;
        return view('admin.qualification.create');
    }

    public function store(QualificationRequest $request)
    {
        try {
            if ($this->service->checkExists(['name' => $request->name])) {
                return redirect()->back()->with('error', 'المؤهل موجود بالفعل')->withInput();
            }

            $validated = $request->validated();
            $this->service->create($validated);

            return redirect()->route('admin.qualifications.index')->with('success', 'تم إنشاء المؤهل بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إنشاء المؤهل ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $company_id = Auth::user()->company_id;
        $item = $this->service->getById($id);
        if (!$item) {
            return redirect()->route('admin.qualifications.index')->with('error', 'المؤهل غير موجود');
        }
        return view('admin.qualification.update', ['qualification' => $item]);
    }

    public function update(QualificationRequest $request, $id)
    {
        try {
            if (!$this->service->getById($id)) {
                return redirect()->route('admin.qualifications.index')->with('error', 'المؤهل غير موجود');
            }

            if ($this->service->checkExists(['name' => $request->name], $id)) {
                return redirect()->back()->with('error', 'المؤهل موجود بالفعل')->withInput();
            }

            $validated = $request->validated();
            $this->service->update($id, $validated);

            return redirect()->route('admin.qualifications.index')->with('success', 'تم تحديث المؤهل بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث المؤهل ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $item = $this->service->getById($id);
            if (!$item) {
                return redirect()->route('admin.qualifications.index')->with('error', 'المؤهل غير موجود');
            }

            if ($item->employees()->exists()) {
                return redirect()->route('admin.qualifications.index')->with('error', 'لا يمكن حذف هذا المؤهل لوجود موظفين مرتبطة به');
            }
            $this->service->delete($id);
            return redirect()->route('admin.qualifications.index')->with('success', 'تم حذف المؤهل بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف المؤهل ' . $e->getMessage());
        }
    }
}