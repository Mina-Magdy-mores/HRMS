<?php

namespace App\Services\HR;

use App\Services\BaseService;
use App\Models\Branche;

class BrancheService extends BaseService
{
    public function __construct()
    {
        $this->setModel(Branche::class);
    }
}