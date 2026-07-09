<?php

namespace App\Services\HR;

use App\Services\BaseService;
use App\Models\EmployeeRequestType;

class EmployeeRequestTypeService extends BaseService
{
    public function __construct()
    {
        $this->setModel(EmployeeRequestType::class);
    }
}