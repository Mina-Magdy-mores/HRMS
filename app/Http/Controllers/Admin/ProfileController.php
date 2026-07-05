<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use App\Models\Admin;
use App\Models\AdminArchive;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * عرض صفحة تعديل الملف الشخصي للمستخدم الحالي
     */
    public function edit()
    {
        $admin = Auth::user();
        return view('admin.profile.edit', compact('admin'));
    }

    /**
     * تحديث بيانات الملف الشخصي للمستخدم الحالي
     */
    public function update(ProfileRequest $request)
    {
        $admin = Auth::user();

        try {
            // أرشفة البيانات القديمة قبل التعديل
            $this->archiveAdmin($admin, 'update');

            $validated = $request->validated();
            $validated['updated_by'] = $admin->id;

            // رفع الصورة
            if ($request->hasFile('image')) {
                if (!empty($admin->image)) {
                    Storage::delete($admin->image);
                }
                $validated['image'] = uploadImage('admins/images', $request->file('image'));
            } else {
                unset($validated['image']);
            }

            // التحقق من كلمة المرور الحالية قبل تغييرها
            if (!empty($validated['password'])) {
                if (empty($validated['current_password']) || !Hash::check($validated['current_password'], $admin->password)) {
                    return redirect()->back()->with('error', 'كلمة المرور الحالية غير صحيحة')->withInput();
                }
                $validated['password'] = Hash::make($validated['password']);
            } else {
                unset($validated['password']);
            }

            unset($validated['current_password']);
            unset($validated['password_confirmation']);

            update($admin, $validated);

            return redirect()->back()->with('success', 'تم تحديث بيانات الملف الشخصي بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ ما، برجاء المحاولة لاحقاً: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * أرشفة بيانات الأدمن قبل التعديل
     */
    private function archiveAdmin(Admin $admin, string $action): void
    {
        AdminArchive::create([
            'admin_id'    => $admin->id,
            'action'      => $action,
            'name'        => $admin->name,
            'email'       => $admin->email,
            'username'    => $admin->username,
            'phone'       => $admin->phone,
            'address'     => $admin->address,
            'birth_date'  => $admin->birth_date,
            'national_id' => $admin->national_id,
            'gender'      => $admin->gender,
            'bio'         => $admin->bio,
            'image'       => $admin->image,
            'status'      => $admin->status,
            'date'        => $admin->date,
            'company_id'  => $admin->company_id,
            'archived_by' => Auth::user()->id,
        ]);
    }
}
