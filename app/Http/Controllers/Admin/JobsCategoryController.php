<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\JobsCategoriesRequest;
use App\Models\JobsCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobsCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $company_id = Auth::user()->company_id;
        $jobCategories = getColsWhereP(JobsCategory::class, [], ['*'], ['company_id' => $company_id]);
        return view('admin.JobsCategories.index', ['jobCategories' => $jobCategories]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.JobsCategories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(JobsCategoriesRequest $request)
    {
        try {
            $company_id = Auth::user()->company_id;
            $checkIf = getColsWhereRow(JobsCategory::class, ['id'], ['company_id' => $company_id, 'name' => $request->name]);
            if ($checkIf) {
                return redirect()->back()->with('error', 'فئة الوظائف موجودة بالفعل');
            }
            $validated = $request->validated();
            $validated['added_by'] = Auth::id();
            $validated['updated_by'] = Auth::id();
            $validated['company_id'] = $company_id;
            insert(JobsCategory::class, $validated);
            return redirect()->route('admin.jobCategories.index')->with('success', 'تم إنشاء فئة الوظائف بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إنشاء فئة الوظائف ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $company_id = Auth::user()->company_id;
        $jobCategory = getColsWhereRow(JobsCategory::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
        if (!$jobCategory) {
            return redirect()->route('admin.jobCategories.index')->with('error', 'فئة الوظائف غير موجودة');
        }
        return view('admin.JobsCategories.update', ['jobCategory' => $jobCategory]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(JobsCategoriesRequest $request, $id)
    {
        try {
            $company_id = Auth::user()->company_id;
            $jobCategory = getColsWhereRow(JobsCategory::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
            if (!$jobCategory) {
                return redirect()->route('admin.jobCategories.index')->with('error', 'فئة الوظائف غير موجودة');
            }
            $checkIf = JobsCategory::select('id')->where(['company_id' => $company_id, 'name' => $request->name])->where('id', '!=', $id)->first();
            if ($checkIf) {
                return redirect()->back()->with('error', 'فئة الوظائف موجودة بالفعل');
            }
            $validated = $request->validated();
            $validated['updated_by'] = Auth::id();
            $validated['company_id'] = $company_id;
            update($jobCategory, $validated);
            return redirect()->route('admin.jobCategories.index')->with('success', 'تم تحديث فئة الوظائف بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث فئة الوظائف ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
               try {
            $company_id = Auth::user()->company_id;
            $jobCategory = getColsWhereRow(JobsCategory::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
            if (!$jobCategory) {
                return redirect()->route('admin.jobCategories.index')->with('error', 'فئة الوظائف غير موجودة');
            }
            destroy($jobCategory);
            return redirect()->route('admin.jobCategories.index')->with('success', 'تم حذف فئة الوظائف بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث فئة الوظائف ' . $e->getMessage());
        }
    }
}
