<?php

namespace App\Services\HR;

use App\Services\BaseService;
use App\Models\PermissionSubMenuAction;

class PermissionSubMenuActionService extends BaseService
{
    public function __construct()
    {
        $this->setModel(PermissionSubMenuAction::class);
    }
}