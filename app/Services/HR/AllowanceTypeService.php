<?php

namespace App\Services\HR;

use App\Services\BaseService;
use App\Models\AllowanceType;

class AllowanceTypeService extends BaseService
{
    public function __construct()
    {
        $this->setModel(AllowanceType::class);
    }
}