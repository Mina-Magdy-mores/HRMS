<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BonusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:bonuses,name',
            'status' => 'required|in:0,1',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'اسم المكافأة مطلوب',
            'name.string' => 'اسم المكافأة يجب أن يكون نصاً',
            'name.unique' => 'اسم المكافأة موجود بالفعل',

            'name.max' => 'اسم المكافأة لا يتجاوز 255 حرف',
            'status.required' => 'حالة المكافأة مطلوبة',
            'status.in' => 'الحالة يجب أن تكون مفعل أو معطل',
        ];
    }
}
