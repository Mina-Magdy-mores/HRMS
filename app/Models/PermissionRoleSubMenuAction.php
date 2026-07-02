<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Model;

#[Guarded([])]
class PermissionRoleSubMenuAction extends Model
{
    protected $table = 'permission_roles_sub_menues_actions';
}
