<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\QualificationRequest;
use App\Models\Qualification;
use Illuminate\Support\Facades\Auth;

class QualificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $company_id = Auth::user()->company_id;
        $qualifications = getColsWhereP(Qualification::class, ['addedBy', 'updatedBy'], ['*'], ['company_id' => $company_id]);
        return view('admin.qualification.index', ['qualifications' => $qualifications]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.qualification.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(QualificationRequest $request)
    {
        try {
            $company_id = Auth::user()->company_id;
            $checkIf = getColsWhereRow(Qualification::class, ['id'], ['company_id' => $company_id, 'name' => $request->name]);
            if ($checkIf) {
                return redirect()->back()->with('error', 'المؤهل موجود بالفعل')->withInput();
            }

            $validated = $request->validated();
            $validated['added_by'] = Auth::id();
            $validated['updated_by'] = Auth::id();
            $validated['company_id'] = $company_id;
            insert(Qualification::class, $validated);

            return redirect()->route('admin.qualifications.index')->with('success', 'تم إنشاء المؤهل بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إنشاء المؤهل ' . $e->getMessage())->withInput();
        }
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $company_id = Auth::user()->company_id;
        $qualification = getColsWhereRow(Qualification::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
        if (!$qualification) {
            return redirect()->route('admin.qualifications.index')->with('error', 'المؤهل غير موجود');
        }
        return view('admin.qualification.update', ['qualification' => $qualification]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(QualificationRequest $request, $id)
    {
        try {
            $company_id = Auth::user()->company_id;
            $qualification = getColsWhereRow(Qualification::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
            if (!$qualification) {
                return redirect()->route('admin.qualifications.index')->with('error', 'المؤهل غير موجود');
            }

            $checkIf = Qualification::select('id')
                ->where(['company_id' => $company_id, 'name' => $request->name])
                ->where('id', '!=', $id)
                ->first();
            if ($checkIf) {
                return redirect()->back()->with('error', 'المؤهل موجود بالفعل')->withInput();
            }

            $validated = $request->validated();
            $validated['updated_by'] = Auth::id();
            $validated['company_id'] = $company_id;
            update($qualification, $validated);

            return redirect()->route('admin.qualifications.index')->with('success', 'تم تحديث المؤهل بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث المؤهل ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $company_id = Auth::user()->company_id;
            $qualification = getColsWhereRow(Qualification::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
            if (!$qualification) {
                return redirect()->route('admin.qualifications.index')->with('error', 'المؤهل غير موجود');
            }
            destroy($qualification);
            return redirect()->route('admin.qualifications.index')->with('success', 'تم حذف المؤهل بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف المؤهل ' . $e->getMessage());
        }
    }
}
