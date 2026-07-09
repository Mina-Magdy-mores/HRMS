<?php

namespace App\Services\HR;

use App\Services\BaseService;
use App\Models\VacationType;

class VacationTypeService extends BaseService
{
    public function __construct()
    {
        $this->setModel(VacationType::class);
    }
}