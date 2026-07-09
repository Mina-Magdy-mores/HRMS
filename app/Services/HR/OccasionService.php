<?php

namespace App\Services\HR;

use App\Services\BaseService;
use App\Models\Occasion;

class OccasionService extends BaseService
{
    public function __construct()
    {
        $this->setModel(Occasion::class);
    }
}