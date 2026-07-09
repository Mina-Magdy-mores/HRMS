<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\NationalityRequest;
use App\Models\Nationality;
use App\Services\HR\NationalityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NationalityController extends Controller
{
    protected $service;

    public function __construct(NationalityService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $items = $this->service->getPaginated([0=>'addedBy',1=>'updatedBy',]);
        $items->getCollection()->loadCount([0=>'employees',]);
        return view('admin.nationality.index', ['nationalities' => $items]);
    }

    public function create()
    {
        $company_id = Auth::user()->company_id;
        return view('admin.nationality.create');
    }

    public function store(NationalityRequest $request)
    {
        try {
            if ($this->service->checkExists(['name' => $request->name])) {
                return redirect()->back()->with('error', 'الجنسية موجودة بالفعل')->withInput();
            }

            $validated = $request->validated();
            $this->service->create($validated);

            return redirect()->route('admin.nationalities.index')->with('success', 'تم إنشاء الجنسية بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إنشاء الجنسية ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $company_id = Auth::user()->company_id;
        $item = $this->service->getById($id);
        if (!$item) {
            return redirect()->route('admin.nationalities.index')->with('error', 'الجنسية غير موجودة');
        }
        return view('admin.nationality.update', ['nationality' => $item]);
    }

    public function update(NationalityRequest $request, $id)
    {
        try {
            if (!$this->service->getById($id)) {
                return redirect()->route('admin.nationalities.index')->with('error', 'الجنسية غير موجودة');
            }

            if ($this->service->checkExists(['name' => $request->name], $id)) {
                return redirect()->back()->with('error', 'الجنسية موجودة بالفعل')->withInput();
            }

            $validated = $request->validated();
            $this->service->update($id, $validated);

            return redirect()->route('admin.nationalities.index')->with('success', 'تم تحديث الجنسية بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث الجنسية ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $item = $this->service->getById($id);
            if (!$item) {
                return redirect()->route('admin.nationalities.index')->with('error', 'الجنسية غير موجودة');
            }

            if ($item->employees()->exists()) {
                return redirect()->route('admin.nationalities.index')->with('error', 'لا يمكن حذف هذه الجنسية لوجود موظفين مرتبطة بها');
            }
            $this->service->delete($id);
            return redirect()->route('admin.nationalities.index')->with('success', 'تم حذف الجنسية بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف الجنسية ' . $e->getMessage());
        }
    }
}