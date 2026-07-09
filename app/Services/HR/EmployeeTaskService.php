<?php

namespace App\Services\HR;

use App\Services\BaseService;
use App\Models\EmployeeTask;
use Illuminate\Support\Facades\Auth;

class EmployeeTaskService extends BaseService
{
    public function __construct()
    {
        $this->setModel(EmployeeTask::class);
    }

    public function getFilteredTasks($filters)
    {
        $companyId = $this->getCompanyId();
        $where = [
            'company_id'  => $companyId,
            'is_archived' => $filters['show_archived'] ?? 0,
        ];

        if (isset($filters['employee_id']) && $filters['employee_id'] !== '') {
            $where['employee_id'] = $filters['employee_id'];
        }

        if (isset($filters['is_completed']) && $filters['is_completed'] !== '') {
            $where['is_completed'] = $filters['is_completed'];
        }

        return getColsWhereP(
            EmployeeTask::class,
            ['employee', 'addedBy', 'updatedBy'],
            ['*'],
            $where,
            'id',
            'desc',
            PAGEINATION_COUNTER
        );
    }

    public function toggleTaskStatus($id, $userId, $isEmployee = false, $employeeId = null)
    {
        $task = $this->getById($id);
        if (!$task) {
            throw new \Exception('المهمة المطلوبة غير موجودة');
        }

        if ($isEmployee && $task->employee_id != $employeeId) {
            throw new \Exception('غير مصرح لك بتغيير حالة هذه المهمة');
        }

        $current = $task->is_completed;
        $next = 0;
        if ($current == 0) $next = 1;
        elseif ($current == 1) $next = 2;

        update($task, [
            'is_completed' => $next,
            'updated_by' => $userId
        ]);

        return $task;
    }

    public function replyToTask($id, $reply, $userId, $employeeId)
    {
        $task = $this->getById($id);
        if (!$task) {
            throw new \Exception('المهمة المطلوبة غير موجودة');
        }

        if ($task->employee_id != $employeeId) {
            throw new \Exception('غير مصرح لك بالرد على هذه المهمة');
        }

        update($task, [
            'employee_reply' => $reply,
            'employee_replied_at' => now(),
            'updated_by' => $userId
        ]);

        return $task;
    }

    public function archiveTask($id, $userId)
    {
        $task = $this->getById($id);
        if (!$task) {
            throw new \Exception('المهمة المطلوبة غير موجودة');
        }

        update($task, [
            'is_archived' => 1,
            'archived_by' => $userId,
            'archived_at' => now(),
            'updated_by' => $userId
        ]);

        return $task;
    }
}