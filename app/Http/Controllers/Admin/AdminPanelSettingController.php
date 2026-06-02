<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminPanelSettingRequest;
use App\Models\AdminPanelSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
            if ($request->hasFile('image')) {
                if (!empty($adminPanelSetting->image)) {
                    Storage::delete($adminPanelSetting->image);
                }

                $validated['image'] = uploadImage('AdminPanelSetting/images', $request->file('image'));
            }
            $adminPanelSetting->update($validated);
            return redirect()->route('admin.general-settings')->with('success', 'تم التحديث بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'عفوا، حدث خطأ ما، يرجى المحاولة مرة أخرى')->withInput();
        }
    }
    public function downloadImage($id)
    {

        $company_id = Auth::user()->company_id;
        $adminPanelSetting = getColsWhereRow(AdminPanelSetting::class, ['image'], ['id' => $id, 'company_id' => $company_id]);
        if (!$adminPanelSetting) {
            return redirect()->route('admin.general-settings')->with(['error' => 'الضبط غير موجود']);
        }

        if ($adminPanelSetting->image) {
            return response()->download(storage_path('app/public/' . $adminPanelSetting->image));
        } else {
            return redirect()->route('admin.general-settings')->with(['error' => 'الملف غير موجود']);
        }
    }
}
