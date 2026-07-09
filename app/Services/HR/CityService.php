<?php

namespace App\Services\HR;

use App\Services\BaseService;
use App\Models\City;

class CityService extends BaseService
{
    public function __construct()
    {
        $this->setModel(City::class);
    }
}