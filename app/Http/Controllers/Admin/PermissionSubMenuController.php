<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PermissionSubMenuRequest;
use App\Models\PermissionMainMenu;
use App\Models\PermissionSubMenu;
use App\Models\PermissionSubMenuAction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PermissionSubMenuController extends Controller
{
    public function index()
    {
        $submenus = PermissionSubMenu::with('mainMenu')
            ->orderBy('permission_main_menu_id', 'asc')
            ->orderBy('id', 'asc')
            ->get();
        return view('admin.permission_sub_menus.index', compact('submenus'));
    }

    public function create()
    {
        $mainMenus = get_cols_where(PermissionMainMenu::class, ['id', 'name'], ['is_active' => 1]);
        return view('admin.permission_sub_menus.create', compact('mainMenus'));
    }

    public function store(PermissionSubMenuRequest $request)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validated();
            $validated['added_by'] = Auth::id();
            $validated['updated_by'] = Auth::id();

            $subMenu = insert(PermissionSubMenu::class, $validated, true);

            // Automatically seed default actions for this submenu
            $defaultActions = ['عرض', 'إضافة', 'تعديل', 'حذف'];
            foreach ($defaultActions as $action) {
                PermissionSubMenuAction::create([
                    'permission_sub_menu_id' => $subMenu->id,
                    'name' => $action,
                    'is_active' => 1,
                    'added_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                ]);
            }

            DB::commit();
            return redirect()->route('admin.permission-sub-menus.index')->with('success', 'تم إضافة القائمة الفرعية مع حركاتها الافتراضية بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'حدث خطأ ما: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $submenu = getColsWhereRow(PermissionSubMenu::class, ['*'], ['id' => $id]);
        if (!$submenu) {
            return redirect()->route('admin.permission-sub-menus.index')->with('error', 'هذه القائمة غير موجودة');
        }

        $mainMenus = get_cols_where(PermissionMainMenu::class, ['id', 'name'], ['is_active' => 1]);
        return view('admin.permission_sub_menus.update', compact('submenu', 'mainMenus'));
    }

    public function update(PermissionSubMenuRequest $request, $id)
    {
        $submenu = getColsWhereRow(PermissionSubMenu::class, ['*'], ['id' => $id]);
        if (!$submenu) {
            return redirect()->route('admin.permission-sub-menus.index')->with('error', 'هذه القائمة غير موجودة');
        }

        try {
            $validated = $request->validated();
            $validated['updated_by'] = Auth::id();

            update($submenu, $validated);

            return redirect()->route('admin.permission-sub-menus.index')->with('success', 'تم تعديل القائمة الفرعية بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ ما: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        $submenu = getColsWhereRow(PermissionSubMenu::class, ['*'], ['id' => $id]);
        if (!$submenu) {
            return redirect()->route('admin.permission-sub-menus.index')->with('error', 'هذه القائمة غير موجودة');
        }

        try {
            DB::beginTransaction();

            // Associations delete cascades automatically because of the foreign key constraint cascadeOnDelete
            destroy($submenu);

            DB::commit();
            return redirect()->route('admin.permission-sub-menus.index')->with('success', 'تم حذف القائمة الفرعية وحركاتها بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'حدث خطأ ما أثناء الحذف: ' . $e->getMessage());
        }
    }
}
