<?php

namespace App\Services\HR;

use App\Services\BaseService;
use App\Models\BloodGroup;

class BloodGroupService extends BaseService
{
    public function __construct()
    {
        $this->setModel(BloodGroup::class);
    }
}