<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PermissionSubMenuActionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('permission_sub_menu_action');
        if ($id) {
            return [
                'permission_sub_menu_id' => 'required|integer|exists:permission_sub_menues,id',
                'name' => 'required|string|max:255|unique:permission_sub_menues_actions,name,' . $id . ',id,permission_sub_menu_id,' . $this->permission_sub_menu_id,
                'is_active' => 'required|integer|in:0,1',
            ];
        }

        return [
            'permission_sub_menu_id' => 'required|integer|exists:permission_sub_menues,id',
            'names' => 'required_without:custom_names|array',
            'names.*' => 'string|max:255',
            'custom_names' => 'required_without:names|nullable|string',
            'is_active' => 'required|integer|in:0,1',
        ];
    }

    public function messages(): array
    {
        return [
            'permission_sub_menu_id.required' => 'القائمة الفرعية مطلوبة',
            'permission_sub_menu_id.exists' => 'القائمة الفرعية غير صحيحة',
            'name.required' => 'اسم الحركة مطلوب',
            'name.string' => 'اسم الحركة يجب أن يكون نصاً',
            'name.max' => 'اسم الحركة لا يتجاوز 255 حرف',
            'name.unique' => 'هذه الحركة مسجلة بالفعل لهذه القائمة الفرعية',
            'names.required_without' => 'يجب اختيار حركة واحدة على الأقل أو كتابة حركة مخصصة',
            'custom_names.required_without' => 'يجب اختيار حركة واحدة على الأقل أو كتابة حركة مخصصة',
            'is_active.required' => 'حالة التفعيل مطلوبة',
            'is_active.in' => 'حالة التفعيل غير صحيحة',
        ];
    }
}
