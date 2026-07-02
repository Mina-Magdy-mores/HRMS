<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

#[Guarded([])]
class PermissionRole extends Model
{
    use LogsActivity;

    protected $table = 'permission_roles';

    public function addedBy()
    {
        return $this->belongsTo(Admin::class, 'added_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }

    public function mainMenus()
    {
        return $this->belongsToMany(PermissionMainMenu::class, 'permission_roles_main_menues', 'permission_role_id', 'permission_main_menu_id');
    }

    public function subMenus()
    {
        return $this->belongsToMany(PermissionSubMenu::class, 'permission_roles_sub_menues', 'permission_role_id', 'permission_sub_menu_id');
    }

    public function actions()
    {
        return $this->belongsToMany(PermissionSubMenuAction::class, 'permission_roles_sub_menues_actions', 'permission_role_id', 'permission_sub_menu_action_id');
    }

    public function admins()
    {
        return $this->hasMany(Admin::class, 'permission_role_id');
    }
}
