<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PermissionRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('permission_role');
        $company_id = auth()->user()->company_id ?? 1;
        return [
            'name' => 'required|string|max:255|unique:permission_roles,name,' . ($id ? $id : 'NULL') . ',id,company_id,' . $company_id,
            'is_active' => 'required|integer|in:0,1',
            'permissions_main' => 'nullable|array',
            'permissions_main.*' => 'integer|exists:permission_main_menues,id',
            'permissions_sub' => 'nullable|array',
            'permissions_sub.*' => 'integer|exists:permission_sub_menues,id',
            'permissions_action' => 'nullable|array',
            'permissions_action.*' => 'integer|exists:permission_sub_menues_actions,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'اسم الدور مطلوب',
            'name.string' => 'اسم الدور يجب أن يكون نصاً',
            'name.max' => 'اسم الدور لا يتجاوز 255 حرف',
            'name.unique' => 'اسم دور الصلاحية هذا مسجل بالفعل لشركتكم في النظام',
            'is_active.required' => 'حالة التفعيل مطلوبة',
            'is_active.in' => 'حالة التفعيل غير صحيحة',
        ];
    }
}
