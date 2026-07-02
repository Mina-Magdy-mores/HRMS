<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Model;

#[Guarded([])]
class PermissionRoleSubMenu extends Model
{
    protected $table = 'permission_roles_sub_menues';
}
