<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeTaskRequest;
use App\Models\EmployeeTask;
use App\Models\Employee;
use Auth;
use Illuminate\Http\Request;

class EmployeeTaskController extends Controller
{
    /**
     * Display a listing of the tasks.
     */
    public function index(Request $request)
    {
        $company_id = Auth::user()->company_id;
        
        $currentUser = Auth::user();
        $showArchived = $request->get('show_archived', 0);
        $employeeId   = $request->get('employee_id');
        $isCompleted  = $request->get('is_completed');

        $where = [
            'company_id'  => $company_id,
            'is_archived' => $showArchived,
        ];

        if ($currentUser->is_employee == 1) {
            $where['employee_id'] = $currentUser->employee_id;
        } else {
            if ($employeeId !== null && $employeeId !== '') {
                $where['employee_id'] = $employeeId;
            }
        }

        if ($isCompleted !== null && $isCompleted !== '') {
            $where['is_completed'] = $isCompleted;
        }

        $tasks = getColsWhereP(
            EmployeeTask::class, 
            ['employee', 'addedBy', 'updatedBy'], 
            ['*'], 
            $where, 
            'id', 
            'desc', 
            PAGEINATION_COUNTER
        );

        // Calculate accurate counts for the 3 states
        $notStartedQuery = EmployeeTask::where('company_id', $company_id)->where('is_archived', $showArchived);
        $inProgressQuery = EmployeeTask::where('company_id', $company_id)->where('is_archived', $showArchived);
        $completedQuery  = EmployeeTask::where('company_id', $company_id)->where('is_archived', $showArchived);
        
        if ($currentUser->is_employee == 1) {
            $notStartedQuery->where('employee_id', $currentUser->employee_id);
            $inProgressQuery->where('employee_id', $currentUser->employee_id);
            $completedQuery->where('employee_id', $currentUser->employee_id);
        }

        $notStartedCount = $notStartedQuery->where('is_completed', 0)->count();
        $inProgressCount = $inProgressQuery->where('is_completed', 1)->count();
        $completedCount  = $completedQuery->where('is_completed', 2)->count();

        $employees = get_cols_where(Employee::class, ['id', 'name'], ['company_id' => $company_id]);

        return view('admin.employeeTasks.index', compact(
            'tasks', 'employees', 'showArchived', 'employeeId', 'isCompleted', 
            'notStartedCount', 'inProgressCount', 'completedCount'
        ));
    }

    /**
     * Show the form for creating a new task.
     */
    public function create()
    {
        if (Auth::user()->is_employee == 1) {
            return redirect()->route('admin.employee-tasks.index')->with('error', 'غير مصرح لك بالقيام بهذا الإجراء');
        }
        $company_id = Auth::user()->company_id;
        $employees = get_cols_where(Employee::class, ['id', 'name', 'employee_code'], ['company_id' => $company_id]);
        return view('admin.employeeTasks.create', compact('employees'));
    }

    /**
     * Store a newly created task in storage.
     */
    public function store(EmployeeTaskRequest $request)
    {
        if (Auth::user()->is_employee == 1) {
            return redirect()->route('admin.employee-tasks.index')->with('error', 'غير مصرح لك بالقيام بهذا الإجراء');
        }
        try {
            $company_id = Auth::user()->company_id;
            $validated = $request->validated();
            
            $validated['company_id']  = $company_id;
            $validated['added_by']    = Auth::id();
            $validated['updated_by']  = Auth::id();
            $validated['is_archived'] = 0;

            insert(EmployeeTask::class, $validated);

            return redirect()->route('admin.employee-tasks.index')->with('success', 'تم إضافة المهمة بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ ما برجاء المحاولة لاحقاً: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show the form for editing the specified task.
     */
    public function edit($id)
    {
        if (Auth::user()->is_employee == 1) {
            return redirect()->route('admin.employee-tasks.index')->with('error', 'غير مصرح لك بالقيام بهذا الإجراء');
        }
        $company_id = Auth::user()->company_id;
        $task = getColsWhereRow(EmployeeTask::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
        
        if (!$task) {
            return redirect()->route('admin.employee-tasks.index')->with('error', 'المهمة المطلوبة غير موجودة');
        }

        $employees = get_cols_where(Employee::class, ['id', 'name', 'employee_code'], ['company_id' => $company_id]);
        return view('admin.employeeTasks.update', compact('task', 'employees'));
    }

    /**
     * Update the specified task in storage.
     */
    public function update(EmployeeTaskRequest $request, $id)
    {
        if (Auth::user()->is_employee == 1) {
            return redirect()->route('admin.employee-tasks.index')->with('error', 'غير مصرح لك بالقيام بهذا الإجراء');
        }
        try {
            $company_id = Auth::user()->company_id;
            $task = getColsWhereRow(EmployeeTask::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
            
            if (!$task) {
                return redirect()->route('admin.employee-tasks.index')->with('error', 'المهمة المطلوبة غير موجودة');
            }

            $validated = $request->validated();
            $validated['updated_by'] = Auth::id();

            update($task, $validated);

            return redirect()->route('admin.employee-tasks.index')->with('success', 'تم تحديث المهمة بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ ما برجاء المحاولة لاحقاً: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Archive the specified task.
     */
    public function archive($id)
    {
        if (Auth::user()->is_employee == 1) {
            return redirect()->route('admin.employee-tasks.index')->with('error', 'غير مصرح لك بالقيام بهذا الإجراء');
        }
        try {
            $company_id = Auth::user()->company_id;
            $task = getColsWhereRow(EmployeeTask::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
            
            if (!$task) {
                return redirect()->route('admin.employee-tasks.index')->with('error', 'المهمة المطلوبة غير موجودة');
            }

            update($task, [
                'is_archived' => 1,
                'archived_by' => Auth::id(),
                'archived_at' => now(),
                'updated_by'  => Auth::id(),
            ]);

            return redirect()->route('admin.employee-tasks.index')->with('success', 'تم أرشفة المهمة بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ ما أثناء الأرشفة: ' . $e->getMessage());
        }
    }

    /**
     * Toggle completion status of the task.
     */
    public function toggleStatus($id)
    {
        try {
            $company_id = Auth::user()->company_id;
            $currentUser = Auth::user();
            $task = getColsWhereRow(EmployeeTask::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
            
            if (!$task) {
                return redirect()->route('admin.employee-tasks.index')->with('error', 'المهمة المطلوبة غير موجودة');
            }

            // Security: check if employee owns the task
            if ($currentUser->is_employee == 1 && $task->employee_id != $currentUser->employee_id) {
                return redirect()->route('admin.employee-tasks.index')->with('error', 'غير مصرح لك بتغيير حالة هذه المهمة');
            }

            $newStatus = ($task->is_completed + 1) % 3;
            
            update($task, [
                'is_completed' => $newStatus,
                'updated_by'   => Auth::id(),
            ]);

            return redirect()->back()->with('success', 'تم تغيير حالة المهمة بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ ما أثناء تغيير الحالة: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified task from storage.
     */
    public function destroy($id)
    {
        if (Auth::user()->is_employee == 1) {
            return redirect()->route('admin.employee-tasks.index')->with('error', 'غير مصرح لك بالقيام بهذا الإجراء');
        }
        try {
            $company_id = Auth::user()->company_id;
            $task = getColsWhereRow(EmployeeTask::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
            
            if (!$task) {
                return redirect()->route('admin.employee-tasks.index')->with('error', 'المهمة المطلوبة غير موجودة');
            }

            destroy($task);

            return redirect()->route('admin.employee-tasks.index')->with('success', 'تم حذف المهمة بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ ما أثناء حذف المهمة: ' . $e->getMessage());
        }
    }

    /**
     * Submit/Update employee reply to a task.
     */
    public function reply(Request $request, $id)
    {
        $request->validate([
            'employee_reply' => 'required|string|max:2000',
        ], [
            'employee_reply.required' => 'نص الرد مطلوب',
            'employee_reply.max' => 'الرد لا يجب أن يتجاوز 2000 حرف',
        ]);

        try {
            $company_id = Auth::user()->company_id;
            $currentUser = Auth::user();

            $task = getColsWhereRow(EmployeeTask::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
            
            if (!$task) {
                return redirect()->route('admin.employee-tasks.index')->with('error', 'المهمة المطلوبة غير موجودة');
            }

            // Security: check if employee owns the task
            if ($currentUser->is_employee == 1 && $task->employee_id != $currentUser->employee_id) {
                return redirect()->route('admin.employee-tasks.index')->with('error', 'غير مصرح لك بالرد على هذه المهمة');
            }

            update($task, [
                'employee_reply' => $request->employee_reply,
                'employee_replied_at' => now(),
                'updated_by' => Auth::id(),
            ]);

            return redirect()->back()->with('success', 'تم حفظ الرد بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ ما أثناء حفظ الرد: ' . $e->getMessage());
        }
    }

    /**
     * Display task details and comments (chat thread).
     */
    public function show($id)
    {
        $company_id = Auth::user()->company_id;
        $currentUser = Auth::user();

        $task = EmployeeTask::with(['employee', 'addedBy', 'comments.admin'])
            ->where('id', $id)
            ->where('company_id', $company_id)
            ->first();

        if (!$task) {
            return redirect()->route('admin.employee-tasks.index')->with('error', 'المهمة المطلوبة غير موجودة');
        }

        // Security check: Employees can only view their own tasks
        if ($currentUser->is_employee == 1 && $task->employee_id != $currentUser->employee_id) {
            abort(403, 'غير مصرح لك بمشاهدة هذه المهمة.');
        }

        return view('admin.employeeTasks.show', compact('task'));
    }

    /**
     * Add a comment to the task.
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

            $task = getColsWhereRow(EmployeeTask::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
            if (!$task) {
                return redirect()->route('admin.employee-tasks.index')->with('error', 'المهمة المطلوبة غير موجودة');
            }

            // Security check: Employees can only comment on their own tasks
            if ($currentUser->is_employee == 1 && $task->employee_id != $currentUser->employee_id) {
                abort(403);
            }

            $commentData = [
                'employee_task_id' => $id,
                'admin_id'         => Auth::id(),
                'comment'          => $request->comment,
            ];

            insert(\App\Models\EmployeeTaskComment::class, $commentData);

            return redirect()->back()->with('success', 'تم إضافة تعليقك بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ ما أثناء إضافة التعليق: ' . $e->getMessage());
        }
    }
}
