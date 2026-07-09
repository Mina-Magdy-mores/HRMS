<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeRequestRequest;
use App\Models\Employee;
use App\Models\EmployeeRequest;
use App\Models\EmployeeRequestComment;
use App\Models\EmployeeRequestType;
use Auth;
use Illuminate\Http\Request;

class EmployeeRequestController extends Controller
{
    /**
     * Display a listing of employee requests.
     */
    public function index(Request $request)
    {
        $company_id = Auth::user()->company_id;
        $currentUser = Auth::user();

        // Build query
        $query = EmployeeRequest::with(['employee', 'type', 'addedBy'])
            ->where('company_id', $company_id);

        // Security check: Employees can only see their own requests
        if ($currentUser->is_employee == 1) {
            $query->where('employee_id', $currentUser->employee_id);
        }

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Default: only show non-archived unless specifically searching for archived
        $isArchived = $request->get('is_archived', '0');
        $query->where('is_archived', $isArchived);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('content', 'like', '%' . $search . '%')
                  ->orWhereHas('employee', function($empQ) use ($search) {
                      $empQ->where('name', 'like', '%' . $search . '%')
                           ->orWhere('employee_code', 'like', '%' . $search . '%');
                  });
            });
        }

        if ($request->filled('employee_request_type_id')) {
            $query->where('employee_request_type_id', $request->employee_request_type_id);
        }

        $requests = $query->orderBy('id', 'desc')->paginate(PAGEINATION_COUNTER);

        // Fetch request types for filters
        $types = get_cols_where(EmployeeRequestType::class, ['id', 'name'], ['is_active' => 1, 'company_id' => $company_id]);

        return view('admin.employeeRequests.index', compact('requests', 'types', 'isArchived'));
    }

    /**
     * Show the form for creating a new request.
     */
    public function create()
    {
        $company_id = Auth::user()->company_id;
        $currentUser = Auth::user();

        $types = get_cols_where(EmployeeRequestType::class, ['id', 'name'], ['is_active' => 1, 'company_id' => $company_id]);
        
        $employees = [];
        if ($currentUser->is_employee == 0) {
            // Admins can create on behalf of any employee
            $employees = get_cols_where(Employee::class, ['id', 'name', 'employee_code'], ['company_id' => $company_id]);
        }

        return view('admin.employeeRequests.create', compact('types', 'employees'));
    }

    /**
     * Store a newly created request.
     */
    public function store(EmployeeRequestRequest $request)
    {
        try {
            $company_id = Auth::user()->company_id;
            $currentUser = Auth::user();
            $validated = $request->validated();

            if ($currentUser->is_employee == 1) {
                $employeeId = $currentUser->employee_id;
            } else {
                $request->validate(['employee_id' => 'required|exists:employees,id'], [
                    'employee_id.required' => 'يجب اختيار الموظف صاحب الطلب.',
                    'employee_id.exists' => 'الموظف المختار غير صحيح.'
                ]);
                $employeeId = $request->employee_id;
            }

            $validated['employee_id'] = $employeeId;
            $validated['company_id']  = $company_id;
            $validated['status']      = 0; // pending
            $validated['is_archived']  = 0;
            $validated['added_by']    = Auth::id();
            $validated['updated_by']  = Auth::id();

            insert(EmployeeRequest::class, $validated);

            return redirect()->route('admin.employee-requests.index')->with('success', 'تم تقديم طلبك بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ ما برجاء المحاولة لاحقاً: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display request details along with comments chat.
     */
    public function show($id)
    {
        $company_id = Auth::user()->company_id;
        $currentUser = Auth::user();

        // Load relations
        $requestObj = EmployeeRequest::with(['employee', 'type', 'comments.admin'])
            ->where('id', $id)
            ->where('company_id', $company_id)
            ->first();

        if (!$requestObj) {
            return redirect()->route('admin.employee-requests.index')->with('error', 'الطلب المطلوب غير موجود');
        }

        // Security check: Employees can only view their own requests
        if ($currentUser->is_employee == 1 && $requestObj->employee_id != $currentUser->employee_id) {
            abort(403, 'غير مصرح لك بمشاهدة هذا الطلب.');
        }

        return view('admin.employeeRequests.show', compact('requestObj'));
    }

    /**
     * Add a comment to the request.
     */
    public function comment(Request $request, $id)
    {
        $request->validate([
            'comment' => 'required|string|max:5000'
        ], [
            'comment.required' => 'التعليق لا يمكن أن يكون فارغاً.'
        ]);

        try {
            $company_id = Auth::user()->company_id;
            $currentUser = Auth::user();

            $requestObj = getColsWhereRow(EmployeeRequest::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
            if (!$requestObj) {
                return redirect()->route('admin.employee-requests.index')->with('error', 'الطلب المطلوب غير موجود');
            }

            // Security check: Employees can only comment on their own requests
            if ($currentUser->is_employee == 1 && $requestObj->employee_id != $currentUser->employee_id) {
                abort(403);
            }

            $commentData = [
                'employee_request_id' => $id,
                'admin_id'            => Auth::id(),
                'comment'             => $request->comment,
            ];

            insert(EmployeeRequestComment::class, $commentData);

            return redirect()->back()->with('success', 'تم إضافة تعليقك بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إضافة تعليقك: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Change status of a request (Approved/Rejected) - Admin only.
     */
    public function changeStatus(Request $request, $id)
    {
        $currentUser = Auth::user();
        if ($currentUser->is_employee == 1) {
            abort(403, 'غير مصرح للموظف بتعديل حالة الطلبات.');
        }

        $request->validate([
            'status' => 'required|integer|in:1,2'
        ]);

        try {
            $company_id = Auth::user()->company_id;
            $requestObj = getColsWhereRow(EmployeeRequest::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
            
            if (!$requestObj) {
                return redirect()->route('admin.employee-requests.index')->with('error', 'الطلب المطلوب غير موجود');
            }

            $requestObj->status = $request->status;
            $requestObj->updated_by = Auth::id();
            
            update($requestObj, [
                'status' => $request->status,
                'updated_by' => Auth::id()
            ]);

            return redirect()->back()->with('success', 'تم تغيير حالة الطلب بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تعديل حالة الطلب: ' . $e->getMessage());
        }
    }

    /**
     * Archive a request - Admin only.
     */
    public function archive($id)
    {
        $currentUser = Auth::user();
        if ($currentUser->is_employee == 1) {
            abort(403, 'غير مصرح للموظف بأرشفة الطلبات.');
        }

        try {
            $company_id = Auth::user()->company_id;
            $requestObj = getColsWhereRow(EmployeeRequest::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
            
            if (!$requestObj) {
                return redirect()->route('admin.employee-requests.index')->with('error', 'الطلب المطلوب غير موجود');
            }

            update($requestObj, [
                'is_archived' => 1,
                'archived_by' => Auth::id(),
                'archived_at' => now(),
                'updated_by'  => Auth::id()
            ]);

            return redirect()->route('admin.employee-requests.index')->with('success', 'تم نقل الطلب إلى الأرشيف بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء أرشفة الطلب: ' . $e->getMessage());
        }
    }
}
