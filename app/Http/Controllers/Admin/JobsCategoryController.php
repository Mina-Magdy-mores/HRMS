<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\JobsCategoriesRequest;
use App\Models\JobsCategory;
use App\Services\HR\JobsCategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobsCategoryController extends Controller
{
    protected $service;

    public function __construct(JobsCategoryService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $items = $this->service->getPaginated([0=>'addedBy',1=>'updatedBy',]);
        $items->getCollection()->loadCount([0=>'employees',]);
        return view('admin.JobsCategories.index', ['jobCategories' => $items]);
    }

    public function create()
    {
        $company_id = Auth::user()->company_id;
        return view('admin.JobsCategories.create');
    }

    public function store(JobsCategoriesRequest $request)
    {
        try {
            if ($this->service->checkExists(['name' => $request->name])) {
                return redirect()->back()->with('error', 'فئة الوظائف موجودة بالفعل')->withInput();
            }

            $validated = $request->validated();
            $this->service->create($validated);

            return redirect()->route('admin.jobCategories.index')->with('success', 'تم إنشاء فئة الوظائف بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إنشاء فئة الوظائف ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $company_id = Auth::user()->company_id;
        $item = $this->service->getById($id);
        if (!$item) {
            return redirect()->route('admin.jobCategories.index')->with('error', 'فئة الوظائف غير موجودة');
        }
        return view('admin.JobsCategories.update', ['jobCategory' => $item]);
    }

    public function update(JobsCategoriesRequest $request, $id)
    {
        try {
            if (!$this->service->getById($id)) {
                return redirect()->route('admin.jobCategories.index')->with('error', 'فئة الوظائف غير موجودة');
            }

            if ($this->service->checkExists(['name' => $request->name], $id)) {
                return redirect()->back()->with('error', 'فئة الوظائف موجودة بالفعل')->withInput();
            }

            $validated = $request->validated();
            $this->service->update($id, $validated);

            return redirect()->route('admin.jobCategories.index')->with('success', 'تم تحديث فئة الوظائف بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث فئة الوظائف ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $item = $this->service->getById($id);
            if (!$item) {
                return redirect()->route('admin.jobCategories.index')->with('error', 'فئة الوظائف غير موجودة');
            }

            if ($item->employees()->exists()) {
                return redirect()->route('admin.jobCategories.index')->with('error', 'لا يمكن حذف هذه الوظيفة لوجود موظفين مرتبطة بها');
            }
            $this->service->delete($id);
            return redirect()->route('admin.jobCategories.index')->with('success', 'تم حذف فئة الوظائف بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف فئة الوظائف ' . $e->getMessage());
        }
    }
}