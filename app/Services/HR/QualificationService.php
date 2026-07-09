<?php

namespace App\Services\HR;

use App\Services\BaseService;
use App\Models\Qualification;

class QualificationService extends BaseService
{
    public function __construct()
    {
        $this->setModel(Qualification::class);
    }
}