<?php

namespace App\Http\Controllers;

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
        return view('admin.shifts-types.index', compact('shiftsTypes'));
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

    /**
     * Display the specified resource.
     */
    public function show(ShiftsType $shiftsType)
    {
        //
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
        try {
            destroy($shiftsType);
            return redirect()->route('admin.shifts-types.index')->with('success', 'تم حذف النوع بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطا ما برجاء المحاوله لاحقا ' . $e->getMessage());
        }
    }
}
