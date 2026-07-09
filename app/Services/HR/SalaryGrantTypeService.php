<?php

namespace App\Services\HR;

use App\Services\BaseService;
use App\Models\SalaryGrantType;

class SalaryGrantTypeService extends BaseService
{
    public function __construct()
    {
        $this->setModel(SalaryGrantType::class);
    }
}