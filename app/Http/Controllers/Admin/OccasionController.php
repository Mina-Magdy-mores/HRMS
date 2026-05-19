<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\OccasionRequest;
use App\Models\Occasion;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class OccasionController extends Controller
{
    public function index()
    {
        $company_id = Auth::user()->company_id;
        $occasions = getColsWhereP(Occasion::class, [], ['*'], ['company_id' => $company_id]);
        return view('admin.occasion.index', ['occasions' => $occasions]);
    }

    public function create()
    {
        return view('admin.occasion.create');
    }

    public function store(OccasionRequest $request)
    {
        try {
            $company_id = Auth::user()->company_id;
            $checkIf = getColsWhereRow(Occasion::class, ['id'], ['company_id' => $company_id, 'name' => $request->name]);
            if ($checkIf) {
                return redirect()->back()->with('error', 'المناسبة موجودة بالفعل')->withInput();
            }

            $validated = $request->validated();
            $validated['added_by'] = Auth::id();
            $validated['updated_by'] = Auth::id();
            $validated['company_id'] = $company_id;
            insert(Occasion::class, $validated);

            return redirect()->route('admin.occasions.index')->with('success', 'تم إنشاء المناسبة بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إنشاء المناسبة ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $company_id = Auth::user()->company_id;
        $occasion = getColsWhereRow(Occasion::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
        if (!$occasion) {
            return redirect()->route('admin.occasions.index')->with('error', 'المناسبة غير موجودة');
        }

        return view('admin.occasion.update', ['occasion' => $occasion]);
    }

    public function update(OccasionRequest $request, $id)
    {
        try {
            $company_id = Auth::user()->company_id;
            $occasion = getColsWhereRow(Occasion::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
            if (!$occasion) {
                return redirect()->route('admin.occasions.index')->with('error', 'المناسبة غير موجودة');
            }

            $checkIf = Occasion::select('id')
                ->where(['company_id' => $company_id, 'name' => $request->name])
                ->where('id', '!=', $id)
                ->first();
            if ($checkIf) {
                return redirect()->back()->with('error', 'المناسبة موجودة بالفعل')->withInput();
            }

            $validated = $request->validated();
            $validated['updated_by'] = Auth::id();
            $validated['company_id'] = $company_id;
            update($occasion, $validated);

            return redirect()->route('admin.occasions.index')->with('success', 'تم تحديث المناسبة بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث المناسبة ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $company_id = Auth::user()->company_id;
            $occasion = getColsWhereRow(Occasion::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
            if (!$occasion) {
                return redirect()->route('admin.occasions.index')->with('error', 'المناسبة غير موجودة');
            }
            destroy($occasion);
            return redirect()->route('admin.occasions.index')->with('success', 'تم حذف المناسبة بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف المناسبة ' . $e->getMessage());
        }
    }
}
