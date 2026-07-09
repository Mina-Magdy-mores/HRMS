<?php

namespace App\Services\HR;

use App\Services\BaseService;
use App\Models\Department;

class DepartmentService extends BaseService
{
    public function __construct()
    {
        $this->setModel(Department::class);
    }
}