<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BonusRequest;
use App\Http\Requests\BonusUpdateRequest;
use App\Models\Bonus;
use Illuminate\Support\Facades\Auth;

class BonusController extends Controller
{
    public function index()
    {
        $company_id = Auth::user()->company_id;
        $bonuses = getColsWhereP(Bonus::class, ['addedBy', 'updatedBy'], ['*'], ['company_id' => $company_id]);
        $bonuses->getCollection()->loadCount('mainSalaryEmployeeBonuses');
        return view('admin.bonus.index', ['bonuses' => $bonuses]);
    }

    public function create()
    {
        return view('admin.bonus.create');
    }

    public function store(BonusRequest $request)
    {
        try {
            $company_id = Auth::user()->company_id;
            $checkIf = getColsWhereRow(Bonus::class, ['id'], ['company_id' => $company_id, 'name' => $request->name]);
            if ($checkIf) {
                return redirect()->back()->with('error', 'المكافأة موجودة بالفعل')->withInput();
            }

            $validated = $request->validated();
            $validated['added_by'] = Auth::id();
            $validated['company_id'] = $company_id;
            insert(Bonus::class, $validated);

            return redirect()->route('admin.bonuses.index')->with('success', 'تم إنشاء المكافأة بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إنشاء المكافأة ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $company_id = Auth::user()->company_id;
        $bonus = getColsWhereRow(Bonus::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
        if (!$bonus) {
            return redirect()->route('admin.bonuses.index')->with('error', 'المكافأة غير موجودة');
        }
        return view('admin.bonus.update', ['bonus' => $bonus]);
    }

    public function update(BonusUpdateRequest $request, $id)
    {
        try {
            $company_id = Auth::user()->company_id;
            $bonus = getColsWhereRow(Bonus::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
            if (!$bonus) {
                return redirect()->route('admin.bonuses.index')->with('error', 'المكافأة غير موجودة');
            }

            $validated = $request->validated();
            $validated['updated_by'] = Auth::id();
            $validated['company_id'] = $company_id;
            update($bonus, $validated);

            return redirect()->route('admin.bonuses.index')->with('success', 'تم تحديث المكافأة بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث المكافأة ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $company_id = Auth::user()->company_id;
            $bonus = getColsWhereRow(Bonus::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
            if (!$bonus) {
                return redirect()->route('admin.bonuses.index')->with('error', 'المكافأة غير موجودة');
            }
            if ($bonus->mainSalaryEmployeeBonuses()->exists()) {
                return redirect()->route('admin.bonuses.index')->with('error', 'لا يمكن حذف هذه المكافأة لارتباطها بمكافآت موظفين');
            }
            destroy($bonus);
            return redirect()->route('admin.bonuses.index')->with('success', 'تم حذف المكافأة بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف المكافأة ' . $e->getMessage());
        }
    }
}
