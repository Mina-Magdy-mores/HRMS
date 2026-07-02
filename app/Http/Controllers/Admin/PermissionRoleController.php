<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PermissionRoleRequest;
use App\Models\PermissionMainMenu;
use App\Models\PermissionSubMenu;
use App\Models\PermissionSubMenuAction;
use App\Models\PermissionRole;
use App\Models\PermissionRoleMainMenu;
use App\Models\PermissionRoleSubMenu;
use App\Models\PermissionRoleSubMenuAction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PermissionRoleController extends Controller
{
    public function index()
    {
        $company_id = Auth::user()->company_id;
        $roles = getColsWhereP(PermissionRole::class, [], ['*'], ['company_id' => $company_id], 'id', 'asc', PAGEINATION_COUNTER);
        $roles->getCollection()->loadCount('admins');
        return view('admin.permission_roles.index', compact('roles'));
    }

    public function create()
    {
        $mainMenus = PermissionMainMenu::with(['subMenus.actions'])->where('is_active', 1)->get();
        return view('admin.permission_roles.create', compact('mainMenus'));
    }

    public function store(PermissionRoleRequest $request)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validated();
            unset($validated['permissions_main'], $validated['permissions_sub'], $validated['permissions_action']);
            
            $validated['added_by'] = Auth::id();
            $validated['updated_by'] = Auth::id();
            $validated['company_id'] = Auth::user()->company_id ?? 1;

            $role = insert(PermissionRole::class, $validated, true);

            $this->syncPermissions($role, $request);

            DB::commit();
            return redirect()->route('admin.permission-roles.edit', $role->id)->with('success', 'تم إضافة دور الصلاحية بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'حدث خطأ ما: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $company_id = Auth::user()->company_id;
        $role = getColsWhereRow(PermissionRole::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
        if (!$role) {
            return redirect()->route('admin.permission-roles.index')->with('error', 'هذا الدور غير موجود');
        }

        $mainMenus = PermissionMainMenu::with(['subMenus.actions'])->where('is_active', 1)->get();

        // Get currently assigned keys
        $assignedMain = DB::table('permission_roles_main_menues')
            ->where('permission_role_id', $id)
            ->pluck('permission_main_menu_id')
            ->toArray();

        $assignedSub = DB::table('permission_roles_sub_menues')
            ->where('permission_role_id', $id)
            ->pluck('permission_sub_menu_id')
            ->toArray();

        $assignedActions = DB::table('permission_roles_sub_menues_actions')
            ->where('permission_role_id', $id)
            ->pluck('permission_sub_menu_action_id')
            ->toArray();

        return view('admin.permission_roles.update', compact('role', 'mainMenus', 'assignedMain', 'assignedSub', 'assignedActions'));
    }

    public function update(PermissionRoleRequest $request, $id)
    {
        $company_id = Auth::user()->company_id;
        $role = getColsWhereRow(PermissionRole::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
        if (!$role) {
            return redirect()->route('admin.permission-roles.index')->with('error', 'هذا الدور غير موجود');
        }

        try {
            DB::beginTransaction();

            $validated = $request->validated();
            unset($validated['permissions_main'], $validated['permissions_sub'], $validated['permissions_action']);
            
            $validated['updated_by'] = Auth::id();

            update($role, $validated);

            // Delete old permissions
            DB::table('permission_roles_sub_menues_actions')->where('permission_role_id', $id)->delete();
            DB::table('permission_roles_sub_menues')->where('permission_role_id', $id)->delete();
            DB::table('permission_roles_main_menues')->where('permission_role_id', $id)->delete();

            // Store new ones
            $this->syncPermissions($role, $request);

            DB::commit();
            return redirect()->route('admin.permission-roles.edit', $role->id)->with('success', 'تم تعديل دور الصلاحية بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'حدث خطأ ما: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        $company_id = Auth::user()->company_id;
        $role = getColsWhereRow(PermissionRole::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
        if (!$role) {
            return redirect()->route('admin.permission-roles.index')->with('error', 'هذا الدور غير موجود');
        }

        if ($role->admins()->exists()) {
            return redirect()->route('admin.permission-roles.index')->with('error', 'لا يمكن حذف هذا الدور لوجود مستخدمين مرتبطين به.');
        }

        try {
            DB::beginTransaction();
            // Delete associated permissions first
            DB::table('permission_roles_sub_menues_actions')->where('permission_role_id', $id)->delete();
            DB::table('permission_roles_sub_menues')->where('permission_role_id', $id)->delete();
            DB::table('permission_roles_main_menues')->where('permission_role_id', $id)->delete();

            destroy($role);
            DB::commit();

            return redirect()->route('admin.permission-roles.index')->with('success', 'تم حذف دور الصلاحية بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'حدث خطأ ما أثناء حذف الدور: ' . $e->getMessage());
        }
    }

    private function syncPermissions(PermissionRole $role, PermissionRoleRequest $request)
    {
        $permissionsMain = $request->input('permissions_main', []);
        $permissionsSub = $request->input('permissions_sub', []);
        $permissionsAction = $request->input('permissions_action', []);

        foreach ($permissionsMain as $mainId) {
            $roleMainMenu = PermissionRoleMainMenu::create([
                'permission_role_id' => $role->id,
                'permission_main_menu_id' => $mainId,
                'added_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);

            // Find submenus of this main menu that are checked
            $subs = PermissionSubMenu::where('permission_main_menu_id', $mainId)
                ->whereIn('id', $permissionsSub)
                ->get();

            foreach ($subs as $sub) {
                $roleSubMenu = PermissionRoleSubMenu::create([
                    'permission_role_main_menu_id' => $roleMainMenu->id,
                    'permission_sub_menu_id' => $sub->id,
                    'permission_role_id' => $role->id,
                    'added_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                ]);

                // Find actions of this submenu that are checked
                $actions = PermissionSubMenuAction::where('permission_sub_menu_id', $sub->id)
                    ->whereIn('id', $permissionsAction)
                    ->get();

                foreach ($actions as $action) {
                    PermissionRoleSubMenuAction::create([
                        'permission_roles_sub_menue_id' => $roleSubMenu->id,
                        'permission_sub_menu_action_id' => $action->id,
                        'permission_role_id' => $role->id,
                        'added_by' => Auth::id(),
                        'updated_by' => Auth::id(),
                    ]);
                }
            }
        }
    }
}
