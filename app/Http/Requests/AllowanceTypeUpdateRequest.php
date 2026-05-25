<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AllowanceTypeUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('allowance_types', 'name')->ignore($this->id),
            ],
            'status' => 'required|integer|in:0,1',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'اسم النوع مطلوب',
            'name.string' => 'اسم النوع يجب أن يكون نصاً',
            'name.max' => 'اسم النوع لا يمكن أن يتجاوز 255 حرف',
            'name.unique' => 'اسم المكافأة موجود بالفعل',
            'status.required' => 'الحالة مطلوبة',
            'status.integer' => 'الحالة يجب أن تكون مفعل او معطل',
            'status.in' => 'الحالة يجب أن تكون مفعل او معطل',
        ];
    }
}
