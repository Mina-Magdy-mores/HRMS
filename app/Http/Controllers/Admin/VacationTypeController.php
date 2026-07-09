<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\VacationTypeRequest;
use App\Models\VacationType;
use App\Services\HR\VacationTypeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VacationTypeController extends Controller
{
    protected $service;

    public function __construct(VacationTypeService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $items = $this->service->getPaginated([0=>'addedBy',1=>'updatedBy',]);
        $items->getCollection()->loadCount([0=>'attendancesDepartures',]);
        return view('admin.vacationTypes.index', ['vacationTypes' => $items]);
    }

    public function create()
    {
        $company_id = Auth::user()->company_id;
        return view('admin.vacationTypes.create');
    }

    public function store(VacationTypeRequest $request)
    {
        try {
            if ($this->service->checkExists(['name' => $request->name])) {
                return redirect()->back()->with('error', 'نوع الإجازة موجود بالفعل')->withInput();
            }

            $validated = $request->validated();
            $this->service->create($validated);

            return redirect()->route('admin.vacation-types.index')->with('success', 'تم إنشاء نوع الإجازة بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إنشاء نوع الإجازة ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $company_id = Auth::user()->company_id;
        $item = $this->service->getById($id);
        if (!$item) {
            return redirect()->route('admin.vacation-types.index')->with('error', 'نوع الإجازة غير موجود');
        }
        return view('admin.vacationTypes.update', ['vacationType' => $item]);
    }

    public function update(VacationTypeRequest $request, $id)
    {
        try {
            if (!$this->service->getById($id)) {
                return redirect()->route('admin.vacation-types.index')->with('error', 'نوع الإجازة غير موجود');
            }

            if ($this->service->checkExists(['name' => $request->name], $id)) {
                return redirect()->back()->with('error', 'نوع الإجازة موجود بالفعل')->withInput();
            }

            $validated = $request->validated();
            $this->service->update($id, $validated);

            return redirect()->route('admin.vacation-types.index')->with('success', 'تم تحديث نوع الإجازة بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث نوع الإجازة ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $item = $this->service->getById($id);
            if (!$item) {
                return redirect()->route('admin.vacation-types.index')->with('error', 'نوع الإجازة غير موجود');
            }

            if ($item->attendancesDepartures()->exists()) {
                return redirect()->route('admin.vacation-types.index')->with('error', 'لا يمكن حذف نوع الإجازة لوجود حركات بصمة مرتبطة به');
            }
            $this->service->delete($id);
            return redirect()->route('admin.vacation-types.index')->with('success', 'تم حذف نوع الإجازة بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف نوع الإجازة ' . $e->getMessage());
        }
    }
}