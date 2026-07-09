<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\OccasionsRequest;
use App\Models\Occasion;
use App\Services\HR\OccasionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OccasionController extends Controller
{
    protected $service;

    public function __construct(OccasionService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $items = $this->service->getPaginated([0=>'addedBy',1=>'updatedBy',]);
        
        return view('admin.occasion.index', ['occasions' => $items]);
    }

    public function create()
    {
        $company_id = Auth::user()->company_id;
        return view('admin.occasion.create');
    }

    public function store(OccasionsRequest $request)
    {
        try {
            if ($this->service->checkExists(['name' => $request->name])) {
                return redirect()->back()->with('error', 'المناسبة موجودة بالفعل')->withInput();
            }

            $validated = $request->validated();
            $this->service->create($validated);

            return redirect()->route('admin.occasions.index')->with('success', 'تم إنشاء المناسبة بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إنشاء المناسبة ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $company_id = Auth::user()->company_id;
        $item = $this->service->getById($id);
        if (!$item) {
            return redirect()->route('admin.occasions.index')->with('error', 'المناسبة غير موجودة');
        }
        return view('admin.occasion.update', ['occasion' => $item]);
    }

    public function update(OccasionsRequest $request, $id)
    {
        try {
            if (!$this->service->getById($id)) {
                return redirect()->route('admin.occasions.index')->with('error', 'المناسبة غير موجودة');
            }

            if ($this->service->checkExists(['name' => $request->name], $id)) {
                return redirect()->back()->with('error', 'المناسبة موجودة بالفعل')->withInput();
            }

            $validated = $request->validated();
            $this->service->update($id, $validated);

            return redirect()->route('admin.occasions.index')->with('success', 'تم تحديث المناسبة بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث المناسبة ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $item = $this->service->getById($id);
            if (!$item) {
                return redirect()->route('admin.occasions.index')->with('error', 'المناسبة غير موجودة');
            }

            $this->service->delete($id);
            return redirect()->route('admin.occasions.index')->with('success', 'تم حذف المناسبة بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف المناسبة ' . $e->getMessage());
        }
    }
}