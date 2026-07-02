<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

#[Guarded([])]
class PermissionSubMenuAction extends Model
{
    use LogsActivity;

    protected $table = 'permission_sub_menues_actions';

    public function addedBy()
    {
        return $this->belongsTo(Admin::class, 'added_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }

    public function subMenu()
    {
        return $this->belongsTo(PermissionSubMenu::class, 'permission_sub_menu_id');
    }
}
