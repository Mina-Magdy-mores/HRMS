<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GovernorateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'country_id' => 'required|integer|exists:countries,id',
            'status' => 'required|integer|in:0,1',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'اسم المحافظة مطلوب',
            'name.string' => 'اسم المحافظة يجب أن يكون نصاً',
            'name.max' => 'اسم المحافظة لا يمكن أن يتجاوز 255 حرف',
            'country_id.required' => 'الدولة مطلوبة',
            'country_id.integer' => 'الدولة يجب أن تكون رقماً',
            'country_id.exists' => 'الدولة المختارة غير موجودة',
            'status.required' => 'الحالة مطلوبة',
            'status.integer' => 'الحالة يجب أن تكون رقماً',
            'status.in' => 'الحالة يجب أن تكون مفعل او معطل',
        ];
    }
}
