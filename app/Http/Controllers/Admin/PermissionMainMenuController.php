<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PermissionMainMenuRequest;
use App\Models\PermissionMainMenu;
use Illuminate\Support\Facades\Auth;

class PermissionMainMenuController extends Controller
{
    public function index()
    {
        $menus = getColsWhereP(PermissionMainMenu::class, [], ['*'], [], 'id', 'asc', PAGEINATION_COUNTER);
        $menus->getCollection()->loadCount('subMenus');
        return view('admin.permission_main_menus.index', compact('menus'));
    }

    public function create()
    {
        return view('admin.permission_main_menus.create');
    }

    public function store(PermissionMainMenuRequest $request)
    {
        try {
            $validated = $request->validated();
            $validated['added_by'] = Auth::id();
            $validated['updated_by'] = Auth::id();

            insert(PermissionMainMenu::class, $validated);

            return redirect()->route('admin.permission-main-menus.index')->with('success', 'تم إضافة القائمة الرئيسية بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ ما: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $menu = getColsWhereRow(PermissionMainMenu::class, ['*'], ['id' => $id]);
        if (!$menu) {
            return redirect()->route('admin.permission-main-menus.index')->with('error', 'هذه القائمة غير موجودة');
        }
        return view('admin.permission_main_menus.update', compact('menu'));
    }

    public function update(PermissionMainMenuRequest $request, $id)
    {
        $menu = getColsWhereRow(PermissionMainMenu::class, ['*'], ['id' => $id]);
        if (!$menu) {
            return redirect()->route('admin.permission-main-menus.index')->with('error', 'هذه القائمة غير موجودة');
        }

        try {
            $validated = $request->validated();
            $validated['updated_by'] = Auth::id();

            update($menu, $validated);

            return redirect()->route('admin.permission-main-menus.index')->with('success', 'تم تعديل القائمة الرئيسية بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ ما: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        $menu = getColsWhereRow(PermissionMainMenu::class, ['*'], ['id' => $id]);
        if (!$menu) {
            return redirect()->route('admin.permission-main-menus.index')->with('error', 'هذه القائمة غير موجودة');
        }

        if ($menu->subMenus()->exists()) {
            return redirect()->route('admin.permission-main-menus.index')->with('error', 'لا يمكن حذف القائمة لوجود قوائم فرعية مرتبطة بها.');
        }

        try {
            destroy($menu);
            return redirect()->route('admin.permission-main-menus.index')->with('success', 'تم حذف القائمة الرئيسية بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ ما أثناء الحذف: ' . $e->getMessage());
        }
    }
}
