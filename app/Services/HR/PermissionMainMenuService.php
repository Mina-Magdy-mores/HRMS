<?php

namespace App\Services\HR;

use App\Services\BaseService;
use App\Models\PermissionMainMenu;

class PermissionMainMenuService extends BaseService
{
    public function __construct()
    {
        $this->setModel(PermissionMainMenu::class);
    }
}