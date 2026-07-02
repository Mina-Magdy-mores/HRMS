<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Model;

#[Guarded([])]
class PermissionRoleMainMenu extends Model
{
    protected $table = 'permission_roles_main_menues';
}
