<?php

namespace App\Services\HR;

use App\Services\BaseService;
use App\Models\PermissionRole;
use App\Models\PermissionRoleMainMenu;
use App\Models\PermissionRoleSubMenu;
use App\Models\PermissionRoleSubMenuAction;
use App\Models\PermissionSubMenu;
use App\Models\PermissionSubMenuAction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PermissionRoleService extends BaseService
{
    public function __construct()
    {
        $this->setModel(PermissionRole::class);
    }

    public function createRole($data, $request)
    {
        DB::transaction(function() use (&$role, $data, $request) {
            $data['added_by'] = $this->getUserId();
            $data['updated_by'] = $this->getUserId();
            $data['company_id'] = $this->getCompanyId() ?? 1;

            $role = insert(PermissionRole::class, $data, true);
            $this->syncPermissions($role, $request);
        });
        return $role;
    }

    public function updateRole($id, $data, $request)
    {
        $role = $this->getById($id);
        if (!$role) {
            throw new \Exception('هذا الدور غير موجود');
        }

        DB::transaction(function() use ($role, $data, $request, $id) {
            $data['updated_by'] = $this->getUserId();
            update($role, $data);

            // Delete old permissions
            DB::table('permission_roles_sub_menues_actions')->where('permission_role_id', $id)->delete();
            DB::table('permission_roles_sub_menues')->where('permission_role_id', $id)->delete();
            DB::table('permission_roles_main_menues')->where('permission_role_id', $id)->delete();

            $this->syncPermissions($role, $request);
        });
        return $role;
    }

    public function deleteRole($id)
    {
        $role = $this->getById($id);
        if (!$role) {
            throw new \Exception('هذا الدور غير موجود');
        }

        if ($role->admins()->exists()) {
            throw new \Exception('لا يمكن حذف هذا الدور لوجود مستخدمين مرتبطين به.');
        }

        DB::transaction(function() use ($role, $id) {
            DB::table('permission_roles_sub_menues_actions')->where('permission_role_id', $id)->delete();
            DB::table('permission_roles_sub_menues')->where('permission_role_id', $id)->delete();
            DB::table('permission_roles_main_menues')->where('permission_role_id', $id)->delete();
            destroy($role);
        });

        return true;
    }

    private function syncPermissions(PermissionRole $role, $request)
    {
        $permissionsMain = $request->input('permissions_main', []);
        $permissionsSub = $request->input('permissions_sub', []);
        $permissionsAction = $request->input('permissions_action', []);

        foreach ($permissionsMain as $mainId) {
            $roleMainMenu = PermissionRoleMainMenu::create([
                'permission_role_id' => $role->id,
                'permission_main_menu_id' => $mainId,
                'added_by' => $this->getUserId(),
                'updated_by' => $this->getUserId(),
            ]);

            $subs = PermissionSubMenu::where('permission_main_menu_id', $mainId)
                ->whereIn('id', $permissionsSub)
                ->get();

            foreach ($subs as $sub) {
                $roleSubMenu = PermissionRoleSubMenu::create([
                    'permission_role_main_menu_id' => $roleMainMenu->id,
                    'permission_sub_menu_id' => $sub->id,
                    'permission_role_id' => $role->id,
                    'added_by' => $this->getUserId(),
                    'updated_by' => $this->getUserId(),
                ]);

                $actions = PermissionSubMenuAction::where('permission_sub_menu_id', $sub->id)
                    ->whereIn('id', $permissionsAction)
                    ->get();

                foreach ($actions as $action) {
                    PermissionRoleSubMenuAction::create([
                        'permission_roles_sub_menue_id' => $roleSubMenu->id,
                        'permission_sub_menu_action_id' => $action->id,
                        'permission_role_id' => $role->id,
                        'added_by' => $this->getUserId(),
                        'updated_by' => $this->getUserId(),
                    ]);
                }
            }
        }
    }
}