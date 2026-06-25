<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\VacationTypeRequest;
use App\Models\VacationType;
use Illuminate\Support\Facades\Auth;

class VacationTypeController extends Controller
{
    public function index()
    {
        $company_id = Auth::user()->company_id;
        $vacationTypes = getColsWhereP(VacationType::class, ['addedBy', 'updatedBy'], ['*'], ['company_id' => $company_id]);
        $vacationTypes->getCollection()->loadCount('attendancesDepartures');
        return view('admin.vacationTypes.index', ['vacationTypes' => $vacationTypes]);
    }

    public function create()
    {
        return view('admin.vacationTypes.create');
    }

    public function store(VacationTypeRequest $request)
    {
        try {
            $company_id = Auth::user()->company_id;
            $checkIf = getColsWhereRow(VacationType::class, ['id'], ['company_id' => $company_id, 'name' => $request->name]);
            if ($checkIf) {
                return redirect()->back()->with('error', 'نوع الإجازة موجود بالفعل')->withInput();
            }

            $validated = $request->validated();
            $validated['added_by'] = Auth::id();
            $validated['updated_by'] = Auth::id();
            $validated['company_id'] = $company_id;
            insert(VacationType::class, $validated);

            return redirect()->route('admin.vacation-types.index')->with('success', 'تم إنشاء نوع الإجازة بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إنشاء نوع الإجازة ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $company_id = Auth::user()->company_id;
        $vacationType = getColsWhereRow(VacationType::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
        if (!$vacationType) {
            return redirect()->route('admin.vacation-types.index')->with('error', 'نوع الإجازة غير موجود');
        }
        return view('admin.vacationTypes.update', ['vacationType' => $vacationType]);
    }

    public function update(VacationTypeRequest $request, $id)
    {
        try {
            $company_id = Auth::user()->company_id;
            $vacationType = getColsWhereRow(VacationType::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
            if (!$vacationType) {
                return redirect()->route('admin.vacation-types.index')->with('error', 'نوع الإجازة غير موجود');
            }

            $checkIf = VacationType::select('id')
                ->where(['company_id' => $company_id, 'name' => $request->name])
                ->where('id', '!=', $id)
                ->first();
            if ($checkIf) {
                return redirect()->back()->with('error', 'نوع الإجازة موجود بالفعل')->withInput();
            }

            $validated = $request->validated();
            $validated['updated_by'] = Auth::id();
            $validated['company_id'] = $company_id;
            update($vacationType, $validated);

            return redirect()->route('admin.vacation-types.index')->with('success', 'تم تحديث نوع الإجازة بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث نوع الإجازة ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $company_id = Auth::user()->company_id;
            $vacationType = getColsWhereRow(VacationType::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
            if (!$vacationType) {
                return redirect()->route('admin.vacation-types.index')->with('error', 'نوع الإجازة غير موجود');
            }
            if ($vacationType->attendancesDepartures()->exists()) {
                return redirect()->route('admin.vacation-types.index')->with('error', 'لا يمكن حذف نوع الإجازة لوجود حركات بصمة مرتبطة به');
            }
            destroy($vacationType);
            return redirect()->route('admin.vacation-types.index')->with('success', 'تم حذف نوع الإجازة بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف نوع الإجازة ' . $e->getMessage());
        }
    }
}
