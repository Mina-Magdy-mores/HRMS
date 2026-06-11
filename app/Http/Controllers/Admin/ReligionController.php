<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReligionRequest;
use App\Models\Religion;
use Illuminate\Support\Facades\Auth;

class ReligionController extends Controller
{
    public function index()
    {
        $company_id = Auth::user()->company_id;
        $religions = getColsWhereP(Religion::class, ['addedBy', 'updatedBy'], ['*'], ['company_id' => $company_id]);
        $religions->getCollection()->loadCount('employees');
        return view('admin.religion.index', ['religions' => $religions]);
    }

    public function create()
    {
        return view('admin.religion.create');
    }

    public function store(ReligionRequest $request)
    {
        try {
            $company_id = Auth::user()->company_id;
            $checkIf = getColsWhereRow(Religion::class, ['id'], ['company_id' => $company_id, 'name' => $request->name]);
            if ($checkIf) {
                return redirect()->back()->with('error', 'الدين موجود بالفعل')->withInput();
            }

            $validated = $request->validated();
            $validated['added_by'] = Auth::id();
            $validated['updated_by'] = Auth::id();
            $validated['company_id'] = $company_id;
            insert(Religion::class, $validated);

            return redirect()->route('admin.religions.index')->with('success', 'تم إنشاء الدين بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إنشاء الدين ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $company_id = Auth::user()->company_id;
        $religion = getColsWhereRow(Religion::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
        if (!$religion) {
            return redirect()->route('admin.religions.index')->with('error', 'الدين غير موجود');
        }
        return view('admin.religion.update', ['religion' => $religion]);
    }

    public function update(ReligionRequest $request, $id)
    {
        try {
            $company_id = Auth::user()->company_id;
            $religion = getColsWhereRow(Religion::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
            if (!$religion) {
                return redirect()->route('admin.religions.index')->with('error', 'الدين غير موجود');
            }

            $checkIf = Religion::select('id')
                ->where(['company_id' => $company_id, 'name' => $request->name])
                ->where('id', '!=', $id)
                ->first();
            if ($checkIf) {
                return redirect()->back()->with('error', 'الدين موجود بالفعل')->withInput();
            }

            $validated = $request->validated();
            $validated['updated_by'] = Auth::id();
            $validated['company_id'] = $company_id;
            update($religion, $validated);

            return redirect()->route('admin.religions.index')->with('success', 'تم تحديث الدين بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث الدين ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $company_id = Auth::user()->company_id;
            $religion = getColsWhereRow(Religion::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
            if (!$religion) {
                return redirect()->route('admin.religions.index')->with('error', 'الدين غير موجود');
            }
            if ($religion->employees()->exists()) {
                return redirect()->route('admin.religions.index')->with('error', 'لا يمكن حذف هذه الديانة لوجود موظفين مرتبطة بها');
            }
            destroy($religion);
            return redirect()->route('admin.religions.index')->with('success', 'تم حذف الدين بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف الدين ' . $e->getMessage());
        }
    }
}
