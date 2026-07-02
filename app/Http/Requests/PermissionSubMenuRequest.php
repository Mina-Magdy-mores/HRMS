<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PermissionSubMenuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('permission_sub_menu');
        return [
            'permission_main_menu_id' => 'required|integer|exists:permission_main_menues,id',
            'name' => 'required|string|max:255|unique:permission_sub_menues,name,' . ($id ? $id : 'NULL') . ',id',
            'is_active' => 'required|integer|in:0,1',
        ];
    }

    public function messages(): array
    {
        return [
            'permission_main_menu_id.required' => 'القائمة الرئيسية مطلوبة',
            'permission_main_menu_id.exists' => 'القائمة الرئيسية غير صحيحة',
            'name.required' => 'اسم القائمة الفرعية مطلوب',
            'name.string' => 'اسم القائمة الفرعية يجب أن يكون نصاً',
            'name.max' => 'اسم القائمة الفرعية لا يتجاوز 255 حرف',
            'name.unique' => 'اسم القائمة الفرعية مسجل بالفعل في النظام',
            'is_active.required' => 'حالة التفعيل مطلوبة',
            'is_active.in' => 'حالة التفعيل غير صحيحة',
        ];
    }
}
