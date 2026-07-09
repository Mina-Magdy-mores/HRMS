<?php

namespace App\Services\HR;

use App\Services\BaseService;
use App\Models\ShiftsType;
use Illuminate\Support\Facades\DB;

class ShiftsTypeService extends BaseService
{
    public function __construct()
    {
        $this->setModel(ShiftsType::class);
    }
}