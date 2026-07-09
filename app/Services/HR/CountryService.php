<?php

namespace App\Services\HR;

use App\Services\BaseService;
use App\Models\Country;

class CountryService extends BaseService
{
    public function __construct()
    {
        $this->setModel(Country::class);
    }
}