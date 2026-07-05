<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SalaryGrantTypeUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id');
        return [
            'name' => 'required|string|max:255|unique:salary_grant_types,name,' . $id,
            'status' => 'required|in:0,1',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'اسم المنحة مطلوب',
            'name.string' => 'اسم المنحة يجب أن يكون نصاً',
            'name.unique' => 'اسم المنحة موجود بالفعل',
            'name.max' => 'اسم المنحة لا يتجاوز 255 حرف',
            'status.required' => 'حالة المنحة مطلوبة',
            'status.in' => 'الحالة يجب أن تكون مفعل أو معطل',
        ];
    }
}
