<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PermissionSubMenuActionRequest;
use App\Models\PermissionSubMenu;
use App\Models\PermissionSubMenuAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermissionSubMenuActionController extends Controller
{
    public function index()
    {
        $actions = PermissionSubMenuAction::with(['subMenu.mainMenu'])
            ->join('permission_sub_menues', 'permission_sub_menues.id', '=', 'permission_sub_menues_actions.permission_sub_menu_id')
            ->orderBy('permission_sub_menues.permission_main_menu_id', 'asc')
            ->orderBy('permission_sub_menues_actions.permission_sub_menu_id', 'asc')
            ->orderBy('permission_sub_menues_actions.id', 'asc')
            ->select('permission_sub_menues_actions.*')
            ->get();

        return view('admin.permission_sub_menu_actions.index', compact('actions'));
    }

    public function create()
    {
        $subMenus = PermissionSubMenu::with('mainMenu')->get();
        return view('admin.permission_sub_menu_actions.create', compact('subMenus'));
    }

    public function store(PermissionSubMenuActionRequest $request)
    {
        try {
            DB::beginTransaction();

            $subMenuId = $request->permission_sub_menu_id;
            $isActive = $request->is_active;
            $addedBy = auth()->user()->id;

            $insertedCount = 0;
            $duplicateCount = 0;

            // 1. Process checked standard action names
            $names = $request->input('names', []);
            foreach ($names as $name) {
                $name = trim($name);
                if ($name !== '') {
                    $exists = PermissionSubMenuAction::where('permission_sub_menu_id', $subMenuId)
                        ->where('name', $name)
                        ->exists();

                    if (!$exists) {
                        PermissionSubMenuAction::create([
                            'permission_sub_menu_id' => $subMenuId,
                            'name' => $name,
                            'is_active' => $isActive,
                            'added_by' => $addedBy,
                        ]);
                        $insertedCount++;
                    } else {
                        $duplicateCount++;
                    }
                }
            }

            // 2. Process custom action names separated by commas
            if ($request->filled('custom_names')) {
                $customNames = explode(',', $request->custom_names);
                foreach ($customNames as $name) {
                    $name = trim($name);
                    if ($name !== '') {
                        $exists = PermissionSubMenuAction::where('permission_sub_menu_id', $subMenuId)
                            ->where('name', $name)
                            ->exists();

                        if (!$exists) {
                            PermissionSubMenuAction::create([
                                'permission_sub_menu_id' => $subMenuId,
                                'name' => $name,
                                'is_active' => $isActive,
                                'added_by' => $addedBy,
                            ]);
                            $insertedCount++;
                        } else {
                            $duplicateCount++;
                        }
                    }
                }
            }

            DB::commit();

            $msg = "تم إضافة عدد {$insertedCount} حركات بنجاح.";
            if ($duplicateCount > 0) {
                $msg .= " (تم تجاهل عدد {$duplicateCount} حركات لأنها مسجلة بالفعل)";
            }

            return redirect()->route('admin.permission-sub-menu-actions.index')->with('success', $msg);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'حدث خطأ ما: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $action = PermissionSubMenuAction::findOrFail($id);
        $subMenus = PermissionSubMenu::with('mainMenu')->get();
        return view('admin.permission_sub_menu_actions.edit', compact('action', 'subMenus'));
    }

    public function update(PermissionSubMenuActionRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $action = PermissionSubMenuAction::findOrFail($id);
            $action->update([
                'permission_sub_menu_id' => $request->permission_sub_menu_id,
                'name' => $request->name,
                'is_active' => $request->is_active,
                'updated_by' => auth()->user()->id,
            ]);

            DB::commit();
            return redirect()->route('admin.permission-sub-menu-actions.index')->with('success', 'تم تعديل الحركة بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'حدث خطأ ما: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $action = PermissionSubMenuAction::findOrFail($id);
            $action->delete();

            DB::commit();
            return redirect()->route('admin.permission-sub-menu-actions.index')->with('success', 'تم حذف الحركة بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'حدث خطأ ما أثناء الحذف: ' . $e->getMessage());
        }
    }
}
