<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeRequestTypeRequest;
use App\Models\EmployeeRequestType;
use Auth;
use Illuminate\Http\Request;

class EmployeeRequestTypeController extends Controller
{
    /**
     * Display a listing of employee request types.
     */
    public function index()
    {
        $company_id = Auth::user()->company_id;

        $types = getColsWhereP(
            EmployeeRequestType::class, 
            ['addedBy', 'updatedBy'], 
            ['*'], 
            ['company_id' => $company_id], 
            'id', 
            'desc', 
            PAGEINATION_COUNTER
        );

        return view('admin.employeeRequestTypes.index', compact('types'));
    }

    /**
     * Show the form for creating a new request type.
     */
    public function create()
    {
        return view('admin.employeeRequestTypes.create');
    }

    /**
     * Store a newly created request type in storage.
     */
    public function store(EmployeeRequestTypeRequest $request)
    {
        try {
            $company_id = Auth::user()->company_id;
            $validated = $request->validated();
            
            $validated['company_id'] = $company_id;
            $validated['added_by']   = Auth::id();
            $validated['updated_by'] = Auth::id();

            insert(EmployeeRequestType::class, $validated);

            return redirect()->route('admin.employee-request-types.index')->with('success', 'تم إضافة نوع الطلب بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ ما برجاء المحاولة لاحقاً: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show the form for editing the specified request type.
     */
    public function edit($id)
    {
        $company_id = Auth::user()->company_id;
        $type = getColsWhereRow(EmployeeRequestType::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
        
        if (!$type) {
            return redirect()->route('admin.employee-request-types.index')->with('error', 'نوع الطلب المطلوب غير موجود');
        }

        return view('admin.employeeRequestTypes.update', compact('type'));
    }

    /**
     * Update the specified request type in storage.
     */
    public function update(EmployeeRequestTypeRequest $request, $id)
    {
        try {
            $company_id = Auth::user()->company_id;
            $type = getColsWhereRow(EmployeeRequestType::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
            
            if (!$type) {
                return redirect()->route('admin.employee-request-types.index')->with('error', 'نوع الطلب المطلوب غير موجود');
            }

            $validated = $request->validated();
            $validated['updated_by'] = Auth::id();

            update($type, $validated);

            return redirect()->route('admin.employee-request-types.index')->with('success', 'تم تحديث نوع الطلب بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ ما برجاء المحاولة لاحقاً: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified request type from storage.
     */
    public function destroy($id)
    {
        try {
            $company_id = Auth::user()->company_id;
            $type = getColsWhereRow(EmployeeRequestType::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
            
            if (!$type) {
                return redirect()->route('admin.employee-request-types.index')->with('error', 'نوع الطلب المطلوب غير موجود');
            }

            // Check if there are requests associated with this type
            $hasRequests = \App\Models\EmployeeRequest::where('employee_request_type_id', $id)->exists();
            if ($hasRequests) {
                return redirect()->route('admin.employee-request-types.index')->with('error', 'لا يمكن حذف هذا النوع لوجود طلبات مرتبطة به');
            }

            destroy($type);

            return redirect()->route('admin.employee-request-types.index')->with('success', 'تم حذف نوع الطلب بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ ما أثناء حذف نوع الطلب: ' . $e->getMessage());
        }
    }
}
