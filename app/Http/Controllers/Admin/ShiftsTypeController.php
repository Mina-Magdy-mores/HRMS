<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShiftTypeRequest;
use App\Models\ShiftsType;
use Auth;
use Illuminate\Http\Request;

class ShiftsTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $company_id = Auth::user()->company_id;
        $shiftsTypes = getColsWhereP(ShiftsType::class, ['createdBy', 'updatedBy'], ['*'], ['company_id' => $company_id], 'id', 'asc', PAGEINATION_COUNTER);
        $shiftsTypes->getCollection()->loadCount('employees');
        return view('admin.shifts-types.index', ['shiftsTypes' => $shiftsTypes]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.shifts-types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ShiftTypeRequest $request)
    {
        try {

            $validated = $request->validated();
    
            $validated['company_id'] = Auth::user()->company_id;
            unset($validated['status']);
            $checkIfExist = getColsWhereRow(ShiftsType::class, ['id'], $validated);
            if (!empty($checkIfExist)) {
                return redirect()->back()->with('error', 'هذا النوع موجود بالفعل')->withInput();
            }
            $validated['status'] = $request->status;
            $validated['created_by'] = Auth::user()->id;
            $validated['updated_by'] = Auth::user()->id;
            insert(ShiftsType::class, $validated);
            return redirect()->route('admin.shifts-types.index')->with('success', 'تم اضافة النوع بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطا ما برجاء المحاوله لاحقا '  . $e->getMessage())->withInput();
        }
    }

    public function search(Request $request)
    {
        if ($request->ajax()) {
            $company_id = Auth::user()->company_id;
            $type = $request->type;
            $start_time = $request->start_time;
            $end_time = $request->end_time;
            if (empty($type)) {
                $field1 = "status";
                $operator1 = ">=";
                $value1 = 0;
            } else {
                $field1 = "type";
                $operator1 = "=";
                $value1 = $type;
            }
            if (empty($start_time)) {
                $field2 = "status";
                $operator2 = ">=";
                $value2 = 0;
            } else {
                $field2 = "start_time";
                $operator2 = ">=";
                $value2 = $start_time;
            }
            if (empty($end_time)) {
                $field3 = "status";
                $operator3 = ">=";
                $value3 = 0;
            }else {
                $field3 = "end_time";
                $operator3 = "<=";
                $value3 = $end_time;
            }
            $where = [
                [$field1, $operator1, $value1],
                [$field2, $operator2, $value2],
                [$field3, $operator3, $value3],
            ];
            $shiftsTypes = getColsWhereP(ShiftsType::class, ['createdBy', 'updatedBy'], ['*'], $where, 'id', 'asc', PAGEINATION_COUNTER);
            $shiftsTypes->getCollection()->loadCount('employees');
            return view('admin.shifts-types.ajaxSearch', compact('shiftsTypes'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $campany_id = Auth::user()->company_id;
        $shiftsType = getColsWhereRow(ShiftsType::class, ['*'], ['id' => $id, 'company_id' => $campany_id]);
        if (empty($shiftsType)) {
            return redirect()->route('admin.shifts-types.index')->with('error', 'هذا النوع غير موجود');
        }
        return view('admin.shifts-types.update', compact('shiftsType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ShiftTypeRequest $request, $id)
    {
        $campany_id = Auth::user()->company_id;
        $shiftsType = getColsWhereRow(ShiftsType::class, ['*'], ['id' => $id, 'company_id' => $campany_id]);
        if (empty($shiftsType)) {
            return redirect()->route('admin.shifts-types.index')->with('error', 'هذا النوع غير موجود');
        }
        try {
            $validated = $request->validated();
            $validated['company_id'] = Auth::user()->company_id;
            unset($validated['status']);
            $checkIfExist = getColsWhereRow(ShiftsType::class, ['id'], $validated);
            if (!empty($checkIfExist) && $checkIfExist->id != $shiftsType->id) {
                return redirect()->back()->with('error', 'هذا النوع موجود بالفعل')->withInput();
            }
            $validated['status'] = $request->status;
            $validated['updated_by'] = Auth::user()->id;
            update($shiftsType, $validated);
            return redirect()->route('admin.shifts-types.index')->with('success', 'تم تحديث النوع بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطا ما برجاء المحاوله لاحقا ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $campany_id = Auth::user()->company_id;
        $shiftsType = getColsWhereRow(ShiftsType::class, ['*'], ['id' => $id, 'company_id' => $campany_id]);
        if (empty($shiftsType)) {
            return redirect()->route('admin.shifts-types.index')->with('error', 'هذا النوع غير موجود');
        }
        if ($shiftsType->employees()->exists()) {
            return redirect()->route('admin.shifts-types.index')->with('error', 'لا يمكن حذف هذا الشفت لوجود موظفين مرتبطة به');
        }
        try {
            destroy($shiftsType);
            return redirect()->route('admin.shifts-types.index')->with('success', 'تم حذف النوع بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطا ما برجاء المحاوله لاحقا ' . $e->getMessage());
        }
    }
}
