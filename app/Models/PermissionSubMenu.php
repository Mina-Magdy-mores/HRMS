<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

#[Guarded([])]
class PermissionSubMenu extends Model
{
    use LogsActivity;

    protected $table = 'permission_sub_menues';

    public function addedBy()
    {
        return $this->belongsTo(Admin::class, 'added_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }

    public function mainMenu()
    {
        return $this->belongsTo(PermissionMainMenu::class, 'permission_main_menu_id');
    }

    public function actions()
    {
        return $this->hasMany(PermissionSubMenuAction::class, 'permission_sub_menu_id');
    }
}
