<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReligionRequest;
use App\Models\Religion;
use App\Services\HR\ReligionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReligionController extends Controller
{
    protected $service;

    public function __construct(ReligionService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $items = $this->service->getPaginated([0=>'addedBy',1=>'updatedBy',]);
        $items->getCollection()->loadCount([0=>'employees',]);
        return view('admin.religion.index', ['religions' => $items]);
    }

    public function create()
    {
        $company_id = Auth::user()->company_id;
        return view('admin.religion.create');
    }

    public function store(ReligionRequest $request)
    {
        try {
            if ($this->service->checkExists(['name' => $request->name])) {
                return redirect()->back()->with('error', 'الديانة موجودة بالفعل')->withInput();
            }

            $validated = $request->validated();
            $this->service->create($validated);

            return redirect()->route('admin.religions.index')->with('success', 'تم إنشاء الديانة بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إنشاء الديانة ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $company_id = Auth::user()->company_id;
        $item = $this->service->getById($id);
        if (!$item) {
            return redirect()->route('admin.religions.index')->with('error', 'الديانة غير موجودة');
        }
        return view('admin.religion.update', ['religion' => $item]);
    }

    public function update(ReligionRequest $request, $id)
    {
        try {
            if (!$this->service->getById($id)) {
                return redirect()->route('admin.religions.index')->with('error', 'الديانة غير موجودة');
            }

            if ($this->service->checkExists(['name' => $request->name], $id)) {
                return redirect()->back()->with('error', 'الديانة موجودة بالفعل')->withInput();
            }

            $validated = $request->validated();
            $this->service->update($id, $validated);

            return redirect()->route('admin.religions.index')->with('success', 'تم تحديث الديانة بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث الديانة ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $item = $this->service->getById($id);
            if (!$item) {
                return redirect()->route('admin.religions.index')->with('error', 'الديانة غير موجودة');
            }

            if ($item->employees()->exists()) {
                return redirect()->route('admin.religions.index')->with('error', 'لا يمكن حذف هذه الديانة لوجود موظفين مرتبطة بها');
            }
            $this->service->delete($id);
            return redirect()->route('admin.religions.index')->with('success', 'تم حذف الديانة بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف الديانة ' . $e->getMessage());
        }
    }
}