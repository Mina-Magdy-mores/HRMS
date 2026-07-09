<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BrancheRequest;
use App\Models\Branche;
use App\Services\HR\BrancheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BrancheController extends Controller
{
    protected $service;

    public function __construct(BrancheService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $items = $this->service->getPaginated([0=>'createdBy',1=>'updatedBy',]);
        $items->getCollection()->loadCount([0=>'employees',]);
        return view('admin.branches.index', ['branches' => $items]);
    }

    public function create()
    {
        $company_id = Auth::user()->company_id;
        return view('admin.branches.create');
    }

    public function store(BrancheRequest $request)
    {
        try {
            if ($this->service->checkExists(['name' => $request->name])) {
                return redirect()->back()->with('error', 'الفرع موجودة بالفعل')->withInput();
            }

            $validated = $request->validated();
            $this->service->create($validated);

            return redirect()->route('admin.branches.index')->with('success', 'تم إنشاء الفرع بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إنشاء الفرع ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $company_id = Auth::user()->company_id;
        $item = $this->service->getById($id);
        if (!$item) {
            return redirect()->route('admin.branches.index')->with('error', 'الفرع غير موجودة');
        }
        return view('admin.branches.update', ['branch' => $item]);
    }

    public function update(BrancheRequest $request, $id)
    {
        try {
            if (!$this->service->getById($id)) {
                return redirect()->route('admin.branches.index')->with('error', 'الفرع غير موجودة');
            }

            if ($this->service->checkExists(['name' => $request->name], $id)) {
                return redirect()->back()->with('error', 'الفرع موجودة بالفعل')->withInput();
            }

            $validated = $request->validated();
            $this->service->update($id, $validated);

            return redirect()->route('admin.branches.index')->with('success', 'تم تحديث الفرع بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث الفرع ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $item = $this->service->getById($id);
            if (!$item) {
                return redirect()->route('admin.branches.index')->with('error', 'الفرع غير موجودة');
            }

            if ($item->employees()->exists()) {
                return redirect()->route('admin.branches.index')->with('error', 'لا يمكن حذف هذا الفرع لوجود موظفين مرتبطة به');
            }
            $this->service->delete($id);
            return redirect()->route('admin.branches.index')->with('success', 'تم حذف الفرع بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف الفرع ' . $e->getMessage());
        }
    }
}