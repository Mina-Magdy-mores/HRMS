<?php

namespace App\Services\HR;

use App\Services\BaseService;
use App\Models\Nationality;

class NationalityService extends BaseService
{
    public function __construct()
    {
        $this->setModel(Nationality::class);
    }
}