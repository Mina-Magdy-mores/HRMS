<?php

namespace App\Services\HR;

use App\Services\BaseService;
use App\Models\JobsCategory;

class JobsCategoryService extends BaseService
{
    public function __construct()
    {
        $this->setModel(JobsCategory::class);
    }
}