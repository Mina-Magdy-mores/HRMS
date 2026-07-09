<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PermissionSubMenuActionRequest;
use App\Models\PermissionSubMenuAction;
use App\Services\HR\PermissionSubMenuActionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermissionSubMenuActionController extends Controller
{
    protected $service;

    public function __construct(PermissionSubMenuActionService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $items = $this->service->getAll(['*'], [], 'id', 'asc');
        $items->load(['subMenu.mainMenu', 'addedBy', 'updatedBy']);
        
        return view('admin.permission_sub_menu_actions.index', ['actions' => $items]);
    }

    public function create()
    {
        $company_id = Auth::user()->company_id;
        $subMenus = get_cols_where(\App\Models\PermissionSubMenu::class, ['id', 'name'], ['company_id' => $company_id, 'is_active' => 1]);
        return view('admin.permission_sub_menu_actions.create', compact('subMenus'));
    }

    public function store(PermissionSubMenuActionRequest $request)
    {
        try {
            if ($this->service->checkExists(['name' => $request->name])) {
                return redirect()->back()->with('error', 'الإجراء موجود بالفعل')->withInput();
            }

            $validated = $request->validated();
            $this->service->create($validated);

            return redirect()->route('admin.permission-sub-menu-actions.index')->with('success', 'تم إنشاء الإجراء بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إنشاء الإجراء ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $company_id = Auth::user()->company_id;
        $item = $this->service->getById($id);
        if (!$item) {
            return redirect()->route('admin.permission-sub-menu-actions.index')->with('error', 'الإجراء غير موجود');
        }
        $subMenus = get_cols_where(\App\Models\PermissionSubMenu::class, ['id', 'name'], ['company_id' => $company_id, 'is_active' => 1]);
        return view('admin.permission_sub_menu_actions.update', ['action' => $item], compact('subMenus'));
    }

    public function update(PermissionSubMenuActionRequest $request, $id)
    {
        try {
            if (!$this->service->getById($id)) {
                return redirect()->route('admin.permission-sub-menu-actions.index')->with('error', 'الإجراء غير موجود');
            }

            if ($this->service->checkExists(['name' => $request->name], $id)) {
                return redirect()->back()->with('error', 'الإجراء موجود بالفعل')->withInput();
            }

            $validated = $request->validated();
            $this->service->update($id, $validated);

            return redirect()->route('admin.permission-sub-menu-actions.index')->with('success', 'تم تحديث الإجراء بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث الإجراء ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $item = $this->service->getById($id);
            if (!$item) {
                return redirect()->route('admin.permission-sub-menu-actions.index')->with('error', 'الإجراء غير موجود');
            }

            $this->service->delete($id);
            return redirect()->route('admin.permission-sub-menu-actions.index')->with('success', 'تم حذف الإجراء بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف الإجراء ' . $e->getMessage());
        }
    }
}