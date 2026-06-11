<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\NationalityRequest;
use App\Models\Nationality;
use Illuminate\Support\Facades\Auth;

class NationalityController extends Controller
{
    public function index()
    {
        $company_id = Auth::user()->company_id;
        $nationalities = getColsWhereP(Nationality::class, ['addedBy','updatedBy'], ['*'], ['company_id' => $company_id],'id','desc',PAGEINATION_COUNTER);
        $nationalities->getCollection()->loadCount('employees');
        return view('admin.nationality.index', ['nationalities' => $nationalities]);
    }

    public function create()
    {
        return view('admin.nationality.create');
    }

    public function store(NationalityRequest $request)
    {
        try {
            $company_id = Auth::user()->company_id;
            $checkIf = getColsWhereRow(Nationality::class, ['id'], ['company_id' => $company_id, 'name' => $request->name]);
            if ($checkIf) {
                return redirect()->back()->with('error', 'الجنسية موجودة بالفعل')->withInput();
            }

            $validated = $request->validated();
            $validated['added_by'] = Auth::id();
            $validated['updated_by'] = Auth::id();
            $validated['company_id'] = $company_id;
            insert(Nationality::class, $validated);

            return redirect()->route('admin.nationalities.index')->with('success', 'تم إنشاء الجنسية بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إنشاء الجنسية ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $company_id = Auth::user()->company_id;
        $nationality = getColsWhereRow(Nationality::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
        if (!$nationality) {
            return redirect()->route('admin.nationalities.index')->with('error', 'الجنسية غير موجودة');
        }
        return view('admin.nationality.update', ['nationality' => $nationality]);
    }

    public function update(NationalityRequest $request, $id)
    {
        try {
            $company_id = Auth::user()->company_id;
            $nationality = getColsWhereRow(Nationality::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
            if (!$nationality) {
                return redirect()->route('admin.nationalities.index')->with('error', 'الجنسية غير موجودة');
            }

            $checkIf = Nationality::select('id')
                ->where(['company_id' => $company_id, 'name' => $request->name])
                ->where('id', '!=', $id)
                ->first();
            if ($checkIf) {
                return redirect()->back()->with('error', 'الجنسية موجودة بالفعل')->withInput();
            }

            $validated = $request->validated();
            $validated['updated_by'] = Auth::id();
            $validated['company_id'] = $company_id;
            update($nationality, $validated);

            return redirect()->route('admin.nationalities.index')->with('success', 'تم تحديث الجنسية بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث الجنسية ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $company_id = Auth::user()->company_id;
            $nationality = getColsWhereRow(Nationality::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
            if (!$nationality) {
                return redirect()->route('admin.nationalities.index')->with('error', 'الجنسية غير موجودة');
            }
            if ($nationality->employees()->exists()) {
                return redirect()->route('admin.nationalities.index')->with('error', 'لا يمكن حذف هذه الجنسية لوجود موظفين مرتبطة بها');
            }
            destroy($nationality);
            return redirect()->route('admin.nationalities.index')->with('success', 'تم حذف الجنسية بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف الجنسية ' . $e->getMessage());
        }
    }
}
