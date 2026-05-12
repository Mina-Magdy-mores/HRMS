<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin_panel_settingRequest;
use App\Models\Admin_panel_setting;
use Illuminate\Http\Request;

class Admin_panel_settingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $company_id = auth()->user()->company_id;
        $general_settings = Admin_panel_setting::where('company_id', $company_id)->first();
        return view('admin.admin_panel_setting.index', compact('general_settings'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Admin_panel_settingRequest $request, Admin_panel_setting $admin_panel_setting)
    {
        try {
            $validated = $request->validated();
            $validated['updated_by'] = auth()->user()->id;
            $admin_panel_setting->update($validated);
            return redirect()->route('admin.general-settings')->with('success', 'تم التحديث بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'عفوا، حدث خطأ ما، يرجى المحاولة مرة أخرى')->withInput();
        }
    }
}
