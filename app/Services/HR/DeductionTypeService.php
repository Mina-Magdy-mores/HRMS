<?php

namespace App\Services\HR;

use App\Services\BaseService;
use App\Models\DeductionType;

class DeductionTypeService extends BaseService
{
    public function __construct()
    {
        $this->setModel(DeductionType::class);
    }
}