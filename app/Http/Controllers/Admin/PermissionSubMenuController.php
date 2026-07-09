<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PermissionSubMenuRequest;
use App\Models\PermissionSubMenu;
use App\Services\HR\PermissionSubMenuService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermissionSubMenuController extends Controller
{
    protected $service;

    public function __construct(PermissionSubMenuService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $items = $this->service->getAll(['*'], [], 'permission_main_menu_id', 'asc');
        $items->load(['mainMenu', 'addedBy', 'updatedBy']);
        
        return view('admin.permission_sub_menus.index', ['submenus' => $items]);
    }

    public function create()
    {
        $company_id = Auth::user()->company_id;
        $mainMenus = get_cols_where(\App\Models\PermissionMainMenu::class, ['id', 'name'], ['company_id' => $company_id, 'is_active' => 1]);
        return view('admin.permission_sub_menus.create', compact('mainMenus'));
    }

    public function store(PermissionSubMenuRequest $request)
    {
        try {
            if ($this->service->checkExists(['name' => $request->name])) {
                return redirect()->back()->with('error', 'القائمة الفرعية موجودة بالفعل')->withInput();
            }

            $validated = $request->validated();
            $this->service->create($validated);

            return redirect()->route('admin.permission-sub-menus.index')->with('success', 'تم إنشاء القائمة الفرعية بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إنشاء القائمة الفرعية ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $company_id = Auth::user()->company_id;
        $item = $this->service->getById($id);
        if (!$item) {
            return redirect()->route('admin.permission-sub-menus.index')->with('error', 'القائمة الفرعية غير موجودة');
        }
        $mainMenus = get_cols_where(\App\Models\PermissionMainMenu::class, ['id', 'name'], ['company_id' => $company_id, 'is_active' => 1]);
        return view('admin.permission_sub_menus.update', ['menu' => $item], compact('mainMenus'));
    }

    public function update(PermissionSubMenuRequest $request, $id)
    {
        try {
            if (!$this->service->getById($id)) {
                return redirect()->route('admin.permission-sub-menus.index')->with('error', 'القائمة الفرعية غير موجودة');
            }

            if ($this->service->checkExists(['name' => $request->name], $id)) {
                return redirect()->back()->with('error', 'القائمة الفرعية موجودة بالفعل')->withInput();
            }

            $validated = $request->validated();
            $this->service->update($id, $validated);

            return redirect()->route('admin.permission-sub-menus.index')->with('success', 'تم تحديث القائمة الفرعية بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث القائمة الفرعية ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $item = $this->service->getById($id);
            if (!$item) {
                return redirect()->route('admin.permission-sub-menus.index')->with('error', 'القائمة الفرعية غير موجودة');
            }


            $this->service->delete($id);
            return redirect()->route('admin.permission-sub-menus.index')->with('success', 'تم حذف القائمة الفرعية بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف القائمة الفرعية ' . $e->getMessage());
        }
    }
}