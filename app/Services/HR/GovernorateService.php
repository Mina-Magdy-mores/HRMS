<?php

namespace App\Services\HR;

use App\Services\BaseService;
use App\Models\Governorate;

class GovernorateService extends BaseService
{
    public function __construct()
    {
        $this->setModel(Governorate::class);
    }
}