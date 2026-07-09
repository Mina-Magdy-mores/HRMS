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
use App\Services\HR\PermissionRoleService;

class PermissionRoleController extends Controller
{
    protected $service;

    public function __construct(PermissionRoleService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $roles = $this->service->getPaginated([], ['*'], [], 'id', 'asc', PAGEINATION_COUNTER);
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
            $validated = $request->validated();
            unset($validated['permissions_main'], $validated['permissions_sub'], $validated['permissions_action']);
            
            $role = $this->service->createRole($validated, $request);

            return redirect()->route('admin.permission-roles.edit', $role->id)->with('success', 'تم إضافة دور الصلاحية بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ ما: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $company_id = Auth::user()->company_id;
        $role = $this->service->getById($id);
        if (!$role) {
            return redirect()->route('admin.permission-roles.index')->with('error', 'هذا الدور غير موجود');
        }

        $mainMenus = PermissionMainMenu::with(['subMenus.actions'])->where('is_active', 1)->get();

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
        try {
            $validated = $request->validated();
            unset($validated['permissions_main'], $validated['permissions_sub'], $validated['permissions_action']);
            
            $this->service->updateRole($id, $validated, $request);

            return redirect()->route('admin.permission-roles.edit', $id)->with('success', 'تم تعديل دور الصلاحية بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ ما: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $this->service->deleteRole($id);
            return redirect()->route('admin.permission-roles.index')->with('success', 'تم حذف دور الصلاحية بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ ما أثناء حذف الدور: ' . $e->getMessage());
        }
    }
}
