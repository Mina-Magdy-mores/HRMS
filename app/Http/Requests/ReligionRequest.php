<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReligionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'status' => 'required|integer|in:0,1',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'اسم الدين مطلوب',
            'name.string' => 'اسم الدين يجب أن يكون نصاً',
            'name.max' => 'اسم الدين لا يمكن أن يتجاوز 255 حرف',
            'status.required' => 'الحالة مطلوبة',
            'status.integer' => 'الحالة يجب أن تكون رقماً',
            'status.in' => 'الحالة يجب أن تكون مفعل او معطل',
        ];
    }
}
