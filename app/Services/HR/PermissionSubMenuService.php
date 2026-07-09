<?php

namespace App\Services\HR;

use App\Services\BaseService;
use App\Models\PermissionSubMenu;

class PermissionSubMenuService extends BaseService
{
    public function __construct()
    {
        $this->setModel(PermissionSubMenu::class);
    }
}