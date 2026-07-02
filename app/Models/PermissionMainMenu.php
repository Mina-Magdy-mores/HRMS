<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

#[Guarded([])]
class PermissionMainMenu extends Model
{
    use LogsActivity;

    protected $table = 'permission_main_menues';

    public function addedBy()
    {
        return $this->belongsTo(Admin::class, 'added_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }

    public function subMenus()
    {
        return $this->hasMany(PermissionSubMenu::class, 'permission_main_menu_id');
    }

    public function roles()
    {
        return $this->belongsToMany(PermissionRole::class, 'permission_roles_main_menues', 'permission_main_menu_id', 'permission_role_id');
    }
}
