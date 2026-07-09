<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PermissionMainMenuRequest;
use App\Models\PermissionMainMenu;
use App\Services\HR\PermissionMainMenuService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermissionMainMenuController extends Controller
{
    protected $service;

    public function __construct(PermissionMainMenuService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $items = $this->service->getPaginated([0=>'addedBy',1=>'updatedBy',]);
        
        return view('admin.permission_main_menus.index', ['menus' => $items]);
    }

    public function create()
    {
        $company_id = Auth::user()->company_id;
        return view('admin.permission_main_menus.create');
    }

    public function store(PermissionMainMenuRequest $request)
    {
        try {
            if ($this->service->checkExists(['name' => $request->name])) {
                return redirect()->back()->with('error', 'القائمة الرئيسية موجودة بالفعل')->withInput();
            }

            $validated = $request->validated();
            $this->service->create($validated);

            return redirect()->route('admin.permission-main-menus.index')->with('success', 'تم إنشاء القائمة الرئيسية بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إنشاء القائمة الرئيسية ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $company_id = Auth::user()->company_id;
        $item = $this->service->getById($id);
        if (!$item) {
            return redirect()->route('admin.permission-main-menus.index')->with('error', 'القائمة الرئيسية غير موجودة');
        }
        return view('admin.permission_main_menus.update', ['menu' => $item]);
    }

    public function update(PermissionMainMenuRequest $request, $id)
    {
        try {
            if (!$this->service->getById($id)) {
                return redirect()->route('admin.permission-main-menus.index')->with('error', 'القائمة الرئيسية غير موجودة');
            }

            if ($this->service->checkExists(['name' => $request->name], $id)) {
                return redirect()->back()->with('error', 'القائمة الرئيسية موجودة بالفعل')->withInput();
            }

            $validated = $request->validated();
            $this->service->update($id, $validated);

            return redirect()->route('admin.permission-main-menus.index')->with('success', 'تم تحديث القائمة الرئيسية بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث القائمة الرئيسية ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $item = $this->service->getById($id);
            if (!$item) {
                return redirect()->route('admin.permission-main-menus.index')->with('error', 'القائمة الرئيسية غير موجودة');
            }


            $this->service->delete($id);
            return redirect()->route('admin.permission-main-menus.index')->with('success', 'تم حذف القائمة الرئيسية بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف القائمة الرئيسية ' . $e->getMessage());
        }
    }
}