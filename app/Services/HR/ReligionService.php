<?php

namespace App\Services\HR;

use App\Services\BaseService;
use App\Models\Religion;

class ReligionService extends BaseService
{
    public function __construct()
    {
        $this->setModel(Religion::class);
    }
}