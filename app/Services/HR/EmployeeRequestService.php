<?php

namespace App\Services\HR;

use App\Services\BaseService;
use App\Models\EmployeeRequest;
use App\Models\EmployeeRequestComment;
use Illuminate\Support\Facades\Auth;

class EmployeeRequestService extends BaseService
{
    public function __construct()
    {
        $this->setModel(EmployeeRequest::class);
    }

    public function addComment($requestId, $content, $userId)
    {
        $requestObj = $this->getById($requestId);
        if (!$requestObj) {
            throw new \Exception('الطلب المطلوبة غير موجودة');
        }

        return insert(EmployeeRequestComment::class, [
            'employee_request_id' => $requestId,
            'content'             => $content,
            'company_id'          => $this->getCompanyId(),
            'added_by'            => $userId,
            'updated_by'          => $userId
        ]);
    }

    public function toggleStatus($requestId, $status, $userId)
    {
        $requestObj = $this->getById($requestId);
        if (!$requestObj) {
            throw new \Exception('الطلب المطلوبة غير موجودة');
        }

        update($requestObj, [
            'status'     => $status,
            'updated_by' => $userId
        ]);

        return $requestObj;
    }

    public function archiveRequest($requestId, $userId)
    {
        $requestObj = $this->getById($requestId);
        if (!$requestObj) {
            throw new \Exception('الطلب المطلوبة غير موجودة');
        }

        update($requestObj, [
            'is_archived' => 1,
            'updated_by'  => $userId
        ]);

        return $requestObj;
    }
}