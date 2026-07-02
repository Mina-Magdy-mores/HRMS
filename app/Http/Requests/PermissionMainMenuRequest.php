<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PermissionMainMenuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('permission_main_menu');
        return [
            'name' => 'required|string|max:255|unique:permission_main_menues,name,' . ($id ? $id : 'NULL') . ',id',
            'is_active' => 'required|integer|in:0,1',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'اسم القائمة الرئيسية مطلوب',
            'name.string' => 'اسم القائمة الرئيسية يجب أن يكون نصاً',
            'name.max' => 'اسم القائمة الرئيسية لا يتجاوز 255 حرف',
            'name.unique' => 'اسم القائمة الرئيسية مسجل بالفعل في النظام',
            'is_active.required' => 'حالة التفعيل مطلوبة',
            'is_active.in' => 'حالة التفعيل غير صحيحة',
        ];
    }
}
