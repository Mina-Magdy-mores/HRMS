<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DepartmentRequest;
use App\Models\Department;
use Auth;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $company_id = Auth::user()->company_id;
        $departments = getColsWhereP(Department::class, ['createdBy', 'updatedBy'], ['*'], ['company_id' => $company_id], 'id', 'asc', PAGEINATION_COUNTER);
        $departments->getCollection()->loadCount('employees');
        return view('admin.departments.index', compact('departments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.departments.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DepartmentRequest $request)
    {
        try {
            $company_id = Auth::user()->company_id;

            $checkIfExist = getColsWhereRow(Department::class, ['id'], ['name' => $request->name, 'company_id' => $company_id]);
            if ($checkIfExist) {
                return redirect()->back()->with('error', 'اسم القسم مكرر')->withInput();
            }
            $validated = $request->validated();
            $validated['created_by'] = Auth::user()->id;
            $validated['updated_by'] = Auth::user()->id;
            $validated['company_id'] = $company_id;
            insert(Department::class, $validated);
            return redirect()->route('admin.departments.index')->with('success', 'Department created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطا ما برجاء المحاوله لاحقا ' . $e->getMessage())->withInput();
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $company_id = Auth::user()->company_id;
        $department = getColsWhereRow(Department::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
        if (!$department) {
            return redirect()->route('admin.departments.index')->with('error', 'القسم غير موجود');
        }
        return view('admin.departments.update', compact('department'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DepartmentRequest $request, $id)
    {
        try {
            $company_id = Auth::user()->company_id;
            $department = getColsWhereRow(Department::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
            if (!$department) {
                return redirect()->route('admin.departments.index')->with('error', 'القسم غير موجود');
            }
            $checkIfNameExists = Department::select('id')->where('company_id', $company_id)->where('name', $request->name)->where('id', '!=', $id)->first();
            if ($checkIfNameExists) {
                return redirect()->back()->with('error', 'اسم القسم مكرر')->withInput();
            }
            $validated = $request->validated();
            $validated['updated_by'] = Auth::user()->id;
            update($department, $validated);
            return redirect()->route('admin.departments.index')->with('success', 'Department updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطا ما برجاء المحاوله لاحقا ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $company_id = Auth::user()->company_id;
            $department = getColsWhereRow(Department::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
            if (!$department) {
                return redirect()->route('admin.departments.index')->with('error', 'القسم غير موجود');
            }
            if ($department->employees()->exists()) {
                return redirect()->route('admin.departments.index')->with('error', 'لا يمكن حذف القسم لوجود موظفين مرتبطة به');
            }
            destroy($department);
            return redirect()->route('admin.departments.index')->with('success', 'Department deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطا ما برجاء المحاوله لاحقا ' . $e->getMessage())->withInput();
        }
    }

}