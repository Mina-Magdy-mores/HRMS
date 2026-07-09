<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminProfileRequest;
use App\Models\Admin;
use App\Models\AdminArchive;
use App\Models\PermissionRole;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminProfileController extends Controller
{
    /**
     * عرض قائمة الأدمنية
     */
    public function index()
    {
        $company_id = Auth::user()->company_id;

        $admins = getColsWhereP(Admin::class, ['permissionRole'], ['*'], ['company_id' => $company_id], 'id', 'asc', PAGEINATION_COUNTER);

        return view('admin.adminProfile.index', compact('admins'));
    }

    /**
     * عرض فورم إضافة أدمن جديد
     */
    public function create()
    {
        $company_id = Auth::user()->company_id;
        $roles = get_cols_where(PermissionRole::class, ['id', 'name'], ['is_active' => 1, 'company_id' => $company_id]);
        $employees = get_cols_where(\App\Models\Employee::class, ['id', 'name', 'employee_code', 'email', 'birth_date', 'gender', 'nationality_number', 'work_telephone', 'home_telephone', 'home_address', 'stable_address'], ['company_id' => $company_id]);
        return view('admin.adminProfile.create', compact('roles', 'employees'));
    }

    /**
     * حفظ أدمن جديد
     */
    public function store(AdminProfileRequest $request)
    {
        $company_id = Auth::user()->company_id;

        $checkIfExist = getColsWhereRow(Admin::class, ['id'], ['username' => $request->username, 'company_id' => $company_id]);
        if (!empty($checkIfExist)) {
            return redirect()->back()->with('error', 'اسم المستخدم موجود مسبقاً')->withInput();
        }

        try {
            $validated = $request->validated();
            if ($validated['is_master_admin'] == 1) {
                $validated['permission_role_id'] = null;
            }
            if (empty($validated['is_employee']) || $validated['is_employee'] == 0) {
                $validated['is_employee'] = 0;
                $validated['employee_id'] = null;
            }
            if (!isset($validated['allow_login'])) {
                $validated['allow_login'] = 1;
            }
            $validated['added_by']   = Auth::user()->id;
            $validated['updated_by'] = Auth::user()->id;
            $validated['company_id'] = $company_id;
            $validated['date']       = now()->toDateString();

            // رفع الصورة
            if ($request->hasFile('image')) {
                $validated['image'] = uploadImage('admins/images', $request->file('image'));
            }

            // تشفير كلمة المرور
            if (!empty($validated['password'])) {
                $validated['password'] = Hash::make($validated['password']);
            } else {
                unset($validated['password']);
            }

            unset($validated['password_confirmation']);

            insert(Admin::class, $validated);

            return redirect()->route('admin.admin-profiles.index')->with('success', 'تم إضافة المستخدم بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ ما، برجاء المحاولة لاحقاً: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * عرض فورم تعديل أدمن
     */
    public function edit($id)
    {
        $company_id = Auth::user()->company_id;

        $admin = getColsWhereRow(Admin::class, ['*'], ['id' => $id, 'company_id' => $company_id]);

        if (empty($admin)) {
            return redirect()->route('admin.admin-profiles.index')->with('error', 'هذا المستخدم غير موجود');
        }

        $roles = get_cols_where(PermissionRole::class, ['id', 'name'], ['is_active' => 1, 'company_id' => $company_id]);
        $employees = get_cols_where(\App\Models\Employee::class, ['id', 'name', 'employee_code', 'email', 'birth_date', 'gender', 'nationality_number', 'work_telephone', 'home_telephone', 'home_address', 'stable_address'], ['company_id' => $company_id]);
        return view('admin.adminProfile.update', compact('admin', 'roles', 'employees'));
    }

    /**
     * تحديث بيانات أدمن
     */
    public function update(AdminProfileRequest $request, $id)
    {
        $company_id = Auth::user()->company_id;

        $admin = getColsWhereRow(Admin::class, ['*'], ['id' => $id, 'company_id' => $company_id]);

        if (empty($admin)) {
            return redirect()->route('admin.admin-profiles.index')->with('error', 'هذا المستخدم غير موجود');
        }

        $checkIfExist = Admin::select('id')
            ->where(['company_id' => $company_id, 'username' => $request->username])
            ->where('id', '!=', $id)
            ->first();

        if ($checkIfExist) {
            return redirect()->back()->with('error', 'اسم المستخدم موجود مسبقاً')->withInput();
        }

        try {
            // أرشفة البيانات القديمة قبل التعديل
            $this->archiveAdmin($admin, 'update');

            $validated = $request->validated();
            if ($validated['is_master_admin'] == 1) {
                $validated['permission_role_id'] = null;
            }
            if (empty($validated['is_employee']) || $validated['is_employee'] == 0) {
                $validated['is_employee'] = 0;
                $validated['employee_id'] = null;
            }
            $validated['updated_by'] = Auth::user()->id;

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

            return redirect()->route('admin.admin-profiles.index')->with('success', 'تم تعديل بيانات المستخدم بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ ما، برجاء المحاولة لاحقاً: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * عرض أرشيف أدمن معين
     */
    public function archive($id)
    {
        $company_id = Auth::user()->company_id;

        $admin = getColsWhereRow(Admin::class, ['id', 'name', 'username', 'image'], ['id' => $id, 'company_id' => $company_id]);

        if (empty($admin)) {
            return redirect()->route('admin.admin-profiles.index')->with('error', 'هذا المستخدم غير موجود');
        }

        $archives = \App\Models\AdminArchive::with('archivedBy')
            ->where('admin_id', $id)
            ->orderBy('created_at', 'desc')
            ->paginate(PAGEINATION_COUNTER);

        return view('admin.adminProfile.archive', compact('admin', 'archives'));
    }

    /**
     * حذف أدمن
     */
    public function destroy($id)
    {
        $company_id = Auth::user()->company_id;

        // لا يمكن حذف الأدمن المسجل دخوله
        if ($id == Auth::user()->id) {
            return redirect()->route('admin.admin-profiles.index')->with('error', 'لا يمكن حذف حسابك الشخصي');
        }

        $admin = getColsWhereRow(Admin::class, ['*'], ['id' => $id, 'company_id' => $company_id]);

        if (empty($admin)) {
            return redirect()->route('admin.admin-profiles.index')->with('error', 'هذا المستخدم غير موجود');
        }

        try {
            // أرشفة البيانات القديمة قبل الحذف
            $this->archiveAdmin($admin, 'delete');

            // حذف الصورة من التخزين
            if (!empty($admin->image)) {
                Storage::delete($admin->image);
            }

            destroy($admin);

            return redirect()->route('admin.admin-profiles.index')->with('success', 'تم حذف المستخدم بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ ما، برجاء المحاولة لاحقاً: ' . $e->getMessage());
        }
    }

    /**
     * أرشفة بيانات الأدمن قبل التعديل أو الحذف
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
