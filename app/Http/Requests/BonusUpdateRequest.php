<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BonusUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' =>['required','string','max:255',
            Rule::unique('bonuses', 'name')->ignore($this->id),
            ],
            'status' => 'required|in:0,1',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'اسم المكافأة مطلوب',
            'name.string' => 'اسم المكافأة يجب أن يكون نصاً',
            'name.max' => 'اسم المكافأة لا يتجاوز 255 حرف',
            'name.unique' => 'اسم المكافأة موجود بالفعل',
            'status.required' => 'حالة المكافأة مطلوبة',
            'status.in' => 'الحالة يجب أن تكون مفعل أو معطل',
        ];
    }
}
