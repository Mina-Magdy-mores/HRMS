<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AdminProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $adminId = $this->route('id');

        $rules = [
            'name'             => 'required|string|max:255',
            'email'            => 'nullable|email|max:255|unique:admins,email,' . $adminId,
            'username'         => 'required|string|max:255|unique:admins,username,' . $adminId,
            'phone'            => 'nullable|string|max:20',
            'address'          => 'nullable|string|max:500',
            'birth_date'       => 'nullable|date',
            'national_id'      => 'nullable|string|max:20|unique:admins,national_id,' . $adminId,
            'gender'           => 'nullable|in:male,female',
            'bio'              => 'nullable|string|max:1000',
            'image'            => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status'           => 'required|integer|in:0,1',
            'is_master_admin'  => 'required|integer|in:0,1',
            'permission_role_id'=> 'nullable|exists:permission_roles,id',
            'is_employee'      => 'nullable|integer|in:0,1',
            'employee_id'      => 'nullable|exists:employees,id',
            'allow_login'      => 'nullable|integer|in:0,1',
        ];

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $rules['current_password'] = 'nullable|string|required_with:password';
            $rules['password']         = 'nullable|string|min:8|confirmed';
        } else {
            // عند الإضافة (store)
            $rules['password']         = 'required|string|min:8|confirmed';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required'           => 'الاسم مطلوب',
            'name.string'             => 'الاسم يجب أن يكون نصاً',
            'name.max'                => 'الاسم لا يزيد عن 255 حرف',
            'email.email'             => 'البريد الإلكتروني غير صحيح',
            'email.max'               => 'البريد الإلكتروني لا يزيد عن 255 حرف',
            'email.unique'            => 'البريد الإلكتروني مسجل مسبقاً',
            'username.required'       => 'اسم المستخدم مطلوب',
            'username.unique'         => 'اسم المستخدم مسجل مسبقاً',
            'phone.max'               => 'رقم الهاتف لا يزيد عن 20 حرف',
            'address.max'             => 'العنوان لا يزيد عن 500 حرف',
            'birth_date.date'         => 'تاريخ الميلاد غير صحيح',
            'national_id.max'         => 'الرقم القومي لا يزيد عن 20 حرف',
            'national_id.unique'      => 'الرقم القومي مسجل مسبقاً',
            'gender.in'               => 'الجنس يجب أن يكون ذكر أو أنثى',
            'bio.max'                 => 'نبذة شخصية لا تتجاوز 1000 حرف',
            'image.image'             => 'يجب أن يكون ملف صورة',
            'image.mimes'             => 'نوع الصورة يجب أن يكون jpeg أو png أو jpg أو gif',
            'image.max'               => 'حجم الصورة لا يزيد عن 2 ميجا',
            'status.required'         => 'الحالة مطلوبة',
            'status.in'               => 'الحالة يجب أن تكون مفعل أو معطل',
            'current_password.required_with' => 'كلمة المرور الحالية مطلوبة عند تغيير كلمة المرور',
            'password.min'            => 'كلمة المرور لا تقل عن 8 أحرف',
            'password.confirmed'      => 'تأكيد كلمة المرور غير متطابق',
            'is_master_admin.required' => 'تحديد نوع الحساب (مدير رئيسي) مطلوب',
            'is_master_admin.in'       => 'قيمة نوع الحساب غير صحيحة',
            'permission_role_id.exists'=> 'دور الصلاحية المختار غير موجود في سجلاتنا',
            'allow_login.in'           => 'قيمة سماحية دخول النظام غير صحيحة',
        ];
    }
}
