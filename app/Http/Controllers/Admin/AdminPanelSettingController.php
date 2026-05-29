<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminPanelSettingRequest;
use App\Models\AdminPanelSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminPanelSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $company_id = Auth::user()->company_id;
        // $general_settings = AdminPanelSetting::where('company_id', $company_id)->first();
        $general_settings = getColsWhere(AdminPanelSetting::class, [], ['*'], ['company_id' => $company_id], 'id', 'desc');
        return view('admin.AdminPanelSetting.index', compact('general_settings'));
    }
    /*
     * Update the specified resource in storage.
     */
    public function update(AdminPanelSettingRequest $request, AdminPanelSetting $adminPanelSetting)
    {
        try {
            $validated = $request->validated();
            $validated['updated_by'] = auth()->user()->id;
            $adminPanelSetting->update($validated);
            return redirect()->route('admin.general-settings')->with('success', 'تم التحديث بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'عفوا، حدث خطأ ما، يرجى المحاولة مرة أخرى')->withInput();
        }
    }
}
